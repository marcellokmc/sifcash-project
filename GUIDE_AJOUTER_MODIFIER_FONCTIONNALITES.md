# 🛠️ GUIDE POUR AJOUTER/MODIFIER DES FONCTIONNALITÉS

## 📋 Table des matières
1. [Ajouter un nouveau modèle](#ajouter-un-nouveau-modèle)
2. [Ajouter un contrôleur](#ajouter-un-contrôleur)
3. [Ajouter une policy d'autorisation](#ajouter-une-policy)
4. [Ajouter des routes](#ajouter-des-routes)
5. [Ajouter des vues](#ajouter-des-vues)
6. [Ajouter des notifications](#ajouter-des-notifications)
7. [Ajouter de l'audit](#ajouter-de-laudit)
8. [Exemples pratiques](#exemples-pratiques)

---

## 🎯 Ajouter un nouveau modèle

### Étape 1: Créer le modèle et la migration
```bash
php artisan make:model NomModele -m
```

### Étape 2: Définir la migration
**Fichier**: `database/migrations/YYYY_MM_DD_HHMMSS_create_nom_modeles_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nom_modeles', function (Blueprint $table) {
            $table->id();
            
            // Clés étrangères
            $table->foreignId('adherent_id')->constrained('adherents')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Colonnes métier
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('montant', 12, 2)->default(0);
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Si suppression logique
            
            // Index
            $table->index('adherent_id');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nom_modeles');
    }
};
```

### Étape 3: Définir le modèle
**Fichier**: `app/Models/NomModele.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable; // Si audit nécessaire

class NomModele extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'adherent_id',
        'user_id',
        'nom',
        'description',
        'montant',
        'statut',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONS ====================
    
    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ==================== SCOPES ====================
    
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeParAdherent($query, $adherentId)
    {
        return $query->where('adherent_id', $adherentId);
    }

    // ==================== ACCESSEURS ====================
    
    public function getMontantFormatteAttribute()
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }

    // ==================== MUTATEURS ====================
    
    // ==================== MÉTHODES ====================
    
    public function isActif()
    {
        return $this->statut === 'actif';
    }
}
```

### Étape 4: Exécuter la migration
```bash
php artisan migrate
```

---

## 🎮 Ajouter un contrôleur

### Créer le contrôleur
```bash
php artisan make:controller NomModeleController -r
```

### Implémenter le contrôleur
**Fichier**: `app/Http/Controllers/NomModeleController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\NomModele;
use App\Models\Adherent;
use Illuminate\Http\Request;

class NomModeleController extends Controller
{
    /**
     * Afficher la liste des ressources
     */
    public function index()
    {
        $this->authorize('viewAny', NomModele::class);
        
        $nomModeles = NomModele::with('adherent', 'user')
            ->paginate(15);
        
        return view('nom-modeles.index', compact('nomModeles'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $this->authorize('create', NomModele::class);
        
        $adherents = Adherent::where('statut_compte', 'actif')->get();
        
        return view('nom-modeles.create', compact('adherents'));
    }

    /**
     * Stocker une nouvelle ressource
     */
    public function store(Request $request)
    {
        $this->authorize('create', NomModele::class);
        
        $validated = $request->validate([
            'adherent_id' => 'required|exists:adherents,id',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,inactif,suspendu',
        ]);

        $nomModele = NomModele::create($validated);

        return redirect()
            ->route('nom-modeles.show', $nomModele)
            ->with('success', 'Ressource créée avec succès');
    }

    /**
     * Afficher une ressource
     */
    public function show(NomModele $nomModele)
    {
        $this->authorize('view', $nomModele);
        
        return view('nom-modeles.show', compact('nomModele'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(NomModele $nomModele)
    {
        $this->authorize('update', $nomModele);
        
        $adherents = Adherent::where('statut_compte', 'actif')->get();
        
        return view('nom-modeles.edit', compact('nomModele', 'adherents'));
    }

    /**
     * Mettre à jour une ressource
     */
    public function update(Request $request, NomModele $nomModele)
    {
        $this->authorize('update', $nomModele);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,inactif,suspendu',
        ]);

        $nomModele->update($validated);

        return redirect()
            ->route('nom-modeles.show', $nomModele)
            ->with('success', 'Ressource mise à jour avec succès');
    }

    /**
     * Supprimer une ressource
     */
    public function destroy(NomModele $nomModele)
    {
        $this->authorize('delete', $nomModele);
        
        $nomModele->delete();

        return redirect()
            ->route('nom-modeles.index')
            ->with('success', 'Ressource supprimée avec succès');
    }
}
```

---

## 🔐 Ajouter une Policy

### Créer la policy
```bash
php artisan make:policy NomModelePolicy --model=NomModele
```

### Implémenter la policy
**Fichier**: `app/Policies/NomModelePolicy.php`

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NomModele;

class NomModelePolicy
{
    /**
     * Déterminer si l'utilisateur peut voir n'importe quelle ressource
     */
    public function viewAny(User $user): bool
    {
        // Admin et agents peuvent voir
        return $user->isAdmin() || $user->isAgent() || $user->isChefService();
    }

    /**
     * Déterminer si l'utilisateur peut voir une ressource
     */
    public function view(User $user, NomModele $nomModele): bool
    {
        // Admin peut voir tout
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut voir les ressources de son agence
        if ($user->isAgent() && $user->agence_id) {
            return $nomModele->adherent->agence_id === $user->agence_id;
        }

        // Adhérent peut voir ses propres ressources
        if ($user->isAdherent() && $user->adherent) {
            return $nomModele->adherent_id === $user->adherent->id;
        }

        return false;
    }

    /**
     * Déterminer si l'utilisateur peut créer une ressource
     */
    public function create(User $user): bool
    {
        // Admin peut créer
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut créer pour ses adhérents
        if ($user->isAgent()) {
            return true;
        }

        // Adhérent peut créer pour lui-même
        if ($user->isAdherent() && $user->adherent) {
            return true;
        }

        return false;
    }

    /**
     * Déterminer si l'utilisateur peut mettre à jour une ressource
     */
    public function update(User $user, NomModele $nomModele): bool
    {
        // Admin peut mettre à jour
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut mettre à jour les ressources de son agence
        if ($user->isAgent() && $user->agence_id) {
            return $nomModele->adherent->agence_id === $user->agence_id;
        }

        // Adhérent peut mettre à jour ses propres ressources
        if ($user->isAdherent() && $user->adherent) {
            return $nomModele->adherent_id === $user->adherent->id;
        }

        return false;
    }

    /**
     * Déterminer si l'utilisateur peut supprimer une ressource
     */
    public function delete(User $user, NomModele $nomModele): bool
    {
        // Admin peut supprimer
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut supprimer les ressources de son agence
        if ($user->isAgent() && $user->agence_id) {
            return $nomModele->adherent->agence_id === $user->agence_id;
        }

        return false;
    }
}
```

---

## 🛣️ Ajouter des routes

### Dans `/routes/web.php`

```php
// Routes pour les adhérents
Route::middleware(['auth', 'role:adherent'])->prefix('adherent')->name('adherent.')->group(function () {
    Route::resource('nom-modeles', NomModeleController::class)->except(['show']);
    Route::get('nom-modeles/{nomModele}', [NomModeleController::class, 'show'])
        ->middleware('can:view,nomModele')
        ->name('nom-modeles.show');
});

// Routes pour l'admin/backoffice
Route::middleware(['auth', 'role:admin,agent,chef_service'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('nom-modeles', NomModeleController::class);
    Route::post('nom-modeles/{nomModele}/activate', [NomModeleController::class, 'activate'])
        ->name('nom-modeles.activate');
    Route::post('nom-modeles/{nomModele}/deactivate', [NomModeleController::class, 'deactivate'])
        ->name('nom-modeles.deactivate');
});
```

---

## 🎨 Ajouter des vues

### Structure des dossiers
```
resources/views/
├── nom-modeles/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
```

### Exemple: `index.blade.php`
```blade
@extends('layouts.app')

@section('title', 'Gestion des Ressources')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Ressources</h1>
        </div>
        <div class="col-md-4 text-end">
            @can('create', App\Models\NomModele::class)
                <a href="{{ route('nom-modeles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter
                </a>
            @endcan
        </div>
    </div>

    @if($nomModeles->count())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Adhérent</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nomModeles as $nomModele)
                        <tr>
                            <td>{{ $nomModele->nom }}</td>
                            <td>{{ $nomModele->adherent->nom_complet }}</td>
                            <td>{{ $nomModele->montant_formattee }}</td>
                            <td>
                                <span class="badge bg-{{ $nomModele->isActif() ? 'success' : 'danger' }}">
                                    {{ ucfirst($nomModele->statut) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('nom-modeles.show', $nomModele) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('update', $nomModele)
                                    <a href="{{ route('nom-modeles.edit', $nomModele) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('delete', $nomModele)
                                    <form action="{{ route('nom-modeles.destroy', $nomModele) }}" method="POST" style="display:inline;">
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

        {{ $nomModeles->links() }}
    @else
        <div class="alert alert-info">
            Aucune ressource trouvée.
        </div>
    @endif
</div>
@endsection
```

---

## 🔔 Ajouter des notifications

### Dans le contrôleur
```php
use App\Services\NotificationService;

public function store(Request $request)
{
    // ... validation et création ...
    
    // Créer une notification
    NotificationService::infoGenerale(
        $adherent->user_id,
        '✅ Ressource créée',
        'Votre ressource a été créée avec succès.',
        'success'
    );
    
    return redirect()->with('success', 'Ressource créée');
}
```

### Ajouter une méthode personnalisée dans `NotificationService`
**Fichier**: `app/Services/NotificationService.php`

```php
/**
 * Notification pour création de ressource
 */
public static function ressourceCreee($adherentId, $nomRessource, $agentNom = null)
{
    $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
    
    self::creerNotification(
        $adherentId,
        '✅ ' . $nomRessource . ' créée',
        "Votre {$nomRessource} a été créée par {$agent}.",
        'success'
    );
}
```

---

## 📝 Ajouter de l'audit

### Utiliser le trait Auditable
```php
class NomModele extends Model
{
    use HasFactory, Auditable;
    // ...
}
```

### Ajouter la catégorie dans le trait
**Fichier**: `app/Traits/Auditable.php`

```php
protected static function determineActionCategory($modelClass)
{
    $baseName = class_basename($modelClass);
    
    $categoryMap = [
        // ... existant ...
        'NomModele' => 'nom_modele', // Ajouter cette ligne
    ];
    
    return $categoryMap[$baseName] ?? 'autre';
}
```

### Audit personnalisé
```php
// Dans le contrôleur
$nomModele->audit('action_personnalisee', 'Description de l\'action');
```

---

## 💡 Exemples pratiques

### Exemple 1: Ajouter un nouveau type de paiement

**1. Créer le modèle**
```bash
php artisan make:model TypePaiement -m
```

**2. Migration**
```php
Schema::create('type_paiements', function (Blueprint $table) {
    $table->id();
    $table->string('nom');
    $table->text('description')->nullable();
    $table->enum('statut', ['actif', 'inactif'])->default('actif');
    $table->timestamps();
});
```

**3. Modèle**
```php
class TypePaiement extends Model
{
    use HasFactory;
    
    protected $fillable = ['nom', 'description', 'statut'];
    
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
```

**4. Contrôleur**
```bash
php artisan make:controller TypePaiementController -r
```

**5. Policy**
```bash
php artisan make:policy TypePaiementPolicy --model=TypePaiement
```

**6. Routes**
```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('type-paiements', TypePaiementController::class);
});
```

### Exemple 2: Ajouter une action personnalisée

**Dans le contrôleur**
```php
public function valider(Request $request, NomModele $nomModele)
{
    $this->authorize('update', $nomModele);
    
    $nomModele->update(['statut' => 'validé']);
    
    // Audit personnalisé
    $nomModele->audit('validé', 'Ressource validée par ' . Auth::user()->name);
    
    // Notification
    NotificationService::infoGenerale(
        $nomModele->adherent->user_id,
        '✅ Ressource validée',
        'Votre ressource a été validée.'
    );
    
    return redirect()->back()->with('success', 'Ressource validée');
}
```

**Dans les routes**
```php
Route::post('nom-modeles/{nomModele}/valider', [NomModeleController::class, 'valider'])
    ->middleware('can:update,nomModele')
    ->name('nom-modeles.valider');
```

---

## ✅ Checklist pour ajouter une fonctionnalité

- [ ] Créer le modèle et la migration
- [ ] Exécuter la migration
- [ ] Créer le contrôleur
- [ ] Créer la policy
- [ ] Ajouter les routes
- [ ] Créer les vues
- [ ] Ajouter les notifications (si applicable)
- [ ] Ajouter l'audit (si applicable)
- [ ] Tester les autorisations
- [ ] Tester les validations
- [ ] Documenter les changements

---

**Dernière mise à jour**: Décembre 2025
