/**
 * Al-Anika Theme - Core JavaScript
 * Professional E-commerce WordPress Theme
 * 
 * @package Al_Anika_Theme
 * @version 9.0.0 Final
 * @author MiniMax Agent
 */

(function($) {
    'use strict';

    // Global theme object
    window.AlAnikaTheme = {
        init: function() {
            this.mobileMenu();
            this.stickyHeader();
            this.smoothScroll();
            this.dropdownMenus();
            this.formValidation();
            this.ajaxHelpers();
            this.backToTop();
            this.lazyLoading();
            this.accessibilityEnhancements();
        },

        // Mobile menu functionality
        mobileMenu: function() {
            const $mobileToggle = $('.mobile-menu-toggle');
            const $navMenu = $('.nav-menu');
            
            $mobileToggle.on('click', function(e) {
                e.preventDefault();
                $(this).toggleClass('active');
                $navMenu.toggleClass('active');
                $('body').toggleClass('menu-open');
            });

            // Close mobile menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.main-navigation').length) {
                    $mobileToggle.removeClass('active');
                    $navMenu.removeClass('active');
                    $('body').removeClass('menu-open');
                }
            });

            // Handle submenu toggles on mobile
            $('.nav-menu .menu-item-has-children > a').on('click', function(e) {
                if ($(window).width() <= 768) {
                    e.preventDefault();
                    $(this).parent().toggleClass('submenu-open');
                    $(this).next('.sub-menu').slideToggle();
                }
            });
        },

        // Sticky header functionality
        stickyHeader: function() {
            if ($('body').hasClass('sticky-header-enabled')) {
                const $header = $('.site-header');
                const headerHeight = $header.outerHeight();
                let lastScrollTop = 0;

                $(window).on('scroll', function() {
                    const scrollTop = $(this).scrollTop();
                    
                    if (scrollTop > headerHeight) {
                        $header.addClass('scrolled');
                        
                        // Hide/show header based on scroll direction
                        if (scrollTop > lastScrollTop && scrollTop > headerHeight * 2) {
                            $header.addClass('hidden');
                        } else {
                            $header.removeClass('hidden');
                        }
                    } else {
                        $header.removeClass('scrolled hidden');
                    }
                    
                    lastScrollTop = scrollTop;
                });
            }
        },

        // Smooth scrolling for anchor links
        smoothScroll: function() {
            $('a[href*="#"]:not([href="#"])').on('click', function(e) {
                const target = $(this.hash);
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 800);
                }
            });
        },

        // Enhanced dropdown menus
        dropdownMenus: function() {
            let timeout;
            
            $('.nav-menu .menu-item-has-children').hover(
                function() {
                    clearTimeout(timeout);
                    $(this).addClass('hover');
                },
                function() {
                    const $this = $(this);
                    timeout = setTimeout(function() {
                        $this.removeClass('hover');
                    }, 300);
                }
            );

            // Keyboard navigation
            $('.nav-menu a').on('focus blur', function() {
                $(this).parents('.menu-item-has-children').toggleClass('focus');
            });
        },

        // Form validation
        formValidation: function() {
            $('form').on('submit', function(e) {
                let isValid = true;
                const $form = $(this);
                
                // Remove previous error states
                $form.find('.error').removeClass('error');
                $form.find('.error-message').remove();
                
                // Validate required fields
                $form.find('[required]').each(function() {
                    const $field = $(this);
                    const value = $field.val().trim();
                    
                    if (!value) {
                        isValid = false;
                        $field.addClass('error');
                        $field.after('<span class="error-message">This field is required.</span>');
                    }
                });
                
                // Validate email fields
                $form.find('input[type="email"]').each(function() {
                    const $field = $(this);
                    const value = $field.val().trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    
                    if (value && !emailRegex.test(value)) {
                        isValid = false;
                        $field.addClass('error');
                        $field.after('<span class="error-message">Please enter a valid email address.</span>');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    // Focus on first error field
                    $form.find('.error').first().focus();
                }
            });
        },

        // AJAX helpers
        ajaxHelpers: function() {
            // Global AJAX setup
            $.ajaxSetup({
                beforeSend: function(xhr) {
                    if (typeof alAnikaAjax !== 'undefined') {
                        xhr.setRequestHeader('X-WP-Nonce', alAnikaAjax.nonce);
                    }
                }
            });

            // Loading state helper
            window.AlAnikaTheme.setLoading = function($element, state) {
                if (state) {
                    $element.addClass('loading').prop('disabled', true);
                } else {
                    $element.removeClass('loading').prop('disabled', false);
                }
            };

            // Success message helper
            window.AlAnikaTheme.showMessage = function(message, type = 'success') {
                const $message = $('<div class="theme-message theme-message-' + type + '">' + message + '</div>');
                $('body').append($message);
                
                setTimeout(function() {
                    $message.addClass('show');
                }, 100);
                
                setTimeout(function() {
                    $message.removeClass('show');
                    setTimeout(function() {
                        $message.remove();
                    }, 300);
                }, 3000);
            };
        },

        // Back to top button
        backToTop: function() {
            const $backToTop = $('<button class="back-to-top" aria-label="Back to top"><i class="fas fa-chevron-up"></i></button>');
            $('body').append($backToTop);

            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 500) {
                    $backToTop.addClass('show');
                } else {
                    $backToTop.removeClass('show');
                }
            });

            $backToTop.on('click', function() {
                $('html, body').animate({ scrollTop: 0 }, 800);
            });
        },

        // Lazy loading for images
        lazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            observer.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(function(img) {
                    imageObserver.observe(img);
                });
            }
        },

        // Accessibility enhancements
        accessibilityEnhancements: function() {
            // Skip link functionality
            $('.skip-link').on('click', function(e) {
                const target = $(this.hash);
                if (target.length) {
                    e.preventDefault();
                    target.attr('tabindex', '-1').focus();
                }
            });

            // Keyboard navigation for dropdowns
            $('.nav-menu').on('keydown', 'a', function(e) {
                const $this = $(this);
                const $parent = $this.parent();
                const $submenu = $parent.find('.sub-menu').first();

                switch (e.which) {
                    case 38: // Up arrow
                        e.preventDefault();
                        if ($parent.prev().length) {
                            $parent.prev().find('a').first().focus();
                        }
                        break;
                    case 40: // Down arrow
                        e.preventDefault();
                        if ($submenu.length) {
                            $submenu.find('a').first().focus();
                        } else if ($parent.next().length) {
                            $parent.next().find('a').first().focus();
                        }
                        break;
                    case 37: // Left arrow
                        e.preventDefault();
                        if ($parent.parent('.sub-menu').length) {
                            $parent.parent('.sub-menu').siblings('a').focus();
                        }
                        break;
                    case 39: // Right arrow
                        e.preventDefault();
                        if ($submenu.length) {
                            $submenu.find('a').first().focus();
                        }
                        break;
                    case 27: // Escape
                        e.preventDefault();
                        if ($parent.parent('.sub-menu').length) {
                            $parent.parent('.sub-menu').siblings('a').focus();
                        }
                        break;
                }
            });

            // Announce dynamic content changes to screen readers
            window.AlAnikaTheme.announceToScreenReader = function(message) {
                const $announcement = $('<div class="sr-only" aria-live="polite"></div>');
                $('body').append($announcement);
                $announcement.text(message);
                setTimeout(function() {
                    $announcement.remove();
                }, 1000);
            };
        },

        // Utility functions
        utils: {
            debounce: function(func, wait, immediate) {
                let timeout;
                return function() {
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
                        setTimeout(function() {
                            inThrottle = false;
                        }, limit);
                    }
                };
            },

            isInViewport: function(element) {
                const rect = element.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }
        }
    };

    // Performance optimizations
    const optimizedScroll = AlAnikaTheme.utils.throttle(function() {
        // Scroll-based functionality
    }, 16);

    const optimizedResize = AlAnikaTheme.utils.debounce(function() {
        // Resize-based functionality
    }, 250);

    $(window).on('scroll', optimizedScroll);
    $(window).on('resize', optimizedResize);

    // Initialize theme when DOM is ready
    $(document).ready(function() {
        AlAnikaTheme.init();
    });

    // Re-initialize after AJAX content loads
    $(document).on('al_anika_content_loaded', function() {
        AlAnikaTheme.init();
    });

})(jQuery);

// CSS for theme messages and back to top button
const themeCSS = `
    .theme-message {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 4px;
        color: #fff;
        font-weight: 500;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        z-index: 10000;
        max-width: 300px;
    }
    
    .theme-message.show {
        transform: translateX(0);
    }
    
    .theme-message-success {
        background: #28a745;
    }
    
    .theme-message-error {
        background: #dc3545;
    }
    
    .theme-message-warning {
        background: #ffc107;
        color: #212529;
    }
    
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: var(--primary-color, #e74c3c);
        color: #fff;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 18px;
        opacity: 0;
        visibility: hidden;
        transform: scale(0.8);
        transition: all 0.3s ease;
        z-index: 1000;
    }
    
    .back-to-top.show {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }
    
    .back-to-top:hover {
        background: var(--secondary-color, #2c3e50);
        transform: scale(1.1);
    }
    
    .sr-only {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }
    
    .loading {
        position: relative;
        opacity: 0.6;
        pointer-events: none;
    }
    
    .error {
        border-color: #dc3545 !important;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 12px;
        display: block;
        margin-top: 5px;
    }
`;

// Inject CSS
const style = document.createElement('style');
style.textContent = themeCSS;
document.head.appendChild(style);