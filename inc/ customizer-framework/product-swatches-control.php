<?php
/**
 * Product Swatches & Border Control
 * Advanced customization for product image swatches and borders
 *
 * @package Al_Anika_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Product Swatches Customizer Controls
 */
class Al_Anika_Product_Swatches_Control {
    
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_swatches_controls' ) );
    }
    
    /**
     * Register Product Swatches Controls
     */
    public function register_swatches_controls( $wp_customize ) {
        
        // === PRODUCT SWATCHES PANEL ===
        $wp_customize->add_panel( 'al_anika_swatches_panel', array(
            'title'       => __( 'Product Swatches & Borders', 'al-anika' ),
            'priority'    => 27,
            'description' => __( 'Complete control over product image swatches, borders and visual effects', 'al-anika' ),
            'capability'  => 'edit_theme_options',
        ) );
        
        // === SWATCHES LAYOUT SECTION ===
        $wp_customize->add_section( 'al_anika_swatches_layout', array(
            'title'    => __( 'Swatches Layout & Position', 'al-anika' ),
            'panel'    => 'al_anika_swatches_panel',
            'priority' => 10,
        ) );
        
        // Swatches Position
        $wp_customize->add_setting( 'al_anika_swatches_position', array(
            'default'           => 'bottom_center',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_swatches_position', array(
            'label'    => __( 'Color Swatches Position', 'al-anika' ),
            'section'  => 'al_anika_swatches_layout',
            'type'     => 'select',
            'choices'  => array(
                'top_left'      => __( 'Top Left', 'al-anika' ),
                'top_right'     => __( 'Top Right', 'al-anika' ),
                'top_center'    => __( 'Top Center', 'al-anika' ),
                'bottom_left'   => __( 'Bottom Left', 'al-anika' ),
                'bottom_right'  => __( 'Bottom Right', 'al-anika' ),
                'bottom_center' => __( 'Bottom Center', 'al-anika' ),
                'overlay_center' => __( 'Overlay Center', 'al-anika' ),
                'sidebar_right' => __( 'Right Sidebar', 'al-anika' ),
                'under_image'   => __( 'Under Main Image', 'al-anika' ),
                'floating'      => __( 'Floating Panel', 'al-anika' ),
            ),
        ) );
        
        // Swatches Size
        $wp_customize->add_setting( 'al_anika_swatches_size', array(
            'default'           => '30',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_swatches_size', array(
            'label'       => __( 'Swatch Size (px)', 'al-anika' ),
            'section'     => 'al_anika_swatches_layout',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 15,
                'max'  => 60,
                'step' => 5,
            ),
        ) );
        
        // Swatches Shape
        $wp_customize->add_setting( 'al_anika_swatches_shape', array(
            'default'           => 'circle',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_swatches_shape', array(
            'label'    => __( 'Swatch Shape', 'al-anika' ),
            'section'  => 'al_anika_swatches_layout',
            'type'     => 'select',
            'choices'  => array(
                'circle'    => __( 'Circle', 'al-anika' ),
                'square'    => __( 'Square', 'al-anika' ),
                'rounded'   => __( 'Rounded Square', 'al-anika' ),
                'hexagon'   => __( 'Hexagon', 'al-anika' ),
                'diamond'   => __( 'Diamond', 'al-anika' ),
            ),
        ) );
        
        // === BORDER STYLING SECTION ===
        $wp_customize->add_section( 'al_anika_image_borders', array(
            'title'    => __( 'Product Image Borders', 'al-anika' ),
            'panel'    => 'al_anika_swatches_panel',
            'priority' => 20,
        ) );
        
        // Main Image Border Style
        $wp_customize->add_setting( 'al_anika_main_border_style', array(
            'default'           => 'solid',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_main_border_style', array(
            'label'    => __( 'Main Image Border Style', 'al-anika' ),
            'section'  => 'al_anika_image_borders',
            'type'     => 'select',
            'choices'  => array(
                'none'    => __( 'No Border', 'al-anika' ),
                'solid'   => __( 'Solid Border', 'al-anika' ),
                'dashed'  => __( 'Dashed Border', 'al-anika' ),
                'dotted'  => __( 'Dotted Border', 'al-anika' ),
                'double'  => __( 'Double Border', 'al-anika' ),
                'groove'  => __( 'Groove Border', 'al-anika' ),
                'ridge'   => __( 'Ridge Border', 'al-anika' ),
                'gradient' => __( 'Gradient Border', 'al-anika' ),
            ),
        ) );
        
        // Border Width
        $wp_customize->add_setting( 'al_anika_border_width', array(
            'default'           => '2',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_border_width', array(
            'label'       => __( 'Border Width (px)', 'al-anika' ),
            'section'     => 'al_anika_image_borders',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 20,
                'step' => 1,
            ),
        ) );
        
        // Border Color
        $wp_customize->add_setting( 'al_anika_border_color', array(
            'default'           => '#ff6b9d',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_border_color', array(
            'label'   => __( 'Border Color', 'al-anika' ),
            'section' => 'al_anika_image_borders',
        ) ) );
        
        // Border Radius
        $wp_customize->add_setting( 'al_anika_border_radius', array(
            'default'           => '10',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_border_radius', array(
            'label'       => __( 'Border Radius (px)', 'al-anika' ),
            'section'     => 'al_anika_image_borders',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 50,
                'step' => 5,
            ),
        ) );
        
        // === INNER BORDER SECTION ===
        $wp_customize->add_section( 'al_anika_inner_borders', array(
            'title'    => __( 'Inner Border Effects', 'al-anika' ),
            'panel'    => 'al_anika_swatches_panel',
            'priority' => 30,
        ) );
        
        // Inner Border Enable
        $wp_customize->add_setting( 'al_anika_inner_border_enable', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_inner_border_enable', array(
            'label'   => __( 'Enable Inner Border Effects', 'al-anika' ),
            'section' => 'al_anika_inner_borders',
            'type'    => 'checkbox',
        ) );
        
        // Inner Border Style
        $wp_customize->add_setting( 'al_anika_inner_border_style', array(
            'default'           => 'inset_shadow',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_inner_border_style', array(
            'label'    => __( 'Inner Border Effect Type', 'al-anika' ),
            'section'  => 'al_anika_inner_borders',
            'type'     => 'select',
            'choices'  => array(
                'inset_shadow'   => __( 'Inset Shadow', 'al-anika' ),
                'inner_glow'     => __( 'Inner Glow', 'al-anika' ),
                'gradient_overlay' => __( 'Gradient Overlay', 'al-anika' ),
                'double_border'  => __( 'Double Border', 'al-anika' ),
                'animated_pulse' => __( 'Animated Pulse', 'al-anika' ),
                'neon_glow'      => __( 'Neon Glow', 'al-anika' ),
                'vintage_frame'  => __( 'Vintage Frame', 'al-anika' ),
            ),
        ) );
        
        // Inner Border Color
        $wp_customize->add_setting( 'al_anika_inner_border_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_inner_border_color', array(
            'label'   => __( 'Inner Border Color', 'al-anika' ),
            'section' => 'al_anika_inner_borders',
        ) ) );
        
        // Inner Border Opacity
        $wp_customize->add_setting( 'al_anika_inner_border_opacity', array(
            'default'           => '80',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_inner_border_opacity', array(
            'label'       => __( 'Inner Border Opacity (%)', 'al-anika' ),
            'section'     => 'al_anika_inner_borders',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 100,
                'step' => 10,
            ),
        ) );
        
        // === HOVER EFFECTS SECTION ===
        $wp_customize->add_section( 'al_anika_hover_effects', array(
            'title'    => __( 'Hover & Interaction Effects', 'al-anika' ),
            'panel'    => 'al_anika_swatches_panel',
            'priority' => 40,
        ) );
        
        // Hover Border Color
        $wp_customize->add_setting( 'al_anika_hover_border_color', array(
            'default'           => '#4ecdc4',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_hover_border_color', array(
            'label'   => __( 'Hover Border Color', 'al-anika' ),
            'section' => 'al_anika_hover_effects',
        ) ) );
        
        // Hover Animation
        $wp_customize->add_setting( 'al_anika_hover_animation', array(
            'default'           => 'scale_border',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hover_animation', array(
            'label'    => __( 'Hover Animation Type', 'al-anika' ),
            'section'  => 'al_anika_hover_effects',
            'type'     => 'select',
            'choices'  => array(
                'none'           => __( 'No Animation', 'al-anika' ),
                'scale_border'   => __( 'Scale Border', 'al-anika' ),
                'glow_effect'    => __( 'Glow Effect', 'al-anika' ),
                'pulse_border'   => __( 'Pulse Border', 'al-anika' ),
                'rotate_border'  => __( 'Rotate Border', 'al-anika' ),
                'fade_transition' => __( 'Fade Transition', 'al-anika' ),
                'slide_border'   => __( 'Slide Border', 'al-anika' ),
                'zoom_effect'    => __( 'Zoom Effect', 'al-anika' ),
            ),
        ) );
        
        // Selected Swatch Indicator
        $wp_customize->add_setting( 'al_anika_selected_indicator', array(
            'default'           => 'checkmark',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_selected_indicator', array(
            'label'    => __( 'Selected Swatch Indicator', 'al-anika' ),
            'section'  => 'al_anika_hover_effects',
            'type'     => 'select',
            'choices'  => array(
                'checkmark'    => __( 'Checkmark Icon', 'al-anika' ),
                'border_thick' => __( 'Thick Border', 'al-anika' ),
                'glow_ring'    => __( 'Glow Ring', 'al-anika' ),
                'scale_up'     => __( 'Scale Up', 'al-anika' ),
                'badge'        => __( 'Selected Badge', 'al-anika' ),
                'shadow_deep'  => __( 'Deep Shadow', 'al-anika' ),
                'none'         => __( 'No Indicator', 'al-anika' ),
            ),
        ) );
    }
}

// Initialize the product swatches control
new Al_Anika_Product_Swatches_Control();
