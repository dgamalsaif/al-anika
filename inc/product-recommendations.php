<?php
/**
 * Interactive Product Recommendations System
 * منظومة التوصيات التفاعلية للمنتجات
 * 
 * @package AlamAlAnika
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_Product_Recommendations {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // AJAX hooks
        add_action('wp_ajax_get_related_products', array($this, 'ajax_get_related_products'));
        add_action('wp_ajax_nopriv_get_related_products', array($this, 'ajax_get_related_products'));
        add_action('wp_ajax_get_recommended_products', array($this, 'ajax_get_recommended_products'));
        add_action('wp_ajax_nopriv_get_recommended_products', array($this, 'ajax_get_recommended_products'));
        add_action('wp_ajax_get_pickup_products', array($this, 'ajax_get_pickup_products'));
        add_action('wp_ajax_nopriv_get_pickup_products', array($this, 'ajax_get_pickup_products'));
        add_action('wp_ajax_track_product_view', array($this, 'ajax_track_product_view'));
        add_action('wp_ajax_nopriv_track_product_view', array($this, 'ajax_track_product_view'));
        
        // Hooks for displaying recommendations
        add_action('woocommerce_single_product_summary', array($this, 'add_interactive_recommendations'), 25);
        add_action('woocommerce_after_single_product_summary', array($this, 'add_related_products_section'), 15);
        add_action('wp_footer', array($this, 'add_floating_recommendations'));
        
        // Custom post meta for tracking
        add_action('wp_head', array($this, 'track_product_views'));
    }
    
    public function init() {
        // Create custom table for tracking user behavior
        $this->create_tracking_table();
    }
    
    public function create_tracking_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'alam_product_views';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_identifier varchar(255) NOT NULL,
            product_id bigint(20) NOT NULL,
            view_count int(11) DEFAULT 1,
            last_viewed datetime DEFAULT CURRENT_TIMESTAMP,
            category_ids text,
            tags text,
            price_range varchar(50),
            PRIMARY KEY (id),
            KEY user_identifier (user_identifier),
            KEY product_id (product_id),
            KEY last_viewed (last_viewed)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script(
            'alam-product-recommendations',
            get_template_directory_uri() . '/assets/js/product-recommendations.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_enqueue_style(
            'alam-product-recommendations',
            get_template_directory_uri() . '/assets/css/product-recommendations.css',
            array(),
            '1.0.0'
        );
        
        // Swiper for product carousels
        wp_enqueue_script(
            'swiper-js',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            array(),
            '11.0.0',
            true
        );
        
        wp_enqueue_style(
            'swiper-css',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            array(),
            '11.0.0'
        );
        
        wp_localize_script('alam-product-recommendations', 'alamRecommendations', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alam_recommendations_nonce'),
            'current_product_id' => is_product() ? get_the_ID() : 0,
            'user_id' => get_current_user_id(),
            'messages' => array(
                'loading' => __('جاري التحميل...', 'alam-al-anika'),
                'error' => __('حدث خطأ', 'alam-al-anika'),
                'no_products' => __('لا توجد منتجات متاحة', 'alam-al-anika'),
                'add_to_cart' => __('أضف للسلة', 'alam-al-anika'),
                'view_product' => __('عرض المنتج', 'alam-al-anika'),
                'recommended_for_you' => __('موصى لك', 'alam-al-anika'),
                'related_products' => __('منتجات ذات صلة', 'alam-al-anika'),
                'pick_for_you' => __('مختار لك', 'alam-al-anika'),
                'based_on_history' => __('بناءً على تاريخ تصفحك', 'alam-al-anika'),
                'trending_now' => __('الأكثر رواجاً الآن', 'alam-al-anika')
            )
        ));
    }
    
    public function track_product_views() {
        if (is_product()) {
            $product_id = get_the_ID();
            ?>
            <script>
            jQuery(document).ready(function($) {
                // Track product view
                $.ajax({
                    url: alamRecommendations.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'alam_track_product_view',
                        nonce: alamRecommendations.nonce,
                        product_id: <?php echo $product_id; ?>
                    }
                });
            });
            </script>
            <?php
        }
    }
    
    public function ajax_track_product_view() {
        check_ajax_referer('alam_recommendations_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $user_identifier = $this->get_user_identifier();
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'alam_product_views';
        
        // Get product data
        $product = wc_get_product($product_id);
        if (!$product) {
            wp_send_json_error('Product not found');
        }
        
        $category_ids = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
        $tags = wp_get_post_terms($product_id, 'product_tag', array('fields' => 'names'));
        $price = $product->get_price();
        $price_range = $this->get_price_range($price);
        
        // Check if record exists
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table_name} WHERE user_identifier = %s AND product_id = %d",
            $user_identifier,
            $product_id
        ));
        
        if ($existing) {
            // Update view count
            $wpdb->update(
                $table_name,
                array(
                    'view_count' => $existing->view_count + 1,
                    'last_viewed' => current_time('mysql')
                ),
                array('id' => $existing->id),
                array('%d', '%s'),
                array('%d')
            );
        } else {
            // Insert new record
            $wpdb->insert(
                $table_name,
                array(
                    'user_identifier' => $user_identifier,
                    'product_id' => $product_id,
                    'view_count' => 1,
                    'last_viewed' => current_time('mysql'),
                    'category_ids' => json_encode($category_ids),
                    'tags' => json_encode($tags),
                    'price_range' => $price_range
                ),
                array('%s', '%d', '%d', '%s', '%s', '%s', '%s')
            );
        }
        
        wp_send_json_success('View tracked');
    }
    
    public function ajax_get_related_products() {
        check_ajax_referer('alam_recommendations_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $limit = intval($_POST['limit']) ?: 6;
        
        $related_products = $this->get_smart_related_products($product_id, $limit);
        
        ob_start();
        $this->render_product_cards($related_products, 'related');
        $html = ob_get_clean();
        
        wp_send_json_success(array(
            'html' => $html,
            'count' => count($related_products)
        ));
    }
    
    public function ajax_get_recommended_products() {
        check_ajax_referer('alam_recommendations_nonce', 'nonce');
        
        $limit = intval($_POST['limit']) ?: 8;
        $user_identifier = $this->get_user_identifier();
        
        $recommended_products = $this->get_personalized_recommendations($user_identifier, $limit);
        
        ob_start();
        $this->render_product_cards($recommended_products, 'recommended');
        $html = ob_get_clean();
        
        wp_send_json_success(array(
            'html' => $html,
            'count' => count($recommended_products)
        ));
    }
    
    public function ajax_get_pickup_products() {
        check_ajax_referer('alam_recommendations_nonce', 'nonce');
        
        $limit = intval($_POST['limit']) ?: 4;
        $user_identifier = $this->get_user_identifier();
        
        $pickup_products = $this->get_pickup_recommendations($user_identifier, $limit);
        
        ob_start();
        $this->render_product_cards($pickup_products, 'pickup');
        $html = ob_get_clean();
        
        wp_send_json_success(array(
            'html' => $html,
            'count' => count($pickup_products)
        ));
    }
    
    public function add_interactive_recommendations() {
        if (!is_product()) return;
        
        ?>
        <div class="alam-interactive-recommendations">
            <div class="recommendations-header">
                <h3>🎯 مختار لك خصيصاً</h3>
                <p>منتجات تناسب اهتماماتك</p>
            </div>
            <div class="pickup-products-container">
                <div class="pickup-products-slider swiper">
                    <div class="swiper-wrapper" id="pickup-products-wrapper">
                        <div class="swiper-slide loading-slide">
                            <div class="loading-spinner">
                                <div class="spinner"></div>
                                <p>جاري تحليل اهتماماتك...</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function add_related_products_section() {
        if (!is_product()) return;
        
        ?>
        <section class="alam-related-products-section">
            <div class="container">
                <div class="section-header">
                    <h2>🔗 منتجات ذات صلة</h2>
                    <p>قد تهمك أيضاً</p>
                </div>
                
                <div class="related-products-tabs">
                    <ul class="tabs-nav">
                        <li class="tab-item active" data-tab="smart-related">
                            <span class="tab-icon">🤖</span>
                            <span class="tab-text">توصيات ذكية</span>
                        </li>
                        <li class="tab-item" data-tab="same-category">
                            <span class="tab-icon">📂</span>
                            <span class="tab-text">نفس الفئة</span>
                        </li>
                        <li class="tab-item" data-tab="trending">
                            <span class="tab-icon">🔥</span>
                            <span class="tab-text">الأكثر رواجاً</span>
                        </li>
                    </ul>
                    
                    <div class="tabs-content">
                        <div class="tab-content active" id="smart-related">
                            <div class="products-slider swiper">
                                <div class="swiper-wrapper" id="smart-related-wrapper">
                                    <!-- Products will be loaded here -->
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                        
                        <div class="tab-content" id="same-category">
                            <div class="products-slider swiper">
                                <div class="swiper-wrapper" id="same-category-wrapper">
                                    <!-- Products will be loaded here -->
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                        
                        <div class="tab-content" id="trending">
                            <div class="products-slider swiper">
                                <div class="swiper-wrapper" id="trending-wrapper">
                                    <!-- Products will be loaded here -->
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
    
    public function add_floating_recommendations() {
        ?>
        <div id="floating-recommendations" class="floating-recommendations">
            <div class="floating-header">
                <span class="floating-title">💡 توصيات شخصية</span>
                <button class="floating-toggle">
                    <span class="toggle-icon">⬇️</span>
                </button>
            </div>
            <div class="floating-content">
                <div class="floating-slider swiper">
                    <div class="swiper-wrapper" id="floating-recommendations-wrapper">
                        <!-- Products will be loaded here -->
                    </div>
                </div>
                <div class="floating-footer">
                    <button class="refresh-recommendations">🔄 تحديث</button>
                    <button class="view-all-recommendations">عرض الكل</button>
                </div>
            </div>
        </div>
        <?php
    }
    
    private function get_smart_related_products($product_id, $limit = 6) {
        $product = wc_get_product($product_id);
        if (!$product) return array();
        
        // Get product categories and tags
        $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
        $tags = wp_get_post_terms($product_id, 'product_tag', array('fields' => 'ids'));
        
        // Smart query based on multiple factors
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $limit * 2, // Get more to filter
            'post__not_in' => array($product_id),
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock'
                )
            )
        );
        
        // Add tax query for categories and tags
        if (!empty($categories) || !empty($tags)) {
            $tax_query = array('relation' => 'OR');
            
            if (!empty($categories)) {
                $tax_query[] = array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $categories
                );
            }
            
            if (!empty($tags)) {
                $tax_query[] = array(
                    'taxonomy' => 'product_tag',
                    'field' => 'term_id',
                    'terms' => $tags
                );
            }
            
            $args['tax_query'] = $tax_query;
        }
        
        $products_query = new WP_Query($args);
        $products = array();
        
        if ($products_query->have_posts()) {
            while ($products_query->have_posts()) {
                $products_query->the_post();
                $products[] = wc_get_product(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        // Sort by relevance score
        usort($products, function($a, $b) use ($product, $categories, $tags) {
            $score_a = $this->calculate_relevance_score($a, $product, $categories, $tags);
            $score_b = $this->calculate_relevance_score($b, $product, $categories, $tags);
            return $score_b - $score_a;
        });
        
        return array_slice($products, 0, $limit);
    }
    
    private function get_personalized_recommendations($user_identifier, $limit = 8) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'alam_product_views';
        
        // Get user's viewing history
        $viewed_products = $wpdb->get_results($wpdb->prepare(
            "SELECT product_id, category_ids, tags, price_range, view_count 
             FROM {$table_name} 
             WHERE user_identifier = %s 
             ORDER BY last_viewed DESC, view_count DESC 
             LIMIT 20",
            $user_identifier
        ));
        
        if (empty($viewed_products)) {
            // Return trending products for new users
            return $this->get_trending_products($limit);
        }
        
        $user_categories = array();
        $user_tags = array();
        $user_price_ranges = array();
        
        // Analyze user preferences
        foreach ($viewed_products as $view) {
            $categories = json_decode($view->category_ids, true) ?: array();
            $tags = json_decode($view->tags, true) ?: array();
            
            foreach ($categories as $cat) {
                $user_categories[$cat] = ($user_categories[$cat] ?? 0) + $view->view_count;
            }
            
            foreach ($tags as $tag) {
                $user_tags[$tag] = ($user_tags[$tag] ?? 0) + $view->view_count;
            }
            
            $user_price_ranges[$view->price_range] = ($user_price_ranges[$view->price_range] ?? 0) + $view->view_count;
        }
        
        // Sort by preference strength
        arsort($user_categories);
        arsort($user_tags);
        arsort($user_price_ranges);
        
        // Get viewed product IDs to exclude
        $viewed_ids = array_column($viewed_products, 'product_id');
        
        // Build recommendation query
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $limit * 3,
            'post__not_in' => $viewed_ids,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock'
                )
            )
        );
        
        // Add preferred categories
        if (!empty($user_categories)) {
            $preferred_cats = array_slice(array_keys($user_categories), 0, 5);
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $preferred_cats
                )
            );
        }
        
        $products_query = new WP_Query($args);
        $recommended_products = array();
        
        if ($products_query->have_posts()) {
            while ($products_query->have_posts()) {
                $products_query->the_post();
                $recommended_products[] = wc_get_product(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        // Score and sort recommendations
        usort($recommended_products, function($a, $b) use ($user_categories, $user_tags, $user_price_ranges) {
            $score_a = $this->calculate_user_preference_score($a, $user_categories, $user_tags, $user_price_ranges);
            $score_b = $this->calculate_user_preference_score($b, $user_categories, $user_tags, $user_price_ranges);
            return $score_b - $score_a;
        });
        
        return array_slice($recommended_products, 0, $limit);
    }
    
    private function get_pickup_recommendations($user_identifier, $limit = 4) {
        // Get highly personalized picks
        $personalized = $this->get_personalized_recommendations($user_identifier, $limit * 2);
        
        // Filter for highest quality picks
        $quality_picks = array();
        foreach ($personalized as $product) {
            $rating = $product->get_average_rating();
            $review_count = $product->get_review_count();
            
            // Only include highly rated products
            if ($rating >= 4.0 || $review_count >= 10) {
                $quality_picks[] = $product;
            }
        }
        
        return array_slice($quality_picks, 0, $limit);
    }
    
    private function get_trending_products($limit = 8) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $limit,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock'
                )
            ),
            'orderby' => 'meta_value_num',
            'meta_key' => 'total_sales',
            'order' => 'DESC'
        );
        
        $products_query = new WP_Query($args);
        $products = array();
        
        if ($products_query->have_posts()) {
            while ($products_query->have_posts()) {
                $products_query->the_post();
                $products[] = wc_get_product(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $products;
    }
    
    private function calculate_relevance_score($product_a, $product_b, $shared_categories, $shared_tags) {
        $score = 0;
        
        // Category relevance
        $a_categories = wp_get_post_terms($product_a->get_id(), 'product_cat', array('fields' => 'ids'));
        $category_matches = count(array_intersect($a_categories, $shared_categories));
        $score += $category_matches * 10;
        
        // Tag relevance
        $a_tags = wp_get_post_terms($product_a->get_id(), 'product_tag', array('fields' => 'ids'));
        $tag_matches = count(array_intersect($a_tags, $shared_tags));
        $score += $tag_matches * 5;
        
        // Price proximity
        $price_diff = abs($product_a->get_price() - $product_b->get_price());
        if ($price_diff < 50) $score += 5;
        elseif ($price_diff < 100) $score += 3;
        elseif ($price_diff < 200) $score += 1;
        
        // Rating bonus
        if ($product_a->get_average_rating() >= 4.0) $score += 3;
        
        return $score;
    }
    
    private function calculate_user_preference_score($product, $user_categories, $user_tags, $user_price_ranges) {
        $score = 0;
        
        // Category preference
        $product_categories = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'ids'));
        foreach ($product_categories as $cat) {
            if (isset($user_categories[$cat])) {
                $score += $user_categories[$cat] * 2;
            }
        }
        
        // Price range preference
        $price_range = $this->get_price_range($product->get_price());
        if (isset($user_price_ranges[$price_range])) {
            $score += $user_price_ranges[$price_range];
        }
        
        // Quality indicators
        if ($product->get_average_rating() >= 4.0) $score += 5;
        if ($product->get_review_count() >= 10) $score += 3;
        if ($product->is_on_sale()) $score += 2;
        
        return $score;
    }
    
    private function get_price_range($price) {
        if ($price < 50) return 'budget';
        elseif ($price < 200) return 'mid';
        elseif ($price < 500) return 'premium';
        else return 'luxury';
    }
    
    private function render_product_cards($products, $type = 'default') {
        foreach ($products as $product) {
            $this->render_single_product_card($product, $type);
        }
    }
    
    private function render_single_product_card($product, $type = 'default') {
        $product_id = $product->get_id();
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium');
        $image_url = $image ? $image[0] : wc_placeholder_img_src();
        
        ?>
        <div class="swiper-slide product-recommendation-card <?php echo esc_attr($type); ?>" data-product-id="<?php echo $product_id; ?>">
            <div class="card-inner">
                <div class="card-image">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" loading="lazy">
                    
                    <?php if ($product->is_on_sale()): ?>
                    <div class="sale-badge">
                        <span>عرض</span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card-overlay">
                        <div class="quick-actions">
                            <button class="quick-view-btn" data-product-id="<?php echo $product_id; ?>">
                                <span class="icon">👁️</span>
                            </button>
                            <button class="add-to-wishlist-btn" data-product-id="<?php echo $product_id; ?>">
                                <span class="icon">❤️</span>
                            </button>
                            <button class="add-to-compare-btn" data-product-id="<?php echo $product_id; ?>">
                                <span class="icon">⚖️</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-content">
                    <h3 class="product-title">
                        <a href="<?php echo get_permalink($product_id); ?>">
                            <?php echo esc_html($product->get_name()); ?>
                        </a>
                    </h3>
                    
                    <div class="product-rating">
                        <?php
                        $rating = $product->get_average_rating();
                        $review_count = $product->get_review_count();
                        ?>
                        <div class="stars">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <span class="star <?php echo $i <= $rating ? 'filled' : ''; ?>">⭐</span>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-text">(<?php echo $review_count; ?>)</span>
                    </div>
                    
                    <div class="product-price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    
                    <div class="product-actions">
                        <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()): ?>
                            <button class="add-to-cart-btn" data-product-id="<?php echo $product_id; ?>">
                                <span class="icon">🛒</span>
                                <span class="text">أضف للسلة</span>
                            </button>
                        <?php else: ?>
                            <a href="<?php echo get_permalink($product_id); ?>" class="view-product-btn">
                                <span class="icon">👁️</span>
                                <span class="text">عرض المنتج</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($type === 'pickup'): ?>
                <div class="pickup-badge">
                    <span class="badge-icon">🎯</span>
                    <span class="badge-text">مختار لك</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    private function get_user_identifier() {
        if (is_user_logged_in()) {
            return 'user_' . get_current_user_id();
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            return 'guest_' . md5($ip . $user_agent);
        }
    }
}

// Initialize the recommendations system
new Alam_Product_Recommendations();