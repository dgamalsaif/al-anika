/**
 * Daily Rewards System JavaScript - نظام المكافآت اليومي
 * Advanced magical rewards system with animations
 */

class AlamDailyRewards {
    constructor() {
        this.isCheckingReward = false;
        this.currentUserData = null;
        this.rewardSounds = alamRewards.sounds || {};
        
        this.init();
    }
    
    init() {
        // Initialize progress bar
        this.initProgressBar();
        
        // Bind events
        this.bindEvents();
        
        // Check for daily reward
        setTimeout(() => {
            this.checkDailyReward();
        }, 1500);
    }
    
    bindEvents() {
        // Progress bar click
        jQuery(document).on('click', '.daily-rewards-progress-bar', () => {
            this.showProgressDetails();
        });
    }
    
    async initProgressBar() {
        try {
            const response = await this.makeAjaxRequest('get_rewards_progress');
            if (response.success) {
                this.updateProgressBar(response.data);
            }
        } catch (error) {
            console.error('Error initializing progress bar:', error);
        }
    }
    
    updateProgressBar(userData) {
        const progressBar = jQuery('.daily-rewards-progress-bar');
        const progressFill = progressBar.find('.progress-fill');
        const progressDays = progressBar.find('.progress-day');
        
        // Update progress fill
        const progressPercentage = (userData.consecutive_days / 7) * 100;
        progressFill.css('width', progressPercentage + '%');
        
        // Update day states
        progressDays.each((index, element) => {
            const day = jQuery(element);
            const dayNumber = index + 1;
            
            day.removeClass('completed current');
            
            if (dayNumber < userData.consecutive_days) {
                day.addClass('completed');
                this.addRewardIcon(day, dayNumber);
            } else if (dayNumber === userData.consecutive_days) {
                day.addClass('current');
            }
        });
        
        // Add sparkle effect to completed days
        this.addSparkleEffect();
    }
    
    addRewardIcon(dayElement, dayNumber) {
        const reward = alamRewards.rewards[dayNumber];
        if (reward) {
            const icon = dayElement.find('.day-reward-icon');
            icon.html(reward.icon).css({
                'position': 'absolute',
                'top': '-5px',
                'right': '-5px',
                'font-size': '12px',
                'background': reward.color,
                'border-radius': '50%',
                'width': '16px',
                'height': '16px',
                'display': 'flex',
                'align-items': 'center',
                'justify-content': 'center'
            });
        }
    }
    
    addSparkleEffect() {
        jQuery('.progress-day.completed').each((index, element) => {
            const sparkle = jQuery('<div class="sparkle-effect">✨</div>');
            sparkle.css({
                'position': 'absolute',
                'top': '-10px',
                'right': '-10px',
                'font-size': '10px',
                'animation': 'sparkle 2s infinite',
                'pointer-events': 'none'
            });
            jQuery(element).append(sparkle);
        });
    }
    
    async checkDailyReward() {
        if (this.isCheckingReward) return;
        this.isCheckingReward = true;
        
        try {
            const response = await this.makeAjaxRequest('check_daily_reward');
            
            if (response.success && response.data.has_reward) {
                await this.showRewardPopup(response.data.reward_data);
                this.updateProgressBar({
                    consecutive_days: response.data.reward_data.day,
                    total_visits: 0,
                    max_streak: 0,
                    total_points: 0
                });
            }
        } catch (error) {
            console.error('Error checking daily reward:', error);
        } finally {
            this.isCheckingReward = false;
        }
    }
    
    async showRewardPopup(rewardData) {
        const reward = rewardData.reward;
        const isWeekComplete = rewardData.is_week_complete;
        
        // Play reward sound
        this.playRewardSound(isWeekComplete ? 'week_complete' : 'reward_bell');
        
        // Create magical popup
        const popup = await this.createMagicalPopup(rewardData);
        
        // Show with SweetAlert2
        const result = await Swal.fire({
            html: popup,
            showConfirmButton: true,
            confirmButtonText: alamRewards.messages.claim_reward,
            allowOutsideClick: false,
            customClass: {
                popup: 'magical-reward-popup',
                confirmButton: 'magical-confirm-btn'
            },
            background: 'transparent',
            backdrop: `
                rgba(0,0,123,0.4)
                url("data:image/svg+xml,<svg width='60' height='60' xmlns='http://www.w3.org/2000/svg'><defs><pattern id='stars' x='0' y='0' width='60' height='60' patternUnits='userSpaceOnUse'><circle cx='30' cy='30' r='1' fill='%23fff'><animate attributeName='opacity' values='0;1;0' dur='2s' repeatCount='indefinite'/></circle></pattern></defs><rect width='100%25' height='100%25' fill='url(%23stars)'/></svg>")
                center/100px 100px repeat
            `,
            didOpen: () => {
                this.animateRewardPopup();
            }
        });
        
        if (result.isConfirmed) {
            await this.claimReward(rewardData.day);
        }
    }
    
