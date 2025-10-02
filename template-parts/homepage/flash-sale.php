<?php
/**
 * Professional Flash Sale Section
 * Enhanced with proper spacing and modern design
 *
 * @package AlamAlAnika
 */

// Get IDs of products on sale
$product_ids_on_sale = wc_get_product_ids_on_sale();

if ( ! empty( $product_ids_on_sale ) ) :
?>
<section class="flash-sale section professional-flash-sale">
    <div class="flash-header">
        <div class="flash-title-container">
            <div class="flash-icon">
                <i class="fas fa-bolt flash-bolt"></i>
            </div>
            <div class="flash-title-text">
                <h2 class="arabic-text"><?php esc_html_e( 'عروض البرق', 'alam-al-anika' ); ?></h2>
                <p class="flash-subtitle arabic-text"><?php esc_html_e( 'خصومات حصرية لفترة محدودة', 'alam-al-anika' ); ?></p>
            </div>
        </div>
        <div class="flash-timer-container">
            <div class="timer-icon">
                <i class="far fa-clock"></i>
            </div>
            <div class="timer-content">
                <span class="timer-label arabic-text"><?php esc_html_e( 'ينتهي خلال', 'alam-al-anika' ); ?></span>
                <div class="countdown-timer" id="flash-countdown">
                    <span class="time-unit">
                        <span class="time-number">05</span>
                        <span class="time-label">ساعات</span>
                    </span>
                    <span class="time-separator">:</span>
                    <span class="time-unit">
                        <span class="time-number">53</span>
                        <span class="time-label">دقائق</span>
                    </span>
                    <span class="time-separator">:</span>
                    <span class="time-unit">
                        <span class="time-number">48</span>
                        <span class="time-label">ثواني</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="flash-products-grid">
        <?php
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 6, // Increased for better display
            'post__in'       => array_slice($product_ids_on_sale, 0, 6), // Limit for performance
            'orderby'        => 'menu_order',
            'order'          => 'ASC'
        );
        $flash_sale_query = new WP_Query( $args );

        if ( $flash_sale_query->have_posts() ) :
            echo '<ul class="products flash-products columns-6">';
            while ( $flash_sale_query->have_posts() ) :
                $flash_sale_query->the_post();
                wc_get_template_part( 'content', 'product' );
            endwhile;
            echo '</ul>';
        else :
            // Professional no products message
            echo '<div class="no-flash-products">';
            echo '<div class="no-products-icon"><i class="fas fa-shopping-bag"></i></div>';
            echo '<h3 class="arabic-text">' . esc_html__( 'لا توجد منتجات في العرض حالياً', 'alam-al-anika' ) . '</h3>';
            echo '<p class="arabic-text">' . esc_html__( 'ترقب العروض القادمة قريباً', 'alam-al-anika' ) . '</p>';
            echo '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="button button-primary">';
            echo '<span class="arabic-text">' . esc_html__( 'تصفح المنتجات', 'alam-al-anika' ) . '</span>';
            echo '</a>';
            echo '</div>';
        endif;
        wp_reset_postdata();
        ?>
    </div>
    
    <!-- Flash Sale Features -->
    <div class="flash-features">
        <div class="feature-item">
            <i class="fas fa-shipping-fast"></i>
            <span class="arabic-text"><?php esc_html_e( 'شحن مجاني فوري', 'alam-al-anika' ); ?></span>
        </div>
        <div class="feature-item">
            <i class="fas fa-shield-alt"></i>
            <span class="arabic-text"><?php esc_html_e( 'ضمان أصالة المنتج', 'alam-al-anika' ); ?></span>
        </div>
        <div class="feature-item">
            <i class="fas fa-undo"></i>
            <span class="arabic-text"><?php esc_html_e( 'إرجاع مجاني لمدة 30 يوم', 'alam-al-anika' ); ?></span>
        </div>
    </div>
</section>

<style>
/* Professional Flash Sale Styles */
.professional-flash-sale {
    background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
    border-radius: var(--border-radius-lg);
    color: white;
    margin: var(--spacing-xxl) 0;
    position: relative;
    overflow: hidden;
}

