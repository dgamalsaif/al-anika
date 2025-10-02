<?php
/**
 * Advanced Header Functions
 * PHP functionality for the professional header
 *
 * @package AlamAlAnika
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Header Functions Class
 */
class Alam_Al_Anika_Header_Functions {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_header_assets' ) );
        add_action( 'wp_ajax_alam_al_anika_search_suggestions', array( $this, 'ajax_search_suggestions' ) );
        add_action( 'wp_ajax_nopriv_alam_al_anika_search_suggestions', array( $this, 'ajax_search_suggestions' ) );
        add_action( 'wp_ajax_alam_al_anika_get_cart_count', array( $this, 'ajax_get_cart_count' ) );
        add_action( 'wp_ajax_nopriv_alam_al_anika_get_cart_count', array( $this, 'ajax_get_cart_count' ) );
        add_action( 'wp_footer', array( $this, 'localize_header_script' ) );
    }

    /**
     * Enqueue header assets
     */
    public function enqueue_header_assets() {
        // Enqueue header CSS
        wp_enqueue_style( 
            'alam-al-anika-header', 
            get_template_directory_uri() . '/assets/css/header-advanced.css', 
            array(), 
            AL_ANIKA_VERSION 
        );

        // Enqueue header JavaScript
        wp_enqueue_script( 
            'alam-al-anika-header', 
            get_template_directory_uri() . '/assets/js/header-advanced.js', 
            array( 'jquery' ), 
            AL_ANIKA_VERSION, 
            true 
        );

        // Enqueue Font Awesome
        wp_enqueue_style( 
            'font-awesome', 
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', 
            array(), 
            '6.0.0' 
        );

        // Enqueue Google Fonts
        $logo_font = get_theme_mod( 'logo_font_family', 'Tajawal' );
        $google_fonts_url = $this->get_google_fonts_url( $logo_font );
        
        if ( $google_fonts_url ) {
            wp_enqueue_style( 
                'alam-al-anika-google-fonts', 
                $google_fonts_url, 
                array(), 
                null 
            );
        }
    }

    /**
     * Get Google Fonts URL
     */
    private function get_google_fonts_url( $font_family ) {
        $font_families = array();
        
        switch ( $font_family ) {
            case 'Tajawal':
                $font_families[] = 'Tajawal:300,400,500,600,700,800';
                break;
            case 'Cairo':
                $font_families[] = 'Cairo:300,400,500,600,700,800';
                break;
            case 'Inter':
                $font_families[] = 'Inter:300,400,500,600,700,800';
                break;
            case 'Roboto':
                $font_families[] = 'Roboto:300,400,500,700';
                break;
            case 'Open Sans':
                $font_families[] = 'Open+Sans:300,400,600,700';
                break;
            case 'Montserrat':
                $font_families[] = 'Montserrat:300,400,500,600,700';
                break;
        }

        // Always include Inter for English text
        if ( $font_family !== 'Inter' ) {
            $font_families[] = 'Inter:300,400,500,600,700';
        }

        if ( empty( $font_families ) ) {
            return false;
        }

        $query_args = array(
            'family' => implode( '|', $font_families ),
            'subset' => 'latin,arabic',
            'display' => 'swap',
        );

        return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    /**
     * AJAX Search Suggestions
     */
    public function ajax_search_suggestions() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'alam_al_anika_search_nonce' ) ) {
            wp_die( 'Security check failed' );
        }

        $query = sanitize_text_field( $_POST['query'] );
        $suggestions = array();

        if ( strlen( $query ) < 2 ) {
            wp_send_json_error( 'Query too short' );
        }

        // Search products
        if ( class_exists( 'WooCommerce' ) ) {
            $product_suggestions = $this->search_products( $query );
            $suggestions = array_merge( $suggestions, $product_suggestions );
        }

        // Search posts/pages
        $content_suggestions = $this->search_content( $query );
        $suggestions = array_merge( $suggestions, $content_suggestions );

        // Limit results
        $suggestions = array_slice( $suggestions, 0, 8 );

        wp_send_json_success( $suggestions );
    }

    /**
     * Search WooCommerce products
     */
    private function search_products( $query ) {
        $suggestions = array();

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            's' => $query,
            'posts_per_page' => 5,
            'meta_query' => array(
                array(
                    'key' => '_visibility',
                    'value' => array( 'catalog', 'visible' ),
                    'compare' => 'IN'
                )
            )
        );

        $products = new WP_Query( $args );

        if ( $products->have_posts() ) {
            while ( $products->have_posts() ) {
                $products->the_post();
                $product = wc_get_product( get_the_ID() );
                
                if ( $product ) {
                    $suggestions[] = array(
                        'title' => get_the_title(),
                        'url' => get_permalink(),
                        'image' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
                        'price' => $product->get_price_html(),
                        'type' => 'product'
                    );
                }
            }
            wp_reset_postdata();
        }

        return $suggestions;
    }

    /**
     * Search content (posts/pages)
     */
    private function search_content( $query ) {
        $suggestions = array();

        $args = array(
            'post_type' => array( 'post', 'page' ),
            'post_status' => 'publish',
            's' => $query,
            'posts_per_page' => 3
        );

        $posts = new WP_Query( $args );

        if ( $posts->have_posts() ) {
            while ( $posts->have_posts() ) {
                $posts->the_post();
                
                $suggestions[] = array(
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'image' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
                    'price' => null,
                    'type' => get_post_type()
                );
            }
            wp_reset_postdata();
        }

        return $suggestions;
    }

    /**
     * AJAX Get Cart Count
     */
    public function ajax_get_cart_count() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'alam_al_anika_cart_nonce' ) ) {
            wp_die( 'Security check failed' );
        }

        if ( ! class_exists( 'WooCommerce' ) ) {
            wp_send_json_error( 'WooCommerce not active' );
        }

        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_total = WC()->cart->get_cart_subtotal();

        wp_send_json_success( array(
            'count' => $cart_count,
            'total' => $cart_total
        ) );
    }

    /**
     * Localize header script
     */
    public function localize_header_script() {
        if ( wp_script_is( 'alam-al-anika-header', 'enqueued' ) ) {
            wp_localize_script( 'alam-al-anika-header', 'alamAlAnikaHeader', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'search_nonce' => wp_create_nonce( 'alam_al_anika_search_nonce' ),
                'cart_nonce' => wp_create_nonce( 'alam_al_anika_cart_nonce' ),
                'currency_symbol' => get_woocommerce_currency_symbol(),
                'language' => get_locale(),
                'rtl' => is_rtl(),
                'strings' => array(
                    'search_placeholder' => __( 'Search for products...', 'alam-al-anika' ),
                    'no_results' => __( 'No results found', 'alam-al-anika' ),
                    'searching' => __( 'Searching...', 'alam-al-anika' ),
                    'view_all' => __( 'View all results', 'alam-al-anika' ),
                )
            ) );
        }
    }
}

