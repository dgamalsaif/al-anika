<?php
/**
 * Enhanced Super Sale Section
 * Advanced product display with countdown and filters
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get sale products
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
        )
    ),
    'orderby' => 'date',
    'order' => 'DESC'
));

if ( empty( $sale_products ) ) {
    return;
}
?>

<section class="super-sale-section enhanced-section" id="super-sale">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header">
            <div class="header-content">
                <h2 class="section-title">
                    <i class="fas fa-fire"></i>
                    عروض خارقة
                    <span class="title-accent">وفر حتى 70%</span>
                </h2>
                <p class="section-description">
                    اكتشف أفضل العروض والخصومات الحصرية على منتجات مختارة بعناية
                </p>
            </div>
            
            <!-- Sale Timer -->
            <div class="sale-timer">
                <div class="timer-container">
                    <h3>ينتهي العرض خلال:</h3>
                    <div class="countdown-timer" data-end-date="2025-12-31">
                        <div class="time-unit">
                            <span class="time-value" id="days">00</span>
                            <span class="time-label">يوم</span>
                        </div>
                        <div class="time-unit">
                            <span class="time-value" id="hours">00</span>
                            <span class="time-label">ساعة</span>
                        </div>
                        <div class="time-unit">
                            <span class="time-value" id="minutes">00</span>
                            <span class="time-label">دقيقة</span>
                        </div>
                        <div class="time-unit">
                            <span class="time-value" id="seconds">00</span>
                            <span class="time-label">ثانية</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Filters -->
        <div class="sale-filters">
            <div class="filter-buttons">
                <button class="filter-btn active" data-category="all">
                    <i class="fas fa-th"></i>
                    جميع المنتجات
                </button>
                <button class="filter-btn" data-category="electronics">
                    <i class="fas fa-laptop"></i>
                    إلكترونيات
                </button>
                <button class="filter-btn" data-category="fashion">
                    <i class="fas fa-tshirt"></i>
                    موضة
                </button>
                <button class="filter-btn" data-category="home">
                    <i class="fas fa-home"></i>
                    منزل
                </button>
                <button class="filter-btn" data-category="sports">
                    <i class="fas fa-dumbbell"></i>
                    رياضة
                </button>
            </div>
            
            <div class="view-options">
                <button class="view-btn active" data-view="grid">
                    <i class="fas fa-th"></i>
                </button>
                <button class="view-btn" data-view="list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-container">
            <div class="products-grid" id="super-sale-products">
                <?php foreach ( $sale_products as $product ) : 
                    $sale_price = $product->get_sale_price();
                    $regular_price = $product->get_regular_price();
                    $discount_percentage = 0;
                    
                    if ( $regular_price && $sale_price ) {
                        $discount_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                    }
                ?>
                    <div class="product-card enhanced-card" data-category="<?php echo esc_attr( $product->get_category_ids()[0] ?? 'general' ); ?>">
                        <div class="product-image-container">
                            <div class="product-badges">
                                <?php if ( $discount_percentage > 0 ) : ?>
                                    <span class="badge badge-discount">-<?php echo $discount_percentage; ?>%</span>
                                <?php endif; ?>
                                
                                <?php if ( $product->is_featured() ) : ?>
                                    <span class="badge badge-featured">
                                        <i class="fas fa-star"></i>
                                        مميز
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ( ! $product->is_in_stock() ) : ?>
                                    <span class="badge badge-out-of-stock">نفد المخزون</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-image">
                                <?php echo $product->get_image( 'product-grid' ); ?>
                            </div>
                            
                            <div class="product-overlay">
                                <div class="product-actions">
                                    <button class="action-btn quick-view" data-product-id="<?php echo $product->get_id(); ?>">
                                        <i class="fas fa-eye"></i>
                                        نظرة سريعة
                                    </button>
                                    
                                    <button class="action-btn add-to-wishlist" data-product-id="<?php echo $product->get_id(); ?>">
                                        <i class="far fa-heart"></i>
                                        المفضلة
                                    </button>
                                    
                                    <button class="action-btn add-to-compare" data-product-id="<?php echo $product->get_id(); ?>">
                                        <i class="fas fa-balance-scale"></i>
                                        مقارنة
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-info">
                            <div class="product-category">
                                <?php echo wp_strip_all_tags( wc_get_product_category_list( $product->get_id() ) ); ?>
                            </div>
                            
                            <h3 class="product-title">
                                <a href="<?php echo get_permalink( $product->get_id() ); ?>">
                                    <?php echo $product->get_name(); ?>
                                </a>
                            </h3>
                            
                            <div class="product-rating">
                                <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                                <span class="rating-count">(<?php echo $product->get_review_count(); ?>)</span>
                            </div>
                            
                            <div class="product-price">
                                <?php if ( $sale_price ) : ?>
                                    <span class="price-current"><?php echo wc_price( $sale_price ); ?></span>
                                    <span class="price-original"><?php echo wc_price( $regular_price ); ?></span>
                                <?php else : ?>
                                    <span class="price-current"><?php echo $product->get_price_html(); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-stock-progress">
                                <?php 
                                $stock_quantity = $product->get_stock_quantity();
                                $sold_count = get_post_meta( $product->get_id(), 'total_sales', true ) ?: 0;
                                $total_stock = $stock_quantity + $sold_count;
                                $sold_percentage = $total_stock > 0 ? ( $sold_count / $total_stock ) * 100 : 0;
                                ?>
                                <div class="stock-info">
                                    <span>تم بيع <?php echo $sold_count; ?> قطعة</span>
                                    <span><?php echo $stock_quantity; ?> متبقي</span>
                                </div>
                                <div class="stock-bar">
                                    <div class="stock-fill" style="width: <?php echo $sold_percentage; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="product-actions-bottom">
                                <button class="btn-add-to-cart" data-product-id="<?php echo $product->get_id(); ?>">
                                    <i class="fas fa-shopping-cart"></i>
                                    أضف للسلة
                                </button>
                                
                                <button class="btn-buy-now" data-product-id="<?php echo $product->get_id(); ?>">
                                    <i class="fas fa-bolt"></i>
                                    اشترِ الآن
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- View More Button -->
        <div class="section-footer">
            <a href="<?php echo esc_url( add_query_arg( 'on_sale', '1', wc_get_page_permalink( 'shop' ) ) ); ?>" 
               class="btn btn-primary btn-large view-all-btn">
                <i class="fas fa-arrow-right"></i>
                عرض جميع المنتجات المخفضة
                <span class="btn-count">(<?php echo count( $sale_products ); ?>+ منتج)</span>
            </a>
        </div>
    </div>
</section>

<script>
// Countdown Timer
document.addEventListener('DOMContentLoaded', function() {
    const timer = document.querySelector('.countdown-timer');
    if (!timer) return;
    
    const endDate = new Date(timer.dataset.endDate).getTime();
    
    function updateTimer() {
        const now = new Date().getTime();
        const distance = endDate - now;
        
        if (distance < 0) {
            timer.innerHTML = "<div class='timer-expired'>انتهى العرض!</div>";
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById('days').textContent = String(days).padStart(2, '0');
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }
    
    updateTimer();
    setInterval(updateTimer, 1000);
});

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            
            // Filter products
            productCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.5s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>