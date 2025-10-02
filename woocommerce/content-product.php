<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 * @package AlamAlAnika/WooCommerce
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'product-card al-anika-product-item', $product ); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>

	<div class="product-card-inner">
		<!-- Product Image Section -->
		<div class="product-image-container">
			<?php do_action( 'al_anika_before_product_image' ); ?>
			
			<!-- Product Badges -->
			<div class="product-badges">
				<?php
				/**
				 * Hook: woocommerce_before_shop_loop_item_title.
				 *
				 * @hooked woocommerce_show_product_loop_sale_flash - 10
				 * @hooked woocommerce_template_loop_product_thumbnail - 10
				 */
				do_action( 'woocommerce_before_shop_loop_item_title' );
				
				// Custom badges
				if ( function_exists( 'al_anika_product_badges' ) ) {
					al_anika_product_badges();
				}
				?>
			</div>
			
			<!-- Main Product Image with Hover Effect -->
			<div class="product-image-wrapper">
				<?php
				// Main image
				$image_id = $product->get_image_id();
				if ( $image_id ) {
					echo wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array(
						'class' => 'product-main-image',
						'loading' => 'lazy'
					) );
				} else {
					echo wc_placeholder_img( 'woocommerce_thumbnail', 'product-main-image' );
				}
				
				// Hover image (second gallery image)
				$gallery_images = $product->get_gallery_image_ids();
				if ( ! empty( $gallery_images ) ) {
					echo wp_get_attachment_image( $gallery_images[0], 'woocommerce_thumbnail', false, array(
						'class' => 'product-hover-image',
						'loading' => 'lazy'
					) );
				}
				?>
			</div>
			
			<!-- Quick Action Buttons -->
			<div class="product-actions">
				<!-- Quick View -->
				<button class="btn-quick-view" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" title="<?php esc_attr_e( 'Quick View', 'alam-al-anika' ); ?>">
					<i class="fas fa-eye"></i>
				</button>
				
				<!-- Wishlist -->
				<button class="btn-wishlist" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" title="<?php esc_attr_e( 'Add to Wishlist', 'alam-al-anika' ); ?>">
					<i class="fas fa-heart"></i>
				</button>
				
				<!-- Compare -->
				<button class="btn-compare" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" title="<?php esc_attr_e( 'Compare', 'alam-al-anika' ); ?>">
					<i class="fas fa-balance-scale"></i>
				</button>
			</div>
			
			<?php do_action( 'al_anika_after_product_image' ); ?>
		</div>
		
		<!-- Product Info Section -->
		<div class="product-info">
			<?php do_action( 'al_anika_before_product_info' ); ?>
			
			<!-- Product Title -->
			<h2 class="product-title woocommerce-loop-product__title">
				<?php
				/**
				 * Hook: woocommerce_shop_loop_item_title.
				 *
				 * @hooked woocommerce_template_loop_product_title - 10
				 */
				do_action( 'woocommerce_shop_loop_item_title' );
				?>
			</h2>
			
			<!-- Product Rating and Price -->
			<?php
			/**
			 * Hook: woocommerce_after_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
			
			<!-- Product Colors/Variations Preview -->
			<?php if ( $product->is_type( 'variable' ) && function_exists( 'al_anika_show_variation_swatches' ) ) : ?>
				<div class="product-variations-preview">
					<?php al_anika_show_variation_swatches( $product ); ?>
				</div>
			<?php endif; ?>
			
			<?php do_action( 'al_anika_after_product_info' ); ?>
		</div>
	</div>
	
	<!-- Add to Cart Button -->
	<div class="product-cart-action">
		<?php
		/**
		 * Hook: woocommerce_after_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item' );
		?>
	</div>
	
	<!-- Alam Al Anika Size Guide Modal Trigger -->
	<?php if ( has_term( '', 'product_cat', $product->get_id() ) ) : ?>
		<div class="size-guide-trigger" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
			<small><?php esc_html_e( 'Size Guide', 'alam-al-anika' ); ?></small>
		</div>
	<?php endif; ?>
</li>