const fs = require('fs');
const path = require('path');
const sharp = require('sharp');
const glob = require('glob');

/**
 * Configuration des optimisations finales
 */
const config = {
    // Dossiers
    publicDir: path.join(__dirname, '../public'),
    imagesDir: path.join(__dirname, '../public/images'),
    buildDir: path.join(__dirname, '../public/build'),
    
    // Optimisation des images
    imageOptimization: {
        formats: ['webp', 'avif'], // Formats modernes à générer
        quality: {
            webp: 85,
            avif: 80,
            jpeg: 85
        },
        sizes: [400, 600, 800, 1200, 1600], // Tailles responsives
        enableProgressive: true
    },
    
    // Preload/Prefetch
    preload: {
        // Ressources critiques à preloader
        critical: [
            '/build/assets/app.css',
            '/build/assets/app.js'
        ],
        // Polices critiques
        fonts: [
            '/fonts/inter-regular.woff2',
            '/fonts/inter-bold.woff2'
        ]
    },
    
    // Compression
    compression: {
        brotli: true,
        gzip: true
    }
};

/**
 * Optimisations finales
 */
async function runFinalOptimizations() {
    console.log('🚀 Démarrage des optimisations finales...');
    
    try {
        // 1. Optimiser les images
        await optimizeImages();
        
        // 2. Générer les preload/prefetch hints
        await generateResourceHints();
        
        // 3. Comprimer les assets
        await compressAssets();
        
        // 4. Générer le sitemap des ressources
        await generateResourceSitemap();
        
        // 5. Valider les optimisations
        await validateOptimizations();
        
        console.log('✅ Optimisations finales terminées !');
        
        // Afficher le rapport final
        printOptimizationReport();
        
    } catch (error) {
        console.error('❌ Erreur lors des optimisations:', error);
        process.exit(1);
    }
}

/**
 * Optimiser les images
 */
async function optimizeImages() {
    console.log('🖼️  Optimisation des images...');
    
    if (!fs.existsSync(config.imagesDir)) {
        console.log('⚠️  Dossier images non trouvé, création...');
        fs.mkdirSync(config.imagesDir, { recursive: true });
        return;
    }
    
    const imageFiles = glob.sync('**/*.{jpg,jpeg,png}', {
        cwd: config.imagesDir,
        absolute: true
    });
    
    console.log(`📸 ${imageFiles.length} images trouvées`);
    
    const results = {
        processed: 0,
        generated: 0,
        originalSize: 0,
        optimizedSize: 0
    };
    
    for (const imagePath of imageFiles) {
        try {
            await optimizeImage(imagePath, results);
            results.processed++;
        } catch (error) {
            console.error(`❌ Erreur avec ${imagePath}:`, error.message);
        }
    }
    
    const savedBytes = results.originalSize - results.optimizedSize;
    const savedPercent = results.originalSize > 0 
        ? Math.round((savedBytes / results.originalSize) * 100) 
        : 0;
    
    console.log(`✅ Images optimisées: ${results.processed} traitées, ${results.generated} générées`);
    console.log(`💾 Espace économisé: ${formatBytes(savedBytes)} (${savedPercent}%)`);
}

/**
 * Optimiser une image individuelle
 */
async function optimizeImage(imagePath, results) {
    const stats = fs.statSync(imagePath);
    results.originalSize += stats.size;
    
    const filename = path.basename(imagePath, path.extname(imagePath));
    const dir = path.dirname(imagePath);
    
    // Charger l'image
    const image = sharp(imagePath);
    const metadata = await image.metadata();
    
    // Générer les formats modernes
    for (const format of config.imageOptimization.formats) {
        const outputPath = path.join(dir, `${filename}.${format}`);
        
        if (!fs.existsSync(outputPath)) {
            await image
                [format]({ 
                    quality: config.imageOptimization.quality[format] || 85 
                })
                .toFile(outputPath);
            
            results.generated++;
            
            const optimizedStats = fs.statSync(outputPath);
            results.optimizedSize += optimizedStats.size;
        }
    }
    
    // Générer les tailles responsives
    if (metadata.width > 800) {
        for (const size of config.imageOptimization.sizes) {
            if (size < metadata.width) {
                const responsivePath = path.join(dir, `${filename}-${size}w.webp`);
                
                if (!fs.existsSync(responsivePath)) {
                    await image
                        .resize(size, null, { 
                            withoutEnlargement: true 
                        })
                        .webp({ 
                            quality: config.imageOptimization.quality.webp 
                        })
                        .toFile(responsivePath);
                    
                    results.generated++;
                }
            }
        }
    }
}

