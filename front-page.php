<?php
/**
 * Al-Anika Front Page Template - v9.2.0 OPTIMIZED
 * Clean, responsive homepage with modern e-commerce design
 * Performance optimized and accessibility enhanced
 *
 * @package Al_Anika_Theme
 * @version 9.2.0
 */

get_header(); ?>

<main id="primary" class="site-main shein-homepage">

    <!-- SHEIN-Style Hero Section (Minimal & Image-Focused) -->
    <section class="shein-hero-section" aria-labelledby="hero-title">
        <div class="hero-container">
            
            <!-- Main Hero Banner -->
            <div class="hero-main-banner">
                <div class="hero-image-wrapper">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/hero-banner.jpg' ); ?>" 
                         alt="<?php esc_attr_e( 'Latest Fashion Collection', 'alam-al-anika' ); ?>" 
                         class="hero-image"
                         loading="eager">
                    
                    <!-- Hero Content Overlay -->
                    <div class="hero-content-overlay">
                        <div class="hero-content">
                            <h1 id="hero-title" class="hero-title">
                                <?php esc_html_e( 'New Arrivals', 'alam-al-anika' ); ?>
                            </h1>
                            <p class="hero-subtitle">
                                <?php esc_html_e( 'Discover the latest trends and express your unique style', 'alam-al-anika' ); ?>
                            </p>
                            <div class="hero-cta">
                                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-hero-primary">
                                    <?php esc_html_e( 'Shop Now', 'alam-al-anika' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- SHEIN-Style Sale Badge -->
                    <div class="hero-sale-badge">
                        <span class="sale-text"><?php esc_html_e( 'Up to', 'alam-al-anika' ); ?></span>
                        <span class="sale-percentage">70%</span>
                        <span class="sale-off"><?php esc_html_e( 'OFF', 'alam-al-anika' ); ?></span>
                    </div>
                </div>
            </div>

            <!-- Secondary Hero Banners (SHEIN Style) -->
            <div class="hero-secondary-banners">
                <div class="secondary-banner">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/banner-women.jpg' ); ?>" 
                         alt="<?php esc_attr_e( 'Women Fashion', 'alam-al-anika' ); ?>" 
                         class="banner-image"
                         loading="lazy">
                    <div class="banner-overlay">
                        <h3 class="banner-title"><?php esc_html_e( 'Women', 'alam-al-anika' ); ?></h3>
                        <span class="banner-cta"><?php esc_html_e( 'Shop Now', 'alam-al-anika' ); ?></span>
                    </div>
                </div>
                <div class="secondary-banner">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/banner-men.jpg' ); ?>" 
                         alt="<?php esc_attr_e( 'Men Fashion', 'alam-al-anika' ); ?>" 
                         class="banner-image"
                         loading="lazy">
                    <div class="banner-overlay">
                        <h3 class="banner-title"><?php esc_html_e( 'Men', 'alam-al-anika' ); ?></h3>
                        <span class="banner-cta"><?php esc_html_e( 'Shop Now', 'alam-al-anika' ); ?></span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SHEIN-Style Category Grid (Image-Based) -->
    <?php get_template_part( 'template-parts/homepage/alam-category-grid' ); ?>

    <!-- SHEIN-Style Flash Sale Section -->
    <section class="shein-flash-sale" aria-labelledby="flash-sale-title">
        <div class="container">
            
            <!-- Flash Sale Header -->
            <div class="flash-sale-header">
                <div class="flash-badge">
                    <i class="fas fa-bolt" aria-hidden="true"></i>
                    <span><?php esc_html_e( 'Flash Sale', 'alam-al-anika' ); ?></span>
                </div>
                <h2 id="flash-sale-title" class="flash-title">
                    <?php esc_html_e( 'Limited Time Offers', 'alam-al-anika' ); ?>
                </h2>
                <div class="flash-timer">
                    <div class="timer-item">
                        <span class="timer-number" data-hours>12</span>
                        <span class="timer-label"><?php esc_html_e( 'Hours', 'alam-al-anika' ); ?></span>
                    </div>
                    <div class="timer-item">
                        <span class="timer-number" data-minutes>34</span>
                        <span class="timer-label"><?php esc_html_e( 'Minutes', 'alam-al-anika' ); ?></span>
                    </div>
                    <div class="timer-item">
                        <span class="timer-number" data-seconds>56</span>
                        <span class="timer-label"><?php esc_html_e( 'Seconds', 'alam-al-anika' ); ?></span>
                    </div>
                </div>
            </div>

            <!-- Flash Sale Products -->
            <?php if ( function_exists( 'wc_get_products' ) ) : ?>
                <?php
                $flash_products = wc_get_products( array(
                    'limit' => 8,
                    'status' => 'publish',
                    'meta_query' => array(
                        array(
                            'key' => '_sale_price',
                            'compare' => 'EXISTS'
                        )
                    ),
                    'orderby' => 'rand'
                ) );
                ?>
                
                <?php if ( ! empty( $flash_products ) ) : ?>
                    <div class="flash-products-grid">
                        <?php foreach ( $flash_products as $product ) : ?>
                            <div class="flash-product-card">
                                <div class="product-image-container">
                                    <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                                        <?php echo $product->get_image( 'al-anika-product-card', array( 'loading' => 'lazy' ) ); ?>
                                    </a>
                                    
                                    <?php if ( $product->is_on_sale() ) : ?>
                                        <div class="product-sale-badge">
                                            <?php
                                            $regular_price = floatval( $product->get_regular_price() );
                                            $sale_price = floatval( $product->get_sale_price() );
                                            if ( $regular_price > 0 && $sale_price > 0 ) {
                                                $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                                                echo '-' . $percentage . '%';
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="product-quick-actions">
                                        <button class="quick-add-cart" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Add to cart', 'alam-al-anika' ); ?>">
                                            <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                                        </button>
                                        <button class="quick-wishlist" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Add to wishlist', 'alam-al-anika' ); ?>">
                                            <i class="fas fa-heart" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="product-info">
                                    <h3 class="product-title">
                                        <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                                            <?php echo esc_html( $product->get_name() ); ?>
                                        </a>
                                    </h3>
                                    <div class="product-price">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </section>

    <!-- SHEIN-Style Hashtag Campaigns -->
    <?php get_template_part( 'template-parts/homepage/alam-hashtag-campaigns' ); ?>

    <!-- SHEIN-Style Popular Products -->
    <section class="shein-popular-products" aria-labelledby="popular-title">
        <div class="container">
            
            <div class="section-header text-center">
                <h2 id="popular-title" class="section-title">
                    <?php esc_html_e( 'Popular Right Now', 'alam-al-anika' ); ?>
                </h2>
                <p class="section-subtitle">
                    <?php esc_html_e( 'Trending items that everyone is talking about', 'alam-al-anika' ); ?>
                </p>
            </div>

            <!-- Popular Product Tabs (SHEIN Style) -->
            <div class="popular-tabs">
                <button class="tab-button active" data-tab="all"><?php esc_html_e( 'All', 'alam-al-anika' ); ?></button>
                <button class="tab-button" data-tab="women"><?php esc_html_e( 'Women', 'alam-al-anika' ); ?></button>
                <button class="tab-button" data-tab="men"><?php esc_html_e( 'Men', 'alam-al-anika' ); ?></button>
                <button class="tab-button" data-tab="accessories"><?php esc_html_e( 'Accessories', 'alam-al-anika' ); ?></button>
            </div>

            <!-- Popular Products Grid -->
            <?php if ( function_exists( 'wc_get_products' ) ) : ?>
                <?php
                $popular_products = wc_get_products( array(
                    'limit' => 12,
                    'status' => 'publish',
                    'orderby' => 'popularity',
                    'order' => 'DESC'
                ) );
                ?>
                
                <?php if ( ! empty( $popular_products ) ) : ?>
                    <div class="popular-products-grid">
                        <?php foreach ( $popular_products as $product ) : ?>
                            <div class="popular-product-card">
                                <div class="product-image-container">
                                    <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                                        <?php echo $product->get_image( 'al-anika-product-card', array( 'loading' => 'lazy' ) ); ?>
                                    </a>
                                    
                                    <!-- SHEIN-style product badges -->
                                    <div class="product-badges">
                                        <?php if ( $product->is_featured() ) : ?>
                                            <span class="badge featured"><?php esc_html_e( 'Hot', 'alam-al-anika' ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( $product->is_on_sale() ) : ?>
                                            <span class="badge sale"><?php esc_html_e( 'Sale', 'alam-al-anika' ); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="product-hover-actions">
                                        <button class="product-action" data-action="cart" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                                            <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                                        </button>
                                        <button class="product-action" data-action="wishlist" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                                            <i class="fas fa-heart" aria-hidden="true"></i>
                                        </button>
                                        <button class="product-action" data-action="quickview" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="product-info">
                                    <h3 class="product-title">
                                        <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                                            <?php echo esc_html( wp_trim_words( $product->get_name(), 8 ) ); ?>
                                        </a>
                                    </h3>
                                    <div class="product-rating">
                                        <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                                    </div>
                                    <div class="product-price">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- View All CTA -->
            <div class="section-footer text-center">
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline btn-lg">
                    <?php esc_html_e( 'View All Products', 'alam-al-anika' ); ?>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

        </div>
    </section>

    <!-- SHEIN-Style Newsletter Section -->
    <section class="shein-newsletter" aria-labelledby="newsletter-title">
        <div class="container">
            <div class="newsletter-content">
                <div class="newsletter-text">
                    <h2 id="newsletter-title" class="newsletter-title">
                        <?php esc_html_e( 'Stay in the Loop', 'alam-al-anika' ); ?>
                    </h2>
                    <p class="newsletter-subtitle">
                        <?php esc_html_e( 'Get the latest trends, exclusive offers, and style inspiration delivered to your inbox', 'alam-al-anika' ); ?>
                    </p>
                </div>
                <div class="newsletter-form">
                    <form class="newsletter-signup" method="post" action="#" aria-labelledby="newsletter-title">
                        <div class="form-group">
                            <label for="newsletter-email" class="sr-only"><?php esc_html_e( 'Email address', 'alam-al-anika' ); ?></label>
                            <input type="email" 
                                   id="newsletter-email" 
                                   name="email" 
                                   class="newsletter-input" 
                                   placeholder="<?php esc_attr_e( 'Enter your email address', 'alam-al-anika' ); ?>" 
                                   required>
                            <button type="submit" class="newsletter-btn">
                                <?php esc_html_e( 'Subscribe', 'alam-al-anika' ); ?>
                            </button>
                        </div>
                        <p class="newsletter-privacy">
                            <?php esc_html_e( 'By subscribing, you agree to our Privacy Policy and consent to receive updates from us.', 'alam-al-anika' ); ?>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

</main>

<style>
/* SHEIN-Style Homepage Styles */
.shein-homepage {
    background: #fff;
}

/* Hero Section */
.shein-hero-section {
    padding: 0;
    margin-bottom: 3rem;
}

.hero-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1rem;
    height: 600px;
}

.hero-main-banner {
    position: relative;
    overflow: hidden;
    border-radius: 0;
}

.hero-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-content-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, transparent 60%);
    display: flex;
    align-items: center;
    padding: 3rem;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 1rem;
    line-height: 1.1;
}

