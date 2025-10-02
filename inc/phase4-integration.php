<?php
/**
 * Phase 4: Advanced Product Systems - Main Integration File
 * Integrates all advanced product features into the theme
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_Phase4_Integration {
    
    public function __construct() {
        add_action('after_setup_theme', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('init', array($this, 'add_image_sizes'));
        add_action('customize_register', array($this, 'customize_register'));
        add_filter('woocommerce_locate_template', array($this, 'woocommerce_locate_template'), 10, 3);
        add_action('woocommerce_before_shop_loop_item', array($this, 'add_product_wrapper_start'), 5);
        add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_wrapper_end'), 25);
        add_action('wp_ajax_alam_get_quick_view', array($this, 'ajax_get_quick_view'));
        add_action('wp_ajax_nopriv_alam_get_quick_view', array($this, 'ajax_get_quick_view'));
    }
    
    public function init() {
        // Include all component files
        $this->include_components();
        
        // Add theme support
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Register custom post types if needed
        $this->register_custom_post_types();
        
        // Add custom meta boxes
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_custom_fields'));
    }
    
    private function include_components() {
        $components = array(
            'inc/product-compare.php',
            'inc/advanced-reviews.php',
            'inc/return-system.php',
            'inc/yith-integration.php'
        );
        
        foreach ($components as $component) {
            $file = get_template_directory() . '/' . $component;
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
    
    public function enqueue_assets() {
        // CSS
        wp_enqueue_style(
            'alam-phase4-products', 
            get_template_directory_uri() . '/assets/css/phase4-advanced-products.css', 
            array(), 
            '1.0.0'
        );
        
        // JavaScript
        wp_enqueue_script(
            'alam-phase4-products', 
            get_template_directory_uri() . '/assets/js/phase4-advanced-products.js', 
            array('jquery'), 
            '1.0.0', 
            true
        );
        
        // Localize script
        wp_localize_script('alam-phase4-products', 'alamAdvanced', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alam_advanced_nonce'),
            'site_url' => home_url(),
            'theme_url' => get_template_directory_uri(),
            'is_rtl' => is_rtl(),
            'messages' => array(
                'added_to_cart' => __('تمت إضافة المنتج للسلة', 'alam-al-anika'),
                'added_to_wishlist' => __('تمت إضافة المنتج لقائمة الأمنيات', 'alam-al-anika'),
                'added_to_compare' => __('تمت إضافة المنتج للمقارنة', 'alam-al-anika'),
                'compare_limit' => __('يمكن مقارنة 4 منتجات كحد أقصى', 'alam-al-anika'),
                'loading' => __('جاري التحميل...', 'alam-al-anika'),
                'error' => __('حدث خطأ', 'alam-al-anika')
            )
        ));
        
        // Additional styles for WooCommerce
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            wp_enqueue_style('alam-woocommerce-enhanced', get_template_directory_uri() . '/assets/css/woocommerce-enhanced.css', array(), '1.0.0');
        }
    }
    
    public function add_image_sizes() {
        // Product gallery sizes
        add_image_size('alam-product-gallery', 800, 800, true);
        add_image_size('alam-product-gallery-thumb', 150, 150, true);
        add_image_size('alam-product-card', 350, 350, true);
        add_image_size('alam-product-card-list', 200, 200, true);
        
        // Sale page sizes
        add_image_size('alam-sale-hero', 1920, 600, true);
        add_image_size('alam-category-hero', 1920, 400, true);
        add_image_size('alam-sale-category', 400, 250, true);
    }
    
    public function customize_register($wp_customize) {
        // Super Sale Page Settings
        $wp_customize->add_section('alam_super_sale', array(
            'title' => __('إعدادات صفحة التخفيضات الكبرى', 'alam-al-anika'),
            'description' => __('إعدادات صفحة Super Sale', 'alam-al-anika'),
            'priority' => 130
        ));
        
        // Hero Background
        $wp_customize->add_setting('super_sale_hero_bg', array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw'
        ));
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'super_sale_hero_bg', array(
            'label' => __('خلفية القسم الرئيسي', 'alam-al-anika'),
            'section' => 'alam_super_sale',
            'settings' => 'super_sale_hero_bg'
        )));
        
        // Badge Text
        $wp_customize->add_setting('super_sale_badge', array(
            'default' => 'SUPER SALE',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('super_sale_badge', array(
            'label' => __('نص الشارة', 'alam-al-anika'),
            'section' => 'alam_super_sale',
            'type' => 'text'
        ));
        
        // Title
        $wp_customize->add_setting('super_sale_title', array(
            'default' => 'تخفيضات هائلة تصل إلى 70%',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('super_sale_title', array(
            'label' => __('العنوان الرئيسي', 'alam-al-anika'),
            'section' => 'alam_super_sale',
            'type' => 'text'
        ));
        
        // Subtitle
        $wp_customize->add_setting('super_sale_subtitle', array(
            'default' => 'عروض محدودة الوقت على أفضل المنتجات',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('super_sale_subtitle', array(
            'label' => __('العنوان الفرعي', 'alam-al-anika'),
            'section' => 'alam_super_sale',
            'type' => 'text'
        ));
        
        // End Date
        $wp_customize->add_setting('super_sale_end_date', array(
            'default' => date('Y-m-d H:i:s', strtotime('+7 days')),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('super_sale_end_date', array(
            'label' => __('تاريخ انتهاء العرض', 'alam-al-anika'),
            'section' => 'alam_super_sale',
            'type' => 'datetime-local'
        ));
        
        // Flash Sale Page Settings
        $wp_customize->add_section('alam_flash_sale', array(
            'title' => __('إعدادات صفحة عروض البرق', 'alam-al-anika'),
            'description' => __('إعدادات صفحة Flash Sale', 'alam-al-anika'),
            'priority' => 131
        ));
        
        // Flash Sale Title
        $wp_customize->add_setting('flash_sale_title', array(
            'default' => 'صفقة البرق!',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('flash_sale_title', array(
            'label' => __('العنوان الرئيسي', 'alam-al-anika'),
            'section' => 'alam_flash_sale',
            'type' => 'text'
        ));
        
        // Flash Sale Subtitle
        $wp_customize->add_setting('flash_sale_subtitle', array(
            'default' => 'تخفيضات تصل إلى 80%',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('flash_sale_subtitle', array(
            'label' => __('العنوان الفرعي', 'alam-al-anika'),
            'section' => 'alam_flash_sale',
            'type' => 'text'
        ));
        
        // Flash Sale End Date
        $wp_customize->add_setting('flash_sale_end_date', array(
            'default' => date('Y-m-d H:i:s', strtotime('+6 hours')),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('flash_sale_end_date', array(
            'label' => __('تاريخ انتهاء العرض', 'alam-al-anika'),
            'section' => 'alam_flash_sale',
            'type' => 'datetime-local'
        ));
        
        // Featured Product
        $wp_customize->add_setting('flash_sale_featured_product', array(
            'default' => 0,
            'sanitize_callback' => 'absint'
        ));
        
        $wp_customize->add_control('flash_sale_featured_product', array(
            'label' => __('المنتج المميز (ID)', 'alam-al-anika'),
            'section' => 'alam_flash_sale',
            'type' => 'number'
        ));
        
        // Product Features Settings
        $wp_customize->add_section('alam_product_features', array(
            'title' => __('ميزات المنتجات المتقدمة', 'alam-al-anika'),
            'description' => __('إعدادات الميزات المتقدمة للمنتجات', 'alam-al-anika'),
            'priority' => 132
        ));
        
        // Enable Video Gallery
        $wp_customize->add_setting('enable_video_gallery', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('enable_video_gallery', array(
            'label' => __('تفعيل الفيديو في معرض الصور', 'alam-al-anika'),
            'section' => 'alam_product_features',
            'type' => 'checkbox'
        ));
        
        // Enable Product Comparison
        $wp_customize->add_setting('enable_product_comparison', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('enable_product_comparison', array(
            'label' => __('تفعيل مقارنة المنتجات', 'alam-al-anika'),
            'section' => 'alam_product_features',
            'type' => 'checkbox'
        ));
        
        // Enable Advanced Reviews
        $wp_customize->add_setting('enable_advanced_reviews', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('enable_advanced_reviews', array(
            'label' => __('تفعيل نظام المراجعات المتقدم', 'alam-al-anika'),
            'section' => 'alam_product_features',
            'type' => 'checkbox'
        ));
        
        // Enable Return System
        $wp_customize->add_setting('enable_return_system', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('enable_return_system', array(
            'label' => __('تفعيل نظام طلب الإرجاع', 'alam-al-anika'),
            'section' => 'alam_product_features',
            'type' => 'checkbox'
        ));
        
        // Enable Quick View
        $wp_customize->add_setting('enable_quick_view', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('enable_quick_view', array(
            'label' => __('تفعيل النظرة السريعة', 'alam-al-anika'),
            'section' => 'alam_product_features',
            'type' => 'checkbox'
        ));
        
        // Enable Color Swatches
        $wp_customize->add_setting('enable_color_swatches', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('enable_color_swatches', array(
            'label' => __('تفعيل عينات الألوان', 'alam-al-anika'),
            'section' => 'alam_product_features',
            'type' => 'checkbox'
        ));
        
        // Category Page Settings
        $wp_customize->add_section('alam_category_settings', array(
            'title' => __('إعدادات صفحات الفئات', 'alam-al-anika'),
            'description' => __('إعدادات صفحات عرض الفئات والمنتجات', 'alam-al-anika'),
            'priority' => 133
        ));
        
        // Products per page
        $wp_customize->add_setting('products_per_page', array(
            'default' => 12,
            'sanitize_callback' => 'absint'
        ));
        
        $wp_customize->add_control('products_per_page', array(
            'label' => __('عدد المنتجات في الصفحة', 'alam-al-anika'),
            'section' => 'alam_category_settings',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 6,
                'max' => 48,
                'step' => 6
            )
        ));
        
        // Enable filters
        $wp_customize->add_setting('enable_product_filters', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('enable_product_filters', array(
            'label' => __('تفعيل فلاتر المنتجات', 'alam-al-anika'),
            'section' => 'alam_category_settings',
            'type' => 'checkbox'
        ));
        
        // Enable infinite scroll
        $wp_customize->add_setting('enable_infinite_scroll', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('enable_infinite_scroll', array(
            'label' => __('تفعيل التمرير اللانهائي', 'alam-al-anika'),
            'section' => 'alam_category_settings',
            'type' => 'checkbox'
        ));
    }
    
    private function register_custom_post_types() {
        // Register any custom post types if needed
    }
    
    public function add_meta_boxes() {
        // Product Video URL
        add_meta_box(
            'alam_product_video',
            __('فيديو المنتج', 'alam-al-anika'),
            array($this, 'product_video_meta_box'),
            'product',
            'normal',
            'high'
        );
        
        // Flash Sale Settings
        add_meta_box(
            'alam_flash_sale',
            __('إعدادات عروض البرق', 'alam-al-anika'),
            array($this, 'flash_sale_meta_box'),
            'product',
            'side',
            'default'
        );
        
        // Color and Size Attributes
        add_meta_box(
            'alam_product_attributes',
            __('الألوان والمقاسات', 'alam-al-anika'),
            array($this, 'product_attributes_meta_box'),
            'product',
            'normal',
            'default'
        );
    }
    
    public function product_video_meta_box($post) {
        wp_nonce_field('alam_product_video_nonce', 'alam_product_video_nonce');
        $video_url = get_post_meta($post->ID, '_product_video_url', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="product_video_url"><?php _e('رابط الفيديو', 'alam-al-anika'); ?></label></th>
                <td>
                    <input type="url" id="product_video_url" name="product_video_url" value="<?php echo esc_url($video_url); ?>" class="regular-text" />
                    <p class="description"><?php _e('أدخل رابط فيديو المنتج (MP4, YouTube, Vimeo)', 'alam-al-anika'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function flash_sale_meta_box($post) {
        wp_nonce_field('alam_flash_sale_nonce', 'alam_flash_sale_nonce');
        $flash_sale_end = get_post_meta($post->ID, '_flash_sale_end', true);
        $original_stock = get_post_meta($post->ID, '_original_stock', true);
        ?>
        <p>
            <label for="flash_sale_end"><strong><?php _e('تاريخ انتهاء عرض البرق', 'alam-al-anika'); ?></strong></label>
            <input type="datetime-local" id="flash_sale_end" name="flash_sale_end" value="<?php echo esc_attr($flash_sale_end); ?>" />
        </p>
        <p>
            <label for="original_stock"><strong><?php _e('المخزون الأصلي', 'alam-al-anika'); ?></strong></label>
            <input type="number" id="original_stock" name="original_stock" value="<?php echo esc_attr($original_stock); ?>" min="0" />
            <small><?php _e('لحساب نسبة المبيعات في شريط التقدم', 'alam-al-anika'); ?></small>
        </p>
        <?php
    }
    
    public function product_attributes_meta_box($post) {
        wp_nonce_field('alam_product_attributes_nonce', 'alam_product_attributes_nonce');
        
        // Get color attributes
        $color_attributes = wc_get_attribute_taxonomies();
        $product_colors = array();
        
        foreach ($color_attributes as $attribute) {
            if (strpos($attribute->attribute_name, 'color') !== false || strpos($attribute->attribute_name, 'colour') !== false) {
                $terms = get_terms(array(
                    'taxonomy' => 'pa_' . $attribute->attribute_name,
                    'hide_empty' => false
                ));
                
                foreach ($terms as $term) {
                    $color_value = get_term_meta($term->term_id, 'color_value', true);
                    $color_image = get_term_meta($term->term_id, 'color_image', true);
                    
                    $product_colors[] = array(
                        'term' => $term,
                        'color_value' => $color_value,
                        'color_image' => $color_image
                    );
                }
            }
        }
        
        if (!empty($product_colors)): ?>
            <h4><?php _e('إعدادات الألوان', 'alam-al-anika'); ?></h4>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('اللون', 'alam-al-anika'); ?></th>
                        <th><?php _e('قيمة اللون', 'alam-al-anika'); ?></th>
                        <th><?php _e('صورة اللون', 'alam-al-anika'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($product_colors as $color): ?>
                        <tr>
                            <td><?php echo esc_html($color['term']->name); ?></td>
                            <td>
                                <input type="color" name="color_values[<?php echo $color['term']->term_id; ?>]" value="<?php echo esc_attr($color['color_value']); ?>" />
                            </td>
                            <td>
                                <input type="hidden" name="color_images[<?php echo $color['term']->term_id; ?>]" value="<?php echo esc_attr($color['color_image']); ?>" />
                                <button type="button" class="button upload-color-image"><?php _e('اختيار صورة', 'alam-al-anika'); ?></button>
                                <?php if ($color['color_image']): ?>
                                    <img src="<?php echo wp_get_attachment_url($color['color_image']); ?>" style="max-width: 50px; height: auto;" />
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif;
    }
    
    public function save_custom_fields($post_id) {
        // Save product video
        if (isset($_POST['alam_product_video_nonce']) && wp_verify_nonce($_POST['alam_product_video_nonce'], 'alam_product_video_nonce')) {
            if (isset($_POST['product_video_url'])) {
                update_post_meta($post_id, '_product_video_url', esc_url_raw($_POST['product_video_url']));
            }
        }
        
        // Save flash sale settings
        if (isset($_POST['alam_flash_sale_nonce']) && wp_verify_nonce($_POST['alam_flash_sale_nonce'], 'alam_flash_sale_nonce')) {
            if (isset($_POST['flash_sale_end'])) {
                update_post_meta($post_id, '_flash_sale_end', sanitize_text_field($_POST['flash_sale_end']));
            }
            if (isset($_POST['original_stock'])) {
                update_post_meta($post_id, '_original_stock', absint($_POST['original_stock']));
            }
        }
        
        // Save color attributes
        if (isset($_POST['alam_product_attributes_nonce']) && wp_verify_nonce($_POST['alam_product_attributes_nonce'], 'alam_product_attributes_nonce')) {
            if (isset($_POST['color_values'])) {
                foreach ($_POST['color_values'] as $term_id => $color_value) {
                    update_term_meta($term_id, 'color_value', sanitize_hex_color($color_value));
                }
            }
            if (isset($_POST['color_images'])) {
                foreach ($_POST['color_images'] as $term_id => $image_id) {
                    update_term_meta($term_id, 'color_image', absint($image_id));
                }
            }
        }
    }
    
    public function woocommerce_locate_template($template, $template_name, $template_path) {
        // Override WooCommerce templates with our enhanced versions
        $custom_templates = array(
            'single-product.php' => 'template-parts/woocommerce/single-product-enhanced.php',
            'archive-product.php' => 'template-parts/woocommerce/archive-product-enhanced.php',
            'single-product/product-image.php' => 'template-parts/woocommerce/product-gallery-video.php'
        );
        
        if (isset($custom_templates[$template_name])) {
            $custom_template = get_template_directory() . '/' . $custom_templates[$template_name];
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        
        return $template;
    }
    
    public function add_product_wrapper_start() {
        echo '<div class="alam-product-card-wrapper">';
    }
    
    public function add_product_wrapper_end() {
        echo '</div>';
    }
    
    public function ajax_get_quick_view() {
        check_ajax_referer('alam_advanced_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error(array('message' => 'المنتج غير موجود'));
        }
        
        ob_start();
        
        // Load quick view template
        global $woocommerce, $product;
        include get_template_directory() . '/template-parts/woocommerce/quick-view-content.php';
        
        $html = ob_get_clean();
        
        wp_send_json_success(array('html' => $html));
    }
}

// Initialize Phase 4
new Alam_Phase4_Integration();

/**
 * Helper Functions
 */

