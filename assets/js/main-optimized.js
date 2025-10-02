/*
 * CONSOLIDATED MAIN JAVASCRIPT - SHEIN-Inspired Functionality
 * Al-Anika Theme v7.0.0 - Performance Optimized
 */

(function() {
    'use strict';

    // === GLOBAL VARIABLES ===
    let isLoading = false;
    let searchTimeout = null;
    const ANIMATION_DURATION = 300;

    // === UTILITY FUNCTIONS ===
    const $ = (selector, context = document) => context.querySelector(selector);
    const $$ = (selector, context = document) => context.querySelectorAll(selector);
    
    const debounce = (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    };

    const throttle = (func, limit) => {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    };

    // === LOADING MANAGEMENT ===
    class LoadingManager {
        constructor() {
            this.loadingScreen = $('#loading-screen');
        }

        show() {
            if (this.loadingScreen) {
                this.loadingScreen.style.display = 'flex';
            }
        }

        hide() {
            if (this.loadingScreen) {
                setTimeout(() => {
                    this.loadingScreen.style.opacity = '0';
                    setTimeout(() => {
                        this.loadingScreen.style.display = 'none';
                    }, 300);
                }, 500);
            }
        }
    }

    // === MOBILE NAVIGATION ===
    class MobileNavigation {
        constructor() {
            this.toggle = $('.mobile-menu-toggle');
            this.menu = $('.primary-nav');
            this.init();
        }

        init() {
            if (this.toggle && this.menu) {
                this.toggle.addEventListener('click', () => this.toggleMenu());
                
                // Close menu when clicking outside
                document.addEventListener('click', (e) => {
                    if (!this.toggle.contains(e.target) && !this.menu.contains(e.target)) {
                        this.closeMenu();
                    }
                });

                // Close menu on window resize
                window.addEventListener('resize', debounce(() => {
                    if (window.innerWidth > 768) {
                        this.closeMenu();
                    }
                }, 250));
            }
        }

        toggleMenu() {
            const isActive = this.menu.classList.contains('active');
            if (isActive) {
                this.closeMenu();
            } else {
                this.openMenu();
            }
        }

        openMenu() {
            this.menu.classList.add('active');
            this.toggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        closeMenu() {
            this.menu.classList.remove('active');
            this.toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }
    }

    // === SEARCH FUNCTIONALITY ===
    class SearchSystem {
        constructor() {
            this.searchInput = $('#search-input');
            this.searchForm = $('.search-form');
            this.suggestions = $('#search-suggestions');
            this.init();
        }

        init() {
            if (this.searchInput) {
                this.searchInput.addEventListener('input', debounce((e) => {
                    this.handleSearch(e.target.value);
                }, 300));

                this.searchInput.addEventListener('focus', () => {
                    this.showSuggestions();
                });

                document.addEventListener('click', (e) => {
                    if (!this.searchForm.contains(e.target)) {
                        this.hideSuggestions();
                    }
                });
            }
        }

        handleSearch(query) {
            if (query.length < 2) {
                this.hideSuggestions();
                return;
            }

            // Simulate search suggestions (in real implementation, this would be an AJAX call)
            this.showSearchSuggestions(query);
        }

        showSearchSuggestions(query) {
            if (!this.suggestions) return;

            // Mock suggestions for demo
            const mockSuggestions = [
                'Women Clothing',
                'Men Shoes',
                'Kids Accessories',
                'Home & Kitchen',
                'Beauty Products'
            ].filter(item => item.toLowerCase().includes(query.toLowerCase()));

            if (mockSuggestions.length > 0) {
                const suggestionsHTML = mockSuggestions
                    .map(item => `<div class="suggestion-item">${item}</div>`)
                    .join('');
                
                this.suggestions.innerHTML = `
                    <div class="suggestions-header">
                        <span class="suggestions-title">Search Suggestions</span>
                    </div>
                    <div class="suggestions-content">${suggestionsHTML}</div>
                `;
                this.showSuggestions();
            }
        }

        showSuggestions() {
            if (this.suggestions) {
                this.suggestions.style.display = 'block';
            }
        }

        hideSuggestions() {
            if (this.suggestions) {
                this.suggestions.style.display = 'none';
            }
        }
    }

    // === PRODUCT INTERACTIONS ===
    class ProductInteractions {
        constructor() {
            this.init();
        }

        init() {
            // Add to cart functionality
            document.addEventListener('click', (e) => {
                if (e.target.matches('.add-to-cart') || e.target.closest('.add-to-cart')) {
                    e.preventDefault();
                    this.handleAddToCart(e.target);
                }
            });

            // Wishlist functionality
            document.addEventListener('click', (e) => {
                if (e.target.matches('.add-to-wishlist') || e.target.closest('.add-to-wishlist')) {
                    e.preventDefault();
                    this.handleWishlist(e.target);
                }
            });

            // Quick view functionality
            document.addEventListener('click', (e) => {
                if (e.target.matches('.quick-view') || e.target.closest('.quick-view')) {
                    e.preventDefault();
                    this.handleQuickView(e.target);
                }
            });

            // Product compare functionality
            document.addEventListener('click', (e) => {
                if (e.target.matches('.add-to-compare') || e.target.closest('.add-to-compare')) {
                    e.preventDefault();
                    this.handleCompare(e.target);
                }
            });
        }

        handleAddToCart(button) {
            const productId = button.dataset.productId;
            if (!productId) return;

            button.classList.add('loading');
            button.disabled = true;

            // Simulate AJAX request
            setTimeout(() => {
                button.classList.remove('loading');
                button.disabled = false;
                this.showNotification('Product added to cart!', 'success');
                this.updateCartCount();
            }, 800);
        }

        handleWishlist(button) {
            const productId = button.dataset.productId;
            if (!productId) return;

            const isActive = button.classList.contains('active');
            
            if (isActive) {
                button.classList.remove('active');
                this.showNotification('Removed from wishlist', 'info');
            } else {
                button.classList.add('active');
                this.showNotification('Added to wishlist!', 'success');
            }
        }

        handleQuickView(button) {
            const productId = button.dataset.productId;
            if (!productId) return;

            // In a real implementation, this would open a modal with product details
            this.showNotification('Quick view feature coming soon!', 'info');
        }

        handleCompare(button) {
            const productId = button.dataset.productId;
            if (!productId) return;

            const isActive = button.classList.contains('active');
            
            if (isActive) {
                button.classList.remove('active');
                this.showNotification('Removed from comparison', 'info');
            } else {
                button.classList.add('active');
                this.showNotification('Added to comparison!', 'success');
            }
        }

        updateCartCount() {
            const cartCount = $('.cart-count');
            if (cartCount) {
                const currentCount = parseInt(cartCount.textContent) || 0;
                cartCount.textContent = currentCount + 1;
                cartCount.style.display = 'flex';
            }
        }

        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <span>${message}</span>
                <button class="notification-close">&times;</button>
            `;

            // Add styles
            Object.assign(notification.style, {
                position: 'fixed',
                top: '20px',
                right: '20px',
                background: type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3',
                color: 'white',
                padding: '12px 20px',
                borderRadius: '4px',
                zIndex: '10000',
                display: 'flex',
                alignItems: 'center',
                gap: '10px',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                transform: 'translateX(100%)',
                transition: 'transform 0.3s ease'
            });

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Close functionality
            const closeBtn = notification.querySelector('.notification-close');
            closeBtn.addEventListener('click', () => {
                this.hideNotification(notification);
            });

            // Auto close after 3 seconds
            setTimeout(() => {
                this.hideNotification(notification);
            }, 3000);
        }

        hideNotification(notification) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }
    }

    // === COUNTDOWN TIMER ===
    class CountdownTimer {
        constructor() {
            this.timers = $$('[data-countdown]');
            this.init();
        }

        init() {
            this.timers.forEach(timer => {
                const endTime = timer.dataset.countdown;
                if (endTime) {
                    this.startCountdown(timer, new Date(endTime));
                }
            });

            // Flash sale timer (demo)
            const flashTimer = $('#flash-sale-timer');
            if (flashTimer) {
                const endTime = new Date();
                endTime.setHours(endTime.getHours() + 12); // 12 hours from now
                this.startCountdown(flashTimer, endTime);
            }
        }

        startCountdown(element, endTime) {
            const updateTimer = () => {
                const now = new Date().getTime();
                const distance = endTime.getTime() - now;

                if (distance < 0) {
                    element.innerHTML = '<span class="timer-expired">Expired</span>';
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const daysEl = element.querySelector('[data-days]');
                const hoursEl = element.querySelector('[data-hours]');
                const minutesEl = element.querySelector('[data-minutes]');
                const secondsEl = element.querySelector('[data-seconds]');

                if (daysEl) daysEl.textContent = days.toString().padStart(2, '0');
                if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
                if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
                if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
            };

            updateTimer();
            setInterval(updateTimer, 1000);
        }
    }

    // === SMOOTH SCROLLING ===
    class SmoothScroll {
        constructor() {
            this.init();
        }

        init() {
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a[href^="#"]');
                if (link) {
                    e.preventDefault();
                    const targetId = link.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        }
    }

    // === INTERSECTION OBSERVER FOR ANIMATIONS ===
    class AnimationObserver {
        constructor() {
            this.init();
        }

        init() {
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('fade-in');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                // Observe elements that should animate
                $$('.category-card, .product-card, .hero-section, .section').forEach(el => {
                    observer.observe(el);
                });
            }
        }
    }

    // === BACK TO TOP BUTTON ===
    class BackToTop {
        constructor() {
            this.button = this.createButton();
            this.init();
        }

        createButton() {
            const button = document.createElement('button');
            button.innerHTML = '<i class="fas fa-arrow-up"></i>';
            button.className = 'back-to-top';
            button.setAttribute('aria-label', 'Back to top');
            
            Object.assign(button.style, {
                position: 'fixed',
                bottom: '20px',
                right: '20px',
                width: '50px',
                height: '50px',
                borderRadius: '50%',
                background: '#000',
                color: '#fff',
                border: 'none',
                cursor: 'pointer',
                display: 'none',
                alignItems: 'center',
                justifyContent: 'center',
                zIndex: '1000',
                transition: 'all 0.3s ease',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)'
            });

            document.body.appendChild(button);
            return button;
        }

        init() {
            const handleScroll = throttle(() => {
                if (window.pageYOffset > 300) {
                    this.button.style.display = 'flex';
                } else {
                    this.button.style.display = 'none';
                }
            }, 100);

            window.addEventListener('scroll', handleScroll);

            this.button.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    }

    // === LAZY LOADING ===
    class LazyLoader {
        constructor() {
            this.images = $$('img[data-src]');
            this.init();
        }

        init() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    });
                });

                this.images.forEach(img => imageObserver.observe(img));
            } else {
                // Fallback for browsers without IntersectionObserver
                this.images.forEach(img => {
                    img.src = img.dataset.src;
                });
            }
        }
    }

    // === THEME INITIALIZATION ===
    class AlAnikaTheme {
        constructor() {
            this.loadingManager = new LoadingManager();
            this.init();
        }

        init() {
            // Initialize all components when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.initComponents());
            } else {
                this.initComponents();
            }
        }

        initComponents() {
            // Initialize all functionality
            new MobileNavigation();
            new SearchSystem();
            new ProductInteractions();
            new CountdownTimer();
            new SmoothScroll();
            new AnimationObserver();
            new BackToTop();
            new LazyLoader();

            // Hide loading screen
            this.loadingManager.hide();

            // Performance monitoring
            this.monitorPerformance();
        }

        monitorPerformance() {
            // Monitor page load performance
            window.addEventListener('load', () => {
                if ('performance' in window) {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    console.log('Page load time:', Math.round(perfData.loadEventEnd - perfData.fetchStart) + 'ms');
                }
            });
        }
    }

    // === INITIALIZE THEME ===
    new AlAnikaTheme();

})();
