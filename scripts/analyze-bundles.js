const fs = require('fs');
const path = require('path');
const { spawn } = require('child_process');

/**
 * Configuration de l'analyse des bundles
 */
const config = {
    outputDir: path.join(__dirname, '../dist'),
    reportDir: path.join(__dirname, '../reports/bundles'),
    buildDir: path.join(__dirname, '../public/build'),
    
    // Seuils d'alerte (en KB)
    thresholds: {
        js: {
            warning: 250,
            error: 500
        },
        css: {
            warning: 50,
            error: 100
        },
        total: {
            warning: 1000,
            error: 2000
        }
    },
    
    // Formats de rapport
    formats: ['html', 'json', 'csv']
};

/**
 * Analyser les bundles
 */
async function analyzeBundles() {
    console.log('🔍 Analyse des bundles JavaScript et CSS...');
    
    try {
        // Créer les dossiers nécessaires
        ensureDirectories();
        
        // Construire avec analyse
        await buildWithAnalysis();
        
        // Analyser les fichiers générés
        const analysis = analyzeBuiltFiles();
        
        // Générer les rapports
        await generateReports(analysis);
        
        // Vérifier les seuils
        checkThresholds(analysis);
        
        console.log('✅ Analyse des bundles terminée !');
        
    } catch (error) {
        console.error('❌ Erreur lors de l\'analyse:', error);
        process.exit(1);
    }
}

/**
 * Créer les dossiers nécessaires
 */
function ensureDirectories() {
    [config.outputDir, config.reportDir].forEach(dir => {
        if (!fs.existsSync(dir)) {
            fs.mkdirSync(dir, { recursive: true });
        }
    });
}

/**
 * Construire avec analyse activée
 */
function buildWithAnalysis() {
    return new Promise((resolve, reject) => {
        console.log('🔨 Construction avec analyse...');
        
        const buildProcess = spawn('npm', ['run', 'build'], {
            stdio: 'inherit',
            env: { ...process.env, ANALYZE: 'true' },
            shell: true
        });
        
        buildProcess.on('close', (code) => {
            if (code === 0) {
                resolve();
            } else {
                reject(new Error(`Build failed with code ${code}`));
            }
        });
        
        buildProcess.on('error', reject);
    });
}

/**
 * Analyser les fichiers construits
 */
function analyzeBuiltFiles() {
    console.log('📊 Analyse des fichiers générés...');
    
    const analysis = {
        timestamp: new Date().toISOString(),
        files: [],
        summary: {
            totalSize: 0,
            jsSize: 0,
            cssSize: 0,
            imageSize: 0,
            otherSize: 0,
            fileCount: 0,
            compressionRatio: 0
        },
        chunks: {},
        dependencies: {},
        recommendations: []
    };
    
    if (!fs.existsSync(config.buildDir)) {
        throw new Error('Build directory not found. Run build first.');
    }
    
    // Analyser récursivement les fichiers
    analyzeDirectory(config.buildDir, analysis);
    
    // Calculer les métriques
    calculateMetrics(analysis);
    
    // Générer des recommandations
    generateRecommendations(analysis);
    
    return analysis;
}

/**
 * Analyser un dossier récursivement
 */
function analyzeDirectory(dirPath, analysis, relativePath = '') {
    const files = fs.readdirSync(dirPath);
    
    files.forEach(file => {
        const filePath = path.join(dirPath, file);
        const stats = fs.statSync(filePath);
        const relativeFile = path.join(relativePath, file);
        
        if (stats.isDirectory()) {
            analyzeDirectory(filePath, analysis, relativeFile);
        } else {
            const fileAnalysis = analyzeFile(filePath, relativeFile, stats);
            analysis.files.push(fileAnalysis);
            
            // Mettre à jour les totaux
            analysis.summary.totalSize += fileAnalysis.size;
            analysis.summary.fileCount++;
            
            // Catégoriser par type
            switch (fileAnalysis.category) {
                case 'js':
                    analysis.summary.jsSize += fileAnalysis.size;
                    break;
                case 'css':
                    analysis.summary.cssSize += fileAnalysis.size;
                    break;
                case 'image':
                    analysis.summary.imageSize += fileAnalysis.size;
                    break;
                default:
                    analysis.summary.otherSize += fileAnalysis.size;
            }
        }
    });
}

/**
 * Analyser un fichier individuel
 */