/**
 * Check if a feature is enabled
 */
function alam_is_feature_enabled($feature) {
    $default_features = array(
        'video_gallery' => true,
        'product_comparison' => true,
        'advanced_reviews' => true,
        'return_system' => true,
        'quick_view' => true,
        'color_swatches' => true,
        'product_filters' => true,
        'infinite_scroll' => false
    );
    
    $option_name = 'enable_' . $feature;
    $default = isset($default_features[$feature]) ? $default_features[$feature] : false;
    
    return get_theme_mod($option_name, $default);
}

/**
 * Get product video URL
 */
function alam_get_product_video($product_id = null) {
    if (!$product_id) {
        global $product;
        $product_id = $product ? $product->get_id() : 0;
    }
    
    if (!$product_id) {
        return '';
    }
    
    return get_post_meta($product_id, '_product_video_url', true);
}

/**
 * Get color swatch for attribute term
 */
function alam_get_color_swatch($term_id, $size = 'medium') {
    $color_value = get_term_meta($term_id, 'color_value', true);
    $color_image = get_term_meta($term_id, 'color_image', true);
    $term = get_term($term_id);
    
    if (!$term) {
        return '';
    }
    
    $swatch_size = $size === 'large' ? '50px' : ($size === 'small' ? '20px' : '30px');
    
    ob_start();
    ?>
    <span class="alam-color-swatch alam-color-swatch-<?php echo esc_attr($size); ?>" 
          style="display: inline-block; width: <?php echo $swatch_size; ?>; height: <?php echo $swatch_size; ?>; border-radius: 50%; background-color: <?php echo esc_attr($color_value ?: '#ccc'); ?>; border: 2px solid #ddd;"
          title="<?php echo esc_attr($term->name); ?>">
        <?php if ($color_image): ?>
            <img src="<?php echo wp_get_attachment_url($color_image); ?>" 
                 alt="<?php echo esc_attr($term->name); ?>" 
                 style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
        <?php endif; ?>
    </span>
    <?php
    return ob_get_clean();
}

