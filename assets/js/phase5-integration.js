/**
 * Phase 5: Enhanced Interactive Systems - Master Integration JavaScript
 * الدمج الشامل للأنظمة التفاعلية المتطورة
 */

class AlamPhase5Integration {
    constructor() {
        this.systems = {};
        this.performance = {
            startTime: performance.now(),
            loadTimes: {},
            errors: []
        };
        
        this.init();
    }
    
    init() {
        console.log('🚀 Initializing Alam Phase 5 Enhanced Interactive Systems...');
        
        this.bindGlobalEvents();
        this.initializeSystemCoordination();
        this.setupPerformanceMonitoring();
        this.enableAccessibilityFeatures();
        this.initializeMobileOptimizations();
        
        // Log initialization completion
        this.performance.loadTimes.initialization = performance.now() - this.performance.startTime;
        console.log(`✅ Phase 5 Systems Initialized in ${this.performance.loadTimes.initialization.toFixed(2)}ms`);
    }
    
    bindGlobalEvents() {
        // Global keyboard shortcuts
        jQuery(document).on('keydown', (e) => {
            this.handleGlobalKeyboard(e);
        });
        
        // Global click tracking for analytics
        jQuery(document).on('click', '[data-track]', (e) => {
            this.trackInteraction(e);
        });
        
        // Global error handling
        window.addEventListener('error', (e) => {
            this.handleGlobalError(e);
        });
        
        // Performance monitoring
        window.addEventListener('load', () => {
            this.measurePagePerformance();
        });
        
        // Visibility change handling
        document.addEventListener('visibilitychange', () => {
            this.handleVisibilityChange();
        });
    }
    
    initializeSystemCoordination() {
        // Coordinate between different systems
        this.systems = {
            rewards: window.alamDailyRewards || null,
            recommendations: window.alamProductRecommendations || null,
            popups: window.alamLabelsPopupsBanners || null
        };
        
        // Set up inter-system communication
        this.setupSystemCommunication();
        
        // Initialize cross-system features
        this.initializeCrossSystemFeatures();
    }
    
    setupSystemCommunication() {
        // Create event bus for system communication
        this.eventBus = jQuery({});
        
        // Register system events
        this.eventBus.on('reward-claimed', (e, data) => {
            this.handleRewardClaimed(data);
        });
        
        this.eventBus.on('product-viewed', (e, data) => {
            this.handleProductViewed(data);
        });
        
        this.eventBus.on('popup-shown', (e, data) => {
            this.handlePopupShown(data);
        });
        
        // Enable systems to communicate
        window.alamEventBus = this.eventBus;
    }
    
    initializeCrossSystemFeatures() {
        // Reward-based product recommendations
        this.setupRewardBasedRecommendations();
        
        // Smart popup triggering based on user behavior
        this.setupSmartPopupTriggers();
        
        // Unified notification system
        this.setupUnifiedNotifications();
        
        // Cross-system analytics
        this.setupCrossSystemAnalytics();
    }
    
    setupRewardBasedRecommendations() {
        // Show special recommendations for users with high reward streaks
        if (this.systems.rewards && this.systems.recommendations) {
            this.eventBus.on('reward-streak-milestone', (e, data) => {
                if (data.streak >= 7) {
                    this.showPremiumRecommendations();
                }
            });
        }
    }
    
    setupSmartPopupTriggers() {
        // Intelligent popup timing based on user engagement
        let engagementScore = 0;
        let interactionCount = 0;
        
        jQuery(document).on('click scroll mousemove', () => {
            interactionCount++;
            engagementScore = Math.min(100, interactionCount / 10);
            
            // Trigger personalized popups based on engagement
            if (engagementScore > 50 && !this.hasShownEngagementPopup) {
                this.showEngagementBasedPopup();
                this.hasShownEngagementPopup = true;
            }
        });
    }
    
    setupUnifiedNotifications() {
        // Create unified notification system
        this.notificationQueue = [];
        this.isShowingNotification = false;
        
        // Process notification queue
        setInterval(() => {
            this.processNotificationQueue();
        }, 3000);
    }
    
    setupCrossSystemAnalytics() {
        // Track cross-system interactions
        this.analytics = {
            rewardInteractions: 0,
            recommendationClicks: 0,
            popupInteractions: 0,
            totalEngagementTime: 0
        };
        
        // Start engagement timer
        this.startEngagementTimer();
    }
    
