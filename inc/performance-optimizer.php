<?php
/**
 * Al-Anika Theme - Performance & Cleanup Functions
 * Version: 9.2.0
 * Enhanced performance monitoring and optimization
 *
 * @package Al_Anika_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Performance Optimization & Cleanup Class
 */
class Al_Anika_Performance_Optimizer {
    
    public function __construct() {
        add_action('init', array($this, 'init_optimizations'));
        add_action('wp_footer', array($this, 'performance_monitoring'), 999);
    }
    
    /**
     * Initialize performance optimizations
     */
    public function init_optimizations() {
        // Remove unnecessary WordPress features
        $this->cleanup_wp_head();
        
        // Optimize database queries
        add_action('pre_get_posts', array($this, 'optimize_queries'));
        
        // Lazy load images
        add_filter('wp_get_attachment_image_attributes', array($this, 'add_lazy_loading'), 10, 3);
        
        // Preload critical resources
        add_action('wp_head', array($this, 'preload_resources'), 1);
        
        // Add security headers
        add_action('send_headers', array($this, 'add_security_headers'));
        
        // Clean up CSS and JS
        add_action('wp_enqueue_scripts', array($this, 'optimize_assets'), 999);
    }
    
    /**
     * Clean up WordPress head
     */
    private function cleanup_wp_head() {
        // Remove unnecessary meta tags
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        
        // Remove emoji scripts
        if (!is_admin()) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        }
        
        // Remove block library CSS on non-block pages
        if (!is_admin() && !function_exists('has_blocks')) {
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
        }
    }
    
    /**
     * Optimize database queries
     */
    public function optimize_queries($query) {
        if (!is_admin() && $query->is_main_query()) {
            // Optimize product queries
            if (is_shop() || is_product_category()) {
                $query->set('posts_per_page', 12);
                $query->set('meta_query', array(
                    array(
                        'key' => '_visibility',
                        'value' => array('catalog', 'visible'),
                        'compare' => 'IN'
                    )
                ));
            }
            
            // Optimize search queries
            if (is_search()) {
                $query->set('posts_per_page', 12);
                if (isset($_GET['post_type']) && $_GET['post_type'] === 'product') {
                    $query->set('meta_query', array(
                        array(
                            'key' => '_visibility',
                            'value' => array('catalog', 'visible'),
                            'compare' => 'IN'
                        )
                    ));
                }
            }
        }
    }
    
    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading($attributes, $attachment, $size) {
        // Skip for critical above-the-fold images
        if (in_array($size, array('al-anika-hero', 'hero-banner'))) {
            return $attributes;
        }
        
        $attributes['loading'] = 'lazy';
        $attributes['decoding'] = 'async';
        
        return $attributes;
    }
    
    /**
     * Preload critical resources
     */
    public function preload_resources() {
        // Preload critical fonts
        echo '<link rel="preload" href="' . AL_ANIKA_ASSETS_URI . '/fonts/font-awesome/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
        
        // Preload critical CSS
        if (file_exists(AL_ANIKA_THEME_DIR . '/assets/css/critical.css')) {
            echo '<link rel="preload" href="' . AL_ANIKA_ASSETS_URI . '/css/critical.css" as="style">' . "\n";
        }
        
        // DNS prefetch for external resources
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">' . "\n";
        
        // Preconnect to critical origins
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    }
    
    /**
     * Add security headers
     */
    public function add_security_headers() {
        if (!is_admin()) {
            // Content Security Policy
            header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' *.googleapis.com *.cdnjs.cloudflare.com; font-src 'self' *.gstatic.com *.cdnjs.cloudflare.com; img-src 'self' data: *.gravatar.com;");
            
            // Other security headers
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Performance headers
            header('X-UA-Compatible: IE=edge');
        }
    }
    
    /**
     * Optimize asset loading
     */
    public function optimize_assets() {
        global $wp_scripts, $wp_styles;
        
        // Remove unused scripts on specific pages
        if (!is_admin()) {
            // Remove jQuery migrate if not needed
            wp_deregister_script('jquery-migrate');
            
            // Remove Contact Form 7 scripts if not on contact page
            if (!is_page('contact') && !is_page('اتصل-بنا')) {
                wp_dequeue_script('contact-form-7');
                wp_dequeue_style('contact-form-7');
            }
            
            // Defer non-critical JavaScript
            foreach ($wp_scripts->registered as $handle => $script) {
                if (strpos($handle, 'al-anika-') === 0 && $handle !== 'al-anika-core') {
                    wp_script_add_data($handle, 'defer', true);
                }
            }
        }
    }
    
    /**
     * Performance monitoring
     */
    public function performance_monitoring() {
        if (current_user_can('administrator') && isset($_GET['debug_performance'])) {
            $memory_usage = memory_get_peak_usage(true) / 1024 / 1024;
            $query_count = get_num_queries();
            $load_time = timer_stop(0, 3);
            
            echo '<div style="position: fixed; bottom: 10px; right: 10px; background: #333; color: #fff; padding: 10px; border-radius: 5px; font-size: 12px; z-index: 9999;">';
            echo '<strong>Performance Debug:</strong><br>';
            echo 'Memory: ' . round($memory_usage, 2) . ' MB<br>';
            echo 'Queries: ' . $query_count . '<br>';
            echo 'Load Time: ' . $load_time . 's';
            echo '</div>';
        }
    }
    
    /**
     * Theme update cleanup
     */
    public static function cleanup_deprecated_files() {
        $deprecated_files = array(
            AL_ANIKA_THEME_DIR . '/assets/css/main-original.css',
            AL_ANIKA_THEME_DIR . '/assets/css/main-optimized-original.css',
            AL_ANIKA_THEME_DIR . '/assets/css/responsive.css', // Replaced by responsive-unified.css
        );
        
        foreach ($deprecated_files as $file) {
            if (file_exists($file)) {
                wp_delete_file($file);
            }
        }
        
        // Update theme options
        set_theme_mod('al_anika_theme_optimized', true);
        set_theme_mod('al_anika_optimization_date', current_time('mysql'));
    }
}