/**
 * Générer les resource hints (preload/prefetch)
 */
async function generateResourceHints() {
    console.log('🔗 Génération des resource hints...');
    
    const hints = {
        preload: [],
        prefetch: [],
        preconnect: []
    };
    
    // Preload des ressources critiques
    config.preload.critical.forEach(resource => {
        if (resourceExists(resource)) {
            const type = getResourceType(resource);
            hints.preload.push({
                href: resource,
                as: type,
                crossorigin: type === 'font' ? 'anonymous' : undefined
            });
        }
    });
    
    // Preload des polices
    config.preload.fonts.forEach(font => {
        if (resourceExists(font)) {
            hints.preload.push({
                href: font,
                as: 'font',
                type: 'font/woff2',
                crossorigin: 'anonymous'
            });
        }
    });
    
    // Preconnect vers les domaines externes
    const externalDomains = [
        'fonts.googleapis.com',
        'fonts.gstatic.com',
        'cdn.jsdelivr.net',
        'cdnjs.cloudflare.com'
    ];
    
    externalDomains.forEach(domain => {
        hints.preconnect.push({
            href: `https://${domain}`,
            crossorigin: 'anonymous'
        });
    });
    
    // Prefetch des pages importantes
    const importantPages = [
        '/adherent/dashboard',
        '/adherent/cotisations',
        '/adherent/profil'
    ];
    
    importantPages.forEach(page => {
        hints.prefetch.push({
            href: page
        });
    });
    
    // Générer le fichier de hints
    const hintsPath = path.join(config.publicDir, 'resource-hints.json');
    fs.writeFileSync(hintsPath, JSON.stringify(hints, null, 2));
    
    // Générer le HTML des hints
    generateResourceHintsHTML(hints);
    
    console.log(`✅ Resource hints générés: ${hints.preload.length} preload, ${hints.prefetch.length} prefetch, ${hints.preconnect.length} preconnect`);
}

/**
 * Générer le HTML des resource hints
 */
function generateResourceHintsHTML(hints) {
    const htmlParts = [];
    
    // Preconnect
    hints.preconnect.forEach(hint => {
        const crossorigin = hint.crossorigin ? ` crossorigin="${hint.crossorigin}"` : '';
        htmlParts.push(`<link rel="preconnect" href="${hint.href}"${crossorigin}>`);
    });
    
    // Preload
    hints.preload.forEach(hint => {
        const as = hint.as ? ` as="${hint.as}"` : '';
        const type = hint.type ? ` type="${hint.type}"` : '';
        const crossorigin = hint.crossorigin ? ` crossorigin="${hint.crossorigin}"` : '';
        htmlParts.push(`<link rel="preload" href="${hint.href}"${as}${type}${crossorigin}>`);
    });
    
    // Prefetch
    hints.prefetch.forEach(hint => {
        htmlParts.push(`<link rel="prefetch" href="${hint.href}">`);
    });
    
    const htmlContent = `<!-- Resource Hints - Auto-generated -->\n${htmlParts.join('\n')}`;
    
    const htmlPath = path.join(config.publicDir, 'resource-hints.html');
    fs.writeFileSync(htmlPath, htmlContent);
}

/**
 * Vérifier si une ressource existe
 */
function resourceExists(resource) {
    const fullPath = path.join(config.publicDir, resource.startsWith('/') ? resource.slice(1) : resource);
    return fs.existsSync(fullPath);
}

/**
 * Déterminer le type de ressource
 */
function getResourceType(resource) {
    const ext = path.extname(resource).toLowerCase();
    
    const typeMap = {
        '.css': 'style',
        '.js': 'script',
        '.woff': 'font',
        '.woff2': 'font',
        '.ttf': 'font',
        '.eot': 'font',
        '.png': 'image',
        '.jpg': 'image',
        '.jpeg': 'image',
        '.gif': 'image',
        '.svg': 'image',
        '.webp': 'image'
    };
    
    return typeMap[ext] || 'fetch';
}

/**
 * Comprimer les assets
 */
