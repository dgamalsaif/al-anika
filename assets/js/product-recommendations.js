/**
 * Product Recommendations JavaScript
 * منظومة التوصيات التفاعلية للمنتجات
 */

class AlamProductRecommendations {
    constructor() {
        this.swipers = {};
        this.loadingStates = {};
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.initializeSliders();
        this.loadInitialRecommendations();
    }
    
    bindEvents() {
        // Tab switching
        jQuery(document).on('click', '.tab-item', (e) => {
            this.switchTab(e.currentTarget);
        });
        
        // Floating recommendations toggle
        jQuery(document).on('click', '.floating-toggle', (e) => {
            this.toggleFloatingRecommendations();
        });
        
        // Refresh recommendations
        jQuery(document).on('click', '.refresh-recommendations', (e) => {
            this.refreshRecommendations();
        });
        
        // Product actions
        jQuery(document).on('click', '.add-to-cart-btn', (e) => {
            this.addToCart(e.currentTarget);
        });
        
        jQuery(document).on('click', '.quick-view-btn', (e) => {
            this.quickView(e.currentTarget);
        });
        
        jQuery(document).on('click', '.add-to-wishlist-btn', (e) => {
            this.addToWishlist(e.currentTarget);
        });
        
        jQuery(document).on('click', '.add-to-compare-btn', (e) => {
            this.addToCompare(e.currentTarget);
        });
        
        // Card hover effects
        jQuery(document).on('mouseenter', '.product-recommendation-card', (e) => {
            this.animateCardHover(e.currentTarget, true);
        });
        
        jQuery(document).on('mouseleave', '.product-recommendation-card', (e) => {
            this.animateCardHover(e.currentTarget, false);
        });
    }
    
