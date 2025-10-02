<?php
/**
 * Corner Category Button System
 * Floating category navigation button
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_Corner_Category_Button {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_corner_button'));
        add_action('wp_ajax_alam_get_categories', array($this, 'ajax_get_categories'));
        add_action('wp_ajax_nopriv_alam_get_categories', array($this, 'ajax_get_categories'));
        add_action('customize_register', array($this, 'customize_register'));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('alam-corner-button', get_template_directory_uri() . '/assets/js/corner-category-button.js', array('jquery'), '1.0.0', true);
        wp_localize_script('alam-corner-button', 'alamCornerButton', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alam_corner_button_nonce'),
            'shop_url' => wc_get_page_permalink('shop')
        ));
    }
    
    public function customize_register($wp_customize) {
        // Add Corner Button Section
        $wp_customize->add_section('alam_corner_button', array(
            'title' => 'زر الفئات الجانبي',
            'priority' => 160,
            'panel' => 'alam_theme_options'
        ));
        
        // Enable Corner Button
        $wp_customize->add_setting('corner_button_enable', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('corner_button_enable', array(
            'label' => 'تفعيل زر الفئات الجانبي',
            'section' => 'alam_corner_button',
            'type' => 'checkbox'
        ));
        
        // Button Position
        $wp_customize->add_setting('corner_button_position', array(
            'default' => 'bottom-right',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('corner_button_position', array(
            'label' => 'موقع الزر',
            'section' => 'alam_corner_button',
            'type' => 'select',
            'choices' => array(
                'top-right' => 'أعلى اليمين',
                'top-left' => 'أعلى اليسار',
                'bottom-right' => 'أسفل اليمين',
                'bottom-left' => 'أسفل اليسار'
            )
        ));
        
        // Button Style
        $wp_customize->add_setting('corner_button_style', array(
            'default' => 'circular',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('corner_button_style', array(
            'label' => 'شكل الزر',
            'section' => 'alam_corner_button',
            'type' => 'select',
            'choices' => array(
                'circular' => 'دائري',
                'square' => 'مربع',
                'rounded' => 'مربع مدور الحواف'
            )
        ));
        
        // Button Color
        $wp_customize->add_setting('corner_button_color', array(
            'default' => '#2c5aa0',
            'sanitize_callback' => 'sanitize_hex_color'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'corner_button_color', array(
            'label' => 'لون الزر',
            'section' => 'alam_corner_button'
        )));
        
        // Categories to Show
        $wp_customize->add_setting('corner_button_categories', array(
            'default' => 'all',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('corner_button_categories', array(
            'label' => 'الفئات المعروضة',
            'section' => 'alam_corner_button',
            'type' => 'select',
            'choices' => array(
                'all' => 'جميع الفئات',
                'parent' => 'الفئات الرئيسية فقط',
                'featured' => 'الفئات المميزة'
            )
        ));
        
        // Show Product Count
        $wp_customize->add_setting('corner_button_show_count', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('corner_button_show_count', array(
            'label' => 'عرض عدد المنتجات',
            'section' => 'alam_corner_button',
            'type' => 'checkbox'
        ));
    }
    
    public function render_corner_button() {
        if (!get_theme_mod('corner_button_enable', true)) {
            return;
        }
        
        $position = get_theme_mod('corner_button_position', 'bottom-right');
        $style = get_theme_mod('corner_button_style', 'circular');
        $color = get_theme_mod('corner_button_color', '#2c5aa0');
        
        ?>
        <div class="alam-corner-category-button <?php echo esc_attr($position); ?> <?php echo esc_attr($style); ?>" data-color="<?php echo esc_attr($color); ?>">
            <button class="alam-corner-btn" aria-label="قائمة الفئات">
                <svg class="alam-corner-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="alam-corner-text">الفئات</span>
            </button>
            
            <div class="alam-corner-dropdown">
                <div class="alam-corner-dropdown-header">
                    <h3>فئات المنتجات</h3>
                    <button class="alam-corner-close">
                        <svg width="20" height="20" viewBox="0 0 20 20">
                            <path d="M15 5L5 15M5 5l10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
                
                <div class="alam-corner-dropdown-content">
                    <div class="alam-corner-loading">
                        <div class="alam-corner-spinner"></div>
                        <span>جاري تحميل الفئات...</span>
                    </div>
                </div>
                
                <div class="alam-corner-dropdown-footer">
                    <a href="<?php echo al_anika_safe_wc_url('shop'); ?>" class="alam-corner-shop-link">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 3h12l-1 8H4L2 3zM2 3l-1-2M6 13a1 1 0 102 0 1 1 0 00-2 0zM11 13a1 1 0 102 0 1 1 0 00-2 0z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        </svg>
                        <?php esc_html_e('عرض جميع المنتجات', 'alam-al-anika'); ?>
                    </a>
                </div>
            </div>
            
            <div class="alam-corner-overlay"></div>
        </div>
        
        <style>
            .alam-corner-category-button {
                --corner-button-color: <?php echo esc_attr($color); ?>;
            }
        </style>
        <?php
    }
    
    public function ajax_get_categories() {
        check_ajax_referer('alam_corner_button_nonce', 'nonce');
        
        $categories_type = get_theme_mod('corner_button_categories', 'all');
        $show_count = get_theme_mod('corner_button_show_count', true);
        
        $args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'number' => 20
        );
        
        switch ($categories_type) {
            case 'parent':
                $args['parent'] = 0;
                break;
            case 'featured':
                $args['meta_query'] = array(
                    array(
                        'key' => 'featured_category',
                        'value' => 'yes',
                        'compare' => '='
                    )
                );
                break;
        }
        
        $categories = get_terms($args);
        
        if (empty($categories) || is_wp_error($categories)) {
            wp_send_json_error(array(
                'message' => 'لا توجد فئات متاحة'
            ));
        }
        
        $categories_html = '';
        
        foreach ($categories as $category) {
            $category_link = get_term_link($category);
            $category_image = get_term_meta($category->term_id, 'thumbnail_id', true);
            $image_url = $category_image ? wp_get_attachment_image_url($category_image, 'thumbnail') : '';
            
            $categories_html .= '<div class="alam-corner-category-item">';
            $categories_html .= '<a href="' . esc_url($category_link) . '" class="alam-corner-category-link">';
            
            if ($image_url) {
                $categories_html .= '<div class="alam-corner-category-image">';
                $categories_html .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($category->name) . '">';
                $categories_html .= '</div>';
            } else {
                $categories_html .= '<div class="alam-corner-category-icon">';
                $categories_html .= '<svg width="24" height="24" viewBox="0 0 24 24" fill="none">';
                $categories_html .= '<rect x="3" y="3" width="7" height="7" stroke="currentColor" stroke-width="2"/>';
                $categories_html .= '<rect x="14" y="3" width="7" height="7" stroke="currentColor" stroke-width="2"/>';
                $categories_html .= '<rect x="3" y="14" width="7" height="7" stroke="currentColor" stroke-width="2"/>';
                $categories_html .= '<rect x="14" y="14" width="7" height="7" stroke="currentColor" stroke-width="2"/>';
                $categories_html .= '</svg>';
                $categories_html .= '</div>';
            }
            
            $categories_html .= '<div class="alam-corner-category-info">';
            $categories_html .= '<h4>' . esc_html($category->name) . '</h4>';
            
            if ($show_count) {
                $categories_html .= '<span class="alam-corner-category-count">(' . $category->count . ' منتج)</span>';
            }
            
            $categories_html .= '</div>';
            $categories_html .= '</a>';
            
            // Get subcategories if parent category
            if ($categories_type !== 'parent') {
                $subcategories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'parent' => $category->term_id,
                    'number' => 5
                ));
                
                if (!empty($subcategories) && !is_wp_error($subcategories)) {
                    $categories_html .= '<div class="alam-corner-subcategories">';
                    foreach ($subcategories as $subcategory) {
                        $sub_link = get_term_link($subcategory);
                        $categories_html .= '<a href="' . esc_url($sub_link) . '" class="alam-corner-subcategory-link">';
                        $categories_html .= esc_html($subcategory->name);
                        if ($show_count) {
                            $categories_html .= ' (' . $subcategory->count . ')';
                        }
                        $categories_html .= '</a>';
                    }
                    $categories_html .= '</div>';
                }
            }
            
            $categories_html .= '</div>';
        }
        
        wp_send_json_success(array(
            'categories' => $categories_html
        ));
    }
}

new Alam_Corner_Category_Button();