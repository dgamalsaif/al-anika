<?php
/**
 * Enhanced Header for Al-Anika Theme
 * Optimized for Arabic/RTL support and better UX
 *
 * @package Al_Anika_Theme
 * @since 9.2.1
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php if (get_theme_mod('favicon_url')) : ?>
        <link rel="icon" href="<?php echo esc_url(get_theme_mod('favicon_url')); ?>" type="image/x-icon">
    <?php endif; ?>
    
    <!-- Preconnect to external domains for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Arabic/RTL Fonts Support -->
    <?php if (is_rtl()) : ?>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body, .site {
                font-family: 'Cairo', 'Noto Sans Arabic', sans-serif;
                direction: rtl;
                text-align: right;
            }
            .rtl-support {
                direction: rtl;
                text-align: right;
            }
        </style>
    <?php else : ?>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php endif; ?>
    
    <!-- SEO Meta Tags -->
    <?php if (is_singular() && has_excerpt()) : ?>
        <meta name="description" content="<?php echo esc_attr(wp_trim_words(get_the_excerpt(), 30)); ?>">
    <?php elseif (is_home() || is_front_page()) : ?>
        <meta name="description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
    <?php endif; ?>
    
    <!-- Open Graph Tags -->
    <meta property="og:type" content="<?php echo is_singular() ? 'article' : 'website'; ?>">
    <meta property="og:title" content="<?php wp_title('|', true, 'right'); bloginfo('name'); ?>">
    <meta property="og:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
    <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
    
    <?php if (is_singular() && has_post_thumbnail()) : ?>
        <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url(null, 'large')); ?>">
    <?php endif; ?>
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php wp_title('|', true, 'right'); bloginfo('name'); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
    
    <?php if (is_singular() && has_post_thumbnail()) : ?>
        <meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url(null, 'large')); ?>">
    <?php endif; ?>
    
    <!-- Enhanced Performance and UX -->
    <meta name="theme-color" content="<?php echo esc_attr(get_theme_mod('brand_color', '#ff6b9d')); ?>">
    <meta name="msapplication-TileColor" content="<?php echo esc_attr(get_theme_mod('brand_color', '#ff6b9d')); ?>">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Skip Links for Accessibility -->
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to main content', 'alam-al-anika'); ?></a>
<a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_html_e('Skip to navigation', 'alam-al-anika'); ?></a>
<a class="skip-link screen-reader-text" href="#colophon"><?php esc_html_e('Skip to footer', 'alam-al-anika'); ?></a>

<div id="page" class="site <?php echo is_rtl() ? 'rtl-layout' : 'ltr-layout'; ?>">
    
    <?php if (get_theme_mod('show_header_top_bar', true)) : ?>
        <div class="header-top">
            <div class="container">
                <div class="header-top-content d-flex justify-content-between align-items-center">
                    
                    <div class="header-top-left">
                        <?php if (get_theme_mod('header_phone')) : ?>
                            <span class="header-phone">
                                <i class="fas fa-phone" aria-hidden="true"></i>
                                <a href="tel:<?php echo esc_attr(str_replace(' ', '', get_theme_mod('header_phone'))); ?>">
                                    <?php echo esc_html(get_theme_mod('header_phone')); ?>
                                </a>
                            </span>
                        <?php endif; ?>
                        
                        <?php if (get_theme_mod('header_email')) : ?>
                            <span class="header-email ml-3">
                                <i class="fas fa-envelope" aria-hidden="true"></i>
                                <a href="mailto:<?php echo esc_attr(get_theme_mod('header_email')); ?>">
                                    <?php echo esc_html(get_theme_mod('header_email')); ?>
                                </a>
                            </span>
                        <?php endif; ?>
                        
                        <!-- Language Switcher for Multilingual Support -->
                        <?php if (function_exists('pll_the_languages')) : ?>
                            <div class="language-switcher ml-3">
                                <?php pll_the_languages(array('dropdown' => 1, 'show_flags' => 1, 'show_names' => 0)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="header-top-right">
                        <?php if (is_active_sidebar('header-top')) : ?>
                            <?php dynamic_sidebar('header-top'); ?>
                        <?php else : ?>
                            <div class="header-social">
                                <?php if (get_theme_mod('facebook_url')) : ?>
                                    <a href="<?php echo esc_url(get_theme_mod('facebook_url')); ?>" target="_blank" rel="noopener" class="social-link" aria-label="<?php esc_attr_e('Facebook', 'alam-al-anika'); ?>">
                                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                        <span class="screen-reader-text"><?php esc_html_e('Facebook', 'alam-al-anika'); ?></span>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (get_theme_mod('twitter_url')) : ?>
                                    <a href="<?php echo esc_url(get_theme_mod('twitter_url')); ?>" target="_blank" rel="noopener" class="social-link" aria-label="<?php esc_attr_e('Twitter', 'alam-al-anika'); ?>">
                                        <i class="fab fa-twitter" aria-hidden="true"></i>
                                        <span class="screen-reader-text"><?php esc_html_e('Twitter', 'alam-al-anika'); ?></span>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (get_theme_mod('instagram_url')) : ?>
                                    <a href="<?php echo esc_url(get_theme_mod('instagram_url')); ?>" target="_blank" rel="noopener" class="social-link" aria-label="<?php esc_attr_e('Instagram', 'alam-al-anika'); ?>">
                                        <i class="fab fa-instagram" aria-hidden="true"></i>
                                        <span class="screen-reader-text"><?php esc_html_e('Instagram', 'alam-al-anika'); ?></span>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (get_theme_mod('linkedin_url')) : ?>
                                    <a href="<?php echo esc_url(get_theme_mod('linkedin_url')); ?>" target="_blank" rel="noopener" class="social-link" aria-label="<?php esc_attr_e('LinkedIn', 'alam-al-anika'); ?>">
                                        <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                                        <span class="screen-reader-text"><?php esc_html_e('LinkedIn', 'alam-al-anika'); ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (is_user_logged_in()) : ?>
                            <div class="user-account-links ml-3">
                                <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="account-link">
                                    <i class="fas fa-user" aria-hidden="true"></i>
                                    <?php esc_html_e('My Account', 'alam-al-anika'); ?>
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="auth-links ml-3">
                                <a href="<?php echo esc_url(wp_login_url()); ?>" class="login-link">
                                    <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                                    <?php esc_html_e('Login', 'alam-al-anika'); ?>
                                </a>
                                <span class="separator">|</span>
                                <a href="<?php echo esc_url(wp_registration_url()); ?>" class="register-link">
                                    <i class="fas fa-user-plus" aria-hidden="true"></i>
                                    <?php esc_html_e('Register', 'alam-al-anika'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <header id="masthead" class="site-header enhanced-header">
        <div class="container">
            <div class="header-main">
                <div class="header-content">
                    
                    <div class="site-branding">
                        <?php
                        if (has_custom_logo()) {
                            the_custom_logo();
                        } else {
                            if (is_front_page() && is_home()) :
                                ?>
                                <h1 class="site-title">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                        <?php bloginfo('name'); ?>
                                    </a>
                                </h1>
                                <?php
                            else :
                                ?>
                                <p class="site-title">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                        <?php bloginfo('name'); ?>
                                    </a>
                                </p>
                                <?php
                            endif;
                            $al_anika_description = get_bloginfo('description', 'display');
                            if ($al_anika_description || is_customize_preview()) :
                                ?>
                                <p class="site-description"><?php echo $al_anika_description; ?></p>
                                <?php
                            endif;
                        }
                        ?>
                    </div>
                    
                    <!-- Enhanced Search Bar -->
                    <div class="header-search">
                        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                            <label for="search-field" class="screen-reader-text"><?php esc_html_e('Search for:', 'alam-al-anika'); ?></label>
                            <input type="search" id="search-field" class="search-field" placeholder="<?php esc_attr_e('Search products...', 'alam-al-anika'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                            <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('Search', 'alam-al-anika'); ?>">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </button>
                            <input type="hidden" name="post_type" value="product" />
                        </form>
                    </div>
                    
                    <!-- WooCommerce Cart -->
                    <?php if (class_exists('WooCommerce')) : ?>
                        <div class="header-cart">
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link" aria-label="<?php esc_attr_e('Shopping Cart', 'alam-al-anika'); ?>">
                                <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                                <span class="screen-reader-text"><?php esc_html_e('Cart', 'alam-al-anika'); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <nav id="site-navigation" class="main-navigation enhanced-nav" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'alam-al-anika'); ?>">
                        
                        <button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <span class="menu-toggle-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                            <span class="screen-reader-text"><?php esc_html_e('Toggle Menu', 'alam-al-anika'); ?></span>
                        </button>
                        
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'nav-menu enhanced-menu',
                            'container'      => 'div',
                            'container_class' => 'nav-container',
                            'fallback_cb'    => 'al_anika_fallback_menu',
                            'walker'         => class_exists('Al_Anika_Walker_Nav_Menu') ? new Al_Anika_Walker_Nav_Menu() : '',
                        ));
                        ?>
                        
                        <!-- Quick Navigation for E-commerce -->
                        <div class="quick-nav">
                            <?php if (class_exists('WooCommerce')) : ?>
                                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="quick-nav-link">
                                    <i class="fas fa-store" aria-hidden="true"></i>
                                    <?php esc_html_e('Shop', 'alam-al-anika'); ?>
                                </a>
                                <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="quick-nav-link">
                                    <i class="fas fa-user-circle" aria-hidden="true"></i>
                                    <?php esc_html_e('Account', 'alam-al-anika'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                    </nav>
                    
                </div>
            </div>
        </div>
    </header>

    <main id="main" class="site-main" role="main">
