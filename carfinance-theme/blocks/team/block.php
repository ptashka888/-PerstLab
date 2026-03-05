<?php
defined('ABSPATH') || exit;

$limit = (int) ($args['limit'] ?? 8);

$team = new WP_Query([
    'post_type'      => 'cf_team',
    'posts_per_page' => $limit,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);
?>
<section class="cf-team">
    <div class="cf-team__container">
        <h2 class="cf-team__title">Наша команда</h2>
        <p class="cf-team__subtitle">Профессионалы, которые помогут вам на каждом этапе</p>

        <?php if ($team->have_posts()) : ?>
            <div class="cf-team__grid">
                <?php while ($team->have_posts()) : $team->the_post();
                    $post_id    = get_the_ID();
                    $photo      = get_the_post_thumbnail_url($post_id, 'medium');
                    $role       = cf_get_field('cf_team_role', $post_id);
                    $experience = cf_get_field('cf_team_experience', $post_id);
                    $telegram   = cf_get_field('cf_team_telegram', $post_id);
                    $whatsapp   = cf_get_field('cf_team_whatsapp', $post_id);
                ?>
                    <div class="cf-team__card">
                        <div class="cf-team__photo-wrap">
                            <?php if ($photo) : ?>
                                <img class="cf-team__photo" src="<?php echo esc_url($photo); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" width="200" height="200">
                            <?php else : ?>
                                <div class="cf-team__photo-placeholder"></div>
                            <?php endif; ?>
                        </div>

                        <h3 class="cf-team__name"><?php the_title(); ?></h3>

                        <?php if ($role) : ?>
                            <p class="cf-team__role"><?php echo esc_html($role); ?></p>
                        <?php endif; ?>

                        <?php if ($experience) : ?>
                            <span class="cf-team__badge">Опыт: <?php echo esc_html($experience); ?></span>
                        <?php endif; ?>

                        <?php if ($telegram || $whatsapp) : ?>
                            <div class="cf-team__socials">
                                <?php if ($telegram) : ?>
                                    <a class="cf-team__social-link cf-team__social-link--tg" href="<?php echo esc_url($telegram); ?>" target="_blank" rel="noopener" aria-label="Telegram">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161l-1.97 9.281c-.146.658-.537.818-1.084.508l-3-2.211-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg>
                                    </a>
                                <?php endif; ?>
                                <?php if ($whatsapp) : ?>
                                    <a class="cf-team__social-link cf-team__social-link--wa" href="<?php echo esc_url($whatsapp); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12c0 2.121.553 4.114 1.519 5.845L.053 23.681l5.972-1.435A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm6.29 16.908c-.262.738-1.539 1.37-2.122 1.418-.583.048-1.125.27-3.792-.789-3.222-1.278-5.265-4.574-5.424-4.787-.158-.213-1.293-1.722-1.293-3.284 0-1.562.818-2.33 1.108-2.65.29-.32.634-.4.846-.4.211 0 .422.002.607.011.195.009.456-.074.713.544.262.63.891 2.175.97 2.333.079.158.132.343.026.556-.105.213-.158.343-.316.528-.158.185-.334.412-.476.553-.158.158-.323.33-.139.646.185.316.821 1.354 1.763 2.193 1.21 1.078 2.23 1.413 2.546 1.571.316.158.502.132.686-.079.185-.211.79-.924.999-1.238.211-.316.422-.264.713-.158.29.105 1.845.871 2.161 1.029.316.158.527.237.606.369.079.132.079.764-.184 1.503z"/></svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="cf-team__empty">Команда пока не добавлена.</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
</section>
