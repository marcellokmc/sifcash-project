// ***********************************************
// Commandes personnalisées pour SIFCash-Burkina
// ***********************************************

// Commande de connexion
Cypress.Commands.add('login', (email, password, options = {}) => {
  const user = email || Cypress.env('testUser').email;
  const pass = password || Cypress.env('testUser').password;
  
  cy.session([user, pass], () => {
    cy.visit('/login');
    cy.waitForPageLoad();
    
    cy.get('[data-cy="email-input"]').type(user);
    cy.get('[data-cy="password-input"]').type(pass, { log: false });
    cy.get('[data-cy="login-button"]').click();
    
    cy.url().should('not.include', '/login');
    cy.getCookie('laravel_session').should('exist');
    
    if (options.admin) {
      cy.url().should('include', '/admin');
    } else {
      cy.url().should('include', '/adherent');
    }
  });
});

// Connexion admin
Cypress.Commands.add('loginAsAdmin', () => {
  cy.login(Cypress.env('adminUser').email, Cypress.env('adminUser').password, { admin: true });
});

// Déconnexion
Cypress.Commands.add('logout', () => {
  cy.get('[data-cy="user-menu"]').click();
  cy.get('[data-cy="logout-button"]').click();
  cy.url().should('include', '/login');
});

// Navigation vers une page spécifique
Cypress.Commands.add('navigateTo', (page) => {
  const routes = {
    dashboard: '/adherent/dashboard',
    cotisations: '/adherent/cotisations',
    profil: '/adherent/profil',
    notifications: '/adherent/notifications',
    adminDashboard: '/admin/dashboard',
    adminAdherents: '/admin/adherents'
  };
  
  const url = routes[page] || page;
  cy.visit(url);
  cy.waitForPageLoad();
});

// Attendre qu'un toast soit visible
Cypress.Commands.add('waitForToast', (message, type = 'success') => {
  cy.get('.toast', { timeout: 10000 })
    .should('be.visible')
    .and('contain', message);
    
  if (type) {
    cy.get('.toast').should('have.class', `toast-${type}`);
  }
});

// Remplir un formulaire avec des données
Cypress.Commands.add('fillForm', (formData) => {
  Object.keys(formData).forEach(field => {
    const value = formData[field];
    const selector = `[data-cy="${field}"], [name="${field}"], #${field}`;
    
    cy.get(selector).then($el => {
      const tagName = $el.prop('tagName').toLowerCase();
      const type = $el.attr('type');
      
      if (tagName === 'select') {
        cy.get(selector).select(value);
      } else if (type === 'checkbox') {
        if (value) {
          cy.get(selector).check();
        } else {
          cy.get(selector).uncheck();
        }
      } else if (type === 'radio') {
        cy.get(`${selector}[value="${value}"]`).check();
      } else {
        cy.get(selector).clear().type(value);
      }
    });
  });
});

// Soumettre un formulaire et attendre la réponse
Cypress.Commands.add('submitForm', (formSelector = 'form', expectedMessage) => {
  cy.get(formSelector).submit();
  
  if (expectedMessage) {
    cy.waitForToast(expectedMessage);
  }
});

// Vérifier qu'un élément est dans le viewport
Cypress.Commands.add('isInViewport', (selector) => {
  cy.get(selector).should('be.visible').and(($el) => {
    const bottom = Cypress.$(cy.state('window')).height();
    const rect = $el[0].getBoundingClientRect();
    
    expect(rect.top).to.be.at.least(0);
    expect(rect.bottom).to.be.at.most(bottom);
  });
});

// Attendre le chargement d'une table de données
Cypress.Commands.add('waitForDataTable', (selector = '[data-cy="data-table"]') => {
  cy.get(selector).should('exist');
  cy.get(`${selector} .loading-spinner`, { timeout: 1000 }).should('not.exist');
  cy.get(`${selector} tbody tr`).should('have.length.at.least', 1);
});

// Rechercher dans une table
Cypress.Commands.add('searchInTable', (query, resultSelector = 'tbody tr') => {
  cy.get('[data-cy="search-input"]').clear().type(query);
  cy.get('[data-cy="search-button"]').click();
  cy.waitForDataTable();
  cy.get(resultSelector).should('contain', query);
});

// Tester la responsivité
Cypress.Commands.add('testResponsive', (breakpoints = ['mobile', 'tablet', 'desktop']) => {
  const viewports = {
    mobile: [375, 667],
    tablet: [768, 1024],
    desktop: [1280, 720]
  };
  
  breakpoints.forEach(breakpoint => {
    const [width, height] = viewports[breakpoint];
    cy.viewport(width, height);
    cy.wait(500); // Laisser le temps au responsive de s'appliquer
  });
});

// Mock d'une requête API
Cypress.Commands.add('mockAPI', (method, url, response, statusCode = 200) => {
  cy.intercept(method, url, {
    statusCode,
    body: response
  }).as('mockAPI');
});

