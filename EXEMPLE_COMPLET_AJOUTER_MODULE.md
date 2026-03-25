# 🎓 EXEMPLE COMPLET: AJOUTER UN MODULE DE RAPPORT

Ce document montre comment ajouter un nouveau module complet au projet en respectant la structure existante.

## 📋 Objectif
Ajouter un module de **Rapport d'Adhérent** permettant de générer des rapports sur les activités d'un adhérent (crédits, paiements, retraits, épargne).

---

## 🔧 Étape 1: Créer le modèle

### Commande
```bash
php artisan make:model Rapport -m
```

### Migration: `database/migrations/YYYY_MM_DD_HHMMSS_create_rapports_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            
            // Clés étrangères
            $table->foreignId('adherent_id')->constrained('adherents')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('set null');
            
            // Colonnes métier
            $table->enum('type', ['complet', 'credits', 'paiements', 'retraits', 'epargne'])->default('complet');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->text('description')->nullable();
            $table->enum('statut', ['en_cours', 'genere', 'telecharge'])->default('en_cours');
            $table->string('fichier_path')->nullable();
            $table->integer('nombre_pages')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index('adherent_id');
            $table->index('type');
            $table->index('statut');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
```

### Modèle: `app/Models/Rapport.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Rapport extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'adherent_id',
        'created_by',
        'type',
        'date_debut',
        'date_fin',
        'description',
        'statut',
        'fichier_path',
        'nombre_pages',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONS ====================
    
    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ==================== SCOPES ====================
    
    public function scopeGenere($query)
    {
        return $query->where('statut', 'genere');
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeParAdherent($query, $adherentId)
    {
        return $query->where('adherent_id', $adherentId);
    }

    // ==================== ACCESSEURS ====================
    
    public function getTypeFormatteAttribute()
    {
        $types = [
            'complet' => 'Rapport complet',
            'credits' => 'Rapport crédits',
            'paiements' => 'Rapport paiements',
            'retraits' => 'Rapport retraits',
            'epargne' => 'Rapport épargne',
        ];
        
        return $types[$this->type] ?? $this->type;
    }

    public function getStatutBadgeAttribute()
    {
        $badges = [
            'en_cours' => 'warning',
            'genere' => 'success',
            'telecharge' => 'info',
        ];
        
        return $badges[$this->statut] ?? 'secondary';
    }

    // ==================== MÉTHODES ====================
    
    public function isGenere()
    {
        return $this->statut === 'genere';
    }

    public function isEnCours()
    {
        return $this->statut === 'en_cours';
    }

    public function marquerCommeGenere($cheminFichier, $nombrePages)
    {
        $this->update([
            'statut' => 'genere',
            'fichier_path' => $cheminFichier,
            'nombre_pages' => $nombrePages,
        ]);
    }

    public function marquerCommeTelechargé()
    {
        $this->update(['statut' => 'telecharge']);
    }
}
```

### Exécuter la migration
```bash
php artisan migrate
```

---

## 🎮 Étape 2: Créer le contrôleur

### Commande
```bash
php artisan make:controller RapportController -r
```

### Contrôleur: `app/Http/Controllers/RapportController.php`
```php
<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\Adherent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;

