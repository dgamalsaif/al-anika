<?php
/**
 * Al-Anika Theme Customizer - Consolidated
 * 
 * @package Al_Anika_Theme
 * @version 9.0.0 Final
 * @author MiniMax Agent
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function al_anika_customize_register($wp_customize) {
    
    // Remove default sections we don't need
    $wp_customize->remove_section('colors');
    $wp_customize->remove_section('background_image');
    
    // Modify existing controls
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';
    
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'al_anika_customize_partial_blogname',
        ));
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'al_anika_customize_partial_blogdescription',
        ));
    }
    
    /**
     * GENERAL SETTINGS
     */
    $wp_customize->add_panel('al_anika_general', array(
        'title'       => esc_html__('General Settings', 'al-anika'),
        'description' => esc_html__('Customize general theme settings', 'al-anika'),
        'priority'    => 10,
    ));
    
    // Layout Section
    $wp_customize->add_section('al_anika_layout', array(
        'title'    => esc_html__('Layout Options', 'al-anika'),
        'panel'    => 'al_anika_general',
        'priority' => 10,
    ));
    
    $wp_customize->add_setting('site_layout', array(
        'default'           => 'wide',
        'sanitize_callback' => 'al_anika_sanitize_select',
    ));
    
    $wp_customize->add_control('site_layout', array(
        'label'    => esc_html__('Site Layout', 'al-anika'),
        'section'  => 'al_anika_layout',
        'type'     => 'select',
        'choices'  => array(
            'wide'      => esc_html__('Wide', 'al-anika'),
            'boxed'     => esc_html__('Boxed', 'al-anika'),
            'fullwidth' => esc_html__('Full Width', 'al-anika'),
        ),
    ));
    
    $wp_customize->add_setting('container_width', array(
        'default'           => 1200,
        'sanitize_callback' => 'al_anika_sanitize_number_range',
    ));
    
    $wp_customize->add_control('container_width', array(
        'label'       => esc_html__('Container Width (px)', 'al-anika'),
        'section'     => 'al_anika_layout',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 960,
            'max'  => 1920,
            'step' => 20,
        ),
    ));
    
    // Colors Section
    $wp_customize->add_section('al_anika_colors', array(
        'title'    => esc_html__('Color Scheme', 'al-anika'),
        'panel'    => 'al_anika_general',
        'priority' => 20,
    ));
    
    $wp_customize->add_setting('primary_color', array(
        'default'           => '#e74c3c',
        'sanitize_callback' => 'al_anika_sanitize_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label'    => esc_html__('Primary Color', 'al-anika'),
        'section'  => 'al_anika_colors',
        'priority' => 10,
    )));
    
    $wp_customize->add_setting('secondary_color', array(
        'default'           => '#2c3e50',
        'sanitize_callback' => 'al_anika_sanitize_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', array(
        'label'    => esc_html__('Secondary Color', 'al-anika'),
        'section'  => 'al_anika_colors',
        'priority' => 20,
    )));
    
    $wp_customize->add_setting('accent_color', array(
        'default'           => '#f39c12',
        'sanitize_callback' => 'al_anika_sanitize_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
        'label'    => esc_html__('Accent Color', 'al-anika'),
        'section'  => 'al_anika_colors',
        'priority' => 30,
    )));
    
    // Typography Section
    $wp_customize->add_section('al_anika_typography', array(
        'title'    => esc_html__('Typography', 'al-anika'),
        'panel'    => 'al_anika_general',
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('body_font_family', array(
        'default'           => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('body_font_family', array(
        'label'   => esc_html__('Body Font Family', 'al-anika'),
        'section' => 'al_anika_typography',
        'type'    => 'select',
        'choices' => array(
            'Inter'           => 'Inter',
            'Roboto'          => 'Roboto',
            'Open Sans'       => 'Open Sans',
            'Lato'            => 'Lato',
            'Source Sans Pro' => 'Source Sans Pro',
            'Nunito'          => 'Nunito',
        ),
    ));
    
    $wp_customize->add_setting('heading_font_family', array(
        'default'           => 'Playfair Display',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('heading_font_family', array(
        'label'   => esc_html__('Heading Font Family', 'al-anika'),
        'section' => 'al_anika_typography',
        'type'    => 'select',
        'choices' => array(
            'Playfair Display' => 'Playfair Display',
            'Merriweather'     => 'Merriweather',
            'Crimson Text'     => 'Crimson Text',
            'Libre Baskerville' => 'Libre Baskerville',
            'Lora'             => 'Lora',
            'PT Serif'         => 'PT Serif',
        ),
    ));
    
    /**
     * HEADER SETTINGS
     */
    $wp_customize->add_panel('al_anika_header', array(
        'title'       => esc_html__('Header Settings', 'al-anika'),
        'description' => esc_html__('Customize header appearance and functionality', 'al-anika'),
        'priority'    => 20,
    ));
    
    // Header Layout
    $wp_customize->add_section('al_anika_header_layout', array(
        'title'    => esc_html__('Header Layout', 'al-anika'),
        'panel'    => 'al_anika_header',
        'priority' => 10,
    ));
    
    $wp_customize->add_setting('show_header_top_bar', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('show_header_top_bar', array(
        'label'   => esc_html__('Show Header Top Bar', 'al-anika'),
        'section' => 'al_anika_header_layout',
        'type'    => 'checkbox',
    ));
    
    $wp_customize->add_setting('enable_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('enable_sticky_header', array(
        'label'   => esc_html__('Enable Sticky Header', 'al-anika'),
        'section' => 'al_anika_header_layout',
        'type'    => 'checkbox',
    ));
    
    $wp_customize->add_setting('show_search_in_header', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('show_search_in_header', array(
        'label'   => esc_html__('Show Search in Header', 'al-anika'),
        'section' => 'al_anika_header_layout',
        'type'    => 'checkbox',
    ));
    
    // Header Contact Info
    $wp_customize->add_section('al_anika_header_contact', array(
        'title'    => esc_html__('Contact Information', 'al-anika'),
        'panel'    => 'al_anika_header',
        'priority' => 20,
    ));
    
    $wp_customize->add_setting('header_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('header_phone', array(
        'label'   => esc_html__('Phone Number', 'al-anika'),
        'section' => 'al_anika_header_contact',
        'type'    => 'text',
    ));
    
    $wp_customize->add_setting('header_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('header_email', array(
        'label'   => esc_html__('Email Address', 'al-anika'),
        'section' => 'al_anika_header_contact',
        'type'    => 'email',
    ));
    
    /**
     * NAVIGATION SETTINGS
     */
    $wp_customize->add_section('al_anika_navigation', array(
        'title'    => esc_html__('Navigation Settings', 'al-anika'),
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('enable_mega_menu', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('enable_mega_menu', array(
        'label'   => esc_html__('Enable Mega Menu', 'al-anika'),
        'section' => 'al_anika_navigation',
        'type'    => 'checkbox',
    ));
    
    $wp_customize->add_setting('enable_breadcrumbs', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('enable_breadcrumbs', array(
        'label'   => esc_html__('Enable Breadcrumbs', 'al-anika'),
        'section' => 'al_anika_navigation',
        'type'    => 'checkbox',
    ));
    
    /**
     * FOOTER SETTINGS
     */
    $wp_customize->add_section('al_anika_footer', array(
        'title'    => esc_html__('Footer Settings', 'al-anika'),
        'priority' => 40,
    ));
    
    $wp_customize->add_setting('footer_about_text', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('footer_about_text', array(
        'label'   => esc_html__('About Text', 'al-anika'),
        'section' => 'al_anika_footer',
        'type'    => 'textarea',
    ));
    
    $wp_customize->add_setting('footer_address', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('footer_address', array(
        'label'   => esc_html__('Business Address', 'al-anika'),
        'section' => 'al_anika_footer',
        'type'    => 'textarea',
    ));
    
    $wp_customize->add_setting('business_hours', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('business_hours', array(
        'label'   => esc_html__('Business Hours', 'al-anika'),
        'section' => 'al_anika_footer',
        'type'    => 'textarea',
    ));
    
    $wp_customize->add_setting('footer_copyright', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('footer_copyright', array(
        'label'   => esc_html__('Copyright Text', 'al-anika'),
        'section' => 'al_anika_footer',
        'type'    => 'textarea',
    ));
    
    $wp_customize->add_setting('show_back_to_top', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('show_back_to_top', array(
        'label'   => esc_html__('Show Back to Top Button', 'al-anika'),
        'section' => 'al_anika_footer',
        'type'    => 'checkbox',
    ));
    
    /**
     * SOCIAL MEDIA SETTINGS
     */
    $wp_customize->add_section('al_anika_social', array(
        'title'    => esc_html__('Social Media', 'al-anika'),
        'priority' => 50,
    ));
    
    $social_platforms = array(
        'facebook'  => 'Facebook',
        'twitter'   => 'Twitter',
        'instagram' => 'Instagram',
        'linkedin'  => 'LinkedIn',
        'youtube'   => 'YouTube',
        'pinterest' => 'Pinterest',
        'tiktok'    => 'TikTok',
    );
    
    foreach ($social_platforms as $platform => $label) {
        $wp_customize->add_setting($platform . '_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control($platform . '_url', array(
            'label'   => sprintf(esc_html__('%s URL', 'al-anika'), $label),
            'section' => 'al_anika_social',
            'type'    => 'url',
        ));
    }
    
    /**
     * WOOCOMMERCE SETTINGS
     */
    if (class_exists('WooCommerce')) {
        $wp_customize->add_panel('al_anika_woocommerce', array(
            'title'       => esc_html__('WooCommerce Settings', 'al-anika'),
            'description' => esc_html__('Customize WooCommerce features', 'al-anika'),
            'priority'    => 60,
        ));
        
        // Shop Settings
        $wp_customize->add_section('al_anika_shop', array(
            'title'    => esc_html__('Shop Settings', 'al-anika'),
            'panel'    => 'al_anika_woocommerce',
            'priority' => 10,
        ));
        
        $wp_customize->add_setting('show_cart_in_header', array(
            'default'           => true,
            'sanitize_callback' => 'al_anika_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('show_cart_in_header', array(
            'label'   => esc_html__('Show Cart in Header', 'al-anika'),
            'section' => 'al_anika_shop',
            'type'    => 'checkbox',
        ));
        
        $wp_customize->add_setting('show_wishlist_in_header', array(
            'default'           => true,
            'sanitize_callback' => 'al_anika_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('show_wishlist_in_header', array(
            'label'   => esc_html__('Show Wishlist in Header', 'al-anika'),
            'section' => 'al_anika_shop',
            'type'    => 'checkbox',
        ));
        
        $wp_customize->add_setting('show_mini_cart', array(
            'default'           => true,
            'sanitize_callback' => 'al_anika_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('show_mini_cart', array(
            'label'   => esc_html__('Show Mini Cart Dropdown', 'al-anika'),
            'section' => 'al_anika_shop',
            'type'    => 'checkbox',
        ));
        
        $wp_customize->add_setting('show_payment_methods', array(
            'default'           => true,
            'sanitize_callback' => 'al_anika_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('show_payment_methods', array(
            'label'   => esc_html__('Show Payment Methods in Footer', 'al-anika'),
            'section' => 'al_anika_shop',
            'type'    => 'checkbox',
        ));
    }
    
    /**
     * ADVANCED FEATURES
     */
    $wp_customize->add_panel('al_anika_advanced', array(
        'title'       => esc_html__('Advanced Features', 'al-anika'),
        'description' => esc_html__('Enable/disable advanced theme features', 'al-anika'),
        'priority'    => 70,
    ));
    
    // Search System
    $wp_customize->add_section('al_anika_search_settings', array(
        'title'    => esc_html__('Search System', 'al-anika'),
        'panel'    => 'al_anika_advanced',
        'priority' => 10,
    ));
    
    $wp_customize->add_setting('al_anika_enable_advanced_search', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('al_anika_enable_advanced_search', array(
        'label'   => esc_html__('Enable Advanced Search System', 'al-anika'),
        'section' => 'al_anika_search_settings',
        'type'    => 'checkbox',
    ));
    
    // User Accounts
    $wp_customize->add_section('al_anika_accounts_settings', array(
        'title'    => esc_html__('User Account System', 'al-anika'),
        'panel'    => 'al_anika_advanced',
        'priority' => 20,
    ));
    
    $wp_customize->add_setting('al_anika_enable_advanced_accounts', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('al_anika_enable_advanced_accounts', array(
        'label'   => esc_html__('Enable Advanced User Accounts & Dashboard', 'al-anika'),
        'section' => 'al_anika_accounts_settings',
        'type'    => 'checkbox',
    ));
    
    // Checkout System
    $wp_customize->add_section('al_anika_checkout_settings', array(
        'title'    => esc_html__('Checkout System', 'al-anika'),
        'panel'    => 'al_anika_advanced',
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('al_anika_enable_advanced_checkout', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('al_anika_enable_advanced_checkout', array(
        'label'   => esc_html__('Enable Advanced Checkout & Payment System', 'al-anika'),
        'section' => 'al_anika_checkout_settings',
        'type'    => 'checkbox',
    ));
    
    // Analytics
    $wp_customize->add_section('al_anika_analytics_settings', array(
        'title'    => esc_html__('Analytics & Performance', 'al-anika'),
        'panel'    => 'al_anika_advanced',
        'priority' => 40,
    ));
    
    $wp_customize->add_setting('al_anika_enable_analytics', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('al_anika_enable_analytics', array(
        'label'   => esc_html__('Enable Analytics & Performance Tracking', 'al-anika'),
        'section' => 'al_anika_analytics_settings',
        'type'    => 'checkbox',
    ));
    
    $wp_customize->add_setting('google_analytics_id', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('google_analytics_id', array(
        'label'   => esc_html__('Google Analytics ID', 'al-anika'),
        'section' => 'al_anika_analytics_settings',
        'type'    => 'text',
    ));
    
    $wp_customize->add_setting('facebook_pixel_id', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('facebook_pixel_id', array(
        'label'   => esc_html__('Facebook Pixel ID', 'al-anika'),
        'section' => 'al_anika_analytics_settings',
        'type'    => 'text',
    ));
    
    // Performance Settings
    $wp_customize->add_section('al_anika_performance', array(
        'title'    => esc_html__('Performance Options', 'al-anika'),
        'panel'    => 'al_anika_advanced',
        'priority' => 50,
    ));
    
    $wp_customize->add_setting('disable_emojis', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('disable_emojis', array(
        'label'   => esc_html__('Disable WordPress Emojis', 'al-anika'),
        'section' => 'al_anika_performance',
        'type'    => 'checkbox',
    ));
    
    $wp_customize->add_setting('enable_lazy_loading', array(
        'default'           => true,
        'sanitize_callback' => 'al_anika_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('enable_lazy_loading', array(
        'label'   => esc_html__('Enable Image Lazy Loading', 'al-anika'),
        'section' => 'al_anika_performance',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'al_anika_customize_register');

/**
 * Render the site title for the selective refresh partial.
 */
function al_anika_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 */
function al_anika_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function al_anika_customize_preview_js() {
    wp_enqueue_script('al-anika-customizer', AL_ANIKA_ASSETS_URI . '/js/customizer-preview.js', array('customize-preview'), AL_ANIKA_VERSION, true);
}
add_action('customize_preview_init', 'al_anika_customize_preview_js');