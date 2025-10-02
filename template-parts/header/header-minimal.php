<?php
/**
 * Header Layout: Minimal
 * Simple logo + cart layout
 *
 * @package AlamAlAnika
 */
?>

<header id="masthead" class="site-header header-minimal">
    <div class="container">
        <div class="header-content">
            
            <!-- Logo Section -->
            <div class="site-branding">
                <?php
                if ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                    </h1>
                    <?php
                }
                ?>
            </div>
            
            <!-- Cart Section -->
            <div class="header-actions">
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <div class="header-cart">
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-link">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</header>

<style>
.header-minimal {
    background: var(--alam-header-bg, #fff);
    color: var(--alam-header-text, #333);
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.header-minimal .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
}

.header-minimal .site-branding {
    flex: 1;
}

.header-minimal .site-title {
    margin: 0;
    font-size: var(--alam-logo-size, 24px);
}

.header-minimal .site-title a {
    text-decoration: none;
    color: inherit;
}

.header-minimal .header-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.header-minimal .cart-link {
    position: relative;
    display: flex;
    align-items: center;
    padding: 10px 15px;
    background: var(--alam-primary, #FF6B6B);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.header-minimal .cart-link:hover {
    background: var(--alam-button-hover, #FF5252);
    transform: translateY(-2px);
}

.header-minimal .cart-count {
    margin-left: 8px;
    background: rgba(255,255,255,0.2);
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 12px;
    min-width: 18px;
    text-align: center;
}
</style>
