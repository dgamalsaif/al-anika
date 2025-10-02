<?php
/**
 * Color and Image Swatches System
 * Advanced variation swatches for WooCommerce products
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_Color_Swatches {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('woocommerce_product_option_terms', array($this, 'product_option_terms'), 10, 3);
        add_filter('woocommerce_dropdown_variation_attribute_options_html', array($this, 'variation_attribute_options_html'), 10, 2);
        add_action('woocommerce_product_after_variable_attributes', array($this, 'add_swatch_fields'), 10, 3);
        add_action('woocommerce_save_product_variation', array($this, 'save_swatch_fields'), 10, 2);
        add_action('wp_ajax_alam_get_variation_data', array($this, 'ajax_get_variation_data'));
        add_action('wp_ajax_nopriv_alam_get_variation_data', array($this, 'ajax_get_variation_data'));
    }
    
    public function init() {
        // Add custom fields to product attributes
        add_action('product_cat_add_form_fields', array($this, 'add_category_swatch_fields'));
        add_action('product_cat_edit_form_fields', array($this, 'edit_category_swatch_fields'));
        add_action('edited_product_cat', array($this, 'save_category_swatch_fields'));
        add_action('create_product_cat', array($this, 'save_category_swatch_fields'));
    }
    
    public function enqueue_scripts() {
        if (is_product() || is_shop() || is_product_category()) {
            wp_enqueue_script('alam-swatches', get_template_directory_uri() . '/assets/js/color-swatches.js', array('jquery'), '1.0.0', true);
            wp_localize_script('alam-swatches', 'alamSwatches', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('alam_swatches_nonce')
            ));
        }
    }
    
    public function variation_attribute_options_html($html, $args) {
        $options = $args['options'];
        $product = $args['product'];
        $attribute = $args['attribute'];
        $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
        $id = $args['id'] ? $args['id'] : sanitize_title($attribute);
        $class = $args['class'];
        $show_option_none = $args['show_option_none'] ? true : false;
        $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __('Choose an option', 'woocommerce');
        
        if (empty($options) && !empty($product) && !empty($attribute)) {
            $attributes = $product->get_variation_attributes();
            $options = $attributes[$attribute];
        }
        
        // Check if this attribute should display as swatches
        $attribute_name = str_replace('pa_', '', $attribute);
        $swatch_type = get_option("attribute_swatch_type_{$attribute_name}", 'default');
        
        if ($swatch_type === 'default') {
            return $html;
        }
        
        $html = '<div class="alam-swatches-container" data-attribute="' . esc_attr($attribute) . '">';
        $html .= '<input type="hidden" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="">';
        
        if ($show_option_none) {
            $html .= '<span class="alam-swatch-item alam-swatch-none active" data-value="">' . esc_html($show_option_none_text) . '</span>';
        }
        
        if (!empty($options)) {
            if ($product && taxonomy_exists($attribute)) {
                $terms = wc_get_product_terms($product->get_id(), $attribute, array('fields' => 'all'));
                
                foreach ($terms as $term) {
                    if (in_array($term->slug, $options, true)) {
                        $swatch_html = $this->get_swatch_html($term, $swatch_type, $attribute_name);
                        $html .= $swatch_html;
                    }
                }
            } else {
                foreach ($options as $option) {
                    $swatch_html = $this->get_swatch_html_for_custom($option, $swatch_type);
                    $html .= $swatch_html;
                }
            }
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    private function get_swatch_html($term, $swatch_type, $attribute_name) {
        $swatch_value = get_term_meta($term->term_id, "swatch_value_{$swatch_type}", true);
        $swatch_class = "alam-swatch-item alam-swatch-{$swatch_type}";
        
        $html = '<span class="' . esc_attr($swatch_class) . '" data-value="' . esc_attr($term->slug) . '" title="' . esc_attr($term->name) . '">';
        
        switch ($swatch_type) {
            case 'color':
                $color = $swatch_value ? $swatch_value : '#cccccc';
                $html .= '<span class="alam-swatch-color" style="background-color: ' . esc_attr($color) . ';"></span>';
                break;
                
            case 'image':
                if ($swatch_value) {
                    $image_url = wp_get_attachment_image_url($swatch_value, 'thumbnail');
                    $html .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '" class="alam-swatch-image">';
                } else {
                    $html .= '<span class="alam-swatch-text">' . esc_html($term->name) . '</span>';
                }
                break;
                
            case 'text':
            default:
                $html .= '<span class="alam-swatch-text">' . esc_html($term->name) . '</span>';
                break;
        }
        
        $html .= '</span>';
        
        return $html;
    }
    
    private function get_swatch_html_for_custom($option, $swatch_type) {
        $swatch_class = "alam-swatch-item alam-swatch-{$swatch_type}";
        
        $html = '<span class="' . esc_attr($swatch_class) . '" data-value="' . esc_attr($option) . '" title="' . esc_attr($option) . '">';
        $html .= '<span class="alam-swatch-text">' . esc_html($option) . '</span>';
        $html .= '</span>';
        
        return $html;
    }
    
    public function add_category_swatch_fields() {
        ?>
        <div class="form-field">
            <label for="swatch_type"><?php _e('نوع العينة', 'alam-al-anika'); ?></label>
            <select name="swatch_type" id="swatch_type">
                <option value="default"><?php _e('افتراضي', 'alam-al-anika'); ?></option>
                <option value="color"><?php _e('لون', 'alam-al-anika'); ?></option>
                <option value="image"><?php _e('صورة', 'alam-al-anika'); ?></option>
                <option value="text"><?php _e('نص', 'alam-al-anika'); ?></option>
            </select>
            <p><?php _e('اختر كيفية عرض هذه الخاصية في صفحة المنتج', 'alam-al-anika'); ?></p>
        </div>
        <?php
    }
    
    public function edit_category_swatch_fields($term) {
        $swatch_type = get_term_meta($term->term_id, 'swatch_type', true);
        ?>
        <tr class="form-field">
            <th scope="row"><label for="swatch_type"><?php _e('نوع العينة', 'alam-al-anika'); ?></label></th>
            <td>
                <select name="swatch_type" id="swatch_type">
                    <option value="default" <?php selected($swatch_type, 'default'); ?>><?php _e('افتراضي', 'alam-al-anika'); ?></option>
                    <option value="color" <?php selected($swatch_type, 'color'); ?>><?php _e('لون', 'alam-al-anika'); ?></option>
                    <option value="image" <?php selected($swatch_type, 'image'); ?>><?php _e('صورة', 'alam-al-anika'); ?></option>
                    <option value="text" <?php selected($swatch_type, 'text'); ?>><?php _e('نص', 'alam-al-anika'); ?></option>
                </select>
                <p class="description"><?php _e('اختر كيفية عرض هذه الخاصية في صفحة المنتج', 'alam-al-anika'); ?></p>
            </td>
        </tr>
        <?php
    }
    
    public function save_category_swatch_fields($term_id) {
        if (isset($_POST['swatch_type'])) {
            update_term_meta($term_id, 'swatch_type', sanitize_text_field($_POST['swatch_type']));
        }
    }
    
    public function ajax_get_variation_data() {
        check_ajax_referer('alam_swatches_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $attributes = $_POST['attributes'];
        
        $product = wc_get_product($product_id);
        
        if (!$product || !$product->is_type('variable')) {
            wp_die();
        }
        
        $variation_id = $this->find_matching_product_variation($product, $attributes);
        
        if ($variation_id) {
            $variation = wc_get_product($variation_id);
            
            $response = array(
                'variation_id' => $variation_id,
                'price_html' => $variation->get_price_html(),
                'availability_html' => wc_get_stock_html($variation),
                'image' => wp_get_attachment_image_src($variation->get_image_id(), 'woocommerce_single'),
                'variation_description' => $variation->get_description()
            );
            
            wp_send_json_success($response);
        } else {
            wp_send_json_error();
        }
    }
    
    private function find_matching_product_variation($product, $attributes) {
        foreach ($product->get_available_variations() as $variation_values) {
            $match = true;
            foreach ($attributes as $key => $value) {
                if (array_key_exists($key, $variation_values['attributes'])) {
                    if ($variation_values['attributes'][$key] !== $value) {
                        $match = false;
                        break;
                    }
                }
            }
            if ($match) {
                return $variation_values['variation_id'];
            }
        }
        return false;
    }
}

new Alam_Color_Swatches();