<?php
/**
 * Phase 5: Enhanced Interactive Systems - Main Integration File
 * الدمج الشامل للأنظمة التفاعلية المتطورة
 * 
 * @package AlamAlAnika
 * @version 5.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_Phase5_Integration {
    
    public function __construct() {
        add_action('after_setup_theme', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_head', array($this, 'add_custom_styles'));
        add_action('wp_footer', array($this, 'add_integration_scripts'));
        
        // Theme customization hooks
        add_action('customize_register', array($this, 'customize_register'));
        
        // Admin integration
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        
        // Performance optimization
        add_action('init', array($this, 'optimize_performance'));
    }
    
    public function init() {
        // Initialize all Phase 5 systems
        $this->init_phase5_systems();
        
        // Add theme support for new features
        $this->add_theme_supports();
        
        // Register image sizes for new features
        $this->register_image_sizes();
        
        // Create database tables if needed
        $this->create_database_tables();
    }
    
    private function init_phase5_systems() {
        // Systems are auto-initialized by their respective classes
        // This method can be used for any additional initialization
        
        // Set up global settings
        $this->setup_global_settings();
        
        // Initialize caching if needed
        $this->init_caching();
    }
    
    private function add_theme_supports() {
        // Enhanced image support
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ));
        
        // Custom background and header
        add_theme_support('custom-background');
        add_theme_support('custom-header');
        
        // Wide alignment
        add_theme_support('align-wide');
        
        // Responsive embeds
        add_theme_support('responsive-embeds');
    }
    
    private function register_image_sizes() {
        // Recommendation system images
        add_image_size('alam-recommendation-card', 300, 300, true);
        add_image_size('alam-pickup-product', 250, 250, true);
        add_image_size('alam-floating-product', 120, 120, true);
        
        // Banner and popup images
        add_image_size('alam-popup-banner', 600, 400, true);
        add_image_size('alam-announcement-banner', 1200, 200, true);
        
        // Label and badge images
        add_image_size('alam-product-badge', 80, 80, true);
    }
    
    private function create_database_tables() {
        // Tables are created by individual systems
        // This method ensures all tables are properly created
        
        global $wpdb;
        
        // Ensure database charset
        $charset_collate = $wpdb->get_charset_collate();
        
        // Additional custom tables can be created here if needed
        $this->create_analytics_table();
    }
    
    private function create_analytics_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'alam_analytics';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            event_type varchar(50) NOT NULL,
            event_data text,
            user_identifier varchar(255),
            timestamp datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY event_type (event_type),
            KEY timestamp (timestamp)
        ) {$wpdb->get_charset_collate()};";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public function enqueue_assets() {
        // Enhanced main stylesheet
        wp_enqueue_style(
            'alam-phase5-integration',
            get_template_directory_uri() . '/assets/css/phase5-integration.css',
            array(),
            '5.0.0'
        );
        
        // Enhanced main JavaScript
        wp_enqueue_script(
            'alam-phase5-integration',
            get_template_directory_uri() . '/assets/js/phase5-integration.js',
            array('jquery'),
            '5.0.0',
            true
        );
        
        // Performance optimization - conditional loading
        $this->conditional_asset_loading();
        
        // Localize script with comprehensive data
        wp_localize_script('alam-phase5-integration', 'alamPhase5', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alam_phase5_nonce'),
            'theme_url' => get_template_directory_uri(),
            'site_url' => home_url(),
            'current_user_id' => get_current_user_id(),
            'is_mobile' => wp_is_mobile(),
            'is_rtl' => is_rtl(),
            'settings' => array(
                'animation_speed' => get_theme_mod('alam_animation_speed', 'normal'),
                'enable_sounds' => get_theme_mod('alam_enable_sounds', true),
                'enable_animations' => get_theme_mod('alam_enable_animations', true),
                'performance_mode' => get_theme_mod('alam_performance_mode', false)
            ),
            'messages' => array(
                'loading' => __('جاري التحميل...', 'alam-al-anika'),
                'error' => __('حدث خطأ', 'alam-al-anika'),
                'success' => __('تم بنجاح', 'alam-al-anika'),
                'please_wait' => __('يرجى الانتظار...', 'alam-al-anika'),
                'try_again' => __('حاول مرة أخرى', 'alam-al-anika')
            )
        ));
    }
    
    private function conditional_asset_loading() {
        // Load assets based on page type and user preferences
        
        // Only load rewards system on relevant pages
        if (get_theme_mod('alam_enable_rewards_system', true)) {
            // Assets already loaded by rewards system class
        }
        
        // Only load recommendations on product/shop pages
        if (is_woocommerce() || is_shop() || is_product_category() || is_product()) {
            // Assets already loaded by recommendations system class
        }
        
        // Load popups assets conditionally
        if (get_theme_mod('alam_enable_popups', true)) {
            // Assets already loaded by popups system class
        }
    }
    
    public function add_custom_styles() {
        $custom_css = '';
        
        // Dynamic color scheme
        $primary_color = get_theme_mod('alam_primary_color', '#667eea');
        $secondary_color = get_theme_mod('alam_secondary_color', '#764ba2');
        $accent_color = get_theme_mod('alam_accent_color', '#4ecdc4');
        
        $custom_css .= "
        :root {
            --alam-primary: {$primary_color};
            --alam-secondary: {$secondary_color};
            --alam-accent: {$accent_color};
            --alam-primary-rgb: " . $this->hex_to_rgb($primary_color) . ";
            --alam-secondary-rgb: " . $this->hex_to_rgb($secondary_color) . ";
            --alam-accent-rgb: " . $this->hex_to_rgb($accent_color) . ";
        }
        
        .alam-primary-bg { background: var(--alam-primary); }
        .alam-secondary-bg { background: var(--alam-secondary); }
        .alam-accent-bg { background: var(--alam-accent); }
        .alam-primary-color { color: var(--alam-primary); }
        .alam-secondary-color { color: var(--alam-secondary); }
        .alam-accent-color { color: var(--alam-accent); }
        
        .alam-gradient-primary {
            background: linear-gradient(135deg, var(--alam-primary), var(--alam-secondary));
        }
        
        .alam-gradient-accent {
            background: linear-gradient(135deg, var(--alam-accent), var(--alam-primary));
        }
        ";
        
        // Performance optimizations
        if (get_theme_mod('alam_performance_mode', false)) {
            $custom_css .= "
            * { animation-duration: 0.1s !important; }
            .alam-reduced-motion * { animation: none !important; transition: none !important; }
            ";
        }
        
        // Dark mode support
        if (get_theme_mod('alam_enable_dark_mode', false)) {
            $custom_css .= "
            @media (prefers-color-scheme: dark) {
                :root {
                    --alam-bg: #1a1a1a;
                    --alam-text: #ffffff;
                    --alam-border: #333333;
                }
                
                body { background: var(--alam-bg); color: var(--alam-text); }
                .product-recommendation-card { background: #2a2a2a; }
                .alam-announcement-banner { background: #333; }
            }
            ";
        }
        
        if (!empty($custom_css)) {
            echo "<style id='alam-phase5-custom-styles'>{$custom_css}</style>";
        }
    }
    
    public function add_integration_scripts() {
        ?>
        <script>
        // Phase 5 Integration Scripts
        jQuery(document).ready(function($) {
            // Initialize all Phase 5 systems coordination
            if (typeof AlamPhase5Integration === 'function') {
                window.alamPhase5 = new AlamPhase5Integration();
            }
            
            // Global error handling
            window.addEventListener('error', function(e) {
                console.warn('Alam Theme Error:', e.message);
            });
            
            // Performance monitoring
            if (alamPhase5.settings.performance_mode) {
                window.alamPerformanceMonitor = new AlamPerformanceMonitor();
            }
            
            // Accessibility enhancements
            $('body').addClass('alam-a11y-enhanced');
            
            // Mobile optimizations
            if (alamPhase5.is_mobile) {
                $('body').addClass('alam-mobile-optimized');
            }
        });
        </script>
        <?php
    }
    
    public function customize_register($wp_customize) {
        // Phase 5 Master Controls Section
        $wp_customize->add_section('alam_phase5_controls', array(
            'title' => __('🚀 الأنظمة التفاعلية المتطورة', 'alam-al-anika'),
            'description' => __('التحكم في جميع الأنظمة التفاعلية المتطورة', 'alam-al-anika'),
            'priority' => 30
        ));
        
        // Master Color Scheme
        $wp_customize->add_setting('alam_primary_color', array(
            'default' => '#667eea',
            'sanitize_callback' => 'sanitize_hex_color'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'alam_primary_color', array(
            'label' => __('اللون الأساسي', 'alam-al-anika'),
            'section' => 'alam_phase5_controls'
        )));
        
        $wp_customize->add_setting('alam_secondary_color', array(
            'default' => '#764ba2',
            'sanitize_callback' => 'sanitize_hex_color'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'alam_secondary_color', array(
            'label' => __('اللون الثانوي', 'alam-al-anika'),
            'section' => 'alam_phase5_controls'
        )));
        
        $wp_customize->add_setting('alam_accent_color', array(
            'default' => '#4ecdc4',
            'sanitize_callback' => 'sanitize_hex_color'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'alam_accent_color', array(
            'label' => __('لون التمييز', 'alam-al-anika'),
            'section' => 'alam_phase5_controls'
        )));
        
        // Performance Settings
        $wp_customize->add_setting('alam_performance_mode', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('alam_performance_mode', array(
            'label' => __('وضع الأداء العالي', 'alam-al-anika'),
            'description' => __('تقليل الحركات والتأثيرات لتحسين الأداء', 'alam-al-anika'),
            'section' => 'alam_phase5_controls',
            'type' => 'checkbox'
        ));
        
        // Animation Settings
        $wp_customize->add_setting('alam_enable_animations', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('alam_enable_animations', array(
            'label' => __('تفعيل الحركات والتأثيرات', 'alam-al-anika'),
            'section' => 'alam_phase5_controls',
            'type' => 'checkbox'
        ));
        
        // Sound Settings
        $wp_customize->add_setting('alam_enable_sounds', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('alam_enable_sounds', array(
            'label' => __('تفعيل الأصوات التفاعلية', 'alam-al-anika'),
            'section' => 'alam_phase5_controls',
            'type' => 'checkbox'
        ));
        
        // System Controls
        $wp_customize->add_setting('alam_enable_rewards_system', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('alam_enable_rewards_system', array(
            'label' => __('نظام المكافآت اليومي', 'alam-al-anika'),
            'section' => 'alam_phase5_controls',
            'type' => 'checkbox'
        ));
        
        $wp_customize->add_setting('alam_enable_recommendations', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('alam_enable_recommendations', array(
            'label' => __('نظام التوصيات الذكية', 'alam-al-anika'),
            'section' => 'alam_phase5_controls',
            'type' => 'checkbox'
        ));
        
        $wp_customize->add_setting('alam_enable_popups', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('alam_enable_popups', array(
            'label' => __('النوافذ المنبثقة والإشعارات', 'alam-al-anika'),
            'section' => 'alam_phase5_controls',
            'type' => 'checkbox'
        ));
        
        // Dark Mode
        $wp_customize->add_setting('alam_enable_dark_mode', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('alam_enable_dark_mode', array(
            'label' => __('دعم الوضع المظلم', 'alam-al-anika'),
            'section' => 'alam_phase5_controls',
            'type' => 'checkbox'
        ));
    }
    
    public function add_admin_menu() {
        add_theme_page(
            __('لوحة تحكم الأنظمة التفاعلية', 'alam-al-anika'),
            __('الأنظمة التفاعلية', 'alam-al-anika'),
            'manage_options',
            'alam-phase5-dashboard',
            array($this, 'admin_dashboard_page')
        );
    }
    
    public function admin_dashboard_page() {
        ?>
        <div class="wrap alam-admin-dashboard">
            <h1>🚀 لوحة تحكم الأنظمة التفاعلية المتطورة</h1>
            
            <div class="alam-dashboard-grid">
                <div class="dashboard-card">
                    <h2>📊 إحصائيات النظام</h2>
                    <?php $this->render_system_stats(); ?>
                </div>
                
                <div class="dashboard-card">
                    <h2>🎁 نظام المكافآت</h2>
                    <?php $this->render_rewards_stats(); ?>
                </div>
                
                <div class="dashboard-card">
                    <h2>🎯 التوصيات الذكية</h2>
                    <?php $this->render_recommendations_stats(); ?>
                </div>
                
                <div class="dashboard-card">
                    <h2>📢 الإشعارات والإعلانات</h2>
                    <?php $this->render_notifications_stats(); ?>
                </div>
            </div>
            
            <div class="alam-quick-actions">
                <h2>إجراءات سريعة</h2>
                <button class="button button-primary" onclick="alamAdminDashboard.clearCache()">
                    مسح ذاكرة التخزين المؤقت
                </button>
                <button class="button button-secondary" onclick="alamAdminDashboard.regenerateData()">
                    إعادة تجديد البيانات
                </button>
                <button class="button" onclick="alamAdminDashboard.exportSettings()">
                    تصدير الإعدادات
                </button>
            </div>
        </div>
        
        <style>
        .alam-admin-dashboard {
            direction: rtl;
        }
        
        .alam-dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .dashboard-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .dashboard-card h2 {
            margin-top: 0;
            color: #667eea;
        }
        
        .alam-quick-actions {
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        
        .alam-quick-actions button {
            margin-left: 10px;
        }
        </style>
        <?php
    }
    
    private function render_system_stats() {
        global $wpdb;
        
        $total_users = get_users(array('count_total' => true));
        $active_rewards = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}alam_daily_rewards WHERE last_visit_date >= CURDATE() - INTERVAL 7 DAY");
        $total_views = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}alam_product_views");
        
        ?>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number"><?php echo number_format($total_users); ?></div>
                <div class="stat-label">إجمالي المستخدمين</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo number_format($active_rewards); ?></div>
                <div class="stat-label">مستخدمو المكافآت النشطون</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo number_format($total_views); ?></div>
                <div class="stat-label">مشاهدات المنتجات</div>
            </div>
        </div>
        
        <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
        }
        
        .stat-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        </style>
        <?php
    }
    
    private function render_rewards_stats() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'alam_daily_rewards';
        $today_rewards = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name} WHERE last_visit_date = CURDATE()");
        $week_completions = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name} WHERE consecutive_days >= 7");
        
        echo "<p><strong>مكافآت اليوم:</strong> {$today_rewards}</p>";
        echo "<p><strong>إكمال أسبوعي:</strong> {$week_completions}</p>";
    }
    
    private function render_recommendations_stats() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'alam_product_views';
        $recent_views = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name} WHERE last_viewed >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        
        echo "<p><strong>المشاهدات خلال 24 ساعة:</strong> {$recent_views}</p>";
        echo "<p><strong>التوصيات النشطة:</strong> نشط</p>";
    }
    
    private function render_notifications_stats() {
        echo "<p><strong>الإشعارات المفعلة:</strong> " . (get_theme_mod('alam_enable_popups', true) ? 'نشط' : 'معطل') . "</p>";
        echo "<p><strong>شريط الإعلانات:</strong> " . (get_theme_mod('show_announcement_banner', false) ? 'نشط' : 'معطل') . "</p>";
    }
    
    public function admin_enqueue_scripts($hook) {
        if ($hook !== 'appearance_page_alam-phase5-dashboard') {
            return;
        }
        
        wp_enqueue_script(
            'alam-admin-dashboard',
            get_template_directory_uri() . '/assets/js/admin-dashboard.js',
            array('jquery'),
            '5.0.0',
            true
        );
    }
    
    public function optimize_performance() {
        // Optimize database queries
        $this->optimize_database();
        
        // Enable caching
        $this->enable_caching();
        
        // Minify assets if needed
        if (get_theme_mod('alam_performance_mode', false)) {
            $this->enable_asset_optimization();
        }
    }
    
    private function optimize_database() {
        // Add database optimization code here
    }
    
    private function enable_caching() {
        // Add caching logic here
    }
    
    private function enable_asset_optimization() {
        // Add asset optimization here
    }
    
    private function setup_global_settings() {
        // Set up global theme settings
        if (!get_option('alam_phase5_installed')) {
            update_option('alam_phase5_installed', true);
            update_option('alam_phase5_version', '5.0.0');
            update_option('alam_phase5_install_date', current_time('mysql'));
        }
    }
    
    private function init_caching() {
        // Initialize caching system
    }
    
    private function hex_to_rgb($hex) {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "{$r}, {$g}, {$b}";
    }
}

// Initialize Phase 5 Integration
new Alam_Phase5_Integration();