const critical = require('critical');
const path = require('path');
const fs = require('fs');

/**
 * Configuration pour la génération du Critical CSS
 */
const config = {
  // Dossier de sortie
  inline: false, // Ne pas inliner automatiquement
  base: path.join(__dirname, '../public/'),
  src: 'index.html', // Page d'exemple
  dest: path.join(__dirname, '../resources/css/critical.css'),
  
  // Dimensions des viewports
  dimensions: [
    {
      width: 320,
      height: 568
    },
    {
      width: 768,
      height: 1024
    },
    {
      width: 1280,
      height: 720
    }
  ],
  
  // Options de performance
  timeout: 30000,
  concurrency: 4,
  include: ['/build/assets/app-*.css'],
  ignore: [
    '@font-face',
    /url\(/,
    /print/
  ],
  
  // Optimisations
  minify: true,
  extract: true,
  assetPaths: ['public/build/assets/'],
  pathPrefix: '/'
};

/**
 * Pages importantes à analyser pour le Critical CSS
 */
const pages = [
  {
    url: 'http://localhost:8000/',
    output: 'home-critical.css'
  },
  {
    url: 'http://localhost:8000/login',
    output: 'auth-critical.css'
  },
  {
    url: 'http://localhost:8000/adherent/dashboard',
    output: 'dashboard-critical.css'
  },
  {
    url: 'http://localhost:8000/adherent/cotisations',
    output: 'cotisations-critical.css'
  }
];

/**
 * Fonction principale de génération
 */
async function generateCriticalCSS() {
  console.log('🚀 Génération du Critical CSS...');
  
  try {
    // Créer le dossier de sortie s'il n'existe pas
    const outputDir = path.join(__dirname, '../resources/css/critical');
    if (!fs.existsSync(outputDir)) {
      fs.mkdirSync(outputDir, { recursive: true });
    }

    // Générer le CSS critique pour chaque page
    for (const page of pages) {
      console.log(`📄 Traitement de ${page.url}...`);
      
      try {
        const result = await critical.generate({
          ...config,
          src: page.url,
          dest: path.join(outputDir, page.output),
          width: 1280,
          height: 720,
          
          // Options spécifiques par page
          penthouse: {
            timeout: 30000,
            forceInclude: getPageSpecificSelectors(page.url),
            propertiesToRemove: [
              '(-webkit-)?transform'
            ]
          }
        });

        console.log(`✅ ${page.output} généré (${result.css.length} caractères)`);
        
        // Sauvegarder aussi une version minifiée
        const minifiedOutput = page.output.replace('.css', '.min.css');
        fs.writeFileSync(
          path.join(outputDir, minifiedOutput),
          result.css
        );
        
      } catch (pageError) {
        console.error(`❌ Erreur pour ${page.url}:`, pageError.message);
        
        // Générer un CSS critique basique en cas d'erreur
        await generateFallbackCritical(page);
      }
    }

    // Générer un CSS critique global combiné
    await generateGlobalCritical();
    
    // Générer le manifest des Critical CSS
    generateCriticalManifest();
    
    console.log('🎉 Critical CSS généré avec succès !');
    
  } catch (error) {
    console.error('💥 Erreur génération Critical CSS:', error);
    process.exit(1);
  }
}

/**
 * Sélecteurs spécifiques à inclure selon la page
 */
function getPageSpecificSelectors(url) {
  const selectors = [
    // Sélecteurs globaux toujours inclus
    'body',
    '.navbar',
    '.container',
    '.btn',
    '.alert',
    '.card',
    'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
    'p', 'a',
    '.text-center',
    '.d-flex',
    '.justify-content-center',
    '.align-items-center'
  ];

  if (url.includes('/login')) {
    selectors.push(
      '.login-container',
      '.login-form',
      '.form-control',
      '.form-group',
      '.btn-primary'
    );
  }

  if (url.includes('/dashboard')) {
    selectors.push(
      '.dashboard-stats',
      '.stat-card',
      '.chart-container',
      '.recent-activity',
      '.sidebar',
      '.main-content'
    );
  }

  if (url.includes('/cotisations')) {
    selectors.push(
      '.table',
      '.table-responsive',
      '.pagination',
      '.search-form',
      '.filter-controls'
    );
  }

  return selectors;
}

/**
 * Générer un CSS critique de fallback
 */
async function generateFallbackCritical(page) {
  console.log(`🔄 Génération fallback pour ${page.output}...`);
  
  const fallbackCSS = `
    /* Critical CSS Fallback pour ${page.url} */
    body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    .container { max-width: 1140px; margin: 0 auto; padding: 0 15px; }
    .navbar { background: #667eea; padding: 1rem 0; }
    .btn { padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer; }
    .btn-primary { background: #667eea; color: white; }
    .card { background: white; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .alert { padding: 1rem; border-radius: 0.25rem; margin: 1rem 0; }
    .form-control { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 0.25rem; }
  `;
  
  const outputDir = path.join(__dirname, '../resources/css/critical');
  fs.writeFileSync(path.join(outputDir, page.output), fallbackCSS);
}

/**
 * Générer un CSS critique global combiné
 */
async function generateGlobalCritical() {
  console.log('🌐 Génération du CSS critique global...');
  
  const outputDir = path.join(__dirname, '../resources/css/critical');
  const globalCSS = [];
  
  // Lire tous les fichiers critical générés
  const files = fs.readdirSync(outputDir).filter(file => 
    file.endsWith('.css') && !file.includes('global') && !file.includes('.min.')
  );
  
  files.forEach(file => {
    const content = fs.readFileSync(path.join(outputDir, file), 'utf8');
    globalCSS.push(`/* From ${file} */`);
    globalCSS.push(content);
    globalCSS.push('');
  });
  
  // Combiner et dédupliquer
  const combinedCSS = globalCSS.join('\n');
  const dedupedCSS = deduplicateCSS(combinedCSS);
  
  fs.writeFileSync(
    path.join(outputDir, 'global-critical.css'),
    dedupedCSS
  );
  
  console.log('✅ CSS critique global généré');
}

/**
 * Déduplication basique du CSS
 */
function deduplicateCSS(css) {
  const rules = css.split('}');
  const unique = [...new Set(rules)];
  return unique.join('}');
}

/**
 * Générer le manifest des Critical CSS
 */
function generateCriticalManifest() {
  console.log('📋 Génération du manifest Critical CSS...');
  
  const outputDir = path.join(__dirname, '../resources/css/critical');
  const files = fs.readdirSync(outputDir);
  
  const manifest = {
    generated_at: new Date().toISOString(),
    version: '1.0.0',
    files: {}
  };
  
  files.forEach(file => {
    if (file.endsWith('.css')) {
      const stats = fs.statSync(path.join(outputDir, file));
      const content = fs.readFileSync(path.join(outputDir, file), 'utf8');
      
      manifest.files[file] = {
        size: stats.size,
        size_formatted: formatBytes(stats.size),
        rules_count: (content.match(/\{/g) || []).length,
        hash: require('crypto').createHash('md5').update(content).digest('hex').substr(0, 8)
      };
    }
  });
  
  fs.writeFileSync(
    path.join(outputDir, 'manifest.json'),
    JSON.stringify(manifest, null, 2)
  );
  
  console.log('✅ Manifest généré');
}

/**
 * Formater la taille en bytes
 */
function formatBytes(bytes) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Vérifier que le serveur Laravel est accessible
 */
async function checkServer() {
  try {
    const response = await fetch('http://localhost:8000/');
    return response.ok;
  } catch (error) {
    return false;
  }
}

// Exécution du script
if (require.main === module) {
  console.log('🔍 Vérification du serveur Laravel...');
  
  checkServer().then(isRunning => {
    if (!isRunning) {
      console.error('❌ Serveur Laravel non accessible sur http://localhost:8000');
      console.log('💡 Démarrez le serveur avec: php artisan serve');
      process.exit(1);
    }
    
    console.log('✅ Serveur accessible');
    generateCriticalCSS();
  });
}

module.exports = {
  generateCriticalCSS,
  config,
  pages
};