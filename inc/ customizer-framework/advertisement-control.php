<?php
/**
 * Advanced Advertisement Control System
 * Complete control over advertisements, banners, promotions and their animations
 * نظام التحكم الشامل في الإعلانات والبانرات والعروض الترويجية وحركاتها
 *
 * @package Al_Anika_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Advertisement Management Customizer Controls
 */
class Al_Anika_Advertisement_Control {
    
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_advertisement_controls' ) );
    }
    
    /**
     * Register Advertisement Controls
     */
    public function register_advertisement_controls( $wp_customize ) {
        
        // === ADVERTISEMENT PANEL ===
        $wp_customize->add_panel( 'al_anika_advertisements_panel', array(
            'title'       => __( 'Advertisements & Promotions Manager', 'al-anika' ),
            'priority'    => 30,
            'description' => __( 'Complete control over all advertisements, banners, promotions, animations and positioning', 'al-anika' ),
            'capability'  => 'edit_theme_options',
        ) );
        
        // === BANNER ADS SECTION ===
        $wp_customize->add_section( 'al_anika_banner_ads', array(
            'title'    => __( 'Banner Advertisements', 'al-anika' ),
            'panel'    => 'al_anika_advertisements_panel',
            'priority' => 10,
        ) );
        
        // Header Banner Enable
        $wp_customize->add_setting( 'al_anika_header_banner_enable', array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_header_banner_enable', array(
            'label'   => __( 'Enable Header Banner Ad', 'al-anika' ),
            'section' => 'al_anika_banner_ads',
            'type'    => 'checkbox',
        ) );
        
        // Header Banner Position
        $wp_customize->add_setting( 'al_anika_header_banner_position', array(
            'default'           => 'after_nav',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_header_banner_position', array(
            'label'    => __( 'Header Banner Position', 'al-anika' ),
            'section'  => 'al_anika_banner_ads',
            'type'     => 'select',
            'choices'  => array(
                'very_top'     => __( 'Very Top (Above Everything)', 'al-anika' ),
                'before_nav'   => __( 'Before Navigation', 'al-anika' ),
                'after_nav'    => __( 'After Navigation', 'al-anika' ),
                'below_hero'   => __( 'Below Hero Section', 'al-anika' ),
                'floating_top' => __( 'Floating Top Bar', 'al-anika' ),
            ),
        ) );
        
        // Footer Banner Enable
        $wp_customize->add_setting( 'al_anika_footer_banner_enable', array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_footer_banner_enable', array(
            'label'   => __( 'Enable Footer Banner Ad', 'al-anika' ),
            'section' => 'al_anika_banner_ads',
            'type'    => 'checkbox',
        ) );
        
        // Sidebar Banner Enable
        $wp_customize->add_setting( 'al_anika_sidebar_banner_enable', array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_sidebar_banner_enable', array(
            'label'   => __( 'Enable Sidebar Banner Ads', 'al-anika' ),
            'section' => 'al_anika_banner_ads',
            'type'    => 'checkbox',
        ) );
        
        // === PROMOTIONAL BANNERS SECTION ===
        $wp_customize->add_section( 'al_anika_promo_banners', array(
            'title'    => __( 'Promotional Banners', 'al-anika' ),
            'panel'    => 'al_anika_advertisements_panel',
            'priority' => 20,
        ) );
        
        // Flash Sale Banner Type
        $wp_customize->add_setting( 'al_anika_flash_sale_banner_type', array(
            'default'           => 'animated_ribbon',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_flash_sale_banner_type', array(
            'label'    => __( 'Flash Sale Banner Type', 'al-anika' ),
            'section'  => 'al_anika_promo_banners',
            'type'     => 'select',
            'choices'  => array(
                'static_bar'       => __( 'Static Bar', 'al-anika' ),
                'animated_ribbon'  => __( 'Animated Ribbon', 'al-anika' ),
                'pulsing_badge'    => __( 'Pulsing Badge', 'al-anika' ),
                'countdown_banner' => __( 'Countdown Banner', 'al-anika' ),
                'scrolling_text'   => __( 'Scrolling Text', 'al-anika' ),
                'neon_sign'        => __( 'Neon Sign Effect', 'al-anika' ),
                'fire_effect'      => __( 'Fire Effect Banner', 'al-anika' ),
            ),
        ) );
        
        // Promo Banner Position
        $wp_customize->add_setting( 'al_anika_promo_banner_position', array(
            'default'           => 'multiple_positions',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_promo_banner_position', array(
            'label'    => __( 'Promotional Banner Positions', 'al-anika' ),
            'section'  => 'al_anika_promo_banners',
            'type'     => 'select',
            'choices'  => array(
                'top_only'          => __( 'Top Only', 'al-anika' ),
                'bottom_only'       => __( 'Bottom Only', 'al-anika' ),
                'sidebar_only'      => __( 'Sidebar Only', 'al-anika' ),
                'multiple_positions' => __( 'Multiple Positions', 'al-anika' ),
                'floating_corners'  => __( 'Floating Corners', 'al-anika' ),
                'overlay_center'    => __( 'Overlay Center', 'al-anika' ),
                'sticky_edges'      => __( 'Sticky Edges', 'al-anika' ),
            ),
        ) );
        
        // === POP-UP ADS SECTION ===
        $wp_customize->add_section( 'al_anika_popup_ads', array(
            'title'    => __( 'Pop-up & Modal Ads', 'al-anika' ),
            'panel'    => 'al_anika_advertisements_panel',
            'priority' => 30,
        ) );
        
        // Pop-up Ad Enable
        $wp_customize->add_setting( 'al_anika_popup_ads_enable', array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_popup_ads_enable', array(
            'label'   => __( 'Enable Pop-up Advertisements', 'al-anika' ),
            'section' => 'al_anika_popup_ads',
            'type'    => 'checkbox',
        ) );
        
        // Pop-up Trigger Type
        $wp_customize->add_setting( 'al_anika_popup_trigger', array(
            'default'           => 'time_delay',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_popup_trigger', array(
            'label'    => __( 'Pop-up Trigger Type', 'al-anika' ),
            'section'  => 'al_anika_popup_ads',
            'type'     => 'select',
            'choices'  => array(
                'immediate'      => __( 'Immediate (Page Load)', 'al-anika' ),
                'time_delay'     => __( 'Time Delay', 'al-anika' ),
                'scroll_percent' => __( 'Scroll Percentage', 'al-anika' ),
                'exit_intent'    => __( 'Exit Intent', 'al-anika' ),
                'click_trigger'  => __( 'Click Trigger', 'al-anika' ),
                'hover_trigger'  => __( 'Hover Trigger', 'al-anika' ),
                'idle_time'      => __( 'User Idle Time', 'al-anika' ),
            ),
        ) );
        
        // Pop-up Animation
        $wp_customize->add_setting( 'al_anika_popup_animation', array(
            'default'           => 'zoom_bounce',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_popup_animation', array(
            'label'    => __( 'Pop-up Animation', 'al-anika' ),
            'section'  => 'al_anika_popup_ads',
            'type'     => 'select',
            'choices'  => array(
                'fade_in'       => __( 'Fade In', 'al-anika' ),
                'slide_down'    => __( 'Slide Down', 'al-anika' ),
                'slide_up'      => __( 'Slide Up', 'al-anika' ),
                'zoom_in'       => __( 'Zoom In', 'al-anika' ),
                'zoom_bounce'   => __( 'Zoom with Bounce', 'al-anika' ),
                'flip_3d'       => __( 'Flip 3D', 'al-anika' ),
                'elastic_in'    => __( 'Elastic In', 'al-anika' ),
                'rotate_in'     => __( 'Rotate In', 'al-anika' ),
            ),
        ) );
        
        // === AD ANIMATIONS SECTION ===
        $wp_customize->add_section( 'al_anika_ad_animations', array(
            'title'    => __( 'Advertisement Animations', 'al-anika' ),
            'panel'    => 'al_anika_advertisements_panel',
            'priority' => 40,
        ) );
        
        // Banner Animation Type
        $wp_customize->add_setting( 'al_anika_banner_animation_type', array(
            'default'           => 'slide_fade',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_banner_animation_type', array(
            'label'    => __( 'Banner Animation Type', 'al-anika' ),
            'section'  => 'al_anika_ad_animations',
            'type'     => 'select',
            'choices'  => array(
                'none'          => __( 'No Animation', 'al-anika' ),
                'slide_fade'    => __( 'Slide + Fade', 'al-anika' ),
                'pulse_glow'    => __( 'Pulse + Glow', 'al-anika' ),
                'wave_effect'   => __( 'Wave Effect', 'al-anika' ),
                'typing_text'   => __( 'Typing Text', 'al-anika' ),
                'neon_flicker'  => __( 'Neon Flicker', 'al-anika' ),
                'fire_animation' => __( 'Fire Animation', 'al-anika' ),
                'particle_burst' => __( 'Particle Burst', 'al-anika' ),
                'magnetic_pull'  => __( 'Magnetic Pull', 'al-anika' ),
            ),
        ) );
        
        // Animation Speed
        $wp_customize->add_setting( 'al_anika_ad_animation_speed', array(
            'default'           => '2000',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_ad_animation_speed', array(
            'label'       => __( 'Animation Speed (ms)', 'al-anika' ),
            'section'     => 'al_anika_ad_animations',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 500,
                'max'  => 5000,
                'step' => 250,
            ),
        ) );
        
        // Loop Animation
        $wp_customize->add_setting( 'al_anika_ad_animation_loop', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_ad_animation_loop', array(
            'label'   => __( 'Loop Animations Continuously', 'al-anika' ),
            'section' => 'al_anika_ad_animations',
            'type'    => 'checkbox',
        ) );
        
        // === AD STYLING SECTION ===
        $wp_customize->add_section( 'al_anika_ad_styling', array(
            'title'    => __( 'Advertisement Styling & Fonts', 'al-anika' ),
            'panel'    => 'al_anika_advertisements_panel',
            'priority' => 50,
        ) );
        
        // Ad Font Family
        $wp_customize->add_setting( 'al_anika_ad_font_family', array(
            'default'           => 'Poppins',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_ad_font_family', array(
            'label'    => __( 'Advertisement Font Family', 'al-anika' ),
            'section'  => 'al_anika_ad_styling',
            'type'     => 'select',
            'choices'  => array(
                'Poppins'       => __( 'Poppins (Bold & Modern)', 'al-anika' ),
                'Montserrat'    => __( 'Montserrat (Clean)', 'al-anika' ),
                'Oswald'        => __( 'Oswald (Strong)', 'al-anika' ),
                'Bebas Neue'    => __( 'Bebas Neue (Impact)', 'al-anika' ),
                'Anton'         => __( 'Anton (Heavy)', 'al-anika' ),
                'Fredoka One'   => __( 'Fredoka One (Fun)', 'al-anika' ),
                'Bangers'       => __( 'Bangers (Comic)', 'al-anika' ),
                'Righteous'     => __( 'Righteous (Retro)', 'al-anika' ),
            ),
        ) );
        
        // Ad Text Color
        $wp_customize->add_setting( 'al_anika_ad_text_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_ad_text_color', array(
            'label'   => __( 'Advertisement Text Color', 'al-anika' ),
            'section' => 'al_anika_ad_styling',
        ) ) );
        
        // Ad Background Color
        $wp_customize->add_setting( 'al_anika_ad_bg_color', array(
            'default'           => '#e74c3c',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'al_anika_ad_bg_color', array(
            'label'   => __( 'Advertisement Background Color', 'al-anika' ),
            'section' => 'al_anika_ad_styling',
        ) ) );
        
        // Ad Border Style
        $wp_customize->add_setting( 'al_anika_ad_border_style', array(
            'default'           => 'neon_glow',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_ad_border_style', array(
            'label'    => __( 'Advertisement Border Style', 'al-anika' ),
            'section'  => 'al_anika_ad_styling',
            'type'     => 'select',
            'choices'  => array(
                'none'          => __( 'No Border', 'al-anika' ),
                'solid'         => __( 'Solid Border', 'al-anika' ),
                'dashed'        => __( 'Dashed Border', 'al-anika' ),
                'neon_glow'     => __( 'Neon Glow', 'al-anika' ),
                'gradient'      => __( 'Gradient Border', 'al-anika' ),
                'animated_glow' => __( 'Animated Glow', 'al-anika' ),
                'double_line'   => __( 'Double Line', 'al-anika' ),
            ),
        ) );
        
        // === AD TYPES SECTION ===
        $wp_customize->add_section( 'al_anika_ad_types', array(
            'title'    => __( 'Advertisement Types & Formats', 'al-anika' ),
            'panel'    => 'al_anika_advertisements_panel',
            'priority' => 60,
        ) );
        
        // Sale Badge Type
        $wp_customize->add_setting( 'al_anika_sale_badge_type', array(
            'default'           => 'percentage_off',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_sale_badge_type', array(
            'label'    => __( 'Sale Badge Type', 'al-anika' ),
            'section'  => 'al_anika_ad_types',
            'type'     => 'select',
            'choices'  => array(
                'percentage_off'  => __( 'Percentage Off (50% OFF)', 'al-anika' ),
                'amount_off'      => __( 'Amount Off ($20 OFF)', 'al-anika' ),
                'flash_sale'      => __( 'Flash Sale Badge', 'al-anika' ),
                'limited_time'    => __( 'Limited Time Offer', 'al-anika' ),
                'best_seller'     => __( 'Best Seller Badge', 'al-anika' ),
                'new_arrival'     => __( 'New Arrival Badge', 'al-anika' ),
                'exclusive'       => __( 'Exclusive Deal', 'al-anika' ),
                'hot_deal'        => __( 'Hot Deal Badge', 'al-anika' ),
            ),
        ) );
        
        // Discount Banner Format
        $wp_customize->add_setting( 'al_anika_discount_banner_format', array(
            'default'           => 'mega_sale',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_discount_banner_format', array(
            'label'    => __( 'Discount Banner Format', 'al-anika' ),
            'section'  => 'al_anika_ad_types',
            'type'     => 'select',
            'choices'  => array(
                'mega_sale'       => __( 'Mega Sale Banner', 'al-anika' ),
                'clearance'       => __( 'Clearance Sale', 'al-anika' ),
                'black_friday'    => __( 'Black Friday Style', 'al-anika' ),
                'season_sale'     => __( 'Seasonal Sale', 'al-anika' ),
                'buy_one_get'     => __( 'Buy One Get One', 'al-anika' ),
                'free_shipping'   => __( 'Free Shipping Banner', 'al-anika' ),
                'countdown_deal'  => __( 'Countdown Deal', 'al-anika' ),
                'member_only'     => __( 'Members Only Deal', 'al-anika' ),
            ),
        ) );
        
        // === AD POSITIONING SECTION ===
        $wp_customize->add_section( 'al_anika_ad_positioning', array(
            'title'    => __( 'Advanced Ad Positioning', 'al-anika' ),
            'panel'    => 'al_anika_advertisements_panel',
            'priority' => 70,
        ) );
        
        // Floating Ad Position
        $wp_customize->add_setting( 'al_anika_floating_ad_position', array(
            'default'           => 'bottom_right',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_floating_ad_position', array(
            'label'    => __( 'Floating Ad Position', 'al-anika' ),
            'section'  => 'al_anika_ad_positioning',
            'type'     => 'select',
            'choices'  => array(
                'none'           => __( 'No Floating Ads', 'al-anika' ),
                'top_left'       => __( 'Top Left', 'al-anika' ),
                'top_right'      => __( 'Top Right', 'al-anika' ),
                'bottom_left'    => __( 'Bottom Left', 'al-anika' ),
                'bottom_right'   => __( 'Bottom Right', 'al-anika' ),
                'center_left'    => __( 'Center Left', 'al-anika' ),
                'center_right'   => __( 'Center Right', 'al-anika' ),
                'multiple_corners' => __( 'Multiple Corners', 'al-anika' ),
            ),
        ) );
        
        // Sticky Banner Position
        $wp_customize->add_setting( 'al_anika_sticky_banner_position', array(
            'default'           => 'top_sticky',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_sticky_banner_position', array(
            'label'    => __( 'Sticky Banner Position', 'al-anika' ),
            'section'  => 'al_anika_ad_positioning',
            'type'     => 'select',
            'choices'  => array(
                'none'         => __( 'No Sticky Banner', 'al-anika' ),
                'top_sticky'   => __( 'Sticky Top', 'al-anika' ),
                'bottom_sticky' => __( 'Sticky Bottom', 'al-anika' ),
                'side_sticky'  => __( 'Sticky Side', 'al-anika' ),
                'floating_center' => __( 'Floating Center', 'al-anika' ),
            ),
        ) );
        
        // Ad Z-Index
        $wp_customize->add_setting( 'al_anika_ad_zindex', array(
            'default'           => '9999',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'al_anika_ad_zindex', array(
            'label'       => __( 'Advertisement Z-Index (Layer Priority)', 'al-anika' ),
            'section'     => 'al_anika_ad_positioning',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 99999,
                'step' => 1,
            ),
        ) );
    }
}

// Initialize the advertisement control
new Al_Anika_Advertisement_Control();