// Vérifier l'accessibilité basique
Cypress.Commands.add('checkA11y', (context = null, options = {}) => {
  if (Cypress.env('checkA11y')) {
    cy.checkA11y(context, {
      rules: {
        'color-contrast': { enabled: false }, // Désactiver temporairement
        ...options.rules
      }
    });
  }
});

// Télécharger et vérifier un fichier
Cypress.Commands.add('downloadFile', (downloadSelector, fileName) => {
  cy.get(downloadSelector).click();
  cy.readFile(`cypress/downloads/${fileName}`, { timeout: 15000 }).should('exist');
});

// Attendre qu'une notification soit visible
Cypress.Commands.add('waitForNotification', (message) => {
  cy.get('[data-cy="notification"]', { timeout: 10000 })
    .should('be.visible')
    .and('contain', message);
});

// Simuler un glisser-déposer
Cypress.Commands.add('dragAndDrop', (sourceSelector, targetSelector) => {
  cy.get(sourceSelector).trigger('dragstart');
  cy.get(targetSelector).trigger('drop');
});

// Attendre la fin de toutes les requêtes AJAX
Cypress.Commands.add('waitForAjax', () => {
  cy.window().then((win) => {
    // Attendre que jQuery ou Fetch soient inactifs
    cy.wrap(null).should(() => {
      if (win.jQuery) {
        expect(win.jQuery.active).to.equal(0);
      }
    });
  });
});

// Upload de fichier
Cypress.Commands.add('uploadFile', (selector, fileName, fileType = 'application/json') => {
  cy.fixture(fileName).then(fileContent => {
    cy.get(selector).selectFile({
      contents: fileContent,
      fileName: fileName,
      mimeType: fileType
    });
  });
});

// Créer des données de test via API
Cypress.Commands.add('createTestData', (type, data = {}) => {
  cy.request({
    method: 'POST',
    url: `${Cypress.env('apiUrl')}/dev/generate-test-data`,
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: { type, ...data }
  });
});

// Nettoyer les données de test
Cypress.Commands.add('cleanTestData', () => {
  cy.task('cleanDatabase');
});

// Vérifier la performance de la page
Cypress.Commands.add('checkPagePerformance', (thresholds = {}) => {
  cy.window().then((win) => {
    if (win.performance && win.performance.timing) {
      const timing = win.performance.timing;
      const loadTime = timing.loadEventEnd - timing.navigationStart;
      
      const maxLoadTime = thresholds.loadTime || 3000; // 3 secondes par défaut
      expect(loadTime).to.be.lessThan(maxLoadTime);
    }
  });
});

// Attendre qu'un Service Worker soit enregistré
Cypress.Commands.add('waitForServiceWorker', () => {
  cy.window().then((win) => {
    if ('serviceWorker' in win.navigator) {
      cy.wrap(win.navigator.serviceWorker.ready).should('exist');
    }
  });
});

// Tester le mode hors ligne
Cypress.Commands.add('goOffline', () => {
  cy.window().then((win) => {
    // Simuler la déconnexion
    Object.defineProperty(win.navigator, 'onLine', {
      writable: true,
      value: false
    });
    
    win.dispatchEvent(new Event('offline'));
  });
});

Cypress.Commands.add('goOnline', () => {
  cy.window().then((win) => {
    Object.defineProperty(win.navigator, 'onLine', {
      writable: true,
      value: true
    });
    
    win.dispatchEvent(new Event('online'));
  });
});

// Vérifier PWA
Cypress.Commands.add('checkPWA', () => {
  // Vérifier le manifest
  cy.get('link[rel="manifest"]').should('exist');
  
  // Vérifier le Service Worker
  cy.waitForServiceWorker();
  
  // Vérifier les méta tags
  cy.get('meta[name="theme-color"]').should('exist');
});

// Déclaration des types TypeScript (optionnel)
declare global {
  namespace Cypress {
    interface Chainable {
      login(email?: string, password?: string, options?: any): Chainable<void>
      loginAsAdmin(): Chainable<void>
      logout(): Chainable<void>
      navigateTo(page: string): Chainable<void>
      waitForToast(message: string, type?: string): Chainable<void>
      fillForm(formData: object): Chainable<void>
      submitForm(formSelector?: string, expectedMessage?: string): Chainable<void>
      waitForDataTable(selector?: string): Chainable<void>
      searchInTable(query: string, resultSelector?: string): Chainable<void>
      checkA11y(context?: any, options?: any): Chainable<void>
      downloadFile(downloadSelector: string, fileName: string): Chainable<void>
      waitForPageLoad(): Chainable<void>
      checkPWA(): Chainable<void>
      goOffline(): Chainable<void>
      goOnline(): Chainable<void>
    }
  }
}