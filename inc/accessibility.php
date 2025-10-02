<?php
/**
 * Accessibility Enhancements
 * WCAG 2.1 AA compliance and accessibility improvements
 *
 * @package AlamAlAnika
 * @since 6.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Accessibility Class
 */
class Al_Anika_Accessibility {
    
    public function __construct() {
        add_action( 'init', array( $this, 'init_accessibility_features' ) );
    }
    
    /**
     * Initialize accessibility features
     */
    public function init_accessibility_features() {
        // Add skip links
        add_action( 'wp_body_open', array( $this, 'add_skip_links' ) );
        
        // Enhanced focus management
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_accessibility_scripts' ) );
        
        // ARIA labels and roles
        add_filter( 'nav_menu_link_attributes', array( $this, 'add_menu_accessibility' ), 10, 4 );
        
        // Image alt text validation
        add_filter( 'wp_get_attachment_image_attributes', array( $this, 'validate_alt_text' ), 10, 3 );
        
        // Form accessibility
        add_action( 'comment_form_before', array( $this, 'add_form_accessibility' ) );
        
        // Color contrast warnings
        add_action( 'wp_head', array( $this, 'add_accessibility_css' ) );
        
        // Keyboard navigation
        add_action( 'wp_footer', array( $this, 'add_keyboard_navigation' ) );
    }
    
    /**
     * Add skip links for keyboard navigation - with deduplication
     */
    public function add_skip_links() {
        // تجنب التكرار باستخدام static
        static $skip_links_rendered = false;
        
        if ($skip_links_rendered) {
            return;
        }
        
        echo '<div class="skip-links screen-reader-text">';
        echo '<a href="#main" class="skip-link">' . esc_html__( 'Skip to main content', 'alam-al-anika' ) . '</a>';
        echo '<a href="#primary-menu" class="skip-link">' . esc_html__( 'Skip to navigation', 'alam-al-anika' ) . '</a>';
        echo '<a href="#footer" class="skip-link">' . esc_html__( 'Skip to footer', 'alam-al-anika' ) . '</a>';
        echo '</div>';
        
        $skip_links_rendered = true;
    }
    
    /**
     * Enqueue accessibility scripts
     */
    public function enqueue_accessibility_scripts() {
        wp_enqueue_script(
            'al-anika-accessibility',
            get_template_directory_uri() . '/assets/js/accessibility.js',
            array( 'jquery' ),
            AL_ANIKA_VERSION,
            true
        );
        
        wp_localize_script( 'al-anika-accessibility', 'alAnikaA11y', array(
            'expandMenu' => __( 'Expand menu', 'alam-al-anika' ),
            'collapseMenu' => __( 'Collapse menu', 'alam-al-anika' ),
            'previous' => __( 'Previous', 'alam-al-anika' ),
            'next' => __( 'Next', 'alam-al-anika' ),
            'play' => __( 'Play', 'alam-al-anika' ),
            'pause' => __( 'Pause', 'alam-al-anika' ),
        ) );
    }
    
    /**
     * Add accessibility attributes to menu items
     */
    public function add_menu_accessibility( $atts, $item, $args, $depth ) {
        // Add ARIA attributes for dropdown menus
        if ( in_array( 'menu-item-has-children', $item->classes ) ) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }
        
        // Add descriptive aria-label for external links
        if ( strpos( $atts['href'], home_url() ) === false && strpos( $atts['href'], 'http' ) === 0 ) {
            $atts['aria-label'] = $item->title . ' ' . __( '(opens in new window)', 'alam-al-anika' );
            $atts['target'] = '_blank';
            $atts['rel'] = 'noopener noreferrer';
        }
        
