<?php
/**
 * Product Comparison System
 * Advanced comparison functionality with YITH integration
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_Product_Compare {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_alam_add_to_compare', array($this, 'ajax_add_to_compare'));
        add_action('wp_ajax_nopriv_alam_add_to_compare', array($this, 'ajax_add_to_compare'));
        add_action('wp_ajax_alam_remove_from_compare', array($this, 'ajax_remove_from_compare'));
        add_action('wp_ajax_nopriv_alam_remove_from_compare', array($this, 'ajax_remove_from_compare'));
        add_action('wp_ajax_alam_get_compare_products', array($this, 'ajax_get_compare_products'));
        add_action('wp_ajax_nopriv_alam_get_compare_products', array($this, 'ajax_get_compare_products'));
        add_shortcode('alam_compare_button', array($this, 'compare_button_shortcode'));
        add_shortcode('alam_compare_table', array($this, 'compare_table_shortcode'));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('alam-compare', get_template_directory_uri() . '/assets/js/product-compare.js', array('jquery'), '1.0.0', true);
        wp_localize_script('alam-compare', 'alamCompare', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alam_compare_nonce'),
            'messages' => array(
                'added' => 'تمت إضافة المنتج للمقارنة',
                'removed' => 'تم حذف المنتج من المقارنة',
                'limit' => 'يمكن مقارنة 4 منتجات كحد أقصى',
                'empty' => 'لا توجد منتجات للمقارنة'
            )
        ));
    }
    
    public function get_compare_products() {
        $compare_list = isset($_COOKIE['alam_compare_list']) ? json_decode(stripslashes($_COOKIE['alam_compare_list']), true) : array();
        return is_array($compare_list) ? $compare_list : array();
    }
    
    public function compare_button_shortcode($atts) {
        $atts = shortcode_atts(array(
            'product_id' => get_the_ID(),
            'style' => 'default'
        ), $atts);
        
        $product_id = intval($atts['product_id']);
        $compare_list = $this->get_compare_products();
        $is_in_compare = in_array($product_id, $compare_list);
        
        ob_start();
        ?>
        <div class="alam-compare-button-wrapper">
            <button class="alam-compare-button <?php echo esc_attr($atts['style']); ?> <?php echo $is_in_compare ? 'active' : ''; ?>" 
                    data-product-id="<?php echo $product_id; ?>"
                    data-action="<?php echo $is_in_compare ? 'remove' : 'add'; ?>"
                    title="<?php echo $is_in_compare ? 'إزالة من المقارنة' : 'إضافة للمقارنة'; ?>">
                <svg class="alam-compare-icon" width="20" height="20" viewBox="0 0 20 20">
                    <path d="M3 3h4v14H3V3zm6 0h4v14H9V3zm6 0h2v14h-2V3z"/>
                </svg>
                <span class="alam-compare-text">
                    <?php echo $is_in_compare ? 'إزالة من المقارنة' : 'مقارنة'; ?>
                </span>
            </button>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function compare_table_shortcode($atts) {
        $compare_list = $this->get_compare_products();
        
        if (empty($compare_list)) {
            return '<div class="alam-compare-empty">لا توجد منتجات للمقارنة</div>';
        }
        
        ob_start();
        ?>
        <div class="alam-compare-table-wrapper">
            <div class="alam-compare-header">
                <h2>مقارنة المنتجات</h2>
                <button class="alam-clear-compare" title="مسح جميع المنتجات">
                    مسح الكل
                </button>
            </div>
            
            <div class="alam-compare-table-container">
                <table class="alam-compare-table">
                    <thead>
                        <tr>
                            <th class="alam-compare-feature">المواصفات</th>
                            <?php foreach ($compare_list as $product_id): 
                                $product = wc_get_product($product_id);
                                if (!$product) continue;
                                ?>
                                <th class="alam-compare-product">
                                    <div class="alam-compare-product-header">
                                        <button class="alam-remove-product" data-product-id="<?php echo $product_id; ?>">×</button>
                                        <div class="alam-product-image">
                                            <?php echo $product->get_image('woocommerce_thumbnail'); ?>
                                        </div>
                                        <h3><?php echo $product->get_name(); ?></h3>
                                        <div class="alam-product-price">
                                            <?php echo $product->get_price_html(); ?>
                                        </div>
                                        <div class="alam-product-actions">
                                            <a href="<?php echo $product->get_permalink(); ?>" class="button">عرض المنتج</a>
                                            <?php echo do_shortcode('[add_to_cart id="' . $product_id . '" style=""]'); ?>
                                        </div>
                                    </div>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $features = array(
                            'description' => 'الوصف',
                            'sku' => 'رقم المنتج',
                            'stock_status' => 'حالة التوفر',
                            'weight' => 'الوزن',
                            'dimensions' => 'الأبعاد',
                            'reviews' => 'التقييمات'
                        );
                        
                        foreach ($features as $feature_key => $feature_name):
                        ?>
                            <tr class="alam-compare-row">
                                <td class="alam-compare-feature-name"><?php echo $feature_name; ?></td>
                                <?php foreach ($compare_list as $product_id): 
                                    $product = wc_get_product($product_id);
                                    if (!$product) continue;
                                    ?>
                                    <td class="alam-compare-feature-value">
                                        <?php
                                        switch ($feature_key) {
                                            case 'description':
                                                echo wp_trim_words($product->get_short_description(), 20);
                                                break;
                                            case 'sku':
                                                echo $product->get_sku() ? $product->get_sku() : 'غير محدد';
                                                break;
                                            case 'stock_status':
                                                echo $product->is_in_stock() ? 'متوفر' : 'غير متوفر';
                                                break;
                                            case 'weight':
                                                echo $product->get_weight() ? $product->get_weight() . ' ' . get_option('woocommerce_weight_unit') : 'غير محدد';
                                                break;
                                            case 'dimensions':
                                                $dimensions = $product->get_dimensions(false);
                                                echo !empty(array_filter($dimensions)) ? implode(' × ', $dimensions) . ' ' . get_option('woocommerce_dimension_unit') : 'غير محدد';
                                                break;
                                            case 'reviews':
                                                $rating = $product->get_average_rating();
                                                $review_count = $product->get_review_count();
                                                if ($rating) {
                                                    echo '<div class="star-rating" style="width:' . ($rating / 5 * 100) . '%">';
                                                    echo '<span>(' . $review_count . ' تقييم)</span>';
                                                    echo '</div>';
                                                } else {
                                                    echo 'لا توجد تقييمات';
                                                }
                                                break;
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php
                        // Custom attributes
                        $all_attributes = array();
                        foreach ($compare_list as $product_id) {
                            $product = wc_get_product($product_id);
                            if (!$product) continue;
                            $attributes = $product->get_attributes();
                            foreach ($attributes as $attribute_name => $attribute) {
                                if (!isset($all_attributes[$attribute_name])) {
                                    $all_attributes[$attribute_name] = $attribute->get_name();
                                }
                            }
                        }
                        
                        foreach ($all_attributes as $attr_name => $attr_label):
                        ?>
                            <tr class="alam-compare-row">
                                <td class="alam-compare-feature-name"><?php echo $attr_label; ?></td>
                                <?php foreach ($compare_list as $product_id): 
                                    $product = wc_get_product($product_id);
                                    if (!$product) continue;
                                    $attribute = $product->get_attribute($attr_name);
                                    ?>
                                    <td class="alam-compare-feature-value">
                                        <?php echo $attribute ? $attribute : 'غير محدد'; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function ajax_add_to_compare() {
        check_ajax_referer('alam_compare_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $compare_list = $this->get_compare_products();
        
        if (count($compare_list) >= 4) {
            wp_send_json_error(array('message' => 'يمكن مقارنة 4 منتجات كحد أقصى'));
        }
        
        if (!in_array($product_id, $compare_list)) {
            $compare_list[] = $product_id;
            setcookie('alam_compare_list', json_encode($compare_list), time() + (30 * 24 * 60 * 60), '/');
        }
        
        wp_send_json_success(array(
            'message' => 'تمت إضافة المنتج للمقارنة',
            'count' => count($compare_list)
        ));
    }
    
    public function ajax_remove_from_compare() {
        check_ajax_referer('alam_compare_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $compare_list = $this->get_compare_products();
        
        $compare_list = array_diff($compare_list, array($product_id));
        setcookie('alam_compare_list', json_encode(array_values($compare_list)), time() + (30 * 24 * 60 * 60), '/');
        
        wp_send_json_success(array(
            'message' => 'تم حذف المنتج من المقارنة',
            'count' => count($compare_list)
        ));
    }
    
    public function ajax_get_compare_products() {
        $compare_list = $this->get_compare_products();
        wp_send_json_success(array('products' => $compare_list, 'count' => count($compare_list)));
    }
}

new Alam_Product_Compare();