<?php
/**
 * Template Part: Experts & Tips (E-E-A-T)
 *
 * @package StoneArt
 */

$title = sa_option('sa_experts_title', 'Наши эксперты к вашим услугам');

// Get team members from CPT or use defaults
$team_query = new WP_Query([
    'post_type'      => 'sa_team',
    'posts_per_page' => 2,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

$has_team = $team_query->have_posts();

$default_team = [
    [
        'name'  => 'Виктор Петров',
        'role'  => 'Старший технолог',
        'bio'   => 'Опыт работы с камнем: 18 лет.',
        'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400',
    ],
    [
        'name'  => 'Елена Смирнова',
        'role'  => 'Дизайнер-проектировщик',
        'bio'   => 'Спроектировала более 500 изделий.',
        'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400',
    ],
];

$tip_title = sa_option('sa_expert_tip_title', 'Совет эксперта: Как не ошибиться?');
$tip_text  = sa_option('sa_expert_tip_text', '«Для кухонь с высокой нагрузкой (много готовите) я всегда рекомендую Кварцевый агломерат. В отличие от мрамора, он не имеет пор, поэтому в него не впитывается вино, свекла или кофе. А вот для роскошной облицовки камина или стен в ванной натуральный мрамор и оникс вне конкуренции.»');

$calc = get_page_by_path('calculator');
$cta_url = $calc ? get_permalink($calc) : '#quiz-section';
?>

<section id="expert-tips" class="sa-section sa-section--gray sa-animate" style="border-top:1px solid var(--sa-gray-200);">
    <div class="sa-container">
        <h2 class="sa-section__title"><?php echo esc_html($title); ?></h2>
        <div style="margin-top:4rem;">
            <div class="sa-experts">
                <?php if ($has_team) :
                    while ($team_query->have_posts()) : $team_query->the_post();
                        $role  = function_exists('get_field') ? get_field('sa_team_role') : '';
                        $bio   = function_exists('get_field') ? get_field('sa_team_bio') : '';
                        $photo = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'sa-card') : $default_team[0]['photo'];
                ?>
                    <div class="sa-expert" itemscope itemtype="https://schema.org/Person">
                        <div class="sa-expert__photo-wrap">
                            <img src="<?php echo esc_url($photo); ?>" alt="<?php the_title_attribute(); ?>" class="sa-expert__photo" loading="lazy" itemprop="image">
                        </div>
                        <h4 class="sa-expert__name" itemprop="name"><?php the_title(); ?></h4>
                        <p class="sa-expert__role" itemprop="jobTitle"><?php echo esc_html($role); ?></p>
                        <p class="sa-expert__bio"><?php echo esc_html($bio); ?></p>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    foreach ($default_team as $member) : ?>
                        <div class="sa-expert" itemscope itemtype="https://schema.org/Person">
                            <div class="sa-expert__photo-wrap">
                                <img src="<?php echo esc_url($member['photo']); ?>" alt="<?php echo esc_attr($member['name']); ?>" class="sa-expert__photo" loading="lazy" itemprop="image">
                            </div>
                            <h4 class="sa-expert__name" itemprop="name"><?php echo esc_html($member['name']); ?></h4>
                            <p class="sa-expert__role" itemprop="jobTitle"><?php echo esc_html($member['role']); ?></p>
                            <p class="sa-expert__bio"><?php echo esc_html($member['bio']); ?></p>
                        </div>
                    <?php endforeach;
                endif; ?>

                <div class="sa-expert-tip">
                    <i class="fa-solid fa-quote-right sa-expert-tip__icon"></i>
                    <h3 class="sa-expert-tip__title sa-font-serif"><?php echo esc_html($tip_title); ?></h3>
                    <p class="sa-expert-tip__text"><?php echo esc_html($tip_text); ?></p>
                    <a href="<?php echo esc_url($cta_url); ?>" class="sa-expert-tip__link">Получить консультацию</a>
                </div>
            </div>
        </div>
    </div>
</section>
