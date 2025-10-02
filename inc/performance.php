<?php
/**
 * Performance Optimization Functions
 * Advanced performance enhancements for Al-Anika theme
 *
 * @package AlamAlAnika
 * @since 6.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Performance Optimization Class
 */
class Al_Anika_Performance {
    
    public function __construct() {
        add_action( 'init', array( $this, 'init_performance_features' ) );
    }
    
    /**
     * Initialize performance features
     */
    public function init_performance_features() {
        // CSS and JS optimization
        add_action( 'wp_enqueue_scripts', array( $this, 'optimize_scripts_styles' ), 999 );
        
        // Image optimization
        add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_lazy_loading' ), 10, 3 );
        
        // Database optimization
        add_action( 'wp_footer', array( $this, 'optimize_database_queries' ) );
        
        // Caching optimization
        add_action( 'init', array( $this, 'setup_caching' ) );
        
        // Minification
        if ( ! is_admin() && ! wp_is_mobile() ) {
            add_action( 'wp_print_styles', array( $this, 'minify_css' ), 999 );
            add_action( 'wp_print_scripts', array( $this, 'minify_js' ), 999 );
        }
        
        // Critical CSS
        add_action( 'wp_head', array( $this, 'inline_critical_css' ), 1 );
        
        // Preload important resources
        add_action( 'wp_head', array( $this, 'preload_resources' ), 1 );
        
        // DNS prefetch
        add_action( 'wp_head', array( $this, 'dns_prefetch' ), 1 );
    }
    
    /**
     * Optimize scripts and styles loading
     */
    public function optimize_scripts_styles() {
        // Remove unnecessary WordPress defaults
        wp_deregister_script( 'wp-embed' );
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'rsd_link' );
        
        // Defer non-critical JavaScript
        add_filter( 'script_loader_tag', array( $this, 'defer_scripts' ), 10, 3 );
        
        // Async load non-critical CSS
        add_filter( 'style_loader_tag', array( $this, 'async_css' ), 10, 4 );
    }
    
    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading( $attr, $attachment, $size ) {
        // Skip if it's in admin or already has loading attribute
        if ( is_admin() || isset( $attr['loading'] ) ) {
            return $attr;
        }
        
        // Add loading="lazy" for better performance
        $attr['loading'] = 'lazy';
        
        // Add decoding="async" for better rendering
        $attr['decoding'] = 'async';
        
        return $attr;
    }
    
    /**
     * Optimize database queries
     */
    public function optimize_database_queries() {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG && current_user_can( 'manage_options' ) ) {
            $num_queries = get_num_queries();
            $timer_stop = timer_stop();
            echo "<!-- {$num_queries} queries in {$timer_stop} seconds -->";
        }
    }
    
    /**
     * Setup caching headers
     */
    public function setup_caching() {
        if ( ! is_admin() ) {
            // Set cache headers for static assets
            add_action( 'wp_loaded', function() {
                if ( ! headers_sent() ) {
                    // Cache static assets for 1 year
                    if ( preg_match( '/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/i', $_SERVER['REQUEST_URI'] ) ) {
                        header( 'Cache-Control: public, max-age=31536000' );
                        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 31536000 ) . ' GMT' );
                    }
                }
            });
        }
    }
    
    /**
     * Minify CSS output
     */
    public function minify_css() {
        ob_start( array( $this, 'minify_css_callback' ) );
    }
    
    /**
     * CSS minification callback
     */
    public function minify_css_callback( $buffer ) {
        // Check for null or empty buffer
        if ( empty( $buffer ) ) {
            return $buffer;
        }
        
        // Only minify CSS
        if ( strpos( $buffer, '<style' ) !== false || strpos( $buffer, '.css' ) !== false ) {
            // Remove comments
            $buffer = preg_replace( '/\/\*.*?\*\//s', '', $buffer );
            
            // Remove whitespace
            $buffer = preg_replace( '/\s+/', ' ', $buffer );
            $buffer = str_replace( array( '; ', ': ', ' {', '{ ', '} ', ', ' ), array( ';', ':', '{', '{', '}', ',' ), $buffer );
        }
        
        return $buffer;
    }
    
    /**
     * Minify JavaScript output
     */
    public function minify_js() {
        ob_start( array( $this, 'minify_js_callback' ) );
    }
    
    /**
     * JavaScript minification callback
     */
    public function minify_js_callback( $buffer ) {
        // Check for null or empty buffer
        if ( empty( $buffer ) ) {
            return $buffer;
        }
        
        // Only minify JavaScript
        if ( strpos( $buffer, '<script' ) !== false || strpos( $buffer, '.js' ) !== false ) {
            // Remove comments (basic)
            $buffer = preg_replace( '/\/\*.*?\*\//s', '', $buffer );
            $buffer = preg_replace( '/\/\/.*$/m', '', $buffer );
            
            // Remove extra whitespace
            $buffer = preg_replace( '/\s+/', ' ', $buffer );
        }
        
        return $buffer;
    }
    
    /**
     * Defer non-critical scripts
     */
    public function defer_scripts( $tag, $handle, $src ) {
        // Scripts to defer
        $defer_scripts = array(
            'alam-al-anika-main',
            'alam-homepage',
            'al-anika-woocommerce-enhanced'
        );
        
        if ( in_array( $handle, $defer_scripts ) ) {
            return str_replace( ' src', ' defer src', $tag );
        }
        
        return $tag;
    }
    
    /**
     * Async load non-critical CSS
     */
    public function async_css( $html, $handle, $href, $media ) {
        // CSS files to load async
        $async_css = array(
            'font-awesome',
            'alam-google-fonts'
        );
        
        if ( in_array( $handle, $async_css ) ) {
            $html = str_replace( "rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html );
            $html .= '<noscript>' . str_replace( "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", "rel='stylesheet'", $html ) . '</noscript>';
        }
        
        return $html;
    }
    
    /**
     * Inline critical CSS
     */
    public function inline_critical_css() {
        echo '<style id="al-anika-critical-css">';
        echo 'body{font-family:Tajawal,sans-serif;margin:0;padding:0}';
        echo '.al-anika-loading{opacity:0;transition:opacity .3s ease}';
        echo '.al-anika-loaded{opacity:1}';
        echo '@media (max-width:768px){.container{padding:0 15px}}';
        echo '</style>';
    }
    
    /**
     * Preload important resources
     */
    public function preload_resources() {
        // Preload critical CSS
        echo '<link rel="preload" href="' . get_stylesheet_uri() . '" as="style">';
        
        // Preload critical JavaScript
        echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/js/main.js" as="script">';
        
        // Preload critical fonts
        echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" as="style">';
    }
    
    /**
     * DNS prefetch for external resources
     */
    public function dns_prefetch() {
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
        echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">';
        echo '<link rel="dns-prefetch" href="//www.google-analytics.com">';
        echo '<link rel="dns-prefetch" href="//ajax.googleapis.com">';
    }
    
    /**
     * Image optimization
     */
    public static function optimize_images() {
        // WebP support
        add_filter( 'wp_generate_attachment_metadata', array( __CLASS__, 'generate_webp_images' ) );
        
        // Responsive images
        add_filter( 'wp_calculate_image_srcset_meta', array( __CLASS__, 'custom_srcset' ), 10, 4 );
    }
    
    /**
     * Generate WebP images
     */
    public static function generate_webp_images( $metadata ) {
        if ( ! isset( $metadata['file'] ) ) {
            return $metadata;
        }
        
        $upload_dir = wp_upload_dir();
        $image_path = $upload_dir['basedir'] . '/' . $metadata['file'];
        
        // Generate WebP version if supported
        if ( function_exists( 'imagewebp' ) && file_exists( $image_path ) ) {
            $webp_path = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $image_path );
            
            $image_info = getimagesize( $image_path );
            if ( $image_info !== false ) {
                $mime_type = $image_info['mime'];
                
                switch ( $mime_type ) {
                    case 'image/jpeg':
                        $image = imagecreatefromjpeg( $image_path );
                        break;
                    case 'image/png':
                        $image = imagecreatefrompng( $image_path );
                        break;
                    default:
                        return $metadata;
                }
                
                if ( $image ) {
                    imagewebp( $image, $webp_path, 80 );
                    imagedestroy( $image );
                }
            }
        }
        
        return $metadata;
    }
    
    /**
     * Custom srcset for responsive images
     */
    public static function custom_srcset( $image_meta, $size_array, $image_src, $attachment_id ) {
        // Enhance srcset with WebP alternatives
        return $image_meta;
    }
}

