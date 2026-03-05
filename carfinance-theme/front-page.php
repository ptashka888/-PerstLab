<?php
/**
 * Template: Front Page
 * 18 blocks assembled via cf_block()
 */

defined('ABSPATH') || exit;

get_header();

// 1. Hero
cf_block('hero', ['variant' => 'home']);

// 2. Counters / Features
cf_block('features', ['variant' => 'counters']);

// 3. Dealer Traps
cf_block('dealer-traps');

// 4. Self vs Us comparison
cf_block('self-vs-us');

// 5. 48-point Checklist
cf_block('checklist', ['variant' => 'default']);

// 6. Country Cards + comparison table
cf_block('country-cards', ['show_comparison' => true]);

// 7. Cars Slider — featured models
cf_block('cars-slider', ['variant' => 'featured', 'limit' => 8]);

// 8. Service Packages / Pricing
cf_block('service-packages');

// 9. Calculator
cf_block('calculator', ['variant' => 'full']);

// 10. Cases
cf_block('cases', ['variant' => 'slider', 'limit' => 6]);

// 11. Steps / Timeline
cf_block('steps', ['variant' => 'home']);

// 12. Founder
cf_block('founder');

// 13. Team
cf_block('team', ['limit' => 8]);

// 14. Services Cards
cf_block('services-cards');

// 15. Comparison Table (Dealer vs Us)
cf_block('comparison-table', ['variant' => 'dealer-vs-us']);

// 16. Video Reviews
cf_block('reviews-video');

// 17. FAQ
cf_block('faq', ['source' => 'homepage']);

// 18. Final CTA
cf_block('cta-final', ['variant' => 'urgency']);

get_footer();
