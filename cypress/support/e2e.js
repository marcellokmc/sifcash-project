// Import commands.js using ES2015 syntax:
import './commands';

// Configuration globale pour tous les tests
beforeEach(() => {
  // Intercepter les erreurs JavaScript et les ignorer (optionnel)
  cy.on('uncaught:exception', (err, runnable) => {
    // Ignorer certaines erreurs communes
    if (err.message.includes('ResizeObserver') || 
        err.message.includes('Non-Error promise rejection')) {
      return false;
    }
    return true;
  });
  
  // Configuration du viewport responsive
  cy.viewport(1280, 720);
  
  // Préserver les cookies de session
  Cypress.Cookies.preserveOnce('laravel_session', 'XSRF-TOKEN');
});

// Configuration des intercepteurs API globaux
beforeEach(() => {
  // Intercepter les appels API pour monitoring
  cy.intercept('GET', '/api/v1/**', (req) => {
    req.headers['Accept'] = 'application/json';
  }).as('apiCall');
  
  // Intercepter les erreurs 500
  cy.intercept('**', { statusCode: 500 }, { fixture: 'error-500.json' });
});

// Configuration des variables d'environnement
Cypress.env('apiUrl', Cypress.env('apiUrl') || 'http://localhost:8000/api/v1');

// Commandes de nettoyage après chaque test
afterEach(() => {
  // Nettoyer le localStorage
  cy.clearLocalStorage();
  
  // Nettoyer les cookies (sauf session)
  cy.clearCookies({ preserve: ['laravel_session', 'XSRF-TOKEN'] });
});

// Configuration du mode test pour l'application
Cypress.on('window:before:load', (win) => {
  // Marquer comme environnement de test
  win.CYPRESS_TEST_MODE = true;
  
  // Désactiver les animations pour les tests plus rapides
  win.document.body.style.setProperty('--animation-duration', '0ms');
  
  // Stub des APIs externes si nécessaire
  win.fetch = new Proxy(win.fetch, {
    apply(target, thisArg, argumentsList) {
      const url = argumentsList[0];
      
      // Intercepter les appels vers des services externes
      if (typeof url === 'string' && url.includes('external-api.com')) {
        return Promise.resolve(new Response(JSON.stringify({ 
          mocked: true 
        })));
      }
      
      return target.apply(thisArg, argumentsList);
    }
  });
});

// Helper pour attendre le chargement complet des ressources
Cypress.Commands.add('waitForPageLoad', () => {
  cy.window().should('have.property', 'document');
  cy.document().should('have.property', 'readyState', 'complete');
  
  // Attendre que les polyfills et le JS soient chargés
  cy.window().should('have.property', 'SIF');
  
  // Attendre la fin des requêtes en cours
  cy.wait(500);
});

// Configuration des rapports de test
if (Cypress.env('generateReport')) {
  import('cypress-mochawesome-reporter').then((reporter) => {
    reporter.register();
  });
}

// Configuration de l'accessibilité (optionnel)
if (Cypress.env('checkA11y')) {
  import('cypress-axe').then(() => {
    beforeEach(() => {
      cy.injectAxe();
    });
  });
}