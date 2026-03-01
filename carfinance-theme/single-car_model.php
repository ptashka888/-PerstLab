<?php
/**
 * Single Car Model Template
 *
 * /catalog/[brand]/[model]/[generation]/
 *
 * Blocks: Hero gallery, specs, active lots, price forecast,
 * calculator (preset), video reviews, similar models in budget,
 * reviews, FAQ.
 *
 * @package CarFinance
 */

get_header();

$post_id   = get_the_ID();
$brand     = '';
$brands    = get_the_terms($post_id, 'cf_brand');
if ($brands && !is_wp_error($brands)) {
    $brand = $brands[0]->name;
}

$country_terms = get_the_terms($post_id, 'cf_country');
$country_name  = ($country_terms && !is_wp_error($country_terms)) ? $country_terms[0]->name : '';

$year_from    = get_post_meta($post_id, 'cf_year_from', true);
$year_to      = get_post_meta($post_id, 'cf_year_to', true);
$engine       = get_post_meta($post_id, 'cf_engine', true);
$power        = get_post_meta($post_id, 'cf_power_hp', true);
$transmission = get_post_meta($post_id, 'cf_transmission', true);
$drive        = get_post_meta($post_id, 'cf_drive', true);
$price_from   = get_post_meta($post_id, 'cf_price_from', true);
$price_to     = get_post_meta($post_id, 'cf_price_to', true);
$generation   = get_post_meta($post_id, 'cf_generation', true);
$reliability  = get_post_meta($post_id, 'cf_reliability', true);
?>

<!-- ===== HERO ===== -->
<section class="cf-section" style="padding-top:40px;">
  <div class="cf-container">
    <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:40px;align-items:start;">

      <!-- Gallery -->
      <div>
        <?php if (has_post_thumbnail()) : ?>
          <img src="<?php the_post_thumbnail_url('cf-hero'); ?>"
               alt="<?php the_title_attribute(); ?>"
               width="1200" height="600"
               style="border-radius:var(--cf-radius);width:100%;height:auto;object-fit:cover;">
        <?php endif; ?>
      </div>

      <!-- Specs card -->
      <div>
        <h1><?php the_title(); ?></h1>
        <?php if ($brand) : ?>
          <p style="font-size:1.125rem;color:var(--cf-gray-500);margin-top:4px;"><?php echo esc_html($brand); ?> <?php if ($generation) echo '/ ' . esc_html($generation); ?></p>
        <?php endif; ?>

        <?php if ($price_from) : ?>
          <div style="margin-top:20px;">
            <span class="cf-card__price" style="font-size:1.75rem;"><?php echo cf_format_price((int) $price_from); ?></span>
            <?php if ($price_to) : ?>
              <span style="color:var(--cf-gray-500);"> — <?php echo cf_format_price((int) $price_to); ?></span>
            <?php endif; ?>
            <div style="font-size:0.875rem;color:var(--cf-gray-500);">под ключ в Россию</div>
          </div>
        <?php endif; ?>

        <!-- Specs table -->
        <table style="width:100%;margin-top:24px;border-collapse:collapse;">
          <?php
          $specs = array_filter([
              'Год выпуска'  => ($year_from && $year_to) ? "$year_from — $year_to" : ($year_from ?: ''),
              'Двигатель'    => $engine,
              'Мощность'     => $power ? $power . ' л.с.' : '',
              'КПП'          => $transmission,
              'Привод'       => $drive,
              'Поколение'    => $generation,
              'Надёжность'   => $reliability ? $reliability . '/10' : '',
              'Страна'       => $country_name,
          ]);
          foreach ($specs as $label => $val) :
          ?>
            <tr style="border-bottom:1px solid var(--cf-gray-100);">
              <td style="padding:10px 0;color:var(--cf-gray-500);width:40%;"><?php echo esc_html($label); ?></td>
              <td style="padding:10px 0;font-weight:600;"><?php echo esc_html($val); ?></td>
            </tr>
          <?php endforeach; ?>
        </table>

        <div style="display:flex;gap:12px;margin-top:24px;">
          <a href="#cf-lead-modal" class="cf-btn cf-btn--primary" data-modal="lead">Заказать подбор</a>
          <a href="#model-calc" class="cf-btn cf-btn--outline">Рассчитать стоимость</a>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===== DESCRIPTION ===== -->
<?php if (get_the_content()) : ?>
<section class="cf-section cf-section--gray">
  <div class="cf-container">
    <div style="max-width:800px;">
      <h2>Обзор <?php the_title(); ?></h2>
      <div class="cf-mt-3" style="line-height:1.8;">
        <?php the_content(); ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ===== ACTIVE LOTS FOR THIS MODEL ===== -->
