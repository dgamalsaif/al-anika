/**
 * Professional Homepage JavaScript
 * Front page specific functionality for Alam Al Anika Theme
 */

(function($) {
    'use strict';

    const Homepage = {
        
        init: function() {
            this.initCountdown();
            this.initProductTabs();
            this.initHeroSlider();
            this.initTestimonials();
            this.initNewsletterForm();
            this.initProductActions();
            this.initScrollAnimations();
            this.initParallax();
        },

        // Flash Sale Countdown Timer
        initCountdown: function() {
            const $timer = $('#flash-sale-timer');
            if (!$timer.length) return;

            // Set countdown end time (24 hours from now)
            const endTime = new Date().getTime() + (24 * 60 * 60 * 1000);

            const updateTimer = () => {
                const now = new Date().getTime();
                const timeLeft = endTime - now;

                if (timeLeft <= 0) {
                    $timer.html('<div class="timer-expired">انتهى العرض!</div>');
                    return;
                }

                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                $timer.find('[data-days]').text(days.toString().padStart(2, '0'));
                $timer.find('[data-hours]').text(hours.toString().padStart(2, '0'));
                $timer.find('[data-minutes]').text(minutes.toString().padStart(2, '0'));
                $timer.find('[data-seconds]').text(seconds.toString().padStart(2, '0'));
            };

            updateTimer();
            setInterval(updateTimer, 1000);
        },

        // Product Tabs Functionality
        initProductTabs: function() {
            const $tabButtons = $('.tab-button');
            const $tabPanes = $('.tab-pane');

            $tabButtons.on('click', function(e) {
                e.preventDefault();
                
                const targetTab = $(this).data('tab');
                
                // Remove active class from all buttons and panes
                $tabButtons.removeClass('active');
                $tabPanes.removeClass('active');
                
                // Add active class to clicked button and corresponding pane
                $(this).addClass('active');
                $(`#${targetTab}`).addClass('active');
                
                // Trigger animation for new content
                $(`#${targetTab} .product-card`).each(function(index) {
                    $(this).css('animation-delay', (index * 100) + 'ms').addClass('animate-in');
                });
            });
        },

        // Hero Slider (if implemented)
        initHeroSlider: function() {
            const $slider = $('.hero-slider');
            if (!$slider.length) return;

            // Simple slider implementation
            let currentSlide = 0;
            const $slides = $slider.find('.hero-slide');
            const slideCount = $slides.length;

            if (slideCount <= 1) return;

            const showSlide = (index) => {
                $slides.removeClass('active');
                $slides.eq(index).addClass('active');
                
                // Update indicators
                $('.hero-indicators .indicator').removeClass('active');
                $('.hero-indicators .indicator').eq(index).addClass('active');
            };

            const nextSlide = () => {
                currentSlide = (currentSlide + 1) % slideCount;
                showSlide(currentSlide);
            };

            const prevSlide = () => {
                currentSlide = (currentSlide - 1 + slideCount) % slideCount;
                showSlide(currentSlide);
            };

            // Auto-play
            setInterval(nextSlide, 5000);

            // Navigation buttons
            $('.hero-nav .next').on('click', nextSlide);
            $('.hero-nav .prev').on('click', prevSlide);

            // Indicators
            $('.hero-indicators .indicator').on('click', function() {
                currentSlide = $(this).index();
                showSlide(currentSlide);
            });

            // Keyboard navigation
            $(document).on('keydown', function(e) {
                if ($slider.is(':visible')) {
                    if (e.keyCode === 37) prevSlide(); // Left arrow
                    if (e.keyCode === 39) nextSlide(); // Right arrow
                }
            });
        },

        // Testimonials Slider
        initTestimonials: function() {
            const $testimonials = $('.testimonials-slider');
            if (!$testimonials.length) return;

            let currentTestimonial = 0;
            const $items = $testimonials.find('.testimonial-item');
            const itemCount = $items.length;

            if (itemCount <= 1) return;

            const showTestimonial = (index) => {
                $items.removeClass('active');
                $items.eq(index).addClass('active');
            };

            const nextTestimonial = () => {
                currentTestimonial = (currentTestimonial + 1) % itemCount;
                showTestimonial(currentTestimonial);
            };

            // Auto-play testimonials
            setInterval(nextTestimonial, 6000);

            // Touch/swipe support
            let startX = 0;
            let endX = 0;

            $testimonials.on('touchstart', function(e) {
                startX = e.originalEvent.touches[0].clientX;
            });

            $testimonials.on('touchend', function(e) {
                endX = e.originalEvent.changedTouches[0].clientX;
                const diff = startX - endX;
                
                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        nextTestimonial();
                    } else {
                        currentTestimonial = (currentTestimonial - 1 + itemCount) % itemCount;
                        showTestimonial(currentTestimonial);
                    }
                }
            });
        },

        // Newsletter Form Enhancement
        initNewsletterForm: function() {
            $('.newsletter-form, .subscription-form').on('submit', function(e) {
                e.preventDefault();
                
                const $form = $(this);
                const $email = $form.find('input[type="email"]');
                const $button = $form.find('button[type="submit"]');
                const originalText = $button.html();
                const email = $email.val();

                // Validation
                if (!Homepage.isValidEmail(email)) {
                    Homepage.showMessage('البريد الإلكتروني غير صحيح', 'error');
                    $email.addClass('error').focus();
                    return;
                }

                // Loading state
                $button.html('<i class="fas fa-spinner fa-spin"></i> جار التسجيل...').prop('disabled', true);
                $email.removeClass('error');

                // Simulate API call
                setTimeout(() => {
                    // Success
                    Homepage.showMessage('تم الاشتراك بنجاح! ستصلك أحدث العروض قريباً.', 'success');
                    $form[0].reset();
                    $button.html(originalText).prop('disabled', false);
                    
                    // Add success animation
                    $form.addClass('success-animation');
                    setTimeout(() => $form.removeClass('success-animation'), 2000);
                }, 2000);
            });
        },

        // Product Actions
        initProductActions: function() {
            // Quick View
            $('.quick-view-btn').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = $(this).data('product-id');
                Homepage.openQuickView(productId);
            });

            // Add to Wishlist
            $('.wishlist-btn').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const $btn = $(this);
                const productId = $btn.data('product-id');
                
                $btn.toggleClass('active');
                
                if ($btn.hasClass('active')) {
                    $btn.find('i').removeClass('far').addClass('fas');
                    Homepage.showMessage('تمت إضافة المنتج لقائمة الأمنيات', 'success');
                } else {
                    $btn.find('i').removeClass('fas').addClass('far');
                    Homepage.showMessage('تم إزالة المنتج من قائمة الأمنيات', 'info');
                }
            });

            // Add to Cart
            $('.add-to-cart-btn, .add-to-cart-main').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const $btn = $(this);
                const productId = $btn.data('product-id');
                const originalText = $btn.html();
                
                // Loading state
                $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
                
                // Simulate add to cart
                setTimeout(() => {
                    $btn.html('<i class="fas fa-check"></i> تمت الإضافة').addClass('success');
                    Homepage.showMessage('تمت إضافة المنتج للسلة بنجاح', 'success');
                    
                    // Update cart count (this would come from actual AJAX response)
                    Homepage.updateCartCount();
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        $btn.html(originalText).prop('disabled', false).removeClass('success');
                    }, 2000);
                }, 1500);
            });

            // Compare
            $('.compare-btn').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const $btn = $(this);
                $btn.toggleClass('active');
                
                if ($btn.hasClass('active')) {
                    Homepage.showMessage('تمت إضافة المنتج للمقارنة', 'success');
                } else {
                    Homepage.showMessage('تم إزالة المنتج من المقارنة', 'info');
                }
            });
        },

        // Scroll Animations
        initScrollAnimations: function() {
            if ('IntersectionObserver' in window) {
                const animationObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-in');
                            
                            // Stagger animations for child elements
                            const children = entry.target.querySelectorAll('.product-card, .feature-card, .category-card');
                            children.forEach((child, index) => {
                                setTimeout(() => {
                                    child.classList.add('animate-in');
                                }, index * 100);
                            });
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                $('.section, .hero-section, .flash-sale-section').each(function() {
                    animationObserver.observe(this);
                });
            }
        },

        // Parallax Effects
        initParallax: function() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return; // Skip parallax for users who prefer reduced motion
            }

            $(window).on('scroll', Homepage.throttle(function() {
                const scrolled = $(this).scrollTop();
                const rate = scrolled * -0.5;
                
                $('.parallax-bg').css('transform', `translateY(${rate}px)`);
                
                // Floating elements parallax
                $('.floating-card').each(function(index) {
                    const speed = (index + 1) * 0.3;
                    const yPos = -(scrolled * speed);
                    $(this).css('transform', `translateY(${yPos}px)`);
                });
            }, 16));
        },

        // Quick View Modal
        openQuickView: function(productId) {
            // Create modal if it doesn't exist
            if (!$('#quick-view-modal').length) {
                const modal = `
                    <div id="quick-view-modal" class="modal">
                        <div class="modal-overlay"></div>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>عرض سريع</h3>
                                <button class="modal-close" aria-label="إغلاق">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="loading">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <span>جار التحميل...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('body').append(modal);
                
                // Close modal events
                $('#quick-view-modal').on('click', '.modal-close, .modal-overlay', function() {
                    Homepage.closeQuickView();
                });
                
                $(document).on('keydown', function(e) {
                    if (e.keyCode === 27 && $('#quick-view-modal').hasClass('active')) {
                        Homepage.closeQuickView();
                    }
                });
            }
            
            // Show modal
            $('#quick-view-modal').addClass('active');
            $('body').addClass('modal-open');
            
            // Simulate loading product data
            setTimeout(() => {
                const productHTML = `
                    <div class="quick-view-product">
                        <div class="product-image">
                            <img src="https://via.placeholder.com/400x400" alt="منتج مميز">
                        </div>
                        <div class="product-details">
                            <h4>منتج مميز ${productId}</h4>
                            <div class="product-price">
                                <span class="current-price">299 ر.س</span>
                                <span class="original-price">399 ر.س</span>
                            </div>
                            <div class="product-description">
                                <p>وصف مختصر للمنتج يوضح المزايا والخصائص الرئيسية.</p>
                            </div>
                            <div class="product-actions">
                                <button class="btn btn-primary add-to-cart">
                                    <i class="fas fa-shopping-cart"></i>
                                    إضافة للسلة
                                </button>
                                <button class="btn btn-outline wishlist">
                                    <i class="far fa-heart"></i>
                                    المفضلة
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#quick-view-modal .modal-body').html(productHTML);
            }, 1000);
        },

        closeQuickView: function() {
            $('#quick-view-modal').removeClass('active');
            $('body').removeClass('modal-open');
        },

        // Update Cart Count
        updateCartCount: function() {
            const currentCount = parseInt($('.cart-count').text()) || 0;
            $('.cart-count').text(currentCount + 1).addClass('updated');
            
            setTimeout(() => {
                $('.cart-count').removeClass('updated');
            }, 1000);
        },

        // Utility Functions
        isValidEmail: function(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },

        showMessage: function(message, type = 'info') {
            const toast = $(`
                <div class="toast toast-${type}">
                    <div class="toast-content">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'info-circle'}"></i>
                        <span>${message}</span>
                    </div>
                    <button class="toast-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `);
            
            $('body').append(toast);
            
            setTimeout(() => toast.addClass('show'), 100);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                toast.removeClass('show');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
            
            // Manual close
            toast.find('.toast-close').on('click', function() {
                toast.removeClass('show');
                setTimeout(() => toast.remove(), 300);
            });
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
        }
    };

    // Initialize homepage functionality
    $(document).ready(function() {
        if ($('body').hasClass('home') || $('body').hasClass('front-page')) {
            Homepage.init();
        }
    });

    // Make Homepage available globally
    window.Homepage = Homepage;

})(jQuery);

