<?php
/**
 * Server-Side Calculator (AJAX Fallback)
 *
 * Three modes: turnkey (full import cost), customs (duty only),
 * ownership (annual running cost).
 *
 * @package CarFinance
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register AJAX handlers for the calculator.
 */
function cf_calculator_init() {
    add_action( 'wp_ajax_cf_calculate', 'cf_ajax_calculate' );
    add_action( 'wp_ajax_nopriv_cf_calculate', 'cf_ajax_calculate' );
}
add_action( 'init', 'cf_calculator_init' );

/**
 * Main calculator AJAX handler.
 *
 * Reads mode and params from POST, dispatches to the appropriate
 * calculation function, and returns a JSON breakdown.
 */
function cf_ajax_calculate() {
    check_ajax_referer( 'cf_catalog_nonce', 'nonce' );

    $mode        = sanitize_text_field( wp_unslash( $_POST['mode'] ?? 'turnkey' ) );
    $price       = floatval( $_POST['price'] ?? 0 );
    $currency    = sanitize_text_field( wp_unslash( $_POST['currency'] ?? 'USD' ) );
    $year        = absint( $_POST['year'] ?? 0 );
    $engine_cc   = absint( $_POST['engine_cc'] ?? 0 );
    $engine_type = sanitize_text_field( wp_unslash( $_POST['engine_type'] ?? 'petrol' ) );
    $country     = sanitize_text_field( wp_unslash( $_POST['country'] ?? '' ) );

    if ( $price <= 0 ) {
        wp_send_json_error( [ 'message' => 'Укажите стоимость автомобиля.' ] );
    }

    $params = [
        'price'       => $price,
        'currency'    => strtoupper( $currency ),
        'year'        => $year,
        'engine_cc'   => $engine_cc,
        'engine_type' => $engine_type,
        'country'     => $country,
    ];

    switch ( $mode ) {
        case 'customs':
            $price_rub     = cf_convert_to_rub( $price, $params['currency'] );
            $price_eur     = cf_convert_to_eur( $price, $params['currency'] );
            $age_category  = cf_get_age_category( $year );
            $customs_duty  = cf_calc_customs_duty( $price_eur, $engine_cc, $age_category );
            $util_fee      = cf_calc_util_fee( $engine_cc, $age_category, true );

            wp_send_json_success( [
                'mode'           => 'customs',
                'price_rub'      => round( $price_rub ),
                'age_category'   => $age_category,
                'customs_duty'   => round( $customs_duty ),
                'util_fee'       => round( $util_fee ),
                'sbkts'          => 150000,
                'epts'           => 600,
                'total'          => round( $customs_duty + $util_fee + 150000 + 600 ),
            ] );
            break;

        case 'ownership':
            $result = cf_calc_ownership( $params );
            wp_send_json_success( $result );
            break;

        case 'turnkey':
        default:
            $result = cf_calc_turnkey( $params );
            wp_send_json_success( $result );
            break;
    }
}

/**
 * Get default exchange rates, optionally overridden by ACF options.
 *
 * @return array Associative array of currency => rate to RUB.
 */
function cf_get_exchange_rates() {
    $defaults = [
        'USD' => 92,
        'EUR' => 100,
        'KRW' => 0.069,
        'JPY' => 0.62,
        'CNY' => 12.7,
        'AED' => 25,
        'RUB' => 1,
    ];

    if ( function_exists( 'get_field' ) ) {
        $currencies = [ 'USD', 'EUR', 'KRW', 'JPY', 'CNY', 'AED' ];
        foreach ( $currencies as $cur ) {
            $key = 'cf_rate_' . strtolower( $cur );
            $val = get_field( $key, 'option' );
            if ( ! empty( $val ) && floatval( $val ) > 0 ) {
                $defaults[ $cur ] = floatval( $val );
            }
        }
    }

    return $defaults;
}

/**
 * Convert a price to RUB.
 *
 * @param float  $price    Price amount.
 * @param string $currency Currency code.
 * @return float Price in RUB.
 */
function cf_convert_to_rub( $price, $currency ) {
    $rates = cf_get_exchange_rates();
    $rate  = $rates[ $currency ] ?? 1;
    return $price * $rate;
}

