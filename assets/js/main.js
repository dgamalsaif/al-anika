/**
 * Professional Main JavaScript
 * Alam Al Anika Theme - Advanced functionality
 */

(function($) {
    'use strict';

    // Professional Theme Object
    const AlamTheme = {
        
        // Initialize all functions
        init: function() {
            this.setupEventListeners();
            this.initMobileNavigation();
            this.initSmoothScrolling();
            this.initBackToTop();
            this.initLazyLoading();
            this.initProfessionalAnimations();
            this.initSearchFunctionality();
            this.initNewsletterForm();
            this.initTooltips();
            this.initPreloader();
            this.initResponsiveHandling();
            this.initPerformanceOptimizations();
        },

        // Setup global event listeners
        setupEventListeners: function() {
            $(document).ready(this.onDocumentReady.bind(this));
            $(window).on('load', this.onWindowLoad.bind(this));
            $(window).on('scroll', this.throttle(this.onWindowScroll.bind(this), 16));
            $(window).on('resize', this.debounce(this.onWindowResize.bind(this), 250));
        },

        // Document ready handler
        onDocumentReady: function() {
            this.updateHeaderState();
            this.initAccessibility();
            console.log('🎨 Alam Al Anika Professional Theme Loaded');
        },

        // Window load handler
        onWindowLoad: function() {
            this.hidePreloader();
            this.initProgressiveEnhancement();
        },

        // Window scroll handler
        onWindowScroll: function() {
            this.updateHeaderState();
            this.updateBackToTopVisibility();
            this.handleScrollAnimations();
        },

        // Window resize handler
        onWindowResize: function() {
            this.handleResponsiveChanges();
            this.updateMobileNavigation();
        },

        // Mobile Navigation
        initMobileNavigation: function() {
            const mobileToggle = $('.mobile-menu-toggle');
            const mobileOverlay = $('.mobile-nav-overlay');
            const mobileClose = $('.mobile-nav-close');

            mobileToggle.on('click', function(e) {
                e.preventDefault();
                AlamTheme.openMobileNav();
            });

            mobileClose.on('click', function(e) {
                e.preventDefault();
                AlamTheme.closeMobileNav();
            });

            mobileOverlay.on('click', function(e) {
                if (e.target === this) {
                    AlamTheme.closeMobileNav();
                }
            });

            // Close on escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && mobileOverlay.hasClass('active')) {
                    AlamTheme.closeMobileNav();
                }
            });
        },

        openMobileNav: function() {
            $('.mobile-nav-overlay').addClass('active');
            $('body').addClass('nav-open').css('overflow', 'hidden');
            $('.mobile-menu-toggle').attr('aria-expanded', 'true');
            
            // Focus management
            setTimeout(() => {
                $('.mobile-nav-close').focus();
            }, 100);
        },

        closeMobileNav: function() {
            $('.mobile-nav-overlay').removeClass('active');
            $('body').removeClass('nav-open').css('overflow', '');
            $('.mobile-menu-toggle').attr('aria-expanded', 'false');
            
            // Return focus
            $('.mobile-menu-toggle').focus();
        },

        updateMobileNavigation: function() {
            if ($(window).width() > 1023) {
                this.closeMobileNav();
            }
        },

        // Smooth Scrolling
        initSmoothScrolling: function() {
            $('a[href^="#"]').on('click', function(e) {
                const target = $(this.getAttribute('href'));
                
                if (target.length) {
                    e.preventDefault();
                    
                    const headerHeight = $('.site-header').outerHeight() || 0;
                    const targetPosition = target.offset().top - headerHeight - 20;
                    
                    $('html, body').animate({
                        scrollTop: targetPosition
                    }, 800, 'easeInOutCubic');
                }
            });
        },

        // Back to Top Button
        initBackToTop: function() {
            const backToTop = $('.back-to-top');
            
            backToTop.on('click', function(e) {
                e.preventDefault();
                
                $('html, body').animate({
                    scrollTop: 0
                }, 800, 'easeInOutCubic');
            });
        },

        updateBackToTopVisibility: function() {
            const backToTop = $('.back-to-top');
            const scrollPosition = $(window).scrollTop();
            
            if (scrollPosition > 300) {
                backToTop.addClass('show');
            } else {
                backToTop.removeClass('show');
            }
        },

        // Header State Management
        updateHeaderState: function() {
            const header = $('.site-header');
            const scrollPosition = $(window).scrollTop();
            
            if (scrollPosition > 100) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }
        },

        // Lazy Loading
        initLazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            this.loadImage(img);
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '50px 0px',
                    threshold: 0.01
                });

                $('img[data-src]').each(function() {
                    imageObserver.observe(this);
                });
            } else {
                // Fallback for older browsers
                $('img[data-src]').each(function() {
                    AlamTheme.loadImage(this);
                });
            }
        },

        loadImage: function(img) {
            const $img = $(img);
            const src = $img.attr('data-src');
            
            if (src) {
                $img.attr('src', src)
                    .addClass('loaded')
                    .removeAttr('data-src');
            }
        },

        // Professional Animations
        initProfessionalAnimations: function() {
            // Fade in animations
            if ('IntersectionObserver' in window) {
                const animationObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-in');
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                $('.card, .section-header, .feature-card, .product-card').each(function() {
                    animationObserver.observe(this);
                });
            }

            // Stagger animations for groups
            $('.grid .card').each(function(index) {
                $(this).css('animation-delay', (index * 100) + 'ms');
            });
        },

        handleScrollAnimations: function() {
            // Parallax effects (if needed)
            const scrolled = $(window).scrollTop();
            const rate = scrolled * -0.5;
            
            $('.parallax-element').css('transform', `translateY(${rate}px)`);
        },

        // Search Functionality
        initSearchFunctionality: function() {
            const searchInput = $('#search-input');
            const searchSuggestions = $('#search-suggestions');
            let searchTimeout;

            if (searchInput.length && searchSuggestions.length) {
                searchInput.on('input', function() {
                    const query = $(this).val().trim();
                    
                    clearTimeout(searchTimeout);
                    
                    if (query.length >= 2) {
                        searchTimeout = setTimeout(() => {
                            AlamTheme.performSearch(query, searchSuggestions);
                        }, 300);
                    } else {
                        searchSuggestions.hide();
                    }
                });

                // Close suggestions when clicking outside
                $(document).on('click', function(e) {
                    if (!searchInput.is(e.target) && !searchSuggestions.is(e.target) && !searchSuggestions.has(e.target).length) {
                        searchSuggestions.hide();
                    }
                });

                // Keyboard navigation for suggestions
                searchInput.on('keydown', function(e) {
                    const suggestions = searchSuggestions.find('.suggestion-item');
                    const current = suggestions.filter('.active');
                    
                    if (e.keyCode === 40) { // Down arrow
                        e.preventDefault();
                        if (current.length === 0) {
                            suggestions.first().addClass('active');
                        } else {
                            current.removeClass('active').next().addClass('active');
                        }
                    } else if (e.keyCode === 38) { // Up arrow
                        e.preventDefault();
                        if (current.length === 0) {
                            suggestions.last().addClass('active');
                        } else {
                            current.removeClass('active').prev().addClass('active');
                        }
                    } else if (e.keyCode === 13) { // Enter
                        if (current.length > 0) {
                            e.preventDefault();
                            current.trigger('click');
                        }
                    }
                });
            }
        },

        performSearch: function(query, container) {
            // This would typically make an AJAX call to get search results
            // For demo purposes, we'll show a loading state
            container.html(`
                <div class="suggestions-header">
                    <span class="suggestions-title">البحث عن "${query}"</span>
                </div>
                <div class="suggestions-content">
                    <div class="suggestion-item">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>جار البحث...</span>
                    </div>
                </div>
            `).show();

            // Simulate search results
            setTimeout(() => {
                const mockResults = [
                    { title: 'منتج مميز 1', type: 'product', url: '#' },
                    { title: 'منتج مميز 2', type: 'product', url: '#' },
                    { title: 'قسم الملابس', type: 'category', url: '#' },
                ];

                let html = `
                    <div class="suggestions-header">
                        <span class="suggestions-title">نتائج البحث</span>
                    </div>
                    <div class="suggestions-content">
                `;

                mockResults.forEach(result => {
                    html += `
                        <a href="${result.url}" class="suggestion-item">
                            <i class="fas fa-${result.type === 'product' ? 'box' : 'folder'}"></i>
                            <span>${result.title}</span>
                        </a>
                    `;
                });

                html += `
                    </div>
                    <div class="suggestions-footer">
                        <a href="#" class="view-all-results">عرض جميع النتائج</a>
                    </div>
                `;

                container.html(html);
            }, 500);
        },

        // Newsletter Form
        initNewsletterForm: function() {
            $('.newsletter-form, .subscription-form').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const email = form.find('input[type="email"]').val();
                const button = form.find('button[type="submit"]');
                const originalText = button.html();
                
                if (!AlamTheme.isValidEmail(email)) {
                    AlamTheme.showNotification('البريد الإلكتروني غير صحيح', 'error');
                    return;
                }
                
                // Add loading state
                button.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
                
                // Simulate API call
                setTimeout(() => {
                    AlamTheme.showNotification('تم الاشتراك بنجاح!', 'success');
                    form[0].reset();
                    button.html(originalText).prop('disabled', false);
                }, 2000);
            });
        },

        // Tooltips
        initTooltips: function() {
            $('[data-tooltip]').on('mouseenter', function() {
                const tooltip = $(this).attr('data-tooltip');
                const $tooltip = $(`<div class="tooltip">${tooltip}</div>`);
                
                $('body').append($tooltip);
                
                const rect = this.getBoundingClientRect();
                $tooltip.css({
                    top: rect.top - $tooltip.outerHeight() - 10,
                    left: rect.left + (rect.width / 2) - ($tooltip.outerWidth() / 2)
                }).addClass('show');
                
                $(this).data('tooltip-element', $tooltip);
            }).on('mouseleave', function() {
                const $tooltip = $(this).data('tooltip-element');
                if ($tooltip) {
                    $tooltip.remove();
                }
            });
        },

        // Preloader
        initPreloader: function() {
            // Show preloader
            $('body').addClass('loading');
        },

        hidePreloader: function() {
            const preloader = $('#loading-screen');
            
            setTimeout(() => {
                preloader.addClass('hidden');
                $('body').removeClass('loading');
                
                setTimeout(() => {
                    preloader.remove();
                }, 500);
            }, 1000);
        },

        // Responsive Handling
        initResponsiveHandling: function() {
            this.handleResponsiveImages();
            this.handleResponsiveTables();
        },

        handleResponsiveChanges: function() {
            this.handleResponsiveImages();
            this.updateMobileNavigation();
        },

        handleResponsiveImages: function() {
            $('img').each(function() {
                const $img = $(this);
                if (!$img.parent().hasClass('image-container')) {
                    $img.wrap('<div class="image-container"></div>');
                }
            });
        },

        handleResponsiveTables: function() {
            $('table').each(function() {
                if (!$(this).parent().hasClass('table-responsive')) {
                    $(this).wrap('<div class="table-responsive"></div>');
                }
            });
        },

        // Performance Optimizations
        initPerformanceOptimizations: function() {
            // Debounce resize events
            this.setupIntersectionObservers();
            this.optimizeAnimations();
        },

        setupIntersectionObservers: function() {
            // Setup observers for various elements
            if ('IntersectionObserver' in window) {
                // Video lazy loading
                const videoObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const video = entry.target;
                            if (video.dataset.src) {
                                video.src = video.dataset.src;
                                video.load();
                                videoObserver.unobserve(video);
                            }
                        }
                    });
                });

                $('video[data-src]').each(function() {
                    videoObserver.observe(this);
                });
            }
        },

        optimizeAnimations: function() {
            // Reduce motion for users who prefer it
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                $('*').css({
                    'animation-duration': '0.01ms !important',
                    'animation-iteration-count': '1 !important',
                    'transition-duration': '0.01ms !important'
                });
            }
        },

        // Accessibility
        initAccessibility: function() {
            // Focus management
            this.manageFocus();
            
            // Keyboard navigation
            this.setupKeyboardNavigation();
            
            // ARIA attributes
            this.updateAriaAttributes();
        },

        manageFocus: function() {
            // Skip links
            $('.skip-link').on('click', function(e) {
                const target = $($(this).attr('href'));
                if (target.length) {
                    target.attr('tabindex', '-1').focus();
                }
            });
        },

        setupKeyboardNavigation: function() {
            // Dropdown navigation with keyboard
            $('.menu-item-has-children > a').on('keydown', function(e) {
                if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                    e.preventDefault();
                    $(this).parent().toggleClass('open');
                }
            });
        },

        updateAriaAttributes: function() {
            // Update aria-expanded for dropdowns
            $('.menu-item-has-children').each(function() {
                const $item = $(this);
                const $link = $item.children('a');
                
                $link.attr('aria-expanded', $item.hasClass('open') ? 'true' : 'false');
            });
        },

        // Progressive Enhancement
        initProgressiveEnhancement: function() {
            // Add JS class to body
            $('body').addClass('js-enabled');
            
            // Enhanced forms
            this.enhanceForms();
            
            // Enhanced interactions
            this.enhanceInteractions();
        },

        enhanceForms: function() {
            // Add floating labels
            $('.form-group input, .form-group textarea').on('focus blur', function() {
                $(this).parent().toggleClass('focused', this.value !== '' || document.activeElement === this);
            });
            
            // Form validation
            $('form').on('submit', function(e) {
                const form = this;
                const isValid = AlamTheme.validateForm(form);
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        },

        enhanceInteractions: function() {
            // Add ripple effect to buttons
            $('.btn').on('click', function(e) {
                const btn = $(this);
                const ripple = $('<span class="ripple"></span>');
                
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.css({
                    width: size,
                    height: size,
                    left: x,
                    top: y
                });
                
                btn.append(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        },

        // Utility Functions
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
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },

        isValidEmail: function(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },

        validateForm: function(form) {
            let isValid = true;
            
            $(form).find('[required]').each(function() {
                const field = $(this);
                const value = field.val().trim();
                
                if (!value) {
                    field.addClass('error');
                    isValid = false;
                } else {
                    field.removeClass('error');
                    
                    // Email validation
                    if (field.attr('type') === 'email' && !AlamTheme.isValidEmail(value)) {
                        field.addClass('error');
                        isValid = false;
                    }
                }
            });
            
            return isValid;
        },

        showNotification: function(message, type = 'info') {
            const notification = $(`
                <div class="notification notification-${type}">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" aria-label="إغلاق">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `);
            
            $('body').append(notification);
            
            setTimeout(() => notification.addClass('show'), 100);
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                notification.removeClass('show');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
            
            // Close on click
            notification.find('.notification-close').on('click', function() {
                notification.removeClass('show');
                setTimeout(() => notification.remove(), 300);
            });
        }
    };

    // Initialize theme when document is ready
    $(document).ready(function() {
        AlamTheme.init();
    });

    // Make AlamTheme available globally
    window.AlamTheme = AlamTheme;

    // Custom easing function
    $.easing.easeInOutCubic = function(x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
        return c / 2 * ((t -= 2) * t * t + 2) + b;
    };

})(jQuery);

// Additional Professional Styles for JavaScript functionality
const additionalCSS = `
<style>
/* Professional JavaScript Enhancement Styles */
.js-enabled .no-js { display: none !important; }

/* Notification Styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    padding: var(--space-4) var(--space-6);
    z-index: 10000;
    transform: translateX(100%);
    transition: var(--transition-all);
    max-width: 400px;
    border-left: 4px solid var(--info-500);
}

.notification.show {
    transform: translateX(0);
}

.notification-success {
    border-left-color: var(--success-500);
}

.notification-error {
    border-left-color: var(--danger-500);
}

.notification-warning {
    border-left-color: var(--warning-500);
}

.notification-message {
    display: block;
    margin-bottom: var(--space-2);
    font-weight: var(--font-medium);
}

.notification-close {
    position: absolute;
    top: var(--space-2);
    right: var(--space-2);
    background: none;
    border: none;
    font-size: var(--text-sm);
    color: var(--secondary-500);
    cursor: pointer;
}

/* Animation Classes */
.animate-in {
    opacity: 1;
    transform: translateY(0);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.card, .section-header, .feature-card, .product-card {
    opacity: 0;
    transform: translateY(30px);
}

/* Ripple Effect */
.btn {
    position: relative;
    overflow: hidden;
}

.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Form Enhancements */
.form-group {
    position: relative;
}

.form-group.focused label {
    transform: translateY(-12px) scale(0.9);
    color: var(--primary-600);
}

.form-group input.error,
.form-group textarea.error {
    border-color: var(--danger-500);
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

/* Search Suggestions Styles */
.search-suggestions {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    overflow: hidden;
    max-height: 400px;
    overflow-y: auto;
}

.suggestions-header {
    background: var(--secondary-50);
    padding: var(--space-3) var(--space-4);
    border-bottom: 1px solid var(--secondary-200);
}

.suggestions-title {
    font-weight: var(--font-semibold);
    color: var(--secondary-700);
}

.suggestion-item {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-4);
    text-decoration: none;
    color: var(--secondary-700);
    transition: var(--transition-all);
    border-bottom: 1px solid var(--secondary-100);
}

.suggestion-item:hover,
.suggestion-item.active {
    background: var(--primary-50);
    color: var(--primary-700);
    text-decoration: none;
}

.suggestions-footer {
    background: var(--secondary-50);
    padding: var(--space-3) var(--space-4);
    text-align: center;
}

.view-all-results {
    color: var(--primary-600);
    text-decoration: none;
    font-weight: var(--font-semibold);
}

/* Tooltip Styles */
.tooltip {
    position: absolute;
    background: var(--secondary-800);
    color: white;
    padding: var(--space-2) var(--space-3);
    border-radius: var(--radius-default);
    font-size: var(--text-sm);
    white-space: nowrap;
    z-index: 10000;
    opacity: 0;
    transform: translateY(5px);
    transition: var(--transition-all);
    pointer-events: none;
}

.tooltip.show {
    opacity: 1;
    transform: translateY(0);
}

.tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: var(--secondary-800);
}

/* Loading States */
.loading {
    position: relative;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
}

/* Responsive Table */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Image Container */
.image-container {
    position: relative;
    overflow: hidden;
}

.image-container img {
    transition: var(--transition-all);
}

.image-container:hover img {
    transform: scale(1.05);
}

/* Performance Optimizations */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* Mobile Optimizations */
@media (max-width: 767px) {
    .notification {
        top: 10px;
        right: 10px;
        left: 10px;
        max-width: none;
    }
    
    .tooltip {
        font-size: var(--text-xs);
        padding: var(--space-1) var(--space-2);
    }
}
</style>
`;

// Inject additional CSS
document.head.insertAdjacentHTML('beforeend', additionalCSS);