function analyzeFile(filePath, relativePath, stats) {
    const ext = path.extname(relativePath).toLowerCase();
    const basename = path.basename(relativePath, ext);
    
    const analysis = {
        path: relativePath,
        name: basename,
        extension: ext,
        size: stats.size,
        sizeFormatted: formatBytes(stats.size),
        category: getFileCategory(ext),
        isChunk: basename.includes('-') && /[a-f0-9]{8}/.test(basename),
        hash: extractHash(basename),
        modified: stats.mtime,
        gzipEstimate: Math.round(stats.size * 0.3), // Estimation gzip
        brotliEstimate: Math.round(stats.size * 0.25) // Estimation brotli
    };
    
    // Analyse spécifique pour JS et CSS
    if (analysis.category === 'js' || analysis.category === 'css') {
        const content = fs.readFileSync(filePath, 'utf8');
        analysis.lines = content.split('\n').length;
        analysis.minified = !content.includes('\n  ') && content.length > 1000;
        
        if (analysis.category === 'js') {
            analysis.dependencies = extractJSDependencies(content);
        }
    }
    
    return analysis;
}

/**
 * Déterminer la catégorie d'un fichier
 */
function getFileCategory(ext) {
    const categories = {
        js: ['.js', '.mjs', '.jsx', '.ts', '.tsx'],
        css: ['.css', '.scss', '.sass', '.less'],
        image: ['.png', '.jpg', '.jpeg', '.gif', '.svg', '.webp', '.ico'],
        font: ['.woff', '.woff2', '.ttf', '.eot', '.otf'],
        data: ['.json', '.xml', '.yaml', '.yml']
    };
    
    for (const [category, extensions] of Object.entries(categories)) {
        if (extensions.includes(ext)) {
            return category;
        }
    }
    
    return 'other';
}

/**
 * Extraire le hash d'un nom de fichier
 */
function extractHash(filename) {
    const match = filename.match(/-([a-f0-9]{8,})/);
    return match ? match[1] : null;
}

/**
 * Extraire les dépendances JS (basique)
 */
function extractJSDependencies(content) {
    const imports = [];
    const importRegex = /import\s+.*?\s+from\s+['"]([^'"]+)['"]/g;
    let match;
    
    while ((match = importRegex.exec(content)) !== null) {
        imports.push(match[1]);
    }
    
    return imports;
}

/**
 * Calculer les métriques
 */
function calculateMetrics(analysis) {
    // Ratio de compression estimé
    const totalCompressed = analysis.files.reduce((sum, file) => sum + file.gzipEstimate, 0);
    analysis.summary.compressionRatio = analysis.summary.totalSize > 0 
        ? Math.round((1 - totalCompressed / analysis.summary.totalSize) * 100) 
        : 0;
    
    // Top 10 des plus gros fichiers
    analysis.largestFiles = analysis.files
        .sort((a, b) => b.size - a.size)
        .slice(0, 10);
    
    // Statistiques par catégorie
    analysis.byCategory = {};
    ['js', 'css', 'image', 'font', 'data', 'other'].forEach(category => {
        const files = analysis.files.filter(f => f.category === category);
        analysis.byCategory[category] = {
            count: files.length,
            totalSize: files.reduce((sum, f) => sum + f.size, 0),
            averageSize: files.length > 0 ? Math.round(files.reduce((sum, f) => sum + f.size, 0) / files.length) : 0,
            files: files.map(f => ({ name: f.name, size: f.size, sizeFormatted: f.sizeFormatted }))
        };
    });
}

/**
 * Générer des recommandations
 */
function generateRecommendations(analysis) {
    const recommendations = [];
    
    // Vérifier la taille des fichiers JS
    const largeJSFiles = analysis.files.filter(f => 
        f.category === 'js' && f.size > config.thresholds.js.warning * 1024
    );
    
    if (largeJSFiles.length > 0) {
        recommendations.push({
            type: 'warning',
            category: 'performance',
            title: 'Fichiers JavaScript volumineux détectés',
            description: `${largeJSFiles.length} fichier(s) JS > ${config.thresholds.js.warning}KB`,
            files: largeJSFiles.map(f => f.path),
            suggestion: 'Considérez le code splitting ou la suppression de dépendances inutilisées'
        });
    }
    
    // Vérifier la taille des fichiers CSS
    const largeCSSFiles = analysis.files.filter(f => 
        f.category === 'css' && f.size > config.thresholds.css.warning * 1024
    );
    
    if (largeCSSFiles.length > 0) {
        recommendations.push({
            type: 'warning',
            category: 'performance',
            title: 'Fichiers CSS volumineux détectés',
            description: `${largeCSSFiles.length} fichier(s) CSS > ${config.thresholds.css.warning}KB`,
            files: largeCSSFiles.map(f => f.path),
            suggestion: 'Utilisez le Critical CSS et purgez le CSS inutilisé'
        });
    }
    
    // Vérifier le nombre total de fichiers
    if (analysis.files.length > 50) {
        recommendations.push({
            type: 'info',
            category: 'optimization',
            title: 'Nombreux fichiers générés',
            description: `${analysis.files.length} fichiers au total`,
            suggestion: 'Considérez regrouper certains assets pour réduire le nombre de requêtes HTTP'
        });
    }
    
    // Vérifier les images non optimisées
    const largeImages = analysis.files.filter(f => 
        f.category === 'image' && f.size > 100 * 1024 && !f.path.includes('.webp')
    );
    
    if (largeImages.length > 0) {
        recommendations.push({
            type: 'suggestion',
            category: 'images',
            title: 'Images pouvant être optimisées',
            description: `${largeImages.length} image(s) > 100KB sans format WebP`,
            files: largeImages.map(f => f.path),
            suggestion: 'Convertir en WebP et utiliser des tailles responsives'
        });
    }
    
    analysis.recommendations = recommendations;
}

