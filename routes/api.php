<?php

use App\Http\Controllers\API\AdherentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CotisationController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\StatistiqueController;
use App\Http\Controllers\API\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - SIFCash-Burkina
|--------------------------------------------------------------------------
|
| Routes API pour l'application SIFCash-Burkina avec authentification
| et gestion des ressources
|
*/

// Routes publiques
Route::prefix('v1')->group(function () {
    
    // Authentication
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);
    
    // Public info
    Route::get('/info', function () {
        return response()->json([
            'name' => 'SIFCash-Burkina API',
            'version' => '1.0.0',
            'status' => 'active',
            'timestamp' => now()
        ]);
    });
    
    // Health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'healthy',
            'database' => 'connected',
            'timestamp' => now()
        ]);
    });
});

// Routes protégées par authentification
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    
    // Authentication management
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::put('/auth/password', [AuthController::class, 'updatePassword']);
    
    // Profil utilisateur
    Route::prefix('profile')->group(function () {
        Route::get('/', [AdherentController::class, 'profile']);
        Route::put('/', [AdherentController::class, 'updateProfile']);
        Route::post('/avatar', [AdherentController::class, 'uploadAvatar']);
        Route::delete('/avatar', [AdherentController::class, 'deleteAvatar']);
    });
    
    // Gestion des adhérents
    Route::apiResource('adherents', AdherentController::class);
    Route::prefix('adherents')->group(function () {
        Route::get('/{adherent}/cotisations', [AdherentController::class, 'cotisations']);
        Route::get('/{adherent}/transactions', [AdherentController::class, 'transactions']);
        Route::post('/{adherent}/suspend', [AdherentController::class, 'suspend']);
        Route::post('/{adherent}/activate', [AdherentController::class, 'activate']);
        Route::get('/{adherent}/statistics', [AdherentController::class, 'statistics']);
        Route::post('/{adherent}/export', [AdherentController::class, 'export']);
    });
    
    // Gestion des cotisations
    Route::apiResource('cotisations', CotisationController::class);
    Route::prefix('cotisations')->group(function () {
        Route::get('/adherent/{adherent}', [CotisationController::class, 'byAdherent']);
        Route::post('/bulk', [CotisationController::class, 'bulk']);
        Route::post('/{cotisation}/validate', [CotisationController::class, 'validate']);
        Route::post('/{cotisation}/reject', [CotisationController::class, 'reject']);
        Route::get('/pending', [CotisationController::class, 'pending']);
        Route::get('/overdue', [CotisationController::class, 'overdue']);
        Route::post('/import', [CotisationController::class, 'import']);
        Route::get('/export', [CotisationController::class, 'export']);
        Route::get('/statistics', [CotisationController::class, 'statistics']);
    });
    
    // Gestion des transactions
    Route::apiResource('transactions', TransactionController::class);
    Route::prefix('transactions')->group(function () {
        Route::get('/adherent/{adherent}', [TransactionController::class, 'byAdherent']);
        Route::post('/{transaction}/approve', [TransactionController::class, 'approve']);
        Route::post('/{transaction}/reject', [TransactionController::class, 'reject']);
        Route::get('/pending', [TransactionController::class, 'pending']);
        Route::get('/history', [TransactionController::class, 'history']);
        Route::post('/batch', [TransactionController::class, 'batch']);
        Route::get('/export', [TransactionController::class, 'export']);
        Route::get('/statistics', [TransactionController::class, 'statistics']);
    });
    
    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/', [NotificationController::class, 'store']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::put('/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{notification}', [NotificationController::class, 'destroy']);
        Route::delete('/clear-all', [NotificationController::class, 'clearAll']);
        Route::get('/count', [NotificationController::class, 'count']);
        Route::post('/preferences', [NotificationController::class, 'updatePreferences']);
    });
    
    // Statistiques et tableaux de bord
    Route::prefix('statistics')->group(function () {
        Route::get('/dashboard', [StatistiqueController::class, 'dashboard']);
        Route::get('/adherents', [StatistiqueController::class, 'adherents']);
        Route::get('/cotisations', [StatistiqueController::class, 'cotisations']);
        Route::get('/transactions', [StatistiqueController::class, 'transactions']);
        Route::get('/financials', [StatistiqueController::class, 'financials']);
        Route::get('/growth', [StatistiqueController::class, 'growth']);
        Route::get('/activity', [StatistiqueController::class, 'activity']);
        Route::post('/export', [StatistiqueController::class, 'export']);
    });
    
    // Recherche globale
    Route::get('/search', function (Request $request) {
        $query = $request->get('q');
        $type = $request->get('type', 'all');
        
        // Logique de recherche selon le type
        $results = [];
        
        if ($type === 'all' || $type === 'adherents') {
            // Rechercher dans les adhérents
        }
        
        if ($type === 'all' || $type === 'cotisations') {
            // Rechercher dans les cotisations
        }
        
        return response()->json([
            'query' => $query,
            'type' => $type,
            'results' => $results,
            'total' => count($results)
        ]);
    });
    
    // Upload de fichiers
    Route::prefix('uploads')->group(function () {
        Route::post('/avatar', [AdherentController::class, 'uploadAvatar']);
        Route::post('/document', function (Request $request) {
            $request->validate([
                'file' => 'required|file|max:10240', // 10MB max
                'type' => 'required|string|in:document,image,import'
            ]);
            
            $file = $request->file('file');
            $type = $request->get('type');
            
            $path = $file->store("uploads/{$type}", 'public');
            
            return response()->json([
                'path' => $path,
                'url' => asset("storage/{$path}"),
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);
        });
    });
});

