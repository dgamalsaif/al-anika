<?php
/**
 * Smart Category Navigation AJAX Functions
 * Phase 3 Enhancement - AJAX handlers for dynamic content loading
 *
 * @package AlamAlAnika
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Load products for a specific category via AJAX
 */
function alam_al_anika_load_category_products() {
    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'alam_al_anika_ajax_nonce' ) ) {
        wp_die( 'Security check failed' );
    }

    $category_id = intval( $_POST['category_id'] );
    $limit = isset( $_POST['limit'] ) ? intval( $_POST['limit'] ) : 4;

    if ( ! $category_id ) {
        wp_send_json_error( 'Invalid category ID' );
    }

    // Get products from this category
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'meta_query'     => array(
            array(
                'key'     => '_visibility',
                'value'   => array( 'catalog', 'visible' ),
                'compare' => 'IN'
            )
        ),
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $category_id,
            )
        ),
        'orderby'        => 'menu_order title',
        'order'          => 'ASC'
    );

    $products_query = new WP_Query( $args );
    $products = array();

    if ( $products_query->have_posts() ) {
        while ( $products_query->have_posts() ) {
            $products_query->the_post();
            
            $product = wc_get_product( get_the_ID() );
            if ( ! $product ) continue;

            $image_id = $product->get_image_id();
            $image_url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
            
            if ( ! $image_url ) {
                $image_url = wc_placeholder_img_src( 'woocommerce_thumbnail' );
            }

            $products[] = array(
                'id'        => $product->get_id(),
                'title'     => $product->get_name(),
                'permalink' => $product->get_permalink(),
                'price'     => $product->get_price_html(),
                'image'     => $image_url,
                'rating'    => $product->get_average_rating()
            );
        }
        wp_reset_postdata();
    }

    if ( empty( $products ) ) {
        wp_send_json_error( 'No products found' );
    }

    wp_send_json_success( array(
        'products' => $products,
        'count'    => count( $products )
    ) );
}
add_action( 'wp_ajax_load_category_products', 'alam_al_anika_load_category_products' );
add_action( 'wp_ajax_nopriv_load_category_products', 'alam_al_anika_load_category_products' );

/**
 * Filter categories by product type (sale, new, featured)
 */
function alam_al_anika_filter_categories_by_product_type() {
    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'alam_al_anika_ajax_nonce' ) ) {
        wp_die( 'Security check failed' );
    }

    $product_type = sanitize_text_field( $_POST['product_type'] );
    
    if ( ! in_array( $product_type, array( 'sale', 'new', 'featured' ) ) ) {
        wp_send_json_error( 'Invalid product type' );
    }

    $category_ids = array();

    switch ( $product_type ) {
        case 'sale':
            $category_ids = alam_al_anika_get_categories_with_sale_products();
            break;
        case 'new':
            $category_ids = alam_al_anika_get_categories_with_new_products();
            break;
        case 'featured':
            $category_ids = alam_al_anika_get_categories_with_featured_products();
            break;
    }

    wp_send_json_success( array(
        'category_ids' => $category_ids,
        'count'        => count( $category_ids )
    ) );
}
add_action( 'wp_ajax_filter_categories_by_product_type', 'alam_al_anika_filter_categories_by_product_type' );
add_action( 'wp_ajax_nopriv_filter_categories_by_product_type', 'alam_al_anika_filter_categories_by_product_type' );

/**
 * Search categories by name
 */
function alam_al_anika_search_categories() {
    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'alam_al_anika_ajax_nonce' ) ) {
        wp_die( 'Security check failed' );
    }

    $query = sanitize_text_field( $_POST['query'] );
    
    if ( strlen( $query ) < 2 ) {
        wp_send_json_error( 'Query too short' );
    }

    // Search categories by name
    $categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'search'     => $query,
        'number'     => 20
    ) );

    $category_ids = array();

    if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
        foreach ( $categories as $category ) {
            $category_ids[] = $category->term_id;
        }
    }

    wp_send_json_success( array(
        'category_ids' => $category_ids,
        'count'        => count( $category_ids )
    ) );
}
add_action( 'wp_ajax_search_categories', 'alam_al_anika_search_categories' );
add_action( 'wp_ajax_nopriv_search_categories', 'alam_al_anika_search_categories' );

/**
 * Get categories that have products on sale
 */