    setupPerformanceMonitoring() {
        if (!alamPhase5.settings.performance_mode) {
            return;
        }
        
        // Monitor system performance
        this.performanceMonitor = {
            memoryUsage: 0,
            loadTimes: {},
            errorCount: 0
        };
        
        // Check performance every 30 seconds
        setInterval(() => {
            this.checkPerformance();
        }, 30000);
    }
    
    enableAccessibilityFeatures() {
        // Enhanced keyboard navigation
        this.setupKeyboardNavigation();
        
        // Screen reader support
        this.setupScreenReaderSupport();
        
        // High contrast mode detection
        this.setupHighContrastMode();
        
        // Focus management
        this.setupFocusManagement();
    }
    
    initializeMobileOptimizations() {
        if (!alamPhase5.is_mobile) {
            return;
        }
        
        // Touch gesture support
        this.setupTouchGestures();
        
        // Mobile-specific UI adjustments
        this.applyMobileOptimizations();
        
        // Performance optimizations for mobile
        this.applyMobilePerformanceOptimizations();
    }
    
    handleGlobalKeyboard(e) {
        // Escape key - close all popups
        if (e.key === 'Escape') {
            this.eventBus.trigger('close-all-popups');
        }
        
        // Alt + R - show rewards progress
        if (e.altKey && e.key === 'r') {
            e.preventDefault();
            this.eventBus.trigger('show-rewards-progress');
        }
        
        // Alt + P - show product recommendations
        if (e.altKey && e.key === 'p') {
            e.preventDefault();
            this.eventBus.trigger('show-recommendations');
        }
    }
    
    trackInteraction(e) {
        const element = jQuery(e.currentTarget);
        const trackingData = {
            type: element.data('track'),
            element: element.prop('tagName'),
            timestamp: Date.now(),
            url: window.location.href,
            userId: alamPhase5.current_user_id
        };
        
        // Send tracking data
        this.sendAnalytics('interaction', trackingData);
    }
    
    handleGlobalError(e) {
        console.error('Global Error:', e.error);
        
        this.performance.errors.push({
            message: e.message,
            filename: e.filename,
            lineno: e.lineno,
            timestamp: Date.now()
        });
        
        // Show user-friendly error message if too many errors
        if (this.performance.errors.length > 5) {
            this.showSystemErrorNotification();
        }
    }
    
    measurePagePerformance() {
        const navigation = performance.getEntriesByType('navigation')[0];
        const paintEntries = performance.getEntriesByType('paint');
        
        this.performance.loadTimes = {
            domContentLoaded: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart,
            pageLoad: navigation.loadEventEnd - navigation.loadEventStart,
            firstPaint: paintEntries.find(entry => entry.name === 'first-paint')?.startTime || 0,
            firstContentfulPaint: paintEntries.find(entry => entry.name === 'first-contentful-paint')?.startTime || 0
        };
        
        // Send performance data
        this.sendAnalytics('performance', this.performance.loadTimes);
    }
    
    handleVisibilityChange() {
        if (document.hidden) {
            // Page is hidden - pause non-essential systems
            this.pauseNonEssentialSystems();
        } else {
            // Page is visible - resume systems
            this.resumeSystems();
        }
    }
    
    handleRewardClaimed(data) {
        // Show celebration animation
        this.showCelebrationAnimation(data);
        
        // Update recommendations based on reward
        if (this.systems.recommendations) {
            this.eventBus.trigger('update-recommendations', {
                reason: 'reward-claimed',
                rewardType: data.type
            });
        }
        
        // Track analytics
        this.analytics.rewardInteractions++;
    }
    
    handleProductViewed(data) {
        // Update recommendations
        if (this.systems.recommendations) {
            this.systems.recommendations.updateUserPreferences(data);
        }
        
        // Check if user should get viewing milestone reward
        this.checkViewingMilestone(data);
        
        // Track analytics
        this.analytics.recommendationClicks++;
    }
    
    handlePopupShown(data) {
        // Track popup analytics
        this.analytics.popupInteractions++;
        
        // Adjust future popup timing
        this.adjustPopupTiming(data);
    }
    
    showPremiumRecommendations() {
        if (!this.systems.recommendations) return;
        
        this.showNotification({
            type: 'success',
            title: '🏆 مبروك! أنت مستخدم مميز',
            message: 'تم فتح توصيات حصرية لك بناءً على إنجازاتك',
            duration: 5000
        });
        
        // Load premium recommendations
        this.systems.recommendations.loadPremiumRecommendations();
    }
    
