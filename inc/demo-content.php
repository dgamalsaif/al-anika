<?php
/**
 * Professional Demo Content Generator
 * Creates sample products and content for theme demonstration
 *
 * @package AlamAlAnika
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Alam_Demo_Content {
    
    public function __construct() {
        add_action( 'wp_ajax_alam_import_demo', array( $this, 'import_demo_content' ) );
        add_action( 'admin_menu', array( $this, 'add_demo_menu' ) );
    }
    
    /**
     * Add demo import menu
     */
    public function add_demo_menu() {
        add_theme_page(
            esc_html__( 'استيراد المحتوى التجريبي', 'alam-al-anika' ),
            esc_html__( 'المحتوى التجريبي', 'alam-al-anika' ),
            'manage_options',
            'alam-demo-import',
            array( $this, 'demo_import_page' )
        );
    }
    
    /**
     * Demo import page
     */
    public function demo_import_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'استيراد المحتوى التجريبي', 'alam-al-anika' ); ?></h1>
            <div class="notice notice-info">
                <p><?php esc_html_e( 'سيقوم هذا بإنشاء منتجات وفئات تجريبية لعرض إمكانيات القالب الاحترافية.', 'alam-al-anika' ); ?></p>
            </div>
            
            <button id="import-demo" class="button button-primary button-large">
                <?php esc_html_e( 'استيراد المحتوى التجريبي', 'alam-al-anika' ); ?>
            </button>
            
            <div id="import-progress" style="display: none;">
                <p><?php esc_html_e( 'جاري الاستيراد...', 'alam-al-anika' ); ?></p>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#import-demo').click(function() {
                $('#import-progress').show();
                $(this).prop('disabled', true);
                
                $.post(ajaxurl, {
                    action: 'alam_import_demo',
                    nonce: '<?php echo wp_create_nonce( 'alam_demo_nonce' ); ?>'
                }, function(response) {
                    if (response.success) {
                        alert('تم استيراد المحتوى التجريبي بنجاح!');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء الاستيراد');
                    }
                    $('#import-progress').hide();
                    $('#import-demo').prop('disabled', false);
                });
            });
        });
        </script>
        
        <style>
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
        }
        .progress-fill {
            height: 100%;
            background: #e91e63;
            width: 0%;
            animation: progress 3s ease-in-out forwards;
        }
        @keyframes progress {
            to { width: 100%; }
        }
        </style>
        <?php
    }
    
    /**
     * Import demo content
     */
    public function import_demo_content() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'alam_demo_nonce' ) ) {
            wp_die( 'Security check failed' );
        }
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Insufficient permissions' );
        }
        
        $this->create_product_categories();
        $this->create_demo_products();
        $this->create_demo_pages();
        
        wp_send_json_success( 'Demo content imported successfully' );
    }
    
    /**
     * Create product categories
     */
    private function create_product_categories() {
        $categories = array(
            array(
                'name' => 'أزياء نسائية',
                'slug' => 'womens-fashion',
                'description' => 'تشكيلة واسعة من الأزياء النسائية العصرية'
            ),
            array(
                'name' => 'أزياء رجالية',
                'slug' => 'mens-fashion',
                'description' => 'أحدث صيحات الموضة الرجالية'
            ),
            array(
                'name' => 'أحذية',
                'slug' => 'shoes',
                'description' => 'أحذية عالية الجودة لجميع المناسبات'
            ),
            array(
                'name' => 'إكسسوارات',
                'slug' => 'accessories',
                'description' => 'إكسسوارات أنيقة تكمل إطلالتك'
            ),
            array(
                'name' => 'حقائب',
                'slug' => 'bags',
                'description' => 'حقائب عملية وأنيقة'
            ),
            array(
                'name' => 'ساعات',
                'slug' => 'watches',
                'description' => 'ساعات فاخرة ومتميزة'
            )
        );
        
        foreach ( $categories as $category ) {
            if ( ! term_exists( $category['slug'], 'product_cat' ) ) {
                wp_insert_term(
                    $category['name'],
                    'product_cat',
                    array(
                        'slug' => $category['slug'],
                        'description' => $category['description']
                    )
                );
            }
        }
    }
    
    /**
     * Create demo products
     */
    private function create_demo_products() {
        $products = array(
            array(
                'name' => 'فستان صيفي أنيق',
                'price' => 199,
                'sale_price' => 149,
                'category' => 'womens-fashion',
                'description' => 'فستان صيفي مريح وأنيق مصنوع من أجود الخامات. مثالي للنزهات والمناسبات الصيفية.',
                'short_description' => 'فستان صيفي أنيق ومريح',
                'featured' => true
            ),
            array(
                'name' => 'قميص قطني رجالي',
                'price' => 129,
                'sale_price' => 99,
                'category' => 'mens-fashion',
                'description' => 'قميص قطني عالي الجودة مناسب للعمل والمناسبات الرسمية. متوفر بألوان متعددة.',
                'short_description' => 'قميص قطني رجالي كلاسيكي',
                'featured' => true
            ),
            array(
                'name' => 'حذاء رياضي مريح',
                'price' => 299,
                'sale_price' => 249,
                'category' => 'shoes',
                'description' => 'حذاء رياضي مريح مع تقنية امتصاص الصدمات. مثالي للجري والأنشطة الرياضية.',
                'short_description' => 'حذاء رياضي عالي الأداء',
                'featured' => false
            ),
            array(
                'name' => 'ساعة ذكية متطورة',
                'price' => 899,
                'sale_price' => 699,
                'category' => 'watches',
                'description' => 'ساعة ذكية بمواصفات متقدمة مع مراقبة الصحة وتتبع النشاط البدني.',
                'short_description' => 'ساعة ذكية بتقنيات متطورة',
                'featured' => true
            ),
            array(
                'name' => 'حقيبة يد أنيقة',
                'price' => 399,
                'sale_price' => 299,
                'category' => 'bags',
                'description' => 'حقيبة يد جلدية أنيقة بتصميم عصري وجيوب متعددة للتنظيم المثالي.',
                'short_description' => 'حقيبة يد جلدية فاخرة',
                'featured' => false
            ),
            array(
                'name' => 'نظارة شمسية عصرية',
                'price' => 159,
                'sale_price' => 119,
                'category' => 'accessories',
                'description' => 'نظارة شمسية بحماية من الأشعة فوق البنفسجية وتصميم عصري يناسب جميع الوجوه.',
                'short_description' => 'نظارة شمسية بحماية UV',
                'featured' => true
            ),
            array(
                'name' => 'جاكيت شتوي دافئ',
                'price' => 449,
                'sale_price' => 349,
                'category' => 'mens-fashion',
                'description' => 'جاكيت شتوي مقاوم للماء والرياح مع بطانة داخلية دافئة. مثالي للطقس البارد.',
                'short_description' => 'جاكيت شتوي مقاوم للطقس',
                'featured' => false
            ),
            array(
                'name' => 'تنورة كاجوال عملية',
                'price' => 89,
                'sale_price' => 69,
                'category' => 'womens-fashion',
                'description' => 'تنورة كاجوال مريحة ومتعددة الاستخدامات. تناسب المناسبات اليومية والعملية.',
                'short_description' => 'تنورة كاجوال متعددة الاستخدامات',
                'featured' => true
            )
        );
        
        foreach ( $products as $product_data ) {
            $this->create_single_product( $product_data );
        }
    }
    
    /**
     * Create single product
     */
    private function create_single_product( $data ) {
        $product = new WC_Product_Simple();
        
        $product->set_name( $data['name'] );
        $product->set_status( 'publish' );
        $product->set_description( $data['description'] );
        $product->set_short_description( $data['short_description'] );
        $product->set_regular_price( $data['price'] );
        
        if ( isset( $data['sale_price'] ) ) {
            $product->set_sale_price( $data['sale_price'] );
        }
        
        $product->set_manage_stock( true );
        $product->set_stock_quantity( rand( 10, 100 ) );
        $product->set_stock_status( 'instock' );
        
        if ( $data['featured'] ) {
            $product->set_featured( true );
        }
        
        $product_id = $product->save();
        
        // Set category
        $term = get_term_by( 'slug', $data['category'], 'product_cat' );
        if ( $term ) {
            wp_set_object_terms( $product_id, $term->term_id, 'product_cat' );
        }
        
        // Add some attributes for swatches (preserve swatch functionality)
        $this->add_product_attributes( $product_id );
        
        return $product_id;
    }
    
    /**
     * Add product attributes for swatches
     */
    private function add_product_attributes( $product_id ) {
        $colors = array( 'أحمر', 'أزرق', 'أسود', 'أبيض', 'وردي' );
        $sizes = array( 'صغير', 'متوسط', 'كبير', 'كبير جداً' );
        
        $attributes = array();
        
        // Color attribute
        $attributes['pa_color'] = array(
            'name' => 'pa_color',
            'value' => implode( ' | ', array_slice( $colors, 0, rand( 2, 4 ) ) ),
            'position' => 0,
            'is_visible' => 1,
            'is_variation' => 0,
            'is_taxonomy' => 1
        );
        
        // Size attribute
        $attributes['pa_size'] = array(
            'name' => 'pa_size',
            'value' => implode( ' | ', array_slice( $sizes, 0, rand( 2, 3 ) ) ),
            'position' => 1,
            'is_visible' => 1,
            'is_variation' => 0,
            'is_taxonomy' => 1
        );
        
        update_post_meta( $product_id, '_product_attributes', $attributes );
    }
    
    /**
     * Create demo pages
     */
    private function create_demo_pages() {
        $pages = array(
            array(
                'title' => 'من نحن',
                'slug' => 'about',
                'content' => $this->get_about_content()
            ),
            array(
                'title' => 'اتصل بنا',
                'slug' => 'contact',
                'content' => $this->get_contact_content()
            ),
            array(
                'title' => 'سياسة الخصوصية',
                'slug' => 'privacy',
                'content' => $this->get_privacy_content()
            ),
            array(
                'title' => 'الشروط والأحكام',
                'slug' => 'terms',
                'content' => $this->get_terms_content()
            )
        );
        
        foreach ( $pages as $page_data ) {
            $existing_page = get_page_by_path( $page_data['slug'] );
            if ( ! $existing_page ) {
                wp_insert_post( array(
                    'post_title' => $page_data['title'],
                    'post_name' => $page_data['slug'],
                    'post_content' => $page_data['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page'
                ) );
            }
        }
    }
    
    /**
     * Get about page content
     */
    private function get_about_content() {
        return '
        <h2>مرحباً بكم في عالم الأناقة</h2>
        
        <p>نحن متجر متخصص في تقديم أفضل المنتجات عالية الجودة بأسعار منافسة. منذ تأسيسنا، نسعى لتوفير تجربة تسوق مميزة لعملائنا الكرام.</p>
        
        <h3>رؤيتنا</h3>
        <p>أن نكون المتجر الأول في المنطقة لتوفير أحدث صيحات الموضة والمنتجات عالية الجودة.</p>
        
        <h3>مهمتنا</h3>
        <p>تقديم خدمة عملاء متميزة وتوفير منتجات تلبي احتياجات وتطلعات عملائنا مع ضمان أفضل الأسعار.</p>
        
        <h3>قيمنا</h3>
        <ul>
            <li>الجودة العالية في جميع منتجاتنا</li>
            <li>الصدق والشفافية في التعامل</li>
            <li>خدمة عملاء متميزة</li>
            <li>الابتكار والتطوير المستمر</li>
        </ul>
        ';
    }
    
    /**
     * Get contact page content
     */
    private function get_contact_content() {
        return '
        <h2>تواصل معنا</h2>
        
        <p>نحن هنا لخدمتكم. يمكنكم التواصل معنا من خلال الطرق التالية:</p>
        
        <h3>معلومات الاتصال</h3>
        <ul>
            <li><strong>الهاتف:</strong> +966 50 123 4567</li>
            <li><strong>البريد الإلكتروني:</strong> info@alamalanika.com</li>
            <li><strong>العنوان:</strong> الرياض، المملكة العربية السعودية</li>
        </ul>
        
        <h3>ساعات العمل</h3>
        <ul>
            <li>السبت - الخميس: 9:00 ص - 10:00 م</li>
            <li>الجمعة: 2:00 م - 10:00 م</li>
        </ul>
        
        <h3>خدمة العملاء</h3>
        <p>فريق خدمة العملاء متاح 24/7 للإجابة على استفساراتكم ومساعدتكم في أي وقت.</p>
        ';
    }
    
    /**
     * Get privacy policy content
     */
    private function get_privacy_content() {
        return '
        <h2>سياسة الخصوصية</h2>
        
        <p>نحن نحترم خصوصيتكم ونلتزم بحماية بياناتكم الشخصية.</p>
        
        <h3>جمع المعلومات</h3>
        <p>نقوم بجمع المعلومات التي تقدمونها طوعاً عند التسجيل أو التسوق في موقعنا.</p>
        
        <h3>استخدام المعلومات</h3>
        <p>نستخدم معلوماتكم لتحسين خدماتنا وتوفير تجربة تسوق مخصصة.</p>
        
        <h3>حماية البيانات</h3>
        <p>نطبق أعلى معايير الأمان لحماية بياناتكم من الوصول غير المصرح به.</p>
        ';
    }
    
    /**
     * Get terms and conditions content
     */
    private function get_terms_content() {
        return '
        <h2>الشروط والأحكام</h2>
        
        <p>باستخدام موقعنا، فإنكم توافقون على الشروط والأحكام التالية:</p>
        
        <h3>شروط الاستخدام</h3>
        <p>يجب استخدام الموقع لأغراض التسوق المشروعة فقط.</p>
        
        <h3>سياسة الإرجاع</h3>
        <p>يمكن إرجاع المنتجات خلال 30 يوماً من تاريخ الشراء شريطة أن تكون في حالتها الأصلية.</p>
        
        <h3>الشحن والتوصيل</h3>
        <p>نوفر خدمة شحن سريعة وآمنة لجميع أنحاء المملكة.</p>
        ';
    }
}

// Initialize demo content
new Alam_Demo_Content();
