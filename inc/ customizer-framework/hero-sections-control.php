<?php
/**
 * Hero Sections Advanced Control
 * Complete hero sections customization system
 *
 * @package Al_Anika_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Hero Sections Customizer Controls
 */
class Al_Anika_Hero_Sections_Control {
    
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_hero_controls' ) );
    }
    
    /**
     * Register Hero Sections Controls
     */
    public function register_hero_controls( $wp_customize ) {
        
        // === HERO SECTIONS PANEL ===
        $wp_customize->add_panel( 'al_anika_hero_panel', array(
            'title'       => __( 'Hero Sections Manager', 'al-anika' ),
            'priority'    => 25,
            'description' => __( 'Complete control over hero sections with drag-and-drop positioning', 'al-anika' ),
            'capability'  => 'edit_theme_options',
        ) );
        
        // === HERO LAYOUT SECTION ===
        $wp_customize->add_section( 'al_anika_hero_layout', array(
            'title'    => __( 'Hero Layout & Structure', 'al-anika' ),
            'panel'    => 'al_anika_hero_panel',
            'priority' => 10,
        ) );
        
        // Hero Section Type
        $wp_customize->add_setting( 'al_anika_hero_type', array(
            'default'           => 'revolutionary',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_type', array(
            'label'    => __( 'Hero Section Type', 'al-anika' ),
            'section'  => 'al_anika_hero_layout',
            'type'     => 'select',
            'choices'  => array(
                'revolutionary' => __( 'Revolutionary Hero (SHEIN Style)', 'al-anika' ),
                'enhanced'      => __( 'Enhanced Hero', 'al-anika' ),
                'flash_sale'    => __( 'Flash Sale Hero', 'al-anika' ),
                'super_sale'    => __( 'Super Sale Hero', 'al-anika' ),
                'category_grid' => __( 'Category Grid Hero', 'al-anika' ),
                'hashtag'       => __( 'Hashtag Campaign Hero', 'al-anika' ),
                'picks'         => __( 'Picks For You Hero', 'al-anika' ),
                'deals'         => __( 'Super Deals Hero', 'al-anika' ),
                'custom'        => __( 'Custom Hero Layout', 'al-anika' ),
            ),
        ) );
        
        // Hero Position Control
        $wp_customize->add_setting( 'al_anika_hero_position', array(
            'default'           => 'top',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_position', array(
            'label'    => __( 'Hero Section Position', 'al-anika' ),
            'section'  => 'al_anika_hero_layout',
            'type'     => 'select',
            'choices'  => array(
                'top'        => __( 'Top of Page', 'al-anika' ),
                'after_nav'  => __( 'After Navigation', 'al-anika' ),
                'center'     => __( 'Center Page', 'al-anika' ),
                'before_footer' => __( 'Before Footer', 'al-anika' ),
                'fixed'      => __( 'Fixed Position', 'al-anika' ),
                'custom'     => __( 'Custom Position', 'al-anika' ),
            ),
        ) );
        
        // Hero Height Control
        $wp_customize->add_setting( 'al_anika_hero_height', array(
            'default'           => '600',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_height', array(
            'label'       => __( 'Hero Height (px)', 'al-anika' ),
            'section'     => 'al_anika_hero_layout',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 300,
                'max'  => 1200,
                'step' => 50,
            ),
        ) );
        
        // === HERO CONTENT SECTION ===
        $wp_customize->add_section( 'al_anika_hero_content', array(
            'title'    => __( 'Hero Content & Text', 'al-anika' ),
            'panel'    => 'al_anika_hero_panel',
            'priority' => 20,
        ) );
        
        // Main Headline
        $wp_customize->add_setting( 'al_anika_hero_headline', array(
            'default'           => __( 'Revolutionary Shopping Experience', 'al-anika' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_headline', array(
            'label'   => __( 'Main Headline', 'al-anika' ),
            'section' => 'al_anika_hero_content',
            'type'    => 'text',
        ) );
        
        // Subtitle
        $wp_customize->add_setting( 'al_anika_hero_subtitle', array(
            'default'           => __( 'Discover trending fashion with unbeatable prices', 'al-anika' ),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_subtitle', array(
            'label'   => __( 'Subtitle Text', 'al-anika' ),
            'section' => 'al_anika_hero_content',
            'type'    => 'textarea',
        ) );
        
        // CTA Button Text
        $wp_customize->add_setting( 'al_anika_hero_cta_text', array(
            'default'           => __( 'Shop Now', 'al-anika' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_cta_text', array(
            'label'   => __( 'CTA Button Text', 'al-anika' ),
            'section' => 'al_anika_hero_content',
            'type'    => 'text',
        ) );
        
        // CTA Button Link
        $wp_customize->add_setting( 'al_anika_hero_cta_link', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_cta_link', array(
            'label'   => __( 'CTA Button Link', 'al-anika' ),
            'section' => 'al_anika_hero_content',
            'type'    => 'url',
        ) );
        
        // === HERO STYLING SECTION ===
        $wp_customize->add_section( 'al_anika_hero_styling', array(
            'title'    => __( 'Hero Colors & Styling', 'al-anika' ),
            'panel'    => 'al_anika_hero_panel',
            'priority' => 30,
        ) );
        
        // Background Type
        $wp_customize->add_setting( 'al_anika_hero_bg_type', array(
            'default'           => 'gradient',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_bg_type', array(
            'label'    => __( 'Background Type', 'al-anika' ),
            'section'  => 'al_anika_hero_styling',
            'type'     => 'select',
            'choices'  => array(
                'color'    => __( 'Solid Color', 'al-anika' ),
                'gradient' => __( 'Gradient', 'al-anika' ),
                'image'    => __( 'Background Image', 'al-anika' ),
                'video'    => __( 'Background Video', 'al-anika' ),
                'pattern'  => __( 'Pattern Overlay', 'al-anika' ),
            ),
        ) );
        
        // Primary Background Color
        $wp_customize->add_setting( 'al_anika_hero_bg_color', array(
            'default'           => '#ff6b9d',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_hero_bg_color', array(
            'label'   => __( 'Primary Background Color', 'al-anika' ),
            'section' => 'al_anika_hero_styling',
        ) ) );
        
        // Secondary Background Color
        $wp_customize->add_setting( 'al_anika_hero_bg_color_2', array(
            'default'           => '#4ecdc4',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_hero_bg_color_2', array(
            'label'   => __( 'Secondary Background Color', 'al-anika' ),
            'section' => 'al_anika_hero_styling',
        ) ) );
        
        // Text Color
        $wp_customize->add_setting( 'al_anika_hero_text_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_hero_text_color', array(
            'label'   => __( 'Text Color', 'al-anika' ),
            'section' => 'al_anika_hero_styling',
        ) ) );
        
        // === HERO ANIMATIONS SECTION ===
        $wp_customize->add_section( 'al_anika_hero_animations', array(
            'title'    => __( 'Hero Animations & Effects', 'al-anika' ),
            'panel'    => 'al_anika_hero_panel',
            'priority' => 40,
        ) );
        
        // Enable Animations
        $wp_customize->add_setting( 'al_anika_hero_animations_enable', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_animations_enable', array(
            'label'   => __( 'Enable Hero Animations', 'al-anika' ),
            'section' => 'al_anika_hero_animations',
            'type'    => 'checkbox',
        ) );
        
        // Animation Type
        $wp_customize->add_setting( 'al_anika_hero_animation_type', array(
            'default'           => 'fade_slide',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_animation_type', array(
            'label'    => __( 'Animation Type', 'al-anika' ),
            'section'  => 'al_anika_hero_animations',
            'type'     => 'select',
            'choices'  => array(
                'fade'         => __( 'Fade In', 'al-anika' ),
                'slide_up'     => __( 'Slide Up', 'al-anika' ),
                'slide_down'   => __( 'Slide Down', 'al-anika' ),
                'slide_left'   => __( 'Slide Left', 'al-anika' ),
                'slide_right'  => __( 'Slide Right', 'al-anika' ),
                'fade_slide'   => __( 'Fade + Slide', 'al-anika' ),
                'zoom'         => __( 'Zoom In', 'al-anika' ),
                'bounce'       => __( 'Bounce', 'al-anika' ),
                'rotate'       => __( 'Rotate In', 'al-anika' ),
                'typewriter'   => __( 'Typewriter Effect', 'al-anika' ),
            ),
        ) );
        
        // Animation Duration
        $wp_customize->add_setting( 'al_anika_hero_animation_duration', array(
            'default'           => '1000',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_animation_duration', array(
            'label'       => __( 'Animation Duration (ms)', 'al-anika' ),
            'section'     => 'al_anika_hero_animations',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 200,
                'max'  => 3000,
                'step' => 100,
            ),
        ) );
        
        // Parallax Effect
        $wp_customize->add_setting( 'al_anika_hero_parallax', array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_hero_parallax', array(
            'label'   => __( 'Enable Parallax Effect', 'al-anika' ),
            'section' => 'al_anika_hero_animations',
            'type'    => 'checkbox',
        ) );
    }
}

// Initialize the hero sections control
new Al_Anika_Hero_Sections_Control();
