describe('Progressive Web App - SIF Burkina', () => {
  beforeEach(() => {
    cy.login();
  });

  describe('Configuration PWA', () => {
    it('devrait avoir tous les éléments PWA requis', () => {
      cy.visit('/');
      cy.checkPWA();
      
      // Vérifier le manifest
      cy.get('link[rel="manifest"]')
        .should('exist')
        .and('have.attr', 'href', '/manifest.json');
      
      // Vérifier les meta tags
      cy.get('meta[name="theme-color"]').should('have.attr', 'content', '#667eea');
      cy.get('meta[name="mobile-web-app-capable"]').should('exist');
      cy.get('meta[name="apple-mobile-web-app-capable"]').should('exist');
      
      // Vérifier les icônes
      cy.get('link[rel="apple-touch-icon"]').should('exist');
      cy.get('link[rel="icon"]').should('exist');
    });

    it('devrait charger le manifest correctement', () => {
      cy.request('/manifest.json').then((response) => {
        expect(response.status).to.eq(200);
        expect(response.body).to.have.property('name', 'SIF Burkina - Système d\'Information Financière');
        expect(response.body).to.have.property('short_name', 'SIF Burkina');
        expect(response.body).to.have.property('display', 'standalone');
        expect(response.body).to.have.property('theme_color', '#667eea');
        expect(response.body.icons).to.have.length.greaterThan(0);
      });
    });
  });

  describe('Service Worker', () => {
    it('devrait enregistrer le service worker', () => {
      cy.visit('/');
      cy.waitForServiceWorker();
      
      cy.window().then((win) => {
        expect(win.navigator.serviceWorker.controller).to.exist;
      });
    });

    it('devrait mettre en cache les ressources critiques', () => {
      cy.visit('/');
      cy.waitForServiceWorker();
      
      // Vérifier que les ressources sont en cache
      cy.window().then(async (win) => {
        if ('caches' in win) {
          const cacheNames = await win.caches.keys();
          expect(cacheNames).to.have.length.greaterThan(0);
          
          const cache = await win.caches.open(cacheNames[0]);
          const cachedRequests = await cache.keys();
          expect(cachedRequests).to.have.length.greaterThan(0);
        }
      });
    });
  });

  describe('Fonctionnalités Offline', () => {
    it('devrait fonctionner hors ligne après la première visite', () => {
      cy.visit('/adherent/dashboard');
      cy.waitForPageLoad();
      
      // Passer hors ligne
      cy.goOffline();
      
      // Recharger la page
      cy.reload();
      
      // Vérifier que la page se charge (depuis le cache)
      cy.get('[data-cy="dashboard-title"]').should('be.visible');
      cy.get('#connection-status').should('contain', 'Hors ligne');
      
      // Remettre en ligne
      cy.goOnline();
      cy.get('#connection-status').should('contain', 'En ligne');
    });

    it('devrait afficher la page offline pour les ressources non mises en cache', () => {
      cy.visit('/adherent/dashboard');
      cy.goOffline();
      
      // Essayer de naviguer vers une page non mise en cache
      cy.visit('/adherent/new-feature', { failOnStatusCode: false });
      
      // Devrait afficher la page offline
      cy.get('body').should('contain', 'Mode hors ligne');
      cy.get('.offline-card').should('be.visible');
    });

    it('devrait synchroniser les données en reprenant la connexion', () => {
      cy.visit('/adherent/cotisations');
      cy.goOffline();
      
      // Simuler une action hors ligne (si implémentée)
      cy.get('[data-cy="add-cotisation-offline"]').click();
      cy.fillForm({
        montant: '5000',
        type: 'Mensuelle'
      });
      cy.get('[data-cy="save-offline"]').click();
      
      cy.waitForToast('Données sauvegardées hors ligne');
      
      // Repasser en ligne
      cy.goOnline();
      
      // Vérifier la synchronisation
      cy.waitForToast('Données synchronisées', 'success');
    });
  });

  describe('Installation PWA', () => {
    it('devrait proposer l\'installation sur les navigateurs supportés', () => {
      cy.visit('/');
      
      // Simuler l'événement beforeinstallprompt
      cy.window().then((win) => {
        const installEvent = new Event('beforeinstallprompt');
        installEvent.preventDefault = cy.stub();
        installEvent.prompt = cy.stub().resolves({ outcome: 'accepted' });
        
        win.dispatchEvent(installEvent);
      });
      
      // Vérifier que le banner d'installation s'affiche
      cy.get('#pwa-install-banner').should('be.visible');
      cy.get('[data-pwa-install]').should('be.visible');
    });

    it('devrait pouvoir fermer le banner d\'installation', () => {
      cy.visit('/');
      
      cy.window().then((win) => {
        const installEvent = new Event('beforeinstallprompt');
        installEvent.preventDefault = cy.stub();
        win.dispatchEvent(installEvent);
      });
      
      cy.get('#pwa-install-banner').should('be.visible');
      cy.get('[data-pwa-dismiss]').click();
      cy.get('#pwa-install-banner').should('not.be.visible');
    });
  });

  describe('Notifications Push', () => {
    it('devrait demander la permission pour les notifications', () => {
      cy.visit('/');
      
      cy.window().then((win) => {
        // Mock de l'API Notification
        win.Notification = {
          permission: 'default',
          requestPermission: cy.stub().resolves('granted')
        };
      });
      
      cy.get('[data-cy="enable-notifications"]').click();
      cy.waitForToast('Notifications activées');
    });
  });

  describe('Raccourcis d\'application', () => {
    it('devrait supporter les raccourcis clavier', () => {
      cy.visit('/adherent/dashboard');
      
      // Ctrl+1 pour dashboard
      cy.get('body').type('{ctrl}1');
      cy.url().should('include', '/adherent/dashboard');
      
      // Ctrl+2 pour cotisations
      cy.get('body').type('{ctrl}2');
      cy.url().should('include', '/adherent/cotisations');
      
      // Ctrl+3 pour profil
      cy.get('body').type('{ctrl}3');
      cy.url().should('include', '/adherent/profil');
    });
  });

  describe('Mode standalone', () => {
    it('devrait adapter l\'interface en mode standalone', () => {
      // Simuler le mode standalone
      cy.visit('/', {
        onBeforeLoad: (win) => {
          Object.defineProperty(win.navigator, 'standalone', {
            value: true,
            writable: false
          });
        }
      });
      
      cy.get('html').should('have.class', 'pwa-installed');
      cy.get('.browser-only').should('not.be.visible');
    });
  });

  describe('Gestion des mises à jour', () => {
    it('devrait notifier des mises à jour disponibles', () => {
      cy.visit('/');
      cy.waitForServiceWorker();
      
      cy.window().then((win) => {
        // Simuler une nouvelle version du SW
        const swRegistration = win.navigator.serviceWorker.controller.registration;
        const event = new Event('updatefound');
        swRegistration.dispatchEvent(event);
      });
      
      // Vérifier la notification de mise à jour
      cy.get('#sw-update-banner').should('be.visible');
      cy.get('[data-cy="update-app"]').should('be.visible');
    });

    it('devrait pouvoir mettre à jour l\'application', () => {
      cy.visit('/');
      cy.waitForServiceWorker();
      
      // Simuler une mise à jour disponible
      cy.window().then((win) => {
        if (win.swManager) {
          win.swManager.showUpdateBanner();
        }
      });
      
      cy.get('#sw-update-banner').should('be.visible');
      cy.get('[data-cy="update-app"]').click();
      
      // L'application devrait se recharger
      cy.url().should('eq', Cypress.config().baseUrl + '/adherent/dashboard');
    });
  });

  describe('Performance PWA', () => {
    it('devrait avoir de bonnes métriques de performance', () => {
      cy.visit('/');
      cy.checkPagePerformance({
        loadTime: 2000 // 2 secondes max
      });
      
      // Vérifier les Core Web Vitals (si disponible)
      cy.window().then((win) => {
        if (win.PerformanceOptimizer) {
          const metrics = win.PerformanceOptimizer.getMetrics();
          
          if (metrics.LCP) {
            expect(metrics.LCP.value).to.be.lessThan(2500); // LCP < 2.5s
          }
          
          if (metrics.CLS) {
            expect(metrics.CLS.value).to.be.lessThan(0.1); // CLS < 0.1
          }
        }
      });
    });
  });

  describe('Responsive PWA', () => {
    it('devrait fonctionner sur différentes tailles d\'écran', () => {
      const viewports = [
        [375, 667], // iPhone SE
        [768, 1024], // iPad
        [1280, 720]  // Desktop
      ];
      
      viewports.forEach(([width, height]) => {
        cy.viewport(width, height);
        cy.visit('/');
        cy.checkPWA();
        
        cy.get('#pwa-install-banner').should(width < 768 ? 'be.visible' : 'be.visible');
      });
    });
  });
});