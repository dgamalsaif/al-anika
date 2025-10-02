<?php
/**
 * Header Layout: Centered
 * Centered logo with navigation below
 *
 * @package AlamAlAnika
 */
?>

<header id="masthead" class="site-header header-centered">
    <div class="container">
        
        <!-- Top Row: Actions -->
        <div class="header-top">
            <div class="header-info">
                <span class="header-phone">
                    <i class="fa fa-phone"></i>
                    +966 12 345 6789
                </span>
                <span class="header-email">
                    <i class="fa fa-envelope"></i>
                    info@alamalanika.com
                </span>
            </div>
            
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
        
        <!-- Main Row: Logo -->
        <div class="header-main">
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
                
                <?php 
                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ) :
                    ?>
                    <p class="site-description"><?php echo $description; ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Bottom Row: Navigation & Search -->
        <div class="header-bottom">
            <nav id="site-navigation" class="main-navigation">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'menu-1',
                    'menu_id'        => 'primary-menu',
                    'fallback_cb'    => false,
                ) );
                ?>
            </nav>
            
            <div class="header-search">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-wrapper">
                        <input type="search" 
                               class="search-field" 
                               placeholder="<?php esc_attr_e( 'Search...', 'alam-al-anika' ); ?>"
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
        </div>
        
    </div>
</header>

<style>
.header-centered {
    background: var(--alam-header-bg, #fff);
    color: var(--alam-header-text, #333);
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.header-centered .header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    font-size: 13px;
}

.header-centered .header-info {
    display: flex;
    gap: 20px;
    color: #666;
}

.header-centered .header-info i {
    margin-right: 5px;
    color: var(--alam-primary, #FF6B6B);
}

.header-centered .header-main {
    text-align: center;
    padding: 30px 0;
}

.header-centered .site-title {
    margin: 0 0 10px 0;
    font-size: calc(var(--alam-logo-size, 24px) + 8px);
    font-weight: 600;
}

.header-centered .site-title a {
    text-decoration: none;
    color: inherit;
}

.header-centered .site-description {
    margin: 0;
    color: #666;
    font-style: italic;
}

.header-centered .header-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-top: 1px solid rgba(0,0,0,0.05);
}

.header-centered .main-navigation {
    flex: 1;
}

.header-centered .main-navigation ul {
    display: flex;
    justify-content: center;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 30px;
}

.header-centered .main-navigation a {
    text-decoration: none;
    color: inherit;
    font-weight: 500;
    padding: 5px 0;
    position: relative;
    transition: all 0.3s ease;
}

.header-centered .main-navigation a:hover {
    color: var(--alam-primary, #FF6B6B);
}

.header-centered .main-navigation a:after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--alam-primary, #FF6B6B);
    transition: width 0.3s ease;
}

.header-centered .main-navigation a:hover:after {
    width: 100%;
}

.header-centered .header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-centered .cart-link,
.header-centered .user-link {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    background: #f8f9fa;
    color: var(--alam-header-text, #333);
    text-decoration: none;
    border-radius: 50%;
    transition: all 0.3s ease;
    font-size: 14px;
}

.header-centered .cart-link:hover,
.header-centered .user-link:hover {
    background: var(--alam-primary, #FF6B6B);
    color: white;
}

.header-centered .cart-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: var(--alam-primary, #FF6B6B);
    color: white;
    padding: 2px 5px;
    border-radius: 8px;
    font-size: 10px;
    min-width: 14px;
    text-align: center;
    line-height: 1.2;
}

.header-centered .search-wrapper {
    position: relative;
    display: flex;
    background: #f8f9fa;
    border-radius: 20px;
    overflow: hidden;
    width: 250px;
}

.header-centered .search-field {
    flex: 1;
    padding: 8px 15px;
    border: none;
    background: transparent;
    font-size: 13px;
    outline: none;
}

.header-centered .search-submit {
    padding: 8px 15px;
    border: none;
    background: var(--alam-primary, #FF6B6B);
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.header-centered .search-submit:hover {
    background: var(--alam-button-hover, #FF5252);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .header-centered .header-top {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .header-centered .header-bottom {
        flex-direction: column;
        gap: 20px;
    }
    
    .header-centered .main-navigation ul {
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .header-centered .search-wrapper {
        width: 100%;
        max-width: 300px;
    }
}
</style>
