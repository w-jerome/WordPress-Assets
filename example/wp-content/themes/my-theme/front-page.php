<?php
/**
 * Front page
 */

global $wp_assets;

$wp_assets->enqueue_group( 'page-front-page' );

wp_head();

wp_footer();