.hero-subtitle {
    font-size: 1.2rem;
    color: rgba(255,255,255,0.9);
    margin-bottom: 2rem;
    max-width: 400px;
}

.btn-hero-primary {
    display: inline-block;
    background: #fff;
    color: #000;
    padding: 16px 32px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-hero-primary:hover {
    background: #000;
    color: #fff;
    transform: translateY(-2px);
}

.hero-sale-badge {
    position: absolute;
    top: 2rem;
    right: 2rem;
    background: #ff4444;
    color: #fff;
    padding: 1rem;
    border-radius: 50%;
    text-align: center;
    width: 100px;
    height: 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}

.sale-percentage {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.sale-text,
.sale-off {
    font-size: 0.8rem;
    font-weight: 600;
}

.hero-secondary-banners {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.secondary-banner {
    position: relative;
    flex: 1;
    overflow: hidden;
    border-radius: 8px;
}

.banner-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.secondary-banner:hover .banner-image {
    transform: scale(1.05);
}

.banner-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    padding: 2rem 1.5rem 1.5rem;
    color: #fff;
}

.banner-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.banner-cta {
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Flash Sale Section */
.shein-flash-sale {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    color: #fff;
    padding: 4rem 0;
    margin: 4rem 0;
}

.flash-sale-header {
    text-align: center;
    margin-bottom: 3rem;
}

.flash-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.2);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    margin-bottom: 1rem;
    font-weight: 600;
}

.flash-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
}

