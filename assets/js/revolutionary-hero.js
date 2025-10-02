/**
 * Revolutionary Hero Section JavaScript
 * Phase 2 Enhancement - Advanced Animations and Interactions
 * 
 * @package AlamAlAnika
 */

(function($) {
    'use strict';

    class RevolutionaryHero {
        constructor() {
            this.currentSlide = 0;
            this.slideInterval = null;
            this.autoplayDelay = 8000;
            this.animationSpeed = 'medium';
            this.particlesEnabled = true;
            
            this.init();
        }

        init() {
            this.cacheElements();
            this.setAnimationSpeed();
            this.initParticles();
            this.initSlideshow();
            this.initCountdownTimer();
            this.initScrollAnimations();
            this.bindEvents();
            this.startAnimationSequence();
        }

        cacheElements() {
            this.$hero = $('.revolutionary-hero');
            this.$container = $('.revolutionary-hero-container');
            this.$columns = $('.hero-column');
            this.$navBtns = $('.hero-nav-btn');
            this.$indicators = $('.indicator');
            this.$particlesCanvas = $('#hero-particles-canvas');
            this.$timer = $('.timer-display');
        }

        setAnimationSpeed() {
            const speed = this.$hero.data('animation-speed') || 'medium';
            this.animationSpeed = speed;
            
            const speeds = {
                'slow': 1.5,
                'medium': 1,
                'fast': 0.7
            };
            
            const multiplier = speeds[speed] || 1;
            this.autoplayDelay = 8000 * multiplier;
        }

        // ===== PARTICLES SYSTEM =====
        initParticles() {
            if (!this.particlesEnabled || !this.$particlesCanvas.length) return;

            const canvas = this.$particlesCanvas[0];
            const ctx = canvas.getContext('2d');
            
            // Set canvas size
            const resizeCanvas = () => {
                canvas.width = this.$hero.width();
                canvas.height = this.$hero.height();
            };
            
            resizeCanvas();
            $(window).on('resize', resizeCanvas);

            // Particle system
            const particles = [];
            const particleCount = 50;
            
            class Particle {
                constructor() {
                    this.reset();
                    this.y = Math.random() * canvas.height;
                }
                
                reset() {
                    this.x = Math.random() * canvas.width;
                    this.y = -10;
                    this.size = Math.random() * 3 + 1;
                    this.speedX = (Math.random() - 0.5) * 0.5;
                    this.speedY = Math.random() * 1 + 0.5;
                    this.opacity = Math.random() * 0.5 + 0.2;
                }
                
                update() {
                    this.x += this.speedX;
                    this.y += this.speedY;
                    
                    if (this.y > canvas.height + 10) {
                        this.reset();
                    }
                    
                    if (this.x < 0 || this.x > canvas.width) {
                        this.speedX *= -1;
                    }
                }
                
                draw() {
                    ctx.save();
                    ctx.globalAlpha = this.opacity;
                    ctx.fillStyle = '#ffffff';
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.restore();
                }
            }
            
            // Create particles
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
            
            // Animation loop
            const animate = () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                particles.forEach(particle => {
                    particle.update();
                    particle.draw();
                });
                
                requestAnimationFrame(animate);
            };
            
            animate();
        }

        // ===== SLIDESHOW FUNCTIONALITY =====
        initSlideshow() {
            if (this.$indicators.length <= 1) return;
            
            this.startAutoplay();
        }

        startAutoplay() {
            this.slideInterval = setInterval(() => {
                this.nextSlide();
            }, this.autoplayDelay);
        }

        stopAutoplay() {
            if (this.slideInterval) {
                clearInterval(this.slideInterval);
                this.slideInterval = null;
            }
        }

        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.$indicators.length;
            this.goToSlide(this.currentSlide);
        }

        prevSlide() {
            this.currentSlide = this.currentSlide === 0 ? this.$indicators.length - 1 : this.currentSlide - 1;
            this.goToSlide(this.currentSlide);
        }

        goToSlide(index) {
            this.currentSlide = index;
            
            // Update indicators
            this.$indicators.removeClass('active');
            this.$indicators.eq(index).addClass('active');
            
            // Add slide transition effect
            this.$container.addClass('transitioning');
            
            setTimeout(() => {
                this.$container.removeClass('transitioning');
                this.restartAnimationSequence();
            }, 600);
        }

        // ===== COUNTDOWN TIMER =====
        initCountdownTimer() {
            const $timer = this.$timer;
            if (!$timer.length) return;

            const countdownDate = $timer.data('countdown');
            if (!countdownDate) return;

            const targetDate = new Date(countdownDate + 'T23:59:59').getTime();

            const updateTimer = () => {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance < 0) {
                    $timer.html('<div class="timer-expired">انتهى العرض</div>');
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                $timer.find('.days').text(String(days).padStart(2, '0'));
                $timer.find('.hours').text(String(hours).padStart(2, '0'));
                $timer.find('.minutes').text(String(minutes).padStart(2, '0'));
                
                // Add pulse effect to timer when time is running low
                if (distance < 24 * 60 * 60 * 1000) { // Less than 24 hours
                    $timer.addClass('timer-urgent');
                }
            };

            // Update immediately and then every second
            updateTimer();
            setInterval(updateTimer, 1000);
        }

        // ===== SCROLL ANIMATIONS =====
        initScrollAnimations() {
            // Intersection Observer for scroll animations
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.startAnimationSequence();
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.3
                });

                observer.observe(this.$hero[0]);
            } else {
                // Fallback for browsers without IntersectionObserver
                this.startAnimationSequence();
            }
        }

        // ===== ANIMATION SEQUENCES =====
        startAnimationSequence() {
            // Reset all animations
            this.resetAnimations();
            
            // Start animations with delays
            setTimeout(() => this.animateLeftColumn(), 200);
            setTimeout(() => this.animateCenterColumn(), 400);
            setTimeout(() => this.animateRightColumn(), 600);
        }

        restartAnimationSequence() {
            this.resetAnimations();
            setTimeout(() => this.startAnimationSequence(), 100);
        }

        resetAnimations() {
            const elements = [
                '.animate-slide-up',
                '.animate-fade-in',
                '.animate-scale-in',
                '.animate-bounce-in',
                '.animate-pulse-glow'
            ];

            elements.forEach(selector => {
                $(selector).removeClass('animated');
                $(selector).css({
                    'opacity': '0',
                    'transform': ''
                });
            });
        }

        animateLeftColumn() {
            const $leftColumn = this.$columns.filter('.hero-left');
            
            this.animateElement($leftColumn.find('.hero-subtitle'), 0);
            this.animateElement($leftColumn.find('.hero-title'), 200);
            this.animateElement($leftColumn.find('.hero-description'), 400);
            this.animateElement($leftColumn.find('.hero-button-container'), 600);
        }

        animateCenterColumn() {
            const $centerColumn = this.$columns.filter('.hero-center');
            
            this.animateElement($centerColumn.find('.hero-center-title'), 0);
            this.animateElement($centerColumn.find('.hero-badge'), 200);
            this.animateElement($centerColumn.find('.featured-product-showcase, .featured-content-showcase'), 400);
        }

        animateRightColumn() {
            const $rightColumn = this.$columns.filter('.hero-right');
            
            this.animateElement($rightColumn.find('.promo-offer'), 0);
            this.animateElement($rightColumn.find('.promo-title'), 200);
            this.animateElement($rightColumn.find('.promo-subtitle'), 400);
            this.animateElement($rightColumn.find('.promo-button-container'), 600);
            this.animateElement($rightColumn.find('.promo-timer'), 800);
        }

        animateElement($element, delay) {
            if (!$element.length) return;

            setTimeout(() => {
                $element.addClass('animated');
                $element.css({
                    'opacity': '1',
                    'transform': 'translateY(0) scale(1)'
                });
            }, delay);
        }

        // ===== EVENT HANDLERS =====
        bindEvents() {
            // Navigation buttons
            this.$navBtns.on('click', (e) => {
                e.preventDefault();
                
                if ($(e.currentTarget).hasClass('hero-nav-prev')) {
                    this.prevSlide();
                } else {
                    this.nextSlide();
                }
                
                this.stopAutoplay();
                setTimeout(() => this.startAutoplay(), 5000);
            });

            // Indicators
            this.$indicators.on('click', (e) => {
                e.preventDefault();
                const index = $(e.currentTarget).index();
                this.goToSlide(index);
                
                this.stopAutoplay();
                setTimeout(() => this.startAutoplay(), 5000);
            });

            // Column hover effects
            this.$columns.on('mouseenter', function() {
                $(this).addClass('column-hovered');
            }).on('mouseleave', function() {
                $(this).removeClass('column-hovered');
            });

            // Pause autoplay on hover
            this.$hero.on('mouseenter', () => {
                this.stopAutoplay();
            }).on('mouseleave', () => {
                this.startAutoplay();
            });

            // Keyboard navigation
            $(document).on('keydown', (e) => {
                if (!this.$hero.is(':visible')) return;
                
                switch(e.keyCode) {
                    case 37: // Left arrow
                        e.preventDefault();
                        this.prevSlide();
                        break;
                    case 39: // Right arrow
                        e.preventDefault();
                        this.nextSlide();
                        break;
                    case 32: // Space bar
                        e.preventDefault();
                        if (this.slideInterval) {
                            this.stopAutoplay();
                        } else {
                            this.startAutoplay();
                        }
                        break;
                }
            });

            // Window resize handler
            $(window).on('resize', this.debounce(() => {
                this.handleResize();
            }, 250));

            // Visibility change handler (pause when tab is not visible)
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.stopAutoplay();
                } else {
                    this.startAutoplay();
                }
            });
        }

        handleResize() {
            // Recalculate positions and restart animations if needed
            this.restartAnimationSequence();
        }

        // ===== UTILITY FUNCTIONS =====
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // ===== PUBLIC API =====
        destroy() {
            this.stopAutoplay();
            $(window).off('resize.revolutionaryHero');
            $(document).off('keydown.revolutionaryHero');
            this.$hero.off('.revolutionaryHero');
        }

        pause() {
            this.stopAutoplay();
        }

        play() {
            this.startAutoplay();
        }

        goTo(index) {
            if (index >= 0 && index < this.$indicators.length) {
                this.goToSlide(index);
            }
        }
    }

    // ===== HERO ENHANCEMENT EFFECTS =====
    class HeroEnhancements {
        constructor() {
            this.init();
        }

        init() {
            this.initParallaxEffect();
            this.initMagneticButtons();
            this.initGlowEffects();
            this.initRippleEffect();
        }

        initParallaxEffect() {
            $(window).on('scroll', this.debounce(() => {
                const scrollY = window.scrollY;
                const heroHeight = $('.revolutionary-hero').height();
                
                if (scrollY < heroHeight) {
                    const parallaxFactor = scrollY * 0.5;
                    $('.revolutionary-hero-container').css('transform', `translateY(${parallaxFactor}px)`);
                    $('.hero-particles-container').css('transform', `translateY(${parallaxFactor * 0.3}px)`);
                }
            }, 16));
        }

        initMagneticButtons() {
            $('.hero-btn').on('mousemove', function(e) {
                const $btn = $(this);
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                $btn.css('transform', `translate(${x * 0.1}px, ${y * 0.1}px)`);
            }).on('mouseleave', function() {
                $(this).css('transform', '');
            });
        }

        initGlowEffects() {
            $('.hero-column').on('mouseenter', function() {
                const $this = $(this);
                $this.css('box-shadow', '0 0 50px rgba(255, 107, 107, 0.4)');
            }).on('mouseleave', function() {
                $(this).css('box-shadow', '');
            });
        }

        initRippleEffect() {
            $('.hero-btn, .indicator').on('click', function(e) {
                const $btn = $(this);
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const $ripple = $('<span class="ripple"></span>');
                $ripple.css({
                    position: 'absolute',
                    left: x + 'px',
                    top: y + 'px',
                    transform: 'translate(-50%, -50%)',
                    width: '0',
                    height: '0',
                    borderRadius: '50%',
                    background: 'rgba(255, 255, 255, 0.6)',
                    animation: 'ripple 0.6s ease-out'
                });
                
                $btn.css('position', 'relative').append($ripple);
                
                setTimeout(() => $ripple.remove(), 600);
            });
        }

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    }

    // ===== INITIALIZATION =====
    $(document).ready(function() {
        // Initialize only if revolutionary hero exists
        if ($('.revolutionary-hero').length) {
            window.revolutionaryHero = new RevolutionaryHero();
            window.heroEnhancements = new HeroEnhancements();
        }
    });

    // Add CSS for ripple animation
    $('<style>')
        .text(`
            @keyframes ripple {
                to {
                    width: 100px;
                    height: 100px;
                    opacity: 0;
                }
            }
            
            .timer-urgent {
                animation: pulse 1s ease-in-out infinite;
            }
            
            .timer-expired {
                color: #ff6b6b;
                font-weight: bold;
                font-size: 18px;
                padding: 20px;
                text-align: center;
            }
            
            .column-hovered {
                z-index: 10;
            }
            
            .transitioning {
                opacity: 0.7;
                transition: opacity 0.6s ease;
            }
        `)
        .appendTo('head');

})(jQuery);