        return $atts;
    }
    
    /**
     * Validate and enhance image alt text
     */
    public function validate_alt_text( $attr, $attachment, $size ) {
        // Ensure alt text exists
        if ( empty( $attr['alt'] ) ) {
            $alt_text = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
            if ( empty( $alt_text ) ) {
                $attr['alt'] = $attachment->post_title;
            } else {
                $attr['alt'] = $alt_text;
            }
        }
        
        // Add role for decorative images
        if ( $attr['alt'] === '' ) {
            $attr['role'] = 'presentation';
            $attr['aria-hidden'] = 'true';
        }
        
        return $attr;
    }
    
    /**
     * Add form accessibility enhancements
     */
    public function add_form_accessibility() {
        echo '<div class="form-accessibility-note screen-reader-text">';
        echo '<p>' . esc_html__( 'Required fields are marked with an asterisk (*)', 'alam-al-anika' ) . '</p>';
        echo '</div>';
    }
    
    /**
     * Add accessibility CSS
     */
    public function add_accessibility_css() {
        echo '<style id="al-anika-accessibility-css">';
        
        // Skip links
        echo '.skip-links{position:absolute;top:-40px;left:0;z-index:100000;width:100%}';
        echo '.skip-link{position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden}';
        echo '.skip-link:focus{position:static;width:auto;height:auto;padding:8px 16px;background:#000;color:#fff;text-decoration:none;z-index:100001}';
        
        // Screen reader text
        echo '.screen-reader-text{position:absolute!important;clip:rect(1px,1px,1px,1px);width:1px;height:1px;overflow:hidden}';
        echo '.screen-reader-text:focus{background:#f1f1f1;border-radius:3px;box-shadow:0 0 2px 2px rgba(0,0,0,.6);clip:auto;color:#21759b;display:block;font-size:14px;font-weight:bold;height:auto;left:5px;line-height:normal;padding:15px 23px 14px;text-decoration:none;top:5px;width:auto;z-index:100000}';
        
        // Focus styles
        echo 'a:focus,button:focus,input:focus,textarea:focus,select:focus{outline:2px solid #0073aa;outline-offset:2px}';
        echo '.focus-visible{outline:2px solid #0073aa;outline-offset:2px}';
        
        // High contrast mode
        echo '@media (prefers-contrast:high){*{background:transparent!important;color:black!important;text-shadow:none!important;box-shadow:none!important}a,a *{color:blue!important}a:visited,a:visited *{color:purple!important}}';
        
        // Reduced motion
        echo '@media (prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:0.01ms!important;animation-iteration-count:1!important;transition-duration:0.01ms!important;scroll-behavior:auto!important}}';
        
        // Dark mode
        echo '@media (prefers-color-scheme:dark){:root{--bg-color:#1a1a1a;--text-color:#ffffff;--link-color:#4dabf7}body{background-color:var(--bg-color);color:var(--text-color)}a{color:var(--link-color)}}';
        
        echo '</style>';
    }
    
    /**
     * Add keyboard navigation JavaScript
     */
    public function add_keyboard_navigation() {
        ?>
        <script id="al-anika-keyboard-nav">
        (function() {
            // Track if user is navigating with keyboard
            let isKeyboardUser = false;
            
            // Add keyboard user class
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    isKeyboardUser = true;
                    document.body.classList.add('keyboard-navigation');
                }
            });
            
            // Remove keyboard user class on mouse use
            document.addEventListener('mousedown', function() {
                isKeyboardUser = false;
                document.body.classList.remove('keyboard-navigation');
            });
            
            // Enhanced focus management for modals
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Close any open modals
                    const modals = document.querySelectorAll('.al-anika-modal.active');
                    modals.forEach(modal => {
                        modal.classList.remove('active');
                        document.body.classList.remove('modal-open');
                        
                        // Return focus to trigger element
                        const trigger = document.querySelector('[data-modal-trigger]');
                        if (trigger) trigger.focus();
                    });
                }
            });
            
            // Trap focus in modals
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    const activeModal = document.querySelector('.al-anika-modal.active');
                    if (activeModal) {
                        const focusableElements = activeModal.querySelectorAll(
                            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                        );
                        
                        const firstElement = focusableElements[0];
                        const lastElement = focusableElements[focusableElements.length - 1];
                        
                        if (e.shiftKey) {
                            if (document.activeElement === firstElement) {
                                lastElement.focus();
                                e.preventDefault();
                            }
                        } else {
                            if (document.activeElement === lastElement) {
                                firstElement.focus();
                                e.preventDefault();
                            }
                        }
                    }
                }
            });
            
            // Announce dynamic content changes
            function announceToScreenReader(message) {
                const announcement = document.createElement('div');
                announcement.setAttribute('aria-live', 'polite');
                announcement.setAttribute('aria-atomic', 'true');
                announcement.className = 'screen-reader-text';
                announcement.textContent = message;
                
                document.body.appendChild(announcement);
                
                setTimeout(() => {
                    document.body.removeChild(announcement);
                }, 1000);
            }
            
            // Make announcements for cart updates
            if (typeof jQuery !== 'undefined') {
                jQuery(document.body).on('added_to_cart', function() {
                    announceToScreenReader('Product added to cart');
                });
                
                jQuery(document.body).on('removed_from_cart', function() {
                    announceToScreenReader('Product removed from cart');
                });
            }
        })();
        </script>
        <?php
    }
}