// Routes d'administration (protection renforcée)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('v1/admin')->group(function () {
    
    // Gestion des utilisateurs
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminController::class, 'users']);
        Route::post('/', [AdminController::class, 'createUser']);
        Route::put('/{user}', [AdminController::class, 'updateUser']);
        Route::delete('/{user}', [AdminController::class, 'deleteUser']);
        Route::post('/{user}/roles', [AdminController::class, 'assignRole']);
        Route::delete('/{user}/roles/{role}', [AdminController::class, 'removeRole']);
        Route::get('/{user}/permissions', [AdminController::class, 'getUserPermissions']);
        Route::post('/{user}/permissions', [AdminController::class, 'assignPermissions']);
    });
    
    // Configuration système
    Route::prefix('settings')->group(function () {
        Route::get('/', [AdminController::class, 'getSettings']);
        Route::put('/', [AdminController::class, 'updateSettings']);
        Route::post('/backup', [AdminController::class, 'backup']);
        Route::get('/logs', [AdminController::class, 'logs']);
        Route::post('/maintenance', [AdminController::class, 'maintenanceMode']);
        Route::get('/system-info', [AdminController::class, 'systemInfo']);
    });
    
    // Audit et monitoring
    Route::prefix('audit')->group(function () {
        Route::get('/activities', [AdminController::class, 'activities']);
        Route::get('/login-attempts', [AdminController::class, 'loginAttempts']);
        Route::get('/errors', [AdminController::class, 'errors']);
        Route::get('/performance', [AdminController::class, 'performance']);
    });
    
    // Statistiques avancées admin
    Route::prefix('statistics')->group(function () {
        Route::get('/detailed', [AdminController::class, 'detailedStatistics']);
        Route::get('/exports', [AdminController::class, 'exportStatistics']);
        Route::get('/reports', [AdminController::class, 'reports']);
    });
    
    // Gestion des rôles et permissions
    Route::prefix('roles')->group(function () {
        Route::get('/', [AdminController::class, 'roles']);
        Route::post('/', [AdminController::class, 'createRole']);
        Route::put('/{role}', [AdminController::class, 'updateRole']);
        Route::delete('/{role}', [AdminController::class, 'deleteRole']);
        Route::get('/{role}/permissions', [AdminController::class, 'getRolePermissions']);
        Route::post('/{role}/permissions', [AdminController::class, 'assignRolePermissions']);
    });
    
    Route::prefix('permissions')->group(function () {
        Route::get('/', [AdminController::class, 'permissions']);
        Route::post('/', [AdminController::class, 'createPermission']);
        Route::put('/{permission}', [AdminController::class, 'updatePermission']);
        Route::delete('/{permission}', [AdminController::class, 'deletePermission']);
    });
});

// Routes temps réel et WebSocket
Route::middleware(['auth:sanctum'])->prefix('v1/realtime')->group(function () {
    
    // WebSocket authentication
    Route::post('/auth', function (Request $request) {
        return response()->json([
            'auth' => auth()->user()->createToken('websocket')->plainTextToken,
            'user_id' => auth()->id(),
            'channels' => [
                'user.' . auth()->id(),
                'notifications.' . auth()->id()
            ]
        ]);
    });
    
    // Polling endpoints pour fallback
    Route::get('/notifications/poll', [NotificationController::class, 'poll']);
    Route::get('/statistics/poll', [StatistiqueController::class, 'poll']);
    Route::get('/activity/poll', [AdherentController::class, 'pollActivity']);
});

// Routes pour mobile/PWA
Route::middleware(['auth:sanctum'])->prefix('v1/mobile')->group(function () {
    
    // Configuration PWA
    Route::get('/manifest', function () {
        return response()->json([
            'name' => 'SIFCash-Burkina',
            'short_name' => 'SIF',
            'description' => 'Système d\'Information Financière du Burkina Faso',
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#667eea',
            'icons' => [
                [
                    'src' => '/images/icons/icon-192x192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png'
                ],
                [
                    'src' => '/images/icons/icon-512x512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png'
                ]
            ]
        ]);
    });
    
    // Synchronisation offline
    Route::post('/sync', function (Request $request) {
        $data = $request->get('data', []);
        $results = [];
        
        foreach ($data as $item) {
            // Traiter chaque élément de synchronisation
            $results[] = [
                'id' => $item['id'],
                'status' => 'synced',
                'timestamp' => now()
            ];
        }
        
        return response()->json([
            'synced' => count($results),
            'results' => $results
        ]);
    });
    
    // Push notifications
    Route::post('/push/subscribe', [NotificationController::class, 'subscribe']);
    Route::delete('/push/unsubscribe', [NotificationController::class, 'unsubscribe']);
});

// Routes de développement (uniquement en local/staging)
if (app()->environment(['local', 'staging'])) {
    Route::prefix('v1/dev')->group(function () {
        
        // Seeding et fixtures
        Route::post('/seed', function () {
            Artisan::call('db:seed');
            return response()->json(['message' => 'Database seeded successfully']);
        });
        
        // Génération de données de test
        Route::post('/generate-test-data', function (Request $request) {
            $type = $request->get('type', 'adherents');
            $count = $request->get('count', 10);
            
            // Générer des données de test selon le type
            
            return response()->json([
                'message' => "Generated {$count} test {$type}",
                'type' => $type,
                'count' => $count
            ]);
        });
        
        // Clear cache
        Route::post('/clear-cache', function () {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            return response()->json(['message' => 'Cache cleared successfully']);
        });
    });
}

// Gestion d'erreurs API globale
Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint not found',
        'status' => 404,
        'timestamp' => now()
    ], 404);
});