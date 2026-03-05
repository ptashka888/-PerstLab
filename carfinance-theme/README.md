# CarFinance MSK — WordPress Theme v2.0

Модульная тема WordPress для сайта автоимпорта carfinance-msk.ru. Поддержка WordPress Multisite, ACF Pro, SILO SEO архитектура.

## Требования

- WordPress 6.4+
- PHP 8.1+
- ACF Pro 6.x (рекомендуется)
- MySQL 8.0+ / MariaDB 10.6+

## Установка

### Одиночный сайт

1. Скопируйте `carfinance-theme/` в `wp-content/themes/`
2. Активируйте тему: **Внешний вид → Темы**
3. Установите ACF Pro и импортируйте поля из `acf-json/`
4. Тема автоматически создаст страницы при активации

### WordPress Multisite (субдомены)

1. Добавьте в `wp-config.php`:

```php
define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'carfinance-msk.ru');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
```

2. Настройте DNS: `*.carfinance-msk.ru` → IP сервера
3. Настройте wildcard SSL (Let's Encrypt)
4. Используйте `nginx-multisite.conf` для Nginx
5. Активируйте тему для сети: **Сетевая → Темы**

## Структура

```
carfinance-theme/
├── functions.php          — Bootstrap, enqueue, настройки
├── inc/                   — PHP модули
│   ├── cpt.php           — Custom Post Types (7 типов)
│   ├── taxonomies.php    — Таксономии (9 штук)
│   ├── helpers.php       — Вспомогательные функции
│   ├── acf-fields.php    — ACF Pro интеграция
│   ├── schema.php        — Schema.org JSON-LD
│   ├── seo.php           — SEO (canonical, robots, hreflang)
│   ├── breadcrumbs.php   — Хлебные крошки
│   ├── interlinking.php  — SILO перелинковка
│   ├── catalog-filter.php — AJAX фильтр каталога
│   ├── catalog-tags.php  — SEO-теги каталога
│   ├── calculator-ajax.php — Калькулятор (серверная часть)
│   └── multisite.php     — Поддержка Multisite
├── blocks/               — 31 переиспользуемый блок
├── acf-json/             — ACF поля (13 групп)
├── assets/
│   ├── css/              — Модульные стили (BEM)
│   └── js/               — JavaScript
└── Шаблоны страниц       — 24 файла
```

## ACF Pro

Импорт полей: **ACF → Инструменты → Импорт** → выберите JSON файлы из `acf-json/`.

Группы полей:
- Глобальные настройки (телефоны, соцсети, курсы валют)
- Главная страница, Страна, Услуга, Город
- Модель авто, Аукционный лот, Кейс
- Команда, FAQ, Теги каталога, Блог, Калькулятор

## SILO архитектура

- **L1 (Матриарх):** Главная, страны, калькулятор, услуги
- **L2 (Хаб):** Бренды, ценовые диапазоны, хабы блога
- **L3 (Поддержка):** Модели, лоты, посты, кейсы

Перелинковка автоматическая через `cf_block('interlinking')`.

## Разработка

- Нет сборщиков — чистый PHP/CSS/JS
- CSS: BEM с префиксом `cf-`
- Блоки: `blocks/{name}/block.php` + `style.css`
- Стили блоков загружаются автоматически через `cf_block()`
