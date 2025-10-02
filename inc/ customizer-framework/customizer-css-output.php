<?php
/**
 * Advanced Customizer CSS Output Generator
 * Generates CSS for all customizer settings
 *
 * @package Al_Anika_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Advanced Customizer CSS Output
 */
class Al_Anika_Customizer_CSS_Output {
    
    public function __construct() {
        add_action( 'wp_head', array( $this, 'output_customizer_css' ) );
    }
    
    /**
     * Output all customizer CSS
     */
    public function output_customizer_css() {
        $css = '';
        
        // Generate CSS for each section
        $css .= $this->generate_typography_css();
        $css .= $this->generate_hero_sections_css();
        $css .= $this->generate_filters_css();
        $css .= $this->generate_product_swatches_css();
        $css .= $this->generate_animations_css();
        $css .= $this->generate_advertisement_css();
        
        // Output the CSS
        if ( ! empty( $css ) ) {
            echo '<style type="text/css" id="al-anika-customizer-css">' . $css . '</style>';
        }
    }
    
    /**
     * Generate Typography & Color CSS
     */
    private function generate_typography_css() {
        $css = '';
        
        // Get customizer settings
        $primary_font = get_theme_mod( 'al_anika_primary_font', 'Inter' );
        $secondary_font = get_theme_mod( 'al_anika_secondary_font', 'Poppins' );
        $base_font_size = get_theme_mod( 'al_anika_base_font_size', '16' );
        
        // Colors
        $h1_color = get_theme_mod( 'al_anika_h1_color', '#2c3e50' );
        $h2_color = get_theme_mod( 'al_anika_h2_color', '#34495e' );
        $h3_color = get_theme_mod( 'al_anika_h3_color', '#3498db' );
        $body_color = get_theme_mod( 'al_anika_body_color', '#555555' );
        $link_color = get_theme_mod( 'al_anika_link_color', '#e74c3c' );
        $link_hover_color = get_theme_mod( 'al_anika_link_hover_color', '#c0392b' );
        
        // Font sizes
        $h1_size = get_theme_mod( 'al_anika_h1_size', '36' );
        $h2_size = get_theme_mod( 'al_anika_h2_size', '30' );
        
        // Product colors
        $product_title_color = get_theme_mod( 'al_anika_product_title_color', '#2c3e50' );
        $product_price_color = get_theme_mod( 'al_anika_product_price_color', '#e74c3c' );
        $sale_price_color = get_theme_mod( 'al_anika_sale_price_color', '#27ae60' );
        
        $css .= "
        :root {
            --al-anika-primary-font: '{$primary_font}', sans-serif;
            --al-anika-secondary-font: '{$secondary_font}', sans-serif;
            --al-anika-base-font-size: {$base_font_size}px;
            --al-anika-h1-color: {$h1_color};
            --al-anika-h2-color: {$h2_color};
            --al-anika-h3-color: {$h3_color};
            --al-anika-body-color: {$body_color};
            --al-anika-link-color: {$link_color};
            --al-anika-link-hover-color: {$link_hover_color};
        }
        
        body {
            font-family: var(--al-anika-primary-font);
            font-size: var(--al-anika-base-font-size);
            color: var(--al-anika-body-color);
        }
        
        h1, .h1 {
            font-family: var(--al-anika-secondary-font);
            color: var(--al-anika-h1-color);
            font-size: {$h1_size}px;
        }
        
        h2, .h2 {
            font-family: var(--al-anika-secondary-font);
            color: var(--al-anika-h2-color);
            font-size: {$h2_size}px;
        }
        
        h3, .h3 {
            font-family: var(--al-anika-secondary-font);
            color: var(--al-anika-h3-color);
        }
        
        a {
            color: var(--al-anika-link-color);
        }
        
        a:hover {
            color: var(--al-anika-link-hover-color);
        }
        
        .product-title {
            color: {$product_title_color};
        }
        
        .product-price {
            color: {$product_price_color};
        }
        
        .sale-price {
            color: {$sale_price_color};
        }
        ";
        
        return $css;
    }
    
