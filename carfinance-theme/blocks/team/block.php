<?php
/**
 * Block: Team
 * Shows team members from cf_team CPT or falls back to demo data.
 */
defined('ABSPATH') || exit;

$limit = (int) ($args['limit'] ?? 8);

$team = new WP_Query([
    'post_type'      => 'cf_team',
    'posts_per_page' => $limit,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

// Static fallback demo data (shown when no CPT entries exist)
$demo_members = [
    [
        'name'       => 'Артем Бараниченко',
        'role'       => 'Генеральный директор',
        'experience' => '8 лет',
        'city'       => 'Москва',
        'photo'      => '',
        'initials'   => 'АБ',
        'quote'      => 'Честность — наша главная валюта',
        'telegram'   => 'https://t.me/carfinancemsk',
    ],
    [
        'name'       => 'Дмитрий Колесников',
        'role'       => 'Руководитель отдела подбора',
        'experience' => '6 лет',
        'city'       => 'Владивосток',
        'photo'      => '',
        'initials'   => 'ДК',
        'quote'      => 'Знаю каждый японский аукцион',
    ],
    [
        'name'       => 'Анна Громова',
        'role'       => 'Таможенный брокер',
        'experience' => '5 лет',
        'city'       => 'Владивосток',
        'photo'      => '',
        'initials'   => 'АГ',
        'quote'      => 'Ни одной задержки за 5 лет',
    ],
    [
        'name'       => 'Роман Власов',
        'role'       => 'Эксперт по корейскому рынку',
        'experience' => '4 года',
        'city'       => 'Краснодар',
        'photo'      => '',
        'initials'   => 'РВ',
        'quote'      => 'KIA и Hyundai — это мой профиль',
    ],
    [
        'name'       => 'Мария Соколова',
        'role'       => 'Менеджер по работе с клиентами',
        'experience' => '3 года',
        'city'       => 'Москва',
        'photo'      => '',
        'initials'   => 'МС',
        'quote'      => 'На связи 7 дней в неделю',
        'telegram'   => 'https://t.me/carfinancemsk',
    ],
];
?>
<section class="cf-team">
    <div class="cf-team__container">
        <div class="cf-section-header cf-section-header--center">
            <p class="cf-section-header__overtitle">Наши специалисты</p>
            <h2 class="cf-section-header__title">Команда профессионалов</h2>
            <p class="cf-section-header__subtitle">28 сотрудников в 4 офисах — Владивосток, Москва, Краснодар, Сочи</p>
        </div>

        <?php if ($team->have_posts()) : ?>
            <div class="cf-team__grid">
                <?php while ($team->have_posts()) : $team->the_post();
                    $post_id    = get_the_ID();
                    $photo      = get_the_post_thumbnail_url($post_id, 'medium');
                    $role       = cf_get_field('cf_team_role', $post_id);
                    $experience = cf_get_field('cf_team_experience', $post_id);
                    $city       = cf_get_field('cf_team_city', $post_id);
                    $quote      = cf_get_field('cf_team_quote', $post_id);
                    $telegram   = cf_get_field('cf_team_telegram', $post_id);
                    $whatsapp   = cf_get_field('cf_team_whatsapp', $post_id);
                    $page_url   = cf_get_field('cf_team_page_url', $post_id) ?: get_permalink($post_id);
                ?>
                    <div class="cf-team__card">
                        <a href="<?php echo esc_url($page_url); ?>" class="cf-team__photo-link">
                            <div class="cf-team__photo-wrap">
                                <?php if ($photo) : ?>
                                    <img class="cf-team__photo" src="<?php echo esc_url($photo); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" width="200" height="200">
                                <?php else : ?>
                                    <div class="cf-team__photo-placeholder">
                                        <span class="cf-team__initials"><?php echo esc_html(mb_substr(get_the_title(), 0, 1)); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>

                        <div class="cf-team__card-body">
                            <h3 class="cf-team__name">
                                <a href="<?php echo esc_url($page_url); ?>"><?php the_title(); ?></a>
                            </h3>

                            <?php if ($role) : ?>
                                <p class="cf-team__role"><?php echo esc_html($role); ?></p>
                            <?php endif; ?>

                            <div class="cf-team__badges">
                                <?php if ($experience) : ?>
                                    <span class="cf-team__badge">⚡ <?php echo esc_html($experience); ?></span>
                                <?php endif; ?>
                                <?php if ($city) : ?>
                                    <span class="cf-team__badge">📍 <?php echo esc_html($city); ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if ($quote) : ?>
                                <p class="cf-team__quote">&laquo;<?php echo esc_html($quote); ?>&raquo;</p>
                            <?php endif; ?>

                            <?php if ($telegram || $whatsapp) : ?>
                                <div class="cf-team__socials">
                                    <?php if ($telegram) : ?>
                                        <a class="cf-team__social-link cf-team__social-link--tg" href="<?php echo esc_url($telegram); ?>" target="_blank" rel="noopener" aria-label="Telegram">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161l-1.97 9.281c-.146.658-.537.818-1.084.508l-3-2.211-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($whatsapp) : ?>
                                        <a class="cf-team__social-link cf-team__social-link--wa" href="<?php echo esc_url($whatsapp); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12c0 2.121.553 4.114 1.519 5.845L.053 23.681l5.972-1.435A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm6.29 16.908c-.262.738-1.539 1.37-2.122 1.418-.583.048-1.125.27-3.792-.789-3.222-1.278-5.265-4.574-5.424-4.787-.158-.213-1.293-1.722-1.293-3.284 0-1.562.818-2.33 1.108-2.65.29-.32.634-.4.846-.4.211 0 .422.002.607.011.195.009.456-.074.713.544.262.63.891 2.175.97 2.333.079.158.132.343.026.556-.105.213-.158.343-.316.528-.158.185-.334.412-.476.553-.158.158-.323.33-.139.646.185.316.821 1.354 1.763 2.193 1.21 1.078 2.23 1.413 2.546 1.571.316.158.502.132.686-.079.185-.211.79-.924.999-1.238.211-.316.422-.264.713-.158.29.105 1.845.871 2.161 1.029.316.158.527.237.606.369.079.132.079.764-.184 1.503z"/></svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

        <?php else : ?>
            <!-- Demo fallback -->
            <div class="cf-team__grid">
                <?php foreach ($demo_members as $member) : ?>
                    <div class="cf-team__card">
                        <div class="cf-team__photo-wrap">
                            <?php if (!empty($member['photo'])) : ?>
                                <img class="cf-team__photo" src="<?php echo esc_url($member['photo']); ?>" alt="<?php echo esc_attr($member['name']); ?>" loading="lazy" width="200" height="200">
                            <?php else : ?>
                                <div class="cf-team__photo-placeholder">
                                    <span class="cf-team__initials"><?php echo esc_html($member['initials'] ?? mb_substr($member['name'], 0, 2)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="cf-team__card-body">
                            <h3 class="cf-team__name"><?php echo esc_html($member['name']); ?></h3>
                            <p class="cf-team__role"><?php echo esc_html($member['role']); ?></p>

                            <div class="cf-team__badges">
                                <?php if (!empty($member['experience'])) : ?>
                                    <span class="cf-team__badge">⚡ <?php echo esc_html($member['experience']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($member['city'])) : ?>
                                    <span class="cf-team__badge">📍 <?php echo esc_html($member['city']); ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($member['quote'])) : ?>
                                <p class="cf-team__quote">&laquo;<?php echo esc_html($member['quote']); ?>&raquo;</p>
                            <?php endif; ?>

                            <?php if (!empty($member['telegram'])) : ?>
                                <div class="cf-team__socials">
                                    <a class="cf-team__social-link cf-team__social-link--tg" href="<?php echo esc_url($member['telegram']); ?>" target="_blank" rel="noopener" aria-label="Telegram">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161l-1.97 9.281c-.146.658-.537.818-1.084.508l-3-2.211-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="cf-team__footer">
            <p class="cf-team__offices">Наши офисы: <strong>Владивосток</strong> · <strong>Москва</strong> · <strong>Краснодар</strong> · <strong>Сочи</strong></p>
            <a href="<?php echo esc_url(home_url('/o-nas/')); ?>" class="cf-btn cf-btn--outline">Вся команда →</a>
        </div>
    </div>
</section>
