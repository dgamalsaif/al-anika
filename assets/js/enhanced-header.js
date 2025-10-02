/**
 * Enhanced Header Functionality for Al-Anika Theme
 * Includes search improvements, cart updates, and mobile navigation
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Header scroll effect
        let lastScrollTop = 0;
        const header = $('.enhanced-header');
        
        $(window).scroll(function() {
            const scrollTop = $(this).scrollTop();
            
            if (scrollTop > 100) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }
            
            lastScrollTop = scrollTop;
        });

        // Enhanced search functionality
        const searchForm = $('.search-form');
        const searchField = $('.search-field');
        const searchSubmit = $('.search-submit');
        
        // Search field focus effects
        searchField.on('focus', function() {
            searchForm.addClass('focused');
        }).on('blur', function() {
            searchForm.removeClass('focused');
        });
        
        // Search suggestions (if WooCommerce is active)
        if (typeof wc_add_to_cart_params !== 'undefined') {
            let searchTimeout;
            
            searchField.on('input', function() {
                const query = $(this).val();
                
                clearTimeout(searchTimeout);
                
                if (query.length >= 3) {
                    searchTimeout = setTimeout(function() {
                        performProductSearch(query);
                    }, 300);
                }
            });
        }
        
        // Mobile menu toggle
        const mobileToggle = $('.mobile-menu-toggle');
        const navMenu = $('.nav-menu');
        
        mobileToggle.on('click', function() {
            const isExpanded = $(this).attr('aria-expanded') === 'true';
            
            $(this).attr('aria-expanded', !isExpanded);
            navMenu.toggleClass('mobile-open');
            
            // Animate hamburger icon
            $(this).find('.menu-toggle-icon').toggleClass('active');
        });
        
        // Cart count update
        if (typeof wc_add_to_cart_params !== 'undefined') {
            $(document.body).on('added_to_cart', function(event, fragments, cart_hash) {
                updateCartCount();
            });
            
            $(document.body).on('wc_fragments_refreshed', function() {
                updateCartCount();
            });
        }
        
        // Smooth animations for navigation links
        $('.nav-menu a, .quick-nav-link').on('mouseenter', function() {
            $(this).addClass('hovered');
        }).on('mouseleave', function() {
            $(this).removeClass('hovered');
        });
        
        // Search form submission handling
        searchForm.on('submit', function(e) {
            const query = searchField.val().trim();
            
            if (query.length < 2) {
                e.preventDefault();
                searchField.focus();
                showSearchMessage(al_anika_header.search_placeholder);
                return false;
            }
            
            // Add loading state
            searchForm.addClass('loading');
            searchSubmit.prop('disabled', true);
        });
        
        // RTL support adjustments
        if (al_anika_header.is_rtl) {
            $('body').addClass('rtl-enhanced');
            
            // Adjust animations for RTL
            $('.nav-menu li a').each(function() {
                const $this = $(this);
                $this.on('mouseenter', function() {
                    $this.css('transform', 'translateX(5px) translateY(-2px)');
                }).on('mouseleave', function() {
                    $this.css('transform', 'translateX(0) translateY(0)');
                });
            });
        }
        
        // Accessibility improvements
        $('.cart-link, .search-submit, .nav-menu a').on('focus', function() {
            $(this).addClass('keyboard-focused');
        }).on('blur', function() {
            $(this).removeClass('keyboard-focused');
        });
        
        // Keyboard navigation
        $(document).on('keydown', function(e) {
            // ESC key closes mobile menu
            if (e.keyCode === 27 && navMenu.hasClass('mobile-open')) {
                mobileToggle.click();
            }
            
            // Enter key on search
            if (e.keyCode === 13 && searchField.is(':focus')) {
                searchForm.submit();
            }
        });
        
    });
    
    /**
     * Perform product search with AJAX
     */
    function performProductSearch(query) {
        if (typeof al_anika_header === 'undefined') return;
        
        $.ajax({
            url: al_anika_header.ajax_url,
            type: 'POST',
            data: {
                action: 'al_anika_product_search',
                query: query,
                nonce: al_anika_header.search_nonce
            },
            success: function(response) {
                if (response.success) {
                    displaySearchSuggestions(response.data);
                }
            },
            error: function() {
                console.log('Search request failed');
            }
        });
    }
    
    /**
     * Display search suggestions
     */
    function displaySearchSuggestions(suggestions) {
        let suggestionsHtml = '<div class="search-suggestions">';
        
        if (suggestions.length > 0) {
            suggestions.forEach(function(item) {
                suggestionsHtml += `
                    <div class="suggestion-item">
                        <img src="${item.image}" alt="${item.title}" class="suggestion-image">
                        <div class="suggestion-content">
                            <h4>${item.title}</h4>
                            <span class="suggestion-price">${item.price}</span>
                        </div>
                    </div>
                `;
            });
        } else {
            suggestionsHtml += `<div class="no-suggestions">${al_anika_header.no_results}</div>`;
        }
        
        suggestionsHtml += '</div>';
        
        // Remove existing suggestions
        $('.search-suggestions').remove();
        
        // Add new suggestions
        $('.search-form').after(suggestionsHtml);
        
        // Hide suggestions when clicking outside
        $(document).on('click.suggestions', function(e) {
            if (!$(e.target).closest('.search-form, .search-suggestions').length) {
                $('.search-suggestions').remove();
                $(document).off('click.suggestions');
            }
        });
    }
    
    /**
     * Update cart count
     */
    function updateCartCount() {
        if (typeof wc_add_to_cart_params === 'undefined') return;
        
        $.ajax({
            url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_cart_count'),
            type: 'POST',
            success: function(response) {
                if (response) {
                    $('.cart-count').text(response).show();
                    
                    // Animate cart count
                    $('.cart-count').addClass('updated');
                    setTimeout(function() {
                        $('.cart-count').removeClass('updated');
                    }, 500);
                }
            }
        });
    }
    
    /**
     * Show search message
     */
    function showSearchMessage(message) {
        const messageDiv = $(`<div class="search-message">${message}</div>`);
        $('.search-form').after(messageDiv);
        
        setTimeout(function() {
            messageDiv.fadeOut(function() {
                messageDiv.remove();
            });
        }, 3000);
    }
    
    /**
     * Initialize enhanced features based on screen size
     */
    function initResponsiveFeatures() {
        const isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            // Mobile-specific enhancements
            $('.nav-menu').addClass('mobile-nav');
            $('.search-form').addClass('mobile-search');
        } else {
            // Desktop-specific enhancements
            $('.nav-menu').removeClass('mobile-nav');
            $('.search-form').removeClass('mobile-search');
        }
    }
    
    // Initialize on load and resize
    $(window).on('load resize', initResponsiveFeatures);
    
    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));
        
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
    
    // Performance optimization: debounce scroll events
    function debounce(func, wait) {
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
    
    // Apply debouncing to scroll events
    $(window).on('scroll', debounce(function() {
        // Additional scroll-based functionality can be added here
    }, 10));

})(jQuery);

// CSS for additional enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add additional CSS for enhanced features
    const style = document.createElement('style');
    style.textContent = `
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .suggestion-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .suggestion-item:hover {
            background: #f8f9fa;
        }
        
        .suggestion-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .suggestion-content h4 {
            margin: 0;
            font-size: 14px;
            color: #333;
        }
        
        .suggestion-price {
            color: #ff6b9d;
            font-weight: 600;
            font-size: 13px;
        }
        
        .search-message {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #ff6b9d;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            font-size: 13px;
            z-index: 1000;
        }
        
        .cart-count.updated {
            animation: bounce 0.5s ease;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        
        .mobile-nav.mobile-open {
            display: flex !important;
            flex-direction: column;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 20px;
            z-index: 999;
        }
        
        .menu-toggle-icon.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }
        
        .menu-toggle-icon.active span:nth-child(2) {
            opacity: 0;
        }
        
        .menu-toggle-icon.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }
        
        .keyboard-focused {
            outline: 2px solid #ff6b9d !important;
            outline-offset: 2px;
        }
        
        .rtl-enhanced .suggestion-image {
            margin-right: 0;
            margin-left: 10px;
        }
    `;
    
    document.head.appendChild(style);
});
