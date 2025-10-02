<?php
/**
 * Revolutionary Hero Section - 3-Column Layout with Advanced Animations
 * Phase 2 Enhancement
 *
 * @package AlamAlAnika
 */

// Get customizer values with defaults
$hero_layout_style = get_theme_mod('revolutionary_hero_layout_style', 'modern');
$hero_animation_speed = get_theme_mod('revolutionary_hero_animation_speed', 'medium');
$hero_enable_particles = get_theme_mod('revolutionary_hero_enable_particles', true);
$hero_overlay_opacity = get_theme_mod('revolutionary_hero_overlay_opacity', 0.4);

// Left Column Content
$left_title = get_theme_mod('revolutionary_hero_left_title', __('اكتشف مجموعتنا الجديدة', 'alam-al-anika'));
$left_subtitle = get_theme_mod('revolutionary_hero_left_subtitle', __('أحدث الصيحات بأفضل الأسعار', 'alam-al-anika'));
$left_description = get_theme_mod('revolutionary_hero_left_description', __('تسوق من مجموعة متنوعة من المنتجات عالية الجودة مع خصومات تصل إلى 70%', 'alam-al-anika'));
$left_button_text = get_theme_mod('revolutionary_hero_left_button_text', __('تسوق الآن', 'alam-al-anika'));
$left_button_url = get_theme_mod('revolutionary_hero_left_button_url', wc_get_page_permalink('shop'));
$left_bg_image = get_theme_mod('revolutionary_hero_left_bg_image', get_template_directory_uri() . '/assets/images/hero-bg.jpg');

// Center Column Content (Featured Product)
$center_featured_product_id = get_theme_mod('revolutionary_hero_featured_product_id', '');
$center_title = get_theme_mod('revolutionary_hero_center_title', __('المنتج المميز', 'alam-al-anika'));
$center_badge_text = get_theme_mod('revolutionary_hero_center_badge_text', __('جديد', 'alam-al-anika'));
$center_bg_image = get_theme_mod('revolutionary_hero_center_bg_image', get_template_directory_uri() . '/assets/images/placeholder.jpg');

// Right Column Content (Promotional)
$right_title = get_theme_mod('revolutionary_hero_right_title', __('عروض خاصة', 'alam-al-anika'));
$right_subtitle = get_theme_mod('revolutionary_hero_right_subtitle', __('لفترة محدودة', 'alam-al-anika'));
$right_offer_text = get_theme_mod('revolutionary_hero_right_offer_text', __('خصم 50%', 'alam-al-anika'));
$right_button_text = get_theme_mod('revolutionary_hero_right_button_text', __('احصل على العرض', 'alam-al-anika'));
$right_button_url = get_theme_mod('revolutionary_hero_right_button_url', wc_get_page_permalink('shop'));
$right_bg_image = get_theme_mod('revolutionary_hero_right_bg_image', get_template_directory_uri() . '/assets/images/hero-bg.jpg');

// Get featured product if set
$featured_product = null;
if (!empty($center_featured_product_id) && class_exists('WooCommerce')) {
    $featured_product = wc_get_product($center_featured_product_id);
}
?>

