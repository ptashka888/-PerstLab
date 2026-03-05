<?php
/**
 * Block: Checklist
 * 48-point inspection checklist with expandable categories.
 *
 * @param array $args {
 *     @type string $variant  'default'|'country'
 *     @type string $country  Country code (for country variant)
 * }
 */

defined('ABSPATH') || exit;

$variant = $args['variant'] ?? 'default';
$country = $args['country'] ?? '';

$categories = [
    [
        'title' => 'Кузов',
        'count' => 12,
        'items' => [
            'Толщина ЛКП на всех элементах',
            'Зазоры кузовных панелей',
            'Следы вторичной окраски',
            'Состояние порогов и арок',
            'Коррозия днища',
            'Герметик заводских швов',
            'Состояние стёкол (маркировка)',
            'Проверка крыши на вмятины',
            'Работа петель дверей/капота',
            'Состояние бамперов',
            'Проверка рамки радиатора',
            'Геометрия кузова',
        ],
    ],
    [
        'title' => 'Двигатель',
        'count' => 8,
        'items' => [
            'Компрессия цилиндров',
            'Состояние масла и антифриза',
            'Течи прокладок и сальников',
            'Работа на холодную/горячую',
            'Посторонние шумы и стуки',
            'Состояние ремня/цепи ГРМ',
            'Проверка турбины (при наличии)',
            'Дымность выхлопа',
        ],
    ],
    [
        'title' => 'Ходовая',
        'count' => 8,
        'items' => [
            'Состояние амортизаторов',
            'Люфты рулевых наконечников',
            'Состояние тормозных дисков/колодок',
            'Проверка ШРУСов',
            'Состояние сайлентблоков',
            'Проверка ступичных подшипников',
            'Состояние пружин/рессор',
            'Углы развала-схождения',
        ],
    ],
    [
        'title' => 'Электрика',
        'count' => 8,
        'items' => [
            'Диагностика блоков (OBD-II)',
            'Проверка аккумулятора',
            'Работа генератора',
            'Все электростеклоподъёмники',
            'Кондиционер/климат-контроль',
            'Головной свет и оптика',
            'Мультимедиа и навигация',
            'Датчики парковки и камеры',
        ],
    ],
    [
        'title' => 'Салон',
        'count' => 6,
        'items' => [
            'Состояние обивки сидений',
            'Работа электрорегулировок',
            'Подогрев сидений/руля',
            'Состояние руля и педалей (износ)',
            'Запахи в салоне',
            'Работа всех кнопок и переключателей',
        ],
    ],
    [
        'title' => 'Документы',
        'count' => 6,
        'items' => [
            'Проверка VIN-номера',
            'История ДТП по базам',
            'Проверка на залог/арест',
            'Таможенная история',
            'Сверка комплектации',
            'Проверка сервисной книжки',
        ],
    ],
];
?>

<section class="cf-checklist cf-checklist--<?php echo esc_attr($variant); ?>">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title">48-точечная проверка автомобиля</h2>
            <p class="cf-section-header__subtitle">Каждый автомобиль проходит полную диагностику перед покупкой</p>
        </div>

        <div class="cf-checklist__categories">
            <?php foreach ($categories as $index => $cat): ?>
                <div class="cf-checklist__category" data-index="<?php echo esc_attr($index); ?>">
                    <button class="cf-checklist__header" type="button" aria-expanded="false">
                        <span class="cf-checklist__title"><?php echo esc_html($cat['title']); ?></span>
                        <span class="cf-checklist__count"><?php echo esc_html($cat['count']); ?> пунктов</span>
                        <span class="cf-checklist__toggle" aria-hidden="true">+</span>
                    </button>
                    <ul class="cf-checklist__list" hidden>
                        <?php foreach ($cat['items'] as $item): ?>
                            <li class="cf-checklist__item">
                                <span class="cf-checklist__check">✓</span>
                                <?php echo esc_html($item); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
