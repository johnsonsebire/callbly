// Frontend-specific JavaScript for public pages
import './bootstrap';
import '../css/frontend.css';

// Load original template scripts first
const loadTemplateScripts = () => {
    return new Promise((resolve) => {
        // Load the original plugins bundle if not already loaded
        if (!window.KTUtil) {
            const script = document.createElement('script');
            script.src = '/assets/plugins/global/plugins.bundle.js';
            script.onload = resolve;
            script.onerror = resolve; // Continue even if fails
            document.head.appendChild(script);
        } else {
            resolve();
        }
    });
};

// Lazy load AOS animations only when needed
let aosLoaded = false;

function loadAOS() {
    if (!aosLoaded) {
        import('aos').then((AOS) => {
            AOS.default.init({
                duration: 800,
                once: true,
                offset: 100
            });
            aosLoaded = true;
        }).catch(error => {
            console.warn('Failed to load AOS:', error);
        });
    }
}

// Initialize frontend features
document.addEventListener('DOMContentLoaded', function() {
    // Load template scripts first, then initialize custom features
    loadTemplateScripts().then(() => {
        // Initialize any template-specific functionality if available
        if (window.KTUtil) {
            // Template's utility functions are now available
        }
        
        // Logo switching based on scroll
        const headerLogo = document.querySelector('.app-header-logo');
        const landingHeader = document.querySelector('.landing-header');
        
        if (headerLogo && landingHeader) {
            let ticking = false;
            
            function updateLogo() {
                if (window.scrollY > 50 || landingHeader.classList.contains('landing-header-sticky')) {
                    headerLogo.classList.add('scrolled');
                } else {
                    headerLogo.classList.remove('scrolled');
                }
                ticking = false;
            }
            
            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateLogo);
                    ticking = true;
                }
            }
            
            window.addEventListener('scroll', requestTick, { passive: true });
            
            // Initial check
            updateLogo();
        }
        
        // Load AOS when scroll starts
        let scrollTimer = null;
        window.addEventListener('scroll', function() {
            if (scrollTimer !== null) {
                clearTimeout(scrollTimer);        
            }
            scrollTimer = setTimeout(loadAOS, 150);
        }, { passive: true, once: true });
        
        // Preload critical images
        const criticalImages = [
            '/assets/media/logos/callbly-white.png',
            '/assets/media/logos/callbly logo.png'
        ];
        
        criticalImages.forEach(src => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = src;
            document.head.appendChild(link);
        });
        
        // Lazy load testimonial carousel
        const testimonialCarousel = document.getElementById('testimonialCarousel');
        if (testimonialCarousel) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Initialize Bootstrap carousel when visible
                        import('bootstrap').then((bootstrap) => {
                            new bootstrap.Carousel(testimonialCarousel, {
                                interval: 5000,
                                wrap: true
                            });
                        }).catch(error => {
                            console.warn('Failed to load Bootstrap carousel:', error);
                        });
                        observer.unobserve(entry.target);
                    }
                });
            });
            observer.observe(testimonialCarousel);
        }

        // Lazy load typed.js for text animation if element exists
        const typedElement = document.querySelector('[data-typed]');
        if (typedElement) {
            import('typed.js').then((Typed) => {
                const strings = JSON.parse(typedElement.dataset.typed || '["Welcome to Callbly"]');
                new Typed.default(typedElement, {
                    strings: strings,
                    typeSpeed: 50,
                    backSpeed: 30,
                    loop: true
                });
            }).catch(error => {
                console.warn('Failed to load Typed.js:', error);
            });
        }
    });
});

// Performance optimization: Debounce resize events
let resizeTimer;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
        // Handle responsive adjustments
        const mobileBreakpoint = 768;
        if (window.innerWidth <= mobileBreakpoint) {
            document.body.classList.add('mobile-view');
        } else {
            document.body.classList.remove('mobile-view');
        }
    }, 250);
}, { passive: true });