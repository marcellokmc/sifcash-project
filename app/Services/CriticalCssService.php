<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CriticalCssService
{
    protected $basePath;
    protected $manifestPath;
    protected $manifest;
    
    public function __construct()
    {
        $this->basePath = resource_path('css/critical');
        $this->manifestPath = $this->basePath . '/manifest.json';
        $this->loadManifest();
    }
    
    /**
     * Charger le manifest des Critical CSS
     */
    protected function loadManifest()
    {
        if (File::exists($this->manifestPath)) {
            $this->manifest = json_decode(File::get($this->manifestPath), true);
        } else {
            $this->manifest = ['files' => []];
        }
    }
    
    /**
     * Obtenir le Critical CSS pour une route spécifique
     */
    public function getCriticalCss(string $route = null): string
    {
        $cacheKey = 'critical-css-' . ($route ?: 'global');
        
        return Cache::remember($cacheKey, 3600, function () use ($route) {
            $cssFile = $this->determineCssFile($route);
            $filePath = $this->basePath . '/' . $cssFile;
            
            if (File::exists($filePath)) {
                return File::get($filePath);
            }
            
            // Fallback vers le CSS global
            $globalFile = $this->basePath . '/global-critical.css';
            if (File::exists($globalFile)) {
                return File::get($globalFile);
            }
            
            // Fallback ultime
            return $this->getDefaultCriticalCss();
        });
    }
    
    /**
     * Déterminer le fichier CSS selon la route
     */
    protected function determineCssFile(?string $route): string
    {
        if (!$route) {
            return 'global-critical.css';
        }
        
        $routeMap = [
            'login' => 'auth-critical.css',
            'register' => 'auth-critical.css',
            'adherent.dashboard' => 'dashboard-critical.css',
            'adherent.cotisations' => 'cotisations-critical.css',
            'admin.*' => 'admin-critical.css',
            'home' => 'home-critical.css'
        ];
        
        foreach ($routeMap as $pattern => $file) {
            if (Str::is($pattern, $route)) {
                return $file;
            }
        }
        
        return 'global-critical.css';
    }
    
    /**
     * CSS critique par défaut en cas de problème
     */
    protected function getDefaultCriticalCss(): string
    {
        return '
            body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
            .container { max-width: 1140px; margin: 0 auto; padding: 0 15px; }
            .navbar { background: #667eea; padding: 1rem 0; color: white; }
            .btn { padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer; display: inline-block; text-decoration: none; }
            .btn-primary { background: #667eea; color: white; }
            .card { background: white; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 1rem; }
            .alert { padding: 1rem; border-radius: 0.25rem; margin: 1rem 0; }
            .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
            .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
            .form-control { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 0.25rem; }
            .d-flex { display: flex; }
            .justify-content-center { justify-content: center; }
            .align-items-center { align-items: center; }
            .text-center { text-align: center; }
            .mb-3 { margin-bottom: 1rem; }
            .mt-3 { margin-top: 1rem; }
        ';
    }
    
    /**
     * Obtenir les statistiques du Critical CSS
     */
    public function getStats(): array
    {
        return [
            'manifest' => $this->manifest,
            'files_count' => count($this->manifest['files'] ?? []),
            'total_size' => array_sum(array_column($this->manifest['files'] ?? [], 'size')),
            'last_generated' => $this->manifest['generated_at'] ?? null
        ];
    }
    
    /**
     * Injecter le Critical CSS dans une vue
     */
    public function injectCriticalCss(string $route = null): string
    {
        $css = $this->getCriticalCss($route);
        
        if (empty($css)) {
            return '';
        }
        
        $minified = $this->minifyCSS($css);
        
        return sprintf(
            '<style id="critical-css">%s</style>',
            $minified
        );
    }
    
    /**
     * Minification basique du CSS
     */
    protected function minifyCSS(string $css): string
    {
        // Supprimer les commentaires
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Supprimer les espaces inutiles
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
        $css = preg_replace('/\s+/', ' ', $css);
        $css = str_replace([' {', '{ ', ' }', '} ', ': ', '; ', ', '], ['{', '{', '}', '}', ':', ';', ','], $css);
        
        return trim($css);
    }
    
    /**
     * Précharger le CSS non critique
     */
    public function preloadNonCriticalCss(): string
    {
        $assetPath = mix('css/app.css');
        
        return sprintf(
            '<link rel="preload" href="%s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" crossorigin>
            <noscript><link rel="stylesheet" href="%s"></noscript>',
            $assetPath,
            $assetPath
        );
    }
    
    /**
     * Vider le cache du Critical CSS
     */
    public function clearCache(): bool
    {
        $keys = [
            'critical-css-global',
            'critical-css-login',
            'critical-css-adherent.dashboard',
            'critical-css-adherent.cotisations',
            'critical-css-home'
        ];
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        
        return true;
    }
    
    /**
     * Régénérer tous les Critical CSS
     */
    public function regenerate(): bool
    {
        try {
            // Exécuter le script Node.js
            $scriptPath = base_path('scripts/generate-critical-css.js');
            
            if (File::exists($scriptPath)) {
                $command = "node \"{$scriptPath}\"";
                $output = shell_exec($command . ' 2>&1');
                
                \Log::info('Critical CSS regenerated', ['output' => $output]);
                
                // Recharger le manifest
                $this->loadManifest();
                
                // Vider le cache
                $this->clearCache();
                
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Failed to regenerate Critical CSS', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Vérifier si les Critical CSS sont à jour
     */
    public function needsRegeneration(): bool
    {
        if (!$this->manifest || !isset($this->manifest['generated_at'])) {
            return true;
        }
        
        $lastGenerated = \Carbon\Carbon::parse($this->manifest['generated_at']);
        $cssFiles = File::allFiles(resource_path('css'));
        
        foreach ($cssFiles as $file) {
            if ($file->getMTime() > $lastGenerated->timestamp) {
                return true;
            }
        }
        
        return false;
    }
}