    showEngagementBasedPopup() {
        this.showNotification({
            type: 'info',
            title: '💡 نلاحظ اهتمامك بمتجرنا',
            message: 'هل تود الحصول على توصيات شخصية؟',
            actions: [
                {
                    text: 'نعم، أريد التوصيات',
                    action: () => this.enablePersonalizedRecommendations()
                },
                {
                    text: 'لا، شكراً',
                    action: () => this.dismissEngagementPopup()
                }
            ]
        });
    }
    
    processNotificationQueue() {
        if (this.isShowingNotification || this.notificationQueue.length === 0) {
            return;
        }
        
        const notification = this.notificationQueue.shift();
        this.showNotificationToUser(notification);
    }
    
    showNotificationToUser(notification) {
        this.isShowingNotification = true;
        
        const notificationEl = this.createNotificationElement(notification);
        jQuery('body').append(notificationEl);
        
        // Animate in
        gsap.fromTo(notificationEl, 
            { x: 100, opacity: 0 },
            { x: 0, opacity: 1, duration: 0.5, ease: "back.out(1.7)" }
        );
        
        // Auto hide
        setTimeout(() => {
            this.hideNotification(notificationEl);
        }, notification.duration || 4000);
    }
    
    createNotificationElement(notification) {
        const actionsHtml = notification.actions ? 
            notification.actions.map(action => 
                `<button class="notification-action" data-action="${action.text}">${action.text}</button>`
            ).join('') : '';
        
        return jQuery(`
            <div class="alam-notification-system ${notification.type}">
                <div class="notification-content">
                    <h4 class="notification-title">${notification.title}</h4>
                    <p class="notification-message">${notification.message}</p>
                    ${actionsHtml}
                </div>
                <button class="notification-close">×</button>
            </div>
        `);
    }
    
    hideNotification(notificationEl) {
        gsap.to(notificationEl, {
            x: 100,
            opacity: 0,
            duration: 0.3,
            onComplete: () => {
                notificationEl.remove();
                this.isShowingNotification = false;
            }
        });
    }
    
    startEngagementTimer() {
        this.engagementStartTime = Date.now();
        
        setInterval(() => {
            if (!document.hidden) {
                this.analytics.totalEngagementTime += 1000; // Add 1 second
            }
        }, 1000);
    }
    
    checkPerformance() {
        if (performance.memory) {
            this.performanceMonitor.memoryUsage = performance.memory.usedJSHeapSize;
            
            // If memory usage is too high, enable performance mode
            if (this.performanceMonitor.memoryUsage > 50 * 1024 * 1024) { // 50MB
                this.enablePerformanceMode();
            }
        }
    }
    
    setupKeyboardNavigation() {
        // Enhanced tab navigation
        jQuery('body').on('keydown', (e) => {
            if (e.key === 'Tab') {
                this.handleTabNavigation(e);
            }
        });
    }
    
    setupScreenReaderSupport() {
        // Add ARIA labels to dynamic content
        jQuery('[data-dynamic-content]').attr('aria-live', 'polite');
        
        // Announce important changes
        this.eventBus.on('important-change', (e, data) => {
            this.announceToScreenReader(data.message);
        });
    }
    
    setupHighContrastMode() {
        // Detect high contrast mode
        if (window.matchMedia('(prefers-contrast: high)').matches) {
            jQuery('body').addClass('alam-high-contrast');
        }
    }
    
    setupFocusManagement() {
        // Manage focus for popups and dynamic content
        this.eventBus.on('popup-opened', (e, data) => {
            this.manageFocusForPopup(data.popup);
        });
    }
    
    setupTouchGestures() {
        // Swipe gestures for product cards
        let startX = 0;
        let startY = 0;
        
        jQuery(document).on('touchstart', '.product-recommendation-card', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });
        
