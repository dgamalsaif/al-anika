<?php
/**
 * Header Layout: Modern
 * Logo + Search + Cart layout (current default)
 *
 * @package AlamAlAnika
 */
?>

<header id="masthead" class="site-header header-modern">
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
            
            <!-- Search Section -->
            <div class="header-search">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-wrapper">
                        <input type="search" 
                               class="search-field" 
                               placeholder="<?php esc_attr_e( 'Search products...', 'alam-al-anika' ); ?>"
                               value="<?php echo get_search_query(); ?>" 
                               name="s" />
                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <input type="hidden" name="post_type" value="product" />
                        <?php endif; ?>
                        <button type="submit" class="search-submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Actions Section -->
            <div class="header-actions">
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <div class="header-cart">
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-link">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="user-menu">
                    <?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="user-link">
                            <i class="fa fa-user"></i>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="user-link">
                            <i class="fa fa-sign-in-alt"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </div>
</header>

<style>
.header-modern {
    background: var(--alam-header-bg, #fff);
    color: var(--alam-header-text, #333);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.header-modern .header-content {
    display: grid;
    grid-template-columns: 200px 1fr auto;
    align-items: center;
    gap: 30px;
    padding: 15px 0;
}

.header-modern .site-branding {
    /* Logo area */
}

.header-modern .site-title {
    margin: 0;
    font-size: var(--alam-logo-size, 24px);
}

.header-modern .site-title a {
    text-decoration: none;
    color: inherit;
}

.header-modern .header-search {
    max-width: 500px;
    width: 100%;
}

.header-modern .search-wrapper {
    position: relative;
    display: flex;
    background: #f8f9fa;
    border-radius: 25px;
    overflow: hidden;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.header-modern .search-wrapper:focus-within {
    border-color: var(--alam-primary, #FF6B6B);
    box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
}

.header-modern .search-field {
    flex: 1;
    padding: 12px 20px;
    border: none;
    background: transparent;
    font-size: 14px;
    outline: none;
}

.header-modern .search-submit {
    padding: 12px 20px;
    border: none;
    background: var(--alam-primary, #FF6B6B);
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.header-modern .search-submit:hover {
    background: var(--alam-button-hover, #FF5252);
}

.header-modern .header-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.header-modern .cart-link,
.header-modern .user-link {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    color: var(--alam-header-text, #333);
    text-decoration: none;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.header-modern .cart-link:hover,
.header-modern .user-link:hover {
    background: var(--alam-primary, #FF6B6B);
    color: white;
    transform: translateY(-2px);
}

.header-modern .cart-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: var(--alam-primary, #FF6B6B);
    color: white;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 11px;
    min-width: 16px;
    text-align: center;
    line-height: 1.2;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .header-modern .header-content {
        grid-template-columns: 1fr auto;
        gap: 15px;
    }
    
    .header-modern .header-search {
        grid-column: 1 / -1;
        order: 3;
        margin-top: 15px;
    }
}
</style>
