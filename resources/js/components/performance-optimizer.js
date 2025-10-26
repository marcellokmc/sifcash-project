/**
 * Performance Optimizer
 * Optimisations automatiques des performances frontend
 */

class PerformanceOptimizer {
    constructor() {
        this.intersectionObserver = null;
        this.resizeObserver = null;
        this.performanceMetrics = {};
        this.lazyElements = new Set();
        this.deferredElements = new Set();
        
        this.init();
    }

    init() {
        this.setupLazyLoading();
        this.setupImageOptimization();
        this.setupPerformanceMonitoring();
        this.setupResourceOptimization();
        this.setupEventOptimization();
        
        // Démarrer les optimisations après le chargement
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.optimizeAfterLoad());
        } else {
            this.optimizeAfterLoad();
        }
    }

    /**
     * Configuration du lazy loading
     */
    setupLazyLoading() {
        // Intersection Observer pour lazy loading
        if ('IntersectionObserver' in window) {
            this.intersectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadElement(entry.target);
                        this.intersectionObserver.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '50px 0px', // Charger 50px avant d'être visible
                threshold: 0.1
            });
        }

        // Observer les éléments avec data-lazy
        this.observeLazyElements();
    }

    /**
     * Observer les éléments lazy loading
     */
    observeLazyElements() {
        const lazyElements = document.querySelectorAll('[data-lazy]');
        
        lazyElements.forEach(element => {
            if (this.intersectionObserver) {
                this.intersectionObserver.observe(element);
                this.lazyElements.add(element);
            } else {
                // Fallback sans Intersection Observer
                this.loadElement(element);
            }
        });
    }

    /**
     * Charger un élément lazy
     */
    loadElement(element) {
        const type = element.dataset.lazyType || 'image';
        
        switch (type) {
            case 'image':
                this.loadImage(element);
                break;
            case 'iframe':
                this.loadIframe(element);
                break;
            case 'component':
                this.loadComponent(element);
                break;
            case 'script':
                this.loadScript(element);
                break;
            default:
                this.loadGeneric(element);
        }
        
        element.classList.add('lazy-loaded');
        element.removeAttribute('data-lazy');
        this.lazyElements.delete(element);
    }

    /**
     * Chargement d'images lazy
     */
    loadImage(img) {
        const src = img.dataset.lazy;
        const srcset = img.dataset.srcset;
        
        if (src) {
            // Précharger l'image
            const imageLoader = new Image();
            
            imageLoader.onload = () => {
                img.src = src;
                if (srcset) {
                    img.srcset = srcset;
                }
                img.classList.add('fade-in');
                
                // Supprimer le placeholder
                const placeholder = img.parentNode.querySelector('.lazy-placeholder');
                if (placeholder) {
                    placeholder.remove();
                }
            };
            
            imageLoader.onerror = () => {
                img.src = img.dataset.fallback || 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><rect width="100" height="100" fill="%23f8f9fa"/><text x="50" y="50" text-anchor="middle" fill="%236c757d">❌</text></svg>';
            };
            
            imageLoader.src = src;
        }
    }

    /**
     * Chargement d'iframes lazy
     */
    loadIframe(iframe) {
        const src = iframe.dataset.lazy;
        if (src) {
            iframe.src = src;
        }
    }

    /**
     * Chargement de composants lazy
     */
    async loadComponent(element) {
        const componentName = element.dataset.component;
        const componentData = element.dataset.data ? JSON.parse(element.dataset.data) : {};
        
        try {
            // Note: Dynamic imports with template literals are not supported in build tools
            // You need to define specific component imports here
            console.warn(`Lazy component loading for "${componentName}" is not implemented`);
            element.innerHTML = '<div class="alert alert-info">Composant en chargement...</div>';
        } catch (error) {
            console.error(`Erreur chargement composant ${componentName}:`, error);
            element.innerHTML = '<div class="alert alert-warning">Composant non disponible</div>';
        }
    }

    /**
     * Chargement de scripts lazy
     */
    loadScript(element) {
        const src = element.dataset.lazy;
        if (src) {
            const script = document.createElement('script');
            script.src = src;
            script.async = true;
            
            script.onload = () => {
                element.dataset.loaded = 'true';
                element.dispatchEvent(new CustomEvent('scriptLoaded', { detail: { src } }));
            };
            
            document.head.appendChild(script);
        }
    }

    /**
     * Chargement générique
     */
    loadGeneric(element) {
        const content = element.dataset.lazy;
        if (content) {
            element.innerHTML = content;
        }
    }

    /**
     * Optimisation des images
     */
    setupImageOptimization() {
        // WebP support detection
        this.supportsWebP().then(supportsWebP => {
            if (supportsWebP) {
                document.documentElement.classList.add('webp');
                
                // Remplacer les images par versions WebP
                document.querySelectorAll('img[data-webp]').forEach(img => {
                    if (!img.src.includes('.webp')) {
                        img.src = img.dataset.webp;
                    }
                });
            }
        });

        // Responsive images automatiques
        this.setupResponsiveImages();
    }

    /**
     * Détecter le support WebP
     */
    supportsWebP() {
        return new Promise((resolve) => {
            const webP = new Image();
            webP.onload = webP.onerror = () => {
                resolve(webP.height === 2);
            };
            webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4WAoAAAAQAAAAAAAAAAAAQUxQSAwAAAARBxAR/Q9ERP8DAABWUDggGAAAABQBAJ0BKgEAAQAAAP4AAA3AAP7mtQAAAA==';
        });
    }

    /**
     * Images responsives automatiques
     */
    setupResponsiveImages() {
        if ('ResizeObserver' in window) {
            this.resizeObserver = new ResizeObserver((entries) => {
                entries.forEach(entry => {
                    const img = entry.target;
                    this.optimizeImageSize(img, entry.contentRect.width);
                });
            });

            document.querySelectorAll('img[data-responsive]').forEach(img => {
                this.resizeObserver.observe(img);
            });
        }
    }

    /**
     * Optimiser la taille d'image
     */
    optimizeImageSize(img, containerWidth) {
        const baseUrl = img.dataset.responsive;
        const sizes = [400, 800, 1200, 1600, 2000];
        
        // Prendre en compte la densité de pixels
        const pixelRatio = window.devicePixelRatio || 1;
        const targetWidth = containerWidth * pixelRatio;
        
        // Trouver la taille optimale
        const optimalSize = sizes.find(size => size >= targetWidth) || sizes[sizes.length - 1];
        
        const optimizedUrl = baseUrl.replace('{size}', optimalSize);
        
        if (img.src !== optimizedUrl) {
            img.src = optimizedUrl;
        }
    }

    /**
     * Monitoring des performances
     */
    setupPerformanceMonitoring() {
        // Performance Observer
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                list.getEntries().forEach(entry => {
                    this.recordMetric(entry);
                });
            });
            
            observer.observe({ entryTypes: ['navigation', 'paint', 'largest-contentful-paint'] });
        }

        // Core Web Vitals
        this.measureWebVitals();
    }

    /**
     * Mesurer les Core Web Vitals
     */
    async measureWebVitals() {
        try {
            const { getCLS, getFID, getFCP, getLCP, getTTFB } = await import('web-vitals');
            
            getCLS(this.recordMetric.bind(this));
            getFID(this.recordMetric.bind(this));
            getFCP(this.recordMetric.bind(this));
            getLCP(this.recordMetric.bind(this));
            getTTFB(this.recordMetric.bind(this));
        } catch (error) {
            console.warn('Web Vitals non disponible:', error);
        }
    }

    /**
     * Enregistrer une métrique
     */
    recordMetric(metric) {
        const name = metric.name || metric.entryType;
        const value = metric.value || metric.duration || metric.startTime;
        
        this.performanceMetrics[name] = {
            value: value,
            timestamp: Date.now(),
            rating: this.getRating(name, value)
        };

        // Log des métriques critiques
        if (['LCP', 'FID', 'CLS'].includes(name)) {
            console.log(`${name}: ${value} (${this.performanceMetrics[name].rating})`);
        }

        // Alertes pour performances dégradées
        if (this.performanceMetrics[name].rating === 'poor') {
            this.handlePoorPerformance(name, value);
        }
    }

    /**
     * Évaluer la performance
     */
    getRating(metricName, value) {
        const thresholds = {
            'LCP': { good: 2500, poor: 4000 },
            'FID': { good: 100, poor: 300 },
            'CLS': { good: 0.1, poor: 0.25 },
            'FCP': { good: 1800, poor: 3000 },
            'TTFB': { good: 800, poor: 1800 }
        };

        const threshold = thresholds[metricName];
        if (!threshold) return 'unknown';

        if (value <= threshold.good) return 'good';
        if (value <= threshold.poor) return 'needs-improvement';
        return 'poor';
    }

    /**
     * Gérer les performances dégradées
     */
    handlePoorPerformance(metricName, value) {
        console.warn(`Performance dégradée - ${metricName}: ${value}`);
        
        // Actions d'optimisation automatiques
        switch (metricName) {
            case 'LCP':
                this.optimizeLCP();
                break;
            case 'FID':
                this.optimizeFID();
                break;
            case 'CLS':
                this.optimizeCLS();
                break;
        }
    }

    /**
     * Optimiser LCP (Largest Contentful Paint)
     */
    optimizeLCP() {
        // Précharger les images importantes
        const heroImages = document.querySelectorAll('.hero img, [data-priority="high"] img');
        heroImages.forEach(img => {
            if (!img.loading) {
                img.loading = 'eager';
            }
        });

        // Précharger les ressources critiques
        this.preloadCriticalResources();
    }

    /**
     * Optimiser FID (First Input Delay)
     */
    optimizeFID() {
        // Différer l'exécution de scripts non critiques
        this.deferNonCriticalScripts();
        
        // Optimiser les event listeners
        this.optimizeEventListeners();
    }

    /**
     * Optimiser CLS (Cumulative Layout Shift)
     */
    optimizeCLS() {
        // Ajouter des dimensions aux images sans taille
        document.querySelectorAll('img:not([width]):not([height])').forEach(img => {
            img.style.aspectRatio = '16/9'; // Ratio par défaut
        });

        // Réserver l'espace pour le contenu dynamique
        document.querySelectorAll('[data-dynamic-content]').forEach(element => {
            if (!element.style.minHeight) {
                element.style.minHeight = '100px';
            }
        });
    }

    /**
     * Optimisation des ressources
     */
    setupResourceOptimization() {
        // Préconnexion aux domaines externes
        this.preconnectExternalDomains();
        
        // Préchargement intelligent
        this.setupIntelligentPreloading();
        
        // Optimisation des polices
        this.optimizeFonts();
    }

    /**
     * Préconnexion aux domaines externes
     */
    preconnectExternalDomains() {
        const externalDomains = [
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'cdn.jsdelivr.net',
            'cdnjs.cloudflare.com'
        ];

        externalDomains.forEach(domain => {
            if (!document.querySelector(`link[rel="preconnect"][href*="${domain}"]`)) {
                const link = document.createElement('link');
                link.rel = 'preconnect';
                link.href = `https://${domain}`;
                link.crossOrigin = 'anonymous';
                document.head.appendChild(link);
            }
        });
    }

    /**
     * Préchargement intelligent
     */
    setupIntelligentPreloading() {
        // Précharger les liens probables
        const links = document.querySelectorAll('a[href^="/"]');
        const linkProbability = new Map();

        links.forEach(link => {
            link.addEventListener('mouseenter', () => {
                if (!linkProbability.has(link.href)) {
                    this.preloadPage(link.href);
                    linkProbability.set(link.href, true);
                }
            }, { once: true });
        });
    }

    /**
     * Précharger une page
     */
    preloadPage(href) {
        const link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = href;
        document.head.appendChild(link);
    }

    /**
     * Optimisation des polices
     */
    optimizeFonts() {
        // Font display swap pour éviter le FOIT
        const fontLinks = document.querySelectorAll('link[href*="fonts.googleapis.com"]');
        fontLinks.forEach(link => {
            if (!link.href.includes('display=swap')) {
                link.href += link.href.includes('?') ? '&display=swap' : '?display=swap';
            }
        });

        // Préchargement des polices critiques
        const criticalFonts = [
            '/fonts/inter-regular.woff2',
            '/fonts/inter-bold.woff2'
        ];

        criticalFonts.forEach(font => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.href = font;
            link.as = 'font';
            link.type = 'font/woff2';
            link.crossOrigin = 'anonymous';
            document.head.appendChild(link);
        });
    }

    /**
     * Optimisation des événements
     */
    setupEventOptimization() {
        // Debouncing automatique pour resize et scroll
        this.optimizeScrollEvents();
        this.optimizeResizeEvents();
    }

    /**
     * Optimiser les événements scroll
     */
    optimizeScrollEvents() {
        let ticking = false;
        
        const optimizedScrollHandler = (callback) => {
            return () => {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        callback();
                        ticking = false;
                    });
                    ticking = true;
                }
            };
        };

        // Remplacer les gestionnaires de scroll existants
        const scrollElements = document.querySelectorAll('[data-scroll-optimize]');
        scrollElements.forEach(element => {
            const handler = element.scrollHandler;
            if (handler) {
                element.removeEventListener('scroll', handler);
                element.addEventListener('scroll', optimizedScrollHandler(handler), { passive: true });
            }
        });
    }

    /**
     * Optimiser les événements resize
     */
    optimizeResizeEvents() {
        const debounce = (func, wait) => {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        };

        // Optimiser window resize
        const resizeHandlers = [];
        window.addEventListener('resize', debounce(() => {
            resizeHandlers.forEach(handler => handler());
        }, 250));
    }

    /**
     * Optimisations après chargement
     */
    optimizeAfterLoad() {
        // Nettoyer les ressources inutilisées
        this.cleanupUnusedResources();
        
        // Optimiser les animations
        this.optimizeAnimations();
        
        // Setup service worker updates
        this.setupSWOptimizations();
    }

    /**
     * Nettoyer les ressources inutilisées
     */
    cleanupUnusedResources() {
        // Supprimer les éléments cachés depuis longtemps
        document.querySelectorAll('[data-cleanup-after]').forEach(element => {
            const delay = parseInt(element.dataset.cleanupAfter) * 1000;
            setTimeout(() => {
                if (element.style.display === 'none' || element.hidden) {
                    element.remove();
                }
            }, delay);
        });
    }

    /**
     * Optimiser les animations
     */
    optimizeAnimations() {
        // Réduire les animations si préféré
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.style.setProperty('--animation-duration', '0.01ms');
        }

        // Utiliser will-change pour les animations critiques
        document.querySelectorAll('.animate, [data-animate]').forEach(element => {
            element.style.willChange = 'transform, opacity';
            
            // Nettoyer après animation
            element.addEventListener('animationend', () => {
                element.style.willChange = 'auto';
            }, { once: true });
        });
    }

    /**
     * Optimisations Service Worker
     */
    setupSWOptimizations() {
        if ('serviceWorker' in navigator && window.swManager) {
            // Précharger les pages importantes
            const criticalPages = ['/adherent/dashboard', '/adherent/cotisations'];
            window.swManager.cacheUrls(criticalPages);
        }
    }

    /**
     * Différer les scripts non critiques
     */
    deferNonCriticalScripts() {
        document.querySelectorAll('script[data-defer="true"]').forEach(script => {
            this.deferScript(script);
        });
    }

    /**
     * Différer un script
     */
    deferScript(originalScript) {
        const newScript = document.createElement('script');
        
        // Copier les attributs
        Array.from(originalScript.attributes).forEach(attr => {
            newScript.setAttribute(attr.name, attr.value);
        });
        
        newScript.textContent = originalScript.textContent;
        
        // Charger après un delay
        setTimeout(() => {
            originalScript.parentNode.replaceChild(newScript, originalScript);
        }, 100);
    }

    /**
     * Précharger les ressources critiques
     */
    preloadCriticalResources() {
        const criticalResources = [
            { href: '/build/assets/app.css', as: 'style' },
            { href: '/build/assets/app.js', as: 'script' }
        ];

        criticalResources.forEach(resource => {
            if (!document.querySelector(`link[href="${resource.href}"]`)) {
                const link = document.createElement('link');
                link.rel = 'preload';
                link.href = resource.href;
                link.as = resource.as;
                document.head.appendChild(link);
            }
        });
    }

    /**
     * Obtenir les métriques de performance
     */
    getMetrics() {
        return {
            ...this.performanceMetrics,
            lazyElementsCount: this.lazyElements.size,
            timestamp: Date.now()
        };
    }

    /**
     * Optimiser manuellement
     */
    optimize() {
        this.observeLazyElements();
        this.setupResponsiveImages();
        this.preconnectExternalDomains();
        this.cleanupUnusedResources();
        
        console.log('Optimisations manuelles appliquées');
    }
}

// Initialiser automatiquement
const performanceOptimizer = new PerformanceOptimizer();

// API globale
window.PerformanceOptimizer = {
    getMetrics: () => performanceOptimizer.getMetrics(),
    optimize: () => performanceOptimizer.optimize(),
    preloadPage: (url) => performanceOptimizer.preloadPage(url)
};

export default PerformanceOptimizer;