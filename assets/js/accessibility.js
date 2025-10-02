/**
 * Accessibility JavaScript for Al-Anika Theme
 * WCAG 2.1 AA compliance and enhanced keyboard navigation
 */

(function($) {
    'use strict';
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        AlAnikaAccessibility.init();
    });
    
    const AlAnikaAccessibility = {
        
        init: function() {
            this.setupKeyboardNavigation();
            this.setupFocusManagement();
            this.setupScreenReaderSupport();
            this.setupColorContrastToggle();
            this.setupFontSizeToggle();
            this.setupMotionControls();
            this.setupAriaLiveRegions();
            this.setupModalAccessibility();
        },
        
        /**
         * Setup keyboard navigation
         */
        setupKeyboardNavigation: function() {
            // Track keyboard usage
            let isKeyboardUser = false;
            
            $(document).on('keydown', function(e) {
                if (e.key === 'Tab') {
                    isKeyboardUser = true;
                    $('body').addClass('keyboard-navigation');
                }
            });
            
            $(document).on('mousedown', function() {
                isKeyboardUser = false;
                $('body').removeClass('keyboard-navigation');
            });
            
            // Skip links functionality
            $('.skip-link').on('focus', function() {
                $(this).css({
                    'position': 'static',
                    'width': 'auto',
                    'height': 'auto',
                    'padding': '8px 16px',
                    'background': '#000',
                    'color': '#fff',
                    'text-decoration': 'none',
                    'z-index': '100001'
                });
            }).on('blur', function() {
                $(this).css({
                    'position': 'absolute',
                    'left': '-9999px',
                    'top': 'auto',
                    'width': '1px',
                    'height': '1px',
                    'overflow': 'hidden'
                });
            });
            
            // Enhanced menu navigation
            $('.menu-item-has-children > a').attr('aria-haspopup', 'true').attr('aria-expanded', 'false');
            
            $('.menu-item-has-children').on('keydown', function(e) {
                const $menuItem = $(this);
                const $submenu = $menuItem.find('.sub-menu').first();
                const $menuLink = $menuItem.find('> a');
                
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    
                    if ($submenu.is(':visible')) {
                        $submenu.hide();
                        $menuLink.attr('aria-expanded', 'false');
                    } else {
                        $submenu.show();
                        $menuLink.attr('aria-expanded', 'true');
                        $submenu.find('a').first().focus();
                    }
                } else if (e.key === 'Escape') {
                    $submenu.hide();
                    $menuLink.attr('aria-expanded', 'false').focus();
                }
            });
            
            // Arrow key navigation in menus
            $('.menu a').on('keydown', function(e) {
                const $currentLink = $(this);
                let $targetLink;
                
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        $targetLink = $currentLink.parent().next().find('a').first();
                        if ($targetLink.length === 0) {
                            $targetLink = $currentLink.closest('.menu').find('a').first();
                        }
                        $targetLink.focus();
                        break;
                        
                    case 'ArrowUp':
                        e.preventDefault();
                        $targetLink = $currentLink.parent().prev().find('a').first();
                        if ($targetLink.length === 0) {
                            $targetLink = $currentLink.closest('.menu').find('a').last();
                        }
                        $targetLink.focus();
                        break;
                        
                    case 'ArrowRight':
                        if ($currentLink.parent().hasClass('menu-item-has-children')) {
                            e.preventDefault();
                            const $submenu = $currentLink.next('.sub-menu');
                            if ($submenu.length) {
                                $submenu.show();
                                $currentLink.attr('aria-expanded', 'true');
                                $submenu.find('a').first().focus();
                            }
                        }
                        break;
                        
                    case 'ArrowLeft':
                        if ($currentLink.closest('.sub-menu').length) {
                            e.preventDefault();
                            const $parentLink = $currentLink.closest('.sub-menu').prev('a');
                            $currentLink.closest('.sub-menu').hide();
                            $parentLink.attr('aria-expanded', 'false').focus();
                        }
                        break;
                }
            });
        },
        
        /**
         * Setup focus management
         */
        setupFocusManagement: function() {
            // Enhanced focus indicators
            $('a, button, input, textarea, select, [tabindex]').on('focus', function() {
                if ($('body').hasClass('keyboard-navigation')) {
                    $(this).addClass('focus-visible');
                }
            }).on('blur', function() {
                $(this).removeClass('focus-visible');
            });
            
            // Focus trap for modals
            $(document).on('keydown', function(e) {
                if (e.key === 'Tab') {
                    const $activeModal = $('.al-anika-modal.active');
                    if ($activeModal.length) {
                        AlAnikaAccessibility.trapFocus(e, $activeModal);
                    }
                }
            });
        },
        
        /**
         * Trap focus within an element
         */
        trapFocus: function(e, $container) {
            const $focusableElements = $container.find(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            ).filter(':visible');
            
            const $firstElement = $focusableElements.first();
            const $lastElement = $focusableElements.last();
            
            if (e.shiftKey) {
                if (document.activeElement === $firstElement[0]) {
                    e.preventDefault();
                    $lastElement.focus();
                }
            } else {
                if (document.activeElement === $lastElement[0]) {
                    e.preventDefault();
                    $firstElement.focus();
                }
            }
        },
        
        /**
         * Setup screen reader support
         */
        setupScreenReaderSupport: function() {
            // Add missing alt text
            $('img').each(function() {
                const $img = $(this);
                if (!$img.attr('alt')) {
                    const title = $img.attr('title') || $img.closest('a').attr('title') || '';
                    $img.attr('alt', title);
                }
            });
            
            // Add ARIA labels to form fields without labels
            $('input, textarea, select').each(function() {
                const $field = $(this);
                const $label = $('label[for="' + $field.attr('id') + '"]');
                
                if ($label.length === 0 && !$field.attr('aria-label') && !$field.attr('aria-labelledby')) {
                    const placeholder = $field.attr('placeholder');
                    const name = $field.attr('name');
                    
                    if (placeholder) {
                        $field.attr('aria-label', placeholder);
                    } else if (name) {
                        $field.attr('aria-label', name.replace(/[_-]/g, ' ').replace(/\b\w/g, l => l.toUpperCase()));
                    }
                }
            });
            
            // Add role and aria-label to search forms
            $('.search-form').attr('role', 'search').attr('aria-label', alAnikaA11y.searchForm || 'Search');
            
            // Add aria-label to pagination
            $('.pagination').attr('aria-label', alAnikaA11y.pagination || 'Pagination');
            
            // Announce dynamic content changes
            $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', function(e, data) {
                let message = '';
                
                switch (e.type) {
                    case 'added_to_cart':
                        message = alAnikaA11y.addedToCart || 'Product added to cart';
                        break;
                    case 'removed_from_cart':
                        message = alAnikaA11y.removedFromCart || 'Product removed from cart';
                        break;
                    case 'updated_cart_totals':
                        message = alAnikaA11y.cartUpdated || 'Cart totals updated';
                        break;
                }
                
                if (message) {
                    AlAnikaAccessibility.announceToScreenReader(message);
                }
            });
        },
        
        /**
         * Announce message to screen readers
         */
        announceToScreenReader: function(message, priority = 'polite') {
            const $announcement = $('<div>', {
                'aria-live': priority,
                'aria-atomic': 'true',
                'class': 'screen-reader-text',
                'text': message
            });
            
            $('body').append($announcement);
            
            setTimeout(() => {
                $announcement.remove();
            }, 1000);
        },
        
        /**
         * Setup color contrast toggle
         */
        setupColorContrastToggle: function() {
            // Add high contrast toggle button
            if (!$('#contrast-toggle').length) {
                const $contrastToggle = $('<button>', {
                    'id': 'contrast-toggle',
                    'class': 'accessibility-toggle',
                    'aria-label': alAnikaA11y.toggleContrast || 'Toggle high contrast',
                    'title': alAnikaA11y.toggleContrast || 'Toggle high contrast',
                    'html': '<i class="fas fa-adjust"></i>'
                });
                
                $('body').append($contrastToggle);
            }
            
            $('#contrast-toggle').on('click', function() {
                $('body').toggleClass('high-contrast');
                
                const isHighContrast = $('body').hasClass('high-contrast');
                $(this).attr('aria-pressed', isHighContrast);
                
                localStorage.setItem('al-anika-high-contrast', isHighContrast);
                
                AlAnikaAccessibility.announceToScreenReader(
                    isHighContrast ? 
                    (alAnikaA11y.highContrastOn || 'High contrast enabled') : 
                    (alAnikaA11y.highContrastOff || 'High contrast disabled')
                );
            });
            
            // Restore contrast preference
            if (localStorage.getItem('al-anika-high-contrast') === 'true') {
                $('body').addClass('high-contrast');
                $('#contrast-toggle').attr('aria-pressed', 'true');
            }
        },
        
        /**
         * Setup font size toggle
         */
        setupFontSizeToggle: function() {
            // Add font size controls
            if (!$('#font-size-controls').length) {
                const $fontControls = $('<div>', {
                    'id': 'font-size-controls',
                    'class': 'accessibility-controls',
                    'html': `
                        <button id="font-decrease" aria-label="${alAnikaA11y.decreaseFont || 'Decrease font size'}" title="${alAnikaA11y.decreaseFont || 'Decrease font size'}">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button id="font-reset" aria-label="${alAnikaA11y.resetFont || 'Reset font size'}" title="${alAnikaA11y.resetFont || 'Reset font size'}">
                            <i class="fas fa-font"></i>
                        </button>
                        <button id="font-increase" aria-label="${alAnikaA11y.increaseFont || 'Increase font size'}" title="${alAnikaA11y.increaseFont || 'Increase font size'}">
                            <i class="fas fa-plus"></i>
                        </button>
                    `
                });
                
                $('body').append($fontControls);
            }
            
            let currentFontSize = localStorage.getItem('al-anika-font-size') || '100';
            this.applyFontSize(currentFontSize);
            
            $('#font-increase').on('click', function() {
                currentFontSize = Math.min(parseInt(currentFontSize) + 10, 150);
                AlAnikaAccessibility.applyFontSize(currentFontSize);
                localStorage.setItem('al-anika-font-size', currentFontSize);
                AlAnikaAccessibility.announceToScreenReader(alAnikaA11y.fontIncreased || 'Font size increased');
            });
            
            $('#font-decrease').on('click', function() {
                currentFontSize = Math.max(parseInt(currentFontSize) - 10, 80);
                AlAnikaAccessibility.applyFontSize(currentFontSize);
                localStorage.setItem('al-anika-font-size', currentFontSize);
                AlAnikaAccessibility.announceToScreenReader(alAnikaA11y.fontDecreased || 'Font size decreased');
            });
            
            $('#font-reset').on('click', function() {
                currentFontSize = '100';
                AlAnikaAccessibility.applyFontSize(currentFontSize);
                localStorage.setItem('al-anika-font-size', currentFontSize);
                AlAnikaAccessibility.announceToScreenReader(alAnikaA11y.fontReset || 'Font size reset to default');
            });
        },
        
        /**
         * Apply font size
         */
        applyFontSize: function(size) {
            $('html').css('font-size', size + '%');
        },
        
        /**
         * Setup motion controls
         */
        setupMotionControls: function() {
            // Respect user's motion preferences
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            
            if (prefersReducedMotion) {
                $('body').addClass('reduce-motion');
            }
            
            // Add motion toggle
            if (!$('#motion-toggle').length) {
                const $motionToggle = $('<button>', {
                    'id': 'motion-toggle',
                    'class': 'accessibility-toggle',
                    'aria-label': alAnikaA11y.toggleMotion || 'Toggle animations',
                    'title': alAnikaA11y.toggleMotion || 'Toggle animations',
                    'html': '<i class="fas fa-play"></i>'
                });
                
                $('body').append($motionToggle);
            }
            
            $('#motion-toggle').on('click', function() {
                $('body').toggleClass('reduce-motion');
                
                const motionReduced = $('body').hasClass('reduce-motion');
                $(this).find('i').toggleClass('fa-play', !motionReduced).toggleClass('fa-pause', motionReduced);
                $(this).attr('aria-pressed', motionReduced);
                
                localStorage.setItem('al-anika-reduce-motion', motionReduced);
                
                AlAnikaAccessibility.announceToScreenReader(
                    motionReduced ? 
                    (alAnikaA11y.motionReduced || 'Animations disabled') : 
                    (alAnikaA11y.motionEnabled || 'Animations enabled')
                );
            });
            
            // Restore motion preference
            if (localStorage.getItem('al-anika-reduce-motion') === 'true') {
                $('body').addClass('reduce-motion');
                $('#motion-toggle').find('i').removeClass('fa-play').addClass('fa-pause');
                $('#motion-toggle').attr('aria-pressed', 'true');
            }
        },
        
        /**
         * Setup ARIA live regions
         */
        setupAriaLiveRegions: function() {
            // Add live region for dynamic content
            if (!$('#aria-live-region').length) {
                $('body').append('<div id="aria-live-region" aria-live="polite" aria-atomic="true" class="screen-reader-text"></div>');
            }
            
            // Add assertive live region for urgent announcements
            if (!$('#aria-live-assertive').length) {
                $('body').append('<div id="aria-live-assertive" aria-live="assertive" aria-atomic="true" class="screen-reader-text"></div>');
            }
        },
        
        /**
         * Setup modal accessibility
         */
        setupModalAccessibility: function() {
            // When modal opens
            $(document).on('modal:open', '.al-anika-modal', function() {
                const $modal = $(this);
                const $trigger = $(document.activeElement);
                
                // Store the trigger element
                $modal.data('trigger', $trigger);
                
                // Set focus to modal
                const $focusTarget = $modal.find('[autofocus]').first();
                if ($focusTarget.length) {
                    $focusTarget.focus();
                } else {
                    const $firstFocusable = $modal.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])').first();
                    if ($firstFocusable.length) {
                        $firstFocusable.focus();
                    } else {
                        $modal.attr('tabindex', '-1').focus();
                    }
                }
                
                // Add aria-hidden to other content
                $('#main, #header, #footer').attr('aria-hidden', 'true');
                
                // Announce modal opening
                AlAnikaAccessibility.announceToScreenReader(
                    alAnikaA11y.modalOpened || 'Dialog opened',
                    'assertive'
                );
            });
            
            // When modal closes
            $(document).on('modal:close', '.al-anika-modal', function() {
                const $modal = $(this);
                const $trigger = $modal.data('trigger');
                
                // Restore focus to trigger
                if ($trigger && $trigger.length) {
                    $trigger.focus();
                }
                
                // Remove aria-hidden from other content
                $('#main, #header, #footer').removeAttr('aria-hidden');
                
                // Announce modal closing
                AlAnikaAccessibility.announceToScreenReader(
                    alAnikaA11y.modalClosed || 'Dialog closed'
                );
            });
            
            // Handle escape key in modals
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    const $activeModal = $('.al-anika-modal.active');
                    if ($activeModal.length) {
                        $activeModal.find('.modal-close').first().trigger('click');
                    }
                }
            });
        }
    };
    
    // Expose to global scope
    window.AlAnikaAccessibility = AlAnikaAccessibility;
    
})(jQuery);

