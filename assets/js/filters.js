/**
 * Single Product Page Functionality
 *
 * Handles image thumbnails, quantity selectors, tabs, and modals.
 */
(function($) {
    "use strict";

    $(document).ready(function() {
        // Product Detail Page Tabs
        $('.product-details-tabs .tab-btn').on('click', function(e) {
            e.preventDefault();
            const tabId = $(this).data('tab');

            $('.tab-btn').removeClass('active');
            $(this).addClass('active');

            $('.tab-pane').removeClass('active');
            $('#' + tabId).addClass('active');
        });

        // Product Image Thumbnail Selector
        // Note: WooCommerce's default gallery might handle this. This is a fallback.
        $(document).on('click', '.thumbnail-images .thumbnail', function() {
            const mainImage = $('#main-product-image');
            const newSrc = $(this).attr('src');
            mainImage.attr('src', newSrc);

            $('.thumbnail').removeClass('active');
            $(this).addClass('active');
        });

        // Quantity Selector Buttons
        $(document).on('click', '.quantity-btn.increase', function() {
            const input = $(this).siblings('.quantity-input, .input-text.qty');
            let currentValue = parseInt(input.val());
            input.val(currentValue + 1).trigger('change');
        });

        $(document).on('click', '.quantity-btn.decrease', function() {
            const input = $(this).siblings('.quantity-input, .input-text.qty');
            let currentValue = parseInt(input.val());
            if (currentValue > 1) {
                input.val(currentValue - 1).trigger('change');
            }
        });

        // Size Guide Modal
        $('#size-guide-link').on('click', function(e) {
            e.preventDefault();
            $('#size-guide-modal').fadeIn();
        });

        $('.close-size-guide').on('click', function() {
            $('#size-guide-modal').fadeOut();
        });

        $(document).on('click', function(e) {
            if ($(e.target).is('#size-guide-modal')) {
                $('#size-guide-modal').fadeOut();
            }
        });
    });

})(jQuery);