/**
 * Advanced Product Customization JavaScript
 * Phase 5: Product Customization Features
 * Version: 8.2.0
 */

(function($) {
    'use strict';

    class AlamProductCustomization {
        constructor() {
            this.settings = {
                productsPerRow: this.getCustomizerValue('alam_products_per_row', 4),
                productsPerPage: this.getCustomizerValue('alam_products_per_page', 12),
                productCardStyle: this.getCustomizerValue('alam_product_card_style', 'modern_card'),
                quickView: this.getCustomizerValue('alam_product_quick_view', true),
                wishlist: this.getCustomizerValue('alam_product_wishlist', true),
                compare: this.getCustomizerValue('alam_product_compare', true),
                galleryLayout: this.getCustomizerValue('alam_gallery_layout', 'thumbnails_left'),
                galleryZoom: this.getCustomizerValue('alam_gallery_zoom', true),
                galleryLightbox: this.getCustomizerValue('alam_gallery_lightbox', true),
                colorSwatches: this.getCustomizerValue('alam_color_swatches', true),
                swatchStyle: this.getCustomizerValue('alam_swatch_style', 'circle'),
                swatchSize: this.getCustomizerValue('alam_swatch_size', 32),
                imageSwatches: this.getCustomizerValue('alam_image_swatches', true),
                saleBadge: this.getCustomizerValue('alam_sale_badge', true),
                newBadge: this.getCustomizerValue('alam_new_badge', true),
                hotBadge: this.getCustomizerValue('alam_hot_badge', true)
            };

            this.init();
            this.bindEvents();
            this.initSwatchSystem();
            this.initGallerySystem();
            this.initQuickActions();
            this.initWishlistSystem();
            this.initCompareSystem();
            this.initRecentlyViewed();
            this.setupCustomizerLivePreview();
        }

        getCustomizerValue(setting, defaultValue) {
            if (typeof wp !== 'undefined' && wp.customize && wp.customize(setting)) {
                return wp.customize(setting).get();
            }
            
            if (typeof alamProductData !== 'undefined' && alamProductData[setting]) {
                return alamProductData[setting];
            }
            
            return defaultValue;
        }

        init() {
            // Apply CSS variables
            this.applyCSSVariables();
            
            // Apply product card styles
            this.applyProductCardStyles();
            
            // Initialize grid layout
            this.initProductGrid();
            
            // Track product views
            this.trackProductView();
        }

        applyCSSVariables() {
            const root = document.documentElement;
            
            root.style.setProperty('--products-per-row', this.settings.productsPerRow);
            root.style.setProperty('--swatch-size', this.settings.swatchSize + 'px');
        }

        applyProductCardStyles() {
            $('.product-card').each((index, element) => {
                $(element).addClass(`style-${this.settings.productCardStyle}`);
            });
        }

        initProductGrid() {
            // Dynamic grid adjustment based on settings
            $('.products-grid, .woocommerce ul.products').css({
                'grid-template-columns': `repeat(${this.settings.productsPerRow}, 1fr)`
            });
        }

        // ===============================================
        // ADVANCED SWATCH SYSTEM
        // ===============================================
        initSwatchSystem() {
            if (!this.settings.colorSwatches && !this.settings.imageSwatches) return;

            this.generateSwatches();
            this.bindSwatchEvents();
        }

        generateSwatches() {
            $('.product-card').each((index, productCard) => {
                const $productCard = $(productCard);
                const productId = $productCard.data('product-id') || $productCard.find('[data-product-id]').data('product-id');
                
                if (!productId) return;

                // Generate color swatches
                if (this.settings.colorSwatches) {
                    this.generateColorSwatches($productCard, productId);
                }

                // Generate image swatches
                if (this.settings.imageSwatches) {
                    this.generateImageSwatches($productCard, productId);
                }
            });
        }

        generateColorSwatches($productCard, productId) {
            const colors = this.getProductColors(productId);
            if (!colors || colors.length === 0) return;

            const swatchesHtml = colors.map(color => {
                return `<div class="product-swatch color-swatch swatch-${this.settings.swatchStyle}" 
                             data-color="${color.value}" 
                             data-tooltip="${color.name}"
                             style="--swatch-color: ${color.value}">
                        </div>`;
            }).join('');

            const swatchContainer = `
                <div class="product-swatches">
                    <span class="product-swatches-label">${this.getSwatchLabel('color')}</span>
                    ${swatchesHtml}
                </div>
            `;

            $productCard.find('.product-info').append(swatchContainer);
        }

        generateImageSwatches($productCard, productId) {
            const images = this.getProductImages(productId);
            if (!images || images.length <= 1) return;

            const swatchesHtml = images.slice(0, 4).map((image, index) => {
                return `<div class="product-swatch image-swatch swatch-${this.settings.swatchStyle}" 
                             data-image="${image.url}" 
                             data-tooltip="${image.alt || 'صورة ' + (index + 1)}">
                            <img src="${image.thumb}" alt="${image.alt}">
                        </div>`;
            }).join('');

            const swatchContainer = `
                <div class="product-swatches image-swatches">
                    <span class="product-swatches-label">${this.getSwatchLabel('image')}</span>
                    ${swatchesHtml}
                </div>
            `;

            $productCard.find('.product-info').append(swatchContainer);
        }

        getProductColors(productId) {
            // This would typically fetch from AJAX or data attributes
            // For now, return sample colors
            return [
                { name: 'أحمر', value: '#ff0000' },
                { name: 'أزرق', value: '#0000ff' },
                { name: 'أخضر', value: '#00ff00' },
                { name: 'أسود', value: '#000000' }
            ];
        }

        getProductImages(productId) {
            // This would typically fetch from product data
            return [
                { url: '/placeholder1.jpg', thumb: '/placeholder1-thumb.jpg', alt: 'صورة 1' },
                { url: '/placeholder2.jpg', thumb: '/placeholder2-thumb.jpg', alt: 'صورة 2' },
                { url: '/placeholder3.jpg', thumb: '/placeholder3-thumb.jpg', alt: 'صورة 3' }
            ];
        }

        getSwatchLabel(type) {
            const labels = {
                'color': 'اللون:',
                'image': 'الصور:',
                'size': 'المقاس:',
                'material': 'المادة:'
            };
            return labels[type] || '';
        }

        bindSwatchEvents() {
            // Color swatch selection
            $(document).on('click', '.color-swatch', (e) => {
                const $swatch = $(e.currentTarget);
                const $container = $swatch.closest('.product-swatches');
                
                $container.find('.color-swatch').removeClass('selected');
                $swatch.addClass('selected');
                
                this.onSwatchChange($swatch, 'color');
            });

            // Image swatch selection
            $(document).on('click', '.image-swatch', (e) => {
                const $swatch = $(e.currentTarget);
                const $container = $swatch.closest('.product-swatches');
                const $productCard = $swatch.closest('.product-card');
                
                $container.find('.image-swatch').removeClass('selected');
                $swatch.addClass('selected');
                
                // Change main product image
                const newImageUrl = $swatch.data('image');
                if (newImageUrl) {
                    $productCard.find('.product-main-image').attr('src', newImageUrl);
                }
                
                this.onSwatchChange($swatch, 'image');
            });

            // Swatch hover effects
            $(document).on('mouseenter', '.product-swatch', (e) => {
                $(e.currentTarget).addClass('hover');
            }).on('mouseleave', '.product-swatch', (e) => {
                $(e.currentTarget).removeClass('hover');
            });
        }

        onSwatchChange($swatch, type) {
            // Trigger custom event for swatch change
            $(document).trigger('alamSwatchChange', {
                swatch: $swatch,
                type: type,
                value: $swatch.data(type === 'color' ? 'color' : 'image')
            });
        }

        // ===============================================
        // ADVANCED GALLERY SYSTEM
        // ===============================================
        initGallerySystem() {
            this.initGalleryLayout();
            
            if (this.settings.galleryZoom) {
                this.initGalleryZoom();
            }
            
            if (this.settings.galleryLightbox) {
                this.initGalleryLightbox();
            }
            
            this.bindGalleryEvents();
        }

        initGalleryLayout() {
            $('.product-gallery-container').each((index, gallery) => {
                const $gallery = $(gallery);
                $gallery.addClass(`gallery-layout-${this.settings.galleryLayout}`);
            });
        }

        initGalleryZoom() {
            $('.gallery-main-image').each((index, image) => {
                const $image = $(image);
                const $container = $image.closest('.gallery-zoom-container');
                
                if ($container.length === 0) {
                    $image.wrap('<div class="gallery-zoom-container"></div>');
                }
                
                $image.closest('.gallery-zoom-container').append(`
                    <div class="gallery-zoom-lens"></div>
                    <div class="gallery-zoom-result"></div>
                `);
            });
        }

        initGalleryLightbox() {
            $('.gallery-main-image').each((index, image) => {
                const $image = $(image);
                const $container = $image.parent();
                
                if (!$container.find('.gallery-lightbox-trigger').length) {
                    $container.append(`
                        <button class="gallery-lightbox-trigger" title="تكبير الصورة">
                            <i class="fas fa-expand"></i>
                        </button>
                    `);
                }
            });
        }

        bindGalleryEvents() {
            // Thumbnail click
            $(document).on('click', '.gallery-thumbnail', (e) => {
                const $thumb = $(e.currentTarget);
                const $gallery = $thumb.closest('.product-gallery-container');
                const newImageSrc = $thumb.data('large-image') || $thumb.attr('src');
                
                $gallery.find('.gallery-thumbnail').removeClass('active');
                $thumb.addClass('active');
                
                $gallery.find('.gallery-main-image').attr('src', newImageSrc);
            });

            // Zoom functionality
            $(document).on('mousemove', '.gallery-zoom-container', (e) => {
                this.handleZoomMove(e);
            });

            $(document).on('mouseleave', '.gallery-zoom-container', (e) => {
                $(e.currentTarget).find('.gallery-zoom-lens').css('opacity', 0);
            });

            // Lightbox trigger
            $(document).on('click', '.gallery-lightbox-trigger', (e) => {
                e.preventDefault();
                const $container = $(e.currentTarget).closest('.product-gallery-container');
                const imageSrc = $container.find('.gallery-main-image').attr('src');
                this.openLightbox(imageSrc);
            });

            // Slider dots
            $(document).on('click', '.gallery-dot', (e) => {
                const $dot = $(e.currentTarget);
                const index = $dot.index();
                const $gallery = $dot.closest('.product-gallery-container');
                
                $gallery.find('.gallery-dot').removeClass('active');
                $dot.addClass('active');
                
                this.showGalleryImage($gallery, index);
            });
        }

        handleZoomMove(e) {
            const $container = $(e.currentTarget);
            const $lens = $container.find('.gallery-zoom-lens');
            const $result = $container.find('.gallery-zoom-result');
            const $image = $container.find('.gallery-main-image');
            
            const containerRect = $container[0].getBoundingClientRect();
            const x = e.clientX - containerRect.left;
            const y = e.clientY - containerRect.top;
            
            const lensSize = 100;
            const lensX = Math.max(0, Math.min(x - lensSize/2, containerRect.width - lensSize));
            const lensY = Math.max(0, Math.min(y - lensSize/2, containerRect.height - lensSize));
            
            $lens.css({
                left: lensX + 'px',
                top: lensY + 'px',
                width: lensSize + 'px',
                height: lensSize + 'px',
                opacity: 1
            });
            
            // Update zoom result
            const zoomX = (lensX / containerRect.width) * 100;
            const zoomY = (lensY / containerRect.height) * 100;
            
            $result.css({
                'background-image': `url(${$image.attr('src')})`,
                'background-position': `${zoomX}% ${zoomY}%`,
                'background-size': '200%'
            });
        }

        openLightbox(imageSrc) {
            const lightboxHtml = `
                <div class="alam-lightbox" id="alamLightbox">
                    <div class="lightbox-overlay"></div>
                    <div class="lightbox-content">
                        <img src="${imageSrc}" alt="صورة مكبرة">
                        <button class="lightbox-close">&times;</button>
                    </div>
                </div>
            `;
            
            $('body').append(lightboxHtml);
            $('#alamLightbox').fadeIn(300);
            
            // Close lightbox events
            $(document).on('click', '.lightbox-close, .lightbox-overlay', () => {
                this.closeLightbox();
            });
            
            $(document).on('keyup', (e) => {
                if (e.keyCode === 27) { // ESC key
                    this.closeLightbox();
                }
            });
        }

        closeLightbox() {
            $('#alamLightbox').fadeOut(300, function() {
                $(this).remove();
            });
            $(document).off('keyup');
        }

        showGalleryImage($gallery, index) {
            const $images = $gallery.find('.gallery-thumbnail');
            if ($images.length > index) {
                const newImageSrc = $images.eq(index).data('large-image') || $images.eq(index).attr('src');
                $gallery.find('.gallery-main-image').attr('src', newImageSrc);
            }
        }

        // ===============================================
        // QUICK ACTIONS SYSTEM
        // ===============================================
        initQuickActions() {
            this.generateQuickActionButtons();
            this.bindQuickActionEvents();
        }

        generateQuickActionButtons() {
            $('.product-card').each((index, productCard) => {
                const $productCard = $(productCard);
                
                if ($productCard.find('.product-quick-actions').length > 0) return;
                
                let actionsHtml = '<div class="product-quick-actions">';
                
                if (this.settings.quickView) {
                    actionsHtml += `
                        <button class="quick-action-btn quick-view-btn" title="نظرة سريعة">
                            <i class="fas fa-eye"></i>
                        </button>
                    `;
                }
                
                if (this.settings.wishlist) {
                    actionsHtml += `
                        <button class="quick-action-btn wishlist-btn" title="إضافة للمفضلة">
                            <i class="fas fa-heart"></i>
                        </button>
                    `;
                }
                
                if (this.settings.compare) {
                    actionsHtml += `
                        <button class="quick-action-btn compare-btn" title="مقارنة">
                            <i class="fas fa-balance-scale"></i>
                        </button>
                    `;
                }
                
                actionsHtml += '</div>';
                
                $productCard.find('.product-image-container').append(actionsHtml);
            });
        }

        bindQuickActionEvents() {
            // Quick view
            $(document).on('click', '.quick-view-btn', (e) => {
                e.preventDefault();
                const productId = $(e.currentTarget).closest('.product-card').data('product-id');
                this.openQuickView(productId);
            });

            // Wishlist toggle
            $(document).on('click', '.wishlist-btn', (e) => {
                e.preventDefault();
                const $btn = $(e.currentTarget);
                const productId = $btn.closest('.product-card').data('product-id');
                this.toggleWishlist(productId, $btn);
            });

            // Compare toggle
            $(document).on('click', '.compare-btn', (e) => {
                e.preventDefault();
                const $btn = $(e.currentTarget);
                const productId = $btn.closest('.product-card').data('product-id');
                this.toggleCompare(productId, $btn);
            });
        }

        openQuickView(productId) {
            // Show loading spinner
            this.showLoadingSpinner();
            
            // AJAX request for product data
            $.ajax({
                url: alamAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'alam_quick_view',
                    product_id: productId,
                    nonce: alamAjax.nonce
                },
                success: (response) => {
                    this.hideLoadingSpinner();
                    if (response.success) {
                        this.displayQuickView(response.data);
                    }
                },
                error: () => {
                    this.hideLoadingSpinner();
                    this.showErrorMessage('حدث خطأ في تحميل المنتج');
                }
            });
        }

        displayQuickView(productData) {
            const quickViewHtml = `
                <div class="alam-quick-view" id="alamQuickView">
                    <div class="quick-view-overlay"></div>
                    <div class="quick-view-content">
                        <button class="quick-view-close">&times;</button>
                        <div class="quick-view-inner">
                            ${productData.html}
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(quickViewHtml);
            $('#alamQuickView').fadeIn(300);
            
            // Initialize quick view features
            this.initQuickViewFeatures();
            
            // Close events
            $(document).on('click', '.quick-view-close, .quick-view-overlay', () => {
                this.closeQuickView();
            });
        }

        closeQuickView() {
            $('#alamQuickView').fadeOut(300, function() {
                $(this).remove();
            });
        }

        initQuickViewFeatures() {
            // Re-initialize gallery system for quick view
            this.initGallerySystem();
            
            // Re-initialize swatch system for quick view
            this.initSwatchSystem();
        }

        // ===============================================
        // WISHLIST SYSTEM
        // ===============================================
        initWishlistSystem() {
            this.loadWishlistState();
        }

        toggleWishlist(productId, $btn) {
            const isInWishlist = $btn.hasClass('wishlist-active');
            
            $.ajax({
                url: alamAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: isInWishlist ? 'alam_remove_from_wishlist' : 'alam_add_to_wishlist',
                    product_id: productId,
                    nonce: alamAjax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        if (isInWishlist) {
                            $btn.removeClass('wishlist-active');
                            this.showSuccessMessage('تم إزالة المنتج من المفضلة');
                        } else {
                            $btn.addClass('wishlist-active');
                            this.showSuccessMessage('تم إضافة المنتج للمفضلة');
                        }
                        
                        this.updateWishlistCount(response.data.count);
                    }
                },
                error: () => {
                    this.showErrorMessage('حدث خطأ في تحديث المفضلة');
                }
            });
        }

        loadWishlistState() {
            const wishlistItems = this.getWishlistItems();
            
            $('.wishlist-btn').each((index, btn) => {
                const productId = $(btn).closest('.product-card').data('product-id');
                if (wishlistItems.includes(productId)) {
                    $(btn).addClass('wishlist-active');
                }
            });
        }

        getWishlistItems() {
            // Get from localStorage or user data
            const wishlist = localStorage.getItem('alam_wishlist');
            return wishlist ? JSON.parse(wishlist) : [];
        }

        updateWishlistCount(count) {
            $('.wishlist-count').text(count);
        }

        // ===============================================
        // COMPARE SYSTEM
        // ===============================================
        toggleCompare(productId, $btn) {
            const isInCompare = $btn.hasClass('compare-active');
            
            if (isInCompare) {
                this.removeFromCompare(productId, $btn);
            } else {
                this.addToCompare(productId, $btn);
            }
        }

        addToCompare(productId, $btn) {
            const compareItems = this.getCompareItems();
            
            if (compareItems.length >= 4) {
                this.showErrorMessage('لا يمكن مقارنة أكثر من 4 منتجات');
                return;
            }
            
            compareItems.push(productId);
            localStorage.setItem('alam_compare', JSON.stringify(compareItems));
            
            $btn.addClass('compare-active');
            this.updateCompareCount(compareItems.length);
            this.showSuccessMessage('تم إضافة المنتج للمقارنة');
        }

        removeFromCompare(productId, $btn) {
            let compareItems = this.getCompareItems();
            compareItems = compareItems.filter(id => id !== productId);
            
            localStorage.setItem('alam_compare', JSON.stringify(compareItems));
            
            $btn.removeClass('compare-active');
            this.updateCompareCount(compareItems.length);
            this.showSuccessMessage('تم إزالة المنتج من المقارنة');
        }

        getCompareItems() {
            const compare = localStorage.getItem('alam_compare');
            return compare ? JSON.parse(compare) : [];
        }

        updateCompareCount(count) {
            $('.compare-count').text(count);
            
            if (count > 0) {
                $('.compare-widget').addClass('has-items').show();
            } else {
                $('.compare-widget').removeClass('has-items').hide();
            }
        }

        // ===============================================
        // RECENTLY VIEWED SYSTEM
        // ===============================================
        trackProductView() {
            if ($('body').hasClass('single-product')) {
                const productId = this.getCurrentProductId();
                if (productId) {
                    this.addToRecentlyViewed(productId);
                }
            }
        }

        getCurrentProductId() {
            // Get product ID from body class or data attribute
            const bodyClasses = $('body').attr('class');
            const match = bodyClasses.match(/postid-(\d+)/);
            return match ? parseInt(match[1]) : null;
        }

        addToRecentlyViewed(productId) {
            let recentItems = this.getRecentlyViewedItems();
            
            // Remove if already exists
            recentItems = recentItems.filter(id => id !== productId);
            
            // Add to beginning
            recentItems.unshift(productId);
            
            // Keep only last 12 items
            recentItems = recentItems.slice(0, 12);
            
            localStorage.setItem('alam_recently_viewed', JSON.stringify(recentItems));
        }

        getRecentlyViewedItems() {
            const recent = localStorage.getItem('alam_recently_viewed');
            return recent ? JSON.parse(recent) : [];
        }

        initRecentlyViewed() {
            const recentItems = this.getRecentlyViewedItems();
            if (recentItems.length > 0) {
                this.displayRecentlyViewed(recentItems);
            }
        }

        displayRecentlyViewed(productIds) {
            // AJAX request to get product data
            $.ajax({
                url: alamAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'alam_get_recently_viewed',
                    product_ids: productIds,
                    nonce: alamAjax.nonce
                },
                success: (response) => {
                    if (response.success && response.data.html) {
                        $('.recently-viewed-container').html(response.data.html);
                    }
                }
            });
        }

        // ===============================================
        // UTILITY METHODS
        // ===============================================
        showLoadingSpinner() {
            if ($('.alam-loading-overlay').length === 0) {
                $('body').append(`
                    <div class="alam-loading-overlay">
                        <div class="loading-spinner">
                            <div class="spinner-modern-dots">
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                            </div>
                        </div>
                    </div>
                `);
            }
        }

        hideLoadingSpinner() {
            $('.alam-loading-overlay').fadeOut(300, function() {
                $(this).remove();
            });
        }

        showSuccessMessage(message) {
            this.showNotification(message, 'success');
        }

        showErrorMessage(message) {
            this.showNotification(message, 'error');
        }

        showNotification(message, type) {
            const notificationHtml = `
                <div class="alam-notification ${type}">
                    <div class="notification-content">
                        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i>
                        <span>${message}</span>
                        <button class="notification-close">&times;</button>
                    </div>
                </div>
            `;
            
            $('body').append(notificationHtml);
            
            const $notification = $('.alam-notification').last();
            $notification.slideDown(300);
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                $notification.slideUp(300, function() {
                    $(this).remove();
                });
            }, 3000);
            
            // Manual close
            $notification.find('.notification-close').on('click', () => {
                $notification.slideUp(300, function() {
                    $(this).remove();
                });
            });
        }

        bindEvents() {
            // Initialize when new products are loaded (AJAX)
            $(document).on('alam_products_loaded', () => {
                this.generateSwatches();
                this.generateQuickActionButtons();
                this.loadWishlistState();
            });
        }

        setupCustomizerLivePreview() {
            if (typeof wp === 'undefined' || !wp.customize) return;

            // Live preview for product customization settings
            wp.customize('alam_products_per_row', (value) => {
                value.bind((newval) => {
                    this.settings.productsPerRow = newval;
                    this.applyCSSVariables();
                    this.initProductGrid();
                });
            });

            wp.customize('alam_product_card_style', (value) => {
                value.bind((newval) => {
                    // Remove old style classes
                    $('.product-card').removeClass(function(index, className) {
                        return (className.match(/(^|\s)style-\S+/g) || []).join(' ');
                    });
                    
                    // Apply new style
                    this.settings.productCardStyle = newval;
                    this.applyProductCardStyles();
                });
            });

            wp.customize('alam_swatch_size', (value) => {
                value.bind((newval) => {
                    this.settings.swatchSize = newval;
                    this.applyCSSVariables();
                });
            });
        }
    }

    // Initialize when DOM is ready
    $(document).ready(() => {
        new AlamProductCustomization();
    });

    // Export for global access
    window.AlamProductCustomization = AlamProductCustomization;

})(jQuery);

// Additional CSS for notifications and overlays
$('<style>').appendTo('head').text(`
    .alam-loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .alam-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 10001;
        display: none;
        min-width: 300px;
    }
    
    .alam-notification.success {
        border-left: 4px solid #4CAF50;
    }
    
    .alam-notification.error {
        border-left: 4px solid #f44336;
    }
    
    .notification-content {
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .notification-content i {
        color: #4CAF50;
    }
    
    .alam-notification.error .notification-content i {
        color: #f44336;
    }
    
    .notification-close {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: #999;
    }
    
    .alam-lightbox,
    .alam-quick-view {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        display: none;
    }
    
    .lightbox-overlay,
    .quick-view-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
    }
    
    .lightbox-content,
    .quick-view-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border-radius: 8px;
        max-width: 90vw;
        max-height: 90vh;
        overflow: auto;
    }
    
    .lightbox-close,
    .quick-view-close {
        position: absolute;
        top: 10px;
        right: 15px;
        background: none;
        border: none;
        font-size: 2rem;
        cursor: pointer;
        color: #999;
        z-index: 10;
    }
    
    .quick-view-inner {
        padding: 2rem;
    }
`);
