/**
 * Interactive Animations & Micro-interactions JavaScript
 * Phase 4: Advanced Animation System
 * Version: 8.1.0
 */

(function($) {
    'use strict';

    class AlamAnimations {
        constructor() {
            this.init();
            this.bindEvents();
            this.setupCustomizerLivePreview();
            this.initScrollAnimations();
            this.initParallaxEffects();
            this.initMagneticElements();
            this.initProductImageInteractions();
        }

        init() {
            // Get animation settings from customizer
            this.settings = {
                animationSpeed: this.getCustomizerValue('alam_animation_speed', 'medium'),
                scrollAnimations: this.getCustomizerValue('alam_scroll_animations', true),
                parallaxEffects: this.getCustomizerValue('alam_parallax_effects', false),
                buttonHoverStyle: this.getCustomizerValue('alam_button_hover_style', 'scale'),
                imageHoverEffect: this.getCustomizerValue('alam_image_hover_effect', 'zoom'),
                productHoverStyle: this.getCustomizerValue('alam_product_hover_style', 'lift_shadow'),
                productImageInteraction: this.getCustomizerValue('alam_product_image_interaction', 'zoom_rotate'),
                menuTransition: this.getCustomizerValue('alam_menu_transition', 'slide_down'),
                scrollProgress: this.getCustomizerValue('alam_scroll_progress', true),
                pageLoadAnimation: this.getCustomizerValue('alam_page_load_animation', 'fade_in_up'),
                loadingSpinner: this.getCustomizerValue('alam_loading_spinner', 'modern_dots')
            };

            // Apply CSS variables based on settings
            this.applyCSSVariables();
            
            // Apply animation classes
            this.applyAnimationClasses();
            
            // Initialize scroll progress
            if (this.settings.scrollProgress) {
                this.initScrollProgress();
            }
            
            // Apply page load animation
            this.applyPageLoadAnimation();
        }

        getCustomizerValue(setting, defaultValue) {
            // Try to get from wp.customize first (in customizer preview)
            if (typeof wp !== 'undefined' && wp.customize && wp.customize(setting)) {
                return wp.customize(setting).get();
            }
            
            // Fallback to global variables or data attributes
            if (typeof alamCustomizerData !== 'undefined' && alamCustomizerData[setting]) {
                return alamCustomizerData[setting];
            }
            
            // Get from CSS custom property if available
            const root = document.documentElement;
            const cssValue = getComputedStyle(root).getPropertyValue(`--${setting.replace('alam_', '')}`);
            if (cssValue) {
                return cssValue.trim();
            }
            
            return defaultValue;
        }

        applyCSSVariables() {
            const root = document.documentElement;
            
            // Animation speed mapping
            const speedMap = {
                'slow': '0.5s',
                'medium': '0.3s',
                'fast': '0.2s',
                'instant': '0.1s'
            };
            
            root.style.setProperty('--current-animation-speed', speedMap[this.settings.animationSpeed] || '0.3s');
        }

        applyAnimationClasses() {
            // Apply button hover styles
            $('button, .button, input[type="submit"]').each((index, element) => {
                $(element).addClass(`btn-hover-${this.settings.buttonHoverStyle}`);
            });

            // Apply image hover effects
            $('.image-container, .product-image-container').each((index, element) => {
                $(element).addClass(`img-hover-${this.settings.imageHoverEffect}`);
            });

            // Apply product hover styles
            $('.product-card, .woocommerce ul.products li.product').each((index, element) => {
                $(element).addClass(`product-hover-${this.settings.productHoverStyle}`);
            });

            // Apply product image interactions
            $('.product-image-container, .woocommerce div.product div.images').each((index, element) => {
                $(element).addClass(`product-img-${this.settings.productImageInteraction}`);
            });

            // Apply menu transitions
            $('.main-navigation ul, .mobile-menu').each((index, element) => {
                $(element).addClass(`menu-${this.settings.menuTransition}`);
            });
        }

        initScrollProgress() {
            // Create scroll progress indicator
            if (!$('.scroll-progress').length) {
                $('body').prepend(`
                    <div class="scroll-progress">
                        <div class="scroll-progress-bar"></div>
                    </div>
                `);
            }

            $(window).on('scroll', () => {
                const scrollTop = $(window).scrollTop();
                const docHeight = $(document).height();
                const winHeight = $(window).height();
                const scrollPercent = (scrollTop / (docHeight - winHeight)) * 100;
                
                $('.scroll-progress-bar').css('width', scrollPercent + '%');
            });
        }

        applyPageLoadAnimation() {
            $('body').addClass(`page-load-${this.settings.pageLoadAnimation}`);
            
            // Remove animation class after animation completes
            setTimeout(() => {
                $('body').removeClass(`page-load-${this.settings.pageLoadAnimation}`);
            }, 1000);
        }

        initScrollAnimations() {
            if (!this.settings.scrollAnimations) return;

            // Add scroll reveal classes to elements
            $('.product-card, .content-section, .hero-section, .category-grid, .feature-box').each((index, element) => {
                $(element).addClass('scroll-reveal');
            });

            // Intersection Observer for scroll animations
            if (typeof IntersectionObserver !== 'undefined') {
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            $(entry.target).addClass('revealed');
                        }
                    });
                }, observerOptions);

                $('.scroll-reveal').each((index, element) => {
                    observer.observe(element);
                });
            } else {
                // Fallback for older browsers
                $(window).on('scroll', this.throttle(() => {
                    this.checkScrollElements();
                }, 100));
            }
        }

        checkScrollElements() {
            const windowTop = $(window).scrollTop();
            const windowBottom = windowTop + $(window).height();

            $('.scroll-reveal:not(.revealed)').each((index, element) => {
                const elementTop = $(element).offset().top;
                
                if (elementTop < windowBottom - 100) {
                    $(element).addClass('revealed');
                }
            });
        }

        initParallaxEffects() {
            if (!this.settings.parallaxEffects) return;

            // Add parallax classes to elements
            $('.hero-section, .banner-section, .category-card').each((index, element) => {
                $(element).addClass('parallax-element parallax-slow');
            });

            $(window).on('scroll', this.throttle(() => {
                this.updateParallax();
            }, 16)); // 60fps
        }

        updateParallax() {
            const scrollY = window.pageYOffset;
            
            $('.parallax-element').each((index, element) => {
                const rect = element.getBoundingClientRect();
                const speed = element.classList.contains('parallax-fast') ? 0.8 : 
                             element.classList.contains('parallax-medium') ? 0.5 : 0.3;
                
                if (rect.bottom >= 0 && rect.top <= window.innerHeight) {
                    const yPos = scrollY * speed;
                    element.style.setProperty('--parallax-y', `${yPos}px`);
                }
            });
        }

        initMagneticElements() {
            $('.product-img-magnetic').each((index, element) => {
                const $element = $(element);
                
                $element.on('mousemove', (e) => {
                    const rect = element.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    const deltaX = (x - centerX) / centerX;
                    const deltaY = (y - centerY) / centerY;
                    
                    const moveX = deltaX * 10; // Adjust sensitivity
                    const moveY = deltaY * 10;
                    
                    $element.find('img').css('transform', `translate(${moveX}px, ${moveY}px) scale(1.05)`);
                });
                
                $element.on('mouseleave', () => {
                    $element.find('img').css('transform', 'translate(0, 0) scale(1)');
                });
            });
        }

        initProductImageInteractions() {
            // Parallax move effect for product images
            $('.product-img-parallax-move').each((index, element) => {
                const $element = $(element);
                
                $element.on('mousemove', (e) => {
                    const rect = element.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    const deltaX = (x - centerX) / centerX;
                    const deltaY = (y - centerY) / centerY;
                    
                    const moveX = deltaX * 5;
                    const moveY = deltaY * 5;
                    
                    $element.find('img').css('transform', `translate(${moveX}px, ${moveY}px)`);
                });
                
                $element.on('mouseleave', () => {
                    $element.find('img').css('transform', 'translate(0, 0)');
                });
            });

            // Enhanced hover effects for product cards
            $('.product-card, .woocommerce ul.products li.product').each((index, element) => {
                const $element = $(element);
                
                $element.on('mouseenter', () => {
                    // Add micro-interactions
                    $element.find('.product-title').addClass('micro-float');
                    $element.find('.price').addClass('micro-pulse');
                    $element.find('.add-to-cart').addClass('micro-bounce');
                });
                
                $element.on('mouseleave', () => {
                    $element.find('.product-title').removeClass('micro-float');
                    $element.find('.price').removeClass('micro-pulse');
                    $element.find('.add-to-cart').removeClass('micro-bounce');
                });
            });
        }

        bindEvents() {
            // Enhanced menu animations
            $('.menu-toggle').on('click', (e) => {
                e.preventDefault();
                const $menu = $('.main-navigation ul');
                
                if ($menu.hasClass('active')) {
                    $menu.removeClass('active');
                    setTimeout(() => {
                        $menu.hide();
                    }, 300);
                } else {
                    $menu.show();
                    setTimeout(() => {
                        $menu.addClass('active');
                    }, 10);
                }
            });

            // Smooth scroll for anchor links
            $('a[href^="#"]').on('click', (e) => {
                const target = $(e.target.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 800, 'easeInOutCubic');
                }
            });

            // Loading animations for AJAX requests
            $(document).on('ajaxStart', () => {
                this.showLoadingSpinner();
            });

            $(document).on('ajaxComplete', () => {
                this.hideLoadingSpinner();
            });

            // Enhanced button click effects
            $('button, .button, input[type="submit"]').on('mousedown', function() {
                $(this).addClass('micro-bounce');
            }).on('mouseup mouseleave', function() {
                $(this).removeClass('micro-bounce');
            });

            // Image lazy loading with fade-in animation
            if (typeof IntersectionObserver !== 'undefined') {
                this.initLazyLoading();
            }
        }

        showLoadingSpinner() {
            if ($('.loading-overlay').length) return;
            
            const spinnerHTML = this.getSpinnerHTML();
            $('body').append(`
                <div class="loading-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(255, 255, 255, 0.9);
                    z-index: 10000;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                ">
                    ${spinnerHTML}
                </div>
            `);
        }

        hideLoadingSpinner() {
            $('.loading-overlay').fadeOut(300, function() {
                $(this).remove();
            });
        }

        getSpinnerHTML() {
            switch (this.settings.loadingSpinner) {
                case 'elegant_ring':
                    return '<div class="spinner-elegant-ring"></div>';
                case 'pulse_heart':
                    return '<div class="spinner-pulse-heart"></div>';
                case 'wave_motion':
                    return `<div class="spinner-wave-motion">
                        <div class="wave"></div>
                        <div class="wave"></div>
                        <div class="wave"></div>
                    </div>`;
                case 'geometric':
                    return '<div class="spinner-geometric"></div>';
                default: // modern_dots
                    return `<div class="spinner-modern-dots">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>`;
            }
        }

        initLazyLoading() {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.add('fade-in');
                        imageObserver.unobserve(img);
                    }
                });
            });

            $('img[data-src]').each((index, img) => {
                imageObserver.observe(img);
            });
        }

        setupCustomizerLivePreview() {
            if (typeof wp === 'undefined' || !wp.customize) return;

            // Live preview for animation settings
            wp.customize('alam_animation_speed', (value) => {
                value.bind((newval) => {
                    this.settings.animationSpeed = newval;
                    this.applyCSSVariables();
                });
            });

            wp.customize('alam_scroll_animations', (value) => {
                value.bind((newval) => {
                    this.settings.scrollAnimations = newval;
                    if (newval) {
                        this.initScrollAnimations();
                    } else {
                        $('.scroll-reveal').removeClass('scroll-reveal revealed');
                    }
                });
            });

            wp.customize('alam_parallax_effects', (value) => {
                value.bind((newval) => {
                    this.settings.parallaxEffects = newval;
                    if (newval) {
                        this.initParallaxEffects();
                    } else {
                        $('.parallax-element').removeClass('parallax-element parallax-slow parallax-medium parallax-fast');
                    }
                });
            });

            wp.customize('alam_scroll_progress', (value) => {
                value.bind((newval) => {
                    this.settings.scrollProgress = newval;
                    if (newval) {
                        this.initScrollProgress();
                    } else {
                        $('.scroll-progress').remove();
                    }
                });
            });
        }

        throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            }
        }

        // Performance monitoring
        logPerformance() {
            if (typeof performance !== 'undefined') {
                const perfData = performance.getEntriesByType('navigation')[0];
                console.log('Page Load Performance:', {
                    domContentLoaded: perfData.domContentLoadedEventEnd - perfData.domContentLoadedEventStart,
                    loadComplete: perfData.loadEventEnd - perfData.loadEventStart,
                    totalTime: perfData.loadEventEnd - perfData.navigationStart
                });
            }
        }
    }

    // Initialize when DOM is ready
    $(document).ready(() => {
        new AlamAnimations();
    });

    // Add easing functions for jQuery
    if (typeof $.easing !== 'undefined') {
        $.easing.easeInOutCubic = function (x, t, b, c, d) {
            if ((t/=d/2) < 1) return c/2*t*t*t + b;
            return c/2*((t-=2)*t*t + 2) + b;
        };
    }

})(jQuery);