async function compressAssets() {
    if (!config.compression.brotli && !config.compression.gzip) {
        return;
    }
    
    console.log('🗜️  Compression des assets...');
    
    const assetsToCompress = glob.sync('**/*.{css,js,json,svg}', {
        cwd: config.buildDir,
        absolute: true
    });
    
    console.log(`📦 ${assetsToCompress.length} fichiers à comprimer`);
    
    const results = {
        gzip: { count: 0, originalSize: 0, compressedSize: 0 },
        brotli: { count: 0, originalSize: 0, compressedSize: 0 }
    };
    
    for (const filePath of assetsToCompress) {
        const stats = fs.statSync(filePath);
        
        // Ignorer les petits fichiers
        if (stats.size < 1024) continue;
        
        if (config.compression.gzip) {
            await compressFile(filePath, 'gzip', results.gzip);
        }
        
        if (config.compression.brotli) {
            await compressFile(filePath, 'brotli', results.brotli);
        }
    }
    
    // Afficher les résultats
    if (results.gzip.count > 0) {
        const gzipSaved = results.gzip.originalSize - results.gzip.compressedSize;
        const gzipPercent = Math.round((gzipSaved / results.gzip.originalSize) * 100);
        console.log(`📦 Gzip: ${results.gzip.count} fichiers, ${formatBytes(gzipSaved)} économisés (${gzipPercent}%)`);
    }
    
    if (results.brotli.count > 0) {
        const brotliSaved = results.brotli.originalSize - results.brotli.compressedSize;
        const brotliPercent = Math.round((brotliSaved / results.brotli.originalSize) * 100);
        console.log(`📦 Brotli: ${results.brotli.count} fichiers, ${formatBytes(brotliSaved)} économisés (${brotliPercent}%)`);
    }
}

/**
 * Comprimer un fichier
 */
async function compressFile(filePath, format, results) {
    const zlib = require('zlib');
    const { promisify } = require('util');
    
    const compress = format === 'gzip' 
        ? promisify(zlib.gzip)
        : promisify(zlib.brotliCompress);
    
    const extension = format === 'gzip' ? '.gz' : '.br';
    const outputPath = filePath + extension;
    
    if (fs.existsSync(outputPath)) {
        return; // Déjà compressé
    }
    
    try {
        const originalData = fs.readFileSync(filePath);
        const compressedData = await compress(originalData);
        
        fs.writeFileSync(outputPath, compressedData);
        
        results.count++;
        results.originalSize += originalData.length;
        results.compressedSize += compressedData.length;
        
    } catch (error) {
        console.error(`❌ Erreur compression ${format} pour ${filePath}:`, error.message);
    }
}

/**
 * Générer le sitemap des ressources
 */
async function generateResourceSitemap() {
    console.log('🗺️  Génération du sitemap des ressources...');
    
    const resources = [];
    
    // Assets build
    if (fs.existsSync(config.buildDir)) {
        const buildFiles = glob.sync('**/*', {
            cwd: config.buildDir,
            nodir: true
        });
        
        buildFiles.forEach(file => {
            const stats = fs.statSync(path.join(config.buildDir, file));
            resources.push({
                url: `/build/${file}`,
                size: stats.size,
                modified: stats.mtime,
                type: getResourceType(file)
            });
        });
    }
    
    // Images
    if (fs.existsSync(config.imagesDir)) {
        const imageFiles = glob.sync('**/*.{jpg,jpeg,png,gif,svg,webp,avif}', {
            cwd: config.imagesDir,
            nodir: true
        });
        
        imageFiles.forEach(file => {
            const stats = fs.statSync(path.join(config.imagesDir, file));
            resources.push({
                url: `/images/${file}`,
                size: stats.size,
                modified: stats.mtime,
                type: 'image'
            });
        });
    }
    
    // Générer le sitemap
    const sitemap = {
        generated: new Date().toISOString(),
        total_resources: resources.length,
        total_size: resources.reduce((sum, r) => sum + r.size, 0),
        by_type: {},
        resources: resources.sort((a, b) => b.size - a.size) // Trier par taille
    };
    
    // Statistiques par type
    resources.forEach(resource => {
        if (!sitemap.by_type[resource.type]) {
            sitemap.by_type[resource.type] = { count: 0, size: 0 };
        }
        sitemap.by_type[resource.type].count++;
        sitemap.by_type[resource.type].size += resource.size;
    });
    
    // Sauvegarder
    const sitemapPath = path.join(config.publicDir, 'resource-sitemap.json');
    fs.writeFileSync(sitemapPath, JSON.stringify(sitemap, null, 2));
    
    console.log(`✅ Sitemap généré: ${resources.length} ressources, ${formatBytes(sitemap.total_size)} total`);
}

