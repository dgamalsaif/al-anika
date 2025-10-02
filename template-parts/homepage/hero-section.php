 <?php
/**
 * The template for displaying the hero section on the homepage.
 *
 * @package AlamAlAnika
 */

// Get the main shop URL as a fallback
$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

// Get the URL for the 'accessories' category
$accessories_cat_url = get_term_link( 'accessories', 'product_cat' );
// If the category doesn't exist, get_term_link returns a WP_Error object.
// We check for that and use the shop URL as a fallback.
if ( is_wp_error( $accessories_cat_url ) ) {
    $accessories_cat_url = $shop_page_url;
}
?>
<section class="hero">
    <div class="hero-slides-container">
        
        <!-- Slide 1 -->
        <div class="hero-slide active">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-bg.jpg" alt="<?php esc_attr_e( 'Summer Collection', 'alam-al-anika' ); ?>" class="hero-image">
            <div class="hero-content">
                <h1><?php esc_html_e( 'مجموعة الصيف 2023', 'alam-al-anika' ); ?></h1>
                <p><?php esc_html_e( 'اكتشف أحدث الصيحات بخصم يصل إلى 70%', 'alam-al-anika' ); ?></p>
                <a href="<?php echo esc_url( $shop_page_url ); ?>" class="hero-btn"><?php esc_html_e( 'تسوق الآن', 'alam-al-anika' ); ?></a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="hero-slide">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.jpg" alt="<?php esc_attr_e( 'New Arrivals', 'alam-al-anika' ); ?>" class="hero-image">
            <div class="hero-content">
                <h1><?php esc_html_e( 'وصل حديثاً', 'alam-al-anika' ); ?></h1>
                <p><?php esc_html_e( 'كن أول من يتسوق أحدث صيحات الموضة', 'alam-al-anika' ); ?></p>
                <a href="<?php echo esc_url( $shop_page_url ); ?>" class="hero-btn"><?php esc_html_e( 'استكشف', 'alam-al-anika' ); ?></a>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="hero-slide">
             <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-bg.jpg" alt="<?php esc_attr_e( 'Accessories', 'alam-al-anika' ); ?>" class="hero-image">
            <div class="hero-content">
                <h1><?php esc_html_e( 'إكسسوارات مميزة', 'alam-al-anika' ); ?></h1>
                <p><?php esc_html_e( 'أكمل إطلالتك مع إكسسواراتنا الأساسية', 'alam-al-anika' ); ?></p>
                <a href="<?php echo esc_url( $accessories_cat_url ); ?>" class="hero-btn"><?php esc_html_e( 'تسوق الإكسسوارات', 'alam-al-anika' ); ?></a>
            </div>
        </div>

    </div>
    <div class="hero-dots">
        <div class="hero-dot active" data-slide="0"></div>
        <div class="hero-dot" data-slide="1"></div>
        <div class="hero-dot" data-slide="2"></div>
    </div>
</section>
