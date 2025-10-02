<?php
/**
 * Animation Control for Theme Customizer
 * Phase 4: Interactive Features & Animations
 *
 * @package AlamAlAnika
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add Animation & Interaction Controls to Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function alam_add_animation_controls( $wp_customize ) {
    
    // Animation & Interactions Panel
    $wp_customize->add_panel( 'alam_animations_panel', array(
        'title'       => esc_html__( 'التفاعل والحركة', 'alam-al-anika' ),
        'description' => esc_html__( 'إعدادات متقدمة للحركة والتفاعل في الموقع', 'alam-al-anika' ),
        'priority'    => 35,
    ) );

    // === GENERAL ANIMATIONS SECTION ===
    $wp_customize->add_section( 'alam_general_animations', array(
        'title'    => esc_html__( 'الحركة العامة', 'alam-al-anika' ),
        'panel'    => 'alam_animations_panel',
        'priority' => 10,
    ) );

    // Animation Speed
    $wp_customize->add_setting( 'alam_animation_speed', array(
        'default'           => 'medium',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_animation_speed', array(
        'label'    => esc_html__( 'سرعة الحركة', 'alam-al-anika' ),
        'section'  => 'alam_general_animations',
        'type'     => 'select',
        'choices'  => array(
            'slow'     => esc_html__( 'بطيء (0.5s)', 'alam-al-anika' ),
            'medium'   => esc_html__( 'متوسط (0.3s)', 'alam-al-anika' ),
            'fast'     => esc_html__( 'سريع (0.2s)', 'alam-al-anika' ),
            'instant'  => esc_html__( 'فوري (0.1s)', 'alam-al-anika' ),
        ),
    ) );

    // Enable Page Scroll Animations
    $wp_customize->add_setting( 'alam_scroll_animations', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_scroll_animations', array(
        'label'    => esc_html__( 'تفعيل حركة التمرير', 'alam-al-anika' ),
        'section'  => 'alam_general_animations',
        'type'     => 'checkbox',
    ) );

    // Enable Parallax Effects
    $wp_customize->add_setting( 'alam_parallax_effects', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_parallax_effects', array(
        'label'    => esc_html__( 'تفعيل تأثيرات الباراليكس', 'alam-al-anika' ),
        'section'  => 'alam_general_animations',
        'type'     => 'checkbox',
    ) );

    // === HOVER EFFECTS SECTION ===
    $wp_customize->add_section( 'alam_hover_effects', array(
        'title'    => esc_html__( 'تأثيرات التمرير', 'alam-al-anika' ),
        'panel'    => 'alam_animations_panel',
        'priority' => 20,
    ) );

    // Button Hover Style
    $wp_customize->add_setting( 'alam_button_hover_style', array(
        'default'           => 'scale',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_button_hover_style', array(
        'label'    => esc_html__( 'نمط تمرير الأزرار', 'alam-al-anika' ),
        'section'  => 'alam_hover_effects',
        'type'     => 'select',
        'choices'  => array(
            'scale'     => esc_html__( 'تكبير', 'alam-al-anika' ),
            'slide'     => esc_html__( 'انزلاق', 'alam-al-anika' ),
            'glow'      => esc_html__( 'توهج', 'alam-al-anika' ),
            'lift'      => esc_html__( 'رفع', 'alam-al-anika' ),
            'bounce'    => esc_html__( 'ارتداد', 'alam-al-anika' ),
        ),
    ) );

    // Image Hover Effect
    $wp_customize->add_setting( 'alam_image_hover_effect', array(
        'default'           => 'zoom',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_image_hover_effect', array(
        'label'    => esc_html__( 'تأثير تمرير الصور', 'alam-al-anika' ),
        'section'  => 'alam_hover_effects',
        'type'     => 'select',
        'choices'  => array(
            'zoom'      => esc_html__( 'تكبير', 'alam-al-anika' ),
            'rotate'    => esc_html__( 'دوران', 'alam-al-anika' ),
            'blur'      => esc_html__( 'ضبابية', 'alam-al-anika' ),
            'overlay'   => esc_html__( 'طبقة شفافة', 'alam-al-anika' ),
            'slide'     => esc_html__( 'انزلاق', 'alam-al-anika' ),
        ),
    ) );

    // === PRODUCT ANIMATIONS SECTION ===
    $wp_customize->add_section( 'alam_product_animations', array(
        'title'    => esc_html__( 'حركة المنتجات', 'alam-al-anika' ),
        'panel'    => 'alam_animations_panel',
        'priority' => 30,
    ) );

    // Product Card Hover Style
    $wp_customize->add_setting( 'alam_product_hover_style', array(
        'default'           => 'lift_shadow',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_product_hover_style', array(
        'label'    => esc_html__( 'نمط تمرير بطاقة المنتج', 'alam-al-anika' ),
        'section'  => 'alam_product_animations',
        'type'     => 'select',
        'choices'  => array(
            'lift_shadow'  => esc_html__( 'رفع مع ظل', 'alam-al-anika' ),
            'scale_fade'   => esc_html__( 'تكبير مع شفافية', 'alam-al-anika' ),
            'border_glow'  => esc_html__( 'توهج الحدود', 'alam-al-anika' ),
            'flip_3d'      => esc_html__( 'قلب ثلاثي الأبعاد', 'alam-al-anika' ),
            'slide_up'     => esc_html__( 'انزلاق لأعلى', 'alam-al-anika' ),
        ),
    ) );

    // Product Image Interaction
    $wp_customize->add_setting( 'alam_product_image_interaction', array(
        'default'           => 'zoom_rotate',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_product_image_interaction', array(
        'label'    => esc_html__( 'تفاعل صورة المنتج', 'alam-al-anika' ),
        'section'  => 'alam_product_animations',
        'type'     => 'select',
        'choices'  => array(
            'zoom_rotate'    => esc_html__( 'تكبير ودوران', 'alam-al-anika' ),
            'parallax_move'  => esc_html__( 'حركة باراليكس', 'alam-al-anika' ),
            'color_shift'    => esc_html__( 'تحويل اللون', 'alam-al-anika' ),
            'reveal_details' => esc_html__( 'إظهار التفاصيل', 'alam-al-anika' ),
            'magnetic'       => esc_html__( 'مغناطيسي', 'alam-al-anika' ),
        ),
    ) );

    // === NAVIGATION ANIMATIONS SECTION ===
    $wp_customize->add_section( 'alam_navigation_animations', array(
        'title'    => esc_html__( 'حركة التنقل', 'alam-al-anika' ),
        'panel'    => 'alam_animations_panel',
        'priority' => 40,
    ) );

    // Menu Transition Style
    $wp_customize->add_setting( 'alam_menu_transition', array(
        'default'           => 'slide_down',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_menu_transition', array(
        'label'    => esc_html__( 'نمط انتقال القائمة', 'alam-al-anika' ),
        'section'  => 'alam_navigation_animations',
        'type'     => 'select',
        'choices'  => array(
            'slide_down'  => esc_html__( 'انزلاق لأسفل', 'alam-al-anika' ),
            'fade_in'     => esc_html__( 'ظهور تدريجي', 'alam-al-anika' ),
            'scale_up'    => esc_html__( 'تكبير', 'alam-al-anika' ),
            'flip_in'     => esc_html__( 'قلب للداخل', 'alam-al-anika' ),
            'elastic'     => esc_html__( 'مرن', 'alam-al-anika' ),
        ),
    ) );

    // Scroll Progress Indicator
    $wp_customize->add_setting( 'alam_scroll_progress', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_scroll_progress', array(
        'label'    => esc_html__( 'مؤشر تقدم التمرير', 'alam-al-anika' ),
        'section'  => 'alam_navigation_animations',
        'type'     => 'checkbox',
    ) );

    // === LOADING ANIMATIONS SECTION ===
    $wp_customize->add_section( 'alam_loading_animations', array(
        'title'    => esc_html__( 'حركة التحميل', 'alam-al-anika' ),
        'panel'    => 'alam_animations_panel',
        'priority' => 50,
    ) );

    // Page Load Animation
    $wp_customize->add_setting( 'alam_page_load_animation', array(
        'default'           => 'fade_in_up',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_page_load_animation', array(
        'label'    => esc_html__( 'حركة تحميل الصفحة', 'alam-al-anika' ),
        'section'  => 'alam_loading_animations',
        'type'     => 'select',
        'choices'  => array(
            'fade_in_up'    => esc_html__( 'ظهور من الأسفل', 'alam-al-anika' ),
            'slide_in_left' => esc_html__( 'انزلاق من اليسار', 'alam-al-anika' ),
            'zoom_in'       => esc_html__( 'تكبير للداخل', 'alam-al-anika' ),
            'rotate_in'     => esc_html__( 'دوران للداخل', 'alam-al-anika' ),
            'bounce_in'     => esc_html__( 'ارتداد للداخل', 'alam-al-anika' ),
        ),
    ) );

    // Loading Spinner Style
    $wp_customize->add_setting( 'alam_loading_spinner', array(
        'default'           => 'modern_dots',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_loading_spinner', array(
        'label'    => esc_html__( 'نمط دوار التحميل', 'alam-al-anika' ),
        'section'  => 'alam_loading_animations',
        'type'     => 'select',
        'choices'  => array(
            'modern_dots'    => esc_html__( 'نقاط عصرية', 'alam-al-anika' ),
            'elegant_ring'   => esc_html__( 'حلقة أنيقة', 'alam-al-anika' ),
            'pulse_heart'    => esc_html__( 'نبضة القلب', 'alam-al-anika' ),
            'wave_motion'    => esc_html__( 'حركة موجية', 'alam-al-anika' ),
            'geometric'      => esc_html__( 'هندسي', 'alam-al-anika' ),
        ),
    ) );
}

/**
 * Sanitize select choices
 */
function alam_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

// Hook into customizer
add_action( 'customize_register', 'alam_add_animation_controls' );