    createMagicalPopup(rewardData) {
        const reward = rewardData.reward;
        const isWeekComplete = rewardData.is_week_complete;
        
        return `
            <div class="magical-reward-container">
                <div class="reward-crown ${isWeekComplete ? 'week-complete' : ''}">
                    <div class="crown-icon">${isWeekComplete ? '👑' : '🎁'}</div>
                </div>
                
                <div class="reward-sparkles">
                    ${Array.from({length: 12}, (_, i) => `<div class="sparkle sparkle-${i}">✨</div>`).join('')}
                </div>
                
                <div class="reward-content">
                    <h2 class="reward-title">
                        ${isWeekComplete ? alamRewards.messages.week_completed : alamRewards.messages.daily_reward}
                    </h2>
                    
                    <div class="reward-badge" style="background: ${reward.color}">
                        <div class="reward-icon">${reward.icon}</div>
                        <div class="reward-text">
                            <div class="reward-name">${reward.title}</div>
                            <div class="reward-description">${reward.description}</div>
                        </div>
                    </div>
                    
                    <div class="reward-progress">
                        <div class="progress-info">
                            <span>يوم ${rewardData.day} من 7</span>
                        </div>
                        <div class="progress-visual">
                            ${this.createProgressVisual(rewardData.progress)}
                        </div>
                    </div>
                    
                    ${isWeekComplete ? `
                        <div class="week-complete-bonus">
                            <div class="bonus-title">🎉 مكافأة إضافية!</div>
                            <div class="bonus-text">هدية مجانية مع طلبك التالي!</div>
                        </div>
                    ` : `
                        <div class="comeback-message">
                            <span>${alamRewards.messages.comeback_tomorrow}</span>
                        </div>
                    `}
                </div>
                
                <div class="floating-icons">
                    <div class="floating-icon">💎</div>
                    <div class="floating-icon">⭐</div>
                    <div class="floating-icon">🎯</div>
                    <div class="floating-icon">🌟</div>
                </div>
            </div>
        `;
    }
    
    createProgressVisual(progress) {
        return progress.map(day => `
            <div class="progress-step ${day.completed ? 'completed' : ''} ${day.day === progress.length ? 'current' : ''}">
                <div class="step-circle">
                    <span class="step-icon">${day.reward.icon}</span>
                </div>
                <div class="step-connector"></div>
            </div>
        `).join('');
    }
    
    animateRewardPopup() {
        // Animate crown
        gsap.fromTo('.reward-crown', 
            { scale: 0, rotation: -180, opacity: 0 },
            { scale: 1, rotation: 0, opacity: 1, duration: 1, ease: "back.out(1.7)" }
        );
        
        // Animate sparkles
        gsap.fromTo('.sparkle', 
            { scale: 0, opacity: 0 },
            { 
                scale: 1, 
                opacity: 1, 
                duration: 0.5, 
                stagger: 0.1,
                ease: "back.out(1.7)",
                delay: 0.5
            }
        );
        
        // Animate content
        gsap.fromTo('.reward-content', 
            { y: 50, opacity: 0 },
            { y: 0, opacity: 1, duration: 0.8, delay: 0.3 }
        );
        
        // Animate floating icons
        gsap.fromTo('.floating-icon', 
            { y: 0 },
            { 
                y: -20, 
                duration: 2, 
                stagger: 0.2,
                repeat: -1,
                yoyo: true,
                ease: "power2.inOut",
                delay: 1
            }
        );
        
        // Continuous sparkle animation
        gsap.to('.sparkle', {
            rotation: 360,
            duration: 3,
            repeat: -1,
            ease: "none",
            stagger: {
                each: 0.2,
                repeat: -1
            }
        });
    }
    
