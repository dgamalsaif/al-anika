/**
 * Al-Anika Theme - Animation JavaScript
 * 
 * @package Al_Anika_Theme
 * @version 9.0.0 Final
 * @author MiniMax Agent
 */

(function($) {
    'use strict';

    // Animation Controller
    window.AlAnikaAnimations = {
        
        init: function() {
            this.scrollAnimations();
            this.intersectionObserver();
            this.parallaxElements();
            this.countUpNumbers();
            this.typeWriter();
            this.progressBars();
            this.morphingShapes();
            this.particleSystem();
        },

        // Scroll-based animations
        scrollAnimations: function() {
            const animatedElements = document.querySelectorAll('.animate-on-scroll');
            
            if (!animatedElements.length) return;

            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const animationType = element.dataset.animation || 'fade-in-up';
                        const delay = element.dataset.delay || 0;
                        
                        setTimeout(function() {
                            element.classList.add('animated', animationType);
                        }, delay);
                        
                        observer.unobserve(element);
                    }
                });
            }, observerOptions);

            animatedElements.forEach(function(element) {
                observer.observe(element);
            });
        },

        // Enhanced intersection observer for complex animations
        intersectionObserver: function() {
            if (!('IntersectionObserver' in window)) return;

            const elements = document.querySelectorAll('[data-animate]');
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const animation = element.dataset.animate;
                        const duration = element.dataset.duration || '0.8s';
                        const delay = element.dataset.delay || '0s';
                        const easing = element.dataset.easing || 'ease-out';
                        
                        element.style.animationDuration = duration;
                        element.style.animationDelay = delay;
                        element.style.animationTimingFunction = easing;
                        element.classList.add(animation);
                        
                        observer.unobserve(element);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            });

            elements.forEach(function(element) {
                observer.observe(element);
            });
        },

        // Parallax scrolling effect
        parallaxElements: function() {
            const parallaxElements = document.querySelectorAll('.parallax-element');
            
            if (!parallaxElements.length) return;

            const updateParallax = AlAnikaTheme.utils.throttle(function() {
                const scrolled = window.pageYOffset;
                
                parallaxElements.forEach(function(element) {
                    const rate = scrolled * (element.dataset.speed || 0.5);
                    const direction = element.dataset.direction || 'vertical';
                    
                    if (direction === 'horizontal') {
                        element.style.transform = `translateX(${rate}px)`;
                    } else {
                        element.style.transform = `translateY(${rate}px)`;
                    }
                });
            }, 16);

            window.addEventListener('scroll', updateParallax);
        },

        // Count up numbers animation
        countUpNumbers: function() {
            const counters = document.querySelectorAll('.count-up');
            
            if (!counters.length) return;

            const animateCounter = function(counter) {
                const target = parseInt(counter.dataset.target || counter.textContent);
                const duration = parseInt(counter.dataset.duration || 2000);
                const step = target / (duration / 16);
                let current = 0;
                
                const timer = setInterval(function() {
                    current += step;
                    counter.textContent = Math.floor(current);
                    
                    if (current >= target) {
                        counter.textContent = target;
                        clearInterval(timer);
                    }
                }, 16);
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            });

            counters.forEach(function(counter) {
                observer.observe(counter);
            });
        },

        // Typewriter effect
        typeWriter: function() {
            const typewriterElements = document.querySelectorAll('.typewriter');
            
            typewriterElements.forEach(function(element) {
                const text = element.dataset.text || element.textContent;
                const speed = parseInt(element.dataset.speed || 100);
                const delay = parseInt(element.dataset.delay || 0);
                
                element.textContent = '';
                
                setTimeout(function() {
                    let i = 0;
                    const timer = setInterval(function() {
                        element.textContent += text.charAt(i);
                        i++;
                        
                        if (i > text.length) {
                            clearInterval(timer);
                        }
                    }, speed);
                }, delay);
            });
        },

        // Animated progress bars
        progressBars: function() {
            const progressBars = document.querySelectorAll('.progress-bar');
            
            const animateProgress = function(progressBar) {
                const fill = progressBar.querySelector('.progress-bar-fill');
                const percentage = parseInt(progressBar.dataset.percentage || 0);
                const duration = parseInt(progressBar.dataset.duration || 1500);
                
                let current = 0;
                const increment = percentage / (duration / 16);
                
                const timer = setInterval(function() {
                    current += increment;
                    fill.style.width = Math.min(current, percentage) + '%';
                    
                    if (current >= percentage) {
                        clearInterval(timer);
                    }
                }, 16);
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        animateProgress(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            });

            progressBars.forEach(function(bar) {
                observer.observe(bar);
            });
        },

        // Morphing shapes animation
        morphingShapes: function() {
            const shapes = document.querySelectorAll('.morphing-shape');
            
            shapes.forEach(function(shape) {
                const paths = shape.dataset.paths ? shape.dataset.paths.split('|') : [];
                if (paths.length < 2) return;
                
                let currentPath = 0;
                const duration = parseInt(shape.dataset.duration || 3000);
                
                setInterval(function() {
                    currentPath = (currentPath + 1) % paths.length;
                    const pathElement = shape.querySelector('path');
                    if (pathElement) {
                        pathElement.style.transition = `d ${duration}ms ease-in-out`;
                        pathElement.setAttribute('d', paths[currentPath]);
                    }
                }, duration);
            });
        },

        // Simple particle system
        particleSystem: function() {
            const particleContainers = document.querySelectorAll('.particle-system');
            
            particleContainers.forEach(function(container) {
                const particleCount = parseInt(container.dataset.count || 20);
                const particleColor = container.dataset.color || '#e74c3c';
                const particleSize = parseInt(container.dataset.size || 4);
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.cssText = `
                        position: absolute;
                        width: ${particleSize}px;
                        height: ${particleSize}px;
                        background: ${particleColor};
                        border-radius: 50%;
                        pointer-events: none;
                        left: ${Math.random() * 100}%;
                        top: ${Math.random() * 100}%;
                        animation: particleFloat ${3 + Math.random() * 4}s ease-in-out infinite;
                        animation-delay: ${Math.random() * 2}s;
                    `;
                    container.appendChild(particle);
                }
            });
        },

        // Stagger animation for elements
        staggerElements: function(elements, animationClass, delay = 100) {
            elements.forEach(function(element, index) {
                setTimeout(function() {
                    element.classList.add(animationClass);
                }, index * delay);
            });
        },

        // Fade in elements sequentially
        fadeInSequence: function(selector, delay = 200) {
            const elements = document.querySelectorAll(selector);
            this.staggerElements(elements, 'fade-in-up', delay);
        },

        // Scale in elements
        scaleInElements: function(selector) {
            const elements = document.querySelectorAll(selector);
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.style.transform = 'scale(1)';
                        entry.target.style.opacity = '1';
                        observer.unobserve(entry.target);
                    }
                });
            });

            elements.forEach(function(element) {
                element.style.transform = 'scale(0.8)';
                element.style.opacity = '0';
                element.style.transition = 'all 0.6s ease-out';
                observer.observe(element);
            });
        },

        // Slide in from sides
        slideInElements: function(selector, direction = 'left') {
            const elements = document.querySelectorAll(selector);
            
            elements.forEach(function(element) {
                const distance = direction === 'left' ? '-100px' : '100px';
                element.style.transform = `translateX(${distance})`;
                element.style.opacity = '0';
                element.style.transition = 'all 0.8s ease-out';
                
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.style.transform = 'translateX(0)';
                            entry.target.style.opacity = '1';
                            observer.unobserve(entry.target);
                        }
                    });
                });
                
                observer.observe(element);
            });
        },

        // Pulse animation for attention
        pulseElement: function(element, duration = 1000) {
            element.style.animation = `pulse ${duration}ms ease-in-out infinite`;
            
            setTimeout(function() {
                element.style.animation = '';
            }, duration * 3);
        },

        // Shake animation for errors
        shakeElement: function(element) {
            element.classList.add('shake');
            setTimeout(function() {
                element.classList.remove('shake');
            }, 820);
        },

        // Bounce attention grabber
        bounceElement: function(element, duration = 2000) {
            element.style.animation = `bounce ${duration}ms ease-in-out infinite`;
            
            setTimeout(function() {
                element.style.animation = '';
            }, duration * 2);
        },

        // Glow effect
        glowElement: function(element, color = '#e74c3c') {
            element.style.boxShadow = `0 0 20px ${color}`;
            element.style.transition = 'box-shadow 0.3s ease';
            
            setTimeout(function() {
                element.style.boxShadow = '';
            }, 2000);
        },

        // Loading animation
        showLoading: function(element) {
            const loader = document.createElement('div');
            loader.className = 'loading-spinner';
            loader.innerHTML = '<div class="spinner"></div>';
            
            element.appendChild(loader);
            element.classList.add('loading');
        },

        hideLoading: function(element) {
            const loader = element.querySelector('.loading-spinner');
            if (loader) {
                loader.remove();
            }
            element.classList.remove('loading');
        }
    };

    // Page transition effects
    window.AlAnikaPageTransitions = {
        init: function() {
            this.setupTransitions();
            this.handlePageLoad();
        },

        setupTransitions: function() {
            // Add page transition class to body
            document.body.classList.add('page-transition');
            
            // Handle internal links
            const internalLinks = document.querySelectorAll('a[href^="' + window.location.origin + '"]');
            
            internalLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    if (link.target === '_blank') return;
                    
                    e.preventDefault();
                    const href = link.href;
                    
                    document.body.classList.add('page-leaving');
                    
                    setTimeout(function() {
                        window.location.href = href;
                    }, 300);
                });
            });
        },

        handlePageLoad: function() {
            window.addEventListener('load', function() {
                document.body.classList.add('loaded');
                
                // Trigger any initial animations
                setTimeout(function() {
                    $(document).trigger('al_anika_page_loaded');
                }, 100);
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AlAnikaAnimations.init();
        AlAnikaPageTransitions.init();
        
        // Initialize specific animations for common elements
        AlAnikaAnimations.fadeInSequence('.card', 150);
        AlAnikaAnimations.slideInElements('.widget', 'left');
        AlAnikaAnimations.scaleInElements('.btn');
    });

    // Expose to global scope
    window.AlAnikaAnimations = AlAnikaAnimations;
    window.AlAnikaPageTransitions = AlAnikaPageTransitions;

})(jQuery);

// CSS for particle animation
const particleCSS = `
    @keyframes particleFloat {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
            opacity: 1;
        }
        33% {
            transform: translateY(-30px) rotate(120deg);
            opacity: 0.8;
        }
        66% {
            transform: translateY(-20px) rotate(240deg);
            opacity: 0.6;
        }
    }
    
    .particle-system {
        position: relative;
        overflow: hidden;
    }
    
    .page-transition {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    
    .page-leaving {
        opacity: 0;
        transform: translateY(-20px);
    }
    
    .page-transition.loaded {
        opacity: 1;
        transform: translateY(0);
    }
`;

// Inject particle CSS
const style = document.createElement('style');
style.textContent = particleCSS;
document.head.appendChild(style);