function alam_al_anika_get_categories_with_sale_products() {
    global $wpdb;

    $sale_product_ids = wc_get_product_ids_on_sale();
    
    if ( empty( $sale_product_ids ) ) {
        return array();
    }

    $product_ids_string = implode( ',', array_map( 'intval', $sale_product_ids ) );

    $category_ids = $wpdb->get_col( "
        SELECT DISTINCT tr.term_taxonomy_id 
        FROM {$wpdb->term_relationships} tr
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_id
        WHERE tr.object_id IN ({$product_ids_string})
        AND tt.taxonomy = 'product_cat'
    " );

    return array_map( 'intval', $category_ids );
}

/**
 * Get categories that have new products (created in last 30 days)
 */
function alam_al_anika_get_categories_with_new_products() {
    global $wpdb;

    $date_30_days_ago = date( 'Y-m-d H:i:s', strtotime( '-30 days' ) );

    $new_product_ids = $wpdb->get_col( $wpdb->prepare( "
        SELECT ID 
        FROM {$wpdb->posts} 
        WHERE post_type = 'product' 
        AND post_status = 'publish'
        AND post_date >= %s
    ", $date_30_days_ago ) );

    if ( empty( $new_product_ids ) ) {
        return array();
    }

    $product_ids_string = implode( ',', array_map( 'intval', $new_product_ids ) );

    $category_ids = $wpdb->get_col( "
        SELECT DISTINCT tr.term_taxonomy_id 
        FROM {$wpdb->term_relationships} tr
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_id
        WHERE tr.object_id IN ({$product_ids_string})
        AND tt.taxonomy = 'product_cat'
    " );

    return array_map( 'intval', $category_ids );
}

/**
 * Get categories that have featured products
 */
function alam_al_anika_get_categories_with_featured_products() {
    global $wpdb;

    $featured_product_ids = wc_get_featured_product_ids();
    
    if ( empty( $featured_product_ids ) ) {
        return array();
    }

    $product_ids_string = implode( ',', array_map( 'intval', $featured_product_ids ) );

    $category_ids = $wpdb->get_col( "
        SELECT DISTINCT tr.term_taxonomy_id 
        FROM {$wpdb->term_relationships} tr
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_id
        WHERE tr.object_id IN ({$product_ids_string})
        AND tt.taxonomy = 'product_cat'
    " );

    return array_map( 'intval', $category_ids );
}

/**
 * Enqueue AJAX script with localized data
 */
function alam_al_anika_enqueue_category_nav_ajax() {
    if ( is_front_page() || is_shop() || is_product_category() ) {
        wp_localize_script( 'alam-al-anika-smart-category-nav', 'alamAlAnikaAjax', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'alam_al_anika_ajax_nonce' )
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'alam_al_anika_enqueue_category_nav_ajax' );

/**
 * Get popular search terms for category search suggestions
 */
function alam_al_anika_get_category_search_suggestions() {
    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'alam_al_anika_ajax_nonce' ) ) {
        wp_die( 'Security check failed' );
    }

    $query = sanitize_text_field( $_POST['query'] );
    
    if ( strlen( $query ) < 1 ) {
        wp_send_json_error( 'Query too short' );
    }

    // Get categories that match the query
    $categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'search'     => $query,
        'number'     => 5,
        'orderby'    => 'count',
        'order'      => 'DESC'
    ) );

    $suggestions = array();

    if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
        foreach ( $categories as $category ) {
            $suggestions[] = array(
                'id'    => $category->term_id,
                'name'  => $category->name,
                'slug'  => $category->slug,
                'count' => $category->count,
                'url'   => get_term_link( $category )
            );
        }
    }

    wp_send_json_success( array(
        'suggestions' => $suggestions,
        'count'       => count( $suggestions )
    ) );
}
add_action( 'wp_ajax_get_category_search_suggestions', 'alam_al_anika_get_category_search_suggestions' );
add_action( 'wp_ajax_nopriv_get_category_search_suggestions', 'alam_al_anika_get_category_search_suggestions' );

/**
 * Smart category recommendation based on user behavior
 */
function alam_al_anika_get_recommended_categories() {
    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'alam_al_anika_ajax_nonce' ) ) {
        wp_die( 'Security check failed' );
    }

    $user_id = get_current_user_id();
    $recommended_categories = array();

    if ( $user_id ) {
        // Get user's order history
        $customer_orders = wc_get_orders( array(
            'customer' => $user_id,
            'status'   => array( 'wc-completed', 'wc-processing' ),
            'limit'    => 10
        ) );

        $purchased_product_ids = array();
        
        foreach ( $customer_orders as $order ) {
            foreach ( $order->get_items() as $item ) {
                $purchased_product_ids[] = $item->get_product_id();
            }
        }

        if ( ! empty( $purchased_product_ids ) ) {
            // Get categories from purchased products
            $purchased_categories = wp_get_object_terms( 
                $purchased_product_ids, 
                'product_cat', 
                array( 'fields' => 'ids' )
            );

            if ( ! is_wp_error( $purchased_categories ) ) {
                $recommended_categories = array_slice( array_unique( $purchased_categories ), 0, 6 );
            }
        }
    }

    // Fallback to popular categories if no user history
    if ( empty( $recommended_categories ) ) {
        $popular_categories = get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'number'     => 6,
            'orderby'    => 'count',
            'order'      => 'DESC'
        ) );

        if ( ! is_wp_error( $popular_categories ) ) {
            $recommended_categories = wp_list_pluck( $popular_categories, 'term_id' );
        }
    }

    wp_send_json_success( array(
        'category_ids' => $recommended_categories,
        'count'        => count( $recommended_categories )
    ) );
}
add_action( 'wp_ajax_get_recommended_categories', 'alam_al_anika_get_recommended_categories' );
add_action( 'wp_ajax_nopriv_get_recommended_categories', 'alam_al_anika_get_recommended_categories' );

