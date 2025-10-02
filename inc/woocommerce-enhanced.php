<?php
/**
 * Enhanced WooCommerce Functions for Al-Anika Theme
 * Alam Al Anika functionality with professional improvements
 *
 * @package AlamAlAnika/WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enhanced WooCommerce Setup
 */
function al_anika_enhanced_woocommerce_setup() {
    // Declare WooCommerce support with advanced options
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 320,
        'single_image_width'    => 640,
        'gallery_thumbnail_image_width' => 120,
        'product_grid' => array(
            'default_rows'    => 4,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 6,
        ),
    ) );
    
    // Enhanced gallery features
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    
    // Alam Al Anika image sizes
    add_image_size( 'al-anika-product-thumb', 320, 400, true );     // Product cards
    add_image_size( 'al-anika-product-single', 640, 800, true );    // Single product
    add_image_size( 'al-anika-product-gallery', 120, 150, true );   // Gallery thumbs
    add_image_size( 'al-anika-cart-thumb', 80, 100, true );         // Cart thumbnails
    add_image_size( 'al-anika-hero-banner', 1200, 500, true );      // Hero banners
    add_image_size( 'al-anika-category-banner', 800, 300, true );   // Category banners
}
add_action( 'after_setup_theme', 'al_anika_enhanced_woocommerce_setup' );

/**
 * Enhanced Product Badges (Alam Al Anika style)
 */
function al_anika_product_badges() {
    global $product;
    
    if ( ! $product ) return;
    
    echo '<div class="al-anika-product-badges">';
    
    // Sale badge with percentage
    if ( $product->is_on_sale() ) {
        $percentage = '';
        if ( $product->get_regular_price() && $product->get_sale_price() ) {
            $percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
        }
        if ( $percentage ) {
            echo '<span class="badge badge-sale">-' . $percentage . '%</span>';
        } else {
            echo '<span class="badge badge-sale">' . esc_html__( 'Sale', 'alam-al-anika' ) . '</span>';
        }
    }
    
    // New product badge (within 30 days)
    $created = strtotime( $product->get_date_created() );
    if ( $created > strtotime( '-30 days' ) ) {
        echo '<span class="badge badge-new">' . esc_html__( 'New', 'alam-al-anika' ) . '</span>';
    }
    
    // Limited stock badge
    if ( $product->is_in_stock() && $product->get_stock_quantity() && $product->get_stock_quantity() <= 5 ) {
        echo '<span class="badge badge-limited">' . esc_html__( 'Limited', 'alam-al-anika' ) . '</span>';
    }
    
    // Hot badge (based on sales)
    $sales_count = get_post_meta( $product->get_id(), 'total_sales', true );
    if ( $sales_count && $sales_count > 100 ) {
        echo '<span class="badge badge-hot">' . esc_html__( 'Hot', 'alam-al-anika' ) . '</span>';
    }
    
    // Eco-friendly badge
    if ( has_term( 'eco-friendly', 'product_tag', $product->get_id() ) ) {
        echo '<span class="badge badge-eco"><i class="fas fa-leaf"></i></span>';
    }
    
    echo '</div>';
}

/**
 * Show Variation Swatches (Color/Size preview)
 */
function al_anika_show_variation_swatches( $product, $limit = 5 ) {
    if ( ! $product->is_type( 'variable' ) ) return;
    
    $variations = $product->get_available_variations();
    $attributes = $product->get_variation_attributes();
    
    echo '<div class="variation-swatches">';
    
    foreach ( $attributes as $attribute_name => $options ) {
        if ( strpos( $attribute_name, 'color' ) !== false ) {
            echo '<div class="color-swatches">';
            $count = 0;
            foreach ( $options as $option ) {
                if ( $count >= $limit ) {
                    $remaining = count( $options ) - $limit;
                    echo '<span class="more-colors">+' . $remaining . '</span>';
                    break;
                }
                $color_code = get_term_meta( get_term_by( 'slug', $option, $attribute_name )->term_id, 'color_code', true );
                if ( $color_code ) {
                    echo '<span class="color-swatch" style="background-color: ' . esc_attr( $color_code ) . ';" title="' . esc_attr( $option ) . '"></span>';
                } else {
                    echo '<span class="color-swatch color-text" title="' . esc_attr( $option ) . '">' . esc_html( substr( $option, 0, 2 ) ) . '</span>';
                }
                $count++;
            }
            echo '</div>';
        }
    }
    
    echo '</div>';
}

/**
 * Enhanced Add to Cart Button
 */
