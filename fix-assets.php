<?php
/**
 * إصلاح شامل لمشاكل CSS/JS في قالب الأنيقة
 * Comprehensive CSS/JS Fix for Al-Anika Theme
 * 
 * ضع هذا الملف في wp-content/themes/al-anika-theme/fix-assets.php
 * ثم افتح: موقعك.com/wp-content/themes/اسم-القالب/fix-assets.php
 */

// التحقق من وجود الوردبريس
$wp_config_path = dirname(dirname(dirname(__FILE__))) . '/wp-config.php';
if (file_exists($wp_config_path)) {
    require_once($wp_config_path);
} else {
    die('لا يمكن العثور على ملف wp-config.php');
}

?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إصلاح شامل - قالب الأنيقة</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            line-height: 1.6; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            direction: rtl;
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #ff2b4d; 
            text-align: center; 
            font-size: 2.5em; 
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .subtitle { 
            text-align: center; 
            color: #666; 
            margin-bottom: 40px; 
        }
        .fix-section { 
            background: #f8f9fa; 
            padding: 25px; 
            margin: 25px 0; 
            border-right: 5px solid #ff2b4d; 
            border-radius: 10px;
        }
        .status { 
            padding: 15px; 
            margin: 15px 0; 
            border-radius: 8px; 
            font-weight: 500;
        }
        .success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        .warning { background: #fff3cd; color: #856404; border-left: 4px solid #ffc107; }
        .info { background: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8; }
        .fix-button { 
            background: linear-gradient(45deg, #ff2b4d, #ff6b9d); 
            color: white; 
            padding: 12px 25px; 
            border: none; 
            border-radius: 25px; 
            cursor: pointer; 
            margin: 8px; 
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 43, 77, 0.3);
        }
        .fix-button:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 20px rgba(255, 43, 77, 0.4);
        }
        .code-block { 
            background: #2d3748; 
            color: #e2e8f0; 
            padding: 20px; 
            border-radius: 10px; 
            font-family: 'Courier New', monospace; 
            overflow-x: auto;
            margin: 15px 0;
        }
        .step { 
            background: #fff; 
            padding: 20px; 
            margin: 15px 0; 
            border-radius: 10px; 
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .step-number { 
            background: #ff2b4d; 
            color: white; 
            width: 30px; 
            height: 30px; 
            border-radius: 50%; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            margin-left: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛠️ إصلاح شامل لقالب الأنيقة</h1>
        <p class="subtitle">حل جميع مشاكل CSS/JS والعرض النصي</p>
        
        <?php
        
        $fixes_applied = [];
        
        // الإصلاح 1: تحديث functions.php
        if (isset($_POST['fix_functions'])) {
            $functions_file = __DIR__ . '/functions.php';
            if (file_exists($functions_file)) {
                
                $functions_content = file_get_contents($functions_file);
                
                // إضافة كود إصلاح تحميل الأصول
                $fix_code = "
// إصلاح تحميل الأصول - Emergency Fix
function al_anika_emergency_assets_fix() {
    // تأكد من تحميل الستايلات الأساسية
    wp_enqueue_style('al-anika-emergency-main', get_template_directory_uri() . '/assets/css/main.css', array(), time());
    wp_enqueue_style('al-anika-emergency-responsive', get_template_directory_uri() . '/assets/css/responsive-unified.css', array(), time());
    wp_enqueue_style('al-anika-emergency-style', get_stylesheet_uri(), array(), time());
    
    // تحميل الجافاسكريبت الأساسي
    wp_enqueue_script('al-anika-emergency-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), time(), true);
    
    // إضافة ستايلات طوارئ مباشرة
    wp_add_inline_style('al-anika-emergency-main', '
        body { font-family: -apple-system, BlinkMacSystemFont, sans-serif !important; }
        .site-header { background: #fff !important; border-bottom: 1px solid #eee !important; }
        .container { max-width: 1200px !important; margin: 0 auto !important; padding: 0 20px !important; }
    ');
}
add_action('wp_enqueue_scripts', 'al_anika_emergency_assets_fix', 999);
";
                
                if (strpos($functions_content, 'al_anika_emergency_assets_fix') === false) {
                    file_put_contents($functions_file, $functions_content . $fix_code);
                    $fixes_applied[] = 'تم إضافة كود إصلاح الأصول الطارئ';
                }
            }
        }
        
        // الإصلاح 2: إنشاء ملف .htaccess محدث
        if (isset($_POST['fix_htaccess'])) {
            $htaccess_content = "
# Al-Anika Theme - Optimized .htaccess
RewriteEngine On
RewriteBase /

# تحسين الكاش للملفات الثابتة
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css \"access plus 1 year\"
    ExpiresByType application/javascript \"access plus 1 year\"
    ExpiresByType image/png \"access plus 1 year\"
    ExpiresByType image/jpg \"access plus 1 year\"
    ExpiresByType image/jpeg \"access plus 1 year\"
    ExpiresByType image/gif \"access plus 1 year\"
    ExpiresByType image/webp \"access plus 1 year\"
</IfModule>

# ضغط الملفات
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/xml
</IfModule>

# WordPress Core
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
";
            
            $root_htaccess = dirname(dirname(dirname(__DIR__))) . '/.htaccess';
            file_put_contents($root_htaccess, $htaccess_content);
            $fixes_applied[] = 'تم تحديث ملف .htaccess';
        }
        
        // الإصلاح 3: مسح الكاش وتحديث الروابط
        if (isset($_POST['clear_cache_permalinks'])) {
            flush_rewrite_rules(true);
            if (function_exists('wp_cache_flush')) {
                wp_cache_flush();
            }
            $fixes_applied[] = 'تم مسح الكاش وتحديث الروابط الثابتة';
        }
        
        // عرض نتائج الإصلاحات
        if (!empty($fixes_applied)) {
            echo "<div class='status success'><strong>✅ تم تطبيق الإصلاحات:</strong><br>";
            foreach ($fixes_applied as $fix) {
                echo "• $fix<br>";
            }
            echo "</div>";
        }
        
        ?>
        
        <div class="fix-section">
            <h2>🚨 إصلاحات سريعة وفورية</h2>
            <p>اضغط على الأزرار التالية لتطبيق الإصلاحات:</p>
            
            <form method="post" style="text-align: center;">
                <button type="submit" name="fix_functions" class="fix-button">
                    🔧 إصلاح ملف Functions.php
                </button>
                <button type="submit" name="fix_htaccess" class="fix-button">
                    📁 تحديث ملف .htaccess
                </button>
                <button type="submit" name="clear_cache_permalinks" class="fix-button">
                    🗑️ مسح الكاش + تحديث الروابط
                </button>
            </form>
        </div>
        
        <div class="fix-section">
            <h2>📋 خطوات الإصلاح اليدوي</h2>
            
            <div class="step">
                <span class="step-number">1</span>
                <strong>تأكد من تفعيل القالب الصحيح</strong>
                <p>اذهب إلى: <strong>لوحة التحكم ← المظهر ← القوالب</strong> وتأكد من تفعيل "Al-Anika" أو "الأنيقة"</p>
            </div>
            
            <div class="step">
                <span class="step-number">2</span>
                <strong>فحص إعدادات WooCommerce</strong>
                <p>اذهب إلى: <strong>WooCommerce ← الإعدادات ← متقدم ← الصفحات</strong><br>
                تأكد من تحديد صفحات: المتجر، السلة، الدفع، حسابي</p>
            </div>
            
            <div class="step">
                <span class="step-number">3</span>
                <strong>تحديث الروابط الثابتة</strong>
                <p>اذهب إلى: <strong>الإعدادات ← الروابط الثابتة</strong><br>
                اختر "اسم المقال" واضغط "حفظ التغييرات"</p>
            </div>
            
            <div class="step">
                <span class="step-number">4</span>
                <strong>تعطيل الإضافات مؤقتاً</strong>
                <p>اذهب إلى: <strong>الإضافات ← الإضافات المثبتة</strong><br>
                عطّل جميع الإضافات مؤقتاً لاختبار القالب</p>
            </div>
        </div>
        
        <div class="fix-section">
            <h2>💻 إضافة CSS طارئ مباشر</h2>
            <p>إذا لم تعمل الحلول السابقة، أضف هذا الكود في <strong>المظهر ← تخصيص ← CSS إضافي</strong>:</p>
            
            <div class="code-block">
/* إصلاح طارئ للعرض */
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    line-height: 1.6 !important;
    color: #333 !important;
    background: #fff !important;
}

.site-header {
    background: #fff !important;
    border-bottom: 1px solid #eee !important;
    padding: 10px 0 !important;
}

.container, .main-container {
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 0 20px !important;
}

.product, .woocommerce-loop-product__title {
    border: 1px solid #eee !important;
    padding: 15px !important;
    margin: 10px !important;
    background: #fff !important;
    border-radius: 8px !important;
}

/* إصلاح الشبكة */
.products, .woocommerce-loop-product {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;
    gap: 20px !important;
    padding: 20px 0 !important;
}
            </div>
        </div>
        
        <div class="fix-section">
            <h2>🔧 فحص ملفات القالب</h2>
            <?php
            
            $critical_files = [
                'style.css' => 'ملف الستايل الرئيسي',
                'functions.php' => 'ملف الوظائف', 
                'index.php' => 'الملف الرئيسي',
                'header.php' => 'ملف الرأس',
                'footer.php' => 'ملف التذييل',
                'assets/css/main.css' => 'ستايل القالب الرئيسي',
                'assets/js/main.js' => 'جافاسكريبت القالب الرئيسي'
            ];
            
            foreach ($critical_files as $file => $description) {
                if (file_exists(__DIR__ . '/' . $file)) {
                    $size = number_format(filesize(__DIR__ . '/' . $file) / 1024, 2);
                    echo "<div class='status success'>✅ $description ($file) - $size KB</div>";
                } else {
                    echo "<div class='status error'>❌ $description ($file) - مفقود!</div>";
                }
            }
            
            ?>
        </div>
        
        <div class="fix-section">
            <h2>📞 إذا استمرت المشكلة</h2>
            <div class="status warning">
                <strong>جرب هذه الخطوات الإضافية:</strong><br>
                • تأكد من أن مجلد القالب في المسار الصحيح<br>
                • فحص سجلات الأخطاء في لوحة تحكم الاستضافة<br>
                • تأكد من إصدار PHP (يُنصح بـ 7.4 أو أحدث)<br>
                • جرب رفع القالب مرة أخرى عبر FTP<br>
                • تواصل مع مزود الاستضافة لفحص أذونات الملفات
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <h3>🎯 تم إنشاء هذا الملف بواسطة MiniMax Agent</h3>
            <p>لدعم فني إضافي، احتفظ بهذا الملف لمراجعة التشخيص</p>
        </div>
    </div>
</body>
</html>