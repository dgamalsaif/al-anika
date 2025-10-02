/**
 * Professional Navigation JavaScript
 * Advanced navigation functionality for Alam Al Anika Theme
 */

(function($) {
    'use strict';

    const Navigation = {
        
        init: function() {
            this.setupMainNavigation();
            this.setupDropdowns();
            this.setupMegaMenu();
            this.setupSticky();
            this.setupMobileMenu();
            this.setupKeyboardNavigation();
        },

        setupMainNavigation: function() {
            // Add active class to current page
            const currentUrl = window.location.pathname;
            $('.nav-menu a').each(function() {
                if ($(this).attr('href') === currentUrl) {
                    $(this).parent().addClass('current-menu-item');
                }
            });
        },

        setupDropdowns: function() {
            const $dropdowns = $('.menu-item-has-children');
            
            $dropdowns.on('mouseenter', function() {
                $(this).addClass('open');
                $(this).children('.sub-menu').stop().fadeIn(200);
            }).on('mouseleave', function() {
                $(this).removeClass('open');
                $(this).children('.sub-menu').stop().fadeOut(200);
            });

            // Touch device handling
            $dropdowns.children('a').on('click', function(e) {
                if ($(window).width() <= 1023) {
                    e.preventDefault();
                    const $parent = $(this).parent();
                    $parent.toggleClass('open');
                    $parent.children('.sub-menu').slideToggle(300);
                }
            });
        },

        setupMegaMenu: function() {
            $('.mega-menu').each(function() {
                const $megaMenu = $(this);
                const $trigger = $megaMenu.children('a');
                const $content = $megaMenu.find('.mega-menu-content');

                $trigger.on('mouseenter', function() {
                    $content.addClass('show');
                });

                $megaMenu.on('mouseleave', function() {
                    $content.removeClass('show');
                });
            });
        },

        setupSticky: function() {
            const $header = $('.site-header');
            const headerHeight = $header.outerHeight();
            let isSticky = false;

            $(window).on('scroll', function() {
                const scrollTop = $(this).scrollTop();
                
                if (scrollTop > headerHeight && !isSticky) {
                    $header.addClass('sticky');
                    $('body').css('padding-top', headerHeight + 'px');
                    isSticky = true;
                } else if (scrollTop <= headerHeight && isSticky) {
                    $header.removeClass('sticky');
                    $('body').css('padding-top', '0');
                    isSticky = false;
                }
            });
        },

        setupMobileMenu: function() {
            const $toggle = $('.mobile-menu-toggle');
            const $menu = $('.mobile-menu');
            const $overlay = $('.mobile-menu-overlay');
            const $close = $('.mobile-menu-close');

            $toggle.on('click', function(e) {
                e.preventDefault();
                Navigation.openMobileMenu();
            });

            $close.on('click', function(e) {
                e.preventDefault();
                Navigation.closeMobileMenu();
            });

            $overlay.on('click', function() {
                Navigation.closeMobileMenu();
            });

            // Handle mobile submenu toggles
            $('.mobile-menu .menu-item-has-children > a').on('click', function(e) {
                e.preventDefault();
                const $parent = $(this).parent();
                const $submenu = $parent.children('.sub-menu');
                
                $parent.toggleClass('open');
                $submenu.slideToggle(300);
                
                // Update aria-expanded
                const isExpanded = $parent.hasClass('open');
                $(this).attr('aria-expanded', isExpanded);
            });
        },

        openMobileMenu: function() {
            $('.mobile-menu-overlay').addClass('active');
            $('body').addClass('mobile-menu-open').css('overflow', 'hidden');
            $('.mobile-menu-toggle').attr('aria-expanded', 'true');
            
            // Focus management
            setTimeout(() => {
                $('.mobile-menu-close').focus();
            }, 100);
        },

        closeMobileMenu: function() {
            $('.mobile-menu-overlay').removeClass('active');
            $('body').removeClass('mobile-menu-open').css('overflow', '');
            $('.mobile-menu-toggle').attr('aria-expanded', 'false');
            
            // Return focus
            $('.mobile-menu-toggle').focus();
        },

        setupKeyboardNavigation: function() {
            // Handle keyboard navigation
            $('.nav-menu a, .mobile-menu a').on('keydown', function(e) {
                const $this = $(this);
                const $parent = $this.parent();
                
                switch(e.keyCode) {
                    case 13: // Enter
                    case 32: // Space
                        if ($parent.hasClass('menu-item-has-children')) {
                            e.preventDefault();
                            $parent.toggleClass('open');
                            $this.attr('aria-expanded', $parent.hasClass('open'));
                        }
                        break;
                        
                    case 27: // Escape
                        if ($parent.hasClass('open')) {
                            $parent.removeClass('open');
                            $this.attr('aria-expanded', 'false');
                            $this.focus();
                        }
                        break;
                        
                    case 37: // Left arrow
                        if ($(window).width() > 1023) {
                            const $prevItem = $parent.prev();
                            if ($prevItem.length) {
                                $prevItem.children('a').focus();
                            }
                        }
                        break;
                        
                    case 39: // Right arrow
                        if ($(window).width() > 1023) {
                            const $nextItem = $parent.next();
                            if ($nextItem.length) {
                                $nextItem.children('a').focus();
                            }
                        }
                        break;
                        
                    case 40: // Down arrow
                        if ($parent.hasClass('menu-item-has-children') && $parent.hasClass('open')) {
                            e.preventDefault();
                            const $firstSubItem = $parent.find('.sub-menu > li:first-child > a');
                            $firstSubItem.focus();
                        }
                        break;
                        
                    case 38: // Up arrow
                        if ($parent.closest('.sub-menu').length) {
                            e.preventDefault();
                            const $parentLink = $parent.closest('.menu-item-has-children').children('a');
                            $parentLink.focus();
                        }
                        break;
                }
            });

            // Handle focus events
            $('.nav-menu a').on('focus', function() {
                $(this).parents('.menu-item-has-children').addClass('focused');
            }).on('blur', function() {
                setTimeout(() => {
                    const $parent = $(this).parents('.menu-item-has-children');
                    if (!$parent.find(':focus').length) {
                        $parent.removeClass('focused');
                    }
                }, 100);
            });
        }
    };

    // Initialize navigation when document is ready
    $(document).ready(function() {
        Navigation.init();
    });

    // Handle window resize
    $(window).on('resize', function() {
        if ($(window).width() > 1023) {
            Navigation.closeMobileMenu();
            $('.menu-item-has-children').removeClass('open');
            $('.sub-menu').removeAttr('style');
        }
    });

    // Make Navigation available globally
    window.Navigation = Navigation;

})(jQuery);