// CSS for accessibility features
const accessibilityCSS = `
    /* Accessibility Controls */
    .accessibility-toggle,
    .accessibility-controls {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        background: #000;
        color: #fff;
        border: 2px solid #fff;
        border-radius: 4px;
        padding: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .accessibility-controls {
        display: flex;
        gap: 5px;
        right: 80px;
    }
    
    .accessibility-controls button {
        background: transparent;
        color: #fff;
        border: 1px solid #fff;
        border-radius: 3px;
        padding: 5px 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .accessibility-toggle:hover,
    .accessibility-controls button:hover {
        background: #fff;
        color: #000;
    }
    
    .accessibility-toggle:focus,
    .accessibility-controls button:focus {
        outline: 3px solid #4A90E2;
        outline-offset: 2px;
    }
    
    /* High Contrast Mode */
    .high-contrast {
        filter: contrast(150%) brightness(110%);
    }
    
    .high-contrast * {
        background: #000 !important;
        color: #fff !important;
        border-color: #fff !important;
    }
    
    .high-contrast a {
        color: #ffff00 !important;
    }
    
    .high-contrast a:visited {
        color: #ff00ff !important;
    }
    
    .high-contrast button {
        background: #0000ff !important;
        color: #fff !important;
    }
    
    /* Reduced Motion */
    .reduce-motion *,
    .reduce-motion *::before,
    .reduce-motion *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
    
    /* Focus Visible */
    .focus-visible {
        outline: 3px solid #4A90E2 !important;
        outline-offset: 2px !important;
    }
    
    /* Keyboard Navigation */
    .keyboard-navigation .focus-visible {
        outline: 3px solid #4A90E2 !important;
        outline-offset: 2px !important;
        box-shadow: 0 0 0 1px #4A90E2 !important;
    }
    
    /* Screen Reader Only */
    .screen-reader-text {
        position: absolute !important;
        clip: rect(1px, 1px, 1px, 1px) !important;
        width: 1px !important;
        height: 1px !important;
        overflow: hidden !important;
    }
    
    .screen-reader-text:focus {
        background: #f1f1f1 !important;
        border-radius: 3px !important;
        box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6) !important;
        clip: auto !important;
        color: #21759b !important;
        display: block !important;
        font-size: 14px !important;
        font-weight: bold !important;
        height: auto !important;
        left: 5px !important;
        line-height: normal !important;
        padding: 15px 23px 14px !important;
        text-decoration: none !important;
        top: 5px !important;
        width: auto !important;
        z-index: 100000 !important;
    }
    
    /* Skip Links */
    .skip-links a {
        position: absolute;
        left: -9999px;
        top: auto;
        width: 1px;
        height: 1px;
        overflow: hidden;
    }
    
    .skip-links a:focus {
        position: static;
        width: auto;
        height: auto;
        padding: 8px 16px;
        background: #000;
        color: #fff;
        text-decoration: none;
        z-index: 100001;
    }
`;

// Inject accessibility CSS
if (!document.getElementById('al-anika-accessibility-css')) {
    const style = document.createElement('style');
    style.id = 'al-anika-accessibility-css';
    style.textContent = accessibilityCSS;
    document.head.appendChild(style);
}
