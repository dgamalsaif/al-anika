/**
 * Corner Category Button JavaScript
 * Floating category navigation functionality
 */

(function($) {
    'use strict';

    const AlamCornerButton = {
        config: {
            ajax_url: alamCornerButton?.ajax_url || '/wp-admin/admin-ajax.php',
            nonce: alamCornerButton?.nonce || '',
            shop_url: alamCornerButton?.shop_url || '/shop'
        },
        
        isOpen: false,
        isLoading: false,
        categoriesLoaded: false,

        init: function() {
            this.bindEvents();
            this.initScrollBehavior();
            this.setCustomStyles();
        },

        bindEvents: function() {
            // Main button click
            $(document).on('click', '.alam-corner-btn', this.toggleDropdown);
            
            // Close button
            $(document).on('click', '.alam-corner-close', this.closeDropdown);
            
            // Overlay click
            $(document).on('click', '.alam-corner-overlay', this.closeDropdown);
            
            // Category links
            $(document).on('click', '.alam-corner-category-link', this.handleCategoryClick);
            
            // Escape key
            $(document).on('keydown', this.handleKeydown);
            
            // Outside click
            $(document).on('click', this.handleOutsideClick);
            
            // Window resize
            $(window).on('resize', this.handleResize);
        },

        setCustomStyles: function() {
            const $button = $('.alam-corner-category-button');
            if (!$button.length) return;
            
            const color = $button.data('color') || '#2c5aa0';
            
            // Create dynamic styles
            const styles = `
                .alam-corner-category-button {
                    --corner-button-color: ${color};
                    --corner-button-hover: ${this.adjustColor(color, -20)};
                    --corner-button-light: ${this.adjustColor(color, 90)};
                }
            `;
            
            if (!$('#alam-corner-dynamic-styles').length) {
                $('<style id="alam-corner-dynamic-styles">' + styles + '</style>').appendTo('head');
            }
        },

        adjustColor: function(hex, percent) {
            // Convert hex to RGB
            const num = parseInt(hex.replace('#', ''), 16);
            const amt = Math.round(2.55 * percent);
            const R = (num >> 16) + amt;
            const G = (num >> 8 & 0x00FF) + amt;
            const B = (num & 0x0000FF) + amt;
            
            return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
                (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
        },

        initScrollBehavior: function() {
            let lastScrollTop = 0;
            const $button = $('.alam-corner-category-button');
            
            $(window).on('scroll', function() {
                const scrollTop = $(this).scrollTop();
                
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down
                    $button.addClass('hidden');
                } else {
                    // Scrolling up
                    $button.removeClass('hidden');
                }
                
                lastScrollTop = scrollTop;
            });
        },

        toggleDropdown: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (AlamCornerButton.isOpen) {
                AlamCornerButton.closeDropdown();
            } else {
                AlamCornerButton.openDropdown();
            }
        },

        openDropdown: function() {
            if (AlamCornerButton.isOpen) return;
            
            const $button = $('.alam-corner-category-button');
            const $dropdown = $('.alam-corner-dropdown');
            const $overlay = $('.alam-corner-overlay');
            
            // Set state
            AlamCornerButton.isOpen = true;
            
            // Update UI
            $button.addClass('active');
            $dropdown.addClass('active');
            $overlay.addClass('active');
            $('body').addClass('corner-button-open');
            
            // Load categories if not loaded
            if (!AlamCornerButton.categoriesLoaded) {
                AlamCornerButton.loadCategories();
            }
            
            // Focus management
            setTimeout(() => {
                $('.alam-corner-close').focus();
            }, 300);
        },

        closeDropdown: function() {
            if (!AlamCornerButton.isOpen) return;
            
            const $button = $('.alam-corner-category-button');
            const $dropdown = $('.alam-corner-dropdown');
            const $overlay = $('.alam-corner-overlay');
            
            // Set state
            AlamCornerButton.isOpen = false;
            
            // Update UI
            $button.removeClass('active');
            $dropdown.removeClass('active');
            $overlay.removeClass('active');
            $('body').removeClass('corner-button-open');
            
            // Return focus to main button
            $('.alam-corner-btn').focus();
        },

        loadCategories: function() {
            if (AlamCornerButton.isLoading) return;
            
            AlamCornerButton.isLoading = true;
            
            const $content = $('.alam-corner-dropdown-content');
            const $loading = $('.alam-corner-loading');
            
            $loading.show();
            
            $.ajax({
                url: AlamCornerButton.config.ajax_url,
                type: 'POST',
                data: {
                    action: 'alam_get_categories',
                    nonce: AlamCornerButton.config.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $content.html(response.data.categories);
                        AlamCornerButton.categoriesLoaded = true;
                        
                        // Add entrance animation
                        $('.alam-corner-category-item').each(function(index) {
                            const $item = $(this);
                            setTimeout(() => {
                                $item.addClass('animate-in');
                            }, index * 50);
                        });
                    } else {
                        $content.html('<div class="alam-corner-error">' + (response.data?.message || 'خطأ في تحميل الفئات') + '</div>');
                    }
                },
                error: function() {
                    $content.html('<div class="alam-corner-error">حدث خطأ، يرجى المحاولة مرة أخرى</div>');
                },
                complete: function() {
                    AlamCornerButton.isLoading = false;
                    $loading.hide();
                }
            });
        },

        handleCategoryClick: function(e) {
            const $link = $(this);
            
            // Add loading state
            $link.addClass('loading');
            
            // Add click animation
            $link.addClass('clicked');
            
            // Remove loading state after navigation
            setTimeout(() => {
                $link.removeClass('loading clicked');
            }, 300);
        },

        handleKeydown: function(e) {
            if (e.keyCode === 27) { // Escape key
                AlamCornerButton.closeDropdown();
            }
        },

        handleOutsideClick: function(e) {
            if (!$(e.target).closest('.alam-corner-category-button').length) {
                AlamCornerButton.closeDropdown();
            }
        },

        handleResize: function() {
            // Close dropdown on mobile orientation change
            if (window.innerWidth < 768) {
                AlamCornerButton.closeDropdown();
            }
        },

        // Utility function to check if device is mobile
        isMobile: function() {
            return window.innerWidth < 768;
        },

        // Function to update button position dynamically
        updatePosition: function(position) {
            const $button = $('.alam-corner-category-button');
            
            // Remove all position classes
            $button.removeClass('top-right top-left bottom-right bottom-left');
            
            // Add new position class
            $button.addClass(position);
        },

        // Function to update button style
        updateStyle: function(style) {
            const $button = $('.alam-corner-category-button');
            
            // Remove all style classes
            $button.removeClass('circular square rounded');
            
            // Add new style class
            $button.addClass(style);
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        // Check if corner button exists
        if ($('.alam-corner-category-button').length) {
            AlamCornerButton.init();
        }
    });

    // Make AlamCornerButton globally available for customizer live preview
    window.AlamCornerButton = AlamCornerButton;

})(jQuery);