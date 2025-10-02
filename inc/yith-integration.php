<?php
/**
 * YITH Wishlist & Compare Integration
 * Enhanced integration with YITH plugins
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_YITH_Integration {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'init'));
        
        // Wishlist Integration
        if (defined('YITH_WCWL')) {
            add_action('woocommerce_single_product_summary', array($this, 'add_wishlist_button'), 31);
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_loop_wishlist_button'), 15);
            add_filter('yith_wcwl_button_label', array($this, 'customize_wishlist_labels'));
            add_filter('yith_wcwl_browse_wishlist_label', array($this, 'customize_wishlist_labels'));
        }
        
        // Compare Integration
        if (defined('YITH_WOOCOMPARE')) {
            add_action('woocommerce_single_product_summary', array($this, 'add_compare_button'), 32);
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_loop_compare_button'), 16);
            add_filter('yith_woocompare_button_text', array($this, 'customize_compare_labels'));
        }
        
        // Custom shortcodes
        add_shortcode('alam_wishlist_counter', array($this, 'wishlist_counter_shortcode'));
        add_shortcode('alam_compare_counter', array($this, 'compare_counter_shortcode'));
        add_shortcode('alam_wishlist_dropdown', array($this, 'wishlist_dropdown_shortcode'));
        
        // AJAX handlers
        add_action('wp_ajax_alam_get_wishlist_count', array($this, 'ajax_get_wishlist_count'));
        add_action('wp_ajax_nopriv_alam_get_wishlist_count', array($this, 'ajax_get_wishlist_count'));
        add_action('wp_ajax_alam_get_compare_count', array($this, 'ajax_get_compare_count'));
        add_action('wp_ajax_nopriv_alam_get_compare_count', array($this, 'ajax_get_compare_count'));
    }
    
    public function init() {
        // Override YITH styles with our custom styles
        if (defined('YITH_WCWL')) {
            wp_dequeue_style('yith-wcwl-font-awesome');
            wp_dequeue_style('jquery-selectBox');
        }
        
        if (defined('YITH_WOOCOMPARE')) {
            wp_dequeue_style('jquery-colorbox');
        }
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('alam-yith-integration', get_template_directory_uri() . '/assets/js/yith-integration.js', array('jquery'), '1.0.0', true);
        wp_localize_script('alam-yith-integration', 'alamYith', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alam_yith_nonce'),
            'messages' => array(
                'added_to_wishlist' => 'تمت إضافة المنتج لقائمة الأمنيات',
                'removed_from_wishlist' => 'تم حذف المنتج من قائمة الأمنيات',
                'added_to_compare' => 'تمت إضافة المنتج للمقارنة',
                'removed_from_compare' => 'تم حذف المنتج من المقارنة',
                'login_required' => 'يجب تسجيل الدخول أولاً'
            )
        ));
    }
    
    public function add_wishlist_button() {
        if (defined('YITH_WCWL') && function_exists('yith_wcwl_is_product_in_wishlist')) {
            global $product;
            $product_id = $product->get_id();
            $is_in_wishlist = yith_wcwl_is_product_in_wishlist($product_id);
            ?>
            <div class="alam-wishlist-wrapper">
                <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
            </div>
            <?php
        }
    }
    
    public function add_loop_wishlist_button() {
        if (defined('YITH_WCWL')) {
            global $product;
            $product_id = $product->get_id();
            ?>
            <div class="alam-loop-wishlist">
                <?php echo do_shortcode('[yith_wcwl_add_to_wishlist product_id="' . $product_id . '"]'); ?>
            </div>
            <?php
        }
    }
    
    public function add_compare_button() {
        if (defined('YITH_WOOCOMPARE')) {
            global $product;
            ?>
            <div class="alam-compare-wrapper">
                <?php echo do_shortcode('[yith_compare_button]'); ?>
            </div>
            <?php
        }
    }
    
    public function add_loop_compare_button() {
        if (defined('YITH_WOOCOMPARE')) {
            global $product;
            $product_id = $product->get_id();
            ?>
            <div class="alam-loop-compare">
                <?php echo do_shortcode('[yith_compare_button product="' . $product_id . '"]'); ?>
            </div>
            <?php
        }
    }
    
    public function customize_wishlist_labels($label) {
        $labels = array(
            'Add to wishlist' => 'إضافة لقائمة الأمنيات',
            'Browse wishlist' => 'عرض قائمة الأمنيات',
            'Product added!' => 'تمت الإضافة!',
            'Already in wishlist' => 'موجود في القائمة',
            'Remove from wishlist' => 'حذف من القائمة'
        );
        
        return isset($labels[$label]) ? $labels[$label] : $label;
    }
    
    public function customize_compare_labels($label) {
        $labels = array(
            'Compare' => 'مقارنة',
            'Added' => 'تمت الإضافة',
            'Remove' => 'حذف'
        );
        
        return isset($labels[$label]) ? $labels[$label] : $label;
    }
    
    public function wishlist_counter_shortcode($atts) {
        if (!defined('YITH_WCWL')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'style' => 'default'
        ), $atts);
        
        $count = 0;
        if (function_exists('yith_wcwl_count_products')) {
            $count = yith_wcwl_count_products();
        }
        
        ob_start();
        ?>
        <div class="alam-wishlist-counter <?php echo esc_attr($atts['style']); ?>">
            <a href="<?php echo YITH_WCWL()->get_wishlist_url(); ?>" class="alam-wishlist-link">
                <svg class="alam-wishlist-icon" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5 2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.04L12,21.35Z"/>
                </svg>
                <span class="alam-counter" data-count="<?php echo $count; ?>"><?php echo $count; ?></span>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function compare_counter_shortcode($atts) {
        if (!defined('YITH_WOOCOMPARE')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'style' => 'default'
        ), $atts);
        
        $count = 0;
        if (function_exists('yith_woocompare_compare_products_number')) {
            $count = yith_woocompare_compare_products_number();
        }
        
        ob_start();
        ?>
        <div class="alam-compare-counter <?php echo esc_attr($atts['style']); ?>">
            <a href="<?php echo YITH_WOOCOMPARE()->obj->view_table_url(); ?>" class="alam-compare-link">
                <svg class="alam-compare-icon" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M3 3h4v14H3V3zm6 0h4v14H9V3zm6 0h2v14h-2V3z"/>
                </svg>
                <span class="alam-counter" data-count="<?php echo $count; ?>"><?php echo $count; ?></span>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function wishlist_dropdown_shortcode($atts) {
        if (!defined('YITH_WCWL')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'limit' => 5
        ), $atts);
        
        $wishlist_products = array();
        if (function_exists('yith_wcwl_get_products')) {
            $wishlist_products = yith_wcwl_get_products(array(
                'limit' => intval($atts['limit']),
                'wishlist_id' => 'default'
            ));
        }
        
        ob_start();
        ?>
        <div class="alam-wishlist-dropdown">
            <div class="alam-dropdown-header">
                <h4>قائمة الأمنيات</h4>
                <span class="alam-wishlist-count"><?php echo count($wishlist_products); ?> منتج</span>
            </div>
            
            <div class="alam-dropdown-content">
                <?php if (empty($wishlist_products)): ?>
                    <div class="alam-empty-wishlist">
                        <p><?php esc_html_e('قائمة الأمنيات فارغة', 'alam-al-anika'); ?></p>
                        <a href="<?php echo al_anika_safe_wc_url('shop'); ?>" class="button"><?php esc_html_e('تسوق الآن', 'alam-al-anika'); ?></a>
                    </div>
                <?php else: ?>
                    <div class="alam-wishlist-items">
                        <?php foreach ($wishlist_products as $item): 
                            $product = wc_get_product($item['prod_id']);
                            if (!$product) continue;
                            ?>
                            <div class="alam-wishlist-item">
                                <div class="alam-item-image">
                                    <a href="<?php echo $product->get_permalink(); ?>">
                                        <?php echo $product->get_image('thumbnail'); ?>
                                    </a>
                                </div>
                                <div class="alam-item-details">
                                    <h5><a href="<?php echo $product->get_permalink(); ?>"><?php echo $product->get_name(); ?></a></h5>
                                    <div class="alam-item-price"><?php echo $product->get_price_html(); ?></div>
                                </div>
                                <div class="alam-item-actions">
                                    <button class="alam-add-to-cart-mini" data-product-id="<?php echo $product->get_id(); ?>">
                                        <svg width="16" height="16" viewBox="0 0 24 24">
                                            <path d="M19,7H15.5V6.5A3.5,3.5 0 0,0 12,3A3.5,3.5 0 0,0 8.5,6.5V7H5A1,1 0 0,0 4,8V19A3,3 0 0,0 7,22H17A3,3 0 0,0 20,19V8A1,1 0 0,0 19,7M12,5A1.5,1.5 0 0,1 13.5,6.5V7H10.5V6.5A1.5,1.5 0 0,1 12,5M18,19A1,1 0 0,1 17,20H7A1,1 0 0,1 6,19V9H8.5V10A0.5,0.5 0 0,0 9,10.5A0.5,0.5 0 0,0 9.5,10V9H14.5V10A0.5,0.5 0 0,0 15,10.5A0.5,0.5 0 0,0 15.5,10V9H18V19Z"/>
                                        </svg>
                                    </button>
                                    <button class="alam-remove-wishlist-mini" data-product-id="<?php echo $product->get_id(); ?>">
                                        <svg width="16" height="16" viewBox="0 0 24 24">
                                            <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="alam-dropdown-footer">
                        <a href="<?php echo YITH_WCWL()->get_wishlist_url(); ?>" class="button">عرض جميع المنتجات</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function ajax_get_wishlist_count() {
        $count = 0;
        if (defined('YITH_WCWL') && function_exists('yith_wcwl_count_products')) {
            $count = yith_wcwl_count_products();
        }
        
        wp_send_json_success(array('count' => $count));
    }
    
    public function ajax_get_compare_count() {
        $count = 0;
        if (defined('YITH_WOOCOMPARE') && function_exists('yith_woocompare_compare_products_number')) {
            $count = yith_woocompare_compare_products_number();
        }
        
        wp_send_json_success(array('count' => $count));
    }
}

new Alam_YITH_Integration();