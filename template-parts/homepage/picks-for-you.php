<?php
/**
 * The template for displaying the "Picks For You" section on the homepage.
 * Professional product recommendations section.
 *
 * @package AlamAlAnika
 */

// Professional product recommendation logic
$featured_products_args = array(
    'post_type'      => 'product',
    'posts_per_page' => 8, // Increased for better display
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'meta_query'     => array(
        array(
            'key'     => '_featured',
            'value'   => 'yes',
            'compare' => '='
        )
    )
);

$featured_products_query = new WP_Query( $featured_products_args );

// Fallback to recent products if no featured products
if ( ! $featured_products_query->have_posts() ) {
    $featured_products_args = array(
        'post_type'      => 'product',
        'posts_per_page' => 8,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $featured_products_query = new WP_Query( $featured_products_args );
}

if ( $featured_products_query->have_posts() ) :
?>
<section class="section featured-products">
    <div class="section-header">
        <h2 class="section-title arabic-text"><?php esc_html_e( 'مختارات مميزة لك', 'alam-al-anika' ); ?></h2>
        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="view-all">
            <span class="arabic-text"><?php esc_html_e( 'عرض جميع المنتجات', 'alam-al-anika' ); ?></span>
            <i class="fas fa-chevron-left"></i>
        </a>
    </div>

    <div class="product-grid professional-grid">
        <?php
        echo '<ul class="products columns-4">';
        while ( $featured_products_query->have_posts() ) :
            $featured_products_query->the_post();
            wc_get_template_part( 'content', 'product' );
        endwhile;
        echo '</ul>';
        ?>
    </div>
    
    <!-- Professional Call-to-Action -->
    <div class="section-cta">
        <div class="cta-content">
            <h3 class="arabic-text"><?php esc_html_e( 'اكتشف المزيد من العروض المميزة', 'alam-al-anika' ); ?></h3>
            <p class="arabic-text"><?php esc_html_e( 'تسوق من مجموعة واسعة من المنتجات عالية الجودة بأفضل الأسعار', 'alam-al-anika' ); ?></p>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="button button-primary">
                <span class="arabic-text"><?php esc_html_e( 'تسوق الآن', 'alam-al-anika' ); ?></span>
                <i class="fas fa-shopping-bag"></i>
            </a>
        </div>
    </div>
</section>

<style>
/* Professional Section Enhancements */
.featured-products {
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    border-radius: var(--border-radius-lg);
    margin: var(--spacing-xl) 0;
}

.professional-grid .products {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--spacing-xl);
    list-style: none;
    padding: 0;
}

.section-cta {
    margin-top: var(--spacing-xxl);
    padding: var(--spacing-xl);
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: var(--border-radius-lg);
    text-align: center;
    color: white;
}

.cta-content h3 {
    color: white;
    margin-bottom: var(--spacing-md);
    font-size: 1.5rem;
}

.cta-content p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: var(--spacing-lg);
    font-size: var(--font-size-lg);
}

.cta-content .button {
    background: white;
    color: var(--primary-color);
    font-weight: 700;
}

.cta-content .button:hover {
    background: #f8f9fa;
    transform: translateY(-3px);
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .professional-grid .products {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }
    
    .section-cta {
        padding: var(--spacing-lg);
    }
    
    .cta-content h3 {
        font-size: 1.25rem;
    }
    
    .cta-content p {
        font-size: var(--font-size-base);
    }
}
</style>

<?php
endif;
wp_reset_postdata();