// Initialize the header functions
new Alam_Al_Anika_Header_Functions();

/**
 * Currency and Language Helper Functions
 */

/**
 * Get available currencies
 */
function alam_al_anika_get_currencies() {
    return array(
        'USD' => array(
            'name' => __( 'US Dollar', 'alam-al-anika' ),
            'symbol' => '$',
            'rate' => 1.0
        ),
        'SAR' => array(
            'name' => __( 'Saudi Riyal', 'alam-al-anika' ),
            'symbol' => 'ر.س',
            'rate' => 3.75
        ),
        'YER' => array(
            'name' => __( 'Yemeni Rial', 'alam-al-anika' ),
            'symbol' => 'ر.ي',
            'rate' => 250.0
        )
    );
}

/**
 * Get available languages
 */
function alam_al_anika_get_languages() {
    return array(
        'ar' => array(
            'name' => __( 'Arabic', 'alam-al-anika' ),
            'native_name' => 'العربية',
            'flag' => 'sa'
        ),
        'en' => array(
            'name' => __( 'English', 'alam-al-anika' ),
            'native_name' => 'English',
            'flag' => 'us'
        )
    );
}

/**
 * Convert price to selected currency
 */
function alam_al_anika_convert_price( $price, $from_currency = 'USD', $to_currency = 'USD' ) {
    $currencies = alam_al_anika_get_currencies();
    
    if ( ! isset( $currencies[ $from_currency ] ) || ! isset( $currencies[ $to_currency ] ) ) {
        return $price;
    }

    $from_rate = $currencies[ $from_currency ]['rate'];
    $to_rate = $currencies[ $to_currency ]['rate'];
    
    // Convert to USD first, then to target currency
    $usd_price = $price / $from_rate;
    $converted_price = $usd_price * $to_rate;
    
    return round( $converted_price, 2 );
}

/**
 * Format price with currency symbol
 */
function alam_al_anika_format_price( $price, $currency = 'USD' ) {
    $currencies = alam_al_anika_get_currencies();
    
    if ( ! isset( $currencies[ $currency ] ) ) {
        return $price;
    }

    $symbol = $currencies[ $currency ]['symbol'];
    
    // Format based on currency
    switch ( $currency ) {
        case 'SAR':
        case 'YER':
            return number_format( $price, 2 ) . ' ' . $symbol;
        default:
            return $symbol . number_format( $price, 2 );
    }
}

/**
 * Enqueue customizer preview script
 */
function alam_al_anika_header_customizer_preview_js() {
    wp_enqueue_script(
        'alam-al-anika-header-customizer',
        get_template_directory_uri() . '/assets/js/header-customizer.js',
        array( 'customize-preview' ),
        AL_ANIKA_VERSION,
        true
    );
}
add_action( 'customize_preview_init', 'alam_al_anika_header_customizer_preview_js' );
