<?php
/**
 * Include WooCommerce Enhanced Functions
 */

// Include the enhanced WooCommerce functionality
require_once get_template_directory() . '/inc/woocommerce-enhanced.php';

/**
 * Enqueue WooCommerce Enhanced Scripts and Styles
 */
function al_anika_woocommerce_enhanced_scripts() {
    if ( class_exists( 'WooCommerce' ) ) {
        // Enhanced WooCommerce CSS
        wp_enqueue_style(
            'al-anika-woocommerce-enhanced',
            get_template_directory_uri() . '/assets/css/woocommerce-enhanced.css',
            array( 'woocommerce-general' ),
            AL_ANIKA_VERSION
        );
        
        // Enhanced WooCommerce JavaScript
        wp_enqueue_script(
            'al-anika-woocommerce-enhanced',
            get_template_directory_uri() . '/assets/js/woocommerce-enhanced.js',
            array( 'jquery', 'woocommerce' ),
            AL_ANIKA_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script( 'al-anika-woocommerce-enhanced', 'alAnikaWoo', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'al_anika_woo_nonce' ),
            'addedToCartText' => __( 'Added to cart successfully!', 'alam-al-anika' ),
            'sizeGuideTitle' => __( 'Size Guide', 'alam-al-anika' ),
            'shareTitle' => __( 'Share this product', 'alam-al-anika' ),
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'al_anika_woocommerce_enhanced_scripts' );

/**
 * Add WooCommerce support to theme functions.php
 */
function al_anika_add_woocommerce_support() {
    // Add WooCommerce support
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 320,
        'single_image_width'    => 640,
        'product_grid'          => array(
            'default_rows'    => 4,
            'min_rows'        => 1,
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ),
    ) );
    
    // Add support for WC features
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'al_anika_add_woocommerce_support' );

/**
 * Remove default WooCommerce wrappers
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Add custom WooCommerce wrappers
 */
function al_anika_woocommerce_wrapper_start() {
    echo '<div id="primary" class="content-area"><main id="main" class="site-main al-anika-woo-main">';
}
add_action( 'woocommerce_before_main_content', 'al_anika_woocommerce_wrapper_start', 10 );

function al_anika_woocommerce_wrapper_end() {
    echo '</main></div>';
}
add_action( 'woocommerce_after_main_content', 'al_anika_woocommerce_wrapper_end', 10 );

/**
 * Custom WooCommerce breadcrumb arguments
 */
function al_anika_woocommerce_breadcrumbs() {
    return array(
        'delimiter'   => ' <i class="fas fa-chevron-right"></i> ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb al-anika-breadcrumb">',
        'wrap_after'  => '</nav>',
        'before'      => '',
        'after'       => '',
        'home'        => _x( 'Home', 'breadcrumb', 'alam-al-anika' ),
    );
}
add_filter( 'woocommerce_breadcrumb_defaults', 'al_anika_woocommerce_breadcrumbs' );

/**
 * Customize WooCommerce pagination
 */
function al_anika_woocommerce_pagination_args( $args ) {
    $args['prev_text'] = '<i class="fas fa-chevron-left"></i> ' . __( 'Previous', 'alam-al-anika' );
    $args['next_text'] = __( 'Next', 'alam-al-anika' ) . ' <i class="fas fa-chevron-right"></i>';
    return $args;
}
add_filter( 'woocommerce_pagination_args', 'al_anika_woocommerce_pagination_args' );

/**
 * Change number of products per row
 */
function al_anika_woocommerce_loop_columns() {
    return 4; // 4 products per row
}
add_filter( 'loop_shop_columns', 'al_anika_woocommerce_loop_columns' );

/**
 * Change number of products per page
 */
function al_anika_woocommerce_products_per_page() {
    return 16; // 16 products per page
}
add_filter( 'loop_shop_per_page', 'al_anika_woocommerce_products_per_page', 20 );

/**
 * Custom add to cart text
 */
function al_anika_custom_add_to_cart_text( $text, $product ) {
    if ( $product->get_type() === 'simple' && $product->is_purchasable() && $product->is_in_stock() ) {
        $text = __( 'Add to Cart', 'alam-al-anika' );
    } else if ( $product->get_type() === 'variable' ) {
        $text = __( 'Select Options', 'alam-al-anika' );
    } else if ( $product->get_type() === 'grouped' ) {
        $text = __( 'View Products', 'alam-al-anika' );
    } else if ( $product->get_type() === 'external' ) {
        $text = __( 'Buy Now', 'alam-al-anika' );
    } else if ( ! $product->is_in_stock() ) {
        $text = __( 'Out of Stock', 'alam-al-anika' );
    }
    return $text;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'al_anika_custom_add_to_cart_text', 10, 2 );

/**
 * Remove default product loop hooks and add custom ones
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

/**
 * Custom product loop content - use our enhanced content-product.php template
 */

/**
 * Add custom image sizes for cart
 */
function al_anika_add_cart_image_size() {
    add_image_size( 'al-anika-cart-thumb', 80, 100, true );
}
add_action( 'after_setup_theme', 'al_anika_add_cart_image_size' );

/**
 * Custom cart item thumbnail size
 */