    /**
     * Generate Hero Sections CSS
     */
    private function generate_hero_sections_css() {
        $css = '';
        
        $hero_height = get_theme_mod( 'al_anika_hero_height', '600' );
        $hero_bg_color = get_theme_mod( 'al_anika_hero_bg_color', '#ff6b9d' );
        $hero_bg_color_2 = get_theme_mod( 'al_anika_hero_bg_color_2', '#4ecdc4' );
        $hero_text_color = get_theme_mod( 'al_anika_hero_text_color', '#ffffff' );
        $hero_bg_type = get_theme_mod( 'al_anika_hero_bg_type', 'gradient' );
        
        $background = '';
        switch ( $hero_bg_type ) {
            case 'color':
                $background = "background: {$hero_bg_color};";
                break;
            case 'gradient':
                $background = "background: linear-gradient(135deg, {$hero_bg_color} 0%, {$hero_bg_color_2} 100%);";
                break;
        }
        
        $css .= "
        .hero-section {
            height: {$hero_height}px;
            {$background}
            color: {$hero_text_color};
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-content {
            text-align: center;
            z-index: 2;
        }
        ";
        
        // Hero animations
        $animations_enabled = get_theme_mod( 'al_anika_hero_animations_enable', true );
        $animation_type = get_theme_mod( 'al_anika_hero_animation_type', 'fade_slide' );
        $animation_duration = get_theme_mod( 'al_anika_hero_animation_duration', '1000' );
        
        if ( $animations_enabled ) {
            $css .= "
            @keyframes heroFadeSlide {
                0% { opacity: 0; transform: translateY(50px); }
                100% { opacity: 1; transform: translateY(0); }
            }
            
            .hero-content {
                animation: heroFadeSlide {$animation_duration}ms ease-out;
            }
            ";
        }
        
        return $css;
    }
    
    /**
     * Generate Filters CSS
     */
    private function generate_filters_css() {
        $css = '';
        
        $filter_position = get_theme_mod( 'al_anika_filter_position', 'sidebar_left' );
        $filter_width = get_theme_mod( 'al_anika_filter_width', '300' );
        $filter_bg_color = get_theme_mod( 'al_anika_filter_bg_color', '#ffffff' );
        $filter_border_color = get_theme_mod( 'al_anika_filter_border_color', '#e0e0e0' );
        $filter_text_color = get_theme_mod( 'al_anika_filter_text_color', '#333333' );
        
        $position_styles = '';
        switch ( $filter_position ) {
            case 'sidebar_left':
                $position_styles = "
                    position: relative;
                    float: left;
                    width: {$filter_width}px;
                ";
                break;
            case 'sidebar_right':
                $position_styles = "
                    position: relative;
                    float: right;
                    width: {$filter_width}px;
                ";
                break;
            case 'floating_left':
                $position_styles = "
                    position: fixed;
                    left: 20px;
                    top: 50%;
                    transform: translateY(-50%);
                    width: {$filter_width}px;
                    z-index: 1000;
                ";
                break;
        }
        
        $css .= "
        .product-filters {
            {$position_styles}
            background: {$filter_bg_color};
            border: 1px solid {$filter_border_color};
            color: {$filter_text_color};
            padding: 20px;
            border-radius: 8px;
        }
        ";
        
        return $css;
    }
    
    /**
     * Generate Product Swatches CSS
     */
    private function generate_product_swatches_css() {
        $css = '';
        
        $swatch_size = get_theme_mod( 'al_anika_swatches_size', '30' );
        $swatch_shape = get_theme_mod( 'al_anika_swatches_shape', 'circle' );
        $border_color = get_theme_mod( 'al_anika_border_color', '#ff6b9d' );
        $border_width = get_theme_mod( 'al_anika_border_width', '2' );
        $border_radius = get_theme_mod( 'al_anika_border_radius', '10' );
        $inner_border_color = get_theme_mod( 'al_anika_inner_border_color', '#ffffff' );
        $hover_border_color = get_theme_mod( 'al_anika_hover_border_color', '#4ecdc4' );
        
        $shape_styles = '';
        switch ( $swatch_shape ) {
            case 'circle':
                $shape_styles = 'border-radius: 50%;';
                break;
            case 'square':
                $shape_styles = 'border-radius: 0;';
                break;
            case 'rounded':
                $shape_styles = 'border-radius: 8px;';
                break;
        }
        
        $css .= "
        .product-color-swatches .swatch {
            width: {$swatch_size}px;
            height: {$swatch_size}px;
            border: {$border_width}px solid {$border_color};
            {$shape_styles}
            display: inline-block;
            margin: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .product-color-swatches .swatch:hover {
            border-color: {$hover_border_color};
            transform: scale(1.1);
        }
        
        .product-color-swatches .swatch.selected {
            border-color: {$hover_border_color};
            transform: scale(1.15);
            box-shadow: 0 0 10px rgba(255, 107, 157, 0.5);
        }
        
        .product-image {
            border: {$border_width}px solid {$border_color};
            border-radius: {$border_radius}px;
            overflow: hidden;
        }
        
        .product-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 2px solid {$inner_border_color};
            border-radius: calc({$border_radius}px - 4px);
            pointer-events: none;
            opacity: 0.8;
        }
        ";
        
        return $css;
    }
    
    /**
     * Generate Animations CSS
     */
    private function generate_animations_css() {
        $css = '';
        
        $animations_enabled = get_theme_mod( 'al_anika_animations_enabled', true );
        $animation_speed = get_theme_mod( 'al_anika_animation_speed', '400' );
        $scroll_animation = get_theme_mod( 'al_anika_scroll_animation_type', 'fade_up' );
        $product_hover = get_theme_mod( 'al_anika_product_hover_animation', 'scale_rotate' );
        
        if ( ! $animations_enabled ) {
            $css .= '
            * {
                animation: none !important;
                transition: none !important;
            }
            ';
            return $css;
        }
        
        $css .= "
        :root {
            --al-anika-animation-speed: {$animation_speed}ms;
        }
        
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all var(--al-anika-animation-speed) ease;
        }
        
        .fade-in-up.animate {
            opacity: 1;
            transform: translateY(0);
        }
        ";
        
        // Product hover animations
        switch ( $product_hover ) {
            case 'scale_rotate':
                $css .= "
                .product-item:hover {
                    transform: scale(1.05) rotate(1deg);
                    transition: transform var(--al-anika-animation-speed) ease;
                }
                ";
                break;
            case 'lift':
                $css .= "
                .product-item:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                    transition: all var(--al-anika-animation-speed) ease;
                }
                ";
                break;
        }
        