/**
 * Générer les rapports
 */
async function generateReports(analysis) {
    console.log('📝 Génération des rapports...');
    
    // Rapport JSON détaillé
    const jsonReport = path.join(config.reportDir, 'bundle-analysis.json');
    fs.writeFileSync(jsonReport, JSON.stringify(analysis, null, 2));
    
    // Rapport HTML
    await generateHTMLReport(analysis);
    
    // Rapport CSV
    generateCSVReport(analysis);
    
    // Rapport console
    printConsoleReport(analysis);
    
    console.log(`📁 Rapports générés dans: ${config.reportDir}`);
}

/**
 * Générer le rapport HTML
 */
async function generateHTMLReport(analysis) {
    const htmlTemplate = `
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bundle Analysis - SIFCash-Burkina</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1, h2, h3 { color: #333; }
        .summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 30px 0; }
        .stat-card { background: #f8f9fa; padding: 20px; border-radius: 6px; text-align: center; }
        .stat-value { font-size: 2em; font-weight: bold; color: #667eea; }
        .stat-label { color: #666; font-size: 0.9em; margin-top: 5px; }
        .file-list { max-height: 400px; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f1f3f4; font-weight: 600; }
        .warning { color: #f56565; }
        .success { color: #48bb78; }
        .info { color: #4299e1; }
        .recommendation { padding: 15px; margin: 10px 0; border-radius: 6px; border-left: 4px solid #667eea; background: #f7fafc; }
        .rec-warning { border-left-color: #f56565; background: #fed7d7; }
        .rec-suggestion { border-left-color: #48bb78; background: #c6f6d5; }
        .progress-bar { width: 100%; height: 20px; background: #e2e8f0; border-radius: 10px; overflow: hidden; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #667eea, #764ba2); transition: width 0.3s ease; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Bundle Analysis Report</h1>
        <p><strong>Généré le:</strong> ${new Date(analysis.timestamp).toLocaleString('fr-FR')}</p>
        
        <div class="summary">
            <div class="stat-card">
                <div class="stat-value">${formatBytes(analysis.summary.totalSize)}</div>
                <div class="stat-label">Taille totale</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">${analysis.summary.fileCount}</div>
                <div class="stat-label">Fichiers</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">${formatBytes(analysis.summary.jsSize)}</div>
                <div class="stat-label">JavaScript</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">${formatBytes(analysis.summary.cssSize)}</div>
                <div class="stat-label">CSS</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">${analysis.summary.compressionRatio}%</div>
                <div class="stat-label">Compression estimée</div>
            </div>
        </div>
        
        <h2>🎯 Recommandations</h2>
        ${analysis.recommendations.map(rec => `
            <div class="recommendation rec-${rec.type}">
                <h3>${rec.title}</h3>
                <p>${rec.description}</p>
                <p><em>💡 ${rec.suggestion}</em></p>
                ${rec.files ? `<details><summary>Fichiers concernés</summary><ul>${rec.files.map(f => `<li>${f}</li>`).join('')}</ul></details>` : ''}
            </div>
        `).join('')}
        
        <h2>📁 Plus gros fichiers</h2>
        <table>
            <thead>
                <tr>
                    <th>Fichier</th>
                    <th>Type</th>
                    <th>Taille</th>
                    <th>Gzip (est.)</th>
                </tr>
            </thead>
            <tbody>
                ${analysis.largestFiles.map(file => `
                    <tr>
                        <td>${file.path}</td>
                        <td>${file.category}</td>
                        <td>${file.sizeFormatted}</td>
                        <td>${formatBytes(file.gzipEstimate)}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
        
        <h2>📊 Par catégorie</h2>
        ${Object.entries(analysis.byCategory).map(([category, data]) => `
            <h3>${category.toUpperCase()} (${data.count} fichiers)</h3>
            <div class="progress-bar">
                <div class="progress-fill" style="width: ${(data.totalSize / analysis.summary.totalSize * 100)}%"></div>
            </div>
            <p><strong>Total:</strong> ${formatBytes(data.totalSize)} | <strong>Moyenne:</strong> ${formatBytes(data.averageSize)}</p>
        `).join('')}
    </div>
</body>
</html>
    `;
    
    const htmlReport = path.join(config.reportDir, 'bundle-analysis.html');
    fs.writeFileSync(htmlReport, htmlTemplate);
}

/**
 * Générer le rapport CSV
 */
function generateCSVReport(analysis) {
    const csvData = [
        ['Fichier', 'Catégorie', 'Taille (bytes)', 'Taille (formatée)', 'Gzip estimé', 'Hash']
    ];
    
    analysis.files.forEach(file => {
        csvData.push([
            file.path,
            file.category,
            file.size,
            file.sizeFormatted,
            file.gzipEstimate,
            file.hash || ''
        ]);
    });
    
    const csvContent = csvData.map(row => row.join(',')).join('\n');
    const csvReport = path.join(config.reportDir, 'bundle-analysis.csv');
    fs.writeFileSync(csvReport, csvContent);
}

/**
 * Afficher le rapport dans la console
 */
function printConsoleReport(analysis) {
    console.log('\n📊 RÉSUMÉ DE L\'ANALYSE');
    console.log('═'.repeat(50));
    console.log(`📁 Fichiers total: ${analysis.summary.fileCount}`);
    console.log(`📦 Taille totale: ${formatBytes(analysis.summary.totalSize)}`);
    console.log(`🟨 JavaScript: ${formatBytes(analysis.summary.jsSize)}`);
    console.log(`🎨 CSS: ${formatBytes(analysis.summary.cssSize)}`);
    console.log(`🖼️  Images: ${formatBytes(analysis.summary.imageSize)}`);
    console.log(`🗜️  Compression estimée: ${analysis.summary.compressionRatio}%`);
    
    if (analysis.recommendations.length > 0) {
        console.log('\n⚠️  RECOMMANDATIONS');
        console.log('─'.repeat(30));
        analysis.recommendations.forEach((rec, i) => {
            const icon = rec.type === 'warning' ? '⚠️' : rec.type === 'suggestion' ? '💡' : 'ℹ️';
            console.log(`${icon} ${rec.title}`);
            console.log(`   ${rec.description}`);
        });
    }
}

/**
 * Vérifier les seuils et alerter
 */
function checkThresholds(analysis) {
    const errors = [];
    const warnings = [];
    
    // Vérifier le total
    const totalMB = analysis.summary.totalSize / (1024 * 1024);
    if (totalMB > config.thresholds.total.error / 1024) {
        errors.push(`Taille totale trop importante: ${formatBytes(analysis.summary.totalSize)}`);
    } else if (totalMB > config.thresholds.total.warning / 1024) {
        warnings.push(`Taille totale élevée: ${formatBytes(analysis.summary.totalSize)}`);
    }
    
    // Vérifier les fichiers individuels
    const largeFiles = analysis.files.filter(f => 
        (f.category === 'js' && f.size > config.thresholds.js.error * 1024) ||
        (f.category === 'css' && f.size > config.thresholds.css.error * 1024)
    );
    
    largeFiles.forEach(file => {
        errors.push(`Fichier trop volumineux: ${file.path} (${file.sizeFormatted})`);
    });
    
    if (errors.length > 0) {
        console.log('\n❌ ERREURS DÉTECTÉES:');
        errors.forEach(error => console.log(`   ${error}`));
    }
    
    if (warnings.length > 0) {
        console.log('\n⚠️  AVERTISSEMENTS:');
        warnings.forEach(warning => console.log(`   ${warning}`));
    }
    
    if (errors.length > 0) {
        console.log('\n💥 L\'analyse a détecté des problèmes critiques !');
        process.exit(1);
    }
}

/**
 * Formater les bytes
 */
function formatBytes(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

// Exécution du script
if (require.main === module) {
    analyzeBundles();
}

module.exports = {
    analyzeBundles,
    config
};