<?php
/**
 * Template Name: Super Sale Page
 * Advanced sale page with countdown timers and special offers
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
                        <span class="alam-countdown-number" data-days>00</span>
                        <span class="alam-countdown-label">أيام</span>
                    </div>
                    <div class="alam-countdown-item">
                        <span class="alam-countdown-number" data-hours>00</span>
                        <span class="alam-countdown-label">ساعات</span>
                    </div>
                    <div class="alam-countdown-item">
                        <span class="alam-countdown-number" data-minutes>00</span>
                        <span class="alam-countdown-label">دقائق</span>
                    </div>
                    <div class="alam-countdown-item">
                        <span class="alam-countdown-number" data-seconds>00</span>
                        <span class="alam-countdown-label">ثواني</span>
                    </div>
                </div>
                
                <div class="alam-sale-cta">
                    <a href="#sale-products" class="alam-cta-button">تسوق الآن</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Sale Categories -->
    <section class="alam-sale-categories">
        <div class="container">
            <h2 class="section-title">تصفح حسب الفئة</h2>
            
            <div class="alam-sale-categories-grid">
                <?php
                $sale_categories = get_theme_mod('super_sale_categories', array());
                if (empty($sale_categories)) {
                    // Default categories if none set
                    $sale_categories = array(
                        array('name' => 'إلكترونيات', 'discount' => '50%', 'image' => ''),
                        array('name' => 'ملابس', 'discount' => '40%', 'image' => ''),
                        array('name' => 'منزل وحديقة', 'discount' => '30%', 'image' => ''),
                        array('name' => 'رياضة', 'discount' => '35%', 'image' => '')
                    );
                }
                
                foreach ($sale_categories as $index => $category):
                ?>
                    <div class="alam-sale-category-card">
                        <div class="alam-category-image">
                            <?php if (!empty($category['image'])): ?>
                                <img src="<?php echo esc_url($category['image']); ?>" alt="<?php echo esc_attr($category['name']); ?>">
                            <?php else: ?>
                                <div class="alam-category-placeholder">
                                    <svg width="60" height="60" viewBox="0 0 24 24">
                                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <div class="alam-category-overlay">
                                <div class="alam-discount-badge">
                                    خصم <?php echo esc_html($category['discount']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="alam-category-content">
                            <h3><?php echo esc_html($category['name']); ?></h3>
                            <a href="<?php echo esc_url($category['link'] ?? '#'); ?>" class="alam-category-button">تسوق الآن</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Featured Sale Products -->
    <section id="sale-products" class="alam-featured-sale-products">
        <div class="container">
            <div class="alam-section-header">
                <h2 class="section-title">عروض مميزة</h2>
                <div class="alam-sale-filters">
                    <button class="alam-filter-btn active" data-filter="all">الكل</button>
                    <button class="alam-filter-btn" data-filter="electronics">إلكترونيات</button>
                    <button class="alam-filter-btn" data-filter="clothing">ملابس</button>
                    <button class="alam-filter-btn" data-filter="home">منزل</button>
                    <button class="alam-filter-btn" data-filter="sports">رياضة</button>
                </div>
            </div>
            
            <div class="alam-sale-products-grid">
                <?php
                // Get sale products
                $sale_products = wc_get_products(array(
                    'limit' => 12,
                    'meta_query' => array(
                        array(
                            'key' => '_sale_price',
                            'value' => '',
                            'compare' => '!='
                        )
                    ),
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));
                
                foreach ($sale_products as $product):
                    $regular_price = (float) $product->get_regular_price();
                    $sale_price = (float) $product->get_sale_price();
                    $discount_percentage = $regular_price > 0 ? round((($regular_price - $sale_price) / $regular_price) * 100) : 0;
                ?>
                    <div class="alam-sale-product-card" data-category="<?php echo esc_attr(strtolower($product->get_category_ids()[0] ?? 'all')); ?>">
                        <div class="alam-product-image">
                            <a href="<?php echo $product->get_permalink(); ?>">
                                <?php echo $product->get_image('medium'); ?>
                            </a>
                            
                            <?php if ($discount_percentage > 0): ?>
                                <div class="alam-discount-badge">
                                    -<?php echo $discount_percentage; ?>%
                                </div>
                            <?php endif; ?>
                            
                            <div class="alam-product-actions">
                                <button class="alam-quick-view" data-product-id="<?php echo $product->get_id(); ?>" title="نظرة سريعة">
                                    <svg width="20" height="20" viewBox="0 0 24 24">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                                    </svg>
                                </button>
                                
                                <?php echo do_shortcode('[alam_compare_button product_id="' . $product->get_id() . '" style="mini"]'); ?>
                                
                                <?php if (defined('YITH_WCWL')): ?>
                                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist product_id="' . $product->get_id() . '"]'); ?>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($product->is_on_sale()): ?>
                                <div class="alam-sale-timer" data-end-date="<?php echo date('Y-m-d H:i:s', strtotime('+3 days')); ?>">
                                    <div class="alam-timer-icon">⏰</div>
                                    <div class="alam-timer-text">
                                        <span class="alam-timer-hours">72</span>:<span class="alam-timer-minutes">00</span>:<span class="alam-timer-seconds">00</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="alam-product-content">
                            <h3 class="alam-product-title">
                                <a href="<?php echo $product->get_permalink(); ?>"><?php echo $product->get_name(); ?></a>
                            </h3>
                            
                            <div class="alam-product-rating">
                                <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                                <span class="alam-rating-count">(<?php echo $product->get_review_count(); ?>)</span>
                            </div>
                            
                            <div class="alam-product-price">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                            
                            <div class="alam-product-cart">
                                <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()): ?>
                                    <button class="alam-add-to-cart-btn" data-product-id="<?php echo $product->get_id(); ?>">
                                        <svg width="20" height="20" viewBox="0 0 24 24">
                                            <path d="M19,7H15.5V6.5A3.5,3.5 0 0,0 12,3A3.5,3.5 0 0,0 8.5,6.5V7H5A1,1 0 0,0 4,8V19A3,3 0 0,0 7,22H17A3,3 0 0,0 20,19V8A1,1 0 0,0 19,7Z"/>
                                        </svg>
                                        إضافة للسلة
                                    </button>
                                <?php else: ?>
                                    <a href="<?php echo $product->get_permalink(); ?>" class="alam-view-product-btn">
                                        عرض المنتج
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="alam-load-more-section">
                <button class="alam-load-more-btn" data-page="1">تحميل المزيد</button>
            </div>
        </div>
    </section>
    
    <!-- Special Offers Banner -->
    <section class="alam-special-offers">
        <div class="container">
            <div class="alam-offers-grid">
                <div class="alam-offer-banner large">
                    <div class="alam-offer-content">
                        <h3>عرض خاص</h3>
                        <h2>خصم 60% على الإلكترونيات</h2>
                        <p>لفترة محدودة فقط</p>
                        <a href="#" class="alam-offer-btn">تسوق الآن</a>
                    </div>
                    <div class="alam-offer-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/electronics-offer.jpg" alt="Electronics Offer">
                    </div>
                </div>
                
                <div class="alam-offer-banner small">
                    <div class="alam-offer-content">
                        <h3>شحن مجاني</h3>
                        <p>على الطلبات أكثر من 500 ريال</p>
                        <a href="#" class="alam-offer-btn">اعرف أكثر</a>
                    </div>
                </div>
                
                <div class="alam-offer-banner small">
                    <div class="alam-offer-content">
                        <h3>ضمان الاسترداد</h3>
                        <p>30 يوم ضمان استرداد المال</p>
                        <a href="#" class="alam-offer-btn">التفاصيل</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Signup -->
    <section class="alam-sale-newsletter">
        <div class="container">
            <div class="alam-newsletter-content">
                <h2>لا تفوت العروض القادمة</h2>
                <p>اشترك في النشرة الإخبارية واحصل على إشعارات بأحدث العروض والتخفيضات</p>
                
                <form class="alam-newsletter-form">
                    <div class="alam-form-group">
                        <input type="email" placeholder="أدخل بريدك الإلكتروني" required>
                        <button type="submit">اشتراك</button>
                    </div>
                    <div class="alam-newsletter-benefits">
                        <span>✓ عروض حصرية</span>
                        <span>✓ كوبونات خصم</span>
                        <span>✓ أولوية الوصول للمنتجات الجديدة</span>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Quick View Modal -->
<div id="alam-quick-view-modal" class="alam-modal" style="display: none;">
    <div class="alam-modal-content">
        <button class="alam-modal-close">&times;</button>
        <div class="alam-quick-view-content">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>

<?php get_footer(); ?>