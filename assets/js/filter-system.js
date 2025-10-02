/**
 * Advanced Filter System JavaScript
 * Category and Product filtering with AJAX
 */

(function($) {
    'use strict';

    const AlamFilters = {
        config: {
            ajax_url: alamFilters?.ajax_url || '/wp-admin/admin-ajax.php',
            nonce: alamFilters?.nonce || '',
            messages: alamFilters?.messages || {}
        },
        
        currentFilters: {},
        currentPage: 1,
        isLoading: false,

        init: function() {
            this.bindEvents();
            this.initPriceSliders();
            this.parseUrlFilters();
        },

        bindEvents: function() {
            // Filter toggle
            $(document).on('click', '.alam-filter-toggle-btn', this.toggleFilterBar);
            $(document).on('click', '.alam-filter-close', this.closeFilterBar);
            
            // Filter inputs
            $(document).on('change', '.alam-filter-bar input[type="checkbox"]', this.handleFilterChange);
            $(document).on('input', '.alam-price-slider', this.handlePriceSlider);
            $(document).on('change', '.alam-price-inputs input', this.handlePriceInput);
            $(document).on('change', '#alam-sort-select', this.handleSortChange);
            
            // Filter actions
            $(document).on('click', '.alam-filter-apply', this.applyFilters);
            $(document).on('click', '.alam-filter-clear', this.clearFilters);
            $(document).on('click', '.alam-active-filter-remove', this.removeActiveFilter);
            
            // Pagination
            $(document).on('click', '.woocommerce-pagination a', this.handlePagination);
            
            // Close filter on outside click
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.alam-filter-bar').length) {
                    AlamFilters.closeFilterBar();
                }
            });
            
            // Handle browser back/forward
            $(window).on('popstate', this.handlePopState);
        },

        toggleFilterBar: function(e) {
            e.preventDefault();
            $('.alam-filter-content').toggleClass('active');
            $('body').toggleClass('filter-active');
        },

        closeFilterBar: function() {
            $('.alam-filter-content').removeClass('active');
            $('body').removeClass('filter-active');
        },

        initPriceSliders: function() {
            const $sliders = $('.alam-price-slider');
            
            if ($sliders.length) {
                $sliders.each(function() {
                    const $slider = $(this);
                    const type = $slider.data('type');
                    const min = parseInt($slider.attr('min'));
                    const max = parseInt($slider.attr('max'));
                    const value = parseInt($slider.val());
                    
                    // Update the corresponding input
                    const $input = $(`input[name="${type}_price_input"]`);
                    $input.val(value);
                    
                    // Update visual representation
                    AlamFilters.updatePriceSliderVisual($slider);
                });
            }
        },

        updatePriceSliderVisual: function($slider) {
            const min = parseInt($slider.attr('min'));
            const max = parseInt($slider.attr('max'));
            const value = parseInt($slider.val());
            const percentage = ((value - min) / (max - min)) * 100;
            
            $slider.css('background', `linear-gradient(to right, var(--alam-primary) 0%, var(--alam-primary) ${percentage}%, #ddd ${percentage}%, #ddd 100%)`);
        },

        handleFilterChange: function() {
            const $input = $(this);
            const name = $input.attr('name');
            const value = $input.val();
            const isChecked = $input.is(':checked');
            
            if (!AlamFilters.currentFilters[name]) {
                AlamFilters.currentFilters[name] = [];
            }
            
            if (isChecked) {
                if (AlamFilters.currentFilters[name].indexOf(value) === -1) {
                    AlamFilters.currentFilters[name].push(value);
                }
            } else {
                const index = AlamFilters.currentFilters[name].indexOf(value);
                if (index > -1) {
                    AlamFilters.currentFilters[name].splice(index, 1);
                }
            }
            
            // Remove empty arrays
            if (AlamFilters.currentFilters[name].length === 0) {
                delete AlamFilters.currentFilters[name];
            }
            
            AlamFilters.updateActiveFilters();
        },

        handlePriceSlider: function() {
            const $slider = $(this);
            const type = $slider.data('type');
            const value = parseInt($slider.val());
            
            // Update corresponding input
            $(`input[name="${type}_price_input"]`).val(value);
            
            // Update visual
            AlamFilters.updatePriceSliderVisual($slider);
            
            // Update filters
            AlamFilters.currentFilters[`${type}_price`] = value;
            
            // Sync both sliders
            const minPrice = parseInt($('input[name="min_price"]').val());
            const maxPrice = parseInt($('input[name="max_price"]').val());
            
            if (minPrice > maxPrice) {
                if (type === 'min') {
                    $('input[name="max_price"]').val(value);
                    AlamFilters.currentFilters.max_price = value;
                } else {
                    $('input[name="min_price"]').val(value);
                    AlamFilters.currentFilters.min_price = value;
                }
            }
            
            AlamFilters.updateActiveFilters();
        },

        handlePriceInput: function() {
            const $input = $(this);
            const name = $input.attr('name');
            const value = parseInt($input.val());
            const type = name.replace('_price_input', '');
            
            // Update corresponding slider
            $(`input[name="${type}_price"]`).val(value);
            
            // Update filters
            AlamFilters.currentFilters[`${type}_price`] = value;
            
            AlamFilters.updateActiveFilters();
        },

        handleSortChange: function() {
            const $select = $(this);
            const value = $select.val();
            
            AlamFilters.currentFilters.orderby = value;
            AlamFilters.applyFilters();
        },

        applyFilters: function(e) {
            if (e) e.preventDefault();
            
            if (AlamFilters.isLoading) return;
            
            AlamFilters.currentPage = 1;
            AlamFilters.loadProducts();
            AlamFilters.closeFilterBar();
        },

        clearFilters: function(e) {
            e.preventDefault();
            
            // Clear all filter inputs
            $('.alam-filter-bar input[type="checkbox"]').prop('checked', false);
            $('.alam-price-slider').each(function() {
                const $slider = $(this);
                const defaultValue = $slider.data('type') === 'min' ? $slider.attr('min') : $slider.attr('max');
                $slider.val(defaultValue);
                AlamFilters.updatePriceSliderVisual($slider);
            });
            $('.alam-price-inputs input').each(function() {
                const $input = $(this);
                const type = $input.attr('name').replace('_price_input', '');
                const defaultValue = type === 'min' ? $input.attr('min') : $input.attr('max');
                $input.val(defaultValue);
            });
            $('#alam-sort-select').val('menu_order');
            
            // Clear filter object
            AlamFilters.currentFilters = {};
            
            // Update UI
            AlamFilters.updateActiveFilters();
            AlamFilters.loadProducts();
        },

        removeActiveFilter: function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const filterType = $button.data('filter-type');
            const filterValue = $button.data('filter-value');
            
            if (filterType && filterValue) {
                // Remove from current filters
                if (AlamFilters.currentFilters[filterType]) {
                    const index = AlamFilters.currentFilters[filterType].indexOf(filterValue);
                    if (index > -1) {
                        AlamFilters.currentFilters[filterType].splice(index, 1);
                        if (AlamFilters.currentFilters[filterType].length === 0) {
                            delete AlamFilters.currentFilters[filterType];
                        }
                    }
                }
                
                // Update UI
                $(`input[name="${filterType}[]"][value="${filterValue}"]`).prop('checked', false);
                AlamFilters.updateActiveFilters();
                AlamFilters.loadProducts();
            }
        },

        updateActiveFilters: function() {
            const $container = $('.alam-active-filters');
            $container.empty();
            
            if (Object.keys(AlamFilters.currentFilters).length === 0) {
                $container.hide();
                return;
            }
            
            $container.show();
            $container.append('<div class="alam-active-filters-title">الفلاتر النشطة:</div>');
            
            const $list = $('<div class="alam-active-filters-list"></div>');
            
            for (const [key, values] of Object.entries(AlamFilters.currentFilters)) {
                if (Array.isArray(values)) {
                    values.forEach(value => {
                        const $filter = $(`<span class="alam-active-filter">${value} <button class="alam-active-filter-remove" data-filter-type="${key}" data-filter-value="${value}">×</button></span>`);
                        $list.append($filter);
                    });
                } else if (key.includes('price')) {
                    const label = key === 'min_price' ? 'أقل سعر' : 'أعلى سعر';
                    const $filter = $(`<span class="alam-active-filter">${label}: ${values} <button class="alam-active-filter-remove" data-filter-type="${key}" data-filter-value="${values}">×</button></span>`);
                    $list.append($filter);
                }
            }
            
            $container.append($list);
        },

        loadProducts: function(page = 1) {
            if (AlamFilters.isLoading) return;
            
            AlamFilters.isLoading = true;
            AlamFilters.currentPage = page;
            
            // Show loading
            const $productsContainer = $('.products');
            $productsContainer.addClass('loading');
            
            // Add loading overlay
            if (!$('.alam-loading-overlay').length) {
                $productsContainer.append('<div class="alam-loading-overlay"><div class="alam-spinner"></div><div class="alam-loading-text">' + AlamFilters.config.messages.loading + '</div></div>');
            }
            
            $.ajax({
                url: AlamFilters.config.ajax_url,
                type: 'POST',
                data: {
                    action: 'alam_filter_products',
                    filters: AlamFilters.currentFilters,
                    page: page,
                    nonce: AlamFilters.config.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update products
                        $productsContainer.html(response.data.products);
                        
                        // Update pagination
                        AlamFilters.updatePagination(response.data);
                        
                        // Update results count
                        AlamFilters.updateResultsCount(response.data.found_posts);
                        
                        // Update URL
                        AlamFilters.updateUrl();
                        
                        // Scroll to products
                        $('html, body').animate({
                            scrollTop: $productsContainer.offset().top - 100
                        }, 500);
                    } else {
                        $productsContainer.html('<div class="alam-no-products">' + (response.data?.message || AlamFilters.config.messages.no_products) + '</div>');
                    }
                },
                error: function() {
                    $productsContainer.html('<div class="alam-error">' + AlamFilters.config.messages.error + '</div>');
                },
                complete: function() {
                    AlamFilters.isLoading = false;
                    $productsContainer.removeClass('loading');
                    $('.alam-loading-overlay').remove();
                }
            });
        },

        updatePagination: function(data) {
            const $pagination = $('.woocommerce-pagination');
            
            if (data.max_pages <= 1) {
                $pagination.hide();
                return;
            }
            
            $pagination.show();
            
            // Generate pagination HTML
            let paginationHtml = '<nav class="woocommerce-pagination"><ul class="page-numbers">';
            
            // Previous button
            if (data.current_page > 1) {
                paginationHtml += `<li><a class="prev page-numbers" href="#" data-page="${data.current_page - 1}">السابق</a></li>`;
            }
            
            // Page numbers
            for (let i = 1; i <= data.max_pages; i++) {
                if (i === data.current_page) {
                    paginationHtml += `<li><span aria-current="page" class="page-numbers current">${i}</span></li>`;
                } else {
                    paginationHtml += `<li><a class="page-numbers" href="#" data-page="${i}">${i}</a></li>`;
                }
            }
            
            // Next button
            if (data.current_page < data.max_pages) {
                paginationHtml += `<li><a class="next page-numbers" href="#" data-page="${data.current_page + 1}">التالي</a></li>`;
            }
            
            paginationHtml += '</ul></nav>';
            
            $pagination.html(paginationHtml);
        },

        updateResultsCount: function(count) {
            const $count = $('.woocommerce-result-count');
            if ($count.length) {
                $count.text(`عرض ${count} منتج`);
            }
        },

        handlePagination: function(e) {
            e.preventDefault();
            
            const $link = $(this);
            const page = parseInt($link.data('page'));
            
            if (page && page !== AlamFilters.currentPage) {
                AlamFilters.loadProducts(page);
            }
        },

        updateUrl: function() {
            const params = new URLSearchParams();
            
            for (const [key, value] of Object.entries(AlamFilters.currentFilters)) {
                if (Array.isArray(value)) {
                    value.forEach(v => params.append(key, v));
                } else {
                    params.set(key, value);
                }
            }
            
            const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.history.pushState({ filters: AlamFilters.currentFilters }, '', newUrl);
        },

        parseUrlFilters: function() {
            const params = new URLSearchParams(window.location.search);
            
            for (const [key, value] of params.entries()) {
                if (!AlamFilters.currentFilters[key]) {
                    AlamFilters.currentFilters[key] = [];
                }
                
                if (Array.isArray(AlamFilters.currentFilters[key])) {
                    AlamFilters.currentFilters[key].push(value);
                } else {
                    AlamFilters.currentFilters[key] = value;
                }
            }
            
            // Update UI based on URL parameters
            for (const [key, values] of Object.entries(AlamFilters.currentFilters)) {
                if (Array.isArray(values)) {
                    values.forEach(value => {
                        $(`input[name="${key}[]"][value="${value}"]`).prop('checked', true);
                    });
                } else if (key.includes('price')) {
                    $(`input[name="${key}"]`).val(values);
                    $(`input[name="${key}_input"]`).val(values);
                } else if (key === 'orderby') {
                    $(`#alam-sort-select`).val(values);
                }
            }
            
            AlamFilters.updateActiveFilters();
        },

        handlePopState: function(e) {
            if (e.originalEvent.state && e.originalEvent.state.filters) {
                AlamFilters.currentFilters = e.originalEvent.state.filters;
                AlamFilters.parseUrlFilters();
                AlamFilters.loadProducts();
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AlamFilters.init();
    });

})(jQuery);