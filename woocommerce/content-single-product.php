<?php
/**
 * The template for displaying single product content with advanced features
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @package AlamAlAnika/WooCommerce
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'al-anika-single-product', $product ); ?>>
	
	<!-- Product Breadcrumb -->
	<div class="product-breadcrumb-wrapper">
		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		?>
	</div>
	
	<div class="single-product-container">
		<!-- Product Images Section -->
		<div class="product-images-section">
			<div class="product-gallery-wrapper">
				<?php
				/**
				 * Hook: woocommerce_before_single_product_summary.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
				?>
				
				<!-- Custom Product Badges -->
				<div class="product-badges-single">
					<?php 
					if ( function_exists( 'al_anika_single_product_badges' ) ) {
						al_anika_single_product_badges(); 
					}
					?>
				</div>
				
				<!-- 360 View Button (if available) -->
				<?php if ( get_post_meta( get_the_ID(), '_360_view_enabled', true ) ) : ?>
					<button class="btn-360-view" data-product-id="<?php echo esc_attr( get_the_ID() ); ?>">
						<i class="fas fa-sync-alt"></i>
						<span><?php esc_html_e( '360° View', 'alam-al-anika' ); ?></span>
					</button>
				<?php endif; ?>
			</div>
		</div>
		
		<!-- Product Details Section -->
		<div class="product-details-section">
			<div class="summary entry-summary">
				<?php
				/**
				 * Hook: woocommerce_single_product_summary.
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 * @hooked WC_Structured_Data::generate_product_data() - 60
				 */
				do_action( 'woocommerce_single_product_summary' );
				?>
				
				<!-- Custom Product Features -->
				<div class="product-features-section">
					<!-- Size Guide -->
					<?php if ( function_exists( 'al_anika_has_size_guide' ) && al_anika_has_size_guide( $product ) ) : ?>
						<button class="btn-size-guide" data-product-id="<?php echo esc_attr( get_the_ID() ); ?>">
							<i class="fas fa-ruler"></i>
							<?php esc_html_e( 'Size Guide', 'alam-al-anika' ); ?>
						</button>
					<?php endif; ?>
					
					<!-- Material Info -->
					<?php $material_info = get_post_meta( get_the_ID(), '_product_material', true ); ?>
					<?php if ( $material_info ) : ?>
						<div class="material-info">
							<i class="fas fa-leaf"></i>
							<span><?php echo esc_html( $material_info ); ?></span>
						</div>
					<?php endif; ?>
					
					<!-- Care Instructions -->
					<?php $care_instructions = get_post_meta( get_the_ID(), '_care_instructions', true ); ?>
					<?php if ( $care_instructions ) : ?>
						<button class="btn-care-instructions" data-content="<?php echo esc_attr( $care_instructions ); ?>">
							<i class="fas fa-heart"></i>
							<?php esc_html_e( 'Care Instructions', 'alam-al-anika' ); ?>
						</button>
					<?php endif; ?>
				</div>
				
				<!-- Alam Al Anika Action Buttons -->
				<div class="product-action-buttons">
					<button class="btn-wishlist-single" data-product-id="<?php echo esc_attr( get_the_ID() ); ?>">
						<i class="fas fa-heart"></i>
						<span><?php esc_html_e( 'Add to Wishlist', 'alam-al-anika' ); ?></span>
					</button>
					
					<button class="btn-share-product">
						<i class="fas fa-share-alt"></i>
						<span><?php esc_html_e( 'Share', 'alam-al-anika' ); ?></span>
					</button>
				</div>
				
				<!-- Trust Badges -->
				<div class="trust-badges">
					<div class="trust-item">
						<i class="fas fa-truck"></i>
						<span><?php esc_html_e( 'Fast Shipping', 'alam-al-anika' ); ?></span>
					</div>
					<div class="trust-item">
						<i class="fas fa-undo"></i>
						<span><?php esc_html_e( 'Easy Returns', 'alam-al-anika' ); ?></span>
					</div>
					<div class="trust-item">
						<i class="fas fa-shield-alt"></i>
						<span><?php esc_html_e( 'Secure Payment', 'alam-al-anika' ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>ocommerce_output_related_products' );
		?>
	</div>
	
	<!-- Recently Viewed Products -->
	<div class="recently-viewed-section">
		<?php al_anika_show_recently_viewed_products(); ?>
	</div>
	
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>