<?php
/**
 * Advanced Customizer Framework for Alam Al Anika Theme
 * Provides comprehensive customization capabilities
 *
 * @package AlamAlAnika
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main Customizer Framework Class
 */
class Alam_Advanced_Customizer {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_advanced_controls' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'output_custom_styles' ) );
        add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
        
        // Include control classes
        $this->include_control_classes();
        
        // Include CSS output system
        if (file_exists(get_template_directory() . '/inc/customizer-framework/customizer-css-output.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/customizer-css-output.php';
        }
    }
    
    /**
     * Include custom control classes - only when customizer is active
     */
    private function include_control_classes() {
        // Check if we're in the customizer context
        if (!class_exists('WP_Customize_Control')) {
            return;
        }
        
        // Original controls
        if (file_exists(get_template_directory() . '/inc/customizer-framework/typography-control.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/typography-control.php';
        }
        if (file_exists(get_template_directory() . '/inc/customizer-framework/color-control.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/color-control.php';
        }
        if (file_exists(get_template_directory() . '/inc/customizer-framework/layout-control.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/layout-control.php';
        }
        if (file_exists(get_template_directory() . '/inc/customizer-framework/slider-control.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/slider-control.php';
        }
        if (file_exists(get_template_directory() . '/inc/customizer-framework/image-control.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/image-control.php';
        }
        
        // Advanced new controls
        if (file_exists(get_template_directory() . '/inc/customizer-framework/hero-sections-control.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/hero-sections-control.php';
        }
        if (file_exists(get_template_directory() . '/inc/customizer-framework/filters-positioning-control.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/filters-positioning-control.php';
        }
        if (file_exists(get_template_directory() . '/inc/customizer-framework/product-swatches-control.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/product-swatches-control.php';
        }
        if (file_exists(get_template_directory() . '/inc/customizer-framework/typography-color-control.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/typography-color-control.php';
        }
        if (file_exists(get_template_directory() . '/inc/customizer-framework/motion-animation-control.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/motion-animation-control.php';
        }
        if (file_exists(get_template_directory() . '/inc/customizer-framework/advertisement-control.php')) {
            require_once get_template_directory() . '/inc/customizer-framework/advertisement-control.php';
        }
    }
    
    /**
     * Register advanced customizer controls
     */
    public function register_advanced_controls( $wp_customize ) {
        
        // === TYPOGRAPHY PANEL ===
        $wp_customize->add_panel( 'alam_typography_panel', array(
            'title'    => __( 'Typography & Fonts', 'alam-al-anika' ),
            'priority' => 30,
            'description' => __( 'Complete control over all typography elements', 'alam-al-anika' ),
        ) );
        
        // === COLOR PANEL ===
        $wp_customize->add_panel( 'alam_colors_panel', array(
            'title'    => __( 'Colors & Styling', 'alam-al-anika' ),
            'priority' => 35,
            'description' => __( 'Advanced color customization for all theme elements', 'alam-al-anika' ),
        ) );
        
        // === LAYOUT PANEL ===
        $wp_customize->add_panel( 'alam_layout_panel', array(
            'title'    => __( 'Layout & Structure', 'alam-al-anika' ),
            'priority' => 40,
            'description' => __( 'Complete layout control with drag-and-drop positioning', 'alam-al-anika' ),
        ) );
        
        // === INTERACTIVE PANEL ===
        $wp_customize->add_panel( 'alam_interactive_panel', array(
            'title'    => __( 'Interactive Features', 'alam-al-anika' ),
            'priority' => 45,
            'description' => __( 'Animations, hover effects, and interactive elements', 'alam-al-anika' ),
        ) );
        
        // === PRODUCTS PANEL ===
        $wp_customize->add_panel( 'alam_products_panel', array(
            'title'    => __( 'Product Customization', 'alam-al-anika' ),
            'priority' => 50,
            'description' => __( 'Advanced product display and interaction controls', 'alam-al-anika' ),
        ) );
        
        // === NAVIGATION PANEL ===
        $wp_customize->add_panel( 'alam_navigation_panel', array(
            'title'    => __( 'Navigation & Menus', 'alam-al-anika' ),
            'priority' => 55,
            'description' => __( 'Mega menus, navigation styles, and positioning', 'alam-al-anika' ),
        ) );
        
        // === CONTENT SECTIONS PANEL ===
        $wp_customize->add_panel( 'alam_content_panel', array(
            'title'    => __( 'Content Sections', 'alam-al-anika' ),
            'priority' => 60,
            'description' => __( 'Hero sections, banners, and content management', 'alam-al-anika' ),
        ) );
        
        // Register sections for each panel
        $this->register_typography_sections( $wp_customize );
        $this->register_color_sections( $wp_customize );
        $this->register_layout_sections( $wp_customize );
        $this->register_interactive_sections( $wp_customize );
        $this->register_product_sections( $wp_customize );
        $this->register_navigation_sections( $wp_customize );
        $this->register_content_sections( $wp_customize );
    }
    
    /**
     * Register Typography Sections
     */
    private function register_typography_sections( $wp_customize ) {
        
        // General Typography Section
        $wp_customize->add_section( 'alam_typography_general', array(
            'title'    => __( 'General Typography', 'alam-al-anika' ),
            'panel'    => 'alam_typography_panel',
            'priority' => 10,
        ) );
        
        // Header Typography Section
        $wp_customize->add_section( 'alam_typography_header', array(
            'title'    => __( 'Header Typography', 'alam-al-anika' ),
            'panel'    => 'alam_typography_panel',
            'priority' => 20,
        ) );
        
        // Product Typography Section
        $wp_customize->add_section( 'alam_typography_products', array(
            'title'    => __( 'Product Typography', 'alam-al-anika' ),
            'panel'    => 'alam_typography_panel',
            'priority' => 30,
        ) );
        
        // Footer Typography Section
        $wp_customize->add_section( 'alam_typography_footer', array(
            'title'    => __( 'Footer Typography', 'alam-al-anika' ),
            'panel'    => 'alam_typography_panel',
            'priority' => 40,
        ) );
        
        // Add typography controls
        $this->add_typography_controls( $wp_customize );
    }
    
    /**
     * Add Typography Controls
     */
    private function add_typography_controls( $wp_customize ) {
        
        // Body Font Family
        $wp_customize->add_setting( 'alam_body_font_family', array(
            'default'           => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_body_font_family', array(
            'label'    => __( 'Body Font Family', 'alam-al-anika' ),
            'section'  => 'alam_typography_general',
            'type'     => 'select',
            'choices'  => array(
                'Inter'        => 'Inter (Modern)',
                'Roboto'       => 'Roboto (Clean)',
                'Poppins'      => 'Poppins (Friendly)',
                'Playfair Display' => 'Playfair Display (Elegant)',
                'Montserrat'   => 'Montserrat (Professional)',
                'Open Sans'    => 'Open Sans (Readable)',
                'Lato'         => 'Lato (Humanist)',
                'Source Sans Pro' => 'Source Sans Pro (Clean)',
                'Cairo'        => 'Cairo (Arabic)',
                'Tajawal'      => 'Tajawal (Arabic Modern)',
            ),
        ) );
        
        // Body Font Size
        $wp_customize->add_setting( 'alam_body_font_size', array(
            'default'           => '16',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_body_font_size', array(
            'label'       => __( 'Body Font Size (px)', 'alam-al-anika' ),
            'section'     => 'alam_typography_general',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
        ) );
        
        // Heading Font Family
        $wp_customize->add_setting( 'alam_heading_font_family', array(
            'default'           => 'Poppins',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_heading_font_family', array(
            'label'    => __( 'Heading Font Family', 'alam-al-anika' ),
            'section'  => 'alam_typography_general',
            'type'     => 'select',
            'choices'  => array(
                'Poppins'      => 'Poppins (Modern)',
                'Montserrat'   => 'Montserrat (Bold)',
                'Playfair Display' => 'Playfair Display (Elegant)',
                'Roboto'       => 'Roboto (Clean)',
                'Inter'        => 'Inter (Minimal)',
                'Cairo'        => 'Cairo (Arabic)',
                'Tajawal'      => 'Tajawal (Arabic Bold)',
            ),
        ) );
        
        // Header Logo Font Size
        $wp_customize->add_setting( 'alam_logo_font_size', array(
            'default'           => '24',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_logo_font_size', array(
            'label'       => __( 'Logo Font Size (px)', 'alam-al-anika' ),
            'section'     => 'alam_typography_header',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 16,
                'max'  => 48,
                'step' => 2,
            ),
        ) );
        
        // Navigation Font Size
        $wp_customize->add_setting( 'alam_nav_font_size', array(
            'default'           => '14',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_nav_font_size', array(
            'label'       => __( 'Navigation Font Size (px)', 'alam-al-anika' ),
            'section'     => 'alam_typography_header',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 20,
                'step' => 1,
            ),
        ) );
        
        // Product Title Font Size
        $wp_customize->add_setting( 'alam_product_title_size', array(
            'default'           => '18',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_product_title_size', array(
            'label'       => __( 'Product Title Font Size (px)', 'alam-al-anika' ),
            'section'     => 'alam_typography_products',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 14,
                'max'  => 28,
                'step' => 1,
            ),
        ) );
        
        // Product Price Font Size
        $wp_customize->add_setting( 'alam_product_price_size', array(
            'default'           => '16',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_product_price_size', array(
            'label'       => __( 'Product Price Font Size (px)', 'alam-al-anika' ),
            'section'     => 'alam_typography_products',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
        ) );
    }
    
    /**
     * Register Color Sections
     */
    private function register_color_sections( $wp_customize ) {
        
        // Primary Colors
        $wp_customize->add_section( 'alam_colors_primary', array(
            'title'    => __( 'Primary Colors', 'alam-al-anika' ),
            'panel'    => 'alam_colors_panel',
            'priority' => 10,
        ) );
        
        // Header Colors
        $wp_customize->add_section( 'alam_colors_header', array(
            'title'    => __( 'Header Colors', 'alam-al-anika' ),
            'panel'    => 'alam_colors_panel',
            'priority' => 20,
        ) );
        
        // Product Colors
        $wp_customize->add_section( 'alam_colors_products', array(
            'title'    => __( 'Product Colors', 'alam-al-anika' ),
            'panel'    => 'alam_colors_panel',
            'priority' => 30,
        ) );
        
        // Button Colors
        $wp_customize->add_section( 'alam_colors_buttons', array(
            'title'    => __( 'Button Colors', 'alam-al-anika' ),
            'panel'    => 'alam_colors_panel',
            'priority' => 40,
        ) );
        
        $this->add_color_controls( $wp_customize );
    }
    
    /**
     * Add Color Controls
     */
    private function add_color_controls( $wp_customize ) {
        
        // Primary Brand Color
        $wp_customize->add_setting( 'alam_primary_color', array(
            'default'           => '#FF6B6B',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alam_primary_color', array(
            'label'    => __( 'Primary Brand Color', 'alam-al-anika' ),
            'section'  => 'alam_colors_primary',
            'settings' => 'alam_primary_color',
        ) ) );
        
        // Secondary Color
        $wp_customize->add_setting( 'alam_secondary_color', array(
            'default'           => '#4ECDC4',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alam_secondary_color', array(
            'label'    => __( 'Secondary Color', 'alam-al-anika' ),
            'section'  => 'alam_colors_primary',
            'settings' => 'alam_secondary_color',
        ) ) );
        
        // Header Background Color
        $wp_customize->add_setting( 'alam_header_bg_color', array(
            'default'           => '#FFFFFF',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alam_header_bg_color', array(
            'label'    => __( 'Header Background Color', 'alam-al-anika' ),
            'section'  => 'alam_colors_header',
            'settings' => 'alam_header_bg_color',
        ) ) );
        
        // Header Text Color
        $wp_customize->add_setting( 'alam_header_text_color', array(
            'default'           => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alam_header_text_color', array(
            'label'    => __( 'Header Text Color', 'alam-al-anika' ),
            'section'  => 'alam_colors_header',
            'settings' => 'alam_header_text_color',
        ) ) );
        
        // Product Card Background
        $wp_customize->add_setting( 'alam_product_bg_color', array(
            'default'           => '#FFFFFF',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alam_product_bg_color', array(
            'label'    => __( 'Product Card Background', 'alam-al-anika' ),
            'section'  => 'alam_colors_products',
            'settings' => 'alam_product_bg_color',
        ) ) );
        
        // Button Primary Color
        $wp_customize->add_setting( 'alam_button_primary_color', array(
            'default'           => '#FF6B6B',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alam_button_primary_color', array(
            'label'    => __( 'Primary Button Color', 'alam-al-anika' ),
            'section'  => 'alam_colors_buttons',
            'settings' => 'alam_button_primary_color',
        ) ) );
        
        // Button Hover Color
        $wp_customize->add_setting( 'alam_button_hover_color', array(
            'default'           => '#FF5252',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alam_button_hover_color', array(
            'label'    => __( 'Button Hover Color', 'alam-al-anika' ),
            'section'  => 'alam_colors_buttons',
            'settings' => 'alam_button_hover_color',
        ) ) );
    }
    
