<?php
/**
 * Daily Rewards Gamification System - نظام المكافآت اليومي التفاعلي
 * Advanced Daily Rewards System with Magical UI
 * 
 * @package AlamAlAnika
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Alam_Daily_Rewards_System {
    
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'alam_daily_rewards';
        
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'add_reward_popup'));
        add_action('wp_ajax_check_daily_reward', array($this, 'ajax_check_daily_reward'));
        add_action('wp_ajax_nopriv_check_daily_reward', array($this, 'ajax_check_daily_reward'));
        add_action('wp_ajax_claim_daily_reward', array($this, 'ajax_claim_daily_reward'));
        add_action('wp_ajax_nopriv_claim_daily_reward', array($this, 'ajax_claim_daily_reward'));
        add_action('wp_ajax_get_rewards_progress', array($this, 'ajax_get_rewards_progress'));
        add_action('wp_ajax_nopriv_get_rewards_progress', array($this, 'ajax_get_rewards_progress'));
        
        // Database creation
        register_activation_hook(__FILE__, array($this, 'create_rewards_table'));
    }
    
    public function init() {
        // Create database table if not exists
        $this->create_rewards_table();
        
        // Check for daily reward on page load
        if (!wp_doing_ajax()) {
            add_action('wp_footer', array($this, 'check_daily_reward_on_load'), 1);
        }
    }
    
    public function create_rewards_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_identifier varchar(255) NOT NULL,
            user_type enum('registered','guest') DEFAULT 'guest',
            last_visit_date date NOT NULL,
            consecutive_days int(11) DEFAULT 1,
            total_visits int(11) DEFAULT 1,
            rewards_earned text,
            current_streak int(11) DEFAULT 1,
            max_streak int(11) DEFAULT 1,
            total_points int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY user_identifier (user_identifier)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public function enqueue_scripts() {
        // SweetAlert2 for magical popups
        wp_enqueue_script(
            'sweetalert2',
            'https://cdn.jsdelivr.net/npm/sweetalert2@11',
            array(),
            '11.0.0',
            true
        );
        
        // GSAP for animations
        wp_enqueue_script(
            'gsap',
            'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js',
            array(),
            '3.12.2',
            true
        );
        
        // Lottie for animations
        wp_enqueue_script(
            'lottie-web',
            'https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js',
            array(),
            '5.12.2',
            true
        );
        
        // Custom rewards system JS
        wp_enqueue_script(
            'alam-daily-rewards',
            get_template_directory_uri() . '/assets/js/daily-rewards.js',
            array('jquery', 'sweetalert2', 'gsap'),
            '1.0.0',
            true
        );
        
        // Custom rewards system CSS
        wp_enqueue_style(
            'alam-daily-rewards',
            get_template_directory_uri() . '/assets/css/daily-rewards.css',
            array(),
            '1.0.0'
        );
        
        // Localize script
        wp_localize_script('alam-daily-rewards', 'alamRewards', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alam_rewards_nonce'),
            'user_id' => get_current_user_id(),
            'is_user_logged_in' => is_user_logged_in(),
            'messages' => array(
                'welcome_back' => __('أهلاً بعودتك! 🎉', 'alam-al-anika'),
                'daily_reward' => __('مكافأة يومية جديدة!', 'alam-al-anika'),
                'comeback_tomorrow' => __('عد غداً للحصول على مكافأة أكبر!', 'alam-al-anika'),
                'week_completed' => __('تهانينا! أكملت أسبوعاً كاملاً! 🏆', 'alam-al-anika'),
                'streak_bonus' => __('مكافأة الانتظام!', 'alam-al-anika'),
                'loading' => __('جاري التحميل...', 'alam-al-anika'),
                'error' => __('حدث خطأ، يرجى المحاولة مرة أخرى', 'alam-al-anika'),
                'claim_reward' => __('استلم المكافأة!', 'alam-al-anika'),
                'reward_claimed' => __('تم استلام المكافأة بنجاح!', 'alam-al-anika')
            ),
            'rewards' => $this->get_reward_config(),
            'sounds' => array(
                'reward_bell' => get_template_directory_uri() . '/assets/sounds/reward-bell.mp3',
                'magic_sparkle' => get_template_directory_uri() . '/assets/sounds/magic-sparkle.mp3',
                'week_complete' => get_template_directory_uri() . '/assets/sounds/week-complete.mp3'
            )
        ));
    }
    
    private function get_reward_config() {
        return array(
            1 => array(
                'type' => 'discount',
                'value' => 5,
                'title' => 'خصم 5%',
                'description' => 'خصم 5% على أي منتج',
                'icon' => '🎁',
                'color' => '#FFD700'
            ),
            2 => array(
                'type' => 'points',
                'value' => 50,
                'title' => '50 نقطة',
                'description' => '50 نقطة إضافية',
                'icon' => '⭐',
                'color' => '#FF69B4'
            ),
            3 => array(
                'type' => 'free_shipping',
                'value' => 1,
                'title' => 'شحن مجاني',
                'description' => 'شحن مجاني على الطلب التالي',
                'icon' => '🚚',
                'color' => '#32CD32'
            ),
            4 => array(
                'type' => 'discount',
                'value' => 10,
                'title' => 'خصم 10%',
                'description' => 'خصم 10% على أي منتج',
                'icon' => '💎',
                'color' => '#9370DB'
            ),
            5 => array(
                'type' => 'lottery',
                'value' => 1,
                'title' => 'دخول السحب',
                'description' => 'دخول السحب على هدية قيّمة',
                'icon' => '🍀',
                'color' => '#FF6347'
            ),
            6 => array(
                'type' => 'discount',
                'value' => 12,
                'title' => 'خصم 12%',
                'description' => 'خصم 12% على أي منتج',
                'icon' => '🌟',
                'color' => '#FF1493'
            ),
            7 => array(
                'type' => 'mega_reward',
                'value' => 15,
                'title' => 'المكافأة الكبرى!',
                'description' => 'خصم 15% + هدية مجانية',
                'icon' => '👑',
                'color' => '#FFD700'
            )
        );
    }
    
    public function check_daily_reward_on_load() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Check for daily reward immediately on page load
            setTimeout(function() {
                alamDailyRewards.checkDailyReward();
            }, 1000);
        });
        </script>
        <?php
    }
    
    public function ajax_check_daily_reward() {
        check_ajax_referer('alam_rewards_nonce', 'nonce');
        
        $user_identifier = $this->get_user_identifier();
        $today = date('Y-m-d');
        
        global $wpdb;
        
        // Get user's reward data
        $user_data = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE user_identifier = %s",
            $user_identifier
        ));
        
        $has_reward = false;
        $reward_data = array();
        
        if (!$user_data) {
            // First time visitor
            $has_reward = true;
            $consecutive_days = 1;
            
            // Insert new user
            $wpdb->insert(
                $this->table_name,
                array(
                    'user_identifier' => $user_identifier,
                    'user_type' => is_user_logged_in() ? 'registered' : 'guest',
                    'last_visit_date' => $today,
                    'consecutive_days' => 1,
                    'total_visits' => 1,
                    'current_streak' => 1,
                    'max_streak' => 1,
                    'total_points' => 0
                ),
                array('%s', '%s', '%s', '%d', '%d', '%d', '%d', '%d')
            );
            
        } else {
            $last_visit = $user_data->last_visit_date;
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            
            if ($last_visit !== $today) {
                $has_reward = true;
                
                if ($last_visit === $yesterday) {
                    // Consecutive day
                    $consecutive_days = $user_data->consecutive_days + 1;
                    if ($consecutive_days > 7) {
                        $consecutive_days = 1; // Reset after week
                    }
                } else {
                    // Streak broken
                    $consecutive_days = 1;
                }
                
                // Update user data
                $wpdb->update(
                    $this->table_name,
                    array(
                        'last_visit_date' => $today,
                        'consecutive_days' => $consecutive_days,
                        'total_visits' => $user_data->total_visits + 1,
                        'current_streak' => $consecutive_days,
                        'max_streak' => max($user_data->max_streak, $consecutive_days)
                    ),
                    array('user_identifier' => $user_identifier),
                    array('%s', '%d', '%d', '%d', '%d'),
                    array('%s')
                );
            } else {
                $consecutive_days = $user_data->consecutive_days;
            }
        }
        
        if ($has_reward) {
            $reward_config = $this->get_reward_config();
            $today_reward = $reward_config[$consecutive_days] ?? $reward_config[1];
            
            $reward_data = array(
                'day' => $consecutive_days,
                'reward' => $today_reward,
                'is_week_complete' => $consecutive_days === 7,
                'progress' => $this->get_week_progress($consecutive_days)
            );
        }
        
        wp_send_json_success(array(
            'has_reward' => $has_reward,
            'reward_data' => $reward_data
        ));
    }
    
    public function ajax_claim_daily_reward() {
        check_ajax_referer('alam_rewards_nonce', 'nonce');
        
        $user_identifier = $this->get_user_identifier();
        $reward_day = intval($_POST['reward_day']);
        
        // Create coupon or apply reward
        $coupon_code = $this->create_reward_coupon($reward_day, $user_identifier);
        
        // Update user rewards
        global $wpdb;
        $user_data = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE user_identifier = %s",
            $user_identifier
        ));
        
        $rewards_earned = !empty($user_data->rewards_earned) ? 
            json_decode($user_data->rewards_earned, true) : array();
        
        $rewards_earned[] = array(
            'day' => $reward_day,
            'date' => date('Y-m-d'),
            'coupon_code' => $coupon_code,
            'type' => $this->get_reward_config()[$reward_day]['type']
        );
        
        $wpdb->update(
            $this->table_name,
            array(
                'rewards_earned' => json_encode($rewards_earned),
                'total_points' => $user_data->total_points + 10
            ),
            array('user_identifier' => $user_identifier),
            array('%s', '%d'),
            array('%s')
        );
        
        wp_send_json_success(array(
            'coupon_code' => $coupon_code,
            'message' => __('تم استلام المكافأة بنجاح!', 'alam-al-anika')
        ));
    }
    
    public function ajax_get_rewards_progress() {
        check_ajax_referer('alam_rewards_nonce', 'nonce');
        
        $user_identifier = $this->get_user_identifier();
        
        global $wpdb;
        $user_data = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE user_identifier = %s",
            $user_identifier
        ));
        
        if (!$user_data) {
            wp_send_json_success(array(
                'consecutive_days' => 0,
                'total_visits' => 0,
                'max_streak' => 0,
                'total_points' => 0,
                'progress' => array()
            ));
        }
        
        wp_send_json_success(array(
            'consecutive_days' => $user_data->consecutive_days,
            'total_visits' => $user_data->total_visits,
            'max_streak' => $user_data->max_streak,
            'total_points' => $user_data->total_points,
            'progress' => $this->get_week_progress($user_data->consecutive_days)
        ));
    }
    
    private function get_user_identifier() {
        if (is_user_logged_in()) {
            return 'user_' . get_current_user_id();
        } else {
            // Use combination of IP and User Agent for guests
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            return 'guest_' . md5($ip . $user_agent);
        }
    }
    
    private function get_week_progress($current_day) {
        $progress = array();
        for ($i = 1; $i <= 7; $i++) {
            $progress[] = array(
                'day' => $i,
                'completed' => $i <= $current_day,
                'reward' => $this->get_reward_config()[$i]
            );
        }
        return $progress;
    }
    
    private function create_reward_coupon($reward_day, $user_identifier) {
        if (!function_exists('wc_create_coupon')) {
            return null;
        }
        
        $reward_config = $this->get_reward_config()[$reward_day];
        $coupon_code = 'DAILY_' . strtoupper(substr($user_identifier, -8)) . '_' . $reward_day . '_' . date('Ymd');
        
        // Check if coupon already exists
        if (wc_get_coupon_id_by_code($coupon_code)) {
            return $coupon_code;
        }
        
        $coupon_data = array(
            'post_title' => $coupon_code,
            'post_content' => 'مكافأة يومية - ' . $reward_config['title'],
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'shop_coupon'
        );
        
        $coupon_id = wp_insert_post($coupon_data);
        
        if ($coupon_id) {
            // Set coupon meta
            if ($reward_config['type'] === 'discount') {
                update_post_meta($coupon_id, 'discount_type', 'percent');
                update_post_meta($coupon_id, 'coupon_amount', $reward_config['value']);
            } elseif ($reward_config['type'] === 'free_shipping') {
                update_post_meta($coupon_id, 'free_shipping', 'yes');
                update_post_meta($coupon_id, 'discount_type', 'fixed_cart');
                update_post_meta($coupon_id, 'coupon_amount', 0);
            }
            
            update_post_meta($coupon_id, 'individual_use', 'yes');
            update_post_meta($coupon_id, 'usage_limit', 1);
            update_post_meta($coupon_id, 'usage_limit_per_user', 1);
            update_post_meta($coupon_id, 'limit_usage_to_x_items', 1);
            update_post_meta($coupon_id, 'expiry_date', date('Y-m-d', strtotime('+30 days')));
            
            // Email coupon to registered users
            if (is_user_logged_in() && $reward_config['type'] !== 'points' && $reward_config['type'] !== 'lottery') {
                $this->email_coupon_to_user($coupon_code, $reward_config);
            }
        }
        
        return $coupon_code;
    }
    
    private function email_coupon_to_user($coupon_code, $reward_config) {
        $user = wp_get_current_user();
        $to = $user->user_email;
        $subject = 'مكافأتك اليومية جاهزة! ' . $reward_config['title'];
        
        $message = "
        <div style='direction: rtl; text-align: right; font-family: Arial, sans-serif;'>
            <h2>🎉 تهانينا! لقد حصلت على مكافأة يومية</h2>
            <p><strong>نوع المكافأة:</strong> {$reward_config['title']}</p>
            <p><strong>الوصف:</strong> {$reward_config['description']}</p>
            <p><strong>كود الخصم:</strong> <code style='background: #f4f4f4; padding: 5px; border-radius: 3px;'>{$coupon_code}</code></p>
            <p>استخدم هذا الكود عند الدفع للحصول على خصمك!</p>
            <p style='color: #666;'>صالح حتى: " . date('Y/m/d', strtotime('+30 days')) . "</p>
        </div>
        ";
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($to, $subject, $message, $headers);
    }
    
    public function add_reward_popup() {
        ?>
        <!-- Daily Rewards Progress Bar (Always Visible) -->
        <div id="daily-rewards-progress-bar" class="daily-rewards-progress-bar">
            <div class="progress-container">
                <div class="progress-title">
                    <span class="magic-icon">✨</span>
                    <span>تقدمك الأسبوعي</span>
                    <span class="magic-icon">✨</span>
                </div>
                <div class="progress-days">
                    <?php for ($i = 1; $i <= 7; $i++): ?>
                    <div class="progress-day" data-day="<?php echo $i; ?>">
                        <div class="day-circle">
                            <span class="day-number"><?php echo $i; ?></span>
                            <div class="day-reward-icon"></div>
                        </div>
                        <div class="day-label">يوم <?php echo $i; ?></div>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="progress-line">
                    <div class="progress-fill"></div>
                </div>
            </div>
        </div>

        <!-- Rewards Popup Container -->
        <div id="rewards-popup-container"></div>
        
        <style>
        .daily-rewards-progress-bar {
            position: fixed;
            top: 50%;
            right: -280px;
            transform: translateY(-50%);
            width: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px 0 0 15px;
            padding: 20px;
            z-index: 9999;
            transition: right 0.5s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            direction: rtl;
        }
        
        .daily-rewards-progress-bar:hover {
            right: 0;
        }
        
        .daily-rewards-progress-bar::before {
            content: '🎁';
            position: absolute;
            left: -30px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            background: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(-50%); }
            40% { transform: translateY(-60%); }
            60% { transform: translateY(-55%); }
        }
        
        .progress-title {
            color: white;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .magic-icon {
            animation: sparkle 1.5s linear infinite;
        }
        
        @keyframes sparkle {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.2); }
        }
        
        .progress-days {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            position: relative;
        }
        
        .progress-day {
            text-align: center;
            position: relative;
        }
        
        .day-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .progress-day.completed .day-circle {
            background: #FFD700;
            border-color: #FFD700;
            transform: scale(1.1);
        }
        
        .progress-day.current .day-circle {
            background: #32CD32;
            border-color: #32CD32;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(50, 205, 50, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(50, 205, 50, 0); }
            100% { box-shadow: 0 0 0 0 rgba(50, 205, 50, 0); }
        }
        
        .day-number {
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        .day-label {
            color: rgba(255,255,255,0.8);
            font-size: 10px;
        }
        
        .progress-line {
            height: 4px;
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
            position: relative;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #FFD700, #32CD32);
            border-radius: 2px;
            width: 0%;
            transition: width 1s ease;
        }
        </style>
        <?php
    }
}

// Initialize the system
new Alam_Daily_Rewards_System();