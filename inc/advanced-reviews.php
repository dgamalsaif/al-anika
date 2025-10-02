<?php
/**
 * Advanced Review System with Image Upload
 * Enhanced review functionality with image attachments
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_Advanced_Reviews {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_alam_upload_review_image', array($this, 'ajax_upload_review_image'));
        add_action('wp_ajax_nopriv_alam_upload_review_image', array($this, 'ajax_upload_review_image'));
        add_action('comment_form_before_fields', array($this, 'add_review_image_field'));
        add_action('comment_post', array($this, 'save_review_images'));
        add_filter('comment_text', array($this, 'display_review_images'));
        add_action('wp_footer', array($this, 'review_image_modal'));
    }
    
    public function init() {
        // Create uploads directory for review images
        $upload_dir = wp_upload_dir();
        $review_dir = $upload_dir['basedir'] . '/review-images';
        
        if (!file_exists($review_dir)) {
            wp_mkdir_p($review_dir);
        }
    }
    
    public function enqueue_scripts() {
        if (is_product()) {
            wp_enqueue_script('alam-reviews', get_template_directory_uri() . '/assets/js/advanced-reviews.js', array('jquery'), '1.0.0', true);
            wp_localize_script('alam-reviews', 'alamReviews', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('alam_reviews_nonce'),
                'max_files' => 5,
                'max_size' => '5MB',
                'allowed_types' => array('jpg', 'jpeg', 'png', 'gif'),
                'messages' => array(
                    'upload_success' => 'تم رفع الصورة بنجاح',
                    'upload_error' => 'خطأ في رفع الصورة',
                    'max_files' => 'يمكن رفع 5 صور كحد أقصى',
                    'invalid_type' => 'نوع الملف غير مدعوم',
                    'too_large' => 'حجم الملف كبير جداً'
                )
            ));
        }
    }
    
    public function add_review_image_field() {
        global $product;
        
        if (!$product || !is_product()) {
            return;
        }
        ?>
        <div class="alam-review-images-section">
            <h4>إضافة صور للمراجعة (اختياري)</h4>
            <div class="alam-review-upload-area">
                <div class="alam-upload-dropzone">
                    <div class="alam-upload-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                            <path d="M12,11L16,15H13V19H11V15H8L12,11Z"/>
                        </svg>
                    </div>
                    <p>اسحب الصور هنا أو انقر للاختيار</p>
                    <p class="alam-upload-note">يمكن رفع 5 صور كحد أقصى (JPG, PNG, GIF - حتى 5MB لكل صورة)</p>
                    <input type="file" id="alam-review-images" name="review_images[]" multiple accept="image/*" style="display: none;">
                    <button type="button" class="alam-upload-button">اختيار الصور</button>
                </div>
                
                <div class="alam-uploaded-images">
                    <!-- Uploaded images will appear here -->
                </div>
                
                <input type="hidden" name="review_image_ids" id="review-image-ids" value="">
            </div>
        </div>
        
        <style>
        .alam-review-images-section {
            margin-bottom: 20px;
            padding: 20px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }
        
        .alam-review-images-section h4 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 16px;
        }
        
        .alam-upload-dropzone {
            text-align: center;
            padding: 30px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .alam-upload-dropzone:hover,
        .alam-upload-dropzone.dragover {
            border-color: #007cba;
            background: #f0f8ff;
        }
        
        .alam-upload-icon svg {
            fill: #ccc;
            margin-bottom: 10px;
        }
        
        .alam-upload-dropzone p {
            margin: 10px 0;
            color: #666;
        }
        
        .alam-upload-note {
            font-size: 12px !important;
            color: #999 !important;
        }
        
        .alam-upload-button {
            background: #007cba;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        
        .alam-upload-button:hover {
            background: #005a87;
        }
        
        .alam-uploaded-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 20px;
        }
        
        .alam-uploaded-image {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .alam-uploaded-image img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            display: block;
        }
        
        .alam-remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255,0,0,0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            line-height: 1;
        }
        
        .alam-remove-image:hover {
            background: rgba(255,0,0,1);
        }
        
        .alam-upload-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: rgba(0,0,0,0.1);
        }
        
        .alam-upload-progress-bar {
            height: 100%;
            background: #007cba;
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .alam-upload-error {
            color: #dc3232;
            font-size: 12px;
            margin-top: 5px;
        }
        </style>
        <?php
    }
    
    public function ajax_upload_review_image() {
        check_ajax_referer('alam_reviews_nonce', 'nonce');
        
        if (!isset($_FILES['image'])) {
            wp_send_json_error('لم يتم العثور على ملف');
        }
        
        $file = $_FILES['image'];
        
        // Validate file type
        $allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
        if (!in_array($file['type'], $allowed_types)) {
            wp_send_json_error('نوع الملف غير مدعوم');
        }
        
        // Validate file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            wp_send_json_error('حجم الملف كبير جداً');
        }
        
        // Upload file
        $upload_overrides = array('test_form' => false);
        $uploaded_file = wp_handle_upload($file, $upload_overrides);
        
        if ($uploaded_file && !isset($uploaded_file['error'])) {
            // Create attachment
            $attachment = array(
                'post_mime_type' => $uploaded_file['type'],
                'post_title' => sanitize_file_name($file['name']),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            
            $attachment_id = wp_insert_attachment($attachment, $uploaded_file['file']);
            
            if (!is_wp_error($attachment_id)) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $uploaded_file['file']);
                wp_update_attachment_metadata($attachment_id, $attachment_data);
                
                $image_url = wp_get_attachment_url($attachment_id);
                $thumb_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                
                wp_send_json_success(array(
                    'attachment_id' => $attachment_id,
                    'url' => $image_url,
                    'thumb' => $thumb_url,
                    'message' => 'تم رفع الصورة بنجاح'
                ));
            }
        }
        
        wp_send_json_error('خطأ في رفع الصورة');
    }
    
    public function save_review_images($comment_id) {
        if (isset($_POST['review_image_ids']) && !empty($_POST['review_image_ids'])) {
            $image_ids = sanitize_text_field($_POST['review_image_ids']);
            add_comment_meta($comment_id, 'review_images', $image_ids);
        }
    }
    
    public function display_review_images($comment_text) {
        global $comment;
        
        $image_ids = get_comment_meta($comment->comment_ID, 'review_images', true);
        
        if (!empty($image_ids)) {
            $ids = explode(',', $image_ids);
            $images_html = '<div class="alam-review-images">';
            
            foreach ($ids as $id) {
                $id = intval(trim($id));
                if ($id) {
                    $image_url = wp_get_attachment_url($id);
                    $thumb_url = wp_get_attachment_image_url($id, 'medium');
                    $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                    
                    if ($image_url) {
                        $images_html .= '<div class="alam-review-image">';
                        $images_html .= '<img src="' . esc_url($thumb_url) . '" alt="' . esc_attr($alt) . '" data-full="' . esc_url($image_url) . '" class="alam-review-thumbnail">';
                        $images_html .= '</div>';
                    }
                }
            }
            
            $images_html .= '</div>';
            $comment_text .= $images_html;
        }
        
        return $comment_text;
    }
    
    public function review_image_modal() {
        if (is_product()) {
            ?>
            <div id="alam-review-image-modal" class="alam-modal" style="display: none;">
                <div class="alam-modal-content">
                    <span class="alam-modal-close">&times;</span>
                    <img id="alam-modal-image" src="" alt="">
                    <div class="alam-modal-nav">
                        <button id="alam-modal-prev">❮</button>
                        <button id="alam-modal-next">❯</button>
                    </div>
                </div>
            </div>
            
            <style>
            .alam-review-images {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
                gap: 8px;
                margin-top: 15px;
                padding: 15px;
                background: #f9f9f9;
                border-radius: 8px;
            }
            
            .alam-review-image {
                border-radius: 8px;
                overflow: hidden;
                cursor: pointer;
                transition: transform 0.2s ease;
            }
            
            .alam-review-image:hover {
                transform: scale(1.05);
            }
            
            .alam-review-thumbnail {
                width: 100%;
                height: 80px;
                object-fit: cover;
                display: block;
            }
            
            .alam-modal {
                position: fixed;
                z-index: 10000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.9);
            }
            
            .alam-modal-content {
                position: relative;
                margin: auto;
                padding: 0;
                width: 90%;
                max-width: 800px;
                top: 50%;
                transform: translateY(-50%);
                text-align: center;
            }
            
            .alam-modal-close {
                color: white;
                float: right;
                font-size: 28px;
                font-weight: bold;
                position: absolute;
                right: 15px;
                top: 15px;
                z-index: 10001;
                cursor: pointer;
            }
            
            .alam-modal-close:hover {
                opacity: 0.7;
            }
            
            #alam-modal-image {
                max-width: 100%;
                max-height: 80vh;
                border-radius: 8px;
            }
            
            .alam-modal-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 100%;
                display: flex;
                justify-content: space-between;
                pointer-events: none;
            }
            
            .alam-modal-nav button {
                background: rgba(0,0,0,0.5);
                color: white;
                border: none;
                padding: 15px 20px;
                font-size: 20px;
                cursor: pointer;
                border-radius: 4px;
                pointer-events: all;
            }
            
            .alam-modal-nav button:hover {
                background: rgba(0,0,0,0.8);
            }
            </style>
            <?php
        }
    }
}

new Alam_Advanced_Reviews();