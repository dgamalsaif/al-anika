<?php
/**
 * Product Customization Controls for Theme Customizer
 * Phase 5: Advanced Product Customization
 *
 * @package AlamAlAnika
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add Product Customization Controls to Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function alam_add_product_customization_controls( $wp_customize ) {
    
    // Product Customization Panel
    $wp_customize->add_panel( 'alam_product_customization_panel', array(
        'title'       => esc_html__( 'تخصيص المنتجات', 'alam-al-anika' ),
        'description' => esc_html__( 'إعدادات متقدمة لعرض وتخصيص المنتجات', 'alam-al-anika' ),
        'priority'    => 40,
    ) );

    // === PRODUCT DISPLAY SECTION ===
    $wp_customize->add_section( 'alam_product_display', array(
        'title'    => esc_html__( 'عرض المنتجات', 'alam-al-anika' ),
        'panel'    => 'alam_product_customization_panel',
        'priority' => 10,
    ) );

    // Products per Row
    $wp_customize->add_setting( 'alam_products_per_row', array(
        'default'           => 4,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_products_per_row', array(
        'label'       => esc_html__( 'عدد المنتجات في الصف', 'alam-al-anika' ),
        'section'     => 'alam_product_display',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 2,
            'max'  => 6,
            'step' => 1,
        ),
    ) );

    // Products per Page
    $wp_customize->add_setting( 'alam_products_per_page', array(
        'default'           => 12,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_products_per_page', array(
        'label'       => esc_html__( 'عدد المنتجات في الصفحة', 'alam-al-anika' ),
        'section'     => 'alam_product_display',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 6,
            'max'  => 48,
            'step' => 6,
        ),
    ) );

    // Product Card Style
    $wp_customize->add_setting( 'alam_product_card_style', array(
        'default'           => 'modern_card',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_product_card_style', array(
        'label'    => esc_html__( 'نمط بطاقة المنتج', 'alam-al-anika' ),
        'section'  => 'alam_product_display',
        'type'     => 'select',
        'choices'  => array(
            'modern_card'    => esc_html__( 'بطاقة عصرية', 'alam-al-anika' ),
            'minimal_clean'  => esc_html__( 'تصميم نظيف', 'alam-al-anika' ),
            'magazine_style' => esc_html__( 'نمط مجلة', 'alam-al-anika' ),
            'grid_overlay'   => esc_html__( 'شبكة مع طبقة', 'alam-al-anika' ),
            'card_3d'        => esc_html__( 'بطاقة ثلاثية الأبعاد', 'alam-al-anika' ),
        ),
    ) );

    // Show Product Quick View
    $wp_customize->add_setting( 'alam_product_quick_view', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_product_quick_view', array(
        'label'    => esc_html__( 'عرض النظرة السريعة', 'alam-al-anika' ),
        'section'  => 'alam_product_display',
        'type'     => 'checkbox',
    ) );

    // Show Wishlist Button
    $wp_customize->add_setting( 'alam_product_wishlist', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_product_wishlist', array(
        'label'    => esc_html__( 'عرض زر المفضلة', 'alam-al-anika' ),
        'section'  => 'alam_product_display',
        'type'     => 'checkbox',
    ) );

    // Show Compare Button
    $wp_customize->add_setting( 'alam_product_compare', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_product_compare', array(
        'label'    => esc_html__( 'عرض زر المقارنة', 'alam-al-anika' ),
        'section'  => 'alam_product_display',
        'type'     => 'checkbox',
    ) );

    // === PRODUCT GALLERY SECTION ===
    $wp_customize->add_section( 'alam_product_gallery', array(
        'title'    => esc_html__( 'معرض المنتج', 'alam-al-anika' ),
        'panel'    => 'alam_product_customization_panel',
        'priority' => 20,
    ) );

    // Gallery Layout
    $wp_customize->add_setting( 'alam_gallery_layout', array(
        'default'           => 'thumbnails_left',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_gallery_layout', array(
        'label'    => esc_html__( 'تخطيط المعرض', 'alam-al-anika' ),
        'section'  => 'alam_product_gallery',
        'type'     => 'select',
        'choices'  => array(
            'thumbnails_left'   => esc_html__( 'صور مصغرة يسار', 'alam-al-anika' ),
            'thumbnails_bottom' => esc_html__( 'صور مصغرة أسفل', 'alam-al-anika' ),
            'slider_dots'       => esc_html__( 'منزلق مع نقاط', 'alam-al-anika' ),
            'grid_layout'       => esc_html__( 'تخطيط شبكي', 'alam-al-anika' ),
            'masonry_style'     => esc_html__( 'نمط البناء', 'alam-al-anika' ),
        ),
    ) );

    // Enable Gallery Zoom
    $wp_customize->add_setting( 'alam_gallery_zoom', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_gallery_zoom', array(
        'label'    => esc_html__( 'تفعيل تكبير الصور', 'alam-al-anika' ),
        'section'  => 'alam_product_gallery',
        'type'     => 'checkbox',
    ) );

    // Enable Gallery Lightbox
    $wp_customize->add_setting( 'alam_gallery_lightbox', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_gallery_lightbox', array(
        'label'    => esc_html__( 'تفعيل صندوق الضوء', 'alam-al-anika' ),
        'section'  => 'alam_product_gallery',
        'type'     => 'checkbox',
    ) );

    // Enable Video in Gallery
    $wp_customize->add_setting( 'alam_gallery_video', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_gallery_video', array(
        'label'    => esc_html__( 'تفعيل الفيديو في المعرض', 'alam-al-anika' ),
        'section'  => 'alam_product_gallery',
        'type'     => 'checkbox',
    ) );

    // === PRODUCT SWATCHES SECTION ===
    $wp_customize->add_section( 'alam_product_swatches', array(
        'title'    => esc_html__( 'عينات المنتج', 'alam-al-anika' ),
        'panel'    => 'alam_product_customization_panel',
        'priority' => 30,
    ) );

    // Enable Color Swatches
    $wp_customize->add_setting( 'alam_color_swatches', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_color_swatches', array(
        'label'    => esc_html__( 'تفعيل عينات الألوان', 'alam-al-anika' ),
        'section'  => 'alam_product_swatches',
        'type'     => 'checkbox',
    ) );

    // Swatch Style
    $wp_customize->add_setting( 'alam_swatch_style', array(
        'default'           => 'circle',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_swatch_style', array(
        'label'    => esc_html__( 'نمط العينة', 'alam-al-anika' ),
        'section'  => 'alam_product_swatches',
        'type'     => 'select',
        'choices'  => array(
            'circle'    => esc_html__( 'دائري', 'alam-al-anika' ),
            'square'    => esc_html__( 'مربع', 'alam-al-anika' ),
            'rounded'   => esc_html__( 'مربع مدور', 'alam-al-anika' ),
            'hexagon'   => esc_html__( 'سداسي', 'alam-al-anika' ),
        ),
    ) );

    // Swatch Size
    $wp_customize->add_setting( 'alam_swatch_size', array(
        'default'           => 32,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_swatch_size', array(
        'label'       => esc_html__( 'حجم العينة (بكسل)', 'alam-al-anika' ),
        'section'     => 'alam_product_swatches',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 60,
            'step' => 4,
        ),
    ) );

    // Enable Image Swatches
    $wp_customize->add_setting( 'alam_image_swatches', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_image_swatches', array(
        'label'    => esc_html__( 'تفعيل عينات الصور', 'alam-al-anika' ),
        'section'  => 'alam_product_swatches',
        'type'     => 'checkbox',
    ) );

    // === PRODUCT BADGES SECTION ===
    $wp_customize->add_section( 'alam_product_badges', array(
        'title'    => esc_html__( 'شارات المنتج', 'alam-al-anika' ),
        'panel'    => 'alam_product_customization_panel',
        'priority' => 40,
    ) );

    // Enable Sale Badge
    $wp_customize->add_setting( 'alam_sale_badge', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_sale_badge', array(
        'label'    => esc_html__( 'عرض شارة التخفيض', 'alam-al-anika' ),
        'section'  => 'alam_product_badges',
        'type'     => 'checkbox',
    ) );

    // Sale Badge Style
    $wp_customize->add_setting( 'alam_sale_badge_style', array(
        'default'           => 'percentage',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_sale_badge_style', array(
        'label'    => esc_html__( 'نمط شارة التخفيض', 'alam-al-anika' ),
        'section'  => 'alam_product_badges',
        'type'     => 'select',
        'choices'  => array(
            'percentage' => esc_html__( 'نسبة مئوية', 'alam-al-anika' ),
            'amount'     => esc_html__( 'مبلغ التوفير', 'alam-al-anika' ),
            'text_only'  => esc_html__( 'نص فقط', 'alam-al-anika' ),
        ),
    ) );

    // Enable New Badge
    $wp_customize->add_setting( 'alam_new_badge', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_new_badge', array(
        'label'    => esc_html__( 'عرض شارة جديد', 'alam-al-anika' ),
        'section'  => 'alam_product_badges',
        'type'     => 'checkbox',
    ) );

    // New Product Days
    $wp_customize->add_setting( 'alam_new_product_days', array(
        'default'           => 30,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_new_product_days', array(
        'label'    => esc_html__( 'أيام المنتج الجديد', 'alam-al-anika' ),
        'section'  => 'alam_product_badges',
        'type'     => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 90,
        ),
    ) );

    // Enable Hot Badge
    $wp_customize->add_setting( 'alam_hot_badge', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_hot_badge', array(
        'label'    => esc_html__( 'عرض شارة مميز', 'alam-al-anika' ),
        'section'  => 'alam_product_badges',
        'type'     => 'checkbox',
    ) );

    // === PRODUCT RECOMMENDATIONS SECTION ===
    $wp_customize->add_section( 'alam_product_recommendations', array(
        'title'    => esc_html__( 'التوصيات', 'alam-al-anika' ),
        'panel'    => 'alam_product_customization_panel',
        'priority' => 50,
    ) );

    // Enable Related Products
    $wp_customize->add_setting( 'alam_related_products', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_related_products', array(
        'label'    => esc_html__( 'عرض المنتجات ذات الصلة', 'alam-al-anika' ),
        'section'  => 'alam_product_recommendations',
        'type'     => 'checkbox',
    ) );

    // Number of Related Products
    $wp_customize->add_setting( 'alam_related_products_count', array(
        'default'           => 4,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_related_products_count', array(
        'label'       => esc_html__( 'عدد المنتجات ذات الصلة', 'alam-al-anika' ),
        'section'     => 'alam_product_recommendations',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 2,
            'max'  => 12,
            'step' => 1,
        ),
    ) );

    // Enable Cross-sell Products
    $wp_customize->add_setting( 'alam_cross_sell_products', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_cross_sell_products', array(
        'label'    => esc_html__( 'عرض المنتجات التكميلية', 'alam-al-anika' ),
        'section'  => 'alam_product_recommendations',
        'type'     => 'checkbox',
    ) );

    // Enable Recently Viewed
    $wp_customize->add_setting( 'alam_recently_viewed', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_recently_viewed', array(
        'label'    => esc_html__( 'عرض المنتجات المشاهدة مؤخراً', 'alam-al-anika' ),
        'section'  => 'alam_product_recommendations',
        'type'     => 'checkbox',
    ) );

    // === PRODUCT LAYOUT SECTION ===
    $wp_customize->add_section( 'alam_product_layout', array(
        'title'    => esc_html__( 'تخطيط المنتج', 'alam-al-anika' ),
        'panel'    => 'alam_product_customization_panel',
        'priority' => 60,
    ) );

    // Single Product Layout
    $wp_customize->add_setting( 'alam_single_product_layout', array(
        'default'           => 'default',
        'sanitize_callback' => 'alam_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_single_product_layout', array(
        'label'    => esc_html__( 'تخطيط صفحة المنتج', 'alam-al-anika' ),
        'section'  => 'alam_product_layout',
        'type'     => 'select',
        'choices'  => array(
            'default'      => esc_html__( 'افتراضي', 'alam-al-anika' ),
            'wide_gallery' => esc_html__( 'معرض واسع', 'alam-al-anika' ),
            'sticky_info'  => esc_html__( 'معلومات ثابتة', 'alam-al-anika' ),
            'tabs_vertical' => esc_html__( 'تبويبات عمودية', 'alam-al-anika' ),
            'accordion'    => esc_html__( 'أكورديون', 'alam-al-anika' ),
        ),
    ) );

    // Enable Product Tabs
    $wp_customize->add_setting( 'alam_product_tabs', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_product_tabs', array(
        'label'    => esc_html__( 'عرض تبويبات المنتج', 'alam-al-anika' ),
        'section'  => 'alam_product_layout',
        'type'     => 'checkbox',
    ) );

    // Enable Product Reviews
    $wp_customize->add_setting( 'alam_product_reviews', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_product_reviews', array(
        'label'    => esc_html__( 'عرض تقييمات المنتج', 'alam-al-anika' ),
        'section'  => 'alam_product_layout',
        'type'     => 'checkbox',
    ) );

    // Enable Social Sharing
    $wp_customize->add_setting( 'alam_product_social_sharing', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'alam_product_social_sharing', array(
        'label'    => esc_html__( 'عرض أزرار المشاركة', 'alam-al-anika' ),
        'section'  => 'alam_product_layout',
        'type'     => 'checkbox',
    ) );
}

// Hook into customizer
add_action( 'customize_register', 'alam_add_product_customization_controls' );
