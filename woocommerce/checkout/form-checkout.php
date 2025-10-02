<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 * @package AlamAlAnika/WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'alam-al-anika' ) ) );
	return;
}
?>

<div class="al-anika-checkout-container">
	<!-- Checkout Header -->
	<div class="checkout-header">
		<h1 class="checkout-title"><?php esc_html_e( 'Checkout', 'alam-al-anika' ); ?></h1>
		<div class="checkout-progress-bar">
			<div class="progress-step completed">
				<span class="step-number">1</span>
				<span class="step-label"><?php esc_html_e( 'Cart', 'alam-al-anika' ); ?></span>
			</div>
			<div class="progress-step active">
				<span class="step-number">2</span>
				<span class="step-label"><?php esc_html_e( 'Checkout', 'alam-al-anika' ); ?></span>
			</div>
			<div class="progress-step">
				<span class="step-number">3</span>
				<span class="step-label"><?php esc_html_e( 'Complete', 'alam-al-anika' ); ?></span>
			</div>
		</div>
	</div>

	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
		
		<div class="checkout-main-content">
			<!-- Checkout Form Section -->
			<div class="checkout-form-section">
				
				<!-- Guest/Login Options -->
				<?php if ( $checkout->get_checkout_fields() ) : ?>
					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
					
					<!-- Customer Information -->
					<div class="checkout-section customer-info">
						<h3 class="section-title">
							<span class="step-number">1</span>
							<?php esc_html_e( 'Customer Information', 'alam-al-anika' ); ?>
						</h3>
						
						<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
							<div class="login-register-toggle">
								<label class="toggle-option">
									<input type="radio" name="customer_type" value="guest" checked>
									<span><?php esc_html_e( 'Continue as Guest', 'alam-al-anika' ); ?></span>
								</label>
								<label class="toggle-option">
									<input type="radio" name="customer_type" value="register">
									<span><?php esc_html_e( 'Create Account', 'alam-al-anika' ); ?></span>
								</label>
							</div>
						<?php endif; ?>
						
						<div class="customer-details">
							<div class="col2-set" id="customer_details">
								<div class="col-1">
									<?php do_action( 'woocommerce_checkout_billing' ); ?>
								</div>
								<div class="col-2">
									<?php do_action( 'woocommerce_checkout_shipping' ); ?>
								</div>
							</div>
						</div>
					</div>
					
					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
				<?php endif; ?>
				
				<!-- Shipping Methods -->
				<div class="checkout-section shipping-methods">
					<h3 class="section-title">
						<span class="step-number">2</span>
						<?php esc_html_e( 'Shipping Method', 'alam-al-anika' ); ?>
					</h3>
					
					<div class="shipping-options">
						<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
							<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
							<?php wc_cart_totals_shipping_html(); ?>
							<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
						<?php endif; ?>
					</div>
				</div>
				
				<!-- Payment Methods -->
				<div class="checkout-section payment-methods">
					<h3 class="section-title">
						<span class="step-number">3</span>
						<?php esc_html_e( 'Payment Method', 'alam-al-anika' ); ?>
					</h3>
					
					<?php if ( WC()->cart->needs_payment() ) : ?>
						<div id="payment" class="woocommerce-checkout-payment">
							<?php if ( WC()->cart->needs_payment() ) : ?>
								<ul class="wc_payment_methods payment_methods methods">
									<?php
									if ( ! empty( $available_gateways = WC()->payment_gateways->get_available_payment_gateways() ) ) {
										foreach ( $available_gateways as $gateway ) {
											wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
										}
									} else {
										echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'alam-al-anika' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'alam-al-anika' ) ) . '</li>'; // @codingStandardsIgnoreLine
									}
									?>
								</ul>
							<?php endif; ?>
							
							<div class="form-row place-order">
								<noscript>
									<?php esc_html_e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the "Update Totals" button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'alam-al-anika' ); ?>
									<br/><button type="submit" class="button alt wp-element-button" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'alam-al-anika' ); ?>"><?php esc_html_e( 'Update totals', 'alam-al-anika' ); ?></button>
								</noscript>
								
								<?php wc_get_template( 'checkout/terms.php' ); ?>
								
								<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
								
								<button type="submit" class="button alt wp-element-button btn-place-order" name="woocommerce_checkout_place_order" id="place_order" value="<?php esc_attr_e( 'Place order', 'alam-al-anika' ); ?>" data-value="<?php esc_attr_e( 'Place order', 'alam-al-anika' ); ?>">
									<i class="fas fa-lock"></i>
									<span><?php esc_html_e( 'Place Order', 'alam-al-anika' ); ?></span>
								</button>
								
								<?php do_action( 'woocommerce_review_order_after_submit' ); ?>
								
								<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				
			</div>
			
			<!-- Order Summary Section -->
			<div class="checkout-summary-section">
				<div class="order-summary-sticky">
					<h3 class="summary-title"><?php esc_html_e( 'Order Summary', 'alam-al-anika' ); ?></h3>
					
					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>
					
					<!-- Security Badges -->
					<div class="security-badges">
						<div class="security-item">
							<i class="fas fa-shield-alt"></i>
							<span><?php esc_html_e( 'SSL Secure', 'alam-al-anika' ); ?></span>
						</div>
						<div class="security-item">
							<i class="fas fa-lock"></i>
							<span><?php esc_html_e( 'Safe Payment', 'alam-al-anika' ); ?></span>
						</div>
						<div class="security-item">
							<i class="fas fa-undo"></i>
							<span><?php esc_html_e( 'Easy Returns', 'alam-al-anika' ); ?></span>
						</div>
					</div>
					
					<!-- Payment Logos -->
					<div class="payment-logos">
						<div class="payment-text"><?php esc_html_e( 'We Accept:', 'alam-al-anika' ); ?></div>
						<div class="payment-icons">
							<i class="fab fa-cc-visa"></i>
							<i class="fab fa-cc-mastercard"></i>
							<i class="fab fa-cc-paypal"></i>
							<i class="fab fa-cc-apple-pay"></i>
							<i class="fab fa-google-pay"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</form>
	
	<!-- Support Contact -->
	<div class="checkout-support">
		<div class="support-item">
			<i class="fas fa-headset"></i>
			<div class="support-text">
				<strong><?php esc_html_e( 'Need Help?', 'alam-al-anika' ); ?></strong>
				<p><?php esc_html_e( 'Contact our support team', 'alam-al-anika' ); ?></p>
			</div>
		</div>
		<div class="support-item">
			<i class="fas fa-phone"></i>
			<div class="support-text">
				<strong><?php esc_html_e( 'Call Us', 'alam-al-anika' ); ?></strong>
				<p>+1 (555) 123-4567</p>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>