    /**
     * Register Layout Sections
     */
    private function register_layout_sections( $wp_customize ) {
        
        // Header Layout Section
        $wp_customize->add_section( 'alam_layout_header', array(
            'title'    => __( 'Header Layouts', 'alam-al-anika' ),
            'panel'    => 'alam_layout_panel',
            'priority' => 10,
        ) );
        
        // Sidebar Layout Section
        $wp_customize->add_section( 'alam_layout_sidebar', array(
            'title'    => __( 'Sidebar Positioning', 'alam-al-anika' ),
            'panel'    => 'alam_layout_panel',
            'priority' => 20,
        ) );
        
        // Footer Layout Section
        $wp_customize->add_section( 'alam_layout_footer', array(
            'title'    => __( 'Footer Layouts', 'alam-al-anika' ),
            'panel'    => 'alam_layout_panel',
            'priority' => 30,
        ) );
        
        // Grid System Section
        $wp_customize->add_section( 'alam_layout_grid', array(
            'title'    => __( 'Grid & Spacing', 'alam-al-anika' ),
            'panel'    => 'alam_layout_panel',
            'priority' => 40,
        ) );
        
        // Responsive Controls Section
        $wp_customize->add_section( 'alam_layout_responsive', array(
            'title'    => __( 'Responsive Controls', 'alam-al-anika' ),
            'panel'    => 'alam_layout_panel',
            'priority' => 50,
        ) );
        
        $this->add_layout_controls( $wp_customize );
    }
    
