<?php
/**
 * Advanced Filters & Positioning Control
 * Complete control over filter positions and functionality
 *
 * @package Al_Anika_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Filters Positioning Customizer Controls
 */
class Al_Anika_Filters_Positioning_Control {
    
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_filters_controls' ) );
    }
    
    /**
     * Register Filters Controls
     */
    public function register_filters_controls( $wp_customize ) {
        
        // === FILTERS PANEL ===
        $wp_customize->add_panel( 'al_anika_filters_panel', array(
            'title'       => __( 'Filters & Positioning Manager', 'al-anika' ),
            'priority'    => 26,
            'description' => __( 'Complete control over filters positioning, styling and functionality', 'al-anika' ),
            'capability'  => 'edit_theme_options',
        ) );
        
        // === FILTER POSITIONING SECTION ===
        $wp_customize->add_section( 'al_anika_filter_positioning', array(
            'title'    => __( 'Filter Positioning', 'al-anika' ),
            'panel'    => 'al_anika_filters_panel',
            'priority' => 10,
        ) );
        
        // Filter Container Position
        $wp_customize->add_setting( 'al_anika_filter_position', array(
            'default'           => 'sidebar_left',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_filter_position', array(
            'label'    => __( 'Filters Container Position', 'al-anika' ),
            'section'  => 'al_anika_filter_positioning',
            'type'     => 'select',
            'choices'  => array(
                'sidebar_left'    => __( 'Left Sidebar', 'al-anika' ),
                'sidebar_right'   => __( 'Right Sidebar', 'al-anika' ),
                'top_horizontal'  => __( 'Top Horizontal Bar', 'al-anika' ),
                'bottom_bar'      => __( 'Bottom Fixed Bar', 'al-anika' ),
                'floating_left'   => __( 'Floating Left Panel', 'al-anika' ),
                'floating_right'  => __( 'Floating Right Panel', 'al-anika' ),
                'overlay_modal'   => __( 'Overlay Modal', 'al-anika' ),
                'slide_in'        => __( 'Slide-in Panel', 'al-anika' ),
                'dropdown_top'    => __( 'Dropdown from Top', 'al-anika' ),
                'inline_content'  => __( 'Inline with Content', 'al-anika' ),
            ),
        ) );
        
        // Filter Width
        $wp_customize->add_setting( 'al_anika_filter_width', array(
            'default'           => '300',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_filter_width', array(
            'label'       => __( 'Filter Panel Width (px)', 'al-anika' ),
            'section'     => 'al_anika_filter_positioning',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 200,
                'max'  => 500,
                'step' => 20,
            ),
        ) );
        
        // Filter Z-Index
        $wp_customize->add_setting( 'al_anika_filter_zindex', array(
            'default'           => '1000',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_filter_zindex', array(
            'label'       => __( 'Filter Z-Index (Layer Priority)', 'al-anika' ),
            'section'     => 'al_anika_filter_positioning',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 9999,
                'step' => 1,
            ),
        ) );
        
        // === FILTER TYPES SECTION ===
        $wp_customize->add_section( 'al_anika_filter_types', array(
            'title'    => __( 'Filter Types & Display', 'al-anika' ),
            'panel'    => 'al_anika_filters_panel',
            'priority' => 20,
        ) );
        
        // Price Filter Position
        $wp_customize->add_setting( 'al_anika_price_filter_position', array(
            'default'           => 'top',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_price_filter_position', array(
            'label'    => __( 'Price Filter Position', 'al-anika' ),
            'section'  => 'al_anika_filter_types',
            'type'     => 'select',
            'choices'  => array(
                'top'      => __( 'Top of Filters', 'al-anika' ),
                'middle'   => __( 'Middle Position', 'al-anika' ),
                'bottom'   => __( 'Bottom of Filters', 'al-anika' ),
                'separate' => __( 'Separate Panel', 'al-anika' ),
                'hidden'   => __( 'Hidden/Disabled', 'al-anika' ),
            ),
        ) );
        
        // Category Filter Position
        $wp_customize->add_setting( 'al_anika_category_filter_position', array(
            'default'           => 'middle',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_category_filter_position', array(
            'label'    => __( 'Category Filter Position', 'al-anika' ),
            'section'  => 'al_anika_filter_types',
            'type'     => 'select',
            'choices'  => array(
                'top'      => __( 'Top of Filters', 'al-anika' ),
                'middle'   => __( 'Middle Position', 'al-anika' ),
                'bottom'   => __( 'Bottom of Filters', 'al-anika' ),
                'separate' => __( 'Separate Panel', 'al-anika' ),
                'hidden'   => __( 'Hidden/Disabled', 'al-anika' ),
            ),
        ) );
        
        // Color/Size Filter Position
        $wp_customize->add_setting( 'al_anika_attribute_filter_position', array(
            'default'           => 'bottom',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_attribute_filter_position', array(
            'label'    => __( 'Color/Size Filter Position', 'al-anika' ),
            'section'  => 'al_anika_filter_types',
            'type'     => 'select',
            'choices'  => array(
                'top'      => __( 'Top of Filters', 'al-anika' ),
                'middle'   => __( 'Middle Position', 'al-anika' ),
                'bottom'   => __( 'Bottom of Filters', 'al-anika' ),
                'separate' => __( 'Separate Panel', 'al-anika' ),
                'hidden'   => __( 'Hidden/Disabled', 'al-anika' ),
            ),
        ) );
        
        // === FILTER STYLING SECTION ===
        $wp_customize->add_section( 'al_anika_filter_styling', array(
            'title'    => __( 'Filter Styling & Colors', 'al-anika' ),
            'panel'    => 'al_anika_filters_panel',
            'priority' => 30,
        ) );
        
        // Filter Background Color
        $wp_customize->add_setting( 'al_anika_filter_bg_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_filter_bg_color', array(
            'label'   => __( 'Filter Background Color', 'al-anika' ),
            'section' => 'al_anika_filter_styling',
        ) ) );
        
        // Filter Border Color
        $wp_customize->add_setting( 'al_anika_filter_border_color', array(
            'default'           => '#e0e0e0',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_filter_border_color', array(
            'label'   => __( 'Filter Border Color', 'al-anika' ),
            'section' => 'al_anika_filter_styling',
        ) ) );
        
        // Filter Text Color
        $wp_customize->add_setting( 'al_anika_filter_text_color', array(
            'default'           => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_filter_text_color', array(
            'label'   => __( 'Filter Text Color', 'al-anika' ),
            'section' => 'al_anika_filter_styling',
        ) ) );
        
        // === FILTER BEHAVIOR SECTION ===
        $wp_customize->add_section( 'al_anika_filter_behavior', array(
            'title'    => __( 'Filter Behavior & Animation', 'al-anika' ),
            'panel'    => 'al_anika_filters_panel',
            'priority' => 40,
        ) );
        
        // Filter Animation Type
        $wp_customize->add_setting( 'al_anika_filter_animation', array(
            'default'           => 'slide',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_filter_animation', array(
            'label'    => __( 'Filter Animation Type', 'al-anika' ),
            'section'  => 'al_anika_filter_behavior',
            'type'     => 'select',
            'choices'  => array(
                'none'     => __( 'No Animation', 'al-anika' ),
                'fade'     => __( 'Fade In/Out', 'al-anika' ),
                'slide'    => __( 'Slide In/Out', 'al-anika' ),
                'zoom'     => __( 'Zoom In/Out', 'al-anika' ),
                'bounce'   => __( 'Bounce Effect', 'al-anika' ),
                'elastic'  => __( 'Elastic Effect', 'al-anika' ),
            ),
        ) );
        
        // Auto-hide Filters
        $wp_customize->add_setting( 'al_anika_filter_auto_hide', array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_filter_auto_hide', array(
            'label'   => __( 'Auto-hide Filters on Mobile', 'al-anika' ),
            'section' => 'al_anika_filter_behavior',
            'type'    => 'checkbox',
        ) );
        
        // Filter Toggle Button
        $wp_customize->add_setting( 'al_anika_filter_toggle_position', array(
            'default'           => 'top_left',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_filter_toggle_position', array(
            'label'    => __( 'Filter Toggle Button Position', 'al-anika' ),
            'section'  => 'al_anika_filter_behavior',
            'type'     => 'select',
            'choices'  => array(
                'top_left'     => __( 'Top Left', 'al-anika' ),
                'top_right'    => __( 'Top Right', 'al-anika' ),
                'top_center'   => __( 'Top Center', 'al-anika' ),
                'bottom_left'  => __( 'Bottom Left', 'al-anika' ),
                'bottom_right' => __( 'Bottom Right', 'al-anika' ),
                'floating'     => __( 'Floating Button', 'al-anika' ),
                'hidden'       => __( 'No Toggle Button', 'al-anika' ),
            ),
        ) );
    }
}

// Initialize the filters positioning control
new Al_Anika_Filters_Positioning_Control();
