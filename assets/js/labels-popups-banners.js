/**
 * Labels, Popups and Banners JavaScript
 * نظام الشارات والنوافذ المنبثقة واللافتات التفاعلية
 */

class AlamLabelsPopupsBanners {
    constructor() {
        this.exitIntentShown = false;
        this.newsletterShown = false;
        this.countdownInterval = null;
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.initializeComponents();
        this.checkDismissedBanners();
        this.startCountdowns();
        this.setupPopupTriggers();
    }
    
    bindEvents() {
        // Banner close buttons
        jQuery(document).on('click', '.banner-close', (e) => {
            this.dismissBanner(e.currentTarget);
        });
        
        // Popup close buttons
        jQuery(document).on('click', '.popup-close, .popup-overlay', (e) => {
            this.closePopup(e.currentTarget);
        });
        
        // Cookie notice actions
        jQuery(document).on('click', '.cookie-accept', () => {
            this.acceptCookies();
        });
        
        jQuery(document).on('click', '.cookie-decline', () => {
            this.declineCookies();
        });
        
        jQuery(document).on('click', '.cookie-customize', () => {
            this.showCookieCustomization();
        });
        
        // Floating banner minimize
        jQuery(document).on('click', '.banner-minimize', () => {
            this.minimizeFloatingBanner();
        });
        
        // Form submissions
        jQuery(document).on('submit', '#exit-intent-form', (e) => {
            this.handleExitIntentForm(e);
        });
        
        jQuery(document).on('submit', '#newsletter-form', (e) => {
            this.handleNewsletterForm(e);
        });
        
        // Exit intent detection
        jQuery(document).on('mouseout', (e) => {
            this.detectExitIntent(e);
        });
        
        // Keyboard events
        jQuery(document).on('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllPopups();
            }
        });
    }
    
    initializeComponents() {
        // Animate labels on product cards
        this.animateProductLabels();
        
        // Initialize floating banners
        this.initFloatingBanners();
        
        // Setup banner auto-hide
        if (alamBanners.settings.banner_auto_hide) {
            this.setupBannerAutoHide();
        }
    }
    
    checkDismissedBanners() {
        // Check for dismissed banners and hide them
        const dismissedBanners = this.getDismissedBanners();
        
        dismissedBanners.forEach(bannerId => {
            const banner = jQuery(`[data-banner-id="${bannerId}"]`);
            if (banner.length) {
                banner.hide();
            }
        });
    }
    
    startCountdowns() {
        // Start sale countdown
        this.startSaleCountdown();
        
        // Start any other countdowns
        this.startEventCountdowns();
    }
    
    setupPopupTriggers() {
        // Newsletter popup trigger (time-based)
        setTimeout(() => {
            if (!this.newsletterShown && !this.isPopupDismissed('newsletter')) {
                this.showNewsletterPopup();
            }
        }, alamBanners.settings.popup_delay);
        
        // Scroll-based triggers
        jQuery(window).on('scroll', () => {
            this.handleScrollTriggers();
        });
        
        // Time-based triggers
        this.setupTimedTriggers();
    }
    
    animateProductLabels() {
        const labels = jQuery('.alam-product-labels .product-label');
        
        labels.each((index, label) => {
            const $label = jQuery(label);
            
            // Stagger animation
            setTimeout(() => {
                $label.addClass('animate-in');
            }, index * 100);
            
            // Add hover effects
            $label.on('mouseenter', function() {
                jQuery(this).addClass('hover-effect');
            }).on('mouseleave', function() {
                jQuery(this).removeClass('hover-effect');
            });
        });
    }
    
    initFloatingBanners() {
        const floatingBanner = jQuery('#floating-sale-banner');
        
        if (floatingBanner.length && !this.isBannerDismissed('floating-sale')) {
            setTimeout(() => {
                floatingBanner.addClass('show');
                this.animateFloatingBanner();
            }, 2000);
        }
    }
    
    animateFloatingBanner() {
        const banner = jQuery('#floating-sale-banner');
        
        // Slide in animation
        gsap.fromTo(banner, 
            { x: '100%', opacity: 0 },
            { x: '0%', opacity: 1, duration: 0.8, ease: "back.out(1.7)" }
        );
        
        // Pulsing CTA button
        gsap.to(banner.find('.banner-cta'), {
            scale: 1.05,
            duration: 1,
            repeat: -1,
            yoyo: true,
            ease: "power2.inOut"
        });
    }
    
    startSaleCountdown() {
        const countdownElement = jQuery('#sale-countdown');
        if (!countdownElement.length) return;
        
        // Set end time (2 hours from now for demo)
        const endTime = new Date().getTime() + (2 * 60 * 60 * 1000);
        
        this.countdownInterval = setInterval(() => {
            const now = new Date().getTime();
            const timeLeft = endTime - now;
            
            if (timeLeft < 0) {
                countdownElement.html('<span class="countdown-ended">انتهى العرض!</span>');
                clearInterval(this.countdownInterval);
                return;
            }
            
            const hours = Math.floor(timeLeft / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
            
            countdownElement.html(`
                <div class="countdown-item">
                    <span class="countdown-number">${String(hours).padStart(2, '0')}</span>
                    <span class="countdown-label">ساعة</span>
                </div>
                <div class="countdown-separator">:</div>
                <div class="countdown-item">
                    <span class="countdown-number">${String(minutes).padStart(2, '0')}</span>
                    <span class="countdown-label">دقيقة</span>
                </div>
                <div class="countdown-separator">:</div>
                <div class="countdown-item">
                    <span class="countdown-number">${String(seconds).padStart(2, '0')}</span>
                    <span class="countdown-label">ثانية</span>
                </div>
            `);
        }, 1000);
    }
    
    startEventCountdowns() {
        // Add any additional countdown timers here
    }
    
    detectExitIntent(e) {
        if (this.exitIntentShown || !alamBanners.settings.show_exit_intent) {
            return;
        }
        
        if (e.clientY <= 0 || e.clientX <= 0 || 
            (e.clientX >= window.innerWidth || e.clientY >= window.innerHeight)) {
            
            this.showExitIntentPopup();
            this.exitIntentShown = true;
        }
    }
    
    showExitIntentPopup() {
        if (this.isPopupDismissed('exit-intent')) {
            return;
        }
        
        const popup = jQuery('#exit-intent-popup');
        popup.addClass('show');
        
        // Animate popup
        gsap.fromTo(popup.find('.popup-container'), 
            { scale: 0.7, opacity: 0, y: 50 },
            { scale: 1, opacity: 1, y: 0, duration: 0.5, ease: "back.out(1.7)" }
        );
        
        // Animate features
        gsap.fromTo(popup.find('.popup-features .feature'), 
            { x: -30, opacity: 0 },
            { x: 0, opacity: 1, duration: 0.4, stagger: 0.1, delay: 0.3 }
        );
        
        // Focus on email input
        setTimeout(() => {
            popup.find('input[type="email"]').focus();
        }, 500);
        
        // Auto-hide after 30 seconds if no interaction
        setTimeout(() => {
            if (popup.hasClass('show')) {
                this.closePopup(popup[0]);
            }
        }, 30000);
    }
    
    showNewsletterPopup() {
        if (this.isPopupDismissed('newsletter')) {
            return;
        }
        
        const popup = jQuery('#newsletter-popup');
        popup.addClass('show');
        this.newsletterShown = true;
        
        // Animate popup
        gsap.fromTo(popup.find('.popup-container'), 
            { scale: 0.8, opacity: 0, rotationY: 15 },
            { scale: 1, opacity: 1, rotationY: 0, duration: 0.6, ease: "power2.out" }
        );
        
        // Animate floating elements
        gsap.fromTo(popup.find('.floating-element'), 
            { scale: 0, rotation: 0 },
            { 
                scale: 1, 
                rotation: 360, 
                duration: 0.8, 
                stagger: 0.2,
                delay: 0.3,
                ease: "back.out(1.7)"
            }
        );
        
        // Continuous floating animation
        gsap.to(popup.find('.floating-element'), {
            y: -10,
            duration: 2,
            repeat: -1,
            yoyo: true,
            stagger: 0.3,
            ease: "power2.inOut"
        });
    }
    
    handleScrollTriggers() {
        const scrollPercent = (jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height())) * 100;
        
        // Show newsletter popup at 50% scroll
        if (scrollPercent > 50 && !this.newsletterShown && !this.isPopupDismissed('newsletter')) {
            this.showNewsletterPopup();
        }
        
        // Add scroll-based animations for labels
        this.animateLabelsOnScroll();
    }
    
    animateLabelsOnScroll() {
        const labels = jQuery('.alam-product-labels:not(.animated)');
        
        labels.each((index, labelContainer) => {
            const $container = jQuery(labelContainer);
            const elementTop = $container.offset().top;
            const elementBottom = elementTop + $container.outerHeight();
            const viewportTop = jQuery(window).scrollTop();
            const viewportBottom = viewportTop + jQuery(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $container.addClass('animated');
                
                const labels = $container.find('.product-label');
                gsap.fromTo(labels, 
                    { scale: 0, rotation: -180, opacity: 0 },
                    { 
                        scale: 1, 
                        rotation: 0, 
                        opacity: 1, 
                        duration: 0.5, 
                        stagger: 0.1,
                        ease: "back.out(1.7)"
                    }
                );
            }
        });
    }
    
    setupTimedTriggers() {
        // Show promotional popup after 1 minute of inactivity
        let inactivityTimer;
        
        const resetInactivityTimer = () => {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(() => {
                if (!this.isPopupDismissed('inactivity-promo')) {
                    this.showInactivityPromo();
                }
            }, 60000); // 1 minute
        };
        
        jQuery(document).on('mousemove scroll keypress click', resetInactivityTimer);
        resetInactivityTimer();
    }
    
    showInactivityPromo() {
        // Create and show inactivity promotion popup
        const promoPopup = this.createPromoPopup();
        jQuery('body').append(promoPopup);
        
        setTimeout(() => {
            promoPopup.addClass('show');
        }, 100);
    }
    
    createPromoPopup() {
        return jQuery(`
            <div class="alam-popup inactivity-promo-popup">
                <div class="popup-overlay"></div>
                <div class="popup-container">
                    <button class="popup-close">&times;</button>
                    <div class="popup-content">
                        <div class="promo-icon">⏰</div>
                        <h3>العرض ينتهي قريباً!</h3>
                        <p>لا تفوت الفرصة - احصل على خصم إضافي 10%</p>
                        <button class="popup-cta">تسوق الآن</button>
                    </div>
                </div>
            </div>
        `);
    }
    
    dismissBanner(button) {
        const banner = jQuery(button).closest('[data-banner-id]');
        const bannerId = banner.data('banner-id');
        
        // Animate out
        gsap.to(banner, {
            height: 0,
            opacity: 0,
            duration: 0.4,
            ease: "power2.inOut",
            onComplete: () => {
                banner.remove();
            }
        });
        
        // Remember dismissal
        this.rememberBannerDismissal(bannerId);
        
        // Send AJAX request
        this.makeAjaxRequest('dismiss_banner', { banner_id: bannerId });
    }
    
    closePopup(element) {
        const popup = jQuery(element).closest('.alam-popup');
        
        // Animate out
        gsap.to(popup.find('.popup-container'), {
            scale: 0.8,
            opacity: 0,
            y: 30,
            duration: 0.3,
            ease: "power2.in",
            onComplete: () => {
                popup.removeClass('show');
                
                // Remember dismissal for session
                const popupId = popup.attr('id') || 'unknown';
                this.rememberPopupDismissal(popupId);
            }
        });
        
        gsap.to(popup.find('.popup-overlay'), {
            opacity: 0,
            duration: 0.3
        });
    }
    
    closeAllPopups() {
        jQuery('.alam-popup.show').each((index, popup) => {
            this.closePopup(popup);
        });
    }
    
    minimizeFloatingBanner() {
        const banner = jQuery('#floating-sale-banner');
        
        if (banner.hasClass('minimized')) {
            // Restore
            banner.removeClass('minimized');
            gsap.to(banner, {
                height: 'auto',
                duration: 0.3
            });
        } else {
            // Minimize
            banner.addClass('minimized');
            gsap.to(banner, {
                height: '60px',
                duration: 0.3
            });
        }
    }
    
    handleExitIntentForm(e) {
        e.preventDefault();
        
        const form = jQuery(e.target);
        const email = form.find('input[type="email"]').val();
        
        if (!this.validateEmail(email)) {
            this.showFormError(form, 'يرجى إدخال بريد إلكتروني صحيح');
            return;
        }
        
        // Show loading
        form.find('button[type="submit"]').html('جاري الإرسال...').prop('disabled', true);
        
        // Simulate API call
        setTimeout(() => {
            this.showSuccessMessage(form, 'تم! تحقق من بريدك للحصول على كود الخصم');
            
            setTimeout(() => {
                this.closePopup(form[0]);
            }, 2000);
        }, 1500);
    }
    
    handleNewsletterForm(e) {
        e.preventDefault();
        
        const form = jQuery(e.target);
        const email = form.find('input[type="email"]').val();
        
        if (!this.validateEmail(email)) {
            this.showFormError(form, 'يرجى إدخال بريد إلكتروني صحيح');
            return;
        }
        
        // Show success animation
        const button = form.find('button[type="submit"]');
        button.html('✓ تم الاشتراك').addClass('success');
        
        setTimeout(() => {
            this.closePopup(form[0]);
        }, 1500);
    }
    
    acceptCookies() {
        this.setCookie('alam_cookies_accepted', 'true', 365);
        this.dismissBanner(jQuery('.cookie-notice .cookie-accept'));
    }
    
    declineCookies() {
        this.setCookie('alam_cookies_declined', 'true', 365);
        this.dismissBanner(jQuery('.cookie-notice .cookie-decline'));
    }
    
    showCookieCustomization() {
        // Create and show cookie customization modal
        console.log('Show cookie customization modal');
    }
    
    validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    showFormError(form, message) {
        form.find('.form-error').remove();
        form.append(`<div class="form-error">${message}</div>`);
        
        setTimeout(() => {
            form.find('.form-error').fadeOut();
        }, 3000);
    }
    
    showSuccessMessage(form, message) {
        form.find('.form-error').remove();
        form.append(`<div class="form-success">${message}</div>`);
    }
    
    rememberBannerDismissal(bannerId) {
        const dismissed = this.getDismissedBanners();
        dismissed.push(bannerId);
        localStorage.setItem('alam_dismissed_banners', JSON.stringify(dismissed));
    }
    
    rememberPopupDismissal(popupId) {
        const dismissed = this.getDismissedPopups();
        dismissed.push(popupId);
        sessionStorage.setItem('alam_dismissed_popups', JSON.stringify(dismissed));
    }
    
    getDismissedBanners() {
        try {
            return JSON.parse(localStorage.getItem('alam_dismissed_banners') || '[]');
        } catch {
            return [];
        }
    }
    
    getDismissedPopups() {
        try {
            return JSON.parse(sessionStorage.getItem('alam_dismissed_popups') || '[]');
        } catch {
            return [];
        }
    }
    
    isBannerDismissed(bannerId) {
        return this.getDismissedBanners().includes(bannerId);
    }
    
    isPopupDismissed(popupId) {
        return this.getDismissedPopups().includes(popupId);
    }
    
    setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    }
    
    getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    
    makeAjaxRequest(action, data = {}) {
        return jQuery.ajax({
            url: alamBanners.ajax_url,
            type: 'POST',
            data: {
                action: `alam_${action}`,
                nonce: alamBanners.nonce,
                ...data
            }
        });
    }
    
    setupBannerAutoHide() {
        // Auto-hide banners after specified time
        setTimeout(() => {
            jQuery('.alam-announcement-banner').each((index, banner) => {
                if (!jQuery(banner).hasClass('sticky')) {
                    this.dismissBanner(jQuery(banner).find('.banner-close'));
                }
            });
        }, 10000); // 10 seconds
    }
}

// Initialize when DOM is ready
jQuery(document).ready(function($) {
    window.alamLabelsPopupsBanners = new AlamLabelsPopupsBanners();
});