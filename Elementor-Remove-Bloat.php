<?php
/**
 * Plugin Name: Remove Elementor Bloat
 * Description: A Collection of snippets to remove the extra unnecessary CSS and JS files for websites using Elementor
 * Version: 1.0
 * Author: Naseem Saadeddine
 */

/* Disable jquery_migrate */
function dequeue_jquery_migrate( $scripts ) {
    if ( ! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            [ 'jquery-migrate' ]
        );
    }
}

// Remove scripts in frontend to non-admin 
function dequeue_for_logged_users() {
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' ); //Remove dashicons
		wp_deregister_style( 'elementor-icons' ); //Remove Eicons
    }
}

// Remove fontawesome
function dequeue_elementor_fontawesome() {
	foreach( [ 'solid', 'regular', 'brands' ] as $style ) {
		wp_deregister_style( 'elementor-icons-fa-' . $style );
	}
}

//Remove Gutenberg Block Library CSS from loading on the frontend
function remove_block_css() {
wp_dequeue_style( 'wp-block-library' ); // WordPress core
wp_dequeue_style( 'wp-block-library-theme' ); // WordPress core
wp_dequeue_style( 'wc-block-style' ); // WooCommerce
wp_dequeue_style( 'storefront-gutenberg-blocks' ); // Storefront theme
}

add_filter('use_block_editor_for_post_type', '__return_false', 10); // Disable Gutenberg Block Library
add_action( 'elementor/frontend/after_register_styles', 'dequeue_elementor_fontawesome', 20 ); // Disable fontawesome
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 ); // Disable Gutenberg Block Library
add_action( 'wp_default_scripts', 'dequeue_jquery_migrate' ); // Disable jquery_migrate
add_action( 'wp_enqueue_scripts', 'dequeue_for_logged_users', 100 ); // Disable scripts in frontend to non-admin 
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' ); // Disable Google Fonts