// Initialize performance optimization
new Al_Anika_Performance();

/**
 * Performance utility functions
 */

/**
 * Get optimized image URL
 */
function al_anika_get_optimized_image( $attachment_id, $size = 'full' ) {
    $image_url = wp_get_attachment_image_url( $attachment_id, $size );
    
    // Check for WebP version
    $webp_url = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $image_url );
    $webp_path = str_replace( wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $webp_url );
    
    if ( file_exists( $webp_path ) ) {
        return $webp_url;
    }
    
    return $image_url;
}

/**
 * Check if page should be cached
 */
function al_anika_should_cache_page() {
    // Don't cache admin, login, or dynamic pages
    if ( is_admin() || is_user_logged_in() || is_404() || is_search() ) {
        return false;
    }
    
    // Don't cache WooCommerce cart/checkout/account pages
    if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
        return false;
    }
    
    return true;
}

/**
 * Get cache key for current page
 */
function al_anika_get_cache_key() {
    $key = 'al_anika_page_' . md5( $_SERVER['REQUEST_URI'] );
    
    if ( wp_is_mobile() ) {
        $key .= '_mobile';
    }
    
    return $key;
}

/**
 * Cache page output
 */
function al_anika_cache_page_output( $output ) {
    if ( al_anika_should_cache_page() ) {
        $cache_key = al_anika_get_cache_key();
        set_transient( $cache_key, $output, 3600 ); // Cache for 1 hour
    }
    
    return $output;
}

/**
 * Get cached page output
 */
function al_anika_get_cached_page() {
    if ( al_anika_should_cache_page() ) {
        $cache_key = al_anika_get_cache_key();
        return get_transient( $cache_key );
    }
    
    return false;
}

/**
 * Clear page cache
 */
function al_anika_clear_page_cache() {
    global $wpdb;
    
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_al_anika_page_%'" );
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_al_anika_page_%'" );
}

// Clear cache on content updates
add_action( 'save_post', 'al_anika_clear_page_cache' );
add_action( 'comment_post', 'al_anika_clear_page_cache' );
add_action( 'wp_update_nav_menu', 'al_anika_clear_page_cache' );
