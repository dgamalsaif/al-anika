<?php
/**
 * حل شامل لجميع مشاكل Console في قالب Al-Anika
 * 
 * تعليمات التطبيق:
 * 1. أضف هذا الكود إلى ملف functions.php في قالب Al-Anika
 * 2. أو أنشئ ملف منفصل في مجلد inc/ واستدعه من functions.php
 * 3. تأكد من تحميل ملفات jQuery والموارد المطلوبة محلياً
 * 
 * @package Al_Anika_Theme
 * @version Console_Fix_1.0
 */

// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit;
}

class Al_Anika_Console_Fixes {
    
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * تهيئة الخطاطيف
     */
    private function init_hooks() {
        // إصلاح jQuery
        add_action('wp_enqueue_scripts', array($this, 'fix_jquery_loading'), 1);
        add_action('wp_head', array($this, 'jquery_fallback_check'), 999);
        
        // إصلاح CSP ومشاكل الموارد
        add_action('wp_enqueue_scripts', array($this, 'optimize_external_resources'), 5);
        add_action('wp_head', array($this, 'fix_font_loading'), 2);
        
        // تحسين الأداء
        add_action('wp_enqueue_scripts', array($this, 'optimize_scripts'), 15);
        add_action('wp_head', array($this, 'add_error_handling'), 1);
        
        // إصلاحات WooCommerce
        if (class_exists('WooCommerce')) {
            add_action('wp_enqueue_scripts', array($this, 'optimize_woocommerce'), 20);
        }
    }
    
    /**
     * إصلاح تحميل jQuery - محسن وأكثر استقراراً
     */
    public function fix_jquery_loading() {
        if (!is_admin()) {
            // التحقق من توفر jQuery محلياً أولاً
            $local_jquery = get_template_directory() . '/assets/js/jquery-3.7.1.min.js';
            
            if (file_exists($local_jquery)) {
                // استخدام نسخة محلية
                wp_deregister_script('jquery');
                wp_register_script('jquery', 
                    get_template_directory_uri() . '/assets/js/jquery-3.7.1.min.js', 
                    array(), 
                    '3.7.1', 
                    false
                );
                wp_enqueue_script('jquery');
            } else {
                // استخدام CDN كحل احتياطي
                wp_deregister_script('jquery');
                wp_register_script('jquery', 
                    'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', 
                    array(), 
                    '3.7.1', 
                    false
                );
                wp_enqueue_script('jquery');
                
                // إضافة integrity check للأمان
                wp_script_add_data('jquery', 'integrity', 'sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==');
                wp_script_add_data('jquery', 'crossorigin', 'anonymous');
            }
        }
    }
    
    /**
     * فحص احتياطي لـ jQuery - محسن ومحدود
     */
    public function jquery_fallback_check() {
        ?>
        <script>
        // فحص تحميل jQuery مع حل احتياطي (بدون console logs مفرطة)
        (function() {
            if (typeof jQuery === 'undefined') {
                var script = document.createElement('script');
                script.src = '<?php echo get_template_directory_uri(); ?>/assets/js/jquery-3.7.1.min.js';
                script.onload = function() {
                    document.dispatchEvent(new CustomEvent('jquery-loaded'));
                    // console log فقط في وضع التطوير
                    <?php if (defined('WP_DEBUG') && WP_DEBUG): ?>
                    console.log('Al-Anika: jQuery fallback loaded');
                    <?php endif; ?>
                };
                script.onerror = function() {
                    // استخدام jQuery من WordPress core كحل أخير
                    if (typeof wp !== 'undefined' && wp.domReady) {
                        document.dispatchEvent(new CustomEvent('jquery-loaded'));
                    }
                };
                document.head.appendChild(script);
            } else {
                document.dispatchEvent(new CustomEvent('jquery-loaded'));
                <?php if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')): ?>
                console.log('Al-Anika: jQuery loaded (' + jQuery.fn.jquery + ')');
                <?php endif; ?>
            }
        })();
        </script>
        <?php
    }
    
    /**
     * تحسين الموارد الخارجية
     */
    public function optimize_external_resources() {
        // إزالة موارد خارجية مشكلة
        wp_dequeue_style('swiper-bundle');
        wp_dequeue_script('swiper-bundle');
        
        // تحميل محلي آمن
        if (file_exists(get_template_directory() . '/assets/css/swiper-bundle.min.css')) {
            wp_enqueue_style('al-anika-swiper', 
                get_template_directory_uri() . '/assets/css/swiper-bundle.min.css', 
                array(), 
                AL_ANIKA_VERSION
            );
        }
        
        if (file_exists(get_template_directory() . '/assets/js/swiper-bundle.min.js')) {
            wp_enqueue_script('al-anika-swiper', 
                get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', 
                array('jquery'), 
                AL_ANIKA_VERSION, 
                true
            );
        }
    }
    
