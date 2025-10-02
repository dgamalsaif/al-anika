<?php
/**
 * Enhanced Flash Sale Section
 * Time-limited deals with urgency indicators
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get flash sale products (products with specific meta or recent sales)
$flash_sale_products = wc_get_products(array(
    'status' => 'publish',
    'limit' => 8,
    'meta_query' => array(
        array(
            'key' => '_flash_sale',
            'value' => 'yes',
            'compare' => '='
        )
    ),
    'orderby' => 'rand'
));

// If no flash sale products, get random sale products
if ( empty( $flash_sale_products ) ) {
    $flash_sale_products = wc_get_products(array(
        'status' => 'publish',
        'limit' => 8,
        'on_sale' => true,
        'orderby' => 'rand'
    ));
}

if ( empty( $flash_sale_products ) ) {
    return;
}
?>

<section class="flash-sale-section enhanced-section" id="flash-sale">
    <div class="container">
        <!-- Section Header with Flash Timer -->
        <div class="flash-header">
            <div class="flash-title-container">
                <h2 class="section-title flash-title">
                    <i class="fas fa-bolt flash-icon"></i>
                    <span class="title-text">عروض خاطفة</span>
                    <span class="title-flash">⚡ محدودة الوقت</span>
                </h2>
                <p class="flash-subtitle">
                    عروض استثنائية لفترة محدودة - لا تفوت الفرصة!
                </p>
            </div>
            
            <!-- Flash Timer -->
            <div class="flash-timer-main">
                <div class="timer-header">
                    <h3><i class="fas fa-clock"></i> ينتهي خلال:</h3>
                </div>
                <div class="flash-countdown" data-end-time="6">
                    <div class="time-block">
                        <span class="time-number" id="flash-hours">06</span>
                        <span class="time-label">ساعات</span>
                    </div>
                    <div class="time-separator">:</div>
                    <div class="time-block">
                        <span class="time-number" id="flash-minutes">00</span>
                        <span class="time-label">دقائق</span>
                    </div>
                    <div class="time-separator">:</div>
                    <div class="time-block">
                        <span class="time-number" id="flash-seconds">00</span>
                        <span class="time-label">ثواني</span>
                    </div>
                </div>
                <div class="timer-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" id="timer-progress"></div>
                    </div>
                    <span class="progress-text">الوقت ينفد!</span>
                </div>
            </div>
        </div>

        <!-- Flash Products Carousel -->
        <div class="flash-products-container">
            <div class="flash-carousel-controls">
                <button class="carousel-btn prev-btn" id="flash-prev">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <button class="carousel-btn next-btn" id="flash-next">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            
            <div class="flash-products-carousel" id="flash-carousel">
                <?php foreach ( $flash_sale_products as $index => $product ) : 
                    $sale_price = $product->get_sale_price();
                    $regular_price = $product->get_regular_price();
                    $discount_percentage = 0;
                    
                    if ( $regular_price && $sale_price ) {
                        $discount_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                    }
                    
                    // Simulate stock urgency
                    $remaining_stock = rand(3, 15);
                ?>
                    <div class="flash-product-card <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                        <div class="flash-card-header">
                            <div class="flash-badges">
                                <span class="flash-badge urgent">
                                    <i class="fas fa-fire"></i>
                                    عاجل
                                </span>
                                <?php if ( $discount_percentage > 0 ) : ?>
                                    <span class="flash-badge discount">-<?php echo $discount_percentage; ?>%</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="stock-urgency">
                                <span class="stock-text">متبقي فقط <?php echo $remaining_stock; ?> قطع!</span>
                                <div class="urgency-bar">
                                    <div class="urgency-fill" style="width: <?php echo min(100, ($remaining_stock / 20) * 100); ?>%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flash-product-image">
                            <div class="image-container">
                                <?php echo $product->get_image( 'product-grid' ); ?>
                                <div class="flash-overlay">
                                    <button class="flash-quick-view" data-product-id="<?php echo $product->get_id(); ?>">
                                        <i class="fas fa-search-plus"></i>
                                        نظرة سريعة
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flash-product-info">
                            <div class="product-category-flash">
                                <?php echo wp_strip_all_tags( wc_get_product_category_list( $product->get_id() ) ); ?>
                            </div>
                            
                            <h3 class="flash-product-title">
                                <a href="<?php echo get_permalink( $product->get_id() ); ?>">
                                    <?php echo $product->get_name(); ?>
                                </a>
                            </h3>
                            
                            <div class="flash-rating">
                                <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                                <span class="rating-text">(<?php echo $product->get_review_count(); ?> تقييم)</span>
                            </div>
                            
                            <div class="flash-price-container">
                                <?php if ( $sale_price ) : ?>
                                    <div class="price-main">
                                        <span class="flash-price-current"><?php echo wc_price( $sale_price ); ?></span>
                                        <span class="flash-price-original"><?php echo wc_price( $regular_price ); ?></span>
                                    </div>
                                    <div class="savings-amount">
                                        وفر <?php echo wc_price( $regular_price - $sale_price ); ?>
                                    </div>
                                <?php else : ?>
                                    <span class="flash-price-current"><?php echo $product->get_price_html(); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Individual Product Timer -->
                            <div class="product-timer" data-product-end="<?php echo date('Y-m-d H:i:s', strtotime('+' . rand(2, 8) . ' hours')); ?>">
                                <div class="mini-timer">
                                    <span class="mini-hours">00</span>:
                                    <span class="mini-minutes">00</span>:
                                    <span class="mini-seconds">00</span>
                                </div>
                                <div class="timer-label">ينتهي العرض</div>
                            </div>
                            
                            <div class="flash-actions">
                                <button class="btn-flash-cart" data-product-id="<?php echo $product->get_id(); ?>">
                                    <i class="fas fa-shopping-cart"></i>
                                    أضف للسلة
                                </button>
                                
                                <button class="btn-flash-buy" data-product-id="<?php echo $product->get_id(); ?>">
                                    <i class="fas fa-bolt"></i>
                                    اشترِ فوراً
                                </button>
                                
                                <button class="btn-flash-heart" data-product-id="<?php echo $product->get_id(); ?>">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Urgency Indicator -->
                        <div class="urgency-indicator">
                            <div class="urgency-pulse"></div>
                            <span class="urgency-text">🔥 يشتريه الآن <?php echo rand(8, 25); ?> شخص</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                <?php for ( $i = 0; $i < count( $flash_sale_products ); $i++ ) : ?>
                    <button class="indicator <?php echo $i === 0 ? 'active' : ''; ?>" 
                            data-slide="<?php echo $i; ?>"></button>
                <?php endfor; ?>
            </div>
        </div>
        
        <!-- Flash Sale Footer -->
        <div class="flash-footer">
            <div class="flash-stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo count( $flash_sale_products ); ?></span>
                    <span class="stat-label">منتجات في العرض</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo rand(150, 300); ?></span>
                    <span class="stat-label">تم البيع اليوم</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo rand(45, 75); ?>%</span>
                    <span class="stat-label">خصم أقصى</span>
                </div>
            </div>
            
            <a href="<?php echo esc_url( add_query_arg( 'flash_sale', '1', wc_get_page_permalink( 'shop' ) ) ); ?>" 
               class="btn btn-primary btn-flash-all">
                <i class="fas fa-bolt"></i>
                عرض جميع العروض الخاطفة
                <span class="btn-urgency">قبل انتهاء الوقت!</span>
            </a>
        </div>
    </div>
</section>

<script>
// Flash Sale Main Timer
document.addEventListener('DOMContentLoaded', function() {
    const flashTimer = document.querySelector('.flash-countdown');
    if (!flashTimer) return;
    
    const endHours = parseInt(flashTimer.dataset.endTime);
    let timeLeft = endHours * 3600; // Convert to seconds
    
    function updateFlashTimer() {
        if (timeLeft <= 0) {
            flashTimer.innerHTML = '<div class="timer-expired">انتهى العرض!</div>';
            return;
        }
        
        const hours = Math.floor(timeLeft / 3600);
        const minutes = Math.floor((timeLeft % 3600) / 60);
        const seconds = timeLeft % 60;
        
        document.getElementById('flash-hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('flash-minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('flash-seconds').textContent = String(seconds).padStart(2, '0');
        
        // Update progress bar
        const progressPercent = ((endHours * 3600 - timeLeft) / (endHours * 3600)) * 100;
        document.getElementById('timer-progress').style.width = progressPercent + '%';
        
        timeLeft--;
    }
    
    updateFlashTimer();
    setInterval(updateFlashTimer, 1000);
});

// Flash Carousel
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('flash-carousel');
    const cards = document.querySelectorAll('.flash-product-card');
    const indicators = document.querySelectorAll('.indicator');
    const prevBtn = document.getElementById('flash-prev');
    const nextBtn = document.getElementById('flash-next');
    
    let currentIndex = 0;
    const totalCards = cards.length;
    
    function updateCarousel() {
        cards.forEach((card, index) => {
            card.classList.toggle('active', index === currentIndex);
        });
        
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentIndex);
        });
    }
    
    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalCards;
        updateCarousel();
    }
    
    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalCards) % totalCards;
        updateCarousel();
    }
    
    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);
    
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentIndex = index;
            updateCarousel();
        });
    });
    
    // Auto-advance carousel
    setInterval(nextSlide, 5000);
});

// Individual Product Timers
document.addEventListener('DOMContentLoaded', function() {
    const productTimers = document.querySelectorAll('.product-timer');
    
    productTimers.forEach(timer => {
        const endTime = new Date(timer.dataset.productEnd).getTime();
        
        function updateProductTimer() {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                timer.innerHTML = '<div class="timer-expired">انتهى!</div>';
                return;
            }
            
            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            timer.querySelector('.mini-hours').textContent = String(hours).padStart(2, '0');
            timer.querySelector('.mini-minutes').textContent = String(minutes).padStart(2, '0');
            timer.querySelector('.mini-seconds').textContent = String(seconds).padStart(2, '0');
        }
        
        updateProductTimer();
        setInterval(updateProductTimer, 1000);
    });
});
</script>