/**
 * Utility Functions for Animations
 */
window.AlamAnimationUtils = {
    // Animate counter numbers
    animateCounter: function(element, start, end, duration) {
        const obj = { value: start };
        const $element = $(element);
        
        $({ value: start }).animate({ value: end }, {
            duration: duration || 2000,
            easing: 'swing',
            step: function() {
                $element.text(Math.ceil(this.value));
            },
            complete: function() {
                $element.text(end);
            }
        });
    },

    // Stagger animation for multiple elements
    staggerAnimation: function(selector, animationClass, delay) {
        $(selector).each((index, element) => {
            setTimeout(() => {
                $(element).addClass(animationClass);
            }, index * (delay || 100));
        });
    },

    // Create floating particles effect
    createParticles: function(container, count) {
        const $container = $(container);
        for (let i = 0; i < (count || 50); i++) {
            const $particle = $('<div class="particle"></div>');
            $particle.css({
                position: 'absolute',
                width: Math.random() * 4 + 'px',
                height: Math.random() * 4 + 'px',
                background: 'rgba(255, 255, 255, 0.8)',
                borderRadius: '50%',
                left: Math.random() * 100 + '%',
                top: Math.random() * 100 + '%',
                animation: `float ${Math.random() * 3 + 2}s ease-in-out infinite`
            });
            $container.append($particle);
        }
    },

    // Magnetic button effect
    addMagneticEffect: function(selector) {
        $(selector).each((index, element) => {
            const $element = $(element);
            
            $element.on('mousemove', (e) => {
                const rect = element.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                $element.css('transform', `translate(${x * 0.3}px, ${y * 0.3}px)`);
            });
            
            $element.on('mouseleave', () => {
                $element.css('transform', 'translate(0, 0)');
            });
        });
    }
};