/**
 * Convert a price to EUR.
 *
 * @param float  $price    Price amount.
 * @param string $currency Currency code.
 * @return float Price in EUR.
 */
function cf_convert_to_eur( $price, $currency ) {
    if ( $currency === 'EUR' ) {
        return $price;
    }

    $rates    = cf_get_exchange_rates();
    $rate_cur = $rates[ $currency ] ?? 1;
    $rate_eur = $rates['EUR'] ?? 100;

    // Convert to RUB, then to EUR.
    return ( $price * $rate_cur ) / $rate_eur;
}

/**
 * Determine age category from year of manufacture.
 *
 * @param int $year Year of manufacture.
 * @return string '0-3', '3-5', or '5+'
 */
function cf_get_age_category( $year ) {
    if ( $year <= 0 ) {
        return '5+';
    }

    $current_year = (int) gmdate( 'Y' );
    $age          = $current_year - $year;

    if ( $age < 3 ) {
        return '0-3';
    } elseif ( $age < 5 ) {
        return '3-5';
    }

    return '5+';
}

/**
 * Calculate customs duty.
 *
 * @param float  $price_eur    Car price in EUR.
 * @param int    $engine_cc    Engine displacement in cc.
 * @param string $age_category '0-3', '3-5', or '5+'.
 * @return float Customs duty in RUB.
 */
function cf_calc_customs_duty( $price_eur, $engine_cc, $age_category ) {
    $rates = cf_get_exchange_rates();
    $eur_rate = $rates['EUR'] ?? 100;

    $duty_eur = 0;

    switch ( $age_category ) {
        case '0-3':
            // Percentage of price OR per-cc rate, whichever is higher.
            $percentage_rate = 0;
            $per_cc_rate     = 0;

            if ( $price_eur <= 8500 ) {
                $percentage_rate = 0.54;
                $per_cc_rate     = 2.5;
            } elseif ( $price_eur <= 16700 ) {
                $percentage_rate = 0.48;
                $per_cc_rate     = 3.5;
            } elseif ( $price_eur <= 42300 ) {
                $percentage_rate = 0.48;
                $per_cc_rate     = 5.5;
            } elseif ( $price_eur <= 84500 ) {
                $percentage_rate = 0.48;
                $per_cc_rate     = 7.5;
            } elseif ( $price_eur <= 169000 ) {
                $percentage_rate = 0.48;
                $per_cc_rate     = 15;
            } else {
                $percentage_rate = 0.48;
                $per_cc_rate     = 20;
            }

            $by_percent = $price_eur * $percentage_rate;
            $by_cc      = $engine_cc * $per_cc_rate;
            $duty_eur   = max( $by_percent, $by_cc );
            break;

        case '3-5':
            // Per-cc rate only.
            if ( $engine_cc <= 1000 ) {
                $duty_eur = $engine_cc * 1.5;
            } elseif ( $engine_cc <= 1500 ) {
                $duty_eur = $engine_cc * 1.7;
            } elseif ( $engine_cc <= 1800 ) {
                $duty_eur = $engine_cc * 2.5;
            } elseif ( $engine_cc <= 2300 ) {
                $duty_eur = $engine_cc * 2.7;
            } elseif ( $engine_cc <= 3000 ) {
                $duty_eur = $engine_cc * 3.0;
            } else {
                $duty_eur = $engine_cc * 3.6;
            }
            break;

        case '5+':
            // Per-cc rate only.
            if ( $engine_cc <= 1000 ) {
                $duty_eur = $engine_cc * 3.0;
            } elseif ( $engine_cc <= 1500 ) {
                $duty_eur = $engine_cc * 3.2;
            } elseif ( $engine_cc <= 1800 ) {
                $duty_eur = $engine_cc * 3.5;
            } elseif ( $engine_cc <= 2300 ) {
                $duty_eur = $engine_cc * 4.8;
            } elseif ( $engine_cc <= 3000 ) {
                $duty_eur = $engine_cc * 5.0;
            } else {
                $duty_eur = $engine_cc * 5.7;
            }
            break;
    }

    // Convert duty from EUR to RUB.
    return $duty_eur * $eur_rate;
}

/**
 * Calculate utilization fee.
 *
 * @param int    $engine_cc    Engine displacement in cc.
 * @param string $age_category '0-3', '3-5', or '5+'.
 * @param bool   $is_individual True for individual, false for legal entity.
 * @return float Utilization fee in RUB.
 */