    /**
     * Add Layout Controls
     */
    private function add_layout_controls( $wp_customize ) {
        
        // === HEADER LAYOUT CONTROLS ===
        
        // Header Layout Style
        $wp_customize->add_setting( 'alam_header_layout', array(
            'default'           => 'modern',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'alam_header_layout', array(
            'label'    => __( 'Header Layout Style', 'alam-al-anika' ),
            'section'  => 'alam_layout_header',
            'type'     => 'select',
            'choices'  => array(
                'minimal'     => __( 'Minimal (Logo + Cart)', 'alam-al-anika' ),
                'modern'      => __( 'Modern (Logo + Search + Cart)', 'alam-al-anika' ),
                'classic'     => __( 'Classic (Full Navigation)', 'alam-al-anika' ),
                'centered'    => __( 'Centered (Logo Center)', 'alam-al-anika' ),
                'mega'        => __( 'Mega Menu (Full Width)', 'alam-al-anika' ),
                'sticky'      => __( 'Sticky (Fixed on Scroll)', 'alam-al-anika' ),
                'transparent' => __( 'Transparent (Overlay)', 'alam-al-anika' ),
            ),
        ) );
        
        // Header Height
        $wp_customize->add_setting( 'alam_header_height', array(
            'default'           => '80',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_header_height', array(
            'label'       => __( 'Header Height (px)', 'alam-al-anika' ),
            'section'     => 'alam_layout_header',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 50,
                'max'  => 150,
                'step' => 5,
            ),
        ) );
        
        // Header Width Container
        $wp_customize->add_setting( 'alam_header_width', array(
            'default'           => 'container',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_header_width', array(
            'label'    => __( 'Header Width', 'alam-al-anika' ),
            'section'  => 'alam_layout_header',
            'type'     => 'select',
            'choices'  => array(
                'container'       => __( 'Contained (Max Width)', 'alam-al-anika' ),
                'full-width'      => __( 'Full Width', 'alam-al-anika' ),
                'wide'            => __( 'Wide (Extended)', 'alam-al-anika' ),
            ),
        ) );
        
        // === SIDEBAR CONTROLS ===
        
        // Sidebar Position
        $wp_customize->add_setting( 'alam_sidebar_position', array(
            'default'           => 'right',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'alam_sidebar_position', array(
            'label'    => __( 'Sidebar Position', 'alam-al-anika' ),
            'section'  => 'alam_layout_sidebar',
            'type'     => 'select',
            'choices'  => array(
                'left'     => __( 'Left Side', 'alam-al-anika' ),
                'right'    => __( 'Right Side', 'alam-al-anika' ),
                'top'      => __( 'Top (Above Content)', 'alam-al-anika' ),
                'bottom'   => __( 'Bottom (Below Content)', 'alam-al-anika' ),
                'none'     => __( 'No Sidebar (Full Width)', 'alam-al-anika' ),
            ),
        ) );
        
        // Sidebar Width
        $wp_customize->add_setting( 'alam_sidebar_width', array(
            'default'           => '25',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_sidebar_width', array(
            'label'       => __( 'Sidebar Width (%)', 'alam-al-anika' ),
            'section'     => 'alam_layout_sidebar',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 15,
                'max'  => 40,
                'step' => 5,
            ),
        ) );
        
        // Sidebar Style
        $wp_customize->add_setting( 'alam_sidebar_style', array(
            'default'           => 'default',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_sidebar_style', array(
            'label'    => __( 'Sidebar Style', 'alam-al-anika' ),
            'section'  => 'alam_layout_sidebar',
            'type'     => 'select',
            'choices'  => array(
                'default'     => __( 'Default (Standard)', 'alam-al-anika' ),
                'bordered'    => __( 'Bordered', 'alam-al-anika' ),
                'shadowed'    => __( 'With Shadow', 'alam-al-anika' ),
                'floating'    => __( 'Floating (Card Style)', 'alam-al-anika' ),
                'minimalist'  => __( 'Minimalist', 'alam-al-anika' ),
            ),
        ) );
        
        // === FOOTER LAYOUT CONTROLS ===
        
        // Footer Layout
        $wp_customize->add_setting( 'alam_footer_layout', array(
            'default'           => '4-columns',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'alam_footer_layout', array(
            'label'    => __( 'Footer Layout', 'alam-al-anika' ),
            'section'  => 'alam_layout_footer',
            'type'     => 'select',
            'choices'  => array(
                '1-column'    => __( '1 Column (Centered)', 'alam-al-anika' ),
                '2-columns'   => __( '2 Columns (50/50)', 'alam-al-anika' ),
                '3-columns'   => __( '3 Columns (33/33/33)', 'alam-al-anika' ),
                '4-columns'   => __( '4 Columns (25/25/25/25)', 'alam-al-anika' ),
                '5-columns'   => __( '5 Columns (20/20/20/20/20)', 'alam-al-anika' ),
                'asymmetric'  => __( 'Asymmetric (40/30/30)', 'alam-al-anika' ),
                'wide-center' => __( 'Wide Center (20/60/20)', 'alam-al-anika' ),
            ),
        ) );
        
        // Footer Widget Areas
        $wp_customize->add_setting( 'alam_footer_widgets', array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'alam_footer_widgets', array(
            'label'       => __( 'Number of Widget Areas', 'alam-al-anika' ),
            'section'     => 'alam_layout_footer',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 6,
                'step' => 1,
            ),
        ) );
        
        // === GRID SYSTEM CONTROLS ===
        
        // Container Max Width
        $wp_customize->add_setting( 'alam_container_width', array(
            'default'           => '1200',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_container_width', array(
            'label'       => __( 'Container Max Width (px)', 'alam-al-anika' ),
            'section'     => 'alam_layout_grid',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1600,
                'step' => 20,
            ),
        ) );
        
