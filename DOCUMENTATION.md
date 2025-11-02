# Documentation SIF-Project

## Table des matières

- [Système de Permissions](#système-de-permissions)
- [Guide Mobile-First](#guide-mobile-first)
- [Changement de Mot de Passe](#changement-de-mot-de-passe)
- [Tests](#tests)

---

## Système de Permissions

### Rôles et Permissions

**Admin** : 123 permissions - Accès complet
- Gestion complète des utilisateurs, adhérents, rôles, permissions
- Toutes les opérations financières
- Accès aux audits et rapports

**Chef de Service / Superviseur** : 46 permissions
- Gestion limitée à son agence (`agence_id`)
- Validation des documents, paiements, crédits
- Accès aux rapports de son agence

**Agent** : 22 permissions
- Gestion des adhérents affectés uniquement
- Création d'adhésions et paiements
- Pas de validation

**Comptable** : 30 permissions
- Gestion financière complète
- Validation des paiements
- Gestion des épargnes

### Utilisation

```php
// Vérifier permission
if (auth()->user()->hasPermissionTo('view_users')) { }

// Dans les vues
@can('view_users')
    <!-- Contenu -->
@endcan

// Middleware
Route::middleware(['permission:create_users'])->group(function () { });
```

### Commandes

```bash
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=RolePermissionSeeder
php artisan cache:clear
```

---

## Guide Mobile-First

### Breakpoints

- **Mobile** : < 768px
- **Tablet** : 768px - 991px
- **Desktop 15"** : 992px - 1399px ⭐
- **Large** : ≥ 1400px

### Classes CSS principales

**Cards** : `.sif-card-mobile`
**Tables** : `.table-responsive-mobile`, `.table-mobile`
**Formulaires** : `.form-mobile`
**Boutons** : `.btn-mobile`, `.btn-group-mobile`
**Visibilité** : `.visible-mobile`, `.visible-desktop`, `.hide-mobile`
**Texte** : `.text-mobile-xs` à `.text-mobile-lg`

### Template de base

```blade
@extends('layouts.adherent-modern')

@section('content')
<div class="container-fluid">
    <div class="row mb-mobile-3">
        <div class="col-12">
            <div class="card sif-card-mobile">
                <div class="card-body p-mobile-2">
                    <!-- Contenu -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## Changement de Mot de Passe

### Fonctionnalités

- ✅ Pas besoin de l'ancien mot de passe
- ✅ Indicateur de force en temps réel
- ✅ Validation frontend et backend
- ✅ Boutons afficher/masquer

### Routes

```
GET /adherent/password/edit - adherent.password.edit
PUT /adherent/password/update - adherent.password.update
```

### Validation

- Minimum 8 caractères
- Confirmation obligatoire
- Hash bcrypt automatique

### Indicateur de force

- 🔴 Faible (<30%)
- 🟡 Moyen (30-60%)
- 🔵 Bon (60-80%)
- 🟢 Excellent (>80%)

---

## Tests

### Configuration

Framework de test : **Cypress**

```bash
# Ouvrir Cypress
npm run cypress:open

# Exécuter les tests
npm run test:e2e

# Tests composants
npm run test:component
```

### Tests disponibles

- `cypress/e2e/auth.cy.js` - Tests d'authentification
- `cypress/e2e/pwa.cy.js` - Tests PWA

---

## Commandes de build

```bash
# Développement
npm run dev

# Production
npm run build

# Build optimisé avec critical CSS
npm run build:optimized

# Analyse des bundles
npm run build:analyze
```

---

**Version** : 1.0.0
**Dernière mise à jour** : Novembre 2025
