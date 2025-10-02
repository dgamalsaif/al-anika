<?php
/**
 * Advanced Typography & Color Control
 * Complete control over all text colors and typography in the theme
 *
 * @package Al_Anika_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Typography & Color Customizer Controls
 */
class Al_Anika_Typography_Color_Control {
    
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_typography_controls' ) );
    }
    
    /**
     * Register Typography & Color Controls
     */
    public function register_typography_controls( $wp_customize ) {
        
        // === TYPOGRAPHY & COLORS PANEL ===
        $wp_customize->add_panel( 'al_anika_typography_panel', array(
            'title'       => __( 'Typography & Text Colors', 'al-anika' ),
            'priority'    => 28,
            'description' => __( 'Complete control over fonts, text colors, and typography throughout the theme', 'al-anika' ),
            'capability'  => 'edit_theme_options',
        ) );
        
        // === GLOBAL TYPOGRAPHY SECTION ===
        $wp_customize->add_section( 'al_anika_global_typography', array(
            'title'    => __( 'Global Typography Settings', 'al-anika' ),
            'panel'    => 'al_anika_typography_panel',
            'priority' => 10,
        ) );
        
        // Primary Font Family
        $wp_customize->add_setting( 'al_anika_primary_font', array(
            'default'           => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_primary_font', array(
            'label'    => __( 'Primary Font Family', 'al-anika' ),
            'section'  => 'al_anika_global_typography',
            'type'     => 'select',
            'choices'  => array(
                'Inter'       => __( 'Inter (Modern)', 'al-anika' ),
                'Poppins'     => __( 'Poppins (Friendly)', 'al-anika' ),
                'Roboto'      => __( 'Roboto (Clean)', 'al-anika' ),
                'Open Sans'   => __( 'Open Sans (Readable)', 'al-anika' ),
                'Lato'        => __( 'Lato (Professional)', 'al-anika' ),
                'Montserrat'  => __( 'Montserrat (Elegant)', 'al-anika' ),
                'Nunito'      => __( 'Nunito (Rounded)', 'al-anika' ),
                'Source Sans Pro' => __( 'Source Sans Pro', 'al-anika' ),
                'Playfair Display' => __( 'Playfair Display (Luxury)', 'al-anika' ),
                'Crimson Text' => __( 'Crimson Text (Editorial)', 'al-anika' ),
            ),
        ) );
        
        // Secondary Font Family
        $wp_customize->add_setting( 'al_anika_secondary_font', array(
            'default'           => 'Poppins',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_secondary_font', array(
            'label'    => __( 'Secondary Font Family (Headings)', 'al-anika' ),
            'section'  => 'al_anika_global_typography',
            'type'     => 'select',
            'choices'  => array(
                'Poppins'     => __( 'Poppins (Friendly)', 'al-anika' ),
                'Inter'       => __( 'Inter (Modern)', 'al-anika' ),
                'Montserrat'  => __( 'Montserrat (Elegant)', 'al-anika' ),
                'Roboto'      => __( 'Roboto (Clean)', 'al-anika' ),
                'Playfair Display' => __( 'Playfair Display (Luxury)', 'al-anika' ),
                'Oswald'      => __( 'Oswald (Bold)', 'al-anika' ),
                'Raleway'     => __( 'Raleway (Sophisticated)', 'al-anika' ),
                'Merriweather' => __( 'Merriweather (Classic)', 'al-anika' ),
            ),
        ) );
        
        // Global Font Size
        $wp_customize->add_setting( 'al_anika_base_font_size', array(
            'default'           => '16',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_base_font_size', array(
            'label'       => __( 'Base Font Size (px)', 'al-anika' ),
            'section'     => 'al_anika_global_typography',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
        ) );
        
        // === HEADINGS SECTION ===
        $wp_customize->add_section( 'al_anika_headings_typography', array(
            'title'    => __( 'Headings Typography', 'al-anika' ),
            'panel'    => 'al_anika_typography_panel',
            'priority' => 20,
        ) );
        
        // H1 Color
        $wp_customize->add_setting( 'al_anika_h1_color', array(
            'default'           => '#2c3e50',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_h1_color', array(
            'label'   => __( 'H1 Heading Color', 'al-anika' ),
            'section' => 'al_anika_headings_typography',
        ) ) );
        
        // H1 Font Size
        $wp_customize->add_setting( 'al_anika_h1_size', array(
            'default'           => '36',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_h1_size', array(
            'label'       => __( 'H1 Font Size (px)', 'al-anika' ),
            'section'     => 'al_anika_headings_typography',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 20,
                'max'  => 60,
                'step' => 2,
            ),
        ) );
        
        // H2 Color
        $wp_customize->add_setting( 'al_anika_h2_color', array(
            'default'           => '#34495e',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_h2_color', array(
            'label'   => __( 'H2 Heading Color', 'al-anika' ),
            'section' => 'al_anika_headings_typography',
        ) ) );
        
        // H2 Font Size
        $wp_customize->add_setting( 'al_anika_h2_size', array(
            'default'           => '30',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_h2_size', array(
            'label'       => __( 'H2 Font Size (px)', 'al-anika' ),
            'section'     => 'al_anika_headings_typography',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 18,
                'max'  => 48,
                'step' => 2,
            ),
        ) );
        
        // H3 Color
        $wp_customize->add_setting( 'al_anika_h3_color', array(
            'default'           => '#3498db',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_h3_color', array(
            'label'   => __( 'H3 Heading Color', 'al-anika' ),
            'section' => 'al_anika_headings_typography',
        ) ) );
        
        // === BODY TEXT SECTION ===
        $wp_customize->add_section( 'al_anika_body_typography', array(
            'title'    => __( 'Body Text Typography', 'al-anika' ),
            'panel'    => 'al_anika_typography_panel',
            'priority' => 30,
        ) );
        
        // Body Text Color
        $wp_customize->add_setting( 'al_anika_body_color', array(
            'default'           => '#555555',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_body_color', array(
            'label'   => __( 'Body Text Color', 'al-anika' ),
            'section' => 'al_anika_body_typography',
        ) ) );
        
        // Paragraph Color
        $wp_customize->add_setting( 'al_anika_paragraph_color', array(
            'default'           => '#666666',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_paragraph_color', array(
            'label'   => __( 'Paragraph Text Color', 'al-anika' ),
            'section' => 'al_anika_body_typography',
        ) ) );
        
        // Link Color
        $wp_customize->add_setting( 'al_anika_link_color', array(
            'default'           => '#e74c3c',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_link_color', array(
            'label'   => __( 'Link Color', 'al-anika' ),
            'section' => 'al_anika_body_typography',
        ) ) );
        
        // Link Hover Color
        $wp_customize->add_setting( 'al_anika_link_hover_color', array(
            'default'           => '#c0392b',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_link_hover_color', array(
            'label'   => __( 'Link Hover Color', 'al-anika' ),
            'section' => 'al_anika_body_typography',
        ) ) );
        
        // === PRODUCT TEXT SECTION ===
        $wp_customize->add_section( 'al_anika_product_typography', array(
            'title'    => __( 'Product Text Colors', 'al-anika' ),
            'panel'    => 'al_anika_typography_panel',
            'priority' => 40,
        ) );
        
        // Product Title Color
        $wp_customize->add_setting( 'al_anika_product_title_color', array(
            'default'           => '#2c3e50',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_product_title_color', array(
            'label'   => __( 'Product Title Color', 'al-anika' ),
            'section' => 'al_anika_product_typography',
        ) ) );
        
        // Product Price Color
        $wp_customize->add_setting( 'al_anika_product_price_color', array(
            'default'           => '#e74c3c',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_product_price_color', array(
            'label'   => __( 'Product Price Color', 'al-anika' ),
            'section' => 'al_anika_product_typography',
        ) ) );
        
        // Sale Price Color
        $wp_customize->add_setting( 'al_anika_sale_price_color', array(
            'default'           => '#27ae60',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_sale_price_color', array(
            'label'   => __( 'Sale Price Color', 'al-anika' ),
            'section' => 'al_anika_product_typography',
        ) ) );
        
        // Original Price Color (Strikethrough)
        $wp_customize->add_setting( 'al_anika_original_price_color', array(
            'default'           => '#95a5a6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_original_price_color', array(
            'label'   => __( 'Original Price Color (Crossed)', 'al-anika' ),
            'section' => 'al_anika_product_typography',
        ) ) );
        
        // === BUTTON TEXT SECTION ===
        $wp_customize->add_section( 'al_anika_button_typography', array(
            'title'    => __( 'Button Text Colors', 'al-anika' ),
            'panel'    => 'al_anika_typography_panel',
            'priority' => 50,
        ) );
        
        // Primary Button Text Color
        $wp_customize->add_setting( 'al_anika_primary_btn_text_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_primary_btn_text_color', array(
            'label'   => __( 'Primary Button Text Color', 'al-anika' ),
            'section' => 'al_anika_button_typography',
        ) ) );
        
        // Secondary Button Text Color
        $wp_customize->add_setting( 'al_anika_secondary_btn_text_color', array(
            'default'           => '#2c3e50',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_secondary_btn_text_color', array(
            'label'   => __( 'Secondary Button Text Color', 'al-anika' ),
            'section' => 'al_anika_button_typography',
        ) ) );
        
        // Button Hover Text Color
        $wp_customize->add_setting( 'al_anika_btn_hover_text_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_btn_hover_text_color', array(
            'label'   => __( 'Button Hover Text Color', 'al-anika' ),
            'section' => 'al_anika_button_typography',
        ) ) );
        
        // === NAVIGATION TEXT SECTION ===
        $wp_customize->add_section( 'al_anika_nav_typography', array(
            'title'    => __( 'Navigation Text Colors', 'al-anika' ),
            'panel'    => 'al_anika_typography_panel',
            'priority' => 60,
        ) );
        
        // Menu Text Color
        $wp_customize->add_setting( 'al_anika_menu_text_color', array(
            'default'           => '#2c3e50',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_menu_text_color', array(
            'label'   => __( 'Menu Text Color', 'al-anika' ),
            'section' => 'al_anika_nav_typography',
        ) ) );
        
        // Menu Hover Text Color
        $wp_customize->add_setting( 'al_anika_menu_hover_text_color', array(
            'default'           => '#e74c3c',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_menu_hover_text_color', array(
            'label'   => __( 'Menu Hover Text Color', 'al-anika' ),
            'section' => 'al_anika_nav_typography',
        ) ) );
        
        // Submenu Text Color
        $wp_customize->add_setting( 'al_anika_submenu_text_color', array(
            'default'           => '#555555',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_submenu_text_color', array(
            'label'   => __( 'Submenu Text Color', 'al-anika' ),
            'section' => 'al_anika_nav_typography',
        ) ) );
    }
}

// Initialize the typography color control
new Al_Anika_Typography_Color_Control();