        // Grid Gutter Size
        $wp_customize->add_setting( 'alam_grid_gutter', array(
            'default'           => '20',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_grid_gutter', array(
            'label'       => __( 'Grid Gutter Size (px)', 'alam-al-anika' ),
            'section'     => 'alam_layout_grid',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 50,
                'step' => 5,
            ),
        ) );
        
        // Section Spacing
        $wp_customize->add_setting( 'alam_section_spacing', array(
            'default'           => '60',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'alam_section_spacing', array(
            'label'       => __( 'Section Spacing (px)', 'alam-al-anika' ),
            'section'     => 'alam_layout_grid',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 20,
                'max'  => 120,
                'step' => 10,
            ),
        ) );
        
        // === RESPONSIVE CONTROLS ===
        
        // Mobile Menu Style
        $wp_customize->add_setting( 'alam_mobile_menu_style', array(
            'default'           => 'slide',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'alam_mobile_menu_style', array(
            'label'    => __( 'Mobile Menu Style', 'alam-al-anika' ),
            'section'  => 'alam_layout_responsive',
            'type'     => 'select',
            'choices'  => array(
                'slide'       => __( 'Slide from Left', 'alam-al-anika' ),
                'slide-right' => __( 'Slide from Right', 'alam-al-anika' ),
                'slide-top'   => __( 'Slide from Top', 'alam-al-anika' ),
                'fade'        => __( 'Fade Overlay', 'alam-al-anika' ),
                'push'        => __( 'Push Content', 'alam-al-anika' ),
                'dropdown'    => __( 'Simple Dropdown', 'alam-al-anika' ),
            ),
        ) );
        
        // Mobile Breakpoint
        $wp_customize->add_setting( 'alam_mobile_breakpoint', array(
            'default'           => '768',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'alam_mobile_breakpoint', array(
            'label'       => __( 'Mobile Breakpoint (px)', 'alam-al-anika' ),
            'section'     => 'alam_layout_responsive',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 480,
                'max'  => 1024,
                'step' => 24,
            ),
        ) );
        
        // Tablet Breakpoint
        $wp_customize->add_setting( 'alam_tablet_breakpoint', array(
            'default'           => '1024',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'alam_tablet_breakpoint', array(
            'label'       => __( 'Tablet Breakpoint (px)', 'alam-al-anika' ),
            'section'     => 'alam_layout_responsive',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 768,
                'max'  => 1200,
                'step' => 24,
            ),
        ) );
    }
    
    private function register_interactive_sections( $wp_customize ) {
        // Interactive sections will be implemented in next phase
    }
    
    private function register_product_sections( $wp_customize ) {
        // Product sections will be implemented in next phase
    }
    
    private function register_navigation_sections( $wp_customize ) {
        // Navigation sections will be implemented in next phase
    }
    
    private function register_content_sections( $wp_customize ) {
        // Content sections will be implemented in next phase
    }
    
    /**
     * Output custom styles
     */
    public function output_custom_styles() {
        $custom_css = $this->generate_custom_css();
        if ( ! empty( $custom_css ) ) {
            wp_add_inline_style( 'alam-main', $custom_css );
        }
    }
    
    /**
     * Generate custom CSS from customizer settings
     */
    private function generate_custom_css() {
        $css = '';
        
        // Typography CSS
        $body_font = get_theme_mod( 'alam_body_font_family', 'Inter' );
        $body_size = get_theme_mod( 'alam_body_font_size', '16' );
        $heading_font = get_theme_mod( 'alam_heading_font_family', 'Poppins' );
        $logo_size = get_theme_mod( 'alam_logo_font_size', '24' );
        $nav_size = get_theme_mod( 'alam_nav_font_size', '14' );
        $product_title_size = get_theme_mod( 'alam_product_title_size', '18' );
        $product_price_size = get_theme_mod( 'alam_product_price_size', '16' );
        
        // Color Settings
        $primary_color = get_theme_mod( 'alam_primary_color', '#FF6B6B' );
        $secondary_color = get_theme_mod( 'alam_secondary_color', '#4ECDC4' );
        $header_bg = get_theme_mod( 'alam_header_bg_color', '#FFFFFF' );
        $header_text = get_theme_mod( 'alam_header_text_color', '#333333' );
        $product_bg = get_theme_mod( 'alam_product_bg_color', '#FFFFFF' );
        $button_primary = get_theme_mod( 'alam_button_primary_color', '#FF6B6B' );
        $button_hover = get_theme_mod( 'alam_button_hover_color', '#FF5252' );
        
        // Layout Settings
        $header_height = get_theme_mod( 'alam_header_height', '80' );
        $header_width = get_theme_mod( 'alam_header_width', 'container' );
        $sidebar_position = get_theme_mod( 'alam_sidebar_position', 'right' );
        $sidebar_width = get_theme_mod( 'alam_sidebar_width', '25' );
        $sidebar_style = get_theme_mod( 'alam_sidebar_style', 'default' );
        $container_width = get_theme_mod( 'alam_container_width', '1200' );
        $grid_gutter = get_theme_mod( 'alam_grid_gutter', '20' );
        $section_spacing = get_theme_mod( 'alam_section_spacing', '60' );
        
        // Generate CSS
        $css .= "
        /* Typography Customizations */
        body {
            font-family: '{$body_font}', sans-serif;
            font-size: {$body_size}px;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: '{$heading_font}', sans-serif;
        }
        
        .site-title {
            font-size: {$logo_size}px;
        }
        
        .main-navigation a {
            font-size: {$nav_size}px;
        }
        
        .woocommerce .product-title,
        .woocommerce-loop-product__title {
            font-size: {$product_title_size}px;
        }
        
        .price {
            font-size: {$product_price_size}px;
        }
        
        /* Color Customizations */
        :root {
            --alam-primary: {$primary_color};
            --alam-secondary: {$secondary_color};
            --alam-header-bg: {$header_bg};
            --alam-header-text: {$header_text};
            --alam-product-bg: {$product_bg};
            --alam-button-primary: {$button_primary};
            --alam-button-hover: {$button_hover};
            --alam-container-width: {$container_width}px;
            --alam-header-height: {$header_height}px;
            --alam-sidebar-width: {$sidebar_width}%;
            --alam-grid-gutter: {$grid_gutter}px;
            --alam-section-spacing: {$section_spacing}px;
        }
        
        /* Layout Customizations */
        .site-header {
            background-color: {$header_bg};
            color: {$header_text};
            height: {$header_height}px;
        }
        
        .site-header .container {
            max-width: " . ($header_width === 'full-width' ? '100%' : ($header_width === 'wide' ? '1400px' : $container_width . 'px')) . ";
        }
        
        .site-container,
        .container {
            max-width: {$container_width}px;
        }
        
        /* Sidebar Layout Styles */
        .content-sidebar-wrap {
            display: grid;
            gap: {$grid_gutter}px;
        }
        
        " . $this->get_sidebar_css($sidebar_position, $sidebar_width, $sidebar_style) . "
        
        /* Grid System */
        .grid-gutter {
            gap: {$grid_gutter}px;
        }
        
        .section-spacing {
            margin-top: {$section_spacing}px;
            margin-bottom: {$section_spacing}px;
        }
        
        /* Product Grid */
        .products,
        .woocommerce .products {
            display: grid;
            gap: {$grid_gutter}px;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
        
        .product-card {
            background-color: {$product_bg};
        }
        
        .btn-primary,
        .button.primary,
        .woocommerce #respond input#submit,
        .woocommerce a.button,
        .woocommerce button.button,
        .woocommerce input.button {
            background-color: {$button_primary};
        }
        
        .btn-primary:hover,
        .button.primary:hover,
        .woocommerce #respond input#submit:hover,
        .woocommerce a.button:hover,
        .woocommerce button.button:hover,
        .woocommerce input.button:hover {
            background-color: {$button_hover};
        }
        ";
        
        return wp_strip_all_tags( $css );
    }
    
    /**
     * Get Sidebar CSS based on position and style
     */
    private function get_sidebar_css( $position, $width, $style ) {
        $main_width = 100 - intval($width);
        $css = '';
        
        switch ( $position ) {
            case 'left':
                $css .= "
                .content-sidebar-wrap {
                    grid-template-columns: {$width}% {$main_width}%;
                    grid-template-areas: 'sidebar content';
                }
                .content-area { grid-area: content; }
                .widget-area { grid-area: sidebar; }
                ";
                break;
                
            case 'right':
                $css .= "
                .content-sidebar-wrap {
                    grid-template-columns: {$main_width}% {$width}%;
                    grid-template-areas: 'content sidebar';
                }
                .content-area { grid-area: content; }
                .widget-area { grid-area: sidebar; }
                ";
                break;
                
            case 'top':
                $css .= "
                .content-sidebar-wrap {
                    grid-template-columns: 1fr;
                    grid-template-areas: 'sidebar' 'content';
                }
                .content-area { grid-area: content; }
                .widget-area { grid-area: sidebar; margin-bottom: var(--alam-section-spacing); }
                ";
                break;
                
            case 'bottom':
                $css .= "
                .content-sidebar-wrap {
                    grid-template-columns: 1fr;
                    grid-template-areas: 'content' 'sidebar';
                }
                .content-area { grid-area: content; }
                .widget-area { grid-area: sidebar; margin-top: var(--alam-section-spacing); }
                ";
                break;
                
            case 'none':
                $css .= "
                .content-sidebar-wrap {
                    grid-template-columns: 1fr;
                }
                .widget-area { display: none; }
                .content-area { width: 100%; }
                ";
                break;
        }
        
        // Add sidebar style CSS
        switch ( $style ) {
            case 'bordered':
                $css .= "
                .widget-area {
                    border: 1px solid #e0e0e0;
                    padding: 20px;
                    border-radius: 8px;
                }
                ";
                break;
                
            case 'shadowed':
                $css .= "
                .widget-area {
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    padding: 20px;
                    border-radius: 8px;
                }
                ";
                break;
                
            case 'floating':
                $css .= "
                .widget-area {
                    background: #fff;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                    padding: 30px;
                    border-radius: 12px;
                    margin: 20px 0;
                }
                ";
                break;
                
            case 'minimalist':
                $css .= "
                .widget-area {
                    background: transparent;
                    padding: 10px;
                }
                .widget-area .widget {
                    border: none;
                    box-shadow: none;
                    background: transparent;
                }
                ";
                break;
        }
        
        return $css;
    }
    
    /**
     * Enqueue customizer preview JavaScript
     */
    public function customize_preview_js() {
        wp_enqueue_script(
            'alam-customizer-preview',
            get_template_directory_uri() . '/assets/js/customizer-preview.js',
            array( 'customize-preview' ),
            AL_ANIKA_VERSION,
            true
        );
    }
}

// Initialize the customizer framework at the right hook
function alam_init_advanced_customizer() {
    new Alam_Advanced_Customizer();
}
add_action('customize_register', 'alam_init_advanced_customizer', 5);
