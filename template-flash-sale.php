<?php
/**
 * Template Name: Flash Sale Page
 * Time-limited flash sale page with dynamic animations
 * 
 * @package AlamAlAnika
 * @version 3.0.0
 */

get_header(); ?>

<div class="alam-flash-sale-page">
    <!-- Flash Sale Hero -->
    <section class="alam-flash-hero">
        <div class="alam-flash-bg-animation">
            <div class="alam-flash-spark"></div>
            <div class="alam-flash-spark"></div>
            <div class="alam-flash-spark"></div>
        </div>
        
        <div class="container">
            <div class="alam-flash-hero-content">
                <div class="alam-flash-badge">
                    <span class="alam-flash-icon">⚡</span>
                    <span><?php echo get_theme_mod('flash_sale_badge', 'FLASH SALE'); ?></span>
                    <span class="alam-flash-icon">⚡</span>
                </div>
                
                <h1 class="alam-flash-title">
                    <?php echo get_theme_mod('flash_sale_title', 'عروض البرق - خصومات فورية'); ?>
                </h1>
                
                <p class="alam-flash-subtitle">
                    <?php echo get_theme_mod('flash_sale_subtitle', 'عروض سريعة لوقت محدود - لا تفوت الفرصة!'); ?>
                </p>
                
                <div class="alam-flash-countdown" data-end-date="<?php echo get_theme_mod('flash_sale_end_date', date('Y-m-d H:i:s', strtotime('+2 hours'))); ?>">
                    <div class="alam-flash-countdown-wrapper">
                        <div class="alam-flash-countdown-item">
                            <span class="alam-flash-countdown-number hours">00</span>
                            <span class="alam-flash-countdown-label">ساعة</span>
                        </div>
                        <div class="alam-flash-countdown-separator">:</div>
                        <div class="alam-flash-countdown-item">
                            <span class="alam-flash-countdown-number minutes">00</span>
                            <span class="alam-flash-countdown-label">دقيقة</span>
                        </div>
                        <div class="alam-flash-countdown-separator">:</div>
                        <div class="alam-flash-countdown-item">
                            <span class="alam-flash-countdown-number seconds">00</span>
                            <span class="alam-flash-countdown-label">ثانية</span>
                        </div>
                    </div>
                    
                    <div class="alam-flash-urgency">
                        <span class="alam-flash-urgency-text">العرض ينتهي قريباً!</span>
                        <div class="alam-flash-urgency-bar">
                            <div class="alam-flash-urgency-fill"></div>
                        </div>
                    </div>
                </div>
                
                <div class="alam-flash-stats">
                    <div class="alam-flash-stat">
                        <span class="alam-flash-stat-number" id="flash-products-count">0</span>
                        <span class="alam-flash-stat-label">منتج مخفض</span>
                    </div>
                    <div class="alam-flash-stat">
                        <span class="alam-flash-stat-number" id="flash-savings-amount">0</span>
                        <span class="alam-flash-stat-label">ريال توفير</span>
                    </div>
                    <div class="alam-flash-stat">
                        <span class="alam-flash-stat-number" id="flash-customers-count">0</span>
                        <span class="alam-flash-stat-label">عميل مشترك</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Flash Sale Grid -->
    <section class="alam-flash-products">
        <div class="container">
            <div class="alam-flash-products-header">
                <h2>عروض البرق النشطة</h2>
                <div class="alam-flash-filters">
                    <button class="alam-flash-filter active" data-filter="all">الكل</button>
                    <button class="alam-flash-filter" data-filter="electronics">إلكترونيات</button>
                    <button class="alam-flash-filter" data-filter="fashion">أزياء</button>
                    <button class="alam-flash-filter" data-filter="home">منزل</button>
                </div>
            </div>
            
            <div class="alam-flash-products-grid">
                <?php
                // Get flash sale products (products with time-limited sales)
                $flash_products = wc_get_products(array(
                    'status' => 'publish',
                    'limit' => 8,
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => '_sale_price',
                            'value' => '',
                            'compare' => '!='
                        ),
                        array(
                            'key' => '_sale_price_dates_to',
                            'value' => current_time('timestamp'),
                            'compare' => '>'
                        )
                    )
                ));
                
                if ($flash_products) {
                    foreach ($flash_products as $index => $product) {
                        $GLOBALS['product'] = $product;
                        
                        // Calculate sale percentage
                        $regular_price = (float) $product->get_regular_price();
                        $sale_price = (float) $product->get_sale_price();
                        $discount_percentage = $regular_price > 0 ? round(((($regular_price - $sale_price) / $regular_price) * 100)) : 0;
                        
                        // Get sale end time
                        $sale_end = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
                        $time_left = $sale_end ? ($sale_end - current_time('timestamp')) : 0;
                        
                        ?>
                        <div class="alam-flash-product-item" data-category="<?php echo esc_attr(get_the_terms($product->get_id(), 'product_cat')[0]->slug ?? 'general'); ?>" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                            <div class="alam-flash-product-card">
                                <div class="alam-flash-product-image">
                                    <a href="<?php echo get_permalink($product->get_id()); ?>">
                                        <?php echo $product->get_image('medium'); ?>
                                    </a>
                                    
                                    <div class="alam-flash-product-badges">
                                        <span class="alam-flash-discount-badge">-<?php echo $discount_percentage; ?>%</span>
                                        <?php if ($time_left > 0 && $time_left < 3600): ?>
                                            <span class="alam-flash-urgent-badge">ينتهي قريباً!</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="alam-flash-product-timer" data-end-time="<?php echo $sale_end; ?>">
                                        <span class="alam-flash-timer-label">ينتهي خلال:</span>
                                        <span class="alam-flash-timer-time">--:--:--</span>
                                    </div>
                                    
                                    <div class="alam-flash-product-actions">
                                        <a href="<?php echo get_permalink($product->get_id()); ?>" class="alam-flash-quick-view" title="عرض سريع">
                                            <svg width="20" height="20" viewBox="0 0 20 20">
                                                <path d="M10 3C5 3 1 10 1 10s4 7 9 7 9-7 9-7-4-7-9-7z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                                <circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                            </svg>
                                        </a>
                                        
                                        <?php if ($product->is_purchasable() && $product->is_in_stock()): ?>
                                            <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="alam-flash-add-to-cart" title="أضف للسلة">
                                                <svg width="20" height="20" viewBox="0 0 20 20">
                                                    <path d="M5 7h10l-1 8H6L5 7zM5 7L3 3H1m4 4v0a2 2 0 002 2h6a2 2 0 002-2v0M9 11v4m2-4v4" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="alam-flash-product-content">
                                    <h3 class="alam-flash-product-title">
                                        <a href="<?php echo get_permalink($product->get_id()); ?>">
                                            <?php echo $product->get_name(); ?>
                                        </a>
                                    </h3>
                                    
                                    <div class="alam-flash-product-rating">
                                        <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                                    </div>
                                    
                                    <div class="alam-flash-product-price">
                                        <span class="alam-flash-sale-price"><?php echo wc_price($sale_price); ?></span>
                                        <span class="alam-flash-regular-price"><?php echo wc_price($regular_price); ?></span>
                                    </div>
                                    
                                    <div class="alam-flash-product-stock">
                                        <?php
                                        $stock_quantity = $product->get_stock_quantity();
                                        if ($stock_quantity && $stock_quantity <= 10):
                                        ?>
                                            <div class="alam-flash-stock-bar">
                                                <div class="alam-flash-stock-fill" style="width: <?php echo ($stock_quantity / 20) * 100; ?>%;"></div>
                                            </div>
                                            <span class="alam-flash-stock-text">متبقي <?php echo $stock_quantity; ?> قطع فقط!</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo '<div class="alam-no-flash-products"><p>لا توجد عروض برق نشطة حالياً</p></div>';
                }
                ?>
            </div>
            
            <div class="alam-flash-load-more">
                <button class="alam-flash-load-more-btn" id="load-more-flash">
                    تحميل المزيد من العروض
                    <div class="alam-flash-load-spinner"></div>
                </button>
            </div>
        </div>
    </section>
    
    <!-- Flash Sale Benefits -->
    <section class="alam-flash-benefits">
        <div class="container">
            <div class="alam-flash-benefits-grid">
                <div class="alam-flash-benefit-item">
                    <div class="alam-flash-benefit-icon">
                        <svg width="40" height="40" viewBox="0 0 40 40">
                            <path d="M20 5l5 10h10l-8 6 3 10-10-7-10 7 3-10-8-6h10l5-10z" fill="currentColor"/>
                        </svg>
                    </div>
                    <h4>خصومات حصرية</h4>
                    <p>خصومات تصل إلى 80% على منتجات مختارة</p>
                </div>
                
                <div class="alam-flash-benefit-item">
                    <div class="alam-flash-benefit-icon">
                        <svg width="40" height="40" viewBox="0 0 40 40">
                            <circle cx="20" cy="20" r="15" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M20 10v10l5 5" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                    </div>
                    <h4>وقت محدود</h4>
                    <p>عروض سريعة لفترة قصيرة - اغتنم الفرصة</p>
                </div>
                
                <div class="alam-flash-benefit-item">
                    <div class="alam-flash-benefit-icon">
                        <svg width="40" height="40" viewBox="0 0 40 40">
                            <path d="M5 15l15-10 15 10v20H5V15z" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M15 35V25h10v10" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                    </div>
                    <h4>توصيل سريع</h4>
                    <p>توصيل مجاني للطلبات خلال عروض البرق</p>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>