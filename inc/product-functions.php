<?php
/**
 * WooCommerce Compatibility Functions for Alam Al Anika Theme.
 *
 * @package AlamAlAnika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Ensure WooCommerce is active
if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

/**
 * Add a wrapper class to all WooCommerce pages for styling purposes.
 */
function alam_al_anika_woocommerce_wrapper_start() {
    echo '<div class="woocommerce-wrapper container" style="padding: 20px 15px;">';
}
add_action('woocommerce_before_main_content', 'alam_al_anika_woocommerce_wrapper_start', 5);

function alam_al_anika_woocommerce_wrapper_end() {
    echo '</div>';
}
add_action('woocommerce_after_main_content', 'alam_al_anika_woocommerce_wrapper_end', 20);

/**
 * Remove default WooCommerce wrappers.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Remove default WooCommerce sidebar.
 * We will add our own in the archive-product.php template.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );