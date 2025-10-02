<?php
/**
 * Advanced Filter Bar System
 * Category and Product filtering with AJAX
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_Filter_System {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_alam_filter_products', array($this, 'ajax_filter_products'));
        add_action('wp_ajax_nopriv_alam_filter_products', array($this, 'ajax_filter_products'));
        add_action('woocommerce_before_shop_loop', array($this, 'add_filter_bar'), 15);
        add_shortcode('alam_filter_bar', array($this, 'filter_bar_shortcode'));
        add_shortcode('alam_category_filter', array($this, 'category_filter_shortcode'));
    }
    
    public function init() {
        // Initialize filter system
    }
    
    public function enqueue_scripts() {
        if (is_shop() || is_product_category() || is_product_tag()) {
            wp_enqueue_script('alam-filters', get_template_directory_uri() . '/assets/js/filter-system.js', array('jquery'), '1.0.0', true);
            wp_localize_script('alam-filters', 'alamFilters', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('alam_filter_nonce'),
                'shop_url' => wc_get_page_permalink('shop'),
                'messages' => array(
                    'loading' => 'جاري التحميل...',
                    'no_products' => 'لا توجد منتجات تطابق الفلاتر المحددة',
                    'error' => 'حدث خطأ، يرجى المحاولة مرة أخرى'
                )
            ));
        }
    }
    
    public function add_filter_bar() {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }
        
        echo $this->render_filter_bar();
    }
    
    public function filter_bar_shortcode($atts) {
        $atts = shortcode_atts(array(
            'style' => 'horizontal',
            'show_categories' => 'yes',
            'show_price' => 'yes',
            'show_rating' => 'yes',
            'show_attributes' => 'yes',
            'show_sort' => 'yes'
        ), $atts);
        
        return $this->render_filter_bar($atts);
    }
    
    public function category_filter_shortcode($atts) {
        $atts = shortcode_atts(array(
            'style' => 'list',
            'show_count' => 'yes',
            'hide_empty' => 'yes',
            'parent_id' => 0
        ), $atts);
        
        return $this->render_category_filter($atts);
    }
    
    private function render_filter_bar($args = array()) {
        $defaults = array(
            'style' => 'horizontal',
            'show_categories' => 'yes',
            'show_price' => 'yes',
            'show_rating' => 'yes',
            'show_attributes' => 'yes',
            'show_sort' => 'yes'
        );
        
        $args = wp_parse_args($args, $defaults);
        
        ob_start();
        ?>
        <div class="alam-filter-bar <?php echo esc_attr($args['style']); ?>">
            <div class="alam-filter-container">
                <div class="alam-filter-toggle">
                    <button class="alam-filter-toggle-btn">
                        <svg width="20" height="20" viewBox="0 0 20 20">
                            <path d="M3 4h14M7 8h6M9 12h2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>الفلاتر</span>
                    </button>
                </div>
                
                <div class="alam-filter-content">
                    <div class="alam-filter-header">
                        <h3>تصفية المنتجات</h3>
                        <button class="alam-filter-close">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="alam-filter-groups">
                        <?php if ($args['show_categories'] === 'yes'): ?>
                            <div class="alam-filter-group">
                                <h4>الفئات</h4>
                                <div class="alam-filter-items">
                                    <?php echo $this->get_category_filters(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($args['show_price'] === 'yes'): ?>
                            <div class="alam-filter-group">
                                <h4>السعر</h4>
                                <div class="alam-filter-items">
                                    <?php echo $this->get_price_filter(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($args['show_rating'] === 'yes'): ?>
                            <div class="alam-filter-group">
                                <h4>التقييم</h4>
                                <div class="alam-filter-items">
                                    <?php echo $this->get_rating_filter(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($args['show_attributes'] === 'yes'): ?>
                            <?php echo $this->get_attribute_filters(); ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="alam-filter-actions">
                        <button class="alam-filter-apply btn btn-primary">تطبيق الفلاتر</button>
                        <button class="alam-filter-clear btn btn-outline">مسح الكل</button>
                    </div>
                </div>
                
                <?php if ($args['show_sort'] === 'yes'): ?>
                    <div class="alam-sort-container">
                        <label for="alam-sort-select">ترتيب حسب:</label>
                        <select id="alam-sort-select" name="orderby">
                            <option value="menu_order" <?php selected(get_query_var('orderby'), 'menu_order'); ?>>الترتيب الافتراضي</option>
                            <option value="popularity" <?php selected(get_query_var('orderby'), 'popularity'); ?>>الأكثر شعبية</option>
                            <option value="rating" <?php selected(get_query_var('orderby'), 'rating'); ?>>الأعلى تقييماً</option>
                            <option value="date" <?php selected(get_query_var('orderby'), 'date'); ?>>الأحدث</option>
                            <option value="price" <?php selected(get_query_var('orderby'), 'price'); ?>>السعر: من الأقل للأعلى</option>
                            <option value="price-desc" <?php selected(get_query_var('orderby'), 'price-desc'); ?>>السعر: من الأعلى للأقل</option>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="alam-active-filters"></div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    private function get_category_filters() {
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => 0
        ));
        
        if (empty($categories) || is_wp_error($categories)) {
            return '';
        }
        
        $output = '<ul class="alam-category-filter-list">';
        
        foreach ($categories as $category) {
            $count = $category->count;
            $selected = is_product_category($category->slug) ? 'checked' : '';
            
            $output .= '<li class="alam-filter-item">';
            $output .= '<label class="alam-filter-label">';
            $output .= '<input type="checkbox" name="product_cat[]" value="' . esc_attr($category->slug) . '" ' . $selected . '>';
            $output .= '<span class="alam-filter-text">' . esc_html($category->name) . '</span>';
            $output .= '<span class="alam-filter-count">(' . $count . ')</span>';
            $output .= '</label>';
            
            // Get subcategories
            $subcategories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => $category->term_id
            ));
            
            if (!empty($subcategories) && !is_wp_error($subcategories)) {
                $output .= '<ul class="alam-subcategory-list">';
                foreach ($subcategories as $subcategory) {
                    $sub_selected = is_product_category($subcategory->slug) ? 'checked' : '';
                    $output .= '<li>';
                    $output .= '<label class="alam-filter-label">';
                    $output .= '<input type="checkbox" name="product_cat[]" value="' . esc_attr($subcategory->slug) . '" ' . $sub_selected . '>';
                    $output .= '<span class="alam-filter-text">' . esc_html($subcategory->name) . '</span>';
                    $output .= '<span class="alam-filter-count">(' . $subcategory->count . ')</span>';
                    $output .= '</label>';
                    $output .= '</li>';
                }
                $output .= '</ul>';
            }
            
            $output .= '</li>';
        }
        
        $output .= '</ul>';
        
        return $output;
    }
    
    private function get_price_filter() {
        $min_price = 0;
        $max_price = 10000;
        
        // Get actual price range from products
        global $wpdb;
        $sql = "SELECT MIN(CAST(meta_value AS UNSIGNED)) as min_price, MAX(CAST(meta_value AS UNSIGNED)) as max_price 
                FROM {$wpdb->postmeta} 
                WHERE meta_key = '_price' 
                AND meta_value != ''";
        $prices = $wpdb->get_row($sql);
        
        if ($prices) {
            $min_price = intval($prices->min_price);
            $max_price = intval($prices->max_price);
        }
        
        $current_min = get_query_var('min_price', $min_price);
        $current_max = get_query_var('max_price', $max_price);
        
        $output = '<div class="alam-price-filter">';
        $output .= '<div class="alam-price-range">';
        $output .= '<input type="range" name="min_price" class="alam-price-slider" min="' . $min_price . '" max="' . $max_price . '" value="' . $current_min . '" data-type="min">';
        $output .= '<input type="range" name="max_price" class="alam-price-slider" min="' . $min_price . '" max="' . $max_price . '" value="' . $current_max . '" data-type="max">';
        $output .= '</div>';
        $output .= '<div class="alam-price-inputs">';
        $output .= '<input type="number" name="min_price_input" placeholder="أقل سعر" value="' . $current_min . '" min="' . $min_price . '" max="' . $max_price . '">';
        $output .= '<span>-</span>';
        $output .= '<input type="number" name="max_price_input" placeholder="أعلى سعر" value="' . $current_max . '" min="' . $min_price . '" max="' . $max_price . '">';
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    private function get_rating_filter() {
        $output = '<ul class="alam-rating-filter-list">';
        
        for ($i = 5; $i >= 1; $i--) {
            $output .= '<li class="alam-filter-item">';
            $output .= '<label class="alam-filter-label">';
            $output .= '<input type="checkbox" name="rating[]" value="' . $i . '">';
            $output .= '<span class="alam-filter-stars">';
            
            for ($j = 1; $j <= 5; $j++) {
                if ($j <= $i) {
                    $output .= '<span class="star filled">★</span>';
                } else {
                    $output .= '<span class="star">☆</span>';
                }
            }
            
            $output .= '</span>';
            $output .= '<span class="alam-filter-text">و أعلى</span>';
            $output .= '</label>';
            $output .= '</li>';
        }
        
        $output .= '</ul>';
        
        return $output;
    }
    
    private function get_attribute_filters() {
        $attributes = wc_get_attribute_taxonomies();
        
        if (empty($attributes)) {
            return '';
        }
        
        $output = '';
        
        foreach ($attributes as $attribute) {
            $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
            $terms = get_terms(array(
                'taxonomy' => $taxonomy,
                'hide_empty' => true
            ));
            
            if (empty($terms) || is_wp_error($terms)) {
                continue;
            }
            
            $output .= '<div class="alam-filter-group">';
            $output .= '<h4>' . esc_html($attribute->attribute_label) . '</h4>';
            $output .= '<div class="alam-filter-items">';
            $output .= '<ul class="alam-attribute-filter-list">';
            
            foreach ($terms as $term) {
                $output .= '<li class="alam-filter-item">';
                $output .= '<label class="alam-filter-label">';
                $output .= '<input type="checkbox" name="' . esc_attr($taxonomy) . '[]" value="' . esc_attr($term->slug) . '">';
                $output .= '<span class="alam-filter-text">' . esc_html($term->name) . '</span>';
                $output .= '<span class="alam-filter-count">(' . $term->count . ')</span>';
                $output .= '</label>';
                $output .= '</li>';
            }
            
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        return $output;
    }
    
    public function ajax_filter_products() {
        check_ajax_referer('alam_filter_nonce', 'nonce');
        
        $filters = $_POST['filters'];
        $page = intval($_POST['page']) ?: 1;
        $per_page = intval($_POST['per_page']) ?: get_option('posts_per_page');
        
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'paged' => $page,
            'meta_query' => array(),
            'tax_query' => array()
        );
        
        // Apply category filter
        if (!empty($filters['product_cat'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $filters['product_cat'],
                'operator' => 'IN'
            );
        }
        
        // Apply price filter
        if (!empty($filters['min_price']) || !empty($filters['max_price'])) {
            $price_query = array(
                'key' => '_price',
                'type' => 'NUMERIC',
                'compare' => 'BETWEEN'
            );
            
            $min = !empty($filters['min_price']) ? floatval($filters['min_price']) : 0;
            $max = !empty($filters['max_price']) ? floatval($filters['max_price']) : 999999;
            
            $price_query['value'] = array($min, $max);
            $args['meta_query'][] = $price_query;
        }
        
        // Apply rating filter
        if (!empty($filters['rating'])) {
            $args['meta_query'][] = array(
                'key' => '_wc_average_rating',
                'value' => min($filters['rating']),
                'type' => 'NUMERIC',
                'compare' => '>='
            );
        }
        
        // Apply attribute filters
        foreach ($filters as $key => $value) {
            if (strpos($key, 'pa_') === 0 && !empty($value)) {
                $args['tax_query'][] = array(
                    'taxonomy' => $key,
                    'field' => 'slug',
                    'terms' => $value,
                    'operator' => 'IN'
                );
            }
        }
        
        // Apply sorting
        if (!empty($filters['orderby'])) {
            switch ($filters['orderby']) {
                case 'price':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'ASC';
                    break;
                case 'price-desc':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'popularity':
                    $args['meta_key'] = 'total_sales';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'rating':
                    $args['meta_key'] = '_wc_average_rating';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'date':
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                    break;
                default:
                    $args['orderby'] = 'menu_order';
                    $args['order'] = 'ASC';
            }
        }
        
        // Set relation for multiple tax queries
        if (count($args['tax_query']) > 1) {
            $args['tax_query']['relation'] = 'AND';
        }
        
        // Set relation for multiple meta queries
        if (count($args['meta_query']) > 1) {
            $args['meta_query']['relation'] = 'AND';
        }
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            ob_start();
            
            woocommerce_product_loop_start();
            
            while ($query->have_posts()) {
                $query->the_post();
                wc_get_template_part('content', 'product');
            }
            
            woocommerce_product_loop_end();
            
            $products_html = ob_get_clean();
            
            wp_send_json_success(array(
                'products' => $products_html,
                'found_posts' => $query->found_posts,
                'max_pages' => $query->max_num_pages,
                'current_page' => $page
            ));
        } else {
            wp_send_json_error(array(
                'message' => 'لا توجد منتجات تطابق الفلاتر المحددة'
            ));
        }
        
        wp_reset_postdata();
    }
}

new Alam_Filter_System();