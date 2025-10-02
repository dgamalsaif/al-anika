/**
 * Al-Anika Enhanced WooCommerce JavaScript
 * SHEIN-inspired functionality with professional improvements
 */

(function($) {
    'use strict';
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        AlAnikaWooCommerce.init();
    });
    
    const AlAnikaWooCommerce = {
        
        init: function() {
            this.quickView();
            this.wishlist();
            this.productGallery();
            this.variationSwatches();
            this.cartUpdates();
            this.productFilters();
            this.mobileOptimizations();
            this.recentlyViewed();
            this.sizeGuide();
            this.productShare();
        },
        
        /**
         * Quick View Functionality
         */
        quickView: function() {
            $(document).on('click', '.btn-quick-view', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                if (!productId) return;
                
                // Show loading
                $button.addClass('loading');
                
                // Create modal if doesn't exist
                if (!$('#quick-view-modal').length) {
                    $('body').append(`
                        <div id="quick-view-modal" class="al-anika-modal">
                            <div class="modal-overlay"></div>
                            <div class="modal-content">
                                <button class="modal-close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="modal-body"></div>
                            </div>
                        </div>
                    `);
                }
                
                // AJAX request
                $.ajax({
                    url: alAnikaWoo.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'al_anika_quick_view',
                        product_id: productId,
                        nonce: alAnikaWoo.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#quick-view-modal .modal-body').html(response.data);
                            $('#quick-view-modal').addClass('active');
                            $('body').addClass('modal-open');
                        }
                    },
                    complete: function() {
                        $button.removeClass('loading');
                    }
                });
            });
            
            // Close modal
            $(document).on('click', '.modal-close, .modal-overlay', function() {
                $('#quick-view-modal').removeClass('active');
                $('body').removeClass('modal-open');
                setTimeout(() => {
                    $('#quick-view-modal .modal-body').empty();
                }, 300);
            });
            
            // ESC key to close
            $(document).on('keyup', function(e) {
                if (e.keyCode === 27 && $('#quick-view-modal').hasClass('active')) {
                    $('#quick-view-modal').removeClass('active');
                    $('body').removeClass('modal-open');
                }
            });
        },
        
        /**
         * Wishlist Functionality
         */
        wishlist: function() {
            $(document).on('click', '.btn-wishlist, .btn-wishlist-single', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                if (!productId) return;
                
                $button.addClass('loading');
                
                $.ajax({
                    url: alAnikaWoo.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'al_anika_wishlist',
                        product_id: productId,
                        nonce: alAnikaWoo.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;
                            
                            // Update button state
                            if (data.action === 'added') {
                                $button.addClass('active');
                                $button.find('i').removeClass('far').addClass('fas');
                            } else {
                                $button.removeClass('active');
                                $button.find('i').removeClass('fas').addClass('far');
                            }
                            
                            // Update wishlist count
                            $('.wishlist-count').text(data.count);
                            
                            // Show notification
                            AlAnikaWooCommerce.showNotification(data.message, 'success');
                        }
                    },
                    complete: function() {
                        $button.removeClass('loading');
                    }
                });
            });
        },
        
        /**
         * Enhanced Product Gallery
         */
        productGallery: function() {
            // Product image hover effect
            $('.product-image-wrapper').on('mouseenter', function() {
                const $wrapper = $(this);
                const $hoverImage = $wrapper.find('.product-hover-image');
                
                if ($hoverImage.length) {
                    $wrapper.addClass('hover-active');
                }
            }).on('mouseleave', function() {
                $(this).removeClass('hover-active');
            });
            
            // 360 view functionality
            $(document).on('click', '.btn-360-view', function(e) {
                e.preventDefault();
                // Implement 360 view functionality
                console.log('360 view clicked');
            });
        },
        
        /**
         * Variation Swatches
         */
        variationSwatches: function() {
            $(document).on('click', '.color-swatch', function() {
                const $swatch = $(this);
                const $container = $swatch.closest('.variation-swatches');
                
                // Remove active class from siblings
                $container.find('.color-swatch').removeClass('active');
                
                // Add active class to clicked swatch
                $swatch.addClass('active');
                
                // Get the color value
                const colorValue = $swatch.attr('title');
                
                // Find and update the actual variation select
                const $variationSelect = $container.closest('.product-item, .single-product')
                    .find('select[name*="color"], select[name*="Color"]');
                
                if ($variationSelect.length) {
                    $variationSelect.val(colorValue).trigger('change');
                }
            });
        },
        
        /**
         * Cart Updates & Animations
         */
        cartUpdates: function() {
            // Add to cart success animation
            $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
                // Show cart icon animation
                $('.cart-icon').addClass('bounce');
                setTimeout(() => {
                    $('.cart-icon').removeClass('bounce');
                }, 600);
                
                // Show success notification
                AlAnikaWooCommerce.showNotification(alAnikaWoo.addedToCartText, 'success');
            });
            
            // Quantity input improvements
            $(document).on('click', '.quantity-btn', function() {
                const $btn = $(this);
                const $input = $btn.siblings('input[type="number"]');
                const currentVal = parseInt($input.val()) || 0;
                const step = parseInt($input.attr('step')) || 1;
                const min = parseInt($input.attr('min')) || 0;
                const max = parseInt($input.attr('max')) || 999;
                
                let newVal = currentVal;
                
                if ($btn.hasClass('quantity-plus') && newVal < max) {
                    newVal += step;
                } else if ($btn.hasClass('quantity-minus') && newVal > min) {
                    newVal -= step;
                }
                
                $input.val(newVal).trigger('change');
            });
        },
        
        /**
         * Advanced Product Filters
         */
        productFilters: function() {
            // Price range slider
            if ($('.price-range-slider').length) {
                $('.price-range-slider').each(function() {
                    const $slider = $(this);
                    const min = parseFloat($slider.data('min')) || 0;
                    const max = parseFloat($slider.data('max')) || 1000;
                    const step = parseFloat($slider.data('step')) || 1;
                    
                    // Initialize range slider (you might want to use a library like nouislider)
                    // This is a basic implementation
                    $slider.on('input', function() {
                        const value = $(this).val();
                        $('.price-display').text('$' + value);
                    });
                });
            }
            
            // Filter by rating
            $(document).on('click', '.rating-filter', function() {
                const $filter = $(this);
                const rating = $filter.data('rating');
                
                $('.rating-filter').removeClass('active');
                $filter.addClass('active');
                
                // Apply filter (implement your filtering logic)
                AlAnikaWooCommerce.applyFilters();
            });
            
            // Size filter
            $(document).on('click', '.size-filter', function() {
                const $filter = $(this);
                
                $filter.toggleClass('active');
                
                // Apply filter
                AlAnikaWooCommerce.applyFilters();
            });
        },
        
        /**
         * Apply Filters
         */
        applyFilters: function() {
            // Collect all active filters
            const filters = {
                price: $('.price-range-slider').val(),
                rating: $('.rating-filter.active').data('rating'),
                sizes: $('.size-filter.active').map(function() {
                    return $(this).data('size');
                }).get(),
                colors: $('.color-filter.active').map(function() {
                    return $(this).data('color');
                }).get()
            };
            
            // Show loading
            $('.products-grid').addClass('loading');
            
            // AJAX request to filter products
            $.ajax({
                url: alAnikaWoo.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'al_anika_filter_products',
                    filters: filters,
                    nonce: alAnikaWoo.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('.products-grid').html(response.data);
                    }
                },
                complete: function() {
                    $('.products-grid').removeClass('loading');
                }
            });
        },
        
        /**
         * Mobile Optimizations
         */
        mobileOptimizations: function() {
            if ($(window).width() <= 768) {
                // Mobile-specific interactions
                
                // Touch-friendly product cards
                $('.product-card').on('touchstart', function() {
                    $(this).addClass('touch-active');
                }).on('touchend', function() {
                    const $card = $(this);
                    setTimeout(() => {
                        $card.removeClass('touch-active');
                    }, 300);
                });
                
                // Swipe gestures for product gallery
                let startX = 0;
                let endX = 0;
                
                $('.product-gallery').on('touchstart', function(e) {
                    startX = e.originalEvent.touches[0].clientX;
                });
                
                $('.product-gallery').on('touchend', function(e) {
                    endX = e.originalEvent.changedTouches[0].clientX;
                    
                    if (startX - endX > 50) {
                        // Swipe left - next image
                        $(this).find('.next-image').trigger('click');
                    } else if (endX - startX > 50) {
                        // Swipe right - previous image
                        $(this).find('.prev-image').trigger('click');
                    }
                });
            }
        },
        
        /**
         * Recently Viewed Products
         */
        recentlyViewed: function() {
            // Initialize slider for recently viewed
            if ($('.recently-viewed-products .products-slider').length) {
                // You can use Swiper.js or similar for better slider functionality
                let isScrolling = false;
                
                $('.recently-viewed-products .products-slider').on('wheel', function(e) {
                    if (!isScrolling) {
                        isScrolling = true;
                        
                        const delta = e.originalEvent.deltaY;
                        const scrollLeft = this.scrollLeft;
                        
                        if (delta > 0) {
                            this.scrollLeft = scrollLeft + 200;
                        } else {
                            this.scrollLeft = scrollLeft - 200;
                        }
                        
                        setTimeout(() => {
                            isScrolling = false;
                        }, 100);
                        
                        e.preventDefault();
                    }
                });
            }
        },
        
        /**
         * Size Guide Modal
         */
        sizeGuide: function() {
            $(document).on('click', '.btn-size-guide, .size-guide-trigger', function(e) {
                e.preventDefault();
                
                const productId = $(this).data('product-id');
                
                // Create size guide modal
                if (!$('#size-guide-modal').length) {
                    $('body').append(`
                        <div id="size-guide-modal" class="al-anika-modal">
                            <div class="modal-overlay"></div>
                            <div class="modal-content size-guide-content">
                                <div class="modal-header">
                                    <h3>${alAnikaWoo.sizeGuideTitle}</h3>
                                    <button class="modal-close">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="modal-body"></div>
                            </div>
                        </div>
                    `);
                }
                
                // Load size guide content
                $.ajax({
                    url: alAnikaWoo.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'al_anika_size_guide',
                        product_id: productId,
                        nonce: alAnikaWoo.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#size-guide-modal .modal-body').html(response.data);
                            $('#size-guide-modal').addClass('active');
                            $('body').addClass('modal-open');
                        }
                    }
                });
            });
        },
        
        /**
         * Product Share
         */
        productShare: function() {
            $(document).on('click', '.btn-share-product', function(e) {
                e.preventDefault();
                
                const url = window.location.href;
                const title = document.title;
                
                if (navigator.share) {
                    // Use native share API if available
                    navigator.share({
                        title: title,
                        url: url
                    });
                } else {
                    // Fallback to custom share modal
                    AlAnikaWooCommerce.showShareModal(url, title);
                }
            });
        },
        
        /**
         * Show Share Modal
         */
        showShareModal: function(url, title) {
            const shareModal = `
                <div id="share-modal" class="al-anika-modal">
                    <div class="modal-overlay"></div>
                    <div class="modal-content share-content">
                        <div class="modal-header">
                            <h3>${alAnikaWoo.shareTitle}</h3>
                            <button class="modal-close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}" class="share-btn facebook" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}" class="share-btn twitter" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                    <span>Twitter</span>
                                </a>
                                <a href="https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}" class="share-btn whatsapp" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>WhatsApp</span>
                                </a>
                                <button class="share-btn copy-link" data-url="${url}">
                                    <i class="fas fa-link"></i>
                                    <span>Copy Link</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(shareModal);
            $('#share-modal').addClass('active');
            $('body').addClass('modal-open');
            
            // Copy link functionality
            $(document).on('click', '.copy-link', function() {
                const url = $(this).data('url');
                navigator.clipboard.writeText(url).then(() => {
                    $(this).find('span').text('Copied!');
                    setTimeout(() => {
                        $(this).find('span').text('Copy Link');
                    }, 2000);
                });
            });
        },
        
        /**
         * Show Notification
         */
        showNotification: function(message, type = 'info') {
            const notification = $(`
                <div class="al-anika-notification ${type}">
                    <div class="notification-content">
                        <span class="notification-message">${message}</span>
                        <button class="notification-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `);
            
            $('body').append(notification);
            
            // Show notification
            setTimeout(() => {
                notification.addClass('show');
            }, 100);
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                notification.removeClass('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
            
            // Manual close
            notification.find('.notification-close').on('click', function() {
                notification.removeClass('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            });
        }
    };
    
    // Expose to global scope
    window.AlAnikaWooCommerce = AlAnikaWooCommerce;
    
})(jQuery);
