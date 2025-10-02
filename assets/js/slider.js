/**
 * Hero Slider Functionality
 *
 * Controls the fading slides and navigation dots for the homepage hero section.
 */
(function($) {
    "use strict";

    $(document).ready(function() {
        let currentSlide = 0;
        const slides = $('.hero-slide');
        const dots = $('.hero-dot');
        const totalSlides = slides.length;

        if (totalSlides <= 1) {
            return; // Don't run the slider if there's only one slide or none.
        }

        function showSlide(index) {
            slides.removeClass('active');
            dots.removeClass('active');
            slides.eq(index).addClass('active');
            dots.eq(index).addClass('active');
        }

        dots.on('click', function() {
            currentSlide = $(this).data('slide');
            showSlide(currentSlide);
        });

        // Set an interval to auto-advance the slides.
        let slideInterval = setInterval(function() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }, 5000); // Change slide every 5 seconds.
    });

})(jQuery);