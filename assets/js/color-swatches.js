/**
 * Color and Image Swatches JavaScript
 * Advanced variation swatches functionality
 */

(function($) {
    'use strict';

    const AlamSwatches = {
        init: function() {
            this.bindEvents();
            this.initSwatches();
        },

        bindEvents: function() {
            $(document).on('click', '.alam-swatch-item', this.handleSwatchClick);
            $(document).on('change', '.variations select', this.handleSelectChange);
            $(document).on('reset_data', '.variations', this.resetSwatches);
        },

        initSwatches: function() {
            $('.alam-swatches-container').each(function() {
                const $container = $(this);
                const attribute = $container.data('attribute');
                const $select = $(`select[name="attribute_${attribute}"]`);
                
                if ($select.length) {
                    $select.hide();
                    
                    // Set initial active state
                    const selectedValue = $select.val();
                    if (selectedValue) {
                        $container.find(`[data-value="${selectedValue}"]`).addClass('active');
                    }
                }
            });
        },

        handleSwatchClick: function(e) {
            e.preventDefault();
            
            const $swatch = $(this);
            const $container = $swatch.closest('.alam-swatches-container');
            const attribute = $container.data('attribute');
            const value = $swatch.data('value');
            
            // Update visual state
            $container.find('.alam-swatch-item').removeClass('active');
            $swatch.addClass('active');
            
            // Update hidden select
            const $select = $(`select[name="attribute_${attribute}"]`);
            $select.val(value).trigger('change');
            
            // Update hidden input
            $container.find('input[type="hidden"]').val(value);
            
            // Trigger variation update
            AlamSwatches.updateVariation($container.closest('.variations'));
        },

        handleSelectChange: function() {
            const $select = $(this);
            const attribute = $select.attr('name').replace('attribute_', '');
            const value = $select.val();
            const $container = $(`.alam-swatches-container[data-attribute="${attribute}"]`);
            
            if ($container.length) {
                $container.find('.alam-swatch-item').removeClass('active');
                $container.find(`[data-value="${value}"]`).addClass('active');
            }
        },

        resetSwatches: function() {
            $('.alam-swatches-container').each(function() {
                const $container = $(this);
                $container.find('.alam-swatch-item').removeClass('active disabled');
                $container.find('.alam-swatch-none').addClass('active');
                $container.find('input[type="hidden"]').val('');
            });
        },

        updateVariation: function($variationsForm) {
            const $form = $variationsForm.closest('form.variations_form');
            
            if ($form.length) {
                $form.find('.single_variation_wrap').show();
                $form.trigger('check_variations');
                
                // Get all selected attributes
                const attributes = {};
                $form.find('.variations select').each(function() {
                    const $select = $(this);
                    const attributeName = $select.attr('name');
                    const value = $select.val();
                    
                    if (value) {
                        attributes[attributeName] = value;
                    }
                });
                
                // Update product information via AJAX
                AlamSwatches.loadVariationData($form, attributes);
            }
        },

        loadVariationData: function($form, attributes) {
            const productId = $form.find('input[name="product_id"]').val();
            
            if (!productId) return;
            
            $.ajax({
                url: alamSwatches.ajax_url,
                type: 'POST',
                data: {
                    action: 'alam_get_variation_data',
                    product_id: productId,
                    attributes: attributes,
                    nonce: alamSwatches.nonce
                },
                success: function(response) {
                    if (response.success) {
                        AlamSwatches.updateProductInfo(response.data);
                    }
                },
                error: function() {
                    console.log('خطأ في تحميل بيانات المتغير');
                }
            });
        },

        updateProductInfo: function(data) {
            // Update price
            if (data.price_html) {
                $('.price').html(data.price_html);
            }
            
            // Update availability
            if (data.availability_html) {
                $('.stock').html(data.availability_html);
            }
            
            // Update main product image
            if (data.image && data.image[0]) {
                const $mainImage = $('.alam-gallery-item.active img');
                if ($mainImage.length) {
                    $mainImage.attr('src', data.image[0]);
                    $mainImage.attr('srcset', data.image[0]);
                }
            }
            
            // Update variation description
            if (data.variation_description) {
                const $description = $('.variation-description');
                if ($description.length) {
                    $description.html(data.variation_description);
                } else {
                    $('.product-summary').append(`<div class="variation-description">${data.variation_description}</div>`);
                }
            }
        },

        // Utility function to check if variation is available
        checkVariationAvailability: function($form, attributes) {
            const availableVariations = $form.data('product_variations');
            
            if (!availableVariations) return;
            
            // Reset all swatches
            $('.alam-swatch-item').removeClass('disabled');
            
            // Check each swatch against available variations
            $('.alam-swatches-container').each(function() {
                const $container = $(this);
                const attribute = $container.data('attribute');
                
                $container.find('.alam-swatch-item:not(.alam-swatch-none)').each(function() {
                    const $swatch = $(this);
                    const value = $swatch.data('value');
                    
                    // Create test attributes
                    const testAttributes = Object.assign({}, attributes);
                    testAttributes[`attribute_${attribute}`] = value;
                    
                    // Check if this combination is available
                    let isAvailable = false;
                    availableVariations.forEach(function(variation) {
                        let matches = true;
                        for (const attr in testAttributes) {
                            if (variation.attributes[attr] && variation.attributes[attr] !== testAttributes[attr] && variation.attributes[attr] !== '') {
                                matches = false;
                                break;
                            }
                        }
                        if (matches && variation.is_in_stock) {
                            isAvailable = true;
                        }
                    });
                    
                    if (!isAvailable) {
                        $swatch.addClass('disabled');
                    }
                });
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AlamSwatches.init();
    });

    // Re-initialize on AJAX events
    $(document).on('wc_variation_form', function() {
        AlamSwatches.init();
    });

})(jQuery);