    initializeSliders() {
        // Pickup products slider
        if (jQuery('.pickup-products-slider').length) {
            this.swipers.pickup = new Swiper('.pickup-products-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: false,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2
                    },
                    1024: {
                        slidesPerView: 3
                    },
                    1280: {
                        slidesPerView: 4
                    }
                }
            });
        }
        
        // Related products sliders
        this.initTabSliders();
        
        // Floating recommendations slider
        if (jQuery('.floating-slider').length) {
            this.swipers.floating = new Swiper('.floating-slider', {
                slidesPerView: 2,
                spaceBetween: 15,
                loop: true,
                autoplay: {
                    delay: 3000
                },
                breakpoints: {
                    480: {
                        slidesPerView: 3
                    },
                    768: {
                        slidesPerView: 4
                    }
                }
            });
        }
    }
    
    initTabSliders() {
        const sliderConfigs = {
            'smart-related': { selector: '.tab-content#smart-related .products-slider' },
            'same-category': { selector: '.tab-content#same-category .products-slider' },
            'trending': { selector: '.tab-content#trending .products-slider' }
        };
        
        Object.keys(sliderConfigs).forEach(key => {
            const config = sliderConfigs[key];
            if (jQuery(config.selector).length) {
                this.swipers[key] = new Swiper(config.selector, {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: false,
                    pagination: {
                        el: config.selector + ' .swiper-pagination',
                        clickable: true
                    },
                    navigation: {
                        nextEl: config.selector + ' .swiper-button-next',
                        prevEl: config.selector + ' .swiper-button-prev'
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2
                        },
                        1024: {
                            slidesPerView: 3
                        },
                        1280: {
                            slidesPerView: 4
                        },
                        1536: {
                            slidesPerView: 5
                        }
                    }
                });
            }
        });
    }
    
    loadInitialRecommendations() {
        // Load pickup products
        this.loadPickupProducts();
        
        // Load smart related products (default tab)
        if (alamRecommendations.current_product_id) {
            this.loadRelatedProducts('smart-related');
        }
        
        // Load floating recommendations
        this.loadFloatingRecommendations();
    }
    
    async loadPickupProducts() {
        this.setLoadingState('pickup', true);
        
        try {
            const response = await this.makeAjaxRequest('get_pickup_products', {
                limit: 4
            });
            
            if (response.success) {
                const wrapper = jQuery('#pickup-products-wrapper');
                wrapper.html(response.data.html);
                
                // Reinitialize slider
                if (this.swipers.pickup) {
                    this.swipers.pickup.update();
                }
                
                // Animate cards
                this.animateCardsEntry('#pickup-products-wrapper .product-recommendation-card');
            }
        } catch (error) {
            console.error('Error loading pickup products:', error);
            this.showError('#pickup-products-wrapper');
        } finally {
            this.setLoadingState('pickup', false);
        }
    }
    
    async loadRelatedProducts(type) {
        if (!alamRecommendations.current_product_id) return;
        
        this.setLoadingState(type, true);
        
        try {
            const response = await this.makeAjaxRequest('get_related_products', {
                product_id: alamRecommendations.current_product_id,
                limit: 8
            });
            
            if (response.success) {
                const wrapper = jQuery(`#${type}-wrapper`);
                wrapper.html(response.data.html);
                
                // Reinitialize slider
                if (this.swipers[type]) {
                    this.swipers[type].update();
                }
                
                // Animate cards
                this.animateCardsEntry(`#${type}-wrapper .product-recommendation-card`);
            }
        } catch (error) {
            console.error(`Error loading ${type} products:`, error);
            this.showError(`#${type}-wrapper`);
        } finally {
            this.setLoadingState(type, false);
        }
    }
    
    async loadFloatingRecommendations() {
        this.setLoadingState('floating', true);
        
        try {
            const response = await this.makeAjaxRequest('get_recommended_products', {
                limit: 6
            });
            
            if (response.success) {
                const wrapper = jQuery('#floating-recommendations-wrapper');
                wrapper.html(response.data.html);
                
                // Reinitialize slider
                if (this.swipers.floating) {
                    this.swipers.floating.update();
                }
            }
        } catch (error) {
            console.error('Error loading floating recommendations:', error);
        } finally {
            this.setLoadingState('floating', false);
        }
    }
    
    switchTab(tabElement) {
        const tab = jQuery(tabElement);
        const tabId = tab.data('tab');
        
        // Update tab states
        tab.siblings().removeClass('active');
        tab.addClass('active');
        
        // Update content states
        jQuery('.tab-content').removeClass('active');
        jQuery(`#${tabId}`).addClass('active');
        
        // Load content if not loaded
        if (!this.loadingStates[tabId] && jQuery(`#${tabId}-wrapper`).children().length === 0) {
            if (tabId === 'smart-related') {
                this.loadRelatedProducts('smart-related');
            } else if (tabId === 'same-category') {
                this.loadSameCategoryProducts();
            } else if (tabId === 'trending') {
                this.loadTrendingProducts();
            }
        }
        
        // Animate tab switch
        gsap.fromTo(`#${tabId}`, 
            { opacity: 0, x: 20 },
            { opacity: 1, x: 0, duration: 0.3 }
        );
    }
    
    async loadSameCategoryProducts() {
        // Implementation for same category products
        this.loadRelatedProducts('same-category');
    }
    
    async loadTrendingProducts() {
        this.setLoadingState('trending', true);
        
        try {
            const response = await this.makeAjaxRequest('get_recommended_products', {
                limit: 8,
                type: 'trending'
            });
            
            if (response.success) {
                const wrapper = jQuery('#trending-wrapper');
                wrapper.html(response.data.html);
                
                if (this.swipers.trending) {
                    this.swipers.trending.update();
                }
                
                this.animateCardsEntry('#trending-wrapper .product-recommendation-card');
            }
        } catch (error) {
            console.error('Error loading trending products:', error);
            this.showError('#trending-wrapper');
        } finally {
            this.setLoadingState('trending', false);
        }
    }
    
    toggleFloatingRecommendations() {
        const floating = jQuery('#floating-recommendations');
        const content = floating.find('.floating-content');
        const toggle = floating.find('.toggle-icon');
        
        if (floating.hasClass('expanded')) {
            // Collapse
            gsap.to(content, {
                height: 0,
                opacity: 0,
                duration: 0.3,
                onComplete: () => {
                    floating.removeClass('expanded');
                    toggle.text('⬇️');
                }
            });
        } else {
            // Expand
            floating.addClass('expanded');
            toggle.text('⬆️');
            
            gsap.fromTo(content, 
                { height: 0, opacity: 0 },
                { height: 'auto', opacity: 1, duration: 0.3 }
            );
        }
    }
    
    refreshRecommendations() {
        this.loadFloatingRecommendations();
        
        // Show refresh animation
        const refreshBtn = jQuery('.refresh-recommendations');
        gsap.to(refreshBtn.find('🔄'), {
            rotation: 360,
            duration: 0.5
        });
    }
    
    async addToCart(button) {
        const btn = jQuery(button);
        const productId = btn.data('product-id');
        
        // Animate button
        btn.addClass('loading');
        btn.prop('disabled', true);
        
        try {
            // Add to cart via AJAX (assuming WooCommerce AJAX add to cart)
            const response = await jQuery.post(wc_add_to_cart_params.ajax_url, {
                action: 'woocommerce_add_to_cart',
                product_id: productId,
                quantity: 1
            });
            
            // Success animation
            btn.removeClass('loading').addClass('success');
            btn.find('.text').text('تمت الإضافة');
            btn.find('.icon').text('✅');
            
            // Show success notification
            this.showNotification('تم إضافة المنتج للسلة بنجاح', 'success');
            
            // Revert button after delay
            setTimeout(() => {
                btn.removeClass('success');
                btn.find('.text').text('أضف للسلة');
                btn.find('.icon').text('🛒');
                btn.prop('disabled', false);
            }, 2000);
            
        } catch (error) {
            console.error('Error adding to cart:', error);
            btn.removeClass('loading').addClass('error');
            this.showNotification('حدث خطأ أثناء إضافة المنتج', 'error');
            
            setTimeout(() => {
                btn.removeClass('error');
                btn.prop('disabled', false);
            }, 2000);
        }
    }
    
    quickView(button) {
        const productId = jQuery(button).data('product-id');
        
        // Show quick view modal (implementation depends on your quick view system)
        console.log('Quick view for product:', productId);
        
        // Animate button
        gsap.to(button, {
            scale: 1.2,
            duration: 0.1,
            yoyo: true,
            repeat: 1
        });
    }
    
    addToWishlist(button) {
        const btn = jQuery(button);
        const productId = btn.data('product-id');
        
        // Toggle wishlist state
        btn.toggleClass('active');
        
        // Animate heart
        gsap.to(btn.find('.icon'), {
            scale: 1.3,
            duration: 0.2,
            yoyo: true,
            repeat: 1
        });
        
        const isActive = btn.hasClass('active');
        this.showNotification(
            isActive ? 'تم إضافة المنتج لقائمة الأمنيات' : 'تم حذف المنتج من قائمة الأمنيات',
            'info'
        );
    }
    
    addToCompare(button) {
        const btn = jQuery(button);
        const productId = btn.data('product-id');
        
        // Toggle compare state
        btn.toggleClass('active');
        
        // Animate scale
        gsap.to(btn.find('.icon'), {
            scale: 1.3,
            duration: 0.2,
            yoyo: true,
            repeat: 1
        });
        
        const isActive = btn.hasClass('active');
        this.showNotification(
            isActive ? 'تم إضافة المنتج للمقارنة' : 'تم حذف المنتج من المقارنة',
            'info'
        );
    }
    
    animateCardHover(card, isEntering) {
        const cardEl = jQuery(card);
        const overlay = cardEl.find('.card-overlay');
        const image = cardEl.find('.card-image img');
        const actions = cardEl.find('.quick-actions');
        
        if (isEntering) {
            gsap.to(overlay, { opacity: 1, duration: 0.3 });
            gsap.to(image, { scale: 1.05, duration: 0.3 });
            gsap.fromTo(actions.children(), 
                { y: 20, opacity: 0 },
                { y: 0, opacity: 1, duration: 0.3, stagger: 0.1 }
            );
        } else {
            gsap.to(overlay, { opacity: 0, duration: 0.3 });
            gsap.to(image, { scale: 1, duration: 0.3 });
            gsap.to(actions.children(), { y: 20, opacity: 0, duration: 0.2 });
        }
    }
    
    animateCardsEntry(selector) {
        gsap.fromTo(selector, 
            { opacity: 0, y: 30, scale: 0.9 },
            { 
                opacity: 1, 
                y: 0, 
                scale: 1, 
                duration: 0.6,
                stagger: 0.1,
                ease: "back.out(1.7)"
            }
        );
    }
    
    setLoadingState(type, isLoading) {
        this.loadingStates[type] = isLoading;
        
        if (isLoading) {
            const wrapper = jQuery(`#${type}-wrapper, #${type}-products-wrapper`);
            wrapper.html(this.getLoadingHTML());
        }
    }
    
    getLoadingHTML() {
        return `
            <div class="swiper-slide loading-slide">
                <div class="loading-spinner">
                    <div class="spinner"></div>
                    <p>جاري التحميل...</p>
                </div>
            </div>
        `;
    }
    
    showError(selector) {
        jQuery(selector).html(`
            <div class="swiper-slide error-slide">
                <div class="error-message">
                    <span class="error-icon">⚠️</span>
                    <p>حدث خطأ أثناء تحميل المنتجات</p>
                    <button onclick="location.reload()" class="retry-btn">إعادة المحاولة</button>
                </div>
            </div>
        `);
    }
    
    showNotification(message, type = 'info') {
        const notification = jQuery(`
            <div class="alam-notification ${type}">
                <span class="notification-message">${message}</span>
                <button class="notification-close">×</button>
            </div>
        `);
        
        jQuery('body').append(notification);
        
        // Animate in
        gsap.fromTo(notification, 
            { opacity: 0, x: 100 },
            { opacity: 1, x: 0, duration: 0.3 }
        );
        
        // Auto hide
        setTimeout(() => {
            gsap.to(notification, {
                opacity: 0,
                x: 100,
                duration: 0.3,
                onComplete: () => notification.remove()
            });
        }, 3000);
        
        // Manual close
        notification.find('.notification-close').on('click', () => {
            gsap.to(notification, {
                opacity: 0,
                x: 100,
                duration: 0.3,
                onComplete: () => notification.remove()
            });
        });
    }
    
    makeAjaxRequest(action, data = {}) {
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: alamRecommendations.ajax_url,
                type: 'POST',
                data: {
                    action: `alam_${action}`,
                    nonce: alamRecommendations.nonce,
                    ...data
                },
                success: resolve,
                error: reject
            });
        });
    }
}

// Initialize when DOM is ready
jQuery(document).ready(function($) {
    window.alamProductRecommendations = new AlamProductRecommendations();
});