const { defineConfig } = require('cypress');

module.exports = defineConfig({
  e2e: {
    baseUrl: 'http://localhost:8000',
    viewportWidth: 1280,
    viewportHeight: 720,
    video: true,
    screenshotOnRunFailure: true,
    chromeWebSecurity: false,
    
    // Dossiers et fichiers
    specPattern: 'cypress/e2e/**/*.cy.{js,jsx,ts,tsx}',
    supportFile: 'cypress/support/e2e.js',
    fixturesFolder: 'cypress/fixtures',
    screenshotsFolder: 'cypress/screenshots',
    videosFolder: 'cypress/videos',
    downloadsFolder: 'cypress/downloads',
    
    // Configuration des tests
    defaultCommandTimeout: 10000,
    requestTimeout: 10000,
    responseTimeout: 10000,
    pageLoadTimeout: 30000,
    
    // Retry configuration
    retries: {
      runMode: 2,
      openMode: 0
    },
    
    // Variables d'environnement
    env: {
      apiUrl: 'http://localhost:8000/api/v1',
      testUser: {
        email: 'testcontact@sifcash-burkina.com',
        password: 'TestPassword123!'
      },
      adminUser: {
        email: 'admincontact@sifcash-burkina.com',
        password: 'AdminPassword123!'
      }
    },
    
    setupNodeEvents(on, config) {
      // Tasks personnalisées
      on('task', {
        // Seeders de base de données
        seedDatabase() {
          return require('./cypress/tasks/database-seeder')();
        },
        
        // Nettoyage de la base
        cleanDatabase() {
          return require('./cypress/tasks/database-cleaner')();
        },
        
        // Génération de données de test
        generateTestData(type) {
          return require('./cypress/tasks/test-data-generator')(type);
        },
        
        // Vérification des emails (si Mailhog ou similaire)
        checkEmail(criteria) {
          return require('./cypress/tasks/email-checker')(criteria);
        },
        
        // Log des erreurs serveur
        logError(message) {
          console.error('[Cypress Task Error]:', message);
          return null;
        }
      });
      
      // Plugin de préprocesseur pour ES6+
      const webpack = require('@cypress/webpack-preprocessor');
      const options = {
        webpackOptions: {
          resolve: {
            extensions: ['.ts', '.tsx', '.js']
          },
          module: {
            rules: [
              {
                test: /\.tsx?$/,
                loader: 'ts-loader',
                options: { transpileOnly: true }
              }
            ]
          }
        }
      };
      on('file:preprocessor', webpack(options));
      
      // Configuration conditionnelle selon l'environnement
      if (config.env.environment === 'staging') {
        config.baseUrl = 'https://staging.sifcash-burkina.com';
        config.env.apiUrl = 'https://staging.sifcash-burkina.com/api/v1';
      } else if (config.env.environment === 'production') {
        config.baseUrl = 'https://sifcash-burkina.com';
        config.env.apiUrl = 'https://sifcash-burkina.com/api/v1';
        config.video = false; // Pas de vidéo en prod
      }
      
      return config;
    }
  },
  
  component: {
    devServer: {
      framework: 'vue',
      bundler: 'vite',
    },
    specPattern: 'resources/js/**/*.cy.{js,ts,jsx,tsx}',
    supportFile: 'cypress/support/component.js'
  }
});