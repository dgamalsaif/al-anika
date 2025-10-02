/**
 * Customizer Live Preview for Phase 4 Animations
 * Real-time animation updates in the customizer
 */

(function($) {
    'use strict';

    // Animation Speed
    wp.customize('alam_animation_speed', function(value) {
        value.bind(function(newval) {
            const speedMap = {
                'slow': '0.5s',
                'medium': '0.3s',
                'fast': '0.2s',
                'instant': '0.1s'
            };
            
            const currentSpeed = speedMap[newval] || '0.3s';
            document.documentElement.style.setProperty('--current-animation-speed', currentSpeed);
            
            // Update body class
            $('body').removeClass('animation-speed-slow animation-speed-medium animation-speed-fast animation-speed-instant')
                     .addClass('animation-speed-' + newval);
        });
    });

    // Scroll Animations
    wp.customize('alam_scroll_animations', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('body').addClass('scroll-animations-enabled');
                // Re-add scroll reveal classes
                $('.product-card, .content-section, .hero-section, .category-grid, .feature-box').each(function() {
                    $(this).addClass('scroll-reveal');
                });
            } else {
                $('body').removeClass('scroll-animations-enabled');
                $('.scroll-reveal').removeClass('scroll-reveal revealed');
            }
        });
    });

    // Parallax Effects
    wp.customize('alam_parallax_effects', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('body').addClass('parallax-enabled');
                $('.hero-section, .banner-section, .category-card').each(function() {
                    $(this).addClass('parallax-element parallax-slow');
                });
            } else {
                $('body').removeClass('parallax-enabled');
                $('.parallax-element').removeClass('parallax-element parallax-slow parallax-medium parallax-fast');
            }
        });
    });

    // Button Hover Style
    wp.customize('alam_button_hover_style', function(value) {
        value.bind(function(newval) {
            // Remove all button hover classes
            $('button, .button, input[type="submit"]').removeClass(function(index, className) {
                return (className.match(/(^|\s)btn-hover-\S+/g) || []).join(' ');
            });
            
            // Add new button hover class
            $('button, .button, input[type="submit"]').each(function() {
                $(this).addClass('btn-hover-' + newval);
            });
            
            document.documentElement.style.setProperty('--button-hover-style', newval);
        });
    });

    // Image Hover Effect
    wp.customize('alam_image_hover_effect', function(value) {
        value.bind(function(newval) {
            // Remove all image hover classes
            $('.image-container, .product-image-container').removeClass(function(index, className) {
                return (className.match(/(^|\s)img-hover-\S+/g) || []).join(' ');
            });
            
            // Add new image hover class
            $('.image-container, .product-image-container').each(function() {
                $(this).addClass('img-hover-' + newval);
            });
            
            document.documentElement.style.setProperty('--image-hover-effect', newval);
        });
    });

    // Product Hover Style
    wp.customize('alam_product_hover_style', function(value) {
        value.bind(function(newval) {
            // Remove all product hover classes
            $('.product-card, .woocommerce ul.products li.product').removeClass(function(index, className) {
                return (className.match(/(^|\s)product-hover-\S+/g) || []).join(' ');
            });
            
            // Add new product hover class
            $('.product-card, .woocommerce ul.products li.product').each(function() {
                $(this).addClass('product-hover-' + newval);
            });
            
            document.documentElement.style.setProperty('--product-hover-style', newval);
        });
    });

    // Product Image Interaction
    wp.customize('alam_product_image_interaction', function(value) {
        value.bind(function(newval) {
            // Remove all product image interaction classes
            $('.product-image-container, .woocommerce div.product div.images').removeClass(function(index, className) {
                return (className.match(/(^|\s)product-img-\S+/g) || []).join(' ');
            });
            
            // Add new product image interaction class
            $('.product-image-container, .woocommerce div.product div.images').each(function() {
                $(this).addClass('product-img-' + newval);
            });
        });
    });

    // Menu Transition
    wp.customize('alam_menu_transition', function(value) {
        value.bind(function(newval) {
            // Remove all menu transition classes
            $('.main-navigation ul, .mobile-menu').removeClass(function(index, className) {
                return (className.match(/(^|\s)menu-\S+/g) || []).join(' ');
            });
            
            // Add new menu transition class
            $('.main-navigation ul, .mobile-menu').each(function() {
                $(this).addClass('menu-' + newval);
            });
        });
    });

    // Scroll Progress
    wp.customize('alam_scroll_progress', function(value) {
        value.bind(function(newval) {
            if (newval) {
                if (!$('.scroll-progress').length) {
                    $('body').prepend(`
                        <div class="scroll-progress">
                            <div class="scroll-progress-bar"></div>
                        </div>
                    `);
                }
            } else {
                $('.scroll-progress').remove();
            }
        });
    });

    // Page Load Animation
    wp.customize('alam_page_load_animation', function(value) {
        value.bind(function(newval) {
            // Remove all page load classes
            $('body').removeClass(function(index, className) {
                return (className.match(/(^|\s)page-load-\S+/g) || []).join(' ');
            });
            
            // Add new page load class
            $('body').addClass('page-load-' + newval.replace('_', '-'));
        });
    });

    // Loading Spinner
    wp.customize('alam_loading_spinner', function(value) {
        value.bind(function(newval) {
            // Update spinner style (will be applied on next AJAX request)
            if (typeof window.alamAnimations !== 'undefined') {
                window.alamAnimations.settings.loadingSpinner = newval;
            }
        });
    });

    // Real-time color updates for animations
    wp.customize('alam_primary_color', function(value) {
        value.bind(function(newval) {
            document.documentElement.style.setProperty('--primary-color', newval);
            
            // Convert hex to RGB for rgba usage
            const hex = newval.replace('#', '');
            const r = parseInt(hex.substr(0, 2), 16);
            const g = parseInt(hex.substr(2, 2), 16);
            const b = parseInt(hex.substr(4, 2), 16);
            
            document.documentElement.style.setProperty('--primary-color-rgb', `${r}, ${g}, ${b}`);
            document.documentElement.style.setProperty('--accent-glow', `rgba(${r}, ${g}, ${b}, 0.6)`);
        });
    });

    wp.customize('alam_secondary_color', function(value) {
        value.bind(function(newval) {
            document.documentElement.style.setProperty('--secondary-color', newval);
            
            // Convert hex to RGB for rgba usage
            const hex = newval.replace('#', '');
            const r = parseInt(hex.substr(0, 2), 16);
            const g = parseInt(hex.substr(2, 2), 16);
            const b = parseInt(hex.substr(4, 2), 16);
            
            document.documentElement.style.setProperty('--secondary-color-rgb', `${r}, ${g}, ${b}`);
        });
    });

    // Demo animation trigger for customizer
    function triggerDemoAnimations() {
        // Temporarily add demo classes to showcase effects
        setTimeout(function() {
            $('.product-card').first().addClass('demo-hover');
            setTimeout(function() {
                $('.product-card').first().removeClass('demo-hover');
            }, 2000);
        }, 500);
    }

    // Trigger demo when customizer panel opens
    wp.customize.panel('alam_animations_panel', function(panel) {
        panel.expanded.bind(function(isExpanded) {
            if (isExpanded) {
                triggerDemoAnimations();
            }
        });
    });

    // Add temporary demo styles
    $('<style id="customizer-demo-styles">').appendTo('head').text(`
        .demo-hover {
            transform: translateY(-10px) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
            transition: all 0.3s ease !important;
        }
    `);

})(jQuery);
