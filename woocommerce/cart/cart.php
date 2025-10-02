<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 * @package AlamAlAnika/WooCommerce
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="al-anika-cart-container">
	<div class="cart-header">
		<h1 class="cart-title"><?php esc_html_e( 'Shopping Cart', 'alam-al-anika' ); ?></h1>
		<div class="cart-progress-bar">
			<div class="progress-step active">
				<span class="step-number">1</span>
				<span class="step-label"><?php esc_html_e( 'Cart', 'alam-al-anika' ); ?></span>
			</div>
			<div class="progress-step">
				<span class="step-number">2</span>
				<span class="step-label"><?php esc_html_e( 'Checkout', 'alam-al-anika' ); ?></span>
			</div>
			<div class="progress-step">
				<span class="step-number">3</span>
				<span class="step-label"><?php esc_html_e( 'Complete', 'alam-al-anika' ); ?></span>
			</div>
		</div>
	</div>

	<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<div class="cart-main-content">
			<div class="cart-items-section">
				<table class="shop_table shop_table_responsive cart woocommerce-cart-table__table" cellspacing="0">
					<thead>
						<tr>
							<th class="product-thumbnail"><?php esc_html_e( 'Product', 'alam-al-anika' ); ?></th>
							<th class="product-name">&nbsp;</th>
							<th class="product-price"><?php esc_html_e( 'Price', 'alam-al-anika' ); ?></th>
							<th class="product-quantity"><?php esc_html_e( 'Quantity', 'alam-al-anika' ); ?></th>
							<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'alam-al-anika' ); ?></th>
							<th class="product-remove">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php do_action( 'woocommerce_before_cart_contents' ); ?>

						<?php
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								?>
								<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

									<td class="product-thumbnail">
										<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'al-anika-cart-thumb' ), $cart_item, $cart_item_key );

										if ( ! $product_permalink ) {
											echo $thumbnail; // PHPCS: XSS ok.
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
										}
										?>
									</td>

									<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'alam-al-anika' ); ?>">
										<div class="product-details">
											<?php
											if ( ! $product_permalink ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
											} else {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
											}

											do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

											// Meta data.
											echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

											// Backorder notification.
											if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'alam-al-anika' ) . '</p>', $product_id ) );
											}
											?>
										</div>
									</td>

									<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'alam-al-anika' ); ?>">
										<?php
											echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
										?>
									</td>

									<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'alam-al-anika' ); ?>">
										<?php
										if ( $_product->is_sold_individually() ) {
											$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
										} else {
											$product_quantity = woocommerce_quantity_input(
												array(
													'input_name'   => "cart[{$cart_item_key}][qty]",
													'input_value'  => $cart_item['quantity'],
													'max_value'    => $_product->get_max_purchase_quantity(),
													'min_value'    => '0',
													'product_name' => wp_strip_all_tags( $_product->get_name() ),
												),
												$_product,
												false
											);
										}

										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
										?>
									</td>

									<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'alam-al-anika' ); ?>">
										<?php
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
										?>
									</td>

									<td class="product-remove">
										<?php
											echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												'woocommerce_cart_item_remove_link',
												sprintf(
													'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fas fa-times"></i></a>',
													esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
													esc_html__( 'Remove this item', 'alam-al-anika' ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() )
												),
												$cart_item_key
											);
										?>
									</td>
								</tr>
								<?php
							}
						}
						?>

						<?php do_action( 'woocommerce_cart_contents' ); ?>

						<tr>
							<td colspan="6" class="actions">
								<div class="cart-actions">
									<?php if ( wc_coupons_enabled() ) { ?>
										<div class="coupon">
											<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'alam-al-anika' ); ?></label>
											<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'alam-al-anika' ); ?>" />
											<button type="submit" class="button wp-element-button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'alam-al-anika' ); ?>"><?php esc_html_e( 'Apply coupon', 'alam-al-anika' ); ?></button>
											<?php do_action( 'woocommerce_cart_coupon' ); ?>
										</div>
									<?php } ?>

									<button type="submit" class="button wp-element-button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'alam-al-anika' ); ?>" disabled aria-disabled="true"><?php esc_html_e( 'Update cart', 'alam-al-anika' ); ?></button>

									<?php do_action( 'woocommerce_cart_actions' ); ?>

									<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
								</div>
							</td>
						</tr>

						<?php do_action( 'woocommerce_after_cart_contents' ); ?>
					</tbody>
				</table>
				<?php do_action( 'woocommerce_after_cart_table' ); ?>
			</div>

			<!-- Cart Totals Sidebar -->
			<div class="cart-totals-section">
				<div class="cart-collaterals">
					<?php
						/**
						 * Cart collaterals hook.
						 *
						 * @hooked woocommerce_cross_sell_display
						 * @hooked woocommerce_cart_totals - 10
						 */
						do_action( 'woocommerce_cart_collaterals' );
					?>
				</div>
			</div>
		</div>
	</form>

	<!-- Cart Benefits/Features -->
	<div class="cart-benefits">
		<div class="benefit-item">
			<i class="fas fa-truck"></i>
			<div class="benefit-text">
				<h4><?php esc_html_e( 'Free Shipping', 'alam-al-anika' ); ?></h4>
				<p><?php esc_html_e( 'On orders over $50', 'alam-al-anika' ); ?></p>
			</div>
		</div>
		<div class="benefit-item">
			<i class="fas fa-undo"></i>
			<div class="benefit-text">
				<h4><?php esc_html_e( 'Easy Returns', 'alam-al-anika' ); ?></h4>
				<p><?php esc_html_e( '30-day return policy', 'alam-al-anika' ); ?></p>
			</div>
		</div>
		<div class="benefit-item">
			<i class="fas fa-shield-alt"></i>
			<div class="benefit-text">
				<h4><?php esc_html_e( 'Secure Payment', 'alam-al-anika' ); ?></h4>
				<p><?php esc_html_e( 'SSL protected checkout', 'alam-al-anika' ); ?></p>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>