class RapportController extends Controller
{
    /**
     * Afficher la liste des rapports
     */
    public function index()
    {
        $this->authorize('viewAny', Rapport::class);
        
        $rapports = Rapport::with('adherent', 'createdBy')
            ->latest()
            ->paginate(15);
        
        return view('rapports.index', compact('rapports'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $this->authorize('create', Rapport::class);
        
        $adherents = Adherent::where('statut_compte', 'actif')
            ->orderBy('nom')
            ->get();
        
        $types = [
            'complet' => 'Rapport complet',
            'credits' => 'Rapport crédits',
            'paiements' => 'Rapport paiements',
            'retraits' => 'Rapport retraits',
            'epargne' => 'Rapport épargne',
        ];
        
        return view('rapports.create', compact('adherents', 'types'));
    }

    /**
     * Stocker un nouveau rapport
     */
    public function store(Request $request)
    {
        $this->authorize('create', Rapport::class);
        
        $validated = $request->validate([
            'adherent_id' => 'required|exists:adherents,id',
            'type' => 'required|in:complet,credits,paiements,retraits,epargne',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['statut'] = 'en_cours';

        $rapport = Rapport::create($validated);

        // Dispatcher un job pour générer le rapport
        \App\Jobs\GenerateRapportJob::dispatch($rapport);

        return redirect()
            ->route('rapports.show', $rapport)
            ->with('success', 'Rapport créé. La génération est en cours...');
    }

    /**
     * Afficher un rapport
     */
    public function show(Rapport $rapport)
    {
        $this->authorize('view', $rapport);
        
        return view('rapports.show', compact('rapport'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Rapport $rapport)
    {
        $this->authorize('update', $rapport);
        
        // Empêcher l'édition si le rapport est généré
        if ($rapport->isGenere()) {
            return redirect()
                ->route('rapports.show', $rapport)
                ->with('error', 'Impossible de modifier un rapport généré');
        }
        
        $adherents = Adherent::where('statut_compte', 'actif')->get();
        
        $types = [
            'complet' => 'Rapport complet',
            'credits' => 'Rapport crédits',
            'paiements' => 'Rapport paiements',
            'retraits' => 'Rapport retraits',
            'epargne' => 'Rapport épargne',
        ];
        
        return view('rapports.edit', compact('rapport', 'adherents', 'types'));
    }

    /**
     * Mettre à jour un rapport
     */
    public function update(Request $request, Rapport $rapport)
    {
        $this->authorize('update', $rapport);
        
        if ($rapport->isGenere()) {
            return redirect()
                ->route('rapports.show', $rapport)
                ->with('error', 'Impossible de modifier un rapport généré');
        }
        
        $validated = $request->validate([
            'type' => 'required|in:complet,credits,paiements,retraits,epargne',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'description' => 'nullable|string|max:500',
        ]);

        $rapport->update($validated);

        return redirect()
            ->route('rapports.show', $rapport)
            ->with('success', 'Rapport mis à jour');
    }

    /**
     * Supprimer un rapport
     */
    public function destroy(Rapport $rapport)
    {
        $this->authorize('delete', $rapport);
        
        // Supprimer le fichier s'il existe
        if ($rapport->fichier_path && Storage::exists($rapport->fichier_path)) {
            Storage::delete($rapport->fichier_path);
        }
        
        $rapport->delete();

        return redirect()
            ->route('rapports.index')
            ->with('success', 'Rapport supprimé');
    }

    /**
     * Télécharger un rapport
     */
    public function download(Rapport $rapport)
    {
        $this->authorize('download', $rapport);
        
        if (!$rapport->isGenere() || !$rapport->fichier_path) {
            return redirect()
                ->back()
                ->with('error', 'Le rapport n\'est pas encore disponible');
        }
        
        if (!Storage::exists($rapport->fichier_path)) {
            return redirect()
                ->back()
                ->with('error', 'Le fichier du rapport n\'existe plus');
        }
        
        // Marquer comme téléchargé
        $rapport->marquerCommeTelechargé();
        
        // Notification
        NotificationService::infoGenerale(
            $rapport->adherent->user_id,
            '📥 Rapport téléchargé',
            'Votre rapport a été téléchargé avec succès.',
            'info'
        );
        
        return Storage::download($rapport->fichier_path, "rapport_{$rapport->id}.pdf");
    }

    /**
     * Régénérer un rapport
     */
    public function regenerate(Rapport $rapport)
    {
        $this->authorize('update', $rapport);
        
        // Supprimer l'ancien fichier
        if ($rapport->fichier_path && Storage::exists($rapport->fichier_path)) {
            Storage::delete($rapport->fichier_path);
        }
        
        // Réinitialiser le statut
        $rapport->update([
            'statut' => 'en_cours',
            'fichier_path' => null,
            'nombre_pages' => null,
        ]);
        
        // Dispatcher un job pour générer le rapport
        \App\Jobs\GenerateRapportJob::dispatch($rapport);
        
        return redirect()
            ->back()
            ->with('success', 'Rapport en cours de régénération...');
    }
}
```

---

## 🔐 Étape 3: Créer la Policy

### Commande
```bash
php artisan make:policy RapportPolicy --model=Rapport
```

### Policy: `app/Policies/RapportPolicy.php`
```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Rapport;

class RapportPolicy
{
    /**
     * Déterminer si l'utilisateur peut voir n'importe quel rapport
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent() || $user->isChefService();
    }

    /**
     * Déterminer si l'utilisateur peut voir un rapport
     */
    public function view(User $user, Rapport $rapport): bool
    {
        // Admin peut voir tous les rapports
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut voir les rapports de son agence
        if ($user->isAgent() && $user->agence_id) {
            return $rapport->adherent->agence_id === $user->agence_id;
        }

        // Chef de service peut voir les rapports de son agence
        if ($user->isChefService() && $user->agence_id) {
            return $rapport->adherent->agence_id === $user->agence_id;
        }

        // Adhérent peut voir ses propres rapports
        if ($user->isAdherent() && $user->adherent) {
            return $rapport->adherent_id === $user->adherent->id;
        }

        return false;
    }

    /**
     * Déterminer si l'utilisateur peut créer un rapport
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent() || $user->isChefService();
    }

    /**
     * Déterminer si l'utilisateur peut mettre à jour un rapport
     */
    public function update(User $user, Rapport $rapport): bool
    {
        // Admin peut mettre à jour
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut mettre à jour les rapports de son agence
        if ($user->isAgent() && $user->agence_id) {
            return $rapport->adherent->agence_id === $user->agence_id;
        }

        return false;
    }

    /**
     * Déterminer si l'utilisateur peut supprimer un rapport
     */
    public function delete(User $user, Rapport $rapport): bool
    {
        return $user->isAdmin() || ($user->isAgent() && $user->agence_id === $rapport->adherent->agence_id);
    }

    /**
     * Déterminer si l'utilisateur peut télécharger un rapport
     */
    public function download(User $user, Rapport $rapport): bool
    {
        return $this->view($user, $rapport);
    }
}
```

---

## 🛣️ Étape 4: Ajouter les routes

### Dans `/routes/web.php`

```php
// Routes pour les rapports (Admin/Agent/Chef de service)
Route::middleware(['auth', 'role:admin,agent,chef_service'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('rapports', RapportController::class);
    Route::post('rapports/{rapport}/regenerate', [RapportController::class, 'regenerate'])
        ->middleware('can:update,rapport')
        ->name('rapports.regenerate');
    Route::get('rapports/{rapport}/download', [RapportController::class, 'download'])
        ->middleware('can:download,rapport')
        ->name('rapports.download');
});

// Routes pour les rapports (Adhérent)
Route::middleware(['auth', 'role:adherent'])->prefix('adherent')->name('adherent.')->group(function () {
    Route::get('rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('rapports/{rapport}', [RapportController::class, 'show'])
        ->middleware('can:view,rapport')
        ->name('rapports.show');
    Route::get('rapports/{rapport}/download', [RapportController::class, 'download'])
        ->middleware('can:download,rapport')
        ->name('rapports.download');
});
```

---

## 🎨 Étape 5: Créer les vues

### Structure
```
resources/views/rapports/
├── index.blade.php
├── create.blade.php
├── edit.blade.php
└── show.blade.php
```

### `resources/views/rapports/index.blade.php`
```blade
@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Rapports d'adhérents</h1>
        </div>
        <div class="col-md-4 text-end">
            @can('create', App\Models\Rapport::class)
                <a href="{{ route('admin.rapports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un rapport
                </a>
            @endcan
        </div>
    </div>

    @if($rapports->count())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Adhérent</th>
                        <th>Type</th>
                        <th>Période</th>
                        <th>Statut</th>
                        <th>Créé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rapports as $rapport)
                        <tr>
                            <td>{{ $rapport->adherent->nom_complet }}</td>
                            <td>{{ $rapport->type_formattee }}</td>
                            <td>{{ $rapport->date_debut->format('d/m/Y') }} - {{ $rapport->date_fin->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $rapport->statut_badge }}">
                                    {{ ucfirst($rapport->statut) }}
                                </span>
                            </td>
                            <td>{{ $rapport->createdBy->name }}</td>
                            <td>
                                <a href="{{ route('admin.rapports.show', $rapport) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($rapport->isGenere())
                                    <a href="{{ route('admin.rapports.download', $rapport) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                                @can('update', $rapport)
                                    <a href="{{ route('admin.rapports.edit', $rapport) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('delete', $rapport)
                                    <form action="{{ route('admin.rapports.destroy', $rapport) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $rapports->links() }}
    @else
        <div class="alert alert-info">
            Aucun rapport trouvé.
        </div>
    @endif
</div>
@endsection
```

---

## 🔔 Étape 6: Ajouter les notifications

### Dans `app/Services/NotificationService.php`

```php
/**
 * Notification pour rapport généré
 */
public static function rapportGenere($adherentId, $typeRapport, $agentNom = null)
{
    $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
    
    self::creerNotification(
        $adherentId,
        '📊 Rapport généré',
        "Votre {$typeRapport} a été généré par {$agent}. Vous pouvez le télécharger.",
        'success'
    );
}

/**
 * Notification pour erreur de génération
 */
public static function rapportErreur($adherentId, $typeRapport, $motif = '')
{
    $messageMotif = $motif ? " Motif : {$motif}" : '';
    
    self::creerNotification(
        $adherentId,
        '❌ Erreur de génération',
        "La génération de votre {$typeRapport} a échoué.{$messageMotif}",
        'error'
    );
}
```

---

## 📝 Étape 7: Ajouter l'audit

L'audit est automatique grâce au trait `Auditable` utilisé dans le modèle `Rapport`.

Ajouter la catégorie dans `app/Traits/Auditable.php`:

```php
protected static function determineActionCategory($modelClass)
{
    $baseName = class_basename($modelClass);
    
    $categoryMap = [
        // ... existant ...
        'Rapport' => 'rapport', // Ajouter cette ligne
    ];
    
    return $categoryMap[$baseName] ?? 'autre';
}
```

---

## 🎯 Résumé

Vous avez maintenant un module complet de rapports qui:
- ✅ Crée des rapports pour les adhérents
- ✅ Gère les permissions par rôle
- ✅ Génère les rapports en arrière-plan
- ✅ Permet le téléchargement
- ✅ Enregistre les actions dans l'audit
- ✅ Envoie des notifications
- ✅ Suit les conventions du projet

---

**Dernière mise à jour**: Décembre 2025