<section class="cf-section">
  <div class="cf-container">
    <h2 class="cf-mb-3">Актуальные лоты <?php the_title(); ?></h2>

    <div class="cf-grid cf-grid--4">
      <?php
      $lots = cf_get_model_lots($post_id, 8);
      if ($lots) :
          foreach ($lots as $lot) :
              $lot_price = get_post_meta($lot->ID, 'cf_lot_price_rub', true);
              $lot_year  = get_post_meta($lot->ID, 'cf_lot_year', true);
              $lot_km    = get_post_meta($lot->ID, 'cf_lot_mileage', true);
      ?>
        <article class="cf-lot-card">
          <?php if (has_post_thumbnail($lot->ID)) : ?>
            <img class="cf-lot-card__img" src="<?php echo get_the_post_thumbnail_url($lot->ID, 'cf-lot'); ?>"
                 alt="<?php echo esc_attr($lot->post_title); ?>" loading="lazy" width="480" height="360">
          <?php endif; ?>
          <div class="cf-lot-card__body">
            <h3 class="cf-lot-card__title"><a href="<?php echo get_permalink($lot->ID); ?>"><?php echo esc_html($lot->post_title); ?></a></h3>
            <div class="cf-lot-card__specs">
              <?php if ($lot_year) : ?><span><?php echo esc_html($lot_year); ?> г.</span><?php endif; ?>
              <?php if ($lot_km) : ?><span><?php echo number_format((int)$lot_km, 0, ',', ' '); ?> км</span><?php endif; ?>
            </div>
            <?php if ($lot_price) : ?>
              <div class="cf-lot-card__price"><?php echo cf_format_price((int) $lot_price); ?></div>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; else : ?>
        <p style="grid-column:1/-1;">Актуальных лотов по данной модели пока нет. <a href="#cf-lead-modal" data-modal="lead">Оставьте заявку</a> — найдём для вас.</p>
      <?php endif; ?>
    </div>
  </div>
</section>


<!-- ===== CALCULATOR (preset for this model) ===== -->
<section class="cf-section cf-section--gray" id="model-calc">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Калькулятор: <?php the_title(); ?> под ключ</h2>
    </div>
    <div class="cf-calculator">
      <form id="cf-calc-form">
        <div class="cf-calc-form__row">
          <div class="cf-calc-form__group">
            <label>Цена автомобиля (&#8381;)</label>
            <input type="number" name="price_fob" value="<?php echo esc_attr($price_from); ?>" min="0" step="10000" required>
          </div>
          <div class="cf-calc-form__group">
            <label>Год выпуска</label>
            <select name="year">
              <?php for ($y = date('Y') + 1; $y >= 2000; $y--) : ?>
                <option value="<?php echo $y; ?>" <?php selected($y, (int)$year_from); ?>><?php echo $y; ?></option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
        <div class="cf-calc-form__row">
          <div class="cf-calc-form__group">
            <label>Объём двигателя (куб.см)</label>
            <input type="number" name="engine_cc" value="2000" min="600" max="8000">
          </div>
          <div class="cf-calc-form__group">
            <label>Тип топлива</label>
            <select name="fuel_type">
              <option value="gasoline">Бензин</option>
              <option value="diesel">Дизель</option>
              <option value="hybrid">Гибрид</option>
              <option value="electric">Электро</option>
            </select>
          </div>
        </div>
        <button type="submit" class="cf-btn cf-btn--primary cf-btn--lg cf-btn--block">Рассчитать</button>
      </form>
      <div class="cf-calc-result" id="cf-calc-result" style="display:none;">
        <div class="cf-calc-result__total" id="calc-total"></div>
        <ul class="cf-calc-result__breakdown" id="calc-breakdown"></ul>
      </div>
    </div>
  </div>
</section>


<!-- ===== SIMILAR MODELS IN BUDGET ===== -->
<?php
$related = cf_get_related_models($post_id, 4);
if ($related) :
?>
<section class="cf-section">
  <div class="cf-container">
    <h2 class="cf-mb-3">Похожие модели в бюджете</h2>
    <div class="cf-grid cf-grid--4">
      <?php foreach ($related as $rel) : ?>
        <a href="<?php echo get_permalink($rel->ID); ?>" class="cf-card" style="text-decoration:none;">
          <?php if (has_post_thumbnail($rel->ID)) : ?>
            <img class="cf-card__img" src="<?php echo get_the_post_thumbnail_url($rel->ID, 'cf-card'); ?>"
                 alt="<?php echo esc_attr($rel->post_title); ?>" loading="lazy" width="600" height="375">
          <?php endif; ?>
          <div class="cf-card__body">
            <h4 class="cf-card__title"><?php echo esc_html($rel->post_title); ?></h4>
            <?php
            $rel_price = get_post_meta($rel->ID, 'cf_price_from', true);
            if ($rel_price) :
            ?>
              <div class="cf-card__price"><?php echo cf_format_price((int) $rel_price); ?></div>
            <?php endif; ?>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ===== CTA ===== -->
<section class="cf-cta">
  <div class="cf-container">
    <h2>Хотите <?php the_title(); ?>?</h2>
    <p>Оставьте заявку — мы найдём лучший вариант и рассчитаем полную стоимость</p>
    <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--lg" data-modal="lead">Заказать подбор</a>
  </div>
</section>

<?php get_footer(); ?>
