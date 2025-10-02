<?php
/**
 * ملف تشخيص وإصلاح قالب الأنيقة
 * Theme Diagnostic and Fix Script for Al-Anika
 * 
 * ضع هذا الملف في مجلد wp-content/themes/al-anika-theme/
 * وافتحه في المتصفح: موقعك.com/wp-content/themes/al-anika-theme/theme-diagnostic-fix.php
 */

// منع الوصول المباشر إذا لم يكن WordPress محملاً
if (!defined('ABSPATH')) {
    // إعداد أساسي للوردبريس إذا لم يكن محملاً
    $wp_path = dirname(dirname(dirname(__FILE__)));
    if (file_exists($wp_path . '/wp-config.php')) {
        require_once($wp_path . '/wp-config.php');
    }
}

?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تشخيص قالب الأنيقة - Al-Anika Theme Diagnostic</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            margin: 20px; 
            background: #f5f5f5;
            direction: rtl;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 { color: #ff2b4d; text-align: center; border-bottom: 3px solid #ff2b4d; padding-bottom: 10px; }
        h2 { color: #333; margin-top: 30px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        code { background: #f8f9fa; padding: 2px 5px; border-radius: 3px; }
        .fix-button { 
            background: #ff2b4d; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            margin: 10px 5px; 
        }
        .fix-button:hover { background: #e02441; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 تشخيص قالب الأنيقة</h1>
        
        <?php
        
        echo "<h2>📋 فحص النظام الأساسي</h2>";
        
        // فحص الوردبريس
        if (defined('ABSPATH')) {
            echo "<div class='status success'>✅ الوردبريس محمّل بنجاح</div>";
            
            // فحص القالب النشط
            $current_theme = wp_get_theme();
            echo "<div class='status info'>📱 القالب النشط: " . $current_theme->get('Name') . " (الإصدار: " . $current_theme->get('Version') . ")</div>";
            
            if (strpos($current_theme->get('Name'), 'Al-Anika') !== false || strpos($current_theme->get('Name'), 'الأنيقة') !== false) {
                echo "<div class='status success'>✅ قالب الأنيقة نشط</div>";
            } else {
                echo "<div class='status error'>❌ قالب الأنيقة غير نشط. القالب النشط: " . $current_theme->get('Name') . "</div>";
                echo "<div class='status warning'>💡 يجب تفعيل قالب Al-Anika من لوحة التحكم ← المظهر ← القوالب</div>";
            }
            
            // فحص ملفات القالب
            $theme_dir = get_template_directory();
            echo "<div class='status info'>📁 مجلد القالب: <code>$theme_dir</code></div>";
            
        } else {
            echo "<div class='status error'>❌ الوردبريس غير محمّل</div>";
        }
        
        echo "<h2>🎨 فحص ملفات CSS</h2>";
        
        $css_files = [
            'assets/css/main.css',
            'assets/css/responsive-unified.css', 
            'assets/css/woocommerce-enhanced.css',
            'style.css'
        ];
        
        foreach ($css_files as $file) {
            $file_path = __DIR__ . '/' . $file;
            if (file_exists($file_path)) {
                $file_size = number_format(filesize($file_path) / 1024, 2);
                echo "<div class='status success'>✅ $file موجود ($file_size KB)</div>";
            } else {
                echo "<div class='status error'>❌ $file مفقود</div>";
            }
        }
        
        echo "<h2>⚡ فحص ملفات JavaScript</h2>";
        
        $js_files = [
            'assets/js/main.js',
            'assets/js/navigation.js',
            'assets/js/animations.js'
        ];
        
        foreach ($js_files as $file) {
            $file_path = __DIR__ . '/' . $file;
            if (file_exists($file_path)) {
                $file_size = number_format(filesize($file_path) / 1024, 2);
                echo "<div class='status success'>✅ $file موجود ($file_size KB)</div>";
            } else {
                echo "<div class='status error'>❌ $file مفقود</div>";
            }
        }
        
        if (defined('ABSPATH')) {
            echo "<h2>🔧 إصلاحات سريعة</h2>";
            
            // إعادة تحديث الروابط الثابتة
            if (isset($_POST['flush_permalinks'])) {
                flush_rewrite_rules();
                echo "<div class='status success'>✅ تم تحديث الروابط الثابتة</div>";
            }
            
            // تنظيف الكاش
            if (isset($_POST['clear_cache'])) {
                // مسح كاش الوردبريس
                if (function_exists('wp_cache_flush')) {
                    wp_cache_flush();
                }
                echo "<div class='status success'>✅ تم مسح الكاش</div>";
            }
            
            echo "<form method='post' style='margin: 20px 0;'>";
            echo "<button type='submit' name='flush_permalinks' class='fix-button'>🔄 تحديث الروابط الثابتة</button>";
            echo "<button type='submit' name='clear_cache' class='fix-button'>🗑️ مسح الكاش</button>";
            echo "</form>";
            
            echo "<h2>📊 معلومات إضافية</h2>";
            
            // فحص صفحات الووكومرس
            if (class_exists('WooCommerce')) {
                echo "<div class='status success'>✅ الووكومرس مثبت</div>";
                $shop_page = get_option('woocommerce_shop_page_id');
                if ($shop_page) {
                    echo "<div class='status info'>🛍️ صفحة المتجر: ID $shop_page</div>";
                } else {
                    echo "<div class='status error'>❌ صفحة المتجر غير محددة</div>";
                }
            } else {
                echo "<div class='status warning'>⚠️ الووكومرس غير مثبت</div>";
            }
            
            // فحص حالة PHP
            echo "<div class='status info'>🐘 إصدار PHP: " . phpversion() . "</div>";
            echo "<div class='status info'>💾 حد الذاكرة: " . ini_get('memory_limit') . "</div>";
        }
        
        echo "<h2>🛠️ خطوات الإصلاح المقترحة</h2>";
        echo "<div class='status info'>";
        echo "<strong>إذا كان الموقع يظهر كنص بسيط:</strong><br>";
        echo "1️⃣ تأكد من تفعيل قالب Al-Anika من لوحة التحكم<br>";
        echo "2️⃣ تحديث الروابط الثابتة (اضغط الزر أعلاه)<br>";
        echo "3️⃣ مسح أي كاش موجود<br>";
        echo "4️⃣ التأكد من وجود ملفات القالب في المجلد الصحيح<br>";
        echo "5️⃣ فحص أذونات الملفات (يجب أن تكون 644 للملفات و 755 للمجلدات)";
        echo "</div>";
        
        ?>
        
        <h2>📞 معلومات الدعم</h2>
        <div class="status info">
            <strong>إذا استمرت المشكلة:</strong><br>
            • تأكد من أن اسم مجلد القالب صحيح<br>
            • تحقق من وجود أخطاء PHP في سجلات الخادم<br>
            • تأكد من تحديث الووكومرس إلى أحدث إصدار<br>
            • جرب إلغاء تفعيل جميع الإضافات مؤقتاً للاختبار
        </div>
    </div>
</body>
</html>