function cf_calc_util_fee( $engine_cc, $age_category, $is_individual = true ) {
    $base_rate = $is_individual ? 20000 : 150000;

    if ( $is_individual ) {
        // Coefficients for individuals.
        if ( $age_category === '0-3' ) {
            // New cars, individual.
            if ( $engine_cc <= 1000 ) {
                $coeff = 0.17;
            } elseif ( $engine_cc <= 2000 ) {
                $coeff = 0.17;
            } elseif ( $engine_cc <= 3000 ) {
                $coeff = 0.17;
            } else {
                $coeff = 48.5;
            }
        } else {
            // 3-5 or 5+ years, individual.
            if ( $engine_cc <= 1000 ) {
                $coeff = 0.26;
            } elseif ( $engine_cc <= 2000 ) {
                $coeff = 0.26;
            } elseif ( $engine_cc <= 3000 ) {
                $coeff = 0.26;
            } else {
                $coeff = 48.5;
            }
        }
    } else {
        // Coefficients for legal entities.
        if ( $age_category === '0-3' ) {
            if ( $engine_cc <= 1000 ) {
                $coeff = 1.42;
            } elseif ( $engine_cc <= 2000 ) {
                $coeff = 5.5;
            } elseif ( $engine_cc <= 3000 ) {
                $coeff = 7.2;
            } elseif ( $engine_cc <= 3500 ) {
                $coeff = 11.29;
            } else {
                $coeff = 12.78;
            }
        } else {
            if ( $engine_cc <= 1000 ) {
                $coeff = 5.7;
            } elseif ( $engine_cc <= 2000 ) {
                $coeff = 13.5;
            } elseif ( $engine_cc <= 3000 ) {
                $coeff = 20.3;
            } elseif ( $engine_cc <= 3500 ) {
                $coeff = 28.12;
            } else {
                $coeff = 35.01;
            }
        }
    }

    return $base_rate * $coeff;
}

/**
 * Calculate full turnkey import cost.
 *
 * @param array $params Calculation parameters.
 * @return array Breakdown with all components and total.
 */
function cf_calc_turnkey( $params ) {
    $price       = floatval( $params['price'] );
    $currency    = $params['currency'];
    $year        = absint( $params['year'] );
    $engine_cc   = absint( $params['engine_cc'] );
    $engine_type = $params['engine_type'];
    $country     = $params['country'];

    $price_rub    = cf_convert_to_rub( $price, $currency );
    $price_eur    = cf_convert_to_eur( $price, $currency );
    $age_category = cf_get_age_category( $year );

    // Customs duty (electric vehicles may have exemptions, but we calculate standard).
    $customs_duty = cf_calc_customs_duty( $price_eur, $engine_cc, $age_category );

    // Utilization fee.
    $util_fee = cf_calc_util_fee( $engine_cc, $age_category, true );

    // Fixed fees.
    $sbkts  = 150000;  // Certification (СБКТС).
    $epts   = 600;     // Electronic passport (ЭПТС).
    $broker = 25000;   // Customs broker.

    // Freight — from ACF options or defaults per country.
    $freight_defaults = [
        'korea' => 120000,
        'japan' => 100000,
        'china' => 80000,
        'usa'   => 250000,
        'uae'   => 200000,
    ];

    $freight = $freight_defaults[ $country ] ?? 120000;

    if ( function_exists( 'get_field' ) ) {
        $acf_freight = get_field( 'cf_freight_' . $country, 'option' );
        if ( ! empty( $acf_freight ) && absint( $acf_freight ) > 0 ) {
            $freight = absint( $acf_freight );
        }
    }

    // Commission.
    $commission = 150000;

    if ( function_exists( 'get_field' ) ) {
        $acf_commission = get_field( 'cf_commission', 'option' );
        if ( ! empty( $acf_commission ) && absint( $acf_commission ) > 0 ) {
            $commission = absint( $acf_commission );
        }
    }

    // Total.
    $total = $price_rub + $customs_duty + $util_fee + $sbkts + $epts + $broker + $freight + $commission;

    return [
        'mode'          => 'turnkey',
        'price_rub'     => round( $price_rub ),
        'age_category'  => $age_category,
        'customs_duty'  => round( $customs_duty ),
        'util_fee'      => round( $util_fee ),
        'sbkts'         => $sbkts,
        'epts'          => $epts,
        'broker'        => $broker,
        'freight'       => round( $freight ),
        'commission'    => round( $commission ),
        'total'         => round( $total ),
        'currency'      => $currency,
        'exchange_rate'=> cf_get_exchange_rates()[ $currency ] ?? 1,
    ];
}