    async claimReward(rewardDay) {
        try {
            const loadingSwal = Swal.fire({
                title: alamRewards.messages.loading,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const response = await this.makeAjaxRequest('claim_daily_reward', {
                reward_day: rewardDay
            });
            
            loadingSwal.close();
            
            if (response.success) {
                this.playRewardSound('magic_sparkle');
                
                await Swal.fire({
                    icon: 'success',
                    title: alamRewards.messages.reward_claimed,
                    text: response.data.coupon_code ? 
                        `كود الخصم: ${response.data.coupon_code}` : 
                        response.data.message,
                    customClass: {
                        popup: 'success-popup'
                    },
                    timer: 3000
                });
                
                // Update progress bar
                this.initProgressBar();
            }
        } catch (error) {
            console.error('Error claiming reward:', error);
            Swal.fire({
                icon: 'error',
                title: alamRewards.messages.error,
                timer: 2000
            });
        }
    }
    
    showProgressDetails() {
        this.makeAjaxRequest('get_rewards_progress').then(response => {
            if (response.success) {
                const userData = response.data;
                
                Swal.fire({
                    title: 'إحصائياتك الشخصية',
                    html: `
                        <div class="stats-container" style="text-align: right; direction: rtl;">
                            <div class="stat-item">
                                <span class="stat-icon">🔥</span>
                                <span class="stat-label">الأيام المتتالية:</span>
                                <span class="stat-value">${userData.consecutive_days}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">📅</span>
                                <span class="stat-label">إجمالي الزيارات:</span>
                                <span class="stat-value">${userData.total_visits}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">🏆</span>
                                <span class="stat-label">أطول سلسلة:</span>
                                <span class="stat-value">${userData.max_streak}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">⭐</span>
                                <span class="stat-label">النقاط المكتسبة:</span>
                                <span class="stat-value">${userData.total_points}</span>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup: 'stats-popup'
                    }
                });
            }
        });
    }
    
    playRewardSound(soundType) {
        if (this.rewardSounds[soundType]) {
            const audio = new Audio(this.rewardSounds[soundType]);
            audio.volume = 0.3;
            audio.play().catch(e => console.log('Audio play failed:', e));
        }
    }
    
    makeAjaxRequest(action, data = {}) {
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: alamRewards.ajax_url,
                type: 'POST',
                data: {
                    action: `alam_${action}`,
                    nonce: alamRewards.nonce,
                    ...data
                },
                success: resolve,
                error: reject
            });
        });
    }
}

// Custom CSS for magical effects
const magicalCSS = `
    <style>
    .magical-reward-popup {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border: 3px solid #FFD700;
        border-radius: 20px;
        box-shadow: 0 0 50px rgba(255, 215, 0, 0.5);
        overflow: hidden;
        position: relative;
    }
    
    .magical-reward-container {
        position: relative;
        padding: 40px 20px;
        text-align: center;
        color: white;
        direction: rtl;
    }
    
    .reward-crown {
        position: relative;
        margin-bottom: 20px;
    }
    
    .crown-icon {
        font-size: 60px;
        display: inline-block;
        animation: float 3s ease-in-out infinite;
    }
    
    .reward-crown.week-complete .crown-icon {
        font-size: 80px;
        animation: float 3s ease-in-out infinite, glow 2s ease-in-out infinite alternate;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes glow {
        from { text-shadow: 0 0 10px #FFD700, 0 0 20px #FFD700, 0 0 30px #FFD700; }
        to { text-shadow: 0 0 20px #FFD700, 0 0 30px #FFD700, 0 0 40px #FFD700; }
    }
    
    .reward-sparkles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }
    
    .sparkle {
        position: absolute;
        font-size: 16px;
        animation: sparkle-float 4s linear infinite;
    }
    
    .sparkle-0 { top: 10%; left: 10%; animation-delay: 0s; }
    .sparkle-1 { top: 20%; right: 15%; animation-delay: 0.5s; }
    .sparkle-2 { top: 30%; left: 20%; animation-delay: 1s; }
    .sparkle-3 { top: 40%; right: 25%; animation-delay: 1.5s; }
    .sparkle-4 { top: 50%; left: 10%; animation-delay: 2s; }
    .sparkle-5 { top: 60%; right: 20%; animation-delay: 2.5s; }
    .sparkle-6 { top: 70%; left: 25%; animation-delay: 3s; }
    .sparkle-7 { top: 80%; right: 15%; animation-delay: 3.5s; }
    .sparkle-8 { top: 15%; left: 50%; animation-delay: 0.2s; }
    .sparkle-9 { top: 35%; right: 50%; animation-delay: 0.7s; }
    .sparkle-10 { top: 55%; left: 60%; animation-delay: 1.2s; }
    .sparkle-11 { top: 75%; right: 60%; animation-delay: 1.7s; }
    
    @keyframes sparkle-float {
        0%, 100% { transform: translateY(0px) scale(1); opacity: 0; }
        10%, 90% { opacity: 1; }
        50% { transform: translateY(-20px) scale(1.2); }
    }
    
    .reward-title {
        font-size: 24px;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .reward-badge {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        border-radius: 15px;
        padding: 20px;
        margin: 20px 0;
        display: flex;
        align-items: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .reward-icon {
        font-size: 40px;
        margin-left: 15px;
    }
    
    .reward-text {
        flex: 1;
        text-align: right;
    }
    
    .reward-name {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }
    
    .reward-description {
        font-size: 14px;
        color: #666;
    }
    
    .reward-progress {
        margin: 20px 0;
    }
    
    .progress-info {
        margin-bottom: 10px;
        font-size: 16px;
        font-weight: bold;
    }
    
    .progress-visual {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }
    
    .progress-step {
        position: relative;
    }
    
    .step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        border: 2px solid rgba(255,255,255,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .progress-step.completed .step-circle {
        background: #32CD32;
        border-color: #32CD32;
        transform: scale(1.1);
    }
    
    .progress-step.current .step-circle {
        background: #FFD700;
        border-color: #FFD700;
        animation: pulse 2s infinite;
    }
    
    .step-icon {
        font-size: 12px;
    }
    
    .step-connector {
        width: 20px;
        height: 2px;
        background: rgba(255,255,255,0.3);
        position: absolute;
        top: 50%;
        right: -22px;
        transform: translateY(-50%);
    }
    
    .progress-step:last-child .step-connector {
        display: none;
    }
    
    .week-complete-bonus {
        background: linear-gradient(135deg, #FF6B6B, #FF8E8E);
        border-radius: 10px;
        padding: 15px;
        margin: 15px 0;
        animation: bonus-glow 2s ease-in-out infinite alternate;
    }
    
    @keyframes bonus-glow {
        from { box-shadow: 0 0 10px rgba(255, 107, 107, 0.5); }
        to { box-shadow: 0 0 20px rgba(255, 107, 107, 0.8); }
    }
    
    .bonus-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .comeback-message {
        font-size: 14px;
        opacity: 0.9;
        margin-top: 15px;
    }
    
    .floating-icons {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
    }
    
    .floating-icon {
        position: absolute;
        font-size: 20px;
        opacity: 0.7;
    }
    
    .floating-icon:nth-child(1) { top: 20%; left: 10%; }
    .floating-icon:nth-child(2) { top: 30%; right: 15%; }
    .floating-icon:nth-child(3) { bottom: 30%; left: 15%; }
    .floating-icon:nth-child(4) { bottom: 20%; right: 10%; }
    
    .magical-confirm-btn {
        background: linear-gradient(135deg, #32CD32, #228B22) !important;
        border: none !important;
        border-radius: 25px !important;
        padding: 15px 30px !important;
        font-size: 16px !important;
        font-weight: bold !important;
        box-shadow: 0 5px 15px rgba(50, 205, 50, 0.3) !important;
        transition: all 0.3s ease !important;
    }
    
    .magical-confirm-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(50, 205, 50, 0.4) !important;
    }
    
    .success-popup {
        border: 3px solid #32CD32 !important;
        border-radius: 15px !important;
    }
    
    .stats-popup {
        border: 3px solid #667eea !important;
        border-radius: 15px !important;
    }
    
    .stats-container {
        display: grid;
        gap: 15px;
        margin: 20px 0;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        border-right: 4px solid #667eea;
    }
    
    .stat-icon {
        font-size: 24px;
        margin-left: 10px;
    }
    
    .stat-label {
        flex: 1;
        font-weight: bold;
        color: #333;
    }
    
    .stat-value {
        font-size: 18px;
        font-weight: bold;
        color: #667eea;
    }
    </style>
`;

// Initialize when DOM is ready
jQuery(document).ready(function($) {
    // Add magical CSS
    $('head').append(magicalCSS);
    
    // Initialize rewards system
    window.alamDailyRewards = new AlamDailyRewards();
});