// Initialize accessibility features
new Al_Anika_Accessibility();

/**
 * Accessibility utility functions
 */

/**
 * Get ARIA label for pagination
 */
function al_anika_get_pagination_aria_label( $type = 'posts' ) {
    switch ( $type ) {
        case 'products':
            return __( 'Products pagination', 'alam-al-anika' );
        case 'comments':
            return __( 'Comments pagination', 'alam-al-anika' );
        default:
            return __( 'Posts pagination', 'alam-al-anika' );
    }
}

/**
 * Add ARIA attributes to search form
 */
function al_anika_search_form_aria( $form ) {
    $form = str_replace( '<input type="search"', '<input type="search" aria-label="' . esc_attr__( 'Search', 'alam-al-anika' ) . '"', $form );
    $form = str_replace( '<button', '<button aria-label="' . esc_attr__( 'Submit search', 'alam-al-anika' ) . '"', $form );
    return $form;
}
add_filter( 'get_search_form', 'al_anika_search_form_aria' );

/**
 * Add ARIA attributes to comment form
 */
function al_anika_comment_form_args( $args ) {
    $args['comment_field'] = str_replace( '<textarea', '<textarea aria-describedby="comment-notes" aria-required="true"', $args['comment_field'] );
    
    $args['fields']['author'] = str_replace( '<input', '<input aria-required="true" aria-describedby="author-notes"', $args['fields']['author'] );
    $args['fields']['email'] = str_replace( '<input', '<input aria-required="true" aria-describedby="email-notes"', $args['fields']['email'] );
    
    return $args;
}
add_filter( 'comment_form_defaults', 'al_anika_comment_form_args' );

/**
 * Add accessibility to WooCommerce
 */
if ( class_exists( 'WooCommerce' ) ) {
    /**
     * Add ARIA labels to WooCommerce forms
     */
    function al_anika_woocommerce_accessibility() {
        // Add to cart button accessibility
        add_filter( 'woocommerce_loop_add_to_cart_link', 'al_anika_add_to_cart_aria', 10, 2 );
        
        // Quantity input accessibility
        add_filter( 'woocommerce_quantity_input_args', 'al_anika_quantity_input_aria', 10, 2 );
        
        // Checkout form accessibility
        add_action( 'woocommerce_checkout_before_customer_details', 'al_anika_checkout_accessibility' );
    }
    add_action( 'init', 'al_anika_woocommerce_accessibility' );
    
    /**
     * Add ARIA labels to add to cart buttons
     */
    function al_anika_add_to_cart_aria( $link, $product ) {
        $product_name = $product->get_name();
        $aria_label = sprintf( __( 'Add "%s" to your cart', 'alam-al-anika' ), $product_name );
        
        return str_replace( '<a ', '<a aria-label="' . esc_attr( $aria_label ) . '" ', $link );
    }
    
    /**
     * Add ARIA labels to quantity inputs
     */
    function al_anika_quantity_input_aria( $args, $product ) {
        if ( $product ) {
            $args['input_aria_label'] = sprintf( __( 'Quantity for %s', 'alam-al-anika' ), $product->get_name() );
        }
        
        return $args;
    }
    
    /**
     * Add accessibility notes to checkout
     */
    function al_anika_checkout_accessibility() {
        echo '<div class="checkout-accessibility-info screen-reader-text">';
        echo '<h2>' . __( 'Checkout Accessibility Information', 'alam-al-anika' ) . '</h2>';
        echo '<p>' . __( 'This checkout form is designed to be accessible. Use Tab to navigate between fields and Enter to submit the form.', 'alam-al-anika' ) . '</p>';
        echo '<p>' . __( 'Required fields are marked with an asterisk (*). Screen reader users will be notified of required fields.', 'alam-al-anika' ) . '</p>';
        echo '</div>';
    }
}