function al_anika_custom_add_to_cart_button() {
    global $product;
    
    if ( ! $product ) return;
    
    $button_text = $product->is_type( 'simple' ) ? __( 'Add to Cart', 'alam-al-anika' ) : __( 'Select Options', 'alam-al-anika' );
    $button_class = 'al-anika-add-to-cart btn-primary';
    
    if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
        echo '<form class="cart" action="' . esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) . '" method="post" enctype="multipart/form-data">';
        echo '<button type="submit" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '" class="' . esc_attr( $button_class ) . '">';
        echo '<i class="fas fa-shopping-cart"></i>';
        echo '<span>' . esc_html( $button_text ) . '</span>';
        echo '</button>';
        echo '</form>';
    } else {
        echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="' . esc_attr( $button_class ) . '">';
        echo '<i class="fas fa-eye"></i>';
        echo '<span>' . esc_html( $button_text ) . '</span>';
        echo '</a>';
    }
}

/**
 * Quick View AJAX Handler
 */
function al_anika_quick_view_ajax() {
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_die();
    }
    
    $product_id = intval( $_POST['product_id'] );
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        wp_die();
    }
    
    // Set global post and product
    global $post, $woocommerce, $product;
    $post = get_post( $product_id );
    setup_postdata( $post );
    
    ob_start();
    ?>
    <div class="al-anika-quick-view-content">
        <div class="quick-view-images">
            <?php
            $image_id = $product->get_image_id();
            if ( $image_id ) {
                echo wp_get_attachment_image( $image_id, 'al-anika-product-single' );
            }
            ?>
        </div>
        <div class="quick-view-details">
            <h3 class="product-title"><?php echo get_the_title(); ?></h3>
            <div class="product-price"><?php echo $product->get_price_html(); ?></div>
            <div class="product-rating">
                <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
            </div>
            <div class="product-description">
                <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
            </div>
            <div class="quick-view-actions">
                <?php woocommerce_template_single_add_to_cart(); ?>
                <a href="<?php echo get_permalink(); ?>" class="btn-view-full">
                    <?php esc_html_e( 'View Full Details', 'alam-al-anika' ); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
    
    $content = ob_get_clean();
    wp_reset_postdata();
    
    wp_send_json_success( $content );
}
add_action( 'wp_ajax_al_anika_quick_view', 'al_anika_quick_view_ajax' );
add_action( 'wp_ajax_nopriv_al_anika_quick_view', 'al_anika_quick_view_ajax' );

/**
 * Wishlist AJAX Handler
 */
function al_anika_wishlist_ajax() {
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( 'Product ID not provided' );
    }
    
    $product_id = intval( $_POST['product_id'] );
    $user_id = get_current_user_id();
    
    if ( ! $user_id ) {
        // For guests, use session
        if ( ! session_id() ) {
            session_start();
        }
        $wishlist = isset( $_SESSION['al_anika_wishlist'] ) ? $_SESSION['al_anika_wishlist'] : array();
        
        if ( in_array( $product_id, $wishlist ) ) {
            $key = array_search( $product_id, $wishlist );
            unset( $wishlist[$key] );
            $action = 'removed';
        } else {
            $wishlist[] = $product_id;
            $action = 'added';
        }
        
        $_SESSION['al_anika_wishlist'] = $wishlist;
    } else {
        // For logged-in users, use user meta
        $wishlist = get_user_meta( $user_id, 'al_anika_wishlist', true ) ?: array();
        
        if ( in_array( $product_id, $wishlist ) ) {
            $key = array_search( $product_id, $wishlist );
            unset( $wishlist[$key] );
            $action = 'removed';
        } else {
            $wishlist[] = $product_id;
            $action = 'added';
        }
        
        update_user_meta( $user_id, 'al_anika_wishlist', array_values( $wishlist ) );
    }
    
    wp_send_json_success( array(
        'action' => $action,
        'count' => count( $wishlist ),
        'message' => $action === 'added' ? __( 'Added to wishlist', 'alam-al-anika' ) : __( 'Removed from wishlist', 'alam-al-anika' )
    ) );
}
add_action( 'wp_ajax_al_anika_wishlist', 'al_anika_wishlist_ajax' );
add_action( 'wp_ajax_nopriv_al_anika_wishlist', 'al_anika_wishlist_ajax' );

/**
 * Get Wishlist Count
 */
