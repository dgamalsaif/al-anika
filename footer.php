<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Al_Anika_Theme
 * @since 9.0.0
 */

?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="grid grid-cols-4">
                        
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-1')) : ?>
                                <?php dynamic_sidebar('footer-1'); ?>
                            <?php else : ?>
                                <div class="widget">
                                    <h3 class="widget-title"><?php bloginfo('name'); ?></h3>
                                    <p><?php bloginfo('description'); ?></p>
                                    
                                    <?php if (get_theme_mod('footer_about_text')) : ?>
                                        <p><?php echo wp_kses_post(get_theme_mod('footer_about_text')); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="footer-social mt-4">
                                        <?php if (get_theme_mod('facebook_url')) : ?>
                                            <a href="<?php echo esc_url(get_theme_mod('facebook_url')); ?>" target="_blank" rel="noopener" class="social-link">
                                                <i class="fab fa-facebook-f"></i>
                                                <span class="screen-reader-text"><?php esc_html_e('Facebook', 'al-anika'); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (get_theme_mod('twitter_url')) : ?>
                                            <a href="<?php echo esc_url(get_theme_mod('twitter_url')); ?>" target="_blank" rel="noopener" class="social-link">
                                                <i class="fab fa-twitter"></i>
                                                <span class="screen-reader-text"><?php esc_html_e('Twitter', 'al-anika'); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (get_theme_mod('instagram_url')) : ?>
                                            <a href="<?php echo esc_url(get_theme_mod('instagram_url')); ?>" target="_blank" rel="noopener" class="social-link">
                                                <i class="fab fa-instagram"></i>
                                                <span class="screen-reader-text"><?php esc_html_e('Instagram', 'al-anika'); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (get_theme_mod('linkedin_url')) : ?>
                                            <a href="<?php echo esc_url(get_theme_mod('linkedin_url')); ?>" target="_blank" rel="noopener" class="social-link">
                                                <i class="fab fa-linkedin-in"></i>
                                                <span class="screen-reader-text"><?php esc_html_e('LinkedIn', 'al-anika'); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (get_theme_mod('youtube_url')) : ?>
                                            <a href="<?php echo esc_url(get_theme_mod('youtube_url')); ?>" target="_blank" rel="noopener" class="social-link">
                                                <i class="fab fa-youtube"></i>
                                                <span class="screen-reader-text"><?php esc_html_e('YouTube', 'al-anika'); ?></span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-2')) : ?>
                                <?php dynamic_sidebar('footer-2'); ?>
                            <?php else : ?>
                                <div class="widget">
                                    <h3 class="widget-title"><?php esc_html_e('Quick Links', 'al-anika'); ?></h3>
                                    <ul>
                                        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'al-anika'); ?></a></li>
                                        <li><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"><?php esc_html_e('Blog', 'al-anika'); ?></a></li>
                                        <?php if (class_exists('WooCommerce')) : ?>
                                            <li><a href="<?php echo esc_url(get_permalink(get_option('woocommerce_shop_page_id'))); ?>"><?php esc_html_e('Shop', 'al-anika'); ?></a></li>
                                            <li><a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>"><?php esc_html_e('My Account', 'al-anika'); ?></a></li>
                                        <?php endif; ?>
                                        <li><a href="<?php echo esc_url(get_privacy_policy_url()); ?>"><?php esc_html_e('Privacy Policy', 'al-anika'); ?></a></li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-3')) : ?>
                                <?php dynamic_sidebar('footer-3'); ?>
                            <?php else : ?>
                                <div class="widget">
                                    <h3 class="widget-title"><?php esc_html_e('Contact Info', 'al-anika'); ?></h3>
                                    
                                    <?php if (get_theme_mod('footer_address')) : ?>
                                        <div class="contact-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?php echo wp_kses_post(get_theme_mod('footer_address')); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (get_theme_mod('header_phone')) : ?>
                                        <div class="contact-item">
                                            <i class="fas fa-phone"></i>
                                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', get_theme_mod('header_phone'))); ?>">
                                                <?php echo esc_html(get_theme_mod('header_phone')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (get_theme_mod('header_email')) : ?>
                                        <div class="contact-item">
                                            <i class="fas fa-envelope"></i>
                                            <a href="mailto:<?php echo esc_attr(get_theme_mod('header_email')); ?>">
                                                <?php echo esc_html(get_theme_mod('header_email')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (get_theme_mod('business_hours')) : ?>
                                        <div class="contact-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?php echo wp_kses_post(get_theme_mod('business_hours')); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-4')) : ?>
                                <?php dynamic_sidebar('footer-4'); ?>
                            <?php else : ?>
                                <div class="widget">
                                    <h3 class="widget-title"><?php esc_html_e('Newsletter', 'al-anika'); ?></h3>
                                    <p><?php esc_html_e('Subscribe to our newsletter for latest updates and offers.', 'al-anika'); ?></p>
                                    
                                    <form class="newsletter-form" action="#" method="post">
                                        <div class="form-group">
                                            <input type="email" name="newsletter_email" placeholder="<?php esc_attr_e('Your email address', 'al-anika'); ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <?php esc_html_e('Subscribe', 'al-anika'); ?>
                                        </button>
                                    </form>
                                    
                                    <?php if (get_theme_mod('show_payment_methods', true)) : ?>
                                        <div class="payment-methods mt-4">
                                            <h4><?php esc_html_e('We Accept', 'al-anika'); ?></h4>
                                            <div class="payment-icons">
                                                <i class="fab fa-cc-visa" title="Visa"></i>
                                                <i class="fab fa-cc-mastercard" title="Mastercard"></i>
                                                <i class="fab fa-cc-amex" title="American Express"></i>
                                                <i class="fab fa-cc-paypal" title="PayPal"></i>
                                                <i class="fab fa-cc-stripe" title="Stripe"></i>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="site-info">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    
                    <div class="copyright">
                        <?php if (get_theme_mod('footer_copyright')) : ?>
                            <?php echo wp_kses_post(get_theme_mod('footer_copyright')); ?>
                        <?php else : ?>
                            <p>&copy; <?php echo esc_html(date('Y')); ?> 
                                <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>. 
                                <?php esc_html_e('All rights reserved.', 'al-anika'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="footer-nav">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_class'     => 'footer-menu d-flex',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'depth'          => 1,
                        ));
                        ?>
                    </div>
                    
                </div>
                
                <?php if (get_theme_mod('show_back_to_top', true)) : ?>
                    <button class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'al-anika'); ?>">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                <?php endif; ?>
                
            </div>
        </div>
        
    </footer><!-- #colophon -->
    