.flash-timer {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.timer-item {
    text-align: center;
    background: rgba(255,255,255,0.1);
    padding: 1rem;
    border-radius: 8px;
    min-width: 80px;
}

.timer-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.timer-label {
    font-size: 0.9rem;
    opacity: 0.8;
    margin-top: 0.25rem;
}

.flash-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.flash-product-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.flash-product-card:hover {
    transform: translateY(-5px);
}

/* Popular Products */
.shein-popular-products {
    padding: 4rem 0;
}

.popular-tabs {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin: 2rem 0 3rem;
    border-bottom: 1px solid #e5e5e5;
}

.tab-button {
    background: none;
    border: none;
    padding: 1rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

.tab-button.active {
    color: #000;
    border-bottom-color: #000;
}

.popular-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

/* Newsletter */
.shein-newsletter {
    background: #f8f9fa;
    padding: 4rem 0;
}

.newsletter-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
}

.newsletter-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #333;
}

.newsletter-form {
    max-width: 400px;
}

.newsletter-input {
    width: 100%;
    padding: 16px;
    border: 2px solid #e5e5e5;
    border-radius: 8px;
    font-size: 16px;
    margin-bottom: 1rem;
}

.newsletter-btn {
    width: 100%;
    background: #000;
    color: #fff;
    padding: 16px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.newsletter-btn:hover {
    background: #333;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .hero-container {
        grid-template-columns: 1fr;
        height: auto;
    }
    
    .hero-main-banner {
        height: 400px;
    }
    
    .hero-secondary-banners {
        flex-direction: row;
        height: 200px;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .newsletter-content {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .flash-timer {
        gap: 0.5rem;
    }
    
    .timer-item {
        min-width: 60px;
        padding: 0.75rem 0.5rem;
    }
    
    .popular-tabs {
        flex-wrap: wrap;
        gap: 1rem;
    }
}
</style>

<?php get_footer(); ?>