// Additional CSS for homepage functionality
const homepageCSS = `
<style>
/* Toast Notifications */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    padding: var(--space-4);
    z-index: 10000;
    transform: translateX(100%);
    transition: var(--transition-all);
    max-width: 350px;
    border-left: 4px solid var(--info-500);
}

.toast.show {
    transform: translateX(0);
}

.toast-success {
    border-left-color: var(--success-500);
}

.toast-error {
    border-left-color: var(--danger-500);
}

.toast-content {
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

.toast-close {
    position: absolute;
    top: var(--space-2);
    right: var(--space-2);
    background: none;
    border: none;
    color: var(--secondary-400);
    cursor: pointer;
}

/* Quick View Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition-all);
}

.modal.active {
    opacity: 1;
    visibility: visible;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: var(--radius-xl);
    max-width: 800px;
    width: 90%;
    max-height: 90%;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-6);
    border-bottom: 1px solid var(--secondary-200);
}

.modal-close {
    background: none;
    border: none;
    font-size: var(--text-xl);
    color: var(--secondary-500);
    cursor: pointer;
}

.modal-body {
    padding: var(--space-6);
}

.quick-view-product {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-8);
}

.product-image img {
    width: 100%;
    border-radius: var(--radius-lg);
}

.product-details h4 {
    font-size: var(--text-2xl);
    margin-bottom: var(--space-4);
}

.product-price {
    margin-bottom: var(--space-4);
}

.current-price {
    font-size: var(--text-2xl);
    font-weight: var(--font-bold);
    color: var(--danger-600);
}

.original-price {
    text-decoration: line-through;
    color: var(--secondary-400);
    margin-right: var(--space-2);
}

.product-actions {
    display: flex;
    gap: var(--space-4);
    margin-top: var(--space-6);
}

/* Animation Classes */
.animate-in {
    opacity: 1;
    transform: translateY(0);
}

.product-card,
.feature-card,
.category-card {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

/* Success Animation */
.success-animation {
    position: relative;
    overflow: hidden;
}

.success-animation::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(34, 197, 94, 0.3), transparent);
    animation: successSweep 1s ease-out;
}

@keyframes successSweep {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Cart Count Update */
.cart-count.updated {
    animation: cartBounce 0.6s ease;
}

@keyframes cartBounce {
    0%, 20%, 50%, 80%, 100% { transform: scale(1); }
    40% { transform: scale(1.2); }
    60% { transform: scale(1.1); }
}

/* Timer Styles */
.timer-expired {
    background: var(--danger-500);
    color: white;
    padding: var(--space-4);
    border-radius: var(--radius-lg);
    text-align: center;
    font-weight: var(--font-bold);
}

/* Mobile Responsive */
@media (max-width: 767px) {
    .toast {
        top: 10px;
        right: 10px;
        left: 10px;
        max-width: none;
    }
    
    .modal-content {
        width: 95%;
    }
    
    .quick-view-product {
        grid-template-columns: 1fr;
    }
    
    .product-actions {
        flex-direction: column;
    }
}
</style>
`;

// Inject homepage CSS
document.head.insertAdjacentHTML('beforeend', homepageCSS);