/**
 * Track category interaction for analytics
 */
function alam_al_anika_track_category_interaction() {
    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'alam_al_anika_ajax_nonce' ) ) {
        wp_die( 'Security check failed' );
    }

    $category_id = intval( $_POST['category_id'] );
    $interaction_type = sanitize_text_field( $_POST['interaction_type'] ); // 'hover', 'click', 'view'
    
    if ( ! $category_id || ! in_array( $interaction_type, array( 'hover', 'click', 'view' ) ) ) {
        wp_send_json_error( 'Invalid parameters' );
    }

    // Store interaction data (you can extend this for analytics)
    $interaction_data = array(
        'category_id'      => $category_id,
        'interaction_type' => $interaction_type,
        'timestamp'        => current_time( 'mysql' ),
        'user_id'          => get_current_user_id(),
        'ip_address'       => $_SERVER['REMOTE_ADDR'],
        'user_agent'       => $_SERVER['HTTP_USER_AGENT']
    );

    // Save to transient for temporary storage (or use custom table)
    $transient_key = 'category_interactions_' . date( 'Y_m_d' );
    $existing_data = get_transient( $transient_key );
    
    if ( ! $existing_data ) {
        $existing_data = array();
    }
    
    $existing_data[] = $interaction_data;
    set_transient( $transient_key, $existing_data, DAY_IN_SECONDS );

    wp_send_json_success( array(
        'message' => 'Interaction tracked successfully'
    ) );
}
add_action( 'wp_ajax_track_category_interaction', 'alam_al_anika_track_category_interaction' );
add_action( 'wp_ajax_nopriv_track_category_interaction', 'alam_al_anika_track_category_interaction' );

/**
 * Get category analytics data for admin dashboard
 */
function alam_al_anika_get_category_analytics() {
    // Verify nonce and admin capability
    if ( ! wp_verify_nonce( $_POST['nonce'], 'alam_al_anika_ajax_nonce' ) || ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Security check failed' );
    }

    $date_range = sanitize_text_field( $_POST['date_range'] ); // 'today', 'week', 'month'
    
    $analytics_data = array();
    
    switch ( $date_range ) {
        case 'today':
            $transient_key = 'category_interactions_' . date( 'Y_m_d' );
            break;
        case 'week':
            // Aggregate last 7 days
            for ( $i = 0; $i < 7; $i++ ) {
                $date = date( 'Y_m_d', strtotime( "-{$i} days" ) );
                $daily_data = get_transient( 'category_interactions_' . $date );
                if ( $daily_data ) {
                    $analytics_data = array_merge( $analytics_data, $daily_data );
                }
            }
            break;
        case 'month':
            // Aggregate last 30 days
            for ( $i = 0; $i < 30; $i++ ) {
                $date = date( 'Y_m_d', strtotime( "-{$i} days" ) );
                $daily_data = get_transient( 'category_interactions_' . $date );
                if ( $daily_data ) {
                    $analytics_data = array_merge( $analytics_data, $daily_data );
                }
            }
            break;
        default:
            $analytics_data = get_transient( 'category_interactions_' . date( 'Y_m_d' ) );
            break;
    }

    // Process analytics data
    $processed_data = alam_al_anika_process_category_analytics( $analytics_data );

    wp_send_json_success( array(
        'analytics' => $processed_data,
        'date_range' => $date_range
    ) );
}
add_action( 'wp_ajax_get_category_analytics', 'alam_al_anika_get_category_analytics' );

/**
 * Process raw analytics data into useful metrics
 */
function alam_al_anika_process_category_analytics( $raw_data ) {
    if ( empty( $raw_data ) ) {
        return array();
    }

    $processed = array(
        'most_viewed_categories' => array(),
        'most_clicked_categories' => array(),
        'interaction_timeline' => array(),
        'total_interactions' => count( $raw_data )
    );

    $category_views = array();
    $category_clicks = array();
    $hourly_interactions = array();

    foreach ( $raw_data as $interaction ) {
        $category_id = $interaction['category_id'];
        $type = $interaction['interaction_type'];
        $hour = date( 'H', strtotime( $interaction['timestamp'] ) );

        // Count by category and type
        if ( $type === 'view' || $type === 'hover' ) {
            $category_views[ $category_id ] = isset( $category_views[ $category_id ] ) ? $category_views[ $category_id ] + 1 : 1;
        } elseif ( $type === 'click' ) {
            $category_clicks[ $category_id ] = isset( $category_clicks[ $category_id ] ) ? $category_clicks[ $category_id ] + 1 : 1;
        }

        // Count by hour
        $hourly_interactions[ $hour ] = isset( $hourly_interactions[ $hour ] ) ? $hourly_interactions[ $hour ] + 1 : 1;
    }

    // Sort and get top categories
    arsort( $category_views );
    arsort( $category_clicks );

    $processed['most_viewed_categories'] = array_slice( $category_views, 0, 10, true );
    $processed['most_clicked_categories'] = array_slice( $category_clicks, 0, 10, true );
    $processed['interaction_timeline'] = $hourly_interactions;

    return $processed;
}
