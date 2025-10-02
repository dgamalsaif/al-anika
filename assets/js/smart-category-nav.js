/**
 * Smart Category Navigation JavaScript
 * Phase 3 Enhancement - Horizontal Scrolling with Mega Menu and AJAX
 * 
 * @package AlamAlAnika
 */

(function($) {
    'use strict';

    class SmartCategoryNavigation {
        constructor() {
            this.currentFilter = 'all';
            this.searchTimeout = null;
            this.activeCategory = null;
            this.scrollPosition = 0;
            this.isScrolling = false;
            this.loadedProducts = new Map();
            
            this.init();
        }

        init() {
            this.cacheElements();
            this.bindEvents();
            this.initScrollControls();
            this.initMegaMenus();
            this.initFilterSystem();
            this.initSearchFunctionality();
            this.updateScrollIndicator();
            this.checkScrollButtons();
        }

        cacheElements() {
            this.$navigation = $('.smart-category-navigation');
            this.$categoryBar = $('.category-bar');
            this.$categoryItems = $('.category-item');
            this.$scrollPrev = $('.category-scroll-prev');
            this.$scrollNext = $('.category-scroll-next');
            this.$scrollIndicator = $('.scroll-indicator-thumb');
            this.$filterBtns = $('.filter-btn');
            this.$searchInput = $('.category-search-input');
            this.$searchBtn = $('.search-btn');
            this.$megaMenus = $('.category-mega-menu');
            this.$ajaxOverlay = $('.category-ajax-overlay');
        }

        // ===== HORIZONTAL SCROLLING SYSTEM =====
        initScrollControls() {
            const scrollAmount = 200;
            
            // Previous button
            this.$scrollPrev.on('click', (e) => {
                e.preventDefault();
                this.scrollCategories(-scrollAmount);
            });

            // Next button  
            this.$scrollNext.on('click', (e) => {
                e.preventDefault();
                this.scrollCategories(scrollAmount);
            });

            // Mouse wheel scrolling
            this.$categoryBar.on('wheel', (e) => {
                if (Math.abs(e.originalEvent.deltaX) > Math.abs(e.originalEvent.deltaY)) {
                    return; // Already scrolling horizontally
                }
                
                e.preventDefault();
                const delta = e.originalEvent.deltaY;
                this.scrollCategories(delta > 0 ? scrollAmount : -scrollAmount);
            });

            // Touch scrolling
            this.initTouchScrolling();

            // Scroll event for indicator
            this.$categoryBar.on('scroll', () => {
                this.updateScrollIndicator();
                this.checkScrollButtons();
            });

            // Keyboard navigation
            $(document).on('keydown', (e) => {
                if (!this.$navigation.is(':visible')) return;
                
                if (e.ctrlKey || e.metaKey) {
                    switch(e.keyCode) {
                        case 37: // Left arrow
                            e.preventDefault();
                            this.scrollCategories(-scrollAmount);
                            break;
                        case 39: // Right arrow
                            e.preventDefault();
                            this.scrollCategories(scrollAmount);
                            break;
                    }
                }
            });
        }

        initTouchScrolling() {
            let startX = 0;
            let scrollLeft = 0;
            let isDown = false;

            this.$categoryBar.on('mousedown touchstart', (e) => {
                isDown = true;
                startX = (e.pageX || e.originalEvent.touches[0].pageX) - this.$categoryBar.offset().left;
                scrollLeft = this.$categoryBar.scrollLeft();
                this.$categoryBar.addClass('scrolling');
            });

            this.$categoryBar.on('mouseleave mouseup touchend', () => {
                isDown = false;
                this.$categoryBar.removeClass('scrolling');
            });

            this.$categoryBar.on('mousemove touchmove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = (e.pageX || e.originalEvent.touches[0].pageX) - this.$categoryBar.offset().left;
                const walk = (x - startX) * 2;
                this.$categoryBar.scrollLeft(scrollLeft - walk);
            });
        }

        scrollCategories(amount) {
            const currentScroll = this.$categoryBar.scrollLeft();
            const newScroll = currentScroll + amount;
            
            this.$categoryBar.animate({
                scrollLeft: newScroll
            }, 300, 'easeOutCubic');
        }

        updateScrollIndicator() {
            const scrollLeft = this.$categoryBar.scrollLeft();
            const scrollWidth = this.$categoryBar[0].scrollWidth;
            const clientWidth = this.$categoryBar[0].clientWidth;
            
            if (scrollWidth <= clientWidth) {
                this.$scrollIndicator.parent().hide();
                return;
            }
            
            this.$scrollIndicator.parent().show();
            const percentage = (scrollLeft / (scrollWidth - clientWidth)) * 100;
            const indicatorWidth = (clientWidth / scrollWidth) * 100;
            
            this.$scrollIndicator.css({
                'left': percentage + '%',
                'width': indicatorWidth + '%'
            });
        }

        checkScrollButtons() {
            const scrollLeft = this.$categoryBar.scrollLeft();
            const scrollWidth = this.$categoryBar[0].scrollWidth;
            const clientWidth = this.$categoryBar[0].clientWidth;
            
            this.$scrollPrev.prop('disabled', scrollLeft <= 0);
            this.$scrollNext.prop('disabled', scrollLeft >= scrollWidth - clientWidth);
        }

        // ===== MEGA MENU SYSTEM =====
        initMegaMenus() {
            // Hover delays for better UX
            let hoverTimeout;
            
            this.$categoryItems.on('mouseenter', (e) => {
                const $item = $(e.currentTarget);
                const $megaMenu = $item.find('.category-mega-menu');
                
                if (!$megaMenu.length) return;
                
                clearTimeout(hoverTimeout);
                hoverTimeout = setTimeout(() => {
                    this.showMegaMenu($item);
                }, 100);
            });

            this.$categoryItems.on('mouseleave', (e) => {
                const $item = $(e.currentTarget);
                const $megaMenu = $item.find('.category-mega-menu');
                
                clearTimeout(hoverTimeout);
                hoverTimeout = setTimeout(() => {
                    this.hideMegaMenu($item);
                }, 200);
            });

            // Keep mega menu open when hovering over it
            this.$megaMenus.on('mouseenter', (e) => {
                clearTimeout(hoverTimeout);
            });

            this.$megaMenus.on('mouseleave', (e) => {
                const $megaMenu = $(e.currentTarget);
                const $item = $megaMenu.closest('.category-item');
                
                hoverTimeout = setTimeout(() => {
                    this.hideMegaMenu($item);
                }, 200);
            });

            // Close mega menus when clicking outside
            $(document).on('click', (e) => {
                if (!$(e.target).closest('.category-item').length) {
                    this.hideAllMegaMenus();
                }
            });

            // Escape key to close mega menus
            $(document).on('keydown', (e) => {
                if (e.keyCode === 27) { // Escape key
                    this.hideAllMegaMenus();
                }
            });
        }

        showMegaMenu($item) {
            const categoryId = $item.data('category-id');
            const $megaMenu = $item.find('.category-mega-menu');
            
            if (!$megaMenu.length) return;
            
            // Hide other mega menus
            this.hideAllMegaMenus();
            
            // Show this mega menu
            $megaMenu.addClass('active');
            this.activeCategory = categoryId;
            
            // Load products if not already loaded
            this.loadCategoryProducts(categoryId);
            
            // Add backdrop
            if (!$('.mega-menu-backdrop').length) {
                $('body').append('<div class="mega-menu-backdrop"></div>');
                $('.mega-menu-backdrop').fadeIn(200);
            }
        }

        hideMegaMenu($item) {
            const $megaMenu = $item.find('.category-mega-menu');
            $megaMenu.removeClass('active');
            
            if (this.activeCategory === $item.data('category-id')) {
                this.activeCategory = null;
            }
            
            // Remove backdrop if no active mega menus
            if (!$('.category-mega-menu.active').length) {
                $('.mega-menu-backdrop').fadeOut(200, function() {
                    $(this).remove();
                });
            }
        }

        hideAllMegaMenus() {
            this.$megaMenus.removeClass('active');
            this.activeCategory = null;
            $('.mega-menu-backdrop').fadeOut(200, function() {
                $(this).remove();
            });
        }

        // ===== AJAX PRODUCT LOADING =====
        loadCategoryProducts(categoryId) {
            // Check if products already loaded
            if (this.loadedProducts.has(categoryId)) {
                return;
            }
            
            const $productsGrid = $(`.featured-products-grid[data-category-id="${categoryId}"]`);
            
            if (!$productsGrid.length) return;
            
            // Show loading state
            $productsGrid.html(`
                <div class="products-loading">
                    <div class="loading-spinner"></div>
                    <span>جاري التحميل...</span>
                </div>
            `);
            
            // AJAX request
            $.ajax({
                url: alamAlAnikaAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'load_category_products',
                    category_id: categoryId,
                    nonce: alamAlAnikaAjax.nonce,
                    limit: 4
                },
                success: (response) => {
                    if (response.success && response.data.products) {
                        this.displayProducts($productsGrid, response.data.products);
                        this.loadedProducts.set(categoryId, response.data.products);
                    } else {
                        this.displayProductsError($productsGrid);
                    }
                },
                error: () => {
                    this.displayProductsError($productsGrid);
                }
            });
        }

        displayProducts($grid, products) {
            let html = '';
            
            products.forEach(product => {
                html += `
                    <div class="product-mini-card">
                        <a href="${product.permalink}" class="product-mini-link">
                            <img src="${product.image}" alt="${product.title}" class="product-mini-image" loading="lazy">
                            <div class="product-mini-info">
                                <h6 class="product-mini-title">${product.title}</h6>
                                <div class="product-mini-price">${product.price}</div>
                            </div>
                        </a>
                    </div>
                `;
            });
            
            $grid.html(html);
            
            // Animate products in
            $grid.find('.product-mini-card').each((index, card) => {
                setTimeout(() => {
                    $(card).addClass('loaded');
                }, index * 100);
            });
        }

        displayProductsError($grid) {
            $grid.html(`
                <div class="products-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>حدث خطأ في تحميل المنتجات</span>
                </div>
            `);
        }

        // ===== FILTER SYSTEM =====
        initFilterSystem() {
            this.$filterBtns.on('click', (e) => {
                e.preventDefault();
                const $btn = $(e.currentTarget);
                const filter = $btn.data('filter');
                
                this.setActiveFilter($btn, filter);
                this.applyFilter(filter);
            });
        }

        setActiveFilter($btn, filter) {
            this.$filterBtns.removeClass('active');
            $btn.addClass('active');
            this.currentFilter = filter;
        }

        applyFilter(filter) {
            switch(filter) {
                case 'all':
                    this.$categoryItems.show();
                    break;
                case 'featured':
                    this.$categoryItems.hide();
                    this.$categoryItems.filter('.featured').show();
                    break;
                case 'sale':
                    this.filterByProductType('sale');
                    break;
                case 'new':
                    this.filterByProductType('new');
                    break;
            }
            
            // Update scroll controls after filtering
            setTimeout(() => {
                this.updateScrollIndicator();
                this.checkScrollButtons();
            }, 300);
        }

        filterByProductType(type) {
            // Show loading
            this.showAjaxOverlay();
            
            $.ajax({
                url: alamAlAnikaAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'filter_categories_by_product_type',
                    product_type: type,
                    nonce: alamAlAnikaAjax.nonce
                },
                success: (response) => {
                    this.hideAjaxOverlay();
                    
                    if (response.success && response.data.category_ids) {
                        this.$categoryItems.hide();
                        response.data.category_ids.forEach(categoryId => {
                            $(`.category-item[data-category-id="${categoryId}"]`).show();
                        });
                    } else {
                        this.$categoryItems.hide();
                        this.showNoResultsMessage();
                    }
                },
                error: () => {
                    this.hideAjaxOverlay();
                    this.$categoryItems.show(); // Fallback to show all
                }
            });
        }

        // ===== SEARCH FUNCTIONALITY =====
        initSearchFunctionality() {
            // Real-time search with debouncing
            this.$searchInput.on('input', (e) => {
                const query = $(e.target).val().trim();
                
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.performSearch(query);
                }, 300);
            });

            // Search button click
            this.$searchBtn.on('click', (e) => {
                e.preventDefault();
                const query = this.$searchInput.val().trim();
                this.performSearch(query);
            });

            // Enter key search
            this.$searchInput.on('keydown', (e) => {
                if (e.keyCode === 13) { // Enter key
                    e.preventDefault();
                    const query = $(e.target).val().trim();
                    this.performSearch(query);
                }
            });

            // Clear search on escape
            this.$searchInput.on('keydown', (e) => {
                if (e.keyCode === 27) { // Escape key
                    $(e.target).val('');
                    this.performSearch('');
                }
            });
        }

        performSearch(query) {
            if (query === '') {
                // Show all categories when search is empty
                this.$categoryItems.show();
                this.updateScrollIndicator();
                return;
            }

            if (query.length < 2) {
                return; // Minimum 2 characters
            }

            // Show loading
            this.showAjaxOverlay();

            $.ajax({
                url: alamAlAnikaAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'search_categories',
                    query: query,
                    nonce: alamAlAnikaAjax.nonce
                },
                success: (response) => {
                    this.hideAjaxOverlay();
                    
                    if (response.success && response.data.category_ids) {
                        // Hide all categories
                        this.$categoryItems.hide();
                        
                        // Show matching categories
                        response.data.category_ids.forEach(categoryId => {
                            $(`.category-item[data-category-id="${categoryId}"]`).show();
                        });
                        
                        // Show no results message if no matches
                        if (response.data.category_ids.length === 0) {
                            this.showNoResultsMessage();
                        }
                    } else {
                        this.showNoResultsMessage();
                    }
                    
                    this.updateScrollIndicator();
                    this.checkScrollButtons();
                },
                error: () => {
                    this.hideAjaxOverlay();
                    // Keep current state on error
                }
            });
        }

        // ===== UI HELPERS =====
        showAjaxOverlay() {
            this.$ajaxOverlay.fadeIn(200);
        }

        hideAjaxOverlay() {
            this.$ajaxOverlay.fadeOut(200);
        }

        showNoResultsMessage() {
            if (!$('.no-results-message').length) {
                this.$categoryBar.append(`
                    <div class="no-results-message">
                        <div class="no-results-content">
                            <i class="fas fa-search"></i>
                            <h4>لا توجد نتائج</h4>
                            <p>لم نجد أي فئات تطابق بحثك</p>
                        </div>
                    </div>
                `);
            }
        }

        // ===== RESPONSIVE UTILITIES =====
        handleResize() {
            // Recalculate scroll positions and indicators
            setTimeout(() => {
                this.updateScrollIndicator();
                this.checkScrollButtons();
            }, 100);

            // Close mega menus on mobile
            if (window.innerWidth < 768) {
                this.hideAllMegaMenus();
            }
        }

        // ===== PUBLIC API =====
        scrollToCategory(categorySlug) {
            const $target = $(`.category-item[data-category-slug="${categorySlug}"]`);
            if ($target.length) {
                const targetOffset = $target.position().left + this.$categoryBar.scrollLeft();
                this.$categoryBar.animate({
                    scrollLeft: targetOffset - 100
                }, 500);
            }
        }

        filterCategories(filter) {
            const $filterBtn = $(`.filter-btn[data-filter="${filter}"]`);
            if ($filterBtn.length) {
                $filterBtn.click();
            }
        }

        searchCategories(query) {
            this.$searchInput.val(query);
            this.performSearch(query);
        }

        // ===== EVENT HANDLERS =====
        bindEvents() {
            // Window resize
            $(window).on('resize', this.debounce(() => {
                this.handleResize();
            }, 250));

            // Visibility change (pause/resume when tab is hidden)
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.hideAllMegaMenus();
                }
            });

            // Clean up on page unload
            $(window).on('beforeunload', () => {
                this.destroy();
            });
        }

        // ===== UTILITY FUNCTIONS =====
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // ===== CLEANUP =====
        destroy() {
            clearTimeout(this.searchTimeout);
            this.hideAllMegaMenus();
            $(window).off('.smartCategoryNav');
            $(document).off('.smartCategoryNav');
        }
    }

    // ===== AJAX HANDLERS =====
    class CategoryAjaxHandlers {
        constructor() {
            this.setupAjaxHandlers();
        }

        setupAjaxHandlers() {
            // Set up AJAX URL and nonce for frontend use
            if (typeof alamAlAnikaAjax === 'undefined') {
                window.alamAlAnikaAjax = {
                    ajaxurl: '/wp-admin/admin-ajax.php',
                    nonce: 'category-ajax-nonce'
                };
            }
        }
    }

    // ===== INITIALIZATION =====
    $(document).ready(function() {
        // Initialize only if smart category navigation exists
        if ($('.smart-category-navigation').length) {
            window.smartCategoryNav = new SmartCategoryNavigation();
            window.categoryAjaxHandlers = new CategoryAjaxHandlers();
        }
    });

    // Add dynamic CSS for enhanced interactions
    $('<style>')
        .text(`
            .category-bar.scrolling {
                cursor: grabbing;
                user-select: none;
            }
            
            .category-mega-menu.active {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateX(-50%) translateY(0) !important;
            }
            
            .mega-menu-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.1);
                z-index: 999;
                backdrop-filter: blur(2px);
            }
            
            .product-mini-card {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.3s ease;
            }
            
            .product-mini-card.loaded {
                opacity: 1;
                transform: translateY(0);
            }
            
            .no-results-message {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
                color: #718096;
                width: 100%;
            }
            
            .no-results-content {
                background: white;
                padding: 40px;
                border-radius: 16px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }
            
            .no-results-content i {
                font-size: 48px;
                margin-bottom: 20px;
                opacity: 0.5;
            }
            
            .no-results-content h4 {
                margin: 0 0 10px;
                font-size: 18px;
                font-weight: 600;
            }
            
            .no-results-content p {
                margin: 0;
                opacity: 0.8;
            }
            
            .products-error {
                grid-column: 1 / -1;
                text-align: center;
                padding: 20px;
                color: #e53e3e;
            }
            
            .products-error i {
                font-size: 24px;
                margin-bottom: 10px;
            }
        `)
        .appendTo('head');

})(jQuery);