        jQuery(document).on('touchend', '.product-recommendation-card', (e) => {
            const endX = e.changedTouches[0].clientX;
            const endY = e.changedTouches[0].clientY;
            
            const diffX = startX - endX;
            const diffY = startY - endY;
            
            // Horizontal swipe
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    // Swipe left - add to wishlist
                    this.handleSwipeLeft(e.currentTarget);
                } else {
                    // Swipe right - add to cart
                    this.handleSwipeRight(e.currentTarget);
                }
            }
        });
    }
    
    applyMobileOptimizations() {
        // Reduce animation intensity
        jQuery('body').addClass('alam-mobile-optimized');
        
        // Optimize touch targets
        jQuery('.product-recommendation-card, .banner-cta, .popup-close').addClass('touch-optimized');
        
        // Lazy load non-critical content
        this.setupMobileLazyLoading();
    }
    
    applyMobilePerformanceOptimizations() {
        // Reduce animation frame rate
        gsap.config({
            force3D: false,
            autoSleep: 60
        });
        
        // Disable non-essential features
        if (this.systems.popups) {
            this.systems.popups.setMobileMode(true);
        }
    }
    
    // Utility Methods
    showNotification(notification) {
        this.notificationQueue.push(notification);
    }
    
    sendAnalytics(type, data) {
        // Send analytics data to server
        jQuery.ajax({
            url: alamPhase5.ajax_url,
            type: 'POST',
            data: {
                action: 'alam_track_analytics',
                nonce: alamPhase5.nonce,
                type: type,
                data: JSON.stringify(data)
            }
        });
    }
    
    enablePerformanceMode() {
        console.log('🚀 Enabling Performance Mode...');
        jQuery('body').addClass('alam-performance-mode');
        
        // Notify all systems
        this.eventBus.trigger('performance-mode-enabled');
    }
    
    pauseNonEssentialSystems() {
        this.eventBus.trigger('pause-non-essential');
    }
    
    resumeSystems() {
        this.eventBus.trigger('resume-systems');
    }
    
    showCelebrationAnimation(data) {
        // Create celebration effect
        const celebration = jQuery(`
            <div class="alam-celebration-overlay">
                <div class="celebration-content">
                    <div class="celebration-icon">🎉</div>
                    <h3>مبروك!</h3>
                    <p>لقد حصلت على ${data.reward.title}</p>
                </div>
            </div>
        `);
        
        jQuery('body').append(celebration);
        
        // Animate
        gsap.fromTo(celebration, 
            { scale: 0, opacity: 0 },
            { scale: 1, opacity: 1, duration: 0.5, ease: "back.out(1.7)" }
        );
        
        // Remove after animation
        setTimeout(() => {
            gsap.to(celebration, {
                scale: 0,
                opacity: 0,
                duration: 0.3,
                onComplete: () => celebration.remove()
            });
        }, 2000);
    }
}

// Performance Monitor Class
class AlamPerformanceMonitor {
    constructor() {
        this.metrics = {
            startTime: performance.now(),
            interactions: 0,
            errors: []
        };
        
        this.init();
    }
    
    init() {
        this.setupMonitoring();
        this.startReporting();
    }
    
    setupMonitoring() {
        // Monitor long tasks
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                for (const entry of list.getEntries()) {
                    if (entry.duration > 50) {
                        console.warn('Long task detected:', entry);
                    }
                }
            });
            
            observer.observe({entryTypes: ['longtask']});
        }
    }
    
    startReporting() {
        // Report metrics every minute
        setInterval(() => {
            this.reportMetrics();
        }, 60000);
    }
    
    reportMetrics() {
        const metrics = {
            loadTime: performance.now() - this.metrics.startTime,
            interactions: this.metrics.interactions,
            errors: this.metrics.errors.length,
            memory: performance.memory ? performance.memory.usedJSHeapSize : 0
        };
        
        console.log('📊 Performance Metrics:', metrics);
    }
}

// Initialize when DOM is ready
jQuery(document).ready(function($) {
    // Add global styles
    $('head').append(`
        <style>
        .alam-notification-system {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 20px;
            max-width: 350px;
            z-index: 10001;
            direction: rtl;
        }
        
        .alam-notification-system.success { border-right: 4px solid #4ecdc4; }
        .alam-notification-system.error { border-right: 4px solid #ff6b6b; }
        .alam-notification-system.info { border-right: 4px solid #667eea; }
        
        .notification-title {
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: bold;
        }
        
        .notification-message {
            margin: 0 0 15px 0;
            color: #666;
            line-height: 1.5;
        }
        
        .notification-action {
            margin-left: 10px;
            padding: 8px 15px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .notification-close {
            position: absolute;
            top: 10px;
            left: 10px;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #999;
        }
        
        .alam-celebration-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10002;
        }
        
        .celebration-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            color: #333;
        }
        
        .celebration-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        .touch-optimized {
            min-height: 44px;
            min-width: 44px;
        }
        
        .alam-performance-mode * {
            animation-duration: 0.1s !important;
            transition-duration: 0.1s !important;
        }
        
        @media (max-width: 768px) {
            .alam-notification-system {
                right: 10px;
                left: 10px;
                max-width: none;
            }
        }
        </style>
    `);
    
    // Initialize the main Phase 5 system
    window.alamPhase5Integration = new AlamPhase5Integration();
});