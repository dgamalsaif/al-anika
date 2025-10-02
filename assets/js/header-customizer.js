/**
 * Header Customizer Preview JavaScript
 * Live preview functionality for header customization
 *
 * @package AlamAlAnika
 */

(function($) {
    'use strict';

    // Header Height
    wp.customize('header_height', function(value) {
        value.bind(function(newval) {
            $('.site-header').css('height', newval + 'px');
        });
    });

    // Header Background Color
    wp.customize('header_bg_color', function(value) {
        value.bind(function(newval) {
            $('.site-header').css('background-color', newval);
        });
    });

    // Header Border Width
    wp.customize('header_border_width', function(value) {
        value.bind(function(newval) {
            $('.site-header').css('border-bottom-width', newval + 'px');
        });
    });

    // Header Border Color
    wp.customize('header_border_color', function(value) {
        value.bind(function(newval) {
            $('.site-header').css('border-bottom-color', newval);
        });
    });

    // Logo Text
    wp.customize('logo_text', function(value) {
        value.bind(function(newval) {
            $('.logo-text').text(newval);
        });
    });

    // Logo Font Family
    wp.customize('logo_font_family', function(value) {
        value.bind(function(newval) {
            $('.logo-text, .site-title a').css('font-family', newval + ', sans-serif');
        });
    });

    // Logo Font Size
    wp.customize('logo_font_size', function(value) {
        value.bind(function(newval) {
            $('.logo-text, .site-title a').css('font-size', newval + 'px');
        });
    });

    // Logo Font Weight
    wp.customize('logo_font_weight', function(value) {
        value.bind(function(newval) {
            $('.logo-text, .site-title a').css('font-weight', newval);
        });
    });

    // Logo Color
    wp.customize('logo_color', function(value) {
        value.bind(function(newval) {
            $('.logo-text, .site-title a').css('color', newval);
        });
    });

    // Logo Hover Color
    wp.customize('logo_hover_color', function(value) {
        value.bind(function(newval) {
            updateDynamicCSS('logo-hover', '.logo-text:hover, .site-title a:hover { color: ' + newval + ' !important; }');
        });
    });

    // Search Placeholder
    wp.customize('search_placeholder', function(value) {
        value.bind(function(newval) {
            $('.search-field').attr('placeholder', newval);
        });
    });

    // Search Bar Width
    wp.customize('search_bar_width', function(value) {
        value.bind(function(newval) {
            $('.search-container').css('width', newval + 'px');
        });
    });

    // Search Background Color
    wp.customize('search_bg_color', function(value) {
        value.bind(function(newval) {
            $('.search-field').css('background-color', newval);
        });
    });

    // Search Border Color
    wp.customize('search_border_color', function(value) {
        value.bind(function(newval) {
            $('.search-field').css('border-color', newval);
        });
    });

    // Search Text Color
    wp.customize('search_text_color', function(value) {
        value.bind(function(newval) {
            $('.search-field').css('color', newval);
        });
    });

    // Search Button Color
    wp.customize('search_button_color', function(value) {
        value.bind(function(newval) {
            $('.search-submit').css({
                'background-color': newval,
                'border-color': newval
            });
        });
    });

    // User Icons Color
    wp.customize('user_icons_color', function(value) {
        value.bind(function(newval) {
            $('.user-actions a').css('color', newval);
        });
    });

    // User Icons Hover Color
    wp.customize('user_icons_hover_color', function(value) {
        value.bind(function(newval) {
            updateDynamicCSS('user-icons-hover', '.user-actions a:hover { color: ' + newval + ' !important; }');
        });
    });

    // User Icons Size
    wp.customize('user_icons_size', function(value) {
        value.bind(function(newval) {
            $('.user-actions a i').css('font-size', newval + 'px');
        });
    });

    /**
     * Helper function to update dynamic CSS
     */
    function updateDynamicCSS(id, css) {
        var $style = $('#' + id + '-dynamic-css');
        if ($style.length) {
            $style.html(css);
        } else {
            $('head').append('<style id="' + id + '-dynamic-css">' + css + '</style>');
        }
    }

    /**
     * Update Google Fonts when font family changes
     */
    function updateGoogleFonts(fontFamily) {
        var fontUrl = '';
        
        switch(fontFamily) {
            case 'Tajawal':
                fontUrl = 'https://fonts.googleapis.com/css?family=Tajawal:300,400,500,600,700,800&subset=arabic&display=swap';
                break;
            case 'Cairo':
                fontUrl = 'https://fonts.googleapis.com/css?family=Cairo:300,400,500,600,700,800&subset=arabic&display=swap';
                break;
            case 'Inter':
                fontUrl = 'https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800&display=swap';
                break;
            case 'Roboto':
                fontUrl = 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap';
                break;
            case 'Open Sans':
                fontUrl = 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap';
                break;
            case 'Montserrat':
                fontUrl = 'https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&display=swap';
                break;
        }

        if (fontUrl) {
            var $existingFont = $('#google-font-' + fontFamily.replace(/\s+/g, '-').toLowerCase());
            if (!$existingFont.length) {
                $('head').append('<link id="google-font-' + fontFamily.replace(/\s+/g, '-').toLowerCase() + '" href="' + fontUrl + '" rel="stylesheet">');
            }
        }
    }

    // Update Google Fonts when logo font changes
    wp.customize('logo_font_family', function(value) {
        value.bind(function(newval) {
            updateGoogleFonts(newval);
        });
    });

    /**
     * Handle visibility toggles
     */
    wp.customize('show_account_icon', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.user-account').show();
            } else {
                $('.user-account').hide();
            }
        });
    });

    wp.customize('show_wishlist_icon', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.user-wishlist').show();
            } else {
                $('.user-wishlist').hide();
            }
        });
    });

    wp.customize('show_cart_icon', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.user-cart').show();
            } else {
                $('.user-cart').hide();
            }
        });
    });

    wp.customize('enable_search_bar', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.header-search').show();
            } else {
                $('.header-search').hide();
            }
        });
    });

    wp.customize('enable_language_switcher', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.language-switcher').show();
            } else {
                $('.language-switcher').hide();
            }
        });
    });

    wp.customize('enable_currency_switcher', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.currency-switcher').show();
            } else {
                $('.currency-switcher').hide();
            }
        });
    });

    /**
     * Handle layout changes
     */
    wp.customize('header_layout_type', function(value) {
        value.bind(function(newval) {
            $('.header-row').removeClass('layout-horizontal layout-vertical layout-centered');
            $('.header-row').addClass('layout-' + newval);
            
            // Apply layout-specific styles
            if (newval === 'vertical') {
                $('.header-row').css({
                    'flex-direction': 'column',
                    'height': 'auto',
                    'padding': '20px 0'
                });
            } else if (newval === 'centered') {
                $('.header-row').css({
                    'justify-content': 'center',
                    'text-align': 'center'
                });
            } else {
                $('.header-row').css({
                    'flex-direction': 'row',
                    'justify-content': 'space-between',
                    'text-align': 'left'
                });
            }
        });
    });

    /**
     * Initialize preview enhancements
     */
    $(document).ready(function() {
        // Add smooth transitions for all customizable elements
        var elements = [
            '.site-header',
            '.logo-text',
            '.site-title a',
            '.search-field',
            '.search-submit',
            '.user-actions a'
        ];
        
        $(elements.join(', ')).css('transition', 'all 0.3s ease');
        
        // Add preview mode indicator
        $('body').addClass('customizer-preview-mode');
        
        // Enhance dropdown interactions in preview
        $('.dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            $(this).closest('.dropdown').toggleClass('active');
        });
    });

})(jQuery);