function al_anika_get_wishlist_count() {
    $user_id = get_current_user_id();
    
    if ( ! $user_id ) {
        if ( ! session_id() ) {
            session_start();
        }
        $wishlist = isset( $_SESSION['al_anika_wishlist'] ) ? $_SESSION['al_anika_wishlist'] : array();
    } else {
        $wishlist = get_user_meta( $user_id, 'al_anika_wishlist', true ) ?: array();
    }
    
    return count( $wishlist );
}

/**
 * Recently Viewed Products
 */
function al_anika_track_product_view() {
    if ( ! is_singular( 'product' ) ) return;
    
    $product_id = get_the_ID();
    $viewed_products = isset( $_COOKIE['al_anika_recently_viewed'] ) ? 
        explode( ',', $_COOKIE['al_anika_recently_viewed'] ) : array();
    
    // Remove if already exists
    if ( ( $key = array_search( $product_id, $viewed_products ) ) !== false ) {
        unset( $viewed_products[$key] );
    }
    
    // Add to beginning
    array_unshift( $viewed_products, $product_id );
    
    // Limit to 10 products
    $viewed_products = array_slice( $viewed_products, 0, 10 );
    
    // Set cookie for 30 days
    setcookie( 'al_anika_recently_viewed', implode( ',', $viewed_products ), time() + ( 30 * DAY_IN_SECONDS ), '/' );
}
add_action( 'wp_head', 'al_anika_track_product_view' );

/**
 * Show Recently Viewed Products
 */
function al_anika_show_recently_viewed_products() {
    $viewed_products = isset( $_COOKIE['al_anika_recently_viewed'] ) ? 
        explode( ',', $_COOKIE['al_anika_recently_viewed'] ) : array();
    
    if ( empty( $viewed_products ) ) return;
    
    // Remove current product from the list
    if ( is_singular( 'product' ) ) {
        $current_id = get_the_ID();
        $viewed_products = array_diff( $viewed_products, array( $current_id ) );
    }
    
    if ( empty( $viewed_products ) ) return;
    
    echo '<div class="recently-viewed-products">';
    echo '<h3>' . esc_html__( 'Recently Viewed', 'alam-al-anika' ) . '</h3>';
    echo '<div class="products-slider">';
    
    foreach ( array_slice( $viewed_products, 0, 8 ) as $product_id ) {
        $product = wc_get_product( $product_id );
        if ( ! $product || ! $product->is_visible() ) continue;
        
        echo '<div class="recently-viewed-item">';
        echo '<a href="' . esc_url( get_permalink( $product_id ) ) . '">';
        echo $product->get_image( 'al-anika-product-thumb' );
        echo '<h4>' . esc_html( $product->get_name() ) . '</h4>';
        echo '<span class="price">' . $product->get_price_html() . '</span>';
        echo '</a>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Check if product has size guide
 */
function al_anika_has_size_guide( $product ) {
    return get_post_meta( $product->get_id(), '_has_size_guide', true ) === 'yes';
}

/**
 * Single Product Badges
 */
function al_anika_single_product_badges() {
    global $product;
    
    if ( ! $product ) return;
    
    echo '<div class="single-product-badges">';
    
    // Free shipping badge
    if ( al_anika_qualifies_for_free_shipping( $product ) ) {
        echo '<span class="badge badge-shipping"><i class="fas fa-truck"></i> ' . esc_html__( 'Free Shipping', 'alam-al-anika' ) . '</span>';
    }
    
    // Fast delivery badge
    if ( has_term( 'fast-delivery', 'product_tag', $product->get_id() ) ) {
        echo '<span class="badge badge-fast"><i class="fas fa-bolt"></i> ' . esc_html__( 'Fast Delivery', 'alam-al-anika' ) . '</span>';
    }
    
    // Bestseller badge
    if ( has_term( 'bestseller', 'product_tag', $product->get_id() ) ) {
        echo '<span class="badge badge-bestseller"><i class="fas fa-star"></i> ' . esc_html__( 'Bestseller', 'alam-al-anika' ) . '</span>';
    }
    
    echo '</div>';
}

/**
 * Check if product qualifies for free shipping
 */
function al_anika_qualifies_for_free_shipping( $product ) {
    $free_shipping_threshold = get_option( 'al_anika_free_shipping_threshold', 50 );
    return $product->get_price() >= $free_shipping_threshold;
}

/**
 * Custom hooks for product cards
 */
add_action( 'al_anika_before_product_image', 'al_anika_product_badges' );
add_action( 'al_anika_before_product_info', function() {
    echo '<div class="product-info-inner">';
} );
add_action( 'al_anika_after_product_info', function() {
    echo '</div>';
} );