.professional-flash-sale::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.flash-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-xl);
    flex-wrap: wrap;
    gap: var(--spacing-lg);
    position: relative;
    z-index: 2;
}

.flash-title-container {
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
}

.flash-icon {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s ease-in-out infinite;
}

.flash-bolt {
    font-size: 1.5rem;
    color: #fff200;
}

.flash-title-text h2 {
    margin: 0;
    font-size: 2rem;
    font-weight: 900;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.flash-subtitle {
    margin: 0;
    font-size: var(--font-size-base);
    opacity: 0.9;
}

.flash-timer-container {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    background: rgba(255, 255, 255, 0.15);
    padding: var(--spacing-md) var(--spacing-lg);
    border-radius: var(--border-radius-lg);
    backdrop-filter: blur(10px);
}

.timer-icon {
    font-size: 1.2rem;
}

.timer-content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.timer-label {
    font-size: var(--font-size-sm);
    opacity: 0.9;
}

.countdown-timer {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.time-unit {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: rgba(255, 255, 255, 0.2);
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    min-width: 50px;
}

.time-number {
    font-size: 1.2rem;
    font-weight: 700;
    line-height: 1;
}

.time-label {
    font-size: var(--font-size-xs);
    opacity: 0.8;
}

.time-separator {
    font-size: 1.2rem;
    font-weight: 700;
    animation: blink 1s ease-in-out infinite;
}

.flash-products-grid {
    margin-bottom: var(--spacing-xl);
    position: relative;
    z-index: 2;
}

.flash-products {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-lg);
    list-style: none;
    padding: 0;
}

.no-flash-products {
    text-align: center;
    padding: var(--spacing-xxl);
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    backdrop-filter: blur(10px);
}

.no-products-icon {
    font-size: 3rem;
    margin-bottom: var(--spacing-lg);
    opacity: 0.7;
}

.no-flash-products h3 {
    color: white;
    margin-bottom: var(--spacing-md);
}

.no-flash-products p {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: var(--spacing-lg);
}

.no-flash-products .button {
    background: white;
    color: var(--primary-color);
}

.flash-features {
    display: flex;
    justify-content: center;
    gap: var(--spacing-xl);
    margin-top: var(--spacing-xl);
    padding-top: var(--spacing-xl);
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 2;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-size: var(--font-size-sm);
    font-weight: 500;
}

.feature-item i {
    font-size: 1rem;
    opacity: 0.9;
}

/* Animations */
@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .flash-header {
        flex-direction: column;
        text-align: center;
        gap: var(--spacing-md);
    }
    
    .flash-title-container {
        flex-direction: column;
        gap: var(--spacing-md);
    }
    
    .flash-title-text h2 {
        font-size: 1.5rem;
    }
    
    .flash-timer-container {
        flex-direction: column;
        gap: var(--spacing-sm);
    }
    
    .flash-products {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }
    
    .flash-features {
        flex-direction: column;
        gap: var(--spacing-md);
        text-align: center;
    }
}

@media (max-width: 480px) {
    .countdown-timer {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .time-unit {
        min-width: 40px;
    }
    
    .time-number {
        font-size: 1rem;
    }
}
</style>

<script>
// Professional Countdown Timer
document.addEventListener('DOMContentLoaded', function() {
    const countdownElement = document.getElementById('flash-countdown');
    if (!countdownElement) return;
    
    function updateCountdown() {
        const now = new Date().getTime();
        const endTime = now + (5 * 60 * 60 * 1000) + (53 * 60 * 1000) + (48 * 1000); // 5:53:48 from now
        
        setInterval(function() {
            const currentTime = new Date().getTime();
            const distance = endTime - currentTime;
            
            if (distance > 0) {
                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                countdownElement.querySelector('.time-unit:nth-child(1) .time-number').textContent = hours.toString().padStart(2, '0');
                countdownElement.querySelector('.time-unit:nth-child(3) .time-number').textContent = minutes.toString().padStart(2, '0');
                countdownElement.querySelector('.time-unit:nth-child(5) .time-number').textContent = seconds.toString().padStart(2, '0');
            }
        }, 1000);
    }
    
    updateCountdown();
});
</script>

<?php
endif;
