describe('Authentification SIF Burkina', () => {
  beforeEach(() => {
    cy.cleanTestData();
    cy.task('seedDatabase');
  });

  describe('Connexion utilisateur', () => {
    it('devrait permettre à un utilisateur de se connecter', () => {
      cy.visit('/login');
      cy.waitForPageLoad();
      
      cy.get('[data-cy="email-input"]').type(Cypress.env('testUser').email);
      cy.get('[data-cy="password-input"]').type(Cypress.env('testUser').password);
      cy.get('[data-cy="login-button"]').click();
      
      cy.url().should('include', '/adherent/dashboard');
      cy.get('[data-cy="user-menu"]').should('contain', 'Mon compte');
      
      // Vérifier les éléments du dashboard
      cy.get('[data-cy="dashboard-stats"]').should('be.visible');
      cy.get('[data-cy="recent-cotisations"]').should('be.visible');
    });

    it('devrait afficher une erreur pour des identifiants incorrects', () => {
      cy.visit('/login');
      
      cy.get('[data-cy="email-input"]').type('wrong@email.com');
      cy.get('[data-cy="password-input"]').type('wrongpassword');
      cy.get('[data-cy="login-button"]').click();
      
      cy.get('.alert-danger').should('be.visible')
        .and('contain', 'Ces identifiants ne correspondent pas');
    });

    it('devrait valider les champs requis', () => {
      cy.visit('/login');
      
      cy.get('[data-cy="login-button"]').click();
      
      cy.get('[data-cy="email-input"]').should('have.class', 'is-invalid');
      cy.get('[data-cy="password-input"]').should('have.class', 'is-invalid');
    });
  });

  describe('Connexion administrateur', () => {
    it('devrait permettre à un admin de se connecter', () => {
      cy.visit('/admin/login');
      cy.waitForPageLoad();
      
      cy.get('[data-cy="email-input"]').type(Cypress.env('adminUser').email);
      cy.get('[data-cy="password-input"]').type(Cypress.env('adminUser').password);
      cy.get('[data-cy="login-button"]').click();
      
      cy.url().should('include', '/admin/dashboard');
      cy.get('[data-cy="admin-sidebar"]').should('be.visible');
    });
  });

  describe('Mot de passe oublié', () => {
    it('devrait envoyer un email de réinitialisation', () => {
      cy.visit('/login');
      cy.get('[data-cy="forgot-password-link"]').click();
      
      cy.url().should('include', '/password/reset');
      cy.get('[data-cy="email-input"]').type(Cypress.env('testUser').email);
      cy.get('[data-cy="send-reset-button"]').click();
      
      cy.waitForToast('Email de réinitialisation envoyé');
    });
  });

  describe('Déconnexion', () => {
    beforeEach(() => {
      cy.login();
    });

    it('devrait permettre à un utilisateur de se déconnecter', () => {
      cy.navigateTo('dashboard');
      cy.logout();
      
      cy.url().should('include', '/login');
      cy.getCookie('laravel_session').should('not.exist');
    });
  });

  describe('Protection des routes', () => {
    it('devrait rediriger vers login pour les pages protégées', () => {
      cy.visit('/adherent/dashboard');
      cy.url().should('include', '/login');
    });

    it('devrait maintenir la page demandée après connexion', () => {
      cy.visit('/adherent/cotisations');
      cy.url().should('include', '/login');
      
      cy.get('[data-cy="email-input"]').type(Cypress.env('testUser').email);
      cy.get('[data-cy="password-input"]').type(Cypress.env('testUser').password);
      cy.get('[data-cy="login-button"]').click();
      
      cy.url().should('include', '/adherent/cotisations');
    });
  });

  describe('Tests responsives', () => {
    it('devrait fonctionner sur mobile', () => {
      cy.viewport('iphone-x');
      cy.visit('/login');
      
      cy.get('[data-cy="login-form"]').should('be.visible');
      cy.get('[data-cy="email-input"]').type(Cypress.env('testUser').email);
      cy.get('[data-cy="password-input"]').type(Cypress.env('testUser').password);
      cy.get('[data-cy="login-button"]').click();
      
      cy.url().should('include', '/adherent/dashboard');
    });
  });

  describe('Sécurité', () => {
    it('devrait protéger contre les attaques CSRF', () => {
      cy.visit('/login');
      
      // Vérifier la présence du token CSRF
      cy.get('input[name="_token"]').should('exist');
      cy.get('meta[name="csrf-token"]').should('exist');
    });

    it('devrait limiter les tentatives de connexion', () => {
      cy.visit('/login');
      
      // Simuler plusieurs tentatives échouées
      for (let i = 0; i < 5; i++) {
        cy.get('[data-cy="email-input"]').clear().type('test@example.com');
        cy.get('[data-cy="password-input"]').clear().type('wrongpassword');
        cy.get('[data-cy="login-button"]').click();
        cy.wait(1000);
      }
      
      // La 6ème tentative devrait être bloquée
      cy.get('.alert-danger').should('contain', 'Trop de tentatives');
    });
  });
});