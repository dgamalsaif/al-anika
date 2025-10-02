/**
 * Phase 4: Advanced Product Systems - Main JavaScript
 * Comprehensive functionality for all advanced product features
 */

(function($) {
    'use strict';

    // Global variables
    window.AlamAdvancedProducts = {
        config: {
            ajax_url: alamAdvanced?.ajax_url || '/wp-admin/admin-ajax.php',
            nonce: alamAdvanced?.nonce || '',
            compareLimit: 4,
            maxReviewImages: 5,
            maxImageSize: 5 * 1024 * 1024 // 5MB
        },
        cache: {
            compareProducts: [],
            recentlyViewed: [],
            wishlistProducts: []
        },
        timers: {}
    };

    /**
     * Product Gallery with Video Support
     */
    const ProductGallery = {
        init: function() {
            this.initGallery();
            this.initLightbox();
            this.initVideoSupport();
        },

        initGallery: function() {
            $('.alam-gallery-thumb').on('click', function() {
                const index = $(this).data('index');
                ProductGallery.showGalleryItem(index);
                
                $('.alam-gallery-thumb').removeClass('active');
                $(this).addClass('active');
            });

            $('.alam-gallery-prev').on('click', function() {
                ProductGallery.navigateGallery('prev');
            });

            $('.alam-gallery-next').on('click', function() {
                ProductGallery.navigateGallery('next');
            });

            // Swipe support for mobile
            this.initSwipeGestures();
        },

        showGalleryItem: function(index) {
            $('.alam-gallery-item').removeClass('active');
            $(`.alam-gallery-item:eq(${index})`).addClass('active');
        },

        navigateGallery: function(direction) {
            const $current = $('.alam-gallery-item.active');
            const currentIndex = $('.alam-gallery-item').index($current);
            const totalItems = $('.alam-gallery-item').length;
            
            let newIndex;
            if (direction === 'prev') {
                newIndex = currentIndex > 0 ? currentIndex - 1 : totalItems - 1;
            } else {
                newIndex = currentIndex < totalItems - 1 ? currentIndex + 1 : 0;
            }
            
            this.showGalleryItem(newIndex);
            
            $('.alam-gallery-thumb').removeClass('active');
            $(`.alam-gallery-thumb:eq(${newIndex})`).addClass('active');
        },

        initSwipeGestures: function() {
            let startX = 0;
            let endX = 0;

            $('.alam-gallery-viewer').on('touchstart', function(e) {
                startX = e.originalEvent.touches[0].clientX;
            });

            $('.alam-gallery-viewer').on('touchend', function(e) {
                endX = e.originalEvent.changedTouches[0].clientX;
                const diff = startX - endX;
                
                if (Math.abs(diff) > 50) { // Minimum swipe distance
                    if (diff > 0) {
                        ProductGallery.navigateGallery('next');
                    } else {
                        ProductGallery.navigateGallery('prev');
                    }
                }
            });
        },

        initLightbox: function() {
            $('.alam-zoom-button').on('click', function() {
                const $galleryItem = $(this).closest('.alam-gallery-item');
                const imageSrc = $galleryItem.find('img').attr('src');
                
                $('#alam-modal-image').attr('src', imageSrc);
                $('#alamLightbox').addClass('active');
                $('body').addClass('modal-open');
            });

            $('.alam-review-thumbnail').on('click', function() {
                const imageSrc = $(this).data('full');
                $('#alam-modal-image').attr('src', imageSrc);
                $('#alamLightbox').addClass('active');
                $('body').addClass('modal-open');
            });

            $('.alam-lightbox-close, .alam-lightbox-modal').on('click', function(e) {
                if (e.target === this) {
                    $('#alamLightbox').removeClass('active');
                    $('body').removeClass('modal-open');
                }
            });

            // Keyboard navigation
            $(document).on('keydown', function(e) {
                if ($('#alamLightbox').hasClass('active')) {
                    if (e.keyCode === 37) { // Left arrow
                        ProductGallery.navigateGallery('prev');
                    } else if (e.keyCode === 39) { // Right arrow
                        ProductGallery.navigateGallery('next');
                    } else if (e.keyCode === 27) { // Escape
                        $('#alamLightbox').removeClass('active');
                        $('body').removeClass('modal-open');
                    }
                }
            });
        },

        initVideoSupport: function() {
            $('.alam-play-button').on('click', function() {
                const $video = $(this).closest('.alam-gallery-item').find('video')[0];
                if ($video) {
                    $video.setAttribute('controls', 'controls');
                    $video.play();
                    $(this).closest('.alam-video-overlay').fadeOut();
                }
            });
        }
    };

    /**
     * Product Comparison System
     */
    const ProductCompare = {
        init: function() {
            this.loadCompareList();
            this.bindEvents();
            this.updateCompareButtons();
        },

        loadCompareList: function() {
            const stored = localStorage.getItem('alam_compare_products');
            if (stored) {
                try {
                    window.AlamAdvancedProducts.cache.compareProducts = JSON.parse(stored);
                } catch (e) {
                    window.AlamAdvancedProducts.cache.compareProducts = [];
                }
            }
        },

        saveCompareList: function() {
            localStorage.setItem('alam_compare_products', 
                JSON.stringify(window.AlamAdvancedProducts.cache.compareProducts));
        },

        bindEvents: function() {
            $(document).on('click', '.alam-compare-button, .alam-add-compare', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                const action = $(this).data('action') || 'toggle';
                
                if (action === 'remove' || ProductCompare.isInCompare(productId)) {
                    ProductCompare.removeFromCompare(productId);
                } else {
                    ProductCompare.addToCompare(productId);
                }
            });

            $(document).on('click', '.alam-clear-compare', function() {
                ProductCompare.clearCompare();
            });

            $(document).on('click', '.alam-remove-product', function() {
                const productId = $(this).data('product-id');
                ProductCompare.removeFromCompare(productId);
                location.reload(); // Reload compare page
            });
        },

        addToCompare: function(productId) {
            const compareList = window.AlamAdvancedProducts.cache.compareProducts;
            
            if (compareList.length >= window.AlamAdvancedProducts.config.compareLimit) {
                Utils.showNotification('يمكن مقارنة ' + window.AlamAdvancedProducts.config.compareLimit + ' منتجات كحد أقصى', 'warning');
                return;
            }

            if (!this.isInCompare(productId)) {
                compareList.push(parseInt(productId));
                this.saveCompareList();
                this.updateCompareButtons();
                this.updateCompareCounter();
                
                Utils.showNotification('تمت إضافة المنتج للمقارنة', 'success');
            }
        },

        removeFromCompare: function(productId) {
            const compareList = window.AlamAdvancedProducts.cache.compareProducts;
            const index = compareList.indexOf(parseInt(productId));
            
            if (index > -1) {
                compareList.splice(index, 1);
                this.saveCompareList();
                this.updateCompareButtons();
                this.updateCompareCounter();
                
                Utils.showNotification('تم حذف المنتج من المقارنة', 'info');
            }
        },

        clearCompare: function() {
            window.AlamAdvancedProducts.cache.compareProducts = [];
            this.saveCompareList();
            this.updateCompareButtons();
            this.updateCompareCounter();
            
            Utils.showNotification('تم مسح جميع منتجات المقارنة', 'info');
        },

        isInCompare: function(productId) {
            return window.AlamAdvancedProducts.cache.compareProducts.indexOf(parseInt(productId)) > -1;
        },

        updateCompareButtons: function() {
            $('.alam-compare-button, .alam-add-compare').each(function() {
                const productId = $(this).data('product-id');
                const $btn = $(this);
                
                if (ProductCompare.isInCompare(productId)) {
                    $btn.addClass('active').data('action', 'remove');
                    $btn.find('.alam-compare-text').text('إزالة من المقارنة');
                } else {
                    $btn.removeClass('active').data('action', 'add');
                    $btn.find('.alam-compare-text').text('مقارنة');
                }
            });
        },

        updateCompareCounter: function() {
            const count = window.AlamAdvancedProducts.cache.compareProducts.length;
            $('.alam-compare-counter .alam-counter').text(count).attr('data-count', count);
        }
    };

    /**
     * Advanced Review System with Image Upload
     */
    const AdvancedReviews = {
        uploadedImages: [],
        
        init: function() {
            this.initImageUpload();
            this.initReviewModal();
        },

        initImageUpload: function() {
            const $uploadArea = $('.alam-upload-dropzone');
            const $fileInput = $('#alam-review-images');

            // Click to upload
            $uploadArea.on('click', function() {
                $fileInput.click();
            });

            // Drag and drop
            $uploadArea.on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('dragover');
            });

            $uploadArea.on('dragleave', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
            });

            $uploadArea.on('drop', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
                
                const files = e.originalEvent.dataTransfer.files;
                AdvancedReviews.handleFiles(files);
            });

            // File input change
            $fileInput.on('change', function() {
                AdvancedReviews.handleFiles(this.files);
            });

            // Remove uploaded image
            $(document).on('click', '.alam-remove-image', function() {
                const index = $(this).data('index');
                AdvancedReviews.removeUploadedImage(index);
            });

            // Upload button
            $('.alam-upload-button').on('click', function() {
                $fileInput.click();
            });
        },

        handleFiles: function(files) {
            const maxFiles = window.AlamAdvancedProducts.config.maxReviewImages;
            const maxSize = window.AlamAdvancedProducts.config.maxImageSize;
            
            if (this.uploadedImages.length + files.length > maxFiles) {
                Utils.showNotification(`يمكن رفع ${maxFiles} صور كحد أقصى`, 'warning');
                return;
            }

            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) {
                    Utils.showNotification('نوع الملف غير مدعوم', 'error');
                    return;
                }

                if (file.size > maxSize) {
                    Utils.showNotification('حجم الملف كبير جداً', 'error');
                    return;
                }

                this.uploadImage(file);
            });
        },

        uploadImage: function(file) {
            const formData = new FormData();
            formData.append('action', 'alam_upload_review_image');
            formData.append('nonce', window.AlamAdvancedProducts.config.nonce);
            formData.append('image', file);

            const $container = $('<div class="alam-uploaded-image"></div>');
            const $progress = $('<div class="alam-upload-progress"><div class="alam-upload-progress-bar"></div></div>');
            
            $container.append($progress);
            $('.alam-uploaded-images').append($container);

            $.ajax({
                url: window.AlamAdvancedProducts.config.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percentComplete = (e.loaded / e.total) * 100;
                            $progress.find('.alam-upload-progress-bar').css('width', percentComplete + '%');
                        }
                    });
                    return xhr;
                },
                success: function(response) {
                    if (response.success) {
                        const imageData = response.data;
                        AdvancedReviews.addUploadedImage(imageData, $container);
                    } else {
                        $container.remove();
                        Utils.showNotification(response.data.message || 'خطأ في رفع الصورة', 'error');
                    }
                },
                error: function() {
                    $container.remove();
                    Utils.showNotification('خطأ في رفع الصورة', 'error');
                }
            });
        },

        addUploadedImage: function(imageData, $container) {
            this.uploadedImages.push(imageData.attachment_id);
            
            $container.html(`
                <img src="${imageData.thumb}" alt="صورة المراجعة">
                <button type="button" class="alam-remove-image" data-index="${this.uploadedImages.length - 1}">×</button>
            `);

            this.updateImageIds();
        },

        removeUploadedImage: function(index) {
            this.uploadedImages.splice(index, 1);
            $('.alam-uploaded-images .alam-uploaded-image').eq(index).remove();
            
            // Update indices
            $('.alam-remove-image').each(function(i) {
                $(this).data('index', i);
            });
            
            this.updateImageIds();
        },

        updateImageIds: function() {
            $('#review-image-ids').val(this.uploadedImages.join(','));
        },

        initReviewModal: function() {
            // Initialize review image modal functionality
            let currentImageIndex = 0;
            const images = [];

            $('.alam-review-thumbnail').each(function(index) {
                images.push($(this).data('full'));
                
                $(this).on('click', function() {
                    currentImageIndex = index;
                    AdvancedReviews.showReviewImageModal(images[currentImageIndex]);
                });
            });

            $('#alam-modal-prev').on('click', function() {
                currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
                AdvancedReviews.showReviewImageModal(images[currentImageIndex]);
            });

            $('#alam-modal-next').on('click', function() {
                currentImageIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
                AdvancedReviews.showReviewImageModal(images[currentImageIndex]);
            });
        },

        showReviewImageModal: function(imageSrc) {
            $('#alam-modal-image').attr('src', imageSrc);
            $('#alam-review-image-modal').addClass('active');
            $('body').addClass('modal-open');
        }
    };

    /**
     * Return Request System
     */
    const ReturnSystem = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            $(document).on('click', '.alam-open-return-form, .alam-request-return-btn', function() {
                const orderId = $(this).data('order-id') || $(this).data('product-id');
                ReturnSystem.showReturnModal(orderId);
            });

            $(document).on('click', '.alam-cancel-return', function() {
                ReturnSystem.hideReturnModal();
            });

            $(document).on('submit', '#alam-return-form', function(e) {
                e.preventDefault();
                ReturnSystem.submitReturnRequest();
            });

            // Return image upload
            $(document).on('change', '#return-images', function() {
                ReturnSystem.handleReturnImages(this.files);
            });
        },

        showReturnModal: function(orderId) {
            $('#alam-return-modal').show();
            if (orderId) {
                $('#alam-return-form').data('order-id', orderId);
            }
        },

        hideReturnModal: function() {
            $('#alam-return-modal').hide();
        },

        submitReturnRequest: function() {
            const $form = $('#alam-return-form');
            const formData = new FormData($form[0]);
            
            formData.append('action', 'alam_submit_return_request');
            formData.append('nonce', window.AlamAdvancedProducts.config.nonce);
            formData.append('order_id', $form.data('order-id'));

            $form.find('button[type="submit"]').prop('disabled', true).text('جاري الإرسال...');

            $.ajax({
                url: window.AlamAdvancedProducts.config.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Utils.showNotification(response.data.message, 'success');
                        ReturnSystem.hideReturnModal();
                        $form[0].reset();
                    } else {
                        Utils.showNotification(response.data.message || 'حدث خطأ أثناء إرسال الطلب', 'error');
                    }
                },
                error: function() {
                    Utils.showNotification('حدث خطأ أثناء إرسال الطلب', 'error');
                },
                complete: function() {
                    $form.find('button[type="submit"]').prop('disabled', false).text('إرسال طلب الإرجاع');
                }
            });
        },

        handleReturnImages: function(files) {
            const $container = $('.alam-uploaded-return-images');
            
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const $img = $(`
                            <div class="alam-return-image">
                                <img src="${e.target.result}" alt="صورة الإرجاع">
                                <button type="button" class="alam-remove-return-image">×</button>
                            </div>
                        `);
                        $container.append($img);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $(document).on('click', '.alam-remove-return-image', function() {
                $(this).closest('.alam-return-image').remove();
            });
        }
    };

    /**
     * Sale Page Functionality
     */
    const SalePages = {
        init: function() {
            this.initCountdowns();
            this.initFilters();
            this.initStats();
            this.initNotifications();
        },

        initCountdowns: function() {
            $('.alam-sale-countdown, .alam-flash-countdown').each(function() {
                const $countdown = $(this);
                const endDate = new Date($countdown.data('end-date')).getTime();
                
                if (endDate) {
                    SalePages.startCountdown($countdown, endDate);
                }
            });

            $('.alam-sale-timer, .alam-flash-timer').each(function() {
                const $timer = $(this);
                const endDate = new Date($timer.data('end-date')).getTime();
                
                if (endDate) {
                    SalePages.startProductTimer($timer, endDate);
                }
            });
        },

        startCountdown: function($countdown, endDate) {
            const timer = setInterval(function() {
                const now = new Date().getTime();
                const distance = endDate - now;

                if (distance < 0) {
                    clearInterval(timer);
                    $countdown.html('<div class="countdown-expired">انتهى العرض</div>');
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                $countdown.find('[data-days]').text(String(days).padStart(2, '0'));
                $countdown.find('[data-hours]').text(String(hours).padStart(2, '0'));
                $countdown.find('[data-minutes]').text(String(minutes).padStart(2, '0'));
                $countdown.find('[data-seconds]').text(String(seconds).padStart(2, '0'));
            }, 1000);

            // Store timer reference
            const timerId = 'countdown_' + Date.now();
            window.AlamAdvancedProducts.timers[timerId] = timer;
        },

        startProductTimer: function($timer, endDate) {
            const timer = setInterval(function() {
                const now = new Date().getTime();
                const distance = endDate - now;

                if (distance < 0) {
                    clearInterval(timer);
                    $timer.html('<span class="timer-expired">انتهى</span>');
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                $timer.find('.alam-timer-hours, .alam-hours').text(String(hours).padStart(2, '0'));
                $timer.find('.alam-timer-minutes, .alam-minutes').text(String(minutes).padStart(2, '0'));
                $timer.find('.alam-timer-seconds, .alam-seconds').text(String(seconds).padStart(2, '0'));
            }, 1000);
        },

        initFilters: function() {
            $('.alam-filter-btn').on('click', function() {
                const filter = $(this).data('filter');
                
                $('.alam-filter-btn').removeClass('active');
                $(this).addClass('active');
                
                SalePages.filterProducts(filter);
            });
        },

        filterProducts: function(filter) {
            const $products = $('.alam-sale-product-card, .alam-flash-product-card');
            
            if (filter === 'all') {
                $products.show();
                return;
            }

            $products.each(function() {
                const $product = $(this);
                let show = false;

                switch (filter) {
                    case 'under-50':
                        show = parseFloat($product.data('price')) < 50;
                        break;
                    case 'under-100':
                        show = parseFloat($product.data('price')) < 100;
                        break;
                    case 'under-200':
                        show = parseFloat($product.data('price')) < 200;
                        break;
                    case 'highest-discount':
                        show = parseFloat($product.data('discount')) >= 50;
                        break;
                    default:
                        show = $product.data('category') === filter;
                }

                $product.toggle(show);
            });
        },

        initStats: function() {
            $('.alam-stat-number').each(function() {
                const $stat = $(this);
                const target = parseInt($stat.data('count'));
                const duration = 2000;
                const increment = target / (duration / 50);
                let current = 0;

                const timer = setInterval(function() {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    $stat.text(Math.floor(current));
                }, 50);
            });
        },

        initNotifications: function() {
            // Show flash sale notifications periodically
            if ($('.alam-flash-sale-page').length) {
                setTimeout(function() {
                    SalePages.showFlashNotification();
                }, 5000);
            }
        },

        showFlashNotification: function() {
            const notifications = [
                'عرض برق جديد! خصم 70% على منتج مميز',
                'تخفيضات هائلة! لا تفوت الفرصة',
                'كمية محدودة! اطلب الآن'
            ];
            
            const randomNotification = notifications[Math.floor(Math.random() * notifications.length)];
            
            $('.alam-notification-text span').text(randomNotification);
            $('.alam-flash-notification').show().addClass('show');
            
            setTimeout(function() {
                $('.alam-flash-notification').removeClass('show');
                setTimeout(function() {
                    $('.alam-flash-notification').hide();
                }, 500);
            }, 5000);

            $('.alam-notification-close').on('click', function() {
                $('.alam-flash-notification').removeClass('show').hide();
            });
        }
    };

    /**
     * Category Page Enhancements
     */
    const CategoryPage = {
        init: function() {
            this.initFilters();
            this.initSorting();
            this.initViewModes();
            this.initPriceRange();
            this.initColorFilters();
            this.initSizeFilters();
            this.initCornerButton();
            this.initInfiniteScroll();
        },

        initFilters: function() {
            // Toggle filters on mobile
            $('.alam-toggle-filters').on('click', function() {
                $('.alam-filters-content').toggleClass('active');
            });

            // Filter options
            $('.alam-filter-option input').on('change', function() {
                CategoryPage.applyFilters();
            });

            // Clear filters
            $('.alam-clear-filters').on('click', function() {
                $('.alam-filter-option input').prop('checked', false);
                $('.alam-size-option, .alam-color-option').removeClass('active');
                $('#price-min-input, #price-max-input').val('');
                CategoryPage.applyFilters();
            });
        },

        initSorting: function() {
            $('#products-sort').on('change', function() {
                const sortBy = $(this).val();
                CategoryPage.sortProducts(sortBy);
            });
        },

        sortProducts: function(sortBy) {
            const $container = $('.alam-products-grid');
            const $products = $container.find('.alam-product-card').get();

            $products.sort(function(a, b) {
                const $a = $(a);
                const $b = $(b);

                switch (sortBy) {
                    case 'price':
                        return CategoryPage.getProductPrice($a) - CategoryPage.getProductPrice($b);
                    case 'price-desc':
                        return CategoryPage.getProductPrice($b) - CategoryPage.getProductPrice($a);
                    case 'rating':
                        return CategoryPage.getProductRating($b) - CategoryPage.getProductRating($a);
                    case 'date':
                        return new Date($b.data('date')) - new Date($a.data('date'));
                    case 'popularity':
                        return $b.data('popularity') - $a.data('popularity');
                    default:
                        return 0;
                }
            });

            $.each($products, function(index, item) {
                $container.append(item);
            });
        },

        getProductPrice: function($product) {
            const priceText = $product.find('.alam-product-price').text().replace(/[^\d.]/g, '');
            return parseFloat(priceText) || 0;
        },

        getProductRating: function($product) {
            return $product.find('.alam-stars .filled').length;
        },

        initViewModes: function() {
            $('.alam-view-mode').on('click', function() {
                const view = $(this).data('view');
                
                $('.alam-view-mode').removeClass('active');
                $(this).addClass('active');
                
                const $grid = $('.alam-products-grid');
                $grid.removeClass('grid-view list-view').addClass(view + '-view');
            });
        },

        initPriceRange: function() {
            const $minSlider = $('#price-min');
            const $maxSlider = $('#price-max');
            const $minInput = $('#price-min-input');
            const $maxInput = $('#price-max-input');

            function updatePriceRange() {
                const min = parseInt($minSlider.val());
                const max = parseInt($maxSlider.val());
                
                if (min >= max) {
                    $minSlider.val(max - 1);
                }
                
                $minInput.val($minSlider.val());
                $maxInput.val($maxSlider.val());
            }

            $minSlider.on('input', updatePriceRange);
            $maxSlider.on('input', updatePriceRange);

            $minInput.on('change', function() {
                $minSlider.val($(this).val());
                updatePriceRange();
            });

            $maxInput.on('change', function() {
                $maxSlider.val($(this).val());
                updatePriceRange();
            });

            $('.alam-apply-price-filter').on('click', function() {
                CategoryPage.applyFilters();
            });
        },

        initColorFilters: function() {
            $('.alam-color-option').on('click', function() {
                $(this).toggleClass('active');
                CategoryPage.applyFilters();
            });
        },

        initSizeFilters: function() {
            $('.alam-size-option').on('click', function() {
                $(this).toggleClass('active');
                CategoryPage.applyFilters();
            });
        },

        applyFilters: function() {
            const filters = CategoryPage.getActiveFilters();
            
            $('.alam-product-card').each(function() {
                const $product = $(this);
                let show = true;

                // Price filter
                if (filters.priceMin || filters.priceMax) {
                    const price = CategoryPage.getProductPrice($product);
                    if (filters.priceMin && price < filters.priceMin) show = false;
                    if (filters.priceMax && price > filters.priceMax) show = false;
                }

                // Brand filter
                if (filters.brands.length > 0) {
                    const productBrand = $product.data('brand');
                    if (!filters.brands.includes(productBrand)) show = false;
                }

                // Rating filter
                if (filters.rating) {
                    const productRating = CategoryPage.getProductRating($product);
                    if (productRating < filters.rating) show = false;
                }

                // Availability filter
                if (filters.availability.length > 0) {
                    let hasAvailability = false;
                    filters.availability.forEach(function(avail) {
                        if (avail === 'in_stock' && $product.find('.alam-in-stock').length) hasAvailability = true;
                        if (avail === 'on_sale' && $product.find('.alam-sale-badge').length) hasAvailability = true;
                        if (avail === 'featured' && $product.find('.alam-featured-badge').length) hasAvailability = true;
                    });
                    if (!hasAvailability) show = false;
                }

                // Color filter
                if (filters.colors.length > 0) {
                    const productColors = CategoryPage.getProductColors($product);
                    const hasColor = filters.colors.some(color => productColors.includes(color));
                    if (!hasColor) show = false;
                }

                // Size filter
                if (filters.sizes.length > 0) {
                    const productSizes = CategoryPage.getProductSizes($product);
                    const hasSize = filters.sizes.some(size => productSizes.includes(size));
                    if (!hasSize) show = false;
                }

                $product.toggle(show);
            });

            CategoryPage.updateResultsCount();
            CategoryPage.updateActiveFilters(filters);
        },

        getActiveFilters: function() {
            const filters = {
                priceMin: parseInt($('#price-min-input').val()) || null,
                priceMax: parseInt($('#price-max-input').val()) || null,
                brands: [],
                rating: null,
                availability: [],
                colors: [],
                sizes: []
            };

            $('input[name="brand[]"]:checked').each(function() {
                filters.brands.push($(this).val());
            });

            $('input[name="rating"]:checked').each(function() {
                filters.rating = parseInt($(this).val());
            });

            $('input[name="availability[]"]:checked').each(function() {
                filters.availability.push($(this).val());
            });

            $('.alam-color-option.active').each(function() {
                filters.colors.push($(this).data('color'));
            });

            $('.alam-size-option.active').each(function() {
                filters.sizes.push($(this).data('size'));
            });

            return filters;
        },

        getProductColors: function($product) {
            const colors = [];
            $product.find('.alam-color-swatch').each(function() {
                colors.push($(this).data('value'));
            });
            return colors;
        },

        getProductSizes: function($product) {
            const sizes = [];
            $product.find('.alam-size-swatch').each(function() {
                colors.push($(this).data('value'));
            });
            return sizes;
        },

        updateResultsCount: function() {
            const visibleProducts = $('.alam-product-card:visible').length;
            const totalProducts = $('.alam-product-card').length;
            
            $('#products-count').text(`عرض ${visibleProducts} من أصل ${totalProducts} منتج`);
        },

        updateActiveFilters: function(filters) {
            const $container = $('.alam-active-filters-list');
            $container.empty();

            let hasActiveFilters = false;

            // Price filter
            if (filters.priceMin || filters.priceMax) {
                const text = `السعر: ${filters.priceMin || 0} - ${filters.priceMax || '∞'}`;
                CategoryPage.addActiveFilter($container, 'price', text);
                hasActiveFilters = true;
            }

            // Other filters
            ['brands', 'availability', 'colors', 'sizes'].forEach(function(filterType) {
                if (filters[filterType].length > 0) {
                    filters[filterType].forEach(function(value) {
                        CategoryPage.addActiveFilter($container, filterType, value);
                        hasActiveFilters = true;
                    });
                }
            });

            $('.alam-active-filters').toggle(hasActiveFilters);
        },

        addActiveFilter: function($container, type, value) {
            const $filter = $(`
                <div class="alam-active-filter">
                    <span>${value}</span>
                    <button class="alam-remove-filter" data-type="${type}" data-value="${value}">×</button>
                </div>
            `);
            
            $container.append($filter);
        },

        initCornerButton: function() {
            $('.alam-corner-toggle').on('click', function() {
                $('.alam-corner-menu').toggleClass('active');
            });

            // Close on outside click
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.alam-category-corner-button').length) {
                    $('.alam-corner-menu').removeClass('active');
                }
            });
        },

        initInfiniteScroll: function() {
            let loading = false;
            let page = 1;

            $(window).on('scroll', function() {
                if (loading) return;

                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();

                if (scrollTop + windowHeight >= documentHeight - 500) {
                    loading = true;
                    CategoryPage.loadMoreProducts(++page);
                }
            });
        },

        loadMoreProducts: function(page) {
            $('.alam-loading').show();

            // Simulate AJAX call - replace with actual implementation
            setTimeout(function() {
                $('.alam-loading').hide();
                loading = false;
                
                // Add more products to grid
                // This would typically load from server
            }, 1000);
        }
    };

    /**
     * Product Details Page
     */
    const ProductDetails = {
        init: function() {
            this.initVariations();
            this.initQuantitySelector();
            this.initTabs();
            this.initStickyCart();
            this.initQuickActions();
            this.initRecentlyViewed();
        },

        initVariations: function() {
            // Color swatches
            $('.alam-color-input').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.alam-color-input').not(this).prop('checked', false);
                    ProductDetails.updateVariation();
                }
            });

            // Size swatches
            $('.alam-size-input').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.alam-size-input').not(this).prop('checked', false);
                    ProductDetails.updateVariation();
                }
            });

            // Dropdown variations
            $('.alam-variation-select').on('change', function() {
                ProductDetails.updateVariation();
            });
        },

        updateVariation: function() {
            const variations = {};
            
            $('.alam-variation-group').each(function() {
                const attribute = $(this).data('attribute');
                let value = null;

                // Check color swatches
                const $colorChecked = $(this).find('.alam-color-input:checked');
                if ($colorChecked.length) {
                    value = $colorChecked.val();
                }

                // Check size swatches
                const $sizeChecked = $(this).find('.alam-size-input:checked');
                if ($sizeChecked.length) {
                    value = $sizeChecked.val();
                }

                // Check dropdown
                const $select = $(this).find('.alam-variation-select');
                if ($select.length && $select.val()) {
                    value = $select.val();
                }

                if (value) {
                    variations[attribute] = value;
                }
            });

            // Find matching variation and update price/availability
            ProductDetails.findVariation(variations);
        },

        findVariation: function(selectedVariations) {
            // This would typically make an AJAX call to get variation data
            // For now, just show the variation info section
            if (Object.keys(selectedVariations).length > 0) {
                $('.alam-variation-info').show();
                // Update variation info here
            } else {
                $('.alam-variation-info').hide();
            }
        },

        initQuantitySelector: function() {
            $('.alam-qty-minus').on('click', function() {
                const $input = $(this).siblings('.alam-qty-input');
                const min = parseInt($input.attr('min')) || 1;
                const current = parseInt($input.val()) || 1;
                
                if (current > min) {
                    $input.val(current - 1);
                }
            });

            $('.alam-qty-plus').on('click', function() {
                const $input = $(this).siblings('.alam-qty-input');
                const max = parseInt($input.attr('max')) || 999;
                const current = parseInt($input.val()) || 1;
                
                if (current < max) {
                    $input.val(current + 1);
                }
            });

            $('.alam-qty-input').on('change', function() {
                const min = parseInt($(this).attr('min')) || 1;
                const max = parseInt($(this).attr('max')) || 999;
                let value = parseInt($(this).val()) || 1;
                
                if (value < min) value = min;
                if (value > max) value = max;
                
                $(this).val(value);
            });
        },

        initTabs: function() {
            $('.alam-tab-btn').on('click', function() {
                const tab = $(this).data('tab');
                
                $('.alam-tab-btn').removeClass('active');
                $(this).addClass('active');
                
                $('.alam-tab-content').removeClass('active');
                $(`#${tab}`).addClass('active');
            });
        },

        initStickyCart: function() {
            const $stickyCart = $('.alam-sticky-cart');
            const $productSummary = $('.alam-product-summary');
            
            if ($stickyCart.length && $productSummary.length) {
                $(window).on('scroll', function() {
                    const summaryBottom = $productSummary.offset().top + $productSummary.outerHeight();
                    const scrollTop = $(window).scrollTop();
                    
                    if (scrollTop > summaryBottom) {
                        $stickyCart.show();
                    } else {
                        $stickyCart.hide();
                    }
                });
            }
        },

        initQuickActions: function() {
            // Add to cart
            $('.alam-add-to-cart-main, .alam-sticky-add-cart').on('click', function(e) {
                e.preventDefault();
                ProductDetails.addToCart();
            });

            // Buy now
            $('.alam-buy-now-btn').on('click', function(e) {
                e.preventDefault();
                ProductDetails.buyNow();
            });

            // Wishlist
            $('.alam-add-to-wishlist').on('click', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                ProductDetails.addToWishlist(productId);
            });

            // Compare
            $('.alam-add-to-compare').on('click', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                ProductCompare.addToCompare(productId);
            });

            // Share
            $('.alam-share-product').on('click', function(e) {
                e.preventDefault();
                ProductDetails.shareProduct();
            });
        },

        addToCart: function() {
            const productId = $('.alam-product-form').data('product-id');
            const quantity = $('.alam-qty-input').val();
            const variations = ProductDetails.getSelectedVariations();

            // Show loading state
            $('.alam-add-to-cart-main').addClass('loading').text('جاري الإضافة...');

            // AJAX call to add to cart
            $.ajax({
                url: window.AlamAdvancedProducts.config.ajax_url,
                type: 'POST',
                data: {
                    action: 'woocommerce_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    variation: variations,
                    nonce: window.AlamAdvancedProducts.config.nonce
                },
                success: function(response) {
                    if (response.success) {
                        Utils.showNotification('تمت إضافة المنتج للسلة', 'success');
                        // Update cart count
                        CartManager.updateCartCount();
                    } else {
                        Utils.showNotification('خطأ في إضافة المنتج للسلة', 'error');
                    }
                },
                error: function() {
                    Utils.showNotification('خطأ في إضافة المنتج للسلة', 'error');
                },
                complete: function() {
                    $('.alam-add-to-cart-main').removeClass('loading').text('إضافة للسلة');
                }
            });
        },

        buyNow: function() {
            // Add to cart first, then redirect to checkout
            ProductDetails.addToCart();
            
            setTimeout(function() {
                window.location.href = '/checkout/';
            }, 1000);
        },

        addToWishlist: function(productId) {
            // Integrate with YITH Wishlist or custom wishlist
            Utils.showNotification('تمت إضافة المنتج لقائمة الأمنيات', 'success');
        },

        shareProduct: function() {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(function() {
                    Utils.showNotification('تم نسخ رابط المنتج', 'success');
                });
            }
        },

        getSelectedVariations: function() {
            const variations = {};
            
            $('.alam-variation-group').each(function() {
                const attribute = $(this).data('attribute');
                
                // Check inputs and selects
                const $checked = $(this).find('input:checked');
                const $select = $(this).find('select');
                
                if ($checked.length) {
                    variations[attribute] = $checked.val();
                } else if ($select.length && $select.val()) {
                    variations[attribute] = $select.val();
                }
            });
            
            return variations;
        },

        initRecentlyViewed: function() {
            const productId = $('.alam-product-form').data('product-id');
            if (productId) {
                ProductDetails.addToRecentlyViewed(productId);
                ProductDetails.loadRecentlyViewed();
            }
        },

        addToRecentlyViewed: function(productId) {
            let recentlyViewed = JSON.parse(localStorage.getItem('alam_recently_viewed') || '[]');
            
            // Remove if already exists
            recentlyViewed = recentlyViewed.filter(id => id !== productId);
            
            // Add to beginning
            recentlyViewed.unshift(productId);
            
            // Keep only last 10
            recentlyViewed = recentlyViewed.slice(0, 10);
            
            localStorage.setItem('alam_recently_viewed', JSON.stringify(recentlyViewed));
        },

        loadRecentlyViewed: function() {
            const recentlyViewed = JSON.parse(localStorage.getItem('alam_recently_viewed') || '[]');
            
            if (recentlyViewed.length > 1) { // Exclude current product
                // Load recently viewed products via AJAX
                // Implementation would depend on your setup
            }
        }
    };

    /**
     * Quick View Modal
     */
    const QuickView = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            $(document).on('click', '.alam-quick-view', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                QuickView.openModal(productId);
            });

            $(document).on('click', '.alam-modal-close, .alam-modal', function(e) {
                if (e.target === this) {
                    QuickView.closeModal();
                }
            });

            // Escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && $('.alam-modal.active').length) {
                    QuickView.closeModal();
                }
            });
        },

        openModal: function(productId) {
            const $modal = $('#alam-quick-view-modal');
            const $content = $modal.find('.alam-quick-view-content');
            
            $content.html('<div class="loading">جاري التحميل...</div>');
            $modal.addClass('active');
            $('body').addClass('modal-open');

            // Load product data via AJAX
            $.ajax({
                url: window.AlamAdvancedProducts.config.ajax_url,
                type: 'POST',
                data: {
                    action: 'alam_get_quick_view',
                    product_id: productId,
                    nonce: window.AlamAdvancedProducts.config.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $content.html(response.data.html);
                        QuickView.initModalFunctionality();
                    } else {
                        $content.html('<div class="error">خطأ في تحميل المنتج</div>');
                    }
                },
                error: function() {
                    $content.html('<div class="error">خطأ في تحميل المنتج</div>');
                }
            });
        },

        closeModal: function() {
            $('.alam-modal').removeClass('active');
            $('body').removeClass('modal-open');
        },

        initModalFunctionality: function() {
            // Initialize functionality for the loaded content
            const $modal = $('#alam-quick-view-modal');
            
            // Quantity selectors
            $modal.find('.alam-qty-minus, .alam-qty-plus').off('click').on('click', function() {
                const $input = $(this).siblings('.alam-qty-input');
                const isPlus = $(this).hasClass('alam-qty-plus');
                const current = parseInt($input.val()) || 1;
                const min = parseInt($input.attr('min')) || 1;
                const max = parseInt($input.attr('max')) || 999;
                
                if (isPlus && current < max) {
                    $input.val(current + 1);
                } else if (!isPlus && current > min) {
                    $input.val(current - 1);
                }
            });

            // Add to cart in modal
            $modal.find('.alam-add-to-cart-btn').off('click').on('click', function(e) {
                e.preventDefault();
                
                const productId = $(this).data('product-id');
                const quantity = $modal.find('.alam-qty-input').val();
                
                $(this).addClass('loading').text('جاري الإضافة...');
                
                // Add to cart logic here
                setTimeout(() => {
                    $(this).removeClass('loading').text('تمت الإضافة');
                    Utils.showNotification('تمت إضافة المنتج للسلة', 'success');
                    
                    setTimeout(() => {
                        QuickView.closeModal();
                    }, 1000);
                }, 500);
            });
        }
    };

    /**
     * Cart Management
     */
    const CartManager = {
        init: function() {
            this.bindEvents();
            this.updateCartCount();
        },

        bindEvents: function() {
            // Quick add to cart
            $(document).on('click', '.alam-add-to-cart-btn, .alam-flash-add-cart', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                CartManager.quickAddToCart(productId, $(this));
            });
        },

        quickAddToCart: function(productId, $button) {
            const originalText = $button.text();
            $button.addClass('loading').text('جاري الإضافة...');

            $.ajax({
                url: window.AlamAdvancedProducts.config.ajax_url,
                type: 'POST',
                data: {
                    action: 'woocommerce_add_to_cart',
                    product_id: productId,
                    quantity: 1,
                    nonce: window.AlamAdvancedProducts.config.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $button.text('تمت الإضافة').addClass('added');
                        Utils.showNotification('تمت إضافة المنتج للسلة', 'success');
                        CartManager.updateCartCount();
                        
                        setTimeout(() => {
                            $button.removeClass('loading added').text(originalText);
                        }, 2000);
                    } else {
                        $button.removeClass('loading').text(originalText);
                        Utils.showNotification('خطأ في إضافة المنتج للسلة', 'error');
                    }
                },
                error: function() {
                    $button.removeClass('loading').text(originalText);
                    Utils.showNotification('خطأ في إضافة المنتج للسلة', 'error');
                }
            });
        },

        updateCartCount: function() {
            $.ajax({
                url: window.AlamAdvancedProducts.config.ajax_url,
                type: 'POST',
                data: {
                    action: 'woocommerce_get_cart_count',
                    nonce: window.AlamAdvancedProducts.config.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('.cart-count, .alam-cart-counter').text(response.data.count);
                    }
                }
            });
        }
    };

    /**
     * Utilities
     */
    const Utils = {
        showNotification: function(message, type = 'info') {
            const types = {
                success: '#28a745',
                error: '#dc3545',
                warning: '#ffc107',
                info: '#17a2b8'
            };

            // Remove existing notifications
            $('.alam-notification').remove();

            const $notification = $(`
                <div class="alam-notification alam-notification-${type}" style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: ${types[type]};
                    color: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 10000;
                    font-weight: 600;
                    max-width: 300px;
                    transform: translateX(100%);
                    transition: transform 0.3s ease;
                ">
                    ${message}
                    <button style="
                        background: none;
                        border: none;
                        color: white;
                        float: right;
                        margin-left: 10px;
                        cursor: pointer;
                        font-size: 16px;
                        line-height: 1;
                    ">&times;</button>
                </div>
            `);

            $('body').append($notification);

            // Animate in
            setTimeout(() => {
                $notification.css('transform', 'translateX(0)');
            }, 100);

            // Auto hide
            setTimeout(() => {
                Utils.hideNotification($notification);
            }, 5000);

            // Manual close
            $notification.find('button').on('click', function() {
                Utils.hideNotification($notification);
            });
        },

        hideNotification: function($notification) {
            $notification.css('transform', 'translateX(100%)');
            setTimeout(() => {
                $notification.remove();
            }, 300);
        },

        debounce: function(func, wait, immediate) {
            let timeout;
            return function executedFunction() {
                const context = this;
                const args = arguments;
                
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                
                if (callNow) func.apply(context, args);
            };
        },

        throttle: function(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },

        formatPrice: function(price, currency = 'SAR') {
            return new Intl.NumberFormat('ar-SA', {
                style: 'currency',
                currency: currency
            }).format(price);
        },

        formatDate: function(date) {
            return new Intl.DateTimeFormat('ar-SA', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }).format(new Date(date));
        }
    };

    /**
     * Initialization
     */
    $(document).ready(function() {
        // Initialize all modules
        ProductGallery.init();
        ProductCompare.init();
        AdvancedReviews.init();
        ReturnSystem.init();
        SalePages.init();
        CategoryPage.init();
        ProductDetails.init();
        QuickView.init();
        CartManager.init();

        // Global event handlers
        $(document).on('click', '.alam-modal-close', function() {
            $(this).closest('.alam-modal').removeClass('active');
            $('body').removeClass('modal-open');
        });

        // Initialize tooltips if available
        if ($.fn.tooltip) {
            $('[title]').tooltip();
        }

        // Lazy load images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Console greeting
        console.log('%c🎉 Alam Al-Anika Theme - Phase 4: Advanced Product Systems Loaded!', 
            'color: #2c5aa0; font-size: 16px; font-weight: bold;');
    });

    // Handle page unload
    $(window).on('beforeunload', function() {
        // Clear any running timers
        Object.values(window.AlamAdvancedProducts.timers).forEach(timer => {
            clearInterval(timer);
        });
    });

})(jQuery);