/**
 * Calculate annual ownership cost.
 *
 * @param array $params Calculation parameters.
 * @return array Breakdown with all components and total annual cost.
 */
function cf_calc_ownership( $params ) {
    $price       = floatval( $params['price'] );
    $currency    = $params['currency'];
    $engine_cc   = absint( $params['engine_cc'] );
    $engine_type = $params['engine_type'];

    $price_rub = cf_convert_to_rub( $price, $currency );

    // Estimate horsepower from engine CC (rough approximation).
    $hp = round( $engine_cc / 13 );

    // --- Transport tax (Moscow rates) ---
    if ( $hp <= 100 ) {
        $tax_rate = 12;
    } elseif ( $hp <= 125 ) {
        $tax_rate = 25;
    } elseif ( $hp <= 150 ) {
        $tax_rate = 35;
    } elseif ( $hp <= 175 ) {
        $tax_rate = 45;
    } elseif ( $hp <= 200 ) {
        $tax_rate = 50;
    } elseif ( $hp <= 225 ) {
        $tax_rate = 65;
    } elseif ( $hp <= 250 ) {
        $tax_rate = 75;
    } else {
        $tax_rate = 150;
    }

    $transport_tax = $hp * $tax_rate;

    // --- OSAGO estimate ---
    if ( $hp <= 100 ) {
        $osago = 8000;
    } elseif ( $hp <= 150 ) {
        $osago = 10000;
    } elseif ( $hp <= 200 ) {
        $osago = 12000;
    } else {
        $osago = 15000;
    }

    // --- KASKO estimate (5-8% of car value) ---
    if ( $price_rub <= 2000000 ) {
        $kasko_rate = 0.08;
    } elseif ( $price_rub <= 5000000 ) {
        $kasko_rate = 0.065;
    } else {
        $kasko_rate = 0.05;
    }
    $kasko = round( $price_rub * $kasko_rate );

    // --- Maintenance estimate ---
    if ( $price_rub <= 2000000 ) {
        $maintenance = 30000;
    } elseif ( $price_rub <= 4000000 ) {
        $maintenance = 50000;
    } elseif ( $price_rub <= 7000000 ) {
        $maintenance = 65000;
    } else {
        $maintenance = 80000;
    }

    // --- Fuel cost estimate ---
    // Average consumption l/100km by engine type.
    switch ( $engine_type ) {
        case 'diesel':
            $consumption = 7.5;
            $fuel_price  = 62;
            break;

        case 'electric':
            // kWh per 100km * price per kWh.
            $consumption = 18;
            $fuel_price  = 6;
            break;

        case 'hybrid':
            $consumption = 5.5;
            $fuel_price  = 55;
            break;

        case 'petrol':
        default:
            if ( $engine_cc <= 1500 ) {
                $consumption = 7;
            } elseif ( $engine_cc <= 2000 ) {
                $consumption = 9;
            } elseif ( $engine_cc <= 3000 ) {
                $consumption = 11;
            } else {
                $consumption = 14;
            }
            $fuel_price = 55;
            break;
    }

    $annual_km  = 15000; // Average annual mileage.
    $fuel_cost  = round( ( $consumption / 100 ) * $annual_km * $fuel_price );

    // --- Total annual cost ---
    $total = $transport_tax + $osago + $kasko + $maintenance + $fuel_cost;

    return [
        'mode'          => 'ownership',
        'price_rub'     => round( $price_rub ),
        'hp'            => $hp,
        'transport_tax' => round( $transport_tax ),
        'osago'         => $osago,
        'kasko'         => $kasko,
        'maintenance'   => $maintenance,
        'fuel_cost'     => $fuel_cost,
        'annual_km'     => $annual_km,
        'total'         => round( $total ),
    ];
}