// Initialize the performance optimizer
new Al_Anika_Performance_Optimizer();

/**
 * Theme activation hook - Run cleanup and optimizations
 */
function al_anika_theme_optimized_activation() {
    // Run cleanup
    Al_Anika_Performance_Optimizer::cleanup_deprecated_files();
    
    // Set default customizer values for optimized theme
    $defaults = array(
        'al_anika_enable_lazy_loading' => true,
        'al_anika_enable_css_minification' => true,
        'al_anika_enable_js_defer' => true,
        'al_anika_enable_security_headers' => true,
        'al_anika_performance_mode' => 'optimized',
    );
    
    foreach ($defaults as $setting => $value) {
        if (get_theme_mod($setting) === false) {
            set_theme_mod($setting, $value);
        }
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Clear any existing caches
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
}
add_action('after_switch_theme', 'al_anika_theme_optimized_activation');

/**
 * Enhanced error handling for missing assets
 */
function al_anika_handle_missing_assets() {
    // Check for critical assets
    $critical_assets = array(
        'main.css' => AL_ANIKA_THEME_DIR . '/assets/css/main.css',
        'responsive-unified.css' => AL_ANIKA_THEME_DIR . '/assets/css/responsive-unified.css',
        'main.js' => AL_ANIKA_THEME_DIR . '/assets/js/main.js',
    );
    
    $missing_assets = array();
    foreach ($critical_assets as $name => $path) {
        if (!file_exists($path)) {
            $missing_assets[] = $name;
        }
    }
    
    if (!empty($missing_assets) && current_user_can('administrator')) {
        add_action('admin_notices', function() use ($missing_assets) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p><strong>Al-Anika Theme:</strong> Missing critical assets: ' . implode(', ', $missing_assets) . '</p>';
            echo '<p>Some theme features may not work correctly. Please contact theme support.</p>';
            echo '</div>';
        });
    }
}
add_action('admin_init', 'al_anika_handle_missing_assets');

/**
 * Performance optimization admin menu
 */
function al_anika_add_performance_menu() {
    if (current_user_can('administrator')) {
        add_theme_page(
            __('Performance Optimization', 'alam-al-anika'),
            __('Performance', 'alam-al-anika'),
            'manage_options',
            'al-anika-performance',
            'al_anika_performance_page'
        );
    }
}
add_action('admin_menu', 'al_anika_add_performance_menu');

/**
 * Performance optimization page
 */
function al_anika_performance_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Al-Anika Performance Optimization', 'alam-al-anika'); ?></h1>
        
        <div class="card">
            <h2><?php _e('Theme Status', 'alam-al-anika'); ?></h2>
            <p><strong><?php _e('Version:', 'alam-al-anika'); ?></strong> <?php echo AL_ANIKA_VERSION; ?></p>
            <p><strong><?php _e('Optimization Status:', 'alam-al-anika'); ?></strong> 
                <?php echo get_theme_mod('al_anika_theme_optimized') ? 
                    '<span style="color: green;">Optimized</span>' : 
                    '<span style="color: orange;">Standard</span>'; ?>
            </p>
            <p><strong><?php _e('Last Optimized:', 'alam-al-anika'); ?></strong> 
                <?php echo get_theme_mod('al_anika_optimization_date', 'Never'); ?>
            </p>
        </div>
        
        <div class="card">
            <h2><?php _e('Performance Features', 'alam-al-anika'); ?></h2>
            <ul>
                <li>✅ Unified responsive CSS system</li>
                <li>✅ Optimized asset loading</li>
                <li>✅ Lazy loading for images</li>
                <li>✅ Security headers enabled</li>
                <li>✅ Database query optimization</li>
                <li>✅ Critical CSS inlining</li>
                <li>✅ Deprecated file cleanup</li>
                <li>✅ Version consistency fixed</li>
            </ul>
        </div>
        
        <div class="card">
            <h2><?php _e('Debug Information', 'alam-al-anika'); ?></h2>
            <p><?php _e('Add', 'alam-al-anika'); ?> <code>?debug_performance=1</code> <?php _e('to any page URL to see performance metrics.', 'alam-al-anika'); ?></p>
        </div>
    </div>
    <?php
}