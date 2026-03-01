# CLAUDE.md — PerstLab / CarFinance MSK

This repository contains two projects:

1. **PerstLab-Analyzer** — Chrome extension for word highlighting
2. **CarFinance Theme** — WordPress theme for carfinance-msk.ru (auto import & selection)

---

## Project 1: PerstLab-Analyzer (Chrome Extension)

### Overview

Chrome extension (Manifest V3) that highlights user-specified words and phrases on web pages. Supports color-coded highlighting, word list import from `.txt` files, and statistics on word occurrences with location context.

- **Author:** Ivan Leshchenko
- **License:** MIT
- **Language:** Russian UI

### Structure

```
PerstLab-Analyzer-pro-fool/
├── manifest.json    # Manifest V3
├── popup.html       # Popup UI (inline CSS)
├── popup.js         # Popup logic
└── content.js       # Content script (highlighting, stats, location)
```

### Key Patterns

- No build system, no dependencies — plain HTML/CSS/JS
- `updateStatsDisplay()` defined twice in `popup.js` (closure + global)
- Words sorted by length (longest first) for regex matching
- `MutationObserver` with throttling (3 updates/sec, 300ms debounce)
- `manifest.json` references `styles.css` in `web_accessible_resources` but file doesn't exist; styles injected by `content.js`

---

## Project 2: CarFinance Theme (WordPress)

### Overview

Custom WordPress theme for carfinance-msk.ru — auto import and selection service (Korea, Japan, China, USA, UAE). SILO architecture, 8 content clusters, 110+ planned pages, Schema.org markup.

### Structure

```
carfinance-theme/
├── style.css                          # Theme declaration + all CSS (variables, components, responsive)
├── functions.php                      # CPTs, taxonomies, meta boxes, Schema.org, AJAX calculator, SILO helpers
├── header.php                         # Sticky header, SILO nav, country dropdown, mobile burger
├── footer.php                         # SILO footer, modal, contacts
├── index.php                          # Fallback template
├── front-page.php                     # Homepage — 18 blocks per spec
├── page.php                           # Default page (SILO pillars)
├── single.php                         # Blog post (sidebar, author E-E-A-T card)
├── single-car_model.php               # Car model page (specs, lots, calculator, related)
├── archive-car_model.php              # Catalog with faceted filters
├── 404.php                            # Not found page
├── page-templates/
│   ├── country.php                    # Country landing (/korea/, /japan/, /china/, /usa/, /uae/)
│   ├── calculator.php                 # 3-in-1 calculator (customs, ownership, constructor)
│   ├── services.php                   # Services overview + pricing packages
│   ├── about.php                      # About company (founder, team, reviews, contacts)
│   ├── faq.php                        # FAQ with categories, Schema.org/FAQPage
│   └── city.php                       # City landing (LocalBusiness schema)
├── assets/
│   ├── css/                           # (reserved for additional stylesheets)
│   ├── js/
│   │   ├── calculator.js              # Customs/ownership/constructor calculators
│   │   └── main.js                    # Burger, FAQ accordion, modal, counters, lead forms
│   └── img/                           # (images go here)
└── inc/                               # (reserved for PHP includes)
```

### Custom Post Types

| CPT | Slug | Purpose |
|---|---|---|
| `car_model` | `/catalog/` | Car models with specs, prices, generations |
| `auction_lot` | `/auctions/` | Live auction lots with status tracking |
| `case_study` | `/kejsy/` | Client cases (before/after, savings) |
| `cf_service` | `/services/` | Service descriptions |
| `cf_faq` | — | FAQ items (grouped by category) |
| `cf_team` | — | Team members (photo, role, social links) |
| `cf_review` | — | Client reviews (rating, video, model) |

### Custom Taxonomies

| Taxonomy | Attached to | Purpose |
|---|---|---|
| `cf_country` | car_model, auction_lot, case_study, cf_review | Country of origin |
| `cf_brand` | car_model, auction_lot | Car brand (Toyota, KIA, etc.) |
| `cf_body_type` | car_model, auction_lot | Body type (sedan, SUV, minivan) |
| `cf_price_range` | car_model, auction_lot | Price brackets |
| `cf_faq_cat` | cf_faq | FAQ categories |
| `cf_blog_cluster` | post | Blog SILO clusters |
| `cf_city` | case_study, cf_review | City (for local SEO) |

### Schema.org Markup (auto-generated JSON-LD)

- **Organization** — on all pages
- **WebSite** + SearchAction — homepage
- **Product** + Offer — `car_model` singles
- **Service** — service pages and country landings
- **FAQPage** — pages with FAQ blocks
- **Article** — blog posts
- **LocalBusiness** — city pages
- **Person** — team member pages
- **BreadcrumbList** — all inner pages

### SILO Architecture Rules

- **Level 1 (Matriarch):** Homepage, country pages, calculator, services — accumulate domain authority
- **Level 2 (Hub):** Brand pages, price ranges, section hubs — redistribute weight
- **Level 3 (Support):** Specific models, lots, blog posts, cases — close user intent
- **Cross-cocoon links:** Only between Level 1 pages. NEVER link Support→Support across different cocoons.
- Countries in directories (`/korea/`), cities on subdomains (`krasnodar.carfinance-msk.ru`)

### Calculator

- Client-side JavaScript calculator with server-side AJAX fallback
- Three modes: Customs duty, Ownership cost, Constructor (Japan)
- Handles customs duty rates by car age (0-3, 3-5, 5+ years) and engine displacement
- Includes: duty, utilization fee, SBKTS, EPTS, broker, freight, commission

### Development Workflow

1. Place `carfinance-theme/` in WordPress `wp-content/themes/`
2. Activate theme in WP Admin → Appearance → Themes
3. Theme auto-creates default pages on activation (country pages, calculator, services, etc.)
4. No build tools — plain PHP/CSS/JS, edit directly
5. No package.json or node_modules
6. Test by refreshing the WordPress site

### Key Conventions

- **Russian locale** — All UI text in Russian
- **No build step** — Vanilla PHP/CSS/JS
- **CSS prefix** — All classes use `cf-` prefix
- **BEM-like naming** — `cf-card__title`, `cf-btn--primary`
- **Deferred scripts** — Calculator and main JS loaded with `defer`
- **Lazy loading** — Images use `loading="lazy"` with explicit dimensions
- **Performance** — Emojis disabled, WordPress head junk removed, preconnect for fonts
- **Commit messages** — English, brief and descriptive

### Important Notes for AI Assistants

- WordPress theme is in `carfinance-theme/`, Chrome extension is in `PerstLab-Analyzer-pro-fool/`
- No `package.json`, `node_modules`, or Node.js tooling in either project
- `functions.php` registers all CPTs, taxonomies, meta boxes, Schema.org, AJAX handlers
- Country data (flags, colors, CSS classes) is centralized in `cf_get_country_data()`
- Meta box fields use `cf_` prefix for all custom field keys
- Theme creates default pages on activation via `cf_create_default_pages()`
- Calculator works client-side by default; AJAX handler in `functions.php` is a fallback
- `front-page.php` contains all 18 homepage blocks inline (no template parts yet)
