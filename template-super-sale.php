<?php
/**
 * Template Name: Super Sale Page
 * Advanced sale page with countdown timers and special offers
 * 
 * @package AlamAlAnika
 * @version 3.0.0
 */

get_header(); ?>

<div class="alam-super-sale-page">
    <!-- Hero Section -->
    <section class="alam-sale-hero">
        <div class="alam-sale-hero-bg">
            <?php 
            $hero_bg = get_theme_mod('super_sale_hero_bg', get_template_directory_uri() . '/assets/images/sale-hero-bg.jpg');
            if ($hero_bg): ?>
                <img src="<?php echo esc_url($hero_bg); ?>" alt="Super Sale" class="alam-hero-bg-image">
            <?php endif; ?>
            <div class="alam-hero-overlay"></div>
        </div>
        
        <div class="container">
            <div class="alam-sale-hero-content">
                <div class="alam-sale-badge">
                    <span><?php echo get_theme_mod('super_sale_badge', 'SUPER SALE'); ?></span>
                </div>
                
                <h1 class="alam-sale-title">
                    <?php echo get_theme_mod('super_sale_title', 'تخفيضات هائلة تصل إلى 70%'); ?>
                </h1>
                
                <p class="alam-sale-subtitle">
                    <?php echo get_theme_mod('super_sale_subtitle', 'عروض محدودة الوقت على أفضل المنتجات'); ?>
                </p>
                
                <div class="alam-sale-countdown" data-end-date="<?php echo get_theme_mod('super_sale_end_date', date('Y-m-d H:i:s', strtotime('+7 days'))); ?>">
                    <div class="alam-countdown-item">
                        <span class="alam-countdown-number days">00</span>
                        <span class="alam-countdown-label">يوم</span>
                    </div>
                    <div class="alam-countdown-separator">:</div>
                    <div class="alam-countdown-item">
                        <span class="alam-countdown-number hours">00</span>
                        <span class="alam-countdown-label">ساعة</span>
                    </div>
                    <div class="alam-countdown-separator">:</div>
                    <div class="alam-countdown-item">
                        <span class="alam-countdown-number minutes">00</span>
                        <span class="alam-countdown-label">دقيقة</span>
                    </div>
                    <div class="alam-countdown-separator">:</div>
                    <div class="alam-countdown-item">
                        <span class="alam-countdown-number seconds">00</span>
                        <span class="alam-countdown-label">ثانية</span>
                    </div>
                </div>
                
                <div class="alam-sale-cta">
                    <a href="<?php echo al_anika_safe_wc_url('shop'); ?>" class="alam-cta-button primary">
                        <?php esc_html_e('تسوق الآن', 'alam-al-anika'); ?>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 3l7 7-7 7M3 10h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Sale Categories -->
    <section class="alam-sale-categories">
        <div class="container">
            <div class="alam-section-header">
                <h2>فئات العروض المميزة</h2>
                <p>اكتشف أفضل العروض في جميع الفئات</p>
            </div>
            
            <div class="alam-sale-categories-grid">
                <?php
                $sale_categories = get_theme_mod('super_sale_categories', array());
                if (empty($sale_categories)) {
                    // Get product categories with products on sale
                    $sale_categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'hide_empty' => true,
                        'number' => 6
                    ));
                }
                
                foreach ($sale_categories as $category):
                    $category_link = get_term_link($category);
                    $category_image = get_term_meta($category->term_id, 'thumbnail_id', true);
                    $image_url = $category_image ? wp_get_attachment_image_url($category_image, 'medium') : '';
                ?>
                    <div class="alam-sale-category-item">
                        <a href="<?php echo esc_url($category_link); ?>" class="alam-category-link">
                            <div class="alam-category-image">
                                <?php if ($image_url): ?>
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
                                <?php endif; ?>
                                <div class="alam-category-overlay">
                                    <span class="alam-category-sale-badge">خصم خاص</span>
                                </div>
                            </div>
                            <div class="alam-category-info">
                                <h3><?php echo esc_html($category->name); ?></h3>
                                <span class="alam-category-count"><?php echo $category->count; ?> منتج</span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Sale Products -->
    <section class="alam-sale-products">
        <div class="container">
            <div class="alam-section-header">
                <h2>المنتجات المخفضة</h2>
                <p>أفضل العروض والتخفيضات الحصرية</p>
            </div>
            
            <div class="alam-products-grid">
                <?php
                // Get products on sale
                $sale_products = wc_get_products(array(
                    'status' => 'publish',
                    'limit' => 12,
                    'meta_query' => array(
                        'relation' => 'OR',
                        array(
                            'key' => '_sale_price',
                            'value' => 0,
                            'compare' => '>',
                            'type' => 'NUMERIC'
                        ),
                        array(
                            'key' => '_min_variation_sale_price',
                            'value' => 0,
                            'compare' => '>',
                            'type' => 'NUMERIC'
                        )
                    )
                ));
                
                if ($sale_products) {
                    foreach ($sale_products as $product) {
                        $GLOBALS['product'] = $product;
                        wc_get_template_part('content', 'product');
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p class="alam-no-products">لا توجد منتجات مخفضة حالياً</p>';
                }
                ?>
            </div>
            
            <div class="alam-load-more-container">
                <a href="<?php echo al_anika_safe_wc_url('shop'); ?>" class="alam-load-more-btn">
                    <?php esc_html_e('عرض جميع المنتجات المخفضة', 'alam-al-anika'); ?>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Signup -->
    <section class="alam-sale-newsletter">
        <div class="container">
            <div class="alam-newsletter-content">
                <div class="alam-newsletter-text">
                    <h3>احصل على إشعارات العروض الحصرية</h3>
                    <p>اشترك في نشرتنا البريدية واحصل على خصم إضافي 10%</p>
                </div>
                
                <div class="alam-newsletter-form">
                    <form class="alam-newsletter-form-element">
                        <input type="email" placeholder="أدخل بريدك الإلكتروني" required>
                        <button type="submit">اشتراك</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>