    /**
     * إصلاح تحميل الخطوط
     */
    public function fix_font_loading() {
        ?>
        <style>
        /* إصلاح خطوط القالب */
        @font-face {
            font-family: 'Al-Anika-Icons';
            src: url('<?php echo get_template_directory_uri(); ?>/assets/fonts/al-anika-icons.woff2') format('woff2');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
        </style>
        
        <!-- تحسين تحميل خطوط Google -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
        <noscript>
            <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        </noscript>
        <?php
    }
    
    /**
     * تحسين تحميل النصوص
     */
    public function optimize_scripts() {
        // إزالة نصوص غير ضرورية
        wp_dequeue_script('wp-embed');
        
        // تحميل النصوص الأساسية محسنة
        if (wp_script_is('al-anika-core', 'registered')) {
            wp_script_add_data('al-anika-core', 'defer', true);
        }
        
        // تجميع النصوص الصغيرة
        $this->inline_critical_js();
    }
    
    /**
     * نصوص حرجة مدمجة
     */
    private function inline_critical_js() {
        wp_add_inline_script('jquery', '
            // إصلاح مشكلة legacy identifier
            window.al_anika_legacy_support = true;
            
            // وظائف أساسية آمنة
            window.Al_Anika = window.Al_Anika || {};
            
            Al_Anika.init = function() {
                if (typeof jQuery !== "undefined") {
                    jQuery(document).ready(function($) {
                        console.log("Al-Anika: Theme initialized successfully");
                        
                        // إصلاح مشاكل WooCommerce العامة
                        if (typeof wc_add_to_cart_params !== "undefined") {
                            $(document.body).trigger("wc_fragment_refresh");
                        }
                    });
                }
            };
            
            // تشغيل التهيئة
            document.addEventListener("jquery-loaded", Al_Anika.init);
            if (typeof jQuery !== "undefined") {
                Al_Anika.init();
            }
        ');
    }
    
    /**
     * إضافة معالجة الأخطاء
     */
    public function add_error_handling() {
        ?>
        <script>
        // نظام معالجة أخطاء محسن
        (function() {
            var errorCount = 0;
            var maxErrors = 5;
            
            window.addEventListener('error', function(e) {
                if (errorCount >= maxErrors) return;
                
                errorCount++;
                console.group('🔴 Al-Anika Error #' + errorCount);
                console.error({
                    message: e.message,
                    filename: e.filename,
                    line: e.lineno,
                    column: e.colno,
                    timestamp: new Date().toISOString()
                });
                console.groupEnd();
            });
            
            window.addEventListener('unhandledrejection', function(e) {
                console.error('🔴 Al-Anika Promise Rejection:', e.reason);
            });
        })();
        </script>
        <?php
    }
    
    /**
     * تحسين WooCommerce
     */
    public function optimize_woocommerce() {
        // تحميل محسن لـ WooCommerce scripts
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            // إزالة WooCommerce CSS/JS من الصفحات غير التجارية
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-layout'); 
            wp_dequeue_style('woocommerce-smallscreen');
            wp_dequeue_script('wc-add-to-cart');
            wp_dequeue_script('wc-cart-fragments');
        }
    }
}

// تفعيل الإصلاحات
new Al_Anika_Console_Fixes();

/**
 * إضافة معلومات Debug (فقط للمدراء وفي وضع التطوير)  - FIXED v9.2.3
 */
if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
    add_action('wp_footer', function() {
        ?>
        <div id="al-anika-admin-debug" style="position:fixed;bottom:10px;left:10px;background:#000;color:#fff;padding:10px;font-size:12px;z-index:99999;border-radius:5px;max-width:250px;opacity:0.9;">
            <strong>🔧 Al-Anika Debug (Admin Only):</strong><br>
            Version: <?php echo defined('AL_ANIKA_VERSION') ? AL_ANIKA_VERSION : '9.2.3'; ?><br>
            jQuery: <span id="admin-jquery-status">Checking...</span><br>
            <small>Click to hide • Auto-hide in 10s</small>
        </div>
        <script>
        setTimeout(function() {
            var adminDebug = document.getElementById('al-anika-admin-debug');
            var status = document.getElementById('admin-jquery-status');
            
            if (adminDebug && status) {
                status.textContent = typeof jQuery !== 'undefined' ? '✅ v' + jQuery.fn.jquery : '❌ Not loaded';
                
                adminDebug.addEventListener('click', function() {
                    this.style.display = 'none';
                });
                
                // إخفاء تلقائياً بعد 10 ثوان
                setTimeout(function() {
                    if (adminDebug) {
                        adminDebug.style.display = 'none';
                    }
                }, 10000);
            }
        }, 1000);
        </script>
        <?php
    });
} else {
    // التأكد من إخفاء أي رسائل debug عن المستخدمين العاديين
    add_action('wp_head', function() {
        echo '<style>#al-anika-debug, [id*="debug"], [class*="debug"] { display: none !important; }</style>';
    });
}
?>