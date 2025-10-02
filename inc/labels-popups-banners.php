<?php
/**
 * Labels, Popups and Banners System
 * نظام الشارات والنوافذ المنبثقة واللافتات التفاعلية
 * 
 * @package AlamAlAnika
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_Labels_Popups_Banners {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Banner hooks
        add_action('wp_footer', array($this, 'add_promotional_banners'));
        add_action('woocommerce_before_main_content', array($this, 'add_announcement_banner'));
        
        // Label hooks
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'add_product_labels'), 15);
        add_action('woocommerce_single_product_summary', array($this, 'add_single_product_labels'), 6);
        
        // Popup hooks
        add_action('wp_footer', array($this, 'add_popup_containers'));
        
        // AJAX hooks
        add_action('wp_ajax_dismiss_banner', array($this, 'ajax_dismiss_banner'));
        add_action('wp_ajax_nopriv_dismiss_banner', array($this, 'ajax_dismiss_banner'));
        add_action('wp_ajax_show_popup', array($this, 'ajax_show_popup'));
        add_action('wp_ajax_nopriv_show_popup', array($this, 'ajax_show_popup'));
        
        // Custom meta boxes for product labels
        add_action('add_meta_boxes', array($this, 'add_product_label_meta_boxes'));
        add_action('save_post', array($this, 'save_product_label_meta'));
        
        // Customizer integration
        add_action('customize_register', array($this, 'customize_register'));
    }
    
    public function init() {
        // Register custom post type for banners if needed
        $this->register_banner_post_type();
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script(
            'alam-labels-popups',
            get_template_directory_uri() . '/assets/js/labels-popups-banners.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_enqueue_style(
            'alam-labels-popups',
            get_template_directory_uri() . '/assets/css/labels-popups-banners.css',
            array(),
            '1.0.0'
        );
        
        wp_localize_script('alam-labels-popups', 'alamBanners', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alam_banners_nonce'),
            'messages' => array(
                'limited_offer' => __('عرض محدود!', 'alam-al-anika'),
                'new_arrival' => __('وصل حديثاً', 'alam-al-anika'),
                'best_seller' => __('الأكثر مبيعاً', 'alam-al-anika'),
                'sale' => __('تخفيض', 'alam-al-anika'),
                'exclusive' => __('حصري', 'alam-al-anika'),
                'trending' => __('رائج', 'alam-al-anika'),
                'recommended' => __('موصى به', 'alam-al-anika'),
                'premium' => __('مميز', 'alam-al-anika'),
                'close' => __('إغلاق', 'alam-al-anika'),
                'shop_now' => __('تسوق الآن', 'alam-al-anika'),
                'learn_more' => __('اعرف المزيد', 'alam-al-anika')
            ),
            'settings' => array(
                'popup_delay' => get_theme_mod('popup_delay', 3000),
                'banner_auto_hide' => get_theme_mod('banner_auto_hide', false),
                'show_exit_intent' => get_theme_mod('show_exit_intent', true)
            )
        ));
    }
    
    public function register_banner_post_type() {
        $args = array(
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'themes.php',
            'supports' => array('title', 'editor', 'thumbnail'),
            'labels' => array(
                'name' => __('اللافتات الإعلانية', 'alam-al-anika'),
                'singular_name' => __('لافتة', 'alam-al-anika'),
                'add_new' => __('إضافة لافتة جديدة', 'alam-al-anika'),
                'add_new_item' => __('إضافة لافتة جديدة', 'alam-al-anika'),
                'edit_item' => __('تحرير اللافتة', 'alam-al-anika'),
                'new_item' => __('لافتة جديدة', 'alam-al-anika'),
                'all_items' => __('جميع اللافتات', 'alam-al-anika'),
                'view_item' => __('عرض اللافتة', 'alam-al-anika'),
                'search_items' => __('البحث في اللافتات', 'alam-al-anika'),
                'not_found' => __('لم يتم العثور على لافتات', 'alam-al-anika'),
                'not_found_in_trash' => __('لا توجد لافتات في سلة المهملات', 'alam-al-anika')
            )
        );
        
        register_post_type('alam_banner', $args);
    }
    
    public function add_product_label_meta_boxes() {
        add_meta_box(
            'alam_product_labels',
            __('شارات المنتج', 'alam-al-anika'),
            array($this, 'product_labels_meta_box'),
            'product',
            'side',
            'high'
        );
    }
    
    public function product_labels_meta_box($post) {
        wp_nonce_field('alam_product_labels_nonce', 'alam_product_labels_nonce');
        
        $labels = get_post_meta($post->ID, '_alam_product_labels', true) ?: array();
        $custom_label = get_post_meta($post->ID, '_alam_custom_label', true);
        $label_color = get_post_meta($post->ID, '_alam_label_color', true) ?: '#667eea';
        
        $available_labels = array(
            'new' => __('جديد', 'alam-al-anika'),
            'hot' => __('مطلوب', 'alam-al-anika'),
            'bestseller' => __('الأكثر مبيعاً', 'alam-al-anika'),
            'trending' => __('رائج', 'alam-al-anika'),
            'exclusive' => __('حصري', 'alam-al-anika'),
            'premium' => __('مميز', 'alam-al-anika'),
            'recommended' => __('موصى به', 'alam-al-anika'),
            'limited' => __('كمية محدودة', 'alam-al-anika')
        );
        
        ?>
        <div class="alam-product-labels-meta">
            <p><strong><?php _e('اختر الشارات:', 'alam-al-anika'); ?></strong></p>
            
            <?php foreach ($available_labels as $key => $label): ?>
            <label style="display: block; margin-bottom: 8px;">
                <input type="checkbox" name="alam_product_labels[]" value="<?php echo $key; ?>" 
                       <?php checked(in_array($key, $labels)); ?>>
                <?php echo $label; ?>
            </label>
            <?php endforeach; ?>
            
            <hr style="margin: 15px 0;">
            
            <p><strong><?php _e('شارة مخصصة:', 'alam-al-anika'); ?></strong></p>
            <input type="text" name="alam_custom_label" value="<?php echo esc_attr($custom_label); ?>" 
                   placeholder="<?php _e('نص الشارة المخصصة', 'alam-al-anika'); ?>" style="width: 100%; margin-bottom: 10px;">
            
            <p><strong><?php _e('لون الشارة:', 'alam-al-anika'); ?></strong></p>
            <input type="color" name="alam_label_color" value="<?php echo esc_attr($label_color); ?>" style="width: 100%;">
        </div>
        <?php
    }
    
    public function save_product_label_meta($post_id) {
        if (!isset($_POST['alam_product_labels_nonce']) || 
            !wp_verify_nonce($_POST['alam_product_labels_nonce'], 'alam_product_labels_nonce')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $labels = isset($_POST['alam_product_labels']) ? $_POST['alam_product_labels'] : array();
        $custom_label = sanitize_text_field($_POST['alam_custom_label'] ?? '');
        $label_color = sanitize_hex_color($_POST['alam_label_color'] ?? '#667eea');
        
        update_post_meta($post_id, '_alam_product_labels', $labels);
        update_post_meta($post_id, '_alam_custom_label', $custom_label);
        update_post_meta($post_id, '_alam_label_color', $label_color);
    }
    
    public function add_product_labels() {
        global $product;
        
        if (!$product) return;
        
        $product_id = $product->get_id();
        $labels = get_post_meta($product_id, '_alam_product_labels', true) ?: array();
        $custom_label = get_post_meta($product_id, '_alam_custom_label', true);
        $label_color = get_post_meta($product_id, '_alam_label_color', true) ?: '#667eea';
        
        // Auto-generated labels
        $auto_labels = $this->get_auto_labels($product);
        $all_labels = array_merge($labels, $auto_labels);
        
        if (empty($all_labels) && empty($custom_label)) {
            return;
        }
        
        ?>
        <div class="alam-product-labels">
            <?php
            $label_texts = array(
                'new' => '🆕 جديد',
                'hot' => '🔥 مطلوب',
                'bestseller' => '👑 الأكثر مبيعاً',
                'trending' => '📈 رائج',
                'exclusive' => '💎 حصري',
                'premium' => '⭐ مميز',
                'recommended' => '👍 موصى به',
                'limited' => '⏰ كمية محدودة',
                'sale' => '🏷️ تخفيض',
                'out_of_stock' => '❌ نفد المخزون'
            );
            
            foreach ($all_labels as $label):
                if (isset($label_texts[$label])):
            ?>
            <span class="product-label product-label-<?php echo $label; ?>" style="background: <?php echo $label_color; ?>">
                <?php echo $label_texts[$label]; ?>
            </span>
            <?php 
                endif;
            endforeach; 
            
            if (!empty($custom_label)):
            ?>
            <span class="product-label product-label-custom" style="background: <?php echo $label_color; ?>">
                ✨ <?php echo esc_html($custom_label); ?>
            </span>
            <?php endif; ?>
        </div>
        <?php
    }
    
    public function add_single_product_labels() {
        global $product;
        
        if (!$product) return;
        
        $this->add_product_labels();
    }
    
    private function get_auto_labels($product) {
        $labels = array();
        
        // Sale label
        if ($product->is_on_sale()) {
            $labels[] = 'sale';
        }
        
        // Stock status
        if (!$product->is_in_stock()) {
            $labels[] = 'out_of_stock';
        }
        
        // New product (within last 30 days)
        $created_date = get_the_date('U', $product->get_id());
        if ($created_date > strtotime('-30 days')) {
            $labels[] = 'new';
        }
        
        // Bestseller (based on sales)
        $sales = get_post_meta($product->get_id(), 'total_sales', true);
        if ($sales && $sales > 50) {
            $labels[] = 'bestseller';
        }
        
        return $labels;
    }
    
    public function add_announcement_banner() {
        $banner_content = get_theme_mod('announcement_banner_content', '');
        $banner_type = get_theme_mod('announcement_banner_type', 'info');
        $banner_show = get_theme_mod('show_announcement_banner', false);
        
        if (!$banner_show || empty($banner_content)) {
            return;
        }
        
        ?>
        <div class="alam-announcement-banner banner-<?php echo esc_attr($banner_type); ?>" data-banner-id="announcement">
            <div class="container">
                <div class="banner-content">
                    <div class="banner-icon">
                        <?php
                        $icons = array(
                            'info' => '📢',
                            'sale' => '🏷️',
                            'warning' => '⚠️',
                            'success' => '✅',
                            'new' => '🆕'
                        );
                        echo $icons[$banner_type] ?? '📢';
                        ?>
                    </div>
                    <div class="banner-text">
                        <?php echo wp_kses_post($banner_content); ?>
                    </div>
                    <div class="banner-actions">
                        <?php
                        $banner_link = get_theme_mod('announcement_banner_link', '');
                        $banner_link_text = get_theme_mod('announcement_banner_link_text', 'تسوق الآن');
                        
                        if (!empty($banner_link)):
                        ?>
                        <a href="<?php echo esc_url($banner_link); ?>" class="banner-cta">
                            <?php echo esc_html($banner_link_text); ?>
                        </a>
                        <?php endif; ?>
                        
                        <button class="banner-close" data-banner-id="announcement">
                            <span>×</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function add_promotional_banners() {
        // Exit intent popup
        $this->add_exit_intent_popup();
        
        // Newsletter popup
        $this->add_newsletter_popup();
        
        // Floating sale banner
        $this->add_floating_sale_banner();
        
        // Cookie notice
        $this->add_cookie_notice();
    }
    
    private function add_exit_intent_popup() {
        if (!get_theme_mod('show_exit_intent', true)) {
            return;
        }
        
        ?>
        <div id="exit-intent-popup" class="alam-popup exit-intent-popup">
            <div class="popup-overlay"></div>
            <div class="popup-container">
                <div class="popup-header">
                    <h3>🛑 انتظر! لا تفوت العرض</h3>
                    <button class="popup-close">&times;</button>
                </div>
                <div class="popup-content">
                    <div class="popup-icon">🎁</div>
                    <h4>احصل على خصم 15% على طلبك الأول</h4>
                    <p>أدخل بريدك الإلكتروني واحصل على كود الخصم فوراً</p>
                    
                    <form class="popup-form" id="exit-intent-form">
                        <div class="form-group">
                            <input type="email" placeholder="بريدك الإلكتروني" required>
                            <button type="submit">احصل على الخصم</button>
                        </div>
                    </form>
                    
                    <div class="popup-features">
                        <div class="feature">
                            <span class="feature-icon">✅</span>
                            <span>شحن مجاني للطلبات فوق 200 ريال</span>
                        </div>
                        <div class="feature">
                            <span class="feature-icon">✅</span>
                            <span>ضمان الاسترداد خلال 30 يوم</span>
                        </div>
                        <div class="feature">
                            <span class="feature-icon">✅</span>
                            <span>دعم فني على مدار الساعة</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    private function add_newsletter_popup() {
        if (!get_theme_mod('show_newsletter_popup', true)) {
            return;
        }
        
        ?>
        <div id="newsletter-popup" class="alam-popup newsletter-popup">
            <div class="popup-overlay"></div>
            <div class="popup-container">
                <div class="popup-content">
                    <button class="popup-close">&times;</button>
                    
                    <div class="popup-visual">
                        <div class="newsletter-icon">📧</div>
                        <div class="floating-elements">
                            <div class="floating-element">💎</div>
                            <div class="floating-element">🎁</div>
                            <div class="floating-element">⭐</div>
                        </div>
                    </div>
                    
                    <div class="popup-text">
                        <h3>🌟 انضم لعائلتنا المميزة</h3>
                        <p>كن أول من يعرف بالعروض الحصرية والمنتجات الجديدة</p>
                        
                        <form class="newsletter-form" id="newsletter-form">
                            <div class="form-group">
                                <input type="email" placeholder="أدخل بريدك الإلكتروني" required>
                                <button type="submit">اشترك الآن</button>
                            </div>
                        </form>
                        
                        <div class="popup-benefits">
                            <span>🎯 عروض حصرية</span>
                            <span>🆕 منتجات جديدة</span>
                            <span>💰 كوبونات خصم</span>
                        </div>
                        
                        <p class="popup-note">لن نرسل لك رسائل مزعجة، وعد!</p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    private function add_floating_sale_banner() {
        if (!get_theme_mod('show_floating_sale_banner', true)) {
            return;
        }
        
        ?>
        <div id="floating-sale-banner" class="floating-sale-banner">
            <div class="banner-content">
                <div class="banner-icon">🔥</div>
                <div class="banner-text">
                    <strong>عرض البرق!</strong>
                    <span>خصم يصل إلى 50% - ينتهي خلال:</span>
                </div>
                <div class="banner-countdown" id="sale-countdown">
                    <div class="countdown-item">
                        <span class="countdown-number">02</span>
                        <span class="countdown-label">ساعة</span>
                    </div>
                    <div class="countdown-separator">:</div>
                    <div class="countdown-item">
                        <span class="countdown-number">30</span>
                        <span class="countdown-label">دقيقة</span>
                    </div>
                </div>
                <button class="banner-cta">تسوق الآن</button>
                <button class="banner-minimize">−</button>
            </div>
        </div>
        <?php
    }
    
    private function add_cookie_notice() {
        if (!get_theme_mod('show_cookie_notice', true)) {
            return;
        }
        
        ?>
        <div id="cookie-notice" class="cookie-notice" data-banner-id="cookie">
            <div class="cookie-content">
                <div class="cookie-icon">🍪</div>
                <div class="cookie-text">
                    <p>نستخدم ملفات تعريف الارتباط لتحسين تجربة التصفح وتقديم أفضل خدمة لك.</p>
                </div>
                <div class="cookie-actions">
                    <button class="cookie-accept">قبول الكل</button>
                    <button class="cookie-customize">تخصيص</button>
                    <button class="cookie-decline">رفض</button>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function add_popup_containers() {
        ?>
        <!-- Popup containers will be added here by individual methods -->
        <?php
    }
    
    public function ajax_dismiss_banner() {
        check_ajax_referer('alam_banners_nonce', 'nonce');
        
        $banner_id = sanitize_text_field($_POST['banner_id']);
        
        // Set cookie to remember dismissal
        setcookie('alam_dismissed_' . $banner_id, '1', time() + (30 * 24 * 60 * 60), '/');
        
        wp_send_json_success('Banner dismissed');
    }
    
    public function ajax_show_popup() {
        check_ajax_referer('alam_banners_nonce', 'nonce');
        
        $popup_type = sanitize_text_field($_POST['popup_type']);
        
        // Logic to show specific popup
        wp_send_json_success('Popup shown');
    }
    
    public function customize_register($wp_customize) {
        // Announcement Banner Section
        $wp_customize->add_section('alam_announcement_banner', array(
            'title' => __('شريط الإعلانات', 'alam-al-anika'),
            'description' => __('إعدادات شريط الإعلانات العلوي', 'alam-al-anika'),
            'priority' => 140
        ));
        
        // Show announcement banner
        $wp_customize->add_setting('show_announcement_banner', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('show_announcement_banner', array(
            'label' => __('إظهار شريط الإعلانات', 'alam-al-anika'),
            'section' => 'alam_announcement_banner',
            'type' => 'checkbox'
        ));
        
        // Banner content
        $wp_customize->add_setting('announcement_banner_content', array(
            'default' => 'عرض خاص: خصم 25% على جميع المنتجات لفترة محدودة!',
            'sanitize_callback' => 'wp_kses_post'
        ));
        
        $wp_customize->add_control('announcement_banner_content', array(
            'label' => __('محتوى الإعلان', 'alam-al-anika'),
            'section' => 'alam_announcement_banner',
            'type' => 'textarea'
        ));
        
        // Banner type
        $wp_customize->add_setting('announcement_banner_type', array(
            'default' => 'info',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('announcement_banner_type', array(
            'label' => __('نوع الإعلان', 'alam-al-anika'),
            'section' => 'alam_announcement_banner',
            'type' => 'select',
            'choices' => array(
                'info' => __('معلوماتي', 'alam-al-anika'),
                'sale' => __('تخفيضات', 'alam-al-anika'),
                'warning' => __('تحذير', 'alam-al-anika'),
                'success' => __('نجاح', 'alam-al-anika'),
                'new' => __('جديد', 'alam-al-anika')
            )
        ));
        
        // Banner link
        $wp_customize->add_setting('announcement_banner_link', array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw'
        ));
        
        $wp_customize->add_control('announcement_banner_link', array(
            'label' => __('رابط الإعلان', 'alam-al-anika'),
            'section' => 'alam_announcement_banner',
            'type' => 'url'
        ));
        
        // Banner link text
        $wp_customize->add_setting('announcement_banner_link_text', array(
            'default' => 'تسوق الآن',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control('announcement_banner_link_text', array(
            'label' => __('نص رابط الإعلان', 'alam-al-anika'),
            'section' => 'alam_announcement_banner',
            'type' => 'text'
        ));
        
        // Popups Section
        $wp_customize->add_section('alam_popups', array(
            'title' => __('النوافذ المنبثقة', 'alam-al-anika'),
            'description' => __('إعدادات النوافذ المنبثقة والإشعارات', 'alam-al-anika'),
            'priority' => 141
        ));
        
        // Exit intent popup
        $wp_customize->add_setting('show_exit_intent', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('show_exit_intent', array(
            'label' => __('نافذة منع المغادرة', 'alam-al-anika'),
            'section' => 'alam_popups',
            'type' => 'checkbox'
        ));
        
        // Newsletter popup
        $wp_customize->add_setting('show_newsletter_popup', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('show_newsletter_popup', array(
            'label' => __('نافذة النشرة البريدية', 'alam-al-anika'),
            'section' => 'alam_popups',
            'type' => 'checkbox'
        ));
        
        // Floating sale banner
        $wp_customize->add_setting('show_floating_sale_banner', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('show_floating_sale_banner', array(
            'label' => __('شريط العروض العائم', 'alam-al-anika'),
            'section' => 'alam_popups',
            'type' => 'checkbox'
        ));
        
        // Cookie notice
        $wp_customize->add_setting('show_cookie_notice', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ));
        
        $wp_customize->add_control('show_cookie_notice', array(
            'label' => __('إشعار ملفات الارتباط', 'alam-al-anika'),
            'section' => 'alam_popups',
            'type' => 'checkbox'
        ));
        
        // Popup delay
        $wp_customize->add_setting('popup_delay', array(
            'default' => 3000,
            'sanitize_callback' => 'absint'
        ));
        
        $wp_customize->add_control('popup_delay', array(
            'label' => __('تأخير النوافذ المنبثقة (بالملي ثانية)', 'alam-al-anika'),
            'section' => 'alam_popups',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 1000,
                'max' => 30000,
                'step' => 500
            )
        ));
    }
}

// Initialize the system
new Alam_Labels_Popups_Banners();