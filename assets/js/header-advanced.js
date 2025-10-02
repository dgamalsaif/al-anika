/**
 * Advanced Header JavaScript
 * Professional E-commerce Header Functionality
 *
 * @package AlamAlAnika
 */

(function($) {
    'use strict';

    class AdvancedHeader {
        constructor() {
            this.searchTimer = null;
            this.searchCache = {};
            this.currentLanguage = 'ar';
            this.currentCurrency = 'USD';
            
            this.init();
        }

        init() {
            this.bindEvents();
            this.initializeComponents();
            this.loadFromStorage();
        }

        bindEvents() {
            // Search functionality
            $(document).on('input', '.search-field', this.handleSearchInput.bind(this));
            $(document).on('click', '.suggestion-item', this.selectSuggestion.bind(this));
            $(document).on('click', this.closeSuggestions.bind(this));

            // Dropdown toggles
            $(document).on('click', '.dropdown-toggle', this.toggleDropdown.bind(this));
            $(document).on('click', '.dropdown-item', this.selectDropdownItem.bind(this));

            // Mobile menu
            $(document).on('click', '.menu-toggle', this.toggleMobileMenu.bind(this));

            // Language switcher
            $(document).on('click', '[data-lang]', this.switchLanguage.bind(this));

            // Currency switcher
            $(document).on('click', '[data-currency]', this.switchCurrency.bind(this));

            // Cart updates
            $(document.body).on('added_to_cart', this.updateCartCount.bind(this));
            $(document.body).on('removed_from_cart', this.updateCartCount.bind(this));

            // Close dropdowns on outside click
            $(document).on('click', this.closeDropdowns.bind(this));

            // Window resize handler
            $(window).on('resize', this.handleResize.bind(this));
        }

        initializeComponents() {
            this.initSearchAutocomplete();
            this.initCartNotifications();
            this.initStickyHeader();
        }

        // ==========================================================================
        // SEARCH FUNCTIONALITY
        // ==========================================================================

        handleSearchInput(e) {
            const $input = $(e.target);
            const query = $input.val().trim();
            const $suggestions = $('#search-suggestions');

            if (query.length < 2) {
                $suggestions.hide();
                return;
            }

            // Clear previous timer
            if (this.searchTimer) {
                clearTimeout(this.searchTimer);
            }

            // Set new timer for debounced search
            this.searchTimer = setTimeout(() => {
                this.performSearch(query, $suggestions);
            }, 300);
        }

        performSearch(query, $suggestions) {
            // Check cache first
            if (this.searchCache[query]) {
                this.displaySuggestions(this.searchCache[query], $suggestions);
                return;
            }

            // Show loading state
            $suggestions.html('<div class="suggestions-content"><div class="suggestion-item">جاري البحث...</div></div>').show();

            // Perform AJAX search
            $.ajax({
                url: alamAlAnikaHeader.ajax_url,
                type: 'POST',
                data: {
                    action: 'alam_al_anika_search_suggestions',
                    query: query,
                    nonce: alamAlAnikaHeader.search_nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.searchCache[query] = response.data;
                        this.displaySuggestions(response.data, $suggestions);
                    }
                },
                error: () => {
                    $suggestions.hide();
                }
            });
        }

        displaySuggestions(suggestions, $suggestions) {
            if (!suggestions || suggestions.length === 0) {
                $suggestions.hide();
                return;
            }

            let html = '<div class="suggestions-content">';
            
            suggestions.forEach(item => {
                html += `
                    <div class="suggestion-item" data-url="${item.url}" data-title="${item.title}">
                        <div class="suggestion-content">
                            ${item.image ? `<img src="${item.image}" alt="${item.title}" class="suggestion-image">` : ''}
                            <div class="suggestion-details">
                                <div class="suggestion-title">${item.title}</div>
                                ${item.price ? `<div class="suggestion-price">${item.price}</div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            
            $suggestions.html(html).show();
        }

        selectSuggestion(e) {
            e.preventDefault();
            const $item = $(e.currentTarget);
            const url = $item.data('url');
            const title = $item.data('title');

            if (url) {
                window.location.href = url;
            }
        }

        closeSuggestions(e) {
            const $target = $(e.target);
            if (!$target.closest('.search-container').length) {
                $('#search-suggestions').hide();
            }
        }

        initSearchAutocomplete() {
            // Add autocomplete attributes
            $('.search-field').attr({
                'autocomplete': 'off',
                'spellcheck': 'false'
            });
        }

        // ==========================================================================
        // DROPDOWN FUNCTIONALITY
        // ==========================================================================

        toggleDropdown(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $button = $(e.currentTarget);
            const $dropdown = $button.closest('.dropdown');
            const isActive = $dropdown.hasClass('active');

            // Close all dropdowns
            $('.dropdown').removeClass('active');

            // Toggle current dropdown
            if (!isActive) {
                $dropdown.addClass('active');
            }
        }

        selectDropdownItem(e) {
            e.preventDefault();
            const $item = $(e.currentTarget);
            const $dropdown = $item.closest('.dropdown');
            
            // Update button text if needed
            const text = $item.text().trim();
            $dropdown.find('.dropdown-toggle span').first().text(text);
            
            // Close dropdown
            $dropdown.removeClass('active');
        }

        closeDropdowns(e) {
            const $target = $(e.target);
            if (!$target.closest('.dropdown').length) {
                $('.dropdown').removeClass('active');
            }
        }

        // ==========================================================================
        // LANGUAGE & CURRENCY SWITCHING
        // ==========================================================================

        switchLanguage(e) {
            e.preventDefault();
            const $link = $(e.currentTarget);
            const lang = $link.data('lang');
            
            this.currentLanguage = lang;
            this.saveToStorage();
            
            // Update UI
            this.updateLanguageDisplay(lang);
            
            // Trigger language change event
            $(document).trigger('language_changed', [lang]);
            
            // You can add actual language switching logic here
            console.log('Language switched to:', lang);
        }

        switchCurrency(e) {
            e.preventDefault();
            const $link = $(e.currentTarget);
            const currency = $link.data('currency');
            
            this.currentCurrency = currency;
            this.saveToStorage();
            
            // Update UI
            this.updateCurrencyDisplay(currency);
            
            // Trigger currency change event
            $(document).trigger('currency_changed', [currency]);
            
            // You can add actual currency switching logic here
            console.log('Currency switched to:', currency);
        }

        updateLanguageDisplay(lang) {
            const langNames = {
                'ar': 'العربية',
                'en': 'English'
            };
            
            $('.current-lang').text(langNames[lang] || lang);
            
            // Update document direction
            if (lang === 'ar') {
                $('html').attr('dir', 'rtl');
                $('body').addClass('rtl');
            } else {
                $('html').attr('dir', 'ltr');
                $('body').removeClass('rtl');
            }
        }

        updateCurrencyDisplay(currency) {
            $('.current-currency').text(currency);
            
            // Update all price displays (you can expand this)
            $('.price').each(function() {
                // Add currency conversion logic here
            });
        }

        // ==========================================================================
        // MOBILE MENU
        // ==========================================================================

        toggleMobileMenu(e) {
            e.preventDefault();
            const $button = $(e.currentTarget);
            const $menu = $('.nav-menu');
            const isActive = $menu.hasClass('active');

            $menu.toggleClass('active');
            $button.toggleClass('active');
            
            // Update accessibility attributes
            $button.attr('aria-expanded', !isActive);
        }

        // ==========================================================================
        // CART FUNCTIONALITY
        // ==========================================================================

        updateCartCount() {
            $.ajax({
                url: alamAlAnikaHeader.ajax_url,
                type: 'POST',
                data: {
                    action: 'alam_al_anika_get_cart_count',
                    nonce: alamAlAnikaHeader.cart_nonce
                },
                success: (response) => {
                    if (response.success) {
                        $('.cart-count').text(response.data.count);
                        $('.cart-total').text(response.data.total);
                        
                        // Animate cart icon
                        this.animateCartIcon();
                    }
                }
            });
        }

        animateCartIcon() {
            const $cartIcon = $('.cart-contents i');
            $cartIcon.addClass('animate-bounce');
            
            setTimeout(() => {
                $cartIcon.removeClass('animate-bounce');
            }, 600);
        }

        initCartNotifications() {
            // Add bounce animation class
            if (!$('head').find('#cart-animations').length) {
                $('head').append(`
                    <style id="cart-animations">
                        .animate-bounce {
                            animation: cartBounce 0.6s ease;
                        }
                        @keyframes cartBounce {
                            0%, 100% { transform: scale(1); }
                            50% { transform: scale(1.2); }
                        }
                    </style>
                `);
            }
        }

        // ==========================================================================
        // STICKY HEADER
        // ==========================================================================

        initStickyHeader() {
            let lastScrollTop = 0;
            const $header = $('.site-header');
            
            $(window).on('scroll', () => {
                const scrollTop = $(window).scrollTop();
                
                if (scrollTop > 100) {
                    $header.addClass('header-scrolled');
                } else {
                    $header.removeClass('header-scrolled');
                }
                
                // Hide/show header on scroll
                if (scrollTop > lastScrollTop && scrollTop > 200) {
                    $header.addClass('header-hidden');
                } else {
                    $header.removeClass('header-hidden');
                }
                
                lastScrollTop = scrollTop;
            });
            
            // Add sticky header styles
            if (!$('head').find('#sticky-header-styles').length) {
                $('head').append(`
                    <style id="sticky-header-styles">
                        .site-header {
                            transition: transform 0.3s ease, box-shadow 0.3s ease;
                        }
                        .site-header.header-scrolled {
                            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                        }
                        .site-header.header-hidden {
                            transform: translateY(-100%);
                        }
                    </style>
                `);
            }
        }

        // ==========================================================================
        // RESPONSIVE HANDLING
        // ==========================================================================

        handleResize() {
            const width = $(window).width();
            
            if (width > 768) {
                $('.nav-menu').removeClass('active');
                $('.menu-toggle').removeClass('active').attr('aria-expanded', 'false');
            }
        }

        // ==========================================================================
        // LOCAL STORAGE
        // ==========================================================================

        saveToStorage() {
            const settings = {
                language: this.currentLanguage,
                currency: this.currentCurrency
            };
            
            localStorage.setItem('alamAlAnikaHeaderSettings', JSON.stringify(settings));
        }

        loadFromStorage() {
            const stored = localStorage.getItem('alamAlAnikaHeaderSettings');
            
            if (stored) {
                try {
                    const settings = JSON.parse(stored);
                    if (settings.language) {
                        this.currentLanguage = settings.language;
                        this.updateLanguageDisplay(settings.language);
                    }
                    if (settings.currency) {
                        this.currentCurrency = settings.currency;
                        this.updateCurrencyDisplay(settings.currency);
                    }
                } catch (e) {
                    console.log('Error loading header settings from storage');
                }
            }
        }
    }

    // Initialize when document is ready
    $(document).ready(function() {
        new AdvancedHeader();
    });

})(jQuery);