/**
 * Display product compare button
 */
function alam_product_compare_button($product_id = null, $style = 'default') {
    if (!alam_is_feature_enabled('product_comparison')) {
        return '';
    }
    
    if (!$product_id) {
        global $product;
        $product_id = $product ? $product->get_id() : 0;
    }
    
    if (!$product_id) {
        return '';
    }
    
    return do_shortcode('[alam_compare_button product_id="' . $product_id . '" style="' . $style . '"]');
}

/**
 * Display wishlist counter
 */
function alam_wishlist_counter($style = 'default') {
    if (!defined('YITH_WCWL')) {
        return '';
    }
    
    return do_shortcode('[alam_wishlist_counter style="' . $style . '"]');
}

/**
 * Display compare counter
 */
function alam_compare_counter($style = 'default') {
    return do_shortcode('[alam_compare_counter style="' . $style . '"]');
}

/**
 * Get sale countdown HTML
 */
function alam_sale_countdown($end_date, $type = 'sale') {
    if (!$end_date) {
        return '';
    }
    
    $class = $type === 'flash' ? 'alam-flash-countdown' : 'alam-sale-countdown';
    
    ob_start();
    ?>
    <div class="<?php echo esc_attr($class); ?>" data-end-date="<?php echo esc_attr($end_date); ?>">
        <div class="alam-countdown-wrapper">
            <?php if ($type !== 'flash'): ?>
                <div class="alam-countdown-item">
                    <span class="alam-countdown-number" data-days>00</span>
                    <span class="alam-countdown-label">أيام</span>
                </div>
            <?php endif; ?>
            <div class="alam-countdown-item">
                <span class="alam-countdown-number" data-hours>00</span>
                <span class="alam-countdown-label">ساعات</span>
            </div>
            <?php if ($type === 'flash'): ?>
                <div class="alam-countdown-separator">:</div>
            <?php endif; ?>
            <div class="alam-countdown-item">
                <span class="alam-countdown-number" data-minutes>00</span>
                <span class="alam-countdown-label">دقائق</span>
            </div>
            <?php if ($type === 'flash'): ?>
                <div class="alam-countdown-separator">:</div>
            <?php endif; ?>
            <div class="alam-countdown-item">
                <span class="alam-countdown-number" data-seconds>00</span>
                <span class="alam-countdown-label">ثواني</span>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Enhanced product card for listings
 */
function alam_enhanced_product_card($product_id = null) {
    if (!$product_id) {
        global $product;
        if (!$product) return '';
        $product_id = $product->get_id();
    }
    
    $original_product = $GLOBALS['product'];
    $GLOBALS['product'] = wc_get_product($product_id);
    
    ob_start();
    include get_template_directory() . '/template-parts/woocommerce/product-card-enhanced.php';
    $output = ob_get_clean();
    
    $GLOBALS['product'] = $original_product;
    
    return $output;
}

/**
 * Check if product has video
 */
function alam_product_has_video($product_id = null) {
    if (!alam_is_feature_enabled('video_gallery')) {
        return false;
    }
    
    $video_url = alam_get_product_video($product_id);
    return !empty($video_url);
}

/**
 * Get return request form
 */
function alam_return_request_form($order_id = null, $product_id = null) {
    if (!alam_is_feature_enabled('return_system')) {
        return '';
    }
    
    $atts = array();
    if ($order_id) $atts['order_id'] = $order_id;
    if ($product_id) $atts['product_id'] = $product_id;
    
    return do_shortcode('[alam_return_form ' . http_build_query($atts, '', ' ') . ']');
}

/**
 * Get category filter sidebar
 */
function alam_category_filters() {
    if (!alam_is_feature_enabled('product_filters')) {
        return '';
    }
    
    ob_start();
    include get_template_directory() . '/template-parts/woocommerce/category-filters.php';
    return ob_get_clean();
}