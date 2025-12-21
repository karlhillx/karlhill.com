import Alpine from 'alpinejs'
import intersect from '@alpinejs/intersect'
import darkMode from './darkMode'
import './github-skills.js'

// Import Font Awesome styles
import '@fortawesome/fontawesome-free/css/fontawesome.css';
import '@fortawesome/fontawesome-free/css/solid.css';
import '@fortawesome/fontawesome-free/css/regular.css';
import '@fortawesome/fontawesome-free/css/brands.css';

// Register Alpine plugins
Alpine.plugin(intersect)

// Make Alpine available globally
window.Alpine = Alpine

// Modern utility functions
const utils = {
    /**
     * Initialize intersection observer for reveal animations
     */
    initRevealAnimations() {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (prefersReduced || !('IntersectionObserver' in window)) {
            // Show content immediately if reduced motion is preferred
            document.querySelectorAll('.reveal-section').forEach(el => {
                el.classList.add('in-view');
            });
            return;
        }

        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -10% 0px',
            threshold: 0.15
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal-section').forEach(el => {
            observer.observe(el);
        });
    },

    /**
     * Initialize smooth page transitions
     */
    initPageTransitions() {
        const enablePageFade = (e) => {
            const link = e.currentTarget;
            const url = link.getAttribute('href');
            
            // Skip if it's an anchor, external link, or modifier key is pressed
            if (!url || 
                url.startsWith('#') || 
                link.target === '_blank' || 
                e.metaKey || 
                e.ctrlKey || 
                e.shiftKey || 
                e.altKey) {
                return;
            }

            e.preventDefault();
            document.body.classList.add('leaving');
            
            // Use requestAnimationFrame for smoother transition
            requestAnimationFrame(() => {
                setTimeout(() => {
                    window.location.href = url;
                }, 120);
            });
        };

        // Remove leaving class on page load
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.remove('leaving');
        });

        // Add click handlers to all links
        document.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', enablePageFade);
        });
    },

    /**
     * Initialize theme system
     */
    initTheme() {
        // Set initial theme based on preference
        const getThemePreference = () => {
            if (localStorage.theme === 'dark' || 
                (!('theme' in localStorage) && 
                 window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                return 'dark';
            }
            return 'light';
        };

        const applyTheme = (theme) => {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            localStorage.theme = theme;
        };

        // Apply initial theme
        applyTheme(getThemePreference());

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!('theme' in localStorage)) {
                applyTheme(e.matches ? 'dark' : 'light');
            }
        });

        // Create global theme toggle function
        window.toggleTheme = function() {
            const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
        };

        // Handle dark mode toggle button if it exists
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', window.toggleTheme);
        }
    },

    /**
     * Initialize smooth scrolling for anchor links
     */
    initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '#!') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    },

    /**
     * Initialize lazy loading for images
     */
    initLazyLoading() {
        if ('loading' in HTMLImageElement.prototype) {
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                img.src = img.dataset.src || img.src;
            });
        } else {
            // Fallback for browsers that don't support native lazy loading
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src || img.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img.lazy').forEach(img => {
                imageObserver.observe(img);
            });
        }
    },

    /**
     * Initialize page loader
     */
    initPageLoader() {
        const loader = document.getElementById('page-loader');
        if (!loader) return;

        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const minDisplayTime = prefersReduced ? 300 : 800; // Shorter for reduced motion
        const startTime = performance.now();

        // Function to hide loader
        const hideLoader = () => {
            const elapsed = performance.now() - startTime;
            const remainingTime = Math.max(0, minDisplayTime - elapsed);

            setTimeout(() => {
                loader.classList.add('loaded');
                
                // Remove from DOM after animation completes
                setTimeout(() => {
                    loader.remove();
                    // Enable body scroll after loader is removed
                    document.body.style.overflow = '';
                }, 600); // Match CSS transition duration
            }, remainingTime);
        };

        // Prevent body scroll while loader is visible
        document.body.style.overflow = 'hidden';

        // Check if page is already loaded
        if (document.readyState === 'complete') {
            // Small delay to ensure loader is visible
            setTimeout(() => {
                hideLoader();
            }, 100);
            return;
        }

        // Wait for page to fully load
        const handleLoad = () => {
            // Ensure fonts are loaded
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(() => {
                    hideLoader();
                }).catch(() => {
                    // If fonts fail to load, still hide loader
                    hideLoader();
                });
            } else {
                hideLoader();
            }
        };

        // Listen for load event
        if (document.readyState === 'loading') {
            window.addEventListener('load', handleLoad, { once: true });
        } else {
            // DOM is already loaded, wait a bit then hide
            setTimeout(handleLoad, 100);
        }

        // Fallback: hide after maximum wait time (prevents stuck loader)
        setTimeout(() => {
            hideLoader();
        }, 5000);
    }
};

// Initialize Alpine.js and utilities when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Register Alpine data
    Alpine.data('darkMode', darkMode);
    
    // Start Alpine
    Alpine.start();

    // Initialize utility functions
    utils.initTheme();
    utils.initPageLoader(); // Initialize loader after DOM is ready
    utils.initRevealAnimations();
    utils.initPageTransitions();
    utils.initSmoothScroll();
    utils.initLazyLoading();
});

// Handle page visibility changes for performance
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        // Page became visible - could refresh data here if needed
        console.debug('Page visible');
    } else {
        // Page hidden - pause animations or heavy operations
        console.debug('Page hidden');
    }
});

// Export utils for potential external use
window.siteUtils = utils;
