<?php
/**
 * Front Page Template
 *
 * @package StoneArt
 */

get_header();

// Hero Section
get_template_part('template-parts/hero');

// Materials Visualizer
get_template_part('template-parts/visualizer');

// Comparison Table
get_template_part('template-parts/comparison');

// Quiz
get_template_part('template-parts/quiz');

// About / Production
get_template_part('template-parts/about');

// Stone Stories
get_template_part('template-parts/stone-stories');

// Product Catalog
get_template_part('template-parts/catalog');

// Work Process
get_template_part('template-parts/process');

// Experts (E-E-A-T)
get_template_part('template-parts/experts');

// Care Tips
get_template_part('template-parts/care-tips');

// Reviews
get_template_part('template-parts/reviews');

// Internal Links (SILO)
sa_render_silo_links();

get_footer();
