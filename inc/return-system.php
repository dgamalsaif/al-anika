<?php
/**
 * Return Request Form System
 * Handles product return requests after purchase
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_Return_System {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_alam_submit_return_request', array($this, 'ajax_submit_return_request'));
        add_action('wp_ajax_nopriv_alam_submit_return_request', array($this, 'ajax_submit_return_request'));
        add_action('woocommerce_order_details_after_order_table', array($this, 'add_return_button'));
        add_shortcode('alam_return_form', array($this, 'return_form_shortcode'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_mail', array($this, 'return_request_notification'));
    }
    
    public function init() {
        $this->create_return_table();
    }
    
    private function create_return_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'alam_return_requests';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            order_id mediumint(9) NOT NULL,
            user_id mediumint(9) NOT NULL,
            product_id mediumint(9) NOT NULL,
            reason varchar(255) NOT NULL,
            description text,
            return_type varchar(50) NOT NULL,
            status varchar(50) DEFAULT 'pending',
            images text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('alam-returns', get_template_directory_uri() . '/assets/js/return-system.js', array('jquery'), '1.0.0', true);
        wp_localize_script('alam-returns', 'alamReturns', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alam_returns_nonce'),
            'messages' => array(
                'success' => 'تم إرسال طلب الإرجاع بنجاح',
                'error' => 'حدث خطأ أثناء إرسال الطلب',
                'required' => 'هذا الحقل مطلوب'
            )
        ));
    }
    
    public function add_return_button($order) {
        if (!is_user_logged_in()) {
            return;
        }
        
        $user_id = get_current_user_id();
        $order_user_id = $order->get_user_id();
        
        if ($user_id != $order_user_id) {
            return;
        }
        
        // Check if order is completed and within return period (30 days)
        $order_status = $order->get_status();
        $order_date = $order->get_date_created();
        $current_date = new DateTime();
        $days_since_order = $current_date->diff($order_date)->days;
        
        if ($order_status === 'completed' && $days_since_order <= 30) {
            ?>
            <div class="alam-return-section">
                <h3>طلب إرجاع</h3>
                <p>يمكنك طلب إرجاع أي منتج من هذا الطلب خلال 30 يوماً من تاريخ الاستلام</p>
                <button class="button alam-open-return-form" data-order-id="<?php echo $order->get_id(); ?>">
                    طلب إرجاع منتج
                </button>
            </div>
            
            <div id="alam-return-modal" class="alam-modal" style="display: none;">
                <div class="alam-modal-content">
                    <span class="alam-modal-close">&times;</span>
                    <h2>طلب إرجاع منتج</h2>
                    <?php echo $this->return_form_shortcode(array('order_id' => $order->get_id())); ?>
                </div>
            </div>
            <?php
        }
    }
    
    public function return_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'order_id' => 0
        ), $atts);
        
        $order_id = intval($atts['order_id']);
        
        if (!$order_id) {
            return '<p>رقم الطلب غير صالح</p>';
        }
        
        $order = wc_get_order($order_id);
        if (!$order) {
            return '<p>الطلب غير موجود</p>';
        }
        
        ob_start();
        ?>
        <form id="alam-return-form" class="alam-return-form" data-order-id="<?php echo $order_id; ?>">
            <div class="alam-form-group">
                <label for="return-product">المنتج المراد إرجاعه *</label>
                <select id="return-product" name="product_id" required>
                    <option value="">اختر المنتج</option>
                    <?php
                    foreach ($order->get_items() as $item_id => $item) {
                        $product = $item->get_product();
                        if ($product) {
                            echo '<option value="' . $product->get_id() . '">';
                            echo $product->get_name() . ' (الكمية: ' . $item->get_quantity() . ')';
                            echo '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            
            <div class="alam-form-group">
                <label for="return-reason">سبب الإرجاع *</label>
                <select id="return-reason" name="reason" required>
                    <option value="">اختر السبب</option>
                    <option value="defective">منتج معيب</option>
                    <option value="wrong-item">منتج خاطئ</option>
                    <option value="not-as-described">غير مطابق للوصف</option>
                    <option value="damaged">تالف عند الوصول</option>
                    <option value="quality-issues">مشاكل في الجودة</option>
                    <option value="changed-mind">تغيير رأي</option>
                    <option value="other">أخرى</option>
                </select>
            </div>
            
            <div class="alam-form-group">
                <label for="return-type">نوع الإرجاع *</label>
                <select id="return-type" name="return_type" required>
                    <option value="">اختر النوع</option>
                    <option value="refund">استرداد المبلغ</option>
                    <option value="exchange">استبدال المنتج</option>
                    <option value="store-credit">رصيد في المتجر</option>
                </select>
            </div>
            
            <div class="alam-form-group">
                <label for="return-description">وصف المشكلة *</label>
                <textarea id="return-description" name="description" rows="4" placeholder="اشرح المشكلة بالتفصيل..." required></textarea>
            </div>
            
            <div class="alam-form-group">
                <label for="return-images">صور المنتج (اختياري)</label>
                <div class="alam-return-upload-area">
                    <input type="file" id="return-images" name="return_images[]" multiple accept="image/*">
                    <div class="alam-upload-dropzone">
                        <p>اسحب الصور هنا أو انقر لاختيارها</p>
                        <p><small>يمكن رفع 5 صور كحد أقصى</small></p>
                    </div>
                    <div class="alam-uploaded-return-images"></div>
                </div>
            </div>
            
            <div class="alam-return-policy">
                <h4>شروط الإرجاع:</h4>
                <ul>
                    <li>يجب أن يكون المنتج في حالته الأصلية</li>
                    <li>يجب إرجاع المنتج خلال 30 يوماً من تاريخ الاستلام</li>
                    <li>تكاليف الشحن قد تكون على العميل حسب سبب الإرجاع</li>
                    <li>سيتم مراجعة الطلب خلال 2-3 أيام عمل</li>
                </ul>
                <label class="alam-checkbox">
                    <input type="checkbox" name="agree_terms" required>
                    <span class="checkmark"></span>
                    أوافق على شروط الإرجاع
                </label>
            </div>
            
            <div class="alam-form-actions">
                <button type="submit" class="button">إرسال طلب الإرجاع</button>
                <button type="button" class="button-secondary alam-cancel-return">إلغاء</button>
            </div>
        </form>
        
        <style>
        .alam-return-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .alam-form-group {
            margin-bottom: 20px;
        }
        
        .alam-form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .alam-form-group input,
        .alam-form-group select,
        .alam-form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .alam-form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .alam-return-upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: #f9f9f9;
        }
        
        .alam-upload-dropzone {
            cursor: pointer;
        }
        
        .alam-upload-dropzone:hover {
            border-color: #007cba;
            background: #f0f8ff;
        }
        
        .alam-uploaded-return-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        
        .alam-return-policy {
            background: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .alam-return-policy h4 {
            margin: 0 0 15px 0;
            color: #333;
        }
        
        .alam-return-policy ul {
            margin: 0 0 15px 20px;
            color: #666;
        }
        
        .alam-return-policy li {
            margin-bottom: 8px;
        }
        
        .alam-checkbox {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .alam-checkbox input[type="checkbox"] {
            margin-right: 10px;
            width: auto;
        }
        
        .alam-form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
        }
        
        .alam-form-actions button {
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .alam-modal {
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .alam-modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }
        
        .alam-modal-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 15px;
            top: 10px;
            cursor: pointer;
        }
        
        .alam-modal-close:hover {
            color: #333;
        }
        </style>
        <?php
        return ob_get_clean();
    }
    
    public function ajax_submit_return_request() {
        check_ajax_referer('alam_returns_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error('يجب تسجيل الدخول أولاً');
        }
        
        $order_id = intval($_POST['order_id']);
        $product_id = intval($_POST['product_id']);
        $reason = sanitize_text_field($_POST['reason']);
        $description = sanitize_textarea_field($_POST['description']);
        $return_type = sanitize_text_field($_POST['return_type']);
        
        if (!$order_id || !$product_id || !$reason || !$description || !$return_type) {
            wp_send_json_error('جميع الحقول مطلوبة');
        }
        
        // Validate order ownership
        $order = wc_get_order($order_id);
        if (!$order || $order->get_user_id() != get_current_user_id()) {
            wp_send_json_error('غير مسموح بهذا الإجراء');
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'alam_return_requests';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'order_id' => $order_id,
                'user_id' => get_current_user_id(),
                'product_id' => $product_id,
                'reason' => $reason,
                'description' => $description,
                'return_type' => $return_type,
                'status' => 'pending'
            ),
            array('%d', '%d', '%d', '%s', '%s', '%s', '%s')
        );
        
        if ($result !== false) {
            $return_id = $wpdb->insert_id;
            
            // Send notification email
            $this->send_return_notification($return_id);
            
            wp_send_json_success(array(
                'message' => 'تم إرسال طلب الإرجاع بنجاح. سيتم مراجعة طلبك خلال 2-3 أيام عمل.',
                'return_id' => $return_id
            ));
        } else {
            wp_send_json_error('حدث خطأ أثناء إرسال الطلب');
        }
    }
    
    private function send_return_notification($return_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'alam_return_requests';
        
        $return_request = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $return_id));
        
        if ($return_request) {
            $order = wc_get_order($return_request->order_id);
            $product = wc_get_product($return_request->product_id);
            $user = get_user_by('id', $return_request->user_id);
            
            $subject = 'طلب إرجاع جديد - رقم ' . $return_id;
            $message = "تم استلام طلب إرجاع جديد:\n\n";
            $message .= "رقم الطلب: " . $return_request->order_id . "\n";
            $message .= "المنتج: " . $product->get_name() . "\n";
            $message .= "العميل: " . $user->display_name . "\n";
            $message .= "السبب: " . $return_request->reason . "\n";
            $message .= "الوصف: " . $return_request->description . "\n";
            $message .= "نوع الإرجاع: " . $return_request->return_type . "\n\n";
            $message .= "يرجى مراجعة الطلب في لوحة التحكم.";
            
            wp_mail(get_option('admin_email'), $subject, $message);
        }
    }
    
    public function add_admin_menu() {
        add_submenu_page(
            'woocommerce',
            'طلبات الإرجاع',
            'طلبات الإرجاع',
            'manage_woocommerce',
            'alam-returns',
            array($this, 'admin_page')
        );
    }
    
    public function admin_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'alam_return_requests';
        
        $returns = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
        
        ?>
        <div class="wrap">
            <h1>طلبات الإرجاع</h1>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>رقم الأوردر</th>
                        <th>المنتج</th>
                        <th>العميل</th>
                        <th>السبب</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($returns as $return): 
                        $order = wc_get_order($return->order_id);
                        $product = wc_get_product($return->product_id);
                        $user = get_user_by('id', $return->user_id);
                        ?>
                        <tr>
                            <td><?php echo $return->id; ?></td>
                            <td><?php echo $return->order_id; ?></td>
                            <td><?php echo $product ? $product->get_name() : 'منتج محذوف'; ?></td>
                            <td><?php echo $user ? $user->display_name : 'مستخدم محذوف'; ?></td>
                            <td><?php echo $return->reason; ?></td>
                            <td><?php echo $return->return_type; ?></td>
                            <td>
                                <select onchange="updateReturnStatus(<?php echo $return->id; ?>, this.value)">
                                    <option value="pending" <?php selected($return->status, 'pending'); ?>>في الانتظار</option>
                                    <option value="approved" <?php selected($return->status, 'approved'); ?>>موافق عليه</option>
                                    <option value="rejected" <?php selected($return->status, 'rejected'); ?>>مرفوض</option>
                                    <option value="completed" <?php selected($return->status, 'completed'); ?>>مكتمل</option>
                                </select>
                            </td>
                            <td><?php echo date('Y-m-d H:i', strtotime($return->created_at)); ?></td>
                            <td>
                                <button onclick="viewReturnDetails(<?php echo $return->id; ?>)">عرض التفاصيل</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}

new Alam_Return_System();