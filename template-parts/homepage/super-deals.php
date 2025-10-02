<?php
/**
 * The template for displaying the super deals section on the homepage.
 *
 * This section displays featured products.
 *
 * @package AlamAlAnika
 */

$featured_products_args = array(
    'post_type'      => 'product',
    'posts_per_page' => 4, // You can change the number of products to show
    'tax_query'      => array(
        array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
        ),
    ),
);

$featured_products_query = new WP_Query( $featured_products_args );

if ( $featured_products_query->have_posts() ) :
?>
<section class="super-deals section">
    <div class="deals-header">
        <div>
            <h2 class="deals-title"><?php esc_html_e( 'صفقات مميزة', 'alam-al-anika' ); ?></h2>
            <p class="deals-subtitle"><?php esc_html_e( 'عروض لفترة محدودة', 'alam-al-anika' ); ?></p>
        </div>
        <div class="deals-timer">
            <i class="far fa-clock"></i>
            <span id="deals-timer"><?php esc_html_e( 'ينتهي خلال: 02:34:56', 'alam-al-anika' ); ?></span>
        </div>
    </div>

    <div class="product-grid">
        <?php
        echo '<ul class="products columns-4">';
        while ( $featured_products_query->have_posts() ) :
            $featured_products_query->the_post();
            wc_get_template_part( 'content', 'product' );
        endwhile;
        echo '</ul>';
        ?>
    </div>
    <!-- Note: Pagination for a homepage section is optional. You can add it if needed. -->
</section>
<?php
endif;
wp_reset_postdata();