        return $css;
    }
    
    /**
     * Generate Advertisement CSS
     */
    private function generate_advertisement_css() {
        $css = '';
        
        $ad_text_color = get_theme_mod( 'al_anika_ad_text_color', '#ffffff' );
        $ad_bg_color = get_theme_mod( 'al_anika_ad_bg_color', '#e74c3c' );
        $ad_font_family = get_theme_mod( 'al_anika_ad_font_family', 'Poppins' );
        $floating_position = get_theme_mod( 'al_anika_floating_ad_position', 'bottom_right' );
        $animation_type = get_theme_mod( 'al_anika_banner_animation_type', 'slide_fade' );
        
        $css .= "
        .advertisement-banner {
            background: {$ad_bg_color};
            color: {$ad_text_color};
            font-family: '{$ad_font_family}', sans-serif;
            font-weight: bold;
            padding: 15px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        ";
        
        // Floating ad positioning
        $position_styles = '';
        switch ( $floating_position ) {
            case 'bottom_right':
                $position_styles = "
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    z-index: 9999;
                ";
                break;
            case 'top_right':
                $position_styles = "
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                ";
                break;
        }
        
        if ( $floating_position !== 'none' ) {
            $css .= "
            .floating-advertisement {
                {$position_styles}
                width: 250px;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            }
            ";
        }
        
        // Advertisement animations
        if ( $animation_type === 'pulse_glow' ) {
            $css .= "
            @keyframes adPulseGlow {
                0%, 100% { box-shadow: 0 0 5px {$ad_bg_color}; }
                50% { box-shadow: 0 0 20px {$ad_bg_color}, 0 0 30px {$ad_bg_color}; }
            }
            
            .advertisement-banner {
                animation: adPulseGlow 2s infinite;
            }
            ";
        }
        
        return $css;
    }
}

// Initialize the CSS output system
new Al_Anika_Customizer_CSS_Output();
