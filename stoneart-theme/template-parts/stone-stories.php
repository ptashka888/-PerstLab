<?php
/**
 * Template Part: Stone Stories (Emotional Block)
 *
 * @package StoneArt
 */
?>

<section id="stone-stories" class="sa-section sa-section--dark sa-animate" style="overflow:hidden;">
    <div class="sa-container">
        <div class="sa-stories">
            <div class="sa-stories__content">
                <h2 class="sa-stories__title sa-font-serif"><?php echo esc_html(sa_option('sa_stories_title', 'Истории, застывшие в камне')); ?></h2>
                <p class="sa-stories__quote"><?php echo esc_html(sa_option('sa_stories_quote', '«Каждый слэб натурального кварцита или мрамора — это картина, которую природа писала миллионы лет.»')); ?></p>
                <p class="sa-stories__text"><?php echo esc_html(sa_option('sa_stories_text', 'От глубокого черного гранита Black Galaxy из Индии до экзотического бразильского кварцита Patagonia. Наша миссия — сохранить эту природную душу и органично интегрировать её в пространство вашего дома.')); ?></p>
                <div class="sa-stories__stats">
                    <div class="sa-stories__stat">
                        <span class="sa-stories__stat-number"><?php echo esc_html(sa_option('sa_stories_stat1_num', '45+')); ?></span>
                        <span class="sa-stories__stat-label"><?php echo esc_html(sa_option('sa_stories_stat1_label', 'Стран импорта')); ?></span>
                    </div>
                    <div class="sa-stories__divider"></div>
                    <div class="sa-stories__stat">
                        <span class="sa-stories__stat-number"><?php echo esc_html(sa_option('sa_stories_stat2_num', '1200')); ?></span>
                        <span class="sa-stories__stat-label"><?php echo esc_html(sa_option('sa_stories_stat2_label', 'Слэбов на складе')); ?></span>
                    </div>
                </div>
            </div>
            <div class="sa-stories__images">
                <div class="sa-stories__image-grid">
                    <?php
                    $img1 = sa_option('sa_stories_image1');
                    $img2 = sa_option('sa_stories_image2');
                    $img1_url = $img1 ? $img1['url'] : 'https://images.unsplash.com/photo-1590373407330-3331804d023f?w=400';
                    $img2_url = $img2 ? $img2['url'] : 'https://images.unsplash.com/photo-1628527302488-349f7ba30f78?w=400';
                    ?>
                    <img src="<?php echo esc_url($img1_url); ?>" class="sa-stories__image" alt="Карьер по добыче мрамора" loading="lazy">
                    <img src="<?php echo esc_url($img2_url); ?>" class="sa-stories__image" alt="Слэб оникса с подсветкой" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>
