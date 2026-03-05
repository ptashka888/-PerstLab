<?php
defined('ABSPATH') || exit;

$sections = [];

if (is_singular('car_model') || is_tax('car_country')) {
    // Country page or model page — show brands from that country + related services
    $country_slug = '';

    if (is_tax('car_country')) {
        $country_slug = get_queried_object()->slug;
    } elseif (is_singular('car_model')) {
        $countries = get_the_terms(get_the_ID(), 'car_country');
        if ($countries && ! is_wp_error($countries)) {
            $country_slug = $countries[0]->slug;
        }
    }

    if ($country_slug) {
        $country_data = function_exists('cf_get_country_data') ? cf_get_country_data($country_slug) : [];
        $country_name = $country_data['name'] ?? $country_slug;

        // Brands in this country
        $brands = get_terms([
            'taxonomy'   => 'car_brand',
            'hide_empty' => true,
        ]);

        if ($brands && ! is_wp_error($brands)) {
            $brand_links = [];
            foreach ($brands as $brand) {
                $brand_links[] = [
                    'url'  => get_term_link($brand),
                    'text' => $brand->name,
                ];
            }
            if ($brand_links) {
                $sections[] = [
                    'title' => 'Марки из ' . $country_name,
                    'links' => $brand_links,
                ];
            }
        }

        // Related services
        $services_page = get_page_by_path('services');
        if ($services_page) {
            $sections[] = [
                'title' => 'Услуги',
                'links' => [
                    ['url' => get_permalink($services_page), 'text' => 'Все услуги'],
                    ['url' => home_url('/calculator/'), 'text' => 'Калькулятор растаможки'],
                ],
            ];
        }
    }
} elseif (is_tax('car_brand')) {
    // Brand page — show models + parent country
    $brand = get_queried_object();

    $model_links = [];
    $models = get_posts([
        'post_type'      => 'car_model',
        'posts_per_page' => 20,
        'tax_query'      => [
            [
                'taxonomy' => 'car_brand',
                'field'    => 'term_id',
                'terms'    => $brand->term_id,
            ],
        ],
    ]);

    foreach ($models as $model) {
        $model_links[] = [
            'url'  => get_permalink($model),
            'text' => get_the_title($model),
        ];
    }

    if ($model_links) {
        $sections[] = [
            'title' => 'Модели ' . $brand->name,
            'links' => $model_links,
        ];
    }

    // Countries
    if (function_exists('cf_get_country_data')) {
        $countries = get_terms(['taxonomy' => 'car_country', 'hide_empty' => true]);
        if ($countries && ! is_wp_error($countries)) {
            $country_links = [];
            foreach ($countries as $c) {
                $cd = cf_get_country_data($c->slug);
                $country_links[] = [
                    'url'  => get_term_link($c),
                    'text' => $cd['name'] ?? $c->name,
                ];
            }
            $sections[] = [
                'title' => 'Страны',
                'links' => $country_links,
            ];
        }
    }
} elseif (is_post_type_archive('car_model')) {
    // Catalog — show countries + popular tags
    if (function_exists('cf_get_country_data')) {
        $countries = get_terms(['taxonomy' => 'car_country', 'hide_empty' => true]);
        if ($countries && ! is_wp_error($countries)) {
            $country_links = [];
            foreach ($countries as $c) {
                $cd = cf_get_country_data($c->slug);
                $country_links[] = [
                    'url'  => get_term_link($c),
                    'text' => $cd['name'] ?? $c->name,
                ];
            }
            $sections[] = [
                'title' => 'По странам',
                'links' => $country_links,
            ];
        }
    }

    $body_types = get_terms(['taxonomy' => 'car_type', 'hide_empty' => true]);
    if ($body_types && ! is_wp_error($body_types)) {
        $type_links = [];
        foreach ($body_types as $bt) {
            $type_links[] = [
                'url'  => get_term_link($bt),
                'text' => $bt->name,
            ];
        }
        $sections[] = [
            'title' => 'Тип кузова',
            'links' => $type_links,
        ];
    }
}

if (empty($sections)) {
    return;
}
?>
<aside class="cf-silo-nav">
    <?php foreach ($sections as $section) : ?>
        <div class="cf-silo-nav__section">
            <h4 class="cf-silo-nav__heading"><?php echo esc_html($section['title']); ?></h4>
            <ul class="cf-silo-nav__list">
                <?php foreach ($section['links'] as $link) :
                    $url = is_wp_error($link['url']) ? '#' : $link['url'];
                ?>
                    <li class="cf-silo-nav__item">
                        <a href="<?php echo esc_url($url); ?>" class="cf-silo-nav__link">
                            <?php echo esc_html($link['text']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</aside>
