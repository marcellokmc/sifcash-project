# Système de Permissions

## 📊 Vue d'ensemble

Le système compte **123 permissions** réparties entre les différents rôles.

## 👑 Administrateur (Admin)
**Total : 123 permissions** - Accès complet à toutes les fonctionnalités

L'administrateur a tous les droits sur l'application, incluant :

### Gestion des utilisateurs
- ✅ Voir, créer, modifier, supprimer des utilisateurs
- ✅ Voir les activités et logs des utilisateurs
- ✅ Activer/Désactiver des utilisateurs
- ✅ **Réinitialiser les mots de passe**
- ✅ **Suspendre des utilisateurs**
- ✅ **Réactiver des utilisateurs**

### Gestion des adhérents
- ✅ Voir, créer, modifier, supprimer des adhérents
- ✅ Valider et activer/désactiver des adhérents
- ✅ **Réinitialiser les mots de passe**
- ✅ **Suspendre des adhérents**
- ✅ **Réactiver des adhérents**
- ✅ **Gérer les affectations agence/agent**

### Gestion des rôles et permissions
- ✅ Voir, créer, modifier, supprimer des rôles
- ✅ Voir, créer, modifier, supprimer des permissions
- ✅ Assigner des rôles et gérer les permissions

### Documents et ayants droit
- ✅ Gestion complète des ayants droit
- ✅ Upload, validation, téléchargement de documents
- ✅ Gestion des types de documents

### Plans et adhésions
- ✅ Gestion complète des plans d'adhésion
- ✅ Création, validation, renouvellement des adhésions

### Paiements
- ✅ Voir, créer, modifier, supprimer des paiements
- ✅ Valider, rejeter, rembourser des paiements
- ✅ Télécharger les preuves de paiement

### Crédits
- ✅ Gestion complète des crédits
- ✅ Approuver, rejeter, valider des demandes
- ✅ Voir les échéanciers et enregistrer les paiements
- ✅ Exporter les contrats et documents

### Épargnes
- ✅ Créer, modifier, supprimer des comptes épargne
- ✅ Effectuer dépôts et retraits
- ✅ Calculer les intérêts
- ✅ Exporter les données

### Retraits
- ✅ Valider, rejeter, traiter les demandes de retrait
- ✅ Gérer les pénalités de retrait anticipé

### Agences
- ✅ Créer, modifier, supprimer des agences
- ✅ Activer/Désactiver des agences

### Notifications
- ✅ Créer et gérer les notifications
- ✅ Marquer comme lues, supprimer

### Audits et Logs
- ✅ Voir tous les journaux d'audit
- ✅ Consulter les logs système et de connexion
- ✅ Exporter les logs

### Rapports et Statistiques
- ✅ Accès au dashboard complet
- ✅ Voir et exporter tous les rapports
- ✅ Accès aux statistiques globales

### Paramètres
- ✅ Voir et modifier les paramètres système

---

## 🎯 Agent
**Total : 22 permissions** - Gestion des adhérents et opérations courantes

### Permissions principales
- Voir et créer des adhérents
- Gérer les ayants droit
- Valider les documents
- Créer des adhésions
- Enregistrer des paiements
- Créer des demandes de crédit
- Accès au dashboard basique

---

## 👔 Chef de Service
**Total : 46 permissions** - Supervision et gestion étendue

### Permissions principales
- Gestion des utilisateurs (création, modification, activation)
- Gestion complète des adhérents
- Validation des documents et paiements
- Approbation des crédits
- Traitement des retraits
- Accès aux rapports et statistiques
- Consultation des logs de connexion

---

## 🔍 Superviseur
**Total : 23 permissions** - Validation et supervision

### Permissions principales
- Validation des adhérents et documents
- Validation des paiements et adhésions
- Approbation et rejet des crédits
- Validation des retraits
- Accès aux audits et logs
- Consultation des rapports

---

## 💰 Comptable
**Total : 30 permissions** - Gestion financière

### Permissions principales
- Consultation des adhérents
- Gestion complète des paiements
- Enregistrement des paiements de crédit
- Traitement des retraits
- Gestion complète des épargnes (dépôts, retraits, intérêts)
- Export des données financières
- Accès aux rapports financiers

---

## 🔄 Mise à jour des permissions

Pour mettre à jour les permissions dans la base de données :

```bash
# Créer les permissions
php artisan db:seed --class=PermissionSeeder

# Assigner les permissions aux rôles
php artisan db:seed --class=RolePermissionSeeder
```

## 🆕 Nouvelles permissions ajoutées

### Gestion de compte (24/10/2025)
- `reset_user_password` - Réinitialiser mot de passe utilisateur
- `suspend_users` - Suspendre des utilisateurs
- `activate_users` - Réactiver des utilisateurs
- `reset_adherent_password` - Réinitialiser mot de passe adhérent
- `suspend_adherents` - Suspendre des adhérents
- `activate_adherents` - Réactiver des adhérents
- `manage_adherent_affectation` - Gérer affectation agence/agent

## 📝 Vérification des permissions

Pour vérifier les permissions d'un utilisateur :

```php
// Dans les contrôleurs
if (auth()->user()->hasPermission('create_users')) {
    // Action autorisée
}

// Dans les vues Blade
@can('create_users')
    <!-- Contenu visible uniquement avec permission -->
@endcan

// Avec les policies
Gate::authorize('update', $user);
```

## 🎨 Middleware de permission

Protéger les routes avec des permissions :

```php
Route::middleware(['permission:create_users'])->group(function () {
    // Routes nécessitant la permission
});
```

---

**Dernière mise à jour:** 24 octobre 2025  
**Version:** 1.1.0