<section class="revolutionary-hero" data-animation-speed="<?php echo esc_attr($hero_animation_speed); ?>" data-layout="<?php echo esc_attr($hero_layout_style); ?>">
    
    <?php if ($hero_enable_particles): ?>
    <div class="hero-particles-container">
        <canvas id="hero-particles-canvas"></canvas>
    </div>
    <?php endif; ?>
    
    <div class="hero-overlay" style="opacity: <?php echo esc_attr($hero_overlay_opacity); ?>"></div>
    
    <div class="revolutionary-hero-container">
        
        <!-- Left Column: Main Content -->
        <div class="hero-column hero-left" data-aos="fade-right" data-aos-duration="1000">
            <div class="hero-column-bg" style="background-image: url('<?php echo esc_url($left_bg_image); ?>')"></div>
            <div class="hero-column-content">
                <div class="hero-content-wrapper">
                    <?php if (!empty($left_subtitle)): ?>
                    <div class="hero-subtitle animate-slide-up" data-delay="200">
                        <?php echo esc_html($left_subtitle); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($left_title)): ?>
                    <h1 class="hero-title animate-slide-up" data-delay="400">
                        <?php echo esc_html($left_title); ?>
                    </h1>
                    <?php endif; ?>
                    
                    <?php if (!empty($left_description)): ?>
                    <p class="hero-description animate-slide-up" data-delay="600">
                        <?php echo esc_html($left_description); ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($left_button_text) && !empty($left_button_url)): ?>
                    <div class="hero-button-container animate-slide-up" data-delay="800">
                        <a href="<?php echo esc_url($left_button_url); ?>" class="hero-btn hero-btn-primary">
                            <span class="btn-text"><?php echo esc_html($left_button_text); ?></span>
                            <span class="btn-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Decorative Elements -->
                <div class="hero-decorative-elements">
                    <div class="floating-shape shape-1"></div>
                    <div class="floating-shape shape-2"></div>
                    <div class="floating-shape shape-3"></div>
                </div>
            </div>
        </div>
        
        <!-- Center Column: Featured Product -->
        <div class="hero-column hero-center" data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="200">
            <div class="hero-column-bg" style="background-image: url('<?php echo esc_url($center_bg_image); ?>')"></div>
            <div class="hero-column-content">
                
                <?php if (!empty($center_badge_text)): ?>
                <div class="hero-badge animate-bounce-in" data-delay="600">
                    <?php echo esc_html($center_badge_text); ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($center_title)): ?>
                <h2 class="hero-center-title animate-slide-up" data-delay="400">
                    <?php echo esc_html($center_title); ?>
                </h2>
                <?php endif; ?>
                
                <?php if ($featured_product && !is_wp_error($featured_product)): ?>
                <div class="featured-product-showcase animate-scale-in" data-delay="800">
                    <div class="product-image-container">
                        <?php echo wp_kses_post($featured_product->get_image('medium')); ?>
                        <div class="product-hover-overlay">
                            <a href="<?php echo esc_url($featured_product->get_permalink()); ?>" class="quick-view-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 12S5 4 12 4S23 12 23 12S19 20 12 20S1 12 1 12Z" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="product-details">
                        <h3 class="product-title">
                            <a href="<?php echo esc_url($featured_product->get_permalink()); ?>">
                                <?php echo esc_html($featured_product->get_name()); ?>
                            </a>
                        </h3>
                        <div class="product-price">
                            <?php echo wp_kses_post($featured_product->get_price_html()); ?>
                        </div>
                        <div class="product-rating">
                            <?php echo wp_kses_post(wc_get_rating_html($featured_product->get_average_rating())); ?>
                        </div>
                    </div>
                </div>
                
                <?php else: ?>
                <!-- Fallback content when no product is selected -->
                <div class="featured-content-showcase animate-scale-in" data-delay="800">
                    <div class="showcase-icon">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L3.09 8.26L12 14L20.91 8.26L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3.09 8.26L12 14.52L20.91 8.26" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3.09 15.74L12 22L20.91 15.74" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <p class="showcase-text"><?php esc_html_e('اختر منتجاً مميزاً من إعدادات التخصيص', 'alam-al-anika'); ?></p>
                </div>
                <?php endif; ?>
                
                <!-- Center Column Decorative Elements -->
                <div class="center-glow-effect"></div>
                <div class="rotating-border"></div>
            </div>
        </div>
        
        <!-- Right Column: Promotional Content -->
        <div class="hero-column hero-right" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="100">
            <div class="hero-column-bg" style="background-image: url('<?php echo esc_url($right_bg_image); ?>')"></div>
            <div class="hero-column-content">
                <div class="promo-content-wrapper">
                    
                    <?php if (!empty($right_offer_text)): ?>
                    <div class="promo-offer animate-pulse-glow" data-delay="300">
                        <span class="offer-text"><?php echo esc_html($right_offer_text); ?></span>
                        <div class="offer-sparkle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L14.09 8.26L22 9L14.09 15.74L12 22L9.91 15.74L2 9L9.91 8.26L12 2Z" fill="currentColor"/>
                            </svg>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($right_title)): ?>
                    <h2 class="promo-title animate-slide-up" data-delay="500">
                        <?php echo esc_html($right_title); ?>
                    </h2>
                    <?php endif; ?>
                    
                    <?php if (!empty($right_subtitle)): ?>
                    <p class="promo-subtitle animate-slide-up" data-delay="700">
                        <?php echo esc_html($right_subtitle); ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($right_button_text) && !empty($right_button_url)): ?>
                    <div class="promo-button-container animate-slide-up" data-delay="900">
                        <a href="<?php echo esc_url($right_button_url); ?>" class="hero-btn hero-btn-secondary">
                            <span class="btn-text"><?php echo esc_html($right_button_text); ?></span>
                            <span class="btn-arrow">→</span>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Countdown Timer (if promotional) -->
                    <div class="promo-timer animate-fade-in" data-delay="1000">
                        <div class="timer-label"><?php esc_html_e('ينتهي العرض خلال', 'alam-al-anika'); ?></div>
                        <div class="timer-display" data-countdown="<?php echo esc_attr(date('Y-m-d', strtotime('+7 days'))); ?>">
                            <div class="timer-segment">
                                <span class="timer-number days">07</span>
                                <span class="timer-unit"><?php esc_html_e('أيام', 'alam-al-anika'); ?></span>
                            </div>
                            <div class="timer-segment">
                                <span class="timer-number hours">12</span>
                                <span class="timer-unit"><?php esc_html_e('ساعات', 'alam-al-anika'); ?></span>
                            </div>
                            <div class="timer-segment">
                                <span class="timer-number minutes">30</span>
                                <span class="timer-unit"><?php esc_html_e('دقائق', 'alam-al-anika'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column Decorative Elements -->
                <div class="promo-decorative-elements">
                    <div class="promo-shape shape-1"></div>
                    <div class="promo-shape shape-2"></div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Hero Navigation -->
    <div class="hero-navigation">
        <button class="hero-nav-btn hero-nav-prev" aria-label="<?php esc_attr_e('السابق', 'alam-al-anika'); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <button class="hero-nav-btn hero-nav-next" aria-label="<?php esc_attr_e('التالي', 'alam-al-anika'); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
    
    <!-- Hero Indicators -->
    <div class="hero-indicators">
        <div class="indicator active" data-slide="0"></div>
        <div class="indicator" data-slide="1"></div>
        <div class="indicator" data-slide="2"></div>
    </div>
    
</section>