/**
 * Valider les optimisations
 */
async function validateOptimizations() {
    console.log('✅ Validation des optimisations...');
    
    const validations = [];
    
    // Vérifier les resource hints
    const hintsPath = path.join(config.publicDir, 'resource-hints.json');
    if (fs.existsSync(hintsPath)) {
        validations.push('✓ Resource hints générés');
    } else {
        validations.push('❌ Resource hints manquants');
    }
    
    // Vérifier les compressions
    const compressedFiles = glob.sync('**/*.{gz,br}', {
        cwd: config.buildDir
    });
    
    if (compressedFiles.length > 0) {
        validations.push(`✓ ${compressedFiles.length} fichiers compressés`);
    } else {
        validations.push('⚠️  Aucun fichier compressé trouvé');
    }
    
    // Vérifier les images optimisées
    const webpImages = glob.sync('**/*.webp', {
        cwd: config.imagesDir
    });
    
    if (webpImages.length > 0) {
        validations.push(`✓ ${webpImages.length} images WebP générées`);
    } else {
        validations.push('⚠️  Aucune image WebP trouvée');
    }
    
    console.log('\n📋 VALIDATION:');
    validations.forEach(validation => console.log(`   ${validation}`));
}

/**
 * Afficher le rapport final
 */
function printOptimizationReport() {
    console.log('\n🎉 RAPPORT FINAL DES OPTIMISATIONS');
    console.log('═'.repeat(50));
    
    // Statistiques des ressources
    const sitemapPath = path.join(config.publicDir, 'resource-sitemap.json');
    if (fs.existsSync(sitemapPath)) {
        const sitemap = JSON.parse(fs.readFileSync(sitemapPath, 'utf8'));
        
        console.log(`📦 Total des ressources: ${sitemap.total_resources}`);
        console.log(`💾 Taille totale: ${formatBytes(sitemap.total_size)}`);
        
        console.log('\n📊 Par type:');
        Object.entries(sitemap.by_type).forEach(([type, stats]) => {
            console.log(`   ${type}: ${stats.count} fichiers (${formatBytes(stats.size)})`);
        });
    }
    
    // Resource hints
    const hintsPath = path.join(config.publicDir, 'resource-hints.json');
    if (fs.existsSync(hintsPath)) {
        const hints = JSON.parse(fs.readFileSync(hintsPath, 'utf8'));
        console.log(`\n🔗 Resource hints: ${hints.preload.length + hints.prefetch.length + hints.preconnect.length} total`);
    }
    
    console.log('\n✨ Optimisations appliquées:');
    console.log('   ✓ Images optimisées (WebP, AVIF, responsive)');
    console.log('   ✓ Assets compressés (Gzip, Brotli)');
    console.log('   ✓ Resource hints générés');
    console.log('   ✓ Sitemap des ressources créé');
    
    console.log('\n💡 Prochaines étapes:');
    console.log('   • Configurer le serveur pour servir les fichiers compressés');
    console.log('   • Ajouter les resource hints dans le layout principal');
    console.log('   • Monitorer les Core Web Vitals en production');
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

// Installer Sharp si pas présent
async function ensureSharp() {
    try {
        require('sharp');
    } catch (error) {
        console.log('📦 Installation de Sharp pour l\'optimisation d\'images...');
        const { spawn } = require('child_process');
        
        return new Promise((resolve, reject) => {
            const install = spawn('npm', ['install', 'sharp', 'glob'], {
                stdio: 'inherit',
                shell: true
            });
            
            install.on('close', (code) => {
                if (code === 0) {
                    resolve();
                } else {
                    reject(new Error('Installation de Sharp échouée'));
                }
            });
        });
    }
}

// Exécution du script
if (require.main === module) {
    ensureSharp()
        .then(() => runFinalOptimizations())
        .catch(error => {
            console.error('❌ Erreur:', error);
            process.exit(1);
        });
}

module.exports = {
    runFinalOptimizations,
    config
};