</div><!-- #page -->

<?php
// Add structured data for organization
if (is_front_page()) :
?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "<?php bloginfo('name'); ?>",
    "description": "<?php bloginfo('description'); ?>",
    "url": "<?php echo esc_url(home_url('/')); ?>",
    <?php if (has_custom_logo()) : ?>
    "logo": "<?php echo esc_url(wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full')); ?>",
    <?php endif; ?>
    <?php if (get_theme_mod('footer_address')) : ?>
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo esc_js(get_theme_mod('footer_address')); ?>"
    },
    <?php endif; ?>
    <?php if (get_theme_mod('header_phone')) : ?>
    "telephone": "<?php echo esc_js(get_theme_mod('header_phone')); ?>",
    <?php endif; ?>
    <?php if (get_theme_mod('header_email')) : ?>
    "email": "<?php echo esc_js(get_theme_mod('header_email')); ?>",
    <?php endif; ?>
    "sameAs": [
        <?php
        $social_links = array();
        if (get_theme_mod('facebook_url')) $social_links[] = '"' . esc_js(get_theme_mod('facebook_url')) . '"';
        if (get_theme_mod('twitter_url')) $social_links[] = '"' . esc_js(get_theme_mod('twitter_url')) . '"';
        if (get_theme_mod('instagram_url')) $social_links[] = '"' . esc_js(get_theme_mod('instagram_url')) . '"';
        if (get_theme_mod('linkedin_url')) $social_links[] = '"' . esc_js(get_theme_mod('linkedin_url')) . '"';
        echo implode(',', $social_links);
        ?>
    ]
}
</script>
<?php endif; ?>

<?php
// Performance and Analytics tracking
if (get_theme_mod('al_anika_enable_analytics', true)) {
    // Google Analytics
    if (get_theme_mod('google_analytics_id')) {
        ?>
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr(get_theme_mod('google_analytics_id')); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo esc_js(get_theme_mod('google_analytics_id')); ?>');
        </script>
        <?php
    }
    
    // Facebook Pixel
    if (get_theme_mod('facebook_pixel_id')) {
        ?>
        <!-- Facebook Pixel -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?php echo esc_js(get_theme_mod('facebook_pixel_id')); ?>');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" 
                 src="https://www.facebook.com/tr?id=<?php echo esc_attr(get_theme_mod('facebook_pixel_id')); ?>&ev=PageView&noscript=1"/>
        </noscript>
        <?php
    }
}
?>

<?php wp_footer(); ?>

</body>
</html>