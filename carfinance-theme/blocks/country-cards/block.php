<?php
/**
 * Block: Country Cards
 * Country selection cards (5 countries).
 *
 * @param array $args {
 *     @type bool $show_comparison  Show comparison table below cards
 * }
 */

defined('ABSPATH') || exit;

$show_comparison = $args['show_comparison'] ?? false;

$countries = ['korea', 'japan', 'china', 'usa', 'uae'];

$fallback_data = [
    'korea' => [
        'name'    => 'Корея',
        'flag'    => '🇰🇷',
        'tagline' => 'KIA, Hyundai, Genesis',
        'price'   => '1 200 000',
        'color'   => '#0047A0',
        'slug'    => 'korea',
    ],
    'japan' => [
        'name'    => 'Япония',
        'flag'    => '🇯🇵',
        'tagline' => 'Toyota, Honda, Mazda',
        'price'   => '900 000',
        'color'   => '#BC002D',
        'slug'    => 'japan',
    ],
    'china' => [
        'name'    => 'Китай',
        'flag'    => '🇨🇳',
        'tagline' => 'Chery, Haval, Geely',
        'price'   => '1 000 000',
        'color'   => '#DE2910',
        'slug'    => 'china',
    ],
    'usa' => [
        'name'    => 'США',
        'flag'    => '🇺🇸',
        'tagline' => 'Tesla, Ford, Chevrolet',
        'price'   => '1 500 000',
        'color'   => '#3C3B6E',
        'slug'    => 'usa',
    ],
    'uae' => [
        'name'    => 'ОАЭ',
        'flag'    => '🇦🇪',
        'tagline' => 'Land Rover, Lexus, BMW',
        'price'   => '2 000 000',
        'color'   => '#00732F',
        'slug'    => 'uae',
    ],
];
?>

<section class="cf-country-cards">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title">Откуда привезём автомобиль</h2>
            <p class="cf-section-header__subtitle">Выберите страну — мы подберём лучший вариант</p>
        </div>

        <div class="cf-country-cards__grid">
            <?php foreach ($countries as $code):
                $data = function_exists('cf_get_country_data') ? cf_get_country_data($code) : [];
                $data = array_merge($fallback_data[$code] ?? [], $data);
                $name    = $data['name'] ?? $code;
                $flag    = $data['flag'] ?? '';
                $tagline = $data['tagline'] ?? '';
                $price   = $data['price'] ?? '';
                $color   = $data['color'] ?? 'var(--cf-primary)';
                $slug    = $data['slug'] ?? $code;
                $url     = home_url('/' . $slug . '/');
            ?>
                <a href="<?php echo esc_url($url); ?>" class="cf-country-cards__card" style="--card-accent: <?php echo esc_attr($color); ?>">
                    <span class="cf-country-cards__flag"><?php echo esc_html($flag); ?></span>
                    <h3 class="cf-country-cards__name"><?php echo esc_html($name); ?></h3>
                    <p class="cf-country-cards__tagline"><?php echo esc_html($tagline); ?></p>
                    <?php if ($price): ?>
                        <span class="cf-country-cards__price">от <?php echo esc_html($price); ?> ₽</span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($show_comparison): ?>
            <div class="cf-country-cards__comparison">
                <h3 class="cf-country-cards__comparison-title">Сравнение стран</h3>
                <div class="cf-country-cards__table-wrap">
                    <table class="cf-country-cards__table">
                        <thead>
                            <tr>
                                <th>Параметр</th>
                                <?php foreach ($countries as $code):
                                    $data = function_exists('cf_get_country_data') ? cf_get_country_data($code) : [];
                                    $data = array_merge($fallback_data[$code] ?? [], $data);
                                ?>
                                    <th><?php echo esc_html($data['flag'] ?? ''); ?> <?php echo esc_html($data['name'] ?? $code); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Срок доставки</td>
                                <td>14–25 дней</td>
                                <td>14–30 дней</td>
                                <td>20–35 дней</td>
                                <td>30–45 дней</td>
                                <td>25–40 дней</td>
                            </tr>
                            <tr>
                                <td>Цены от</td>
                                <td>1 200 000 ₽</td>
                                <td>900 000 ₽</td>
                                <td>1 000 000 ₽</td>
                                <td>1 500 000 ₽</td>
                                <td>2 000 000 ₽</td>
                            </tr>
                            <tr>
                                <td>Популярные марки</td>
                                <td>KIA, Hyundai</td>
                                <td>Toyota, Honda</td>
                                <td>Chery, Haval</td>
                                <td>Tesla, Ford</td>
                                <td>Lexus, BMW</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
