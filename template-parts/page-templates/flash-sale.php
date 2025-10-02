<?php
/**
 * Template Name: Flash Sale Page
 * Ultra-fast flash sale page with real-time countdown and limited quantities
 */

get_header(); ?>

<div class="alam-flash-sale-page">
    <!-- Flash Sale Hero -->
    <section class="alam-flash-hero">
        <div class="alam-flash-bg-animation">
            <div class="alam-flash-particle"></div>
            <div class="alam-flash-particle"></div>
            <div class="alam-flash-particle"></div>
        </div>
        
        <div class="container">
            <div class="alam-flash-hero-content">
                <div class="alam-flash-badge">
                    <span class="alam-flash-icon">⚡</span>
                    <span class="alam-flash-text">FLASH SALE</span>
                </div>
                
                <h1 class="alam-flash-title">
                    <?php echo get_theme_mod('flash_sale_title', 'صفقة البرق!'); ?>
                    <span class="alam-flash-subtitle"><?php echo get_theme_mod('flash_sale_subtitle', 'تخفيضات تصل إلى 80%'); ?></span>
                </h1>
                
                <div class="alam-flash-countdown" data-end-date="<?php echo get_theme_mod('flash_sale_end_date', date('Y-m-d H:i:s', strtotime('+6 hours'))); ?>">
                    <div class="alam-countdown-wrapper">
                        <div class="alam-countdown-item">
                            <span class="alam-countdown-number" data-hours>00</span>
                            <span class="alam-countdown-label">ساعة</span>
                        </div>
                        <div class="alam-countdown-separator">:</div>
                        <div class="alam-countdown-item">
                            <span class="alam-countdown-number" data-minutes>00</span>
                            <span class="alam-countdown-label">دقيقة</span>
                        </div>
                        <div class="alam-countdown-separator">:</div>
                        <div class="alam-countdown-item">
                            <span class="alam-countdown-number" data-seconds>00</span>
                            <span class="alam-countdown-label">ثانية</span>
                        </div>
                    </div>
                    <div class="alam-flash-warning">
                        <span>⚠️ العرض ينتهي قريباً - كميات محدودة!</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Flash Deal of the Hour -->
    <section class="alam-deal-of-hour">
        <div class="container">
            <div class="alam-deal-header">
                <h2>صفقة الساعة</h2>
                <div class="alam-deal-timer" data-next-deal="<?php echo date('Y-m-d H:i:s', strtotime('+1 hour')); ?>">
                    الصفقة التالية في: <span class="alam-next-deal-time">59:59</span>
                </div>
            </div>
            
            <?php
            // Get featured flash sale product
            $featured_product_id = get_theme_mod('flash_sale_featured_product', 0);
            $featured_product = $featured_product_id ? wc_get_product($featured_product_id) : null;
            
            if (!$featured_product) {
                // Fallback to first sale product
                $sale_products = wc_get_products(array(
                    'limit' => 1,
                    'meta_query' => array(
                        array(
                            'key' => '_sale_price',
                            'value' => '',
                            'compare' => '!='
                        )
                    )
                ));
                $featured_product = !empty($sale_products) ? $sale_products[0] : null;
            }
            
            if ($featured_product):
                $regular_price = (float) $featured_product->get_regular_price();
                $sale_price = (float) $featured_product->get_sale_price();
                $discount_percentage = $regular_price > 0 ? round((($regular_price - $sale_price) / $regular_price) * 100) : 0;
                $stock_quantity = $featured_product->get_stock_quantity();
                $total_stock = get_post_meta($featured_product->get_id(), '_original_stock', true) ?: 100;
                $sold_percentage = $stock_quantity > 0 ? round((($total_stock - $stock_quantity) / $total_stock) * 100) : 0;
            ?>
                <div class="alam-featured-deal">
                    <div class="alam-deal-image">
                        <div class="alam-image-container">
                            <?php echo $featured_product->get_image('large'); ?>
                            <div class="alam-flash-overlay">
                                <div class="alam-discount-badge">
                                    -<?php echo $discount_percentage; ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alam-deal-details">
                        <h3 class="alam-deal-title"><?php echo $featured_product->get_name(); ?></h3>
                        
                        <div class="alam-deal-rating">
                            <?php echo wc_get_rating_html($featured_product->get_average_rating()); ?>
                            <span class="alam-rating-count">(<?php echo $featured_product->get_review_count(); ?> تقييم)</span>
                        </div>
                        
                        <div class="alam-deal-price">
                            <span class="alam-sale-price"><?php echo wc_price($sale_price); ?></span>
                            <span class="alam-regular-price"><?php echo wc_price($regular_price); ?></span>
                            <span class="alam-you-save">توفر <?php echo wc_price($regular_price - $sale_price); ?></span>
                        </div>
                        
                        <div class="alam-stock-progress">
                            <div class="alam-progress-header">
                                <span>الكمية المتبقية: <?php echo $stock_quantity; ?></span>
                                <span>تم البيع: <?php echo $sold_percentage; ?>%</span>
                            </div>
                            <div class="alam-progress-bar">
                                <div class="alam-progress-fill" style="width: <?php echo $sold_percentage; ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="alam-deal-actions">
                            <div class="alam-quantity-selector">
                                <button class="alam-qty-minus">-</button>
                                <input type="number" class="alam-qty-input" value="1" min="1" max="<?php echo $stock_quantity; ?>">
                                <button class="alam-qty-plus">+</button>
                            </div>
                            
                            <button class="alam-flash-buy-now" data-product-id="<?php echo $featured_product->get_id(); ?>">
                                <span class="alam-btn-text">اشتري الآن</span>
                                <span class="alam-btn-icon">🔥</span>
                            </button>
                            
                            <button class="alam-add-to-cart" data-product-id="<?php echo $featured_product->get_id(); ?>">
                                إضافة للسلة
                            </button>
                        </div>
                        
                        <div class="alam-deal-features">
                            <div class="alam-feature">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M16.5,9.5L15.09,8.09L10.5,12.69L8.91,11.09L7.5,12.5L10.5,15.5L16.5,9.5Z"/>
                                </svg>
                                <span>شحن مجاني</span>
                            </div>
                            <div class="alam-feature">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11.5C15.4,11.5 16,12.4 16,13V16C16,16.6 15.6,17 15,17H9C8.4,17 8,16.6 8,16V13C8,12.4 8.4,11.5 9,11.5V10C9,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.2,9 10.2,10V11.5H13.8V10C13.8,9 12.8,8.2 12,8.2Z"/>
                                </svg>
                                <span>دفع آمن</span>
                            </div>
                            <div class="alam-feature">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path d="M19,7H15.5V6.5A3.5,3.5 0 0,0 12,3A3.5,3.5 0 0,0 8.5,6.5V7H5A1,1 0 0,0 4,8V19A3,3 0 0,0 7,22H17A3,3 0 0,0 20,19V8A1,1 0 0,0 19,7Z"/>
                                </svg>
                                <span>ضمان الجودة</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Flash Sale Products Grid -->
    <section class="alam-flash-products">
        <div class="container">
            <div class="alam-section-header">
                <h2>عروض البرق المحدودة</h2>
                <div class="alam-flash-filters">
                    <button class="alam-filter-btn active" data-filter="all">الكل</button>
                    <button class="alam-filter-btn" data-filter="under-50">أقل من 50 ريال</button>
                    <button class="alam-filter-btn" data-filter="under-100">أقل من 100 ريال</button>
                    <button class="alam-filter-btn" data-filter="under-200">أقل من 200 ريال</button>
                    <button class="alam-filter-btn" data-filter="highest-discount">أعلى خصم</button>
                </div>
            </div>
            
            <div class="alam-flash-products-grid">
                <?php
                // Get flash sale products
                $flash_products = wc_get_products(array(
                    'limit' => 16,
                    'meta_query' => array(
                        array(
                            'key' => '_sale_price',
                            'value' => '',
                            'compare' => '!='
                        )
                    ),
                    'orderby' => 'rand'
                ));
                
                foreach ($flash_products as $product):
                    $regular_price = (float) $product->get_regular_price();
                    $sale_price = (float) $product->get_sale_price();
                    $discount_percentage = $regular_price > 0 ? round((($regular_price - $sale_price) / $regular_price) * 100) : 0;
                    $stock_quantity = $product->get_stock_quantity();
                    $flash_end_time = get_post_meta($product->get_id(), '_flash_sale_end', true);
                    if (!$flash_end_time) {
                        $flash_end_time = date('Y-m-d H:i:s', strtotime('+' . rand(1, 12) . ' hours'));
                    }
                ?>
                    <div class="alam-flash-product-card" 
                         data-price="<?php echo $sale_price; ?>" 
                         data-discount="<?php echo $discount_percentage; ?>">
                        
                        <div class="alam-product-image">
                            <a href="<?php echo $product->get_permalink(); ?>">
                                <?php echo $product->get_image('medium'); ?>
                            </a>
                            
                            <div class="alam-flash-badges">
                                <div class="alam-discount-badge">
                                    -<?php echo $discount_percentage; ?>%
                                </div>
                                
                                <?php if ($stock_quantity <= 5 && $stock_quantity > 0): ?>
                                    <div class="alam-low-stock-badge">
                                        باقي <?php echo $stock_quantity; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="alam-flash-badge">⚡</div>
                            </div>
                            
                            <div class="alam-product-actions">
                                <button class="alam-quick-view" data-product-id="<?php echo $product->get_id(); ?>" title="نظرة سريعة">
                                    <svg width="18" height="18" viewBox="0 0 24 24">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                                    </svg>
                                </button>
                                
                                <?php if (defined('YITH_WCWL')): ?>
                                    <button class="alam-add-wishlist" data-product-id="<?php echo $product->get_id(); ?>" title="إضافة لقائمة الأمنيات">
                                        <svg width="18" height="18" viewBox="0 0 24 24">
                                            <path d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5 2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.04L12,21.35Z"/>
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                            
                            <div class="alam-flash-timer" data-end-date="<?php echo $flash_end_time; ?>">
                                <div class="alam-timer-icon">⏰</div>
                                <div class="alam-timer-compact">
                                    <span class="alam-hours">00</span>:<span class="alam-minutes">00</span>:<span class="alam-seconds">00</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alam-product-content">
                            <h3 class="alam-product-title">
                                <a href="<?php echo $product->get_permalink(); ?>"><?php echo wp_trim_words($product->get_name(), 6); ?></a>
                            </h3>
                            
                            <div class="alam-product-rating">
                                <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                                <span class="alam-rating-count">(<?php echo $product->get_review_count(); ?>)</span>
                            </div>
                            
                            <div class="alam-product-price">
                                <span class="alam-sale-price"><?php echo wc_price($sale_price); ?></span>
                                <span class="alam-regular-price"><?php echo wc_price($regular_price); ?></span>
                            </div>
                            
                            <?php if ($stock_quantity > 0): ?>
                                <div class="alam-stock-indicator">
                                    <div class="alam-stock-bar">
                                        <div class="alam-stock-fill" style="width: <?php echo min(100, ($stock_quantity / 20) * 100); ?>%"></div>
                                    </div>
                                    <span class="alam-stock-text">متوفر: <?php echo $stock_quantity; ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="alam-product-cart">
                                <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()): ?>
                                    <button class="alam-flash-add-cart" data-product-id="<?php echo $product->get_id(); ?>">
                                        <span>إضافة سريعة</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24">
                                            <path d="M19,7H15.5V6.5A3.5,3.5 0 0,0 12,3A3.5,3.5 0 0,0 8.5,6.5V7H5A1,1 0 0,0 4,8V19A3,3 0 0,0 7,22H17A3,3 0 0,0 20,19V8A1,1 0 0,0 19,7Z"/>
                                        </svg>
                                    </button>
                                <?php else: ?>
                                    <a href="<?php echo $product->get_permalink(); ?>" class="alam-view-product">
                                        عرض المنتج
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="alam-load-more-section">
                <button class="alam-load-more-flash" data-page="1">عرض المزيد من الصفقات</button>
            </div>
        </div>
    </section>
    
    <!-- Flash Sale Stats -->
    <section class="alam-flash-stats">
        <div class="container">
            <div class="alam-stats-grid">
                <div class="alam-stat-item">
                    <div class="alam-stat-icon">🔥</div>
                    <div class="alam-stat-number" data-count="1250">0</div>
                    <div class="alam-stat-label">منتج في العرض</div>
                </div>
                
                <div class="alam-stat-item">
                    <div class="alam-stat-icon">⚡</div>
                    <div class="alam-stat-number" data-count="5840">0</div>
                    <div class="alam-stat-label">عملية شراء اليوم</div>
                </div>
                
                <div class="alam-stat-item">
                    <div class="alam-stat-icon">💰</div>
                    <div class="alam-stat-number" data-count="80">0</div>
                    <div class="alam-stat-label">% متوسط الخصم</div>
                </div>
                
                <div class="alam-stat-item">
                    <div class="alam-stat-icon">⏰</div>
                    <div class="alam-stat-number" data-count="24">0</div>
                    <div class="alam-stat-label">ساعة متبقية</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Flash Sale Tips -->
    <section class="alam-flash-tips">
        <div class="container">
            <h2>نصائح للاستفادة من عروض البرق</h2>
            <div class="alam-tips-grid">
                <div class="alam-tip-card">
                    <div class="alam-tip-icon">⚡</div>
                    <h3>كن سريعاً</h3>
                    <p>عروض البرق تنتهي بسرعة، احجز منتجك فوراً</p>
                </div>
                
                <div class="alam-tip-card">
                    <div class="alam-tip-icon">🔔</div>
                    <h3>فعل الإشعارات</h3>
                    <p>احصل على تنبيهات فورية عند بدء العروض الجديدة</p>
                </div>
                
                <div class="alam-tip-card">
                    <div class="alam-tip-icon">💡</div>
                    <h3>راجع الأسعار</h3>
                    <p>قارن الأسعار مع المنافسين للتأكد من أفضل صفقة</p>
                </div>
                
                <div class="alam-tip-card">
                    <div class="alam-tip-icon">🎯</div>
                    <h3>حدد أولوياتك</h3>
                    <p>ضع قائمة بالمنتجات المرغوبة قبل بدء العرض</p>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Flash Sale Notification -->
<div id="alam-flash-notification" class="alam-flash-notification" style="display: none;">
    <div class="alam-notification-content">
        <div class="alam-notification-icon">🔥</div>
        <div class="alam-notification-text">
            <strong>عرض برق جديد!</strong>
            <span>خصم 70% على منتج مميز</span>
        </div>
        <button class="alam-notification-close">&times;</button>
    </div>
</div>

<?php get_footer(); ?>