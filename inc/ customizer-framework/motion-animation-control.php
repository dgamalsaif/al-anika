<?php
/**
 * Advanced Motion & Animation Control
 * Complete control over animations and motion effects
 *
 * @package Al_Anika_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Motion & Animation Customizer Controls
 */
class Al_Anika_Motion_Animation_Control {
    
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_motion_controls' ) );
    }
    
    /**
     * Register Motion & Animation Controls
     */
    public function register_motion_controls( $wp_customize ) {
        
        // === MOTION & ANIMATIONS PANEL ===
        $wp_customize->add_panel( 'al_anika_motion_panel', array(
            'title'       => __( 'Motion & Animation Control', 'al-anika' ),
            'priority'    => 29,
            'description' => __( 'Complete control over all animations, transitions and motion effects', 'al-anika' ),
            'capability'  => 'edit_theme_options',
        ) );
        
        // === GLOBAL ANIMATION SECTION ===
        $wp_customize->add_section( 'al_anika_global_animations', array(
            'title'    => __( 'Global Animation Settings', 'al-anika' ),
            'panel'    => 'al_anika_motion_panel',
            'priority' => 10,
        ) );
        
        // Enable Global Animations
        $wp_customize->add_setting( 'al_anika_animations_enabled', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_animations_enabled', array(
            'label'   => __( 'Enable Global Animations', 'al-anika' ),
            'section' => 'al_anika_global_animations',
            'type'    => 'checkbox',
        ) );
        
        // Animation Performance Mode
        $wp_customize->add_setting( 'al_anika_animation_performance', array(
            'default'           => 'balanced',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_animation_performance', array(
            'label'    => __( 'Animation Performance Mode', 'al-anika' ),
            'section'  => 'al_anika_global_animations',
            'type'     => 'select',
            'choices'  => array(
                'minimal'   => __( 'Minimal (Best Performance)', 'al-anika' ),
                'balanced'  => __( 'Balanced (Recommended)', 'al-anika' ),
                'enhanced'  => __( 'Enhanced (Rich Experience)', 'al-anika' ),
                'maximum'   => __( 'Maximum (All Effects)', 'al-anika' ),
            ),
        ) );
        
        // Global Animation Speed
        $wp_customize->add_setting( 'al_anika_animation_speed', array(
            'default'           => '400',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_animation_speed', array(
            'label'       => __( 'Global Animation Speed (ms)', 'al-anika' ),
            'section'     => 'al_anika_global_animations',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 100,
                'max'  => 2000,
                'step' => 50,
            ),
        ) );
        
        // === PAGE LOADING ANIMATIONS ===
        $wp_customize->add_section( 'al_anika_loading_animations', array(
            'title'    => __( 'Page Loading Animations', 'al-anika' ),
            'panel'    => 'al_anika_motion_panel',
            'priority' => 20,
        ) );
        
        // Page Load Animation Type
        $wp_customize->add_setting( 'al_anika_page_load_animation', array(
            'default'           => 'fade_in',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_page_load_animation', array(
            'label'    => __( 'Page Load Animation', 'al-anika' ),
            'section'  => 'al_anika_loading_animations',
            'type'     => 'select',
            'choices'  => array(
                'none'          => __( 'No Animation', 'al-anika' ),
                'fade_in'       => __( 'Fade In', 'al-anika' ),
                'slide_up'      => __( 'Slide Up', 'al-anika' ),
                'slide_down'    => __( 'Slide Down', 'al-anika' ),
                'zoom_in'       => __( 'Zoom In', 'al-anika' ),
                'curtain_up'    => __( 'Curtain Up', 'al-anika' ),
                'wipe_right'    => __( 'Wipe Right', 'al-anika' ),
                'split_reveal'  => __( 'Split Reveal', 'al-anika' ),
            ),
        ) );
        
        // Content Stagger Animation
        $wp_customize->add_setting( 'al_anika_content_stagger', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_content_stagger', array(
            'label'   => __( 'Enable Content Stagger Animation', 'al-anika' ),
            'section' => 'al_anika_loading_animations',
            'type'    => 'checkbox',
        ) );
        
        // === SCROLL ANIMATIONS ===
        $wp_customize->add_section( 'al_anika_scroll_animations', array(
            'title'    => __( 'Scroll Animations', 'al-anika' ),
            'panel'    => 'al_anika_motion_panel',
            'priority' => 30,
        ) );
        
        // Scroll Animation Type
        $wp_customize->add_setting( 'al_anika_scroll_animation_type', array(
            'default'           => 'fade_up',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_scroll_animation_type', array(
            'label'    => __( 'Scroll Animation Type', 'al-anika' ),
            'section'  => 'al_anika_scroll_animations',
            'type'     => 'select',
            'choices'  => array(
                'fade_up'       => __( 'Fade Up', 'al-anika' ),
                'fade_down'     => __( 'Fade Down', 'al-anika' ),
                'fade_left'     => __( 'Fade Left', 'al-anika' ),
                'fade_right'    => __( 'Fade Right', 'al-anika' ),
                'zoom_in'       => __( 'Zoom In', 'al-anika' ),
                'zoom_out'      => __( 'Zoom Out', 'al-anika' ),
                'flip_left'     => __( 'Flip Left', 'al-anika' ),
                'flip_right'    => __( 'Flip Right', 'al-anika' ),
                'slide_up'      => __( 'Slide Up', 'al-anika' ),
                'slide_down'    => __( 'Slide Down', 'al-anika' ),
            ),
        ) );
        
        // Parallax Scrolling
        $wp_customize->add_setting( 'al_anika_parallax_enabled', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_parallax_enabled', array(
            'label'   => __( 'Enable Parallax Scrolling', 'al-anika' ),
            'section' => 'al_anika_scroll_animations',
            'type'    => 'checkbox',
        ) );
        
        // Scroll Speed
        $wp_customize->add_setting( 'al_anika_scroll_speed', array(
            'default'           => '50',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_scroll_speed', array(
            'label'       => __( 'Parallax Scroll Speed (%)', 'al-anika' ),
            'section'     => 'al_anika_scroll_animations',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 100,
                'step' => 10,
            ),
        ) );
        
        // === HOVER ANIMATIONS ===
        $wp_customize->add_section( 'al_anika_hover_animations', array(
            'title'    => __( 'Hover & Interaction Animations', 'al-anika' ),
            'panel'    => 'al_anika_motion_panel',
            'priority' => 40,
        ) );
        
        // Product Hover Animation
        $wp_customize->add_setting( 'al_anika_product_hover_animation', array(
            'default'           => 'scale_rotate',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_product_hover_animation', array(
            'label'    => __( 'Product Hover Animation', 'al-anika' ),
            'section'  => 'al_anika_hover_animations',
            'type'     => 'select',
            'choices'  => array(
                'none'          => __( 'No Animation', 'al-anika' ),
                'scale'         => __( 'Scale Up', 'al-anika' ),
                'scale_rotate'  => __( 'Scale + Rotate', 'al-anika' ),
                'lift'          => __( 'Lift Effect', 'al-anika' ),
                'tilt'          => __( 'Tilt Effect', 'al-anika' ),
                'glow'          => __( 'Glow Effect', 'al-anika' ),
                'bounce'        => __( 'Bounce', 'al-anika' ),
                'pulse'         => __( 'Pulse', 'al-anika' ),
                'shake'         => __( 'Shake', 'al-anika' ),
                'flip'          => __( 'Flip Card', 'al-anika' ),
            ),
        ) );
        
        // Button Hover Animation
        $wp_customize->add_setting( 'al_anika_button_hover_animation', array(
            'default'           => 'slide_fill',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_button_hover_animation', array(
            'label'    => __( 'Button Hover Animation', 'al-anika' ),
            'section'  => 'al_anika_hover_animations',
            'type'     => 'select',
            'choices'  => array(
                'none'          => __( 'No Animation', 'al-anika' ),
                'slide_fill'    => __( 'Slide Fill', 'al-anika' ),
                'fade_fill'     => __( 'Fade Fill', 'al-anika' ),
                'border_expand' => __( 'Border Expand', 'al-anika' ),
                'glow_pulse'    => __( 'Glow Pulse', 'al-anika' ),
                'ripple'        => __( 'Ripple Effect', 'al-anika' ),
                'rotate_3d'     => __( 'Rotate 3D', 'al-anika' ),
                'bounce_in'     => __( 'Bounce In', 'al-anika' ),
            ),
        ) );
        
        // === MICRO-INTERACTIONS ===
        $wp_customize->add_section( 'al_anika_micro_interactions', array(
            'title'    => __( 'Micro-Interactions', 'al-anika' ),
            'panel'    => 'al_anika_motion_panel',
            'priority' => 50,
        ) );
        
        // Enable Micro-Interactions
        $wp_customize->add_setting( 'al_anika_micro_interactions', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_micro_interactions', array(
            'label'   => __( 'Enable Micro-Interactions', 'al-anika' ),
            'section' => 'al_anika_micro_interactions',
            'type'    => 'checkbox',
        ) );
        
        // Click Feedback Animation
        $wp_customize->add_setting( 'al_anika_click_feedback', array(
            'default'           => 'ripple',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_click_feedback', array(
            'label'    => __( 'Click Feedback Animation', 'al-anika' ),
            'section'  => 'al_anika_micro_interactions',
            'type'     => 'select',
            'choices'  => array(
                'none'      => __( 'No Feedback', 'al-anika' ),
                'ripple'    => __( 'Ripple Effect', 'al-anika' ),
                'pulse'     => __( 'Pulse', 'al-anika' ),
                'bounce'    => __( 'Bounce', 'al-anika' ),
                'scale'     => __( 'Scale', 'al-anika' ),
                'glow'      => __( 'Glow Flash', 'al-anika' ),
            ),
        ) );
        
        // Form Input Focus Animation
        $wp_customize->add_setting( 'al_anika_input_focus_animation', array(
            'default'           => 'glow_border',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_input_focus_animation', array(
            'label'    => __( 'Form Input Focus Animation', 'al-anika' ),
            'section'  => 'al_anika_micro_interactions',
            'type'     => 'select',
            'choices'  => array(
                'none'          => __( 'No Animation', 'al-anika' ),
                'glow_border'   => __( 'Glow Border', 'al-anika' ),
                'scale_border'  => __( 'Scale Border', 'al-anika' ),
                'slide_border'  => __( 'Slide Border', 'al-anika' ),
                'fade_shadow'   => __( 'Fade Shadow', 'al-anika' ),
                'pulse_glow'    => __( 'Pulse Glow', 'al-anika' ),
            ),
        ) );
        
        // === LOADING INDICATORS ===
        $wp_customize->add_section( 'al_anika_loading_indicators', array(
            'title'    => __( 'Loading Indicators', 'al-anika' ),
            'panel'    => 'al_anika_motion_panel',
            'priority' => 60,
        ) );
        
        // Loading Spinner Type
        $wp_customize->add_setting( 'al_anika_loading_spinner', array(
            'default'           => 'dots_wave',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_loading_spinner', array(
            'label'    => __( 'Loading Spinner Type', 'al-anika' ),
            'section'  => 'al_anika_loading_indicators',
            'type'     => 'select',
            'choices'  => array(
                'spinner'       => __( 'Classic Spinner', 'al-anika' ),
                'dots_bounce'   => __( 'Bouncing Dots', 'al-anika' ),
                'dots_wave'     => __( 'Wave Dots', 'al-anika' ),
                'pulse_ring'    => __( 'Pulse Ring', 'al-anika' ),
                'bars_scale'    => __( 'Scaling Bars', 'al-anika' ),
                'heart_beat'    => __( 'Heart Beat', 'al-anika' ),
                'custom_logo'   => __( 'Custom Logo Animation', 'al-anika' ),
            ),
        ) );
        
        // Progress Bar Animation
        $wp_customize->add_setting( 'al_anika_progress_animation', array(
            'default'           => 'slide_fill',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_progress_animation', array(
            'label'    => __( 'Progress Bar Animation', 'al-anika' ),
            'section'  => 'al_anika_loading_indicators',
            'type'     => 'select',
            'choices'  => array(
                'slide_fill'    => __( 'Slide Fill', 'al-anika' ),
                'gradient_wave' => __( 'Gradient Wave', 'al-anika' ),
                'pulse_fill'    => __( 'Pulse Fill', 'al-anika' ),
                'stripe_move'   => __( 'Moving Stripes', 'al-anika' ),
                'glow_trail'    => __( 'Glow Trail', 'al-anika' ),
            ),
        ) );
    }
}

// Initialize the motion animation control
new Al_Anika_Motion_Animation_Control();