function al_anika_cart_item_thumbnail( $image, $cart_item, $cart_item_key ) {
    if ( ! empty( $cart_item['data'] ) ) {
        $product = $cart_item['data'];
        if ( $product && $product->exists() && has_post_thumbnail( $product->get_id() ) ) {
            $image = get_the_post_thumbnail( $product->get_id(), 'al-anika-cart-thumb' );
        }
    }
    return $image;
}
add_filter( 'woocommerce_cart_item_thumbnail', 'al_anika_cart_item_thumbnail', 10, 3 );

/**
 * Ensure AJAX add to cart works for variable products
 */
function al_anika_add_to_cart_script() {
    if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
        wp_enqueue_script( 'wc-add-to-cart-variation' );
    }
}
add_action( 'wp_enqueue_scripts', 'al_anika_add_to_cart_script' );

/**
 * Custom single product summary hooks
 */
function al_anika_custom_single_product_summary() {
    // Remove default hooks
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
    
    // Add custom hooks
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    add_action( 'woocommerce_single_product_summary', 'al_anika_single_product_rating_price', 10 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 15 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 20 );
    add_action( 'woocommerce_single_product_summary', 'al_anika_single_product_meta_custom', 25 );
}
add_action( 'init', 'al_anika_custom_single_product_summary' );

/**
 * Custom rating and price display
 */
function al_anika_single_product_rating_price() {
    global $product;
    
    echo '<div class="al-anika-rating-price-wrapper">';
    
    // Rating
    if ( wc_review_ratings_enabled() ) {
        echo '<div class="product-rating-single">';
        echo wc_get_rating_html( $product->get_average_rating() );
        echo '<span class="rating-count">(' . $product->get_review_count() . ' ' . __( 'reviews', 'alam-al-anika' ) . ')</span>';
        echo '</div>';
    }
    
    // Price
    echo '<div class="product-price-single">';
    echo $product->get_price_html();
    echo '</div>';
    
    echo '</div>';
}

/**
 * Custom product meta display
 */
function al_anika_single_product_meta_custom() {
    global $product;
    
    echo '<div class="al-anika-product-meta">';
    
    // SKU
    if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) {
        echo '<div class="meta-item sku">';
        echo '<span class="meta-label">' . __( 'SKU:', 'alam-al-anika' ) . '</span>';
        echo '<span class="meta-value">' . ( ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'alam-al-anika' ) ) . '</span>';
        echo '</div>';
    }
    
    // Categories
    echo '<div class="meta-item categories">';
    echo '<span class="meta-label">' . __( 'Categories:', 'alam-al-anika' ) . '</span>';
    echo '<span class="meta-value">' . wc_get_product_category_list( $product->get_id(), ', ' ) . '</span>';
    echo '</div>';
    
    // Tags
    $tags = wc_get_product_tag_list( $product->get_id(), ', ' );
    if ( $tags ) {
        echo '<div class="meta-item tags">';
        echo '<span class="meta-label">' . __( 'Tags:', 'alam-al-anika' ) . '</span>';
        echo '<span class="meta-value">' . $tags . '</span>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Add custom checkout fields
 */
function al_anika_custom_checkout_fields( $fields ) {
    // Add delivery instructions field
    $fields['billing']['billing_delivery_instructions'] = array(
        'label'       => __( 'Delivery Instructions', 'alam-al-anika' ),
        'placeholder' => __( 'Special delivery instructions (optional)', 'alam-al-anika' ),
        'required'    => false,
        'type'        => 'textarea',
        'class'       => array( 'form-row-wide' ),
        'priority'    => 100,
    );
    
    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'al_anika_custom_checkout_fields' );

/**
 * Save custom checkout fields
 */
function al_anika_save_custom_checkout_fields( $order_id ) {
    if ( ! empty( $_POST['billing_delivery_instructions'] ) ) {
        update_post_meta( $order_id, 'delivery_instructions', sanitize_textarea_field( $_POST['billing_delivery_instructions'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'al_anika_save_custom_checkout_fields' );

/**
 * Display custom fields in admin order details
 */
function al_anika_display_custom_fields_admin( $order ) {
    $delivery_instructions = get_post_meta( $order->get_id(), 'delivery_instructions', true );
    
    if ( $delivery_instructions ) {
        echo '<div class="custom-field-section">';
        echo '<h3>' . __( 'Delivery Instructions', 'alam-al-anika' ) . '</h3>';
        echo '<p>' . esc_html( $delivery_instructions ) . '</p>';
        echo '</div>';
    }
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'al_anika_display_custom_fields_admin' );

/**
 * Add body classes for WooCommerce pages
 */
function al_anika_woocommerce_body_classes( $classes ) {
    if ( is_woocommerce() ) {
        $classes[] = 'al-anika-woocommerce';
        
        if ( is_shop() ) {
            $classes[] = 'al-anika-shop';
        }
        
        if ( is_product() ) {
            $classes[] = 'al-anika-single-product-page';
        }
        
        if ( is_cart() ) {
            $classes[] = 'al-anika-cart-page';
        }
        
        if ( is_checkout() ) {
            $classes[] = 'al-anika-checkout-page';
        }
    }
    
    return $classes;
}
add_filter( 'body_class', 'al_anika_woocommerce_body_classes' );
