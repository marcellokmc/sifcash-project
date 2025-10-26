# Gestion des Comptes Utilisateurs et Adhérents

## 📋 Vue d'ensemble

L'administrateur peut désormais gérer complètement les comptes utilisateurs (agents, superviseurs, chefs de service) et les comptes adhérents avec les fonctionnalités suivantes :

### ✨ Fonctionnalités disponibles

1. **Réinitialisation de mot de passe**
   - Génération automatique d'un mot de passe aléatoire sécurisé (12 caractères)
   - Affichage du mot de passe avec possibilité de copie
   - Option d'envoi par email (si configuré)
   - Historique de l'action

2. **Suspension de compte**
   - Blocage de l'accès à la plateforme
   - Raison obligatoire pour la suspension
   - Traçabilité complète (qui, quand, pourquoi)
   - Désactivation automatique du compte

3. **Réactivation de compte**
   - Restauration complète de l'accès
   - Affichage de l'historique de suspension
   - Suppression des données de suspension

## 🗄️ Structure de la base de données

### Table `users`
```sql
suspended_at TIMESTAMP NULL          -- Date de suspension
suspension_reason VARCHAR NULL       -- Raison de la suspension
suspended_by BIGINT UNSIGNED NULL    -- ID de l'admin qui a suspendu
```

### Table `adherents`
```sql
suspended_at TIMESTAMP NULL          -- Date de suspension
suspension_reason VARCHAR NULL       -- Raison de la suspension
suspended_by BIGINT UNSIGNED NULL    -- ID de l'admin qui a suspendu
```

## 🎯 Modèles

### User Model
Nouvelles méthodes :
- `suspend($reason, $suspendedBy)` - Suspendre le compte
- `activate()` - Réactiver le compte
- `isSuspended()` - Vérifier si suspendu
- `isActive()` - Vérifier si actif (non suspendu)
- `suspendedByUser()` - Relation avec l'admin suspenseur

### Adherent Model
Nouvelles méthodes :
- `suspend($reason, $suspendedBy)` - Suspendre le compte (+ compte user lié)
- `activate()` - Réactiver le compte (+ compte user lié)
- `isSuspended()` - Vérifier si suspendu
- `suspendedByUser()` - Relation avec l'admin suspenseur

## 🛣️ Routes

### Utilisateurs
```php
POST /admin/account-management/users/{user}/reset-password
POST /admin/account-management/users/{user}/suspend
POST /admin/account-management/users/{user}/activate
```

### Adhérents
```php
POST /admin/account-management/adherents/{adherent}/reset-password
POST /admin/account-management/adherents/{adherent}/suspend
POST /admin/account-management/adherents/{adherent}/activate
```

## 🎨 Interface Utilisateur

### Page Utilisateur (`/admin/users/{user}`)
Nouvelle carte "Gestion du Compte" avec :
- Bouton "Réinitialiser le mot de passe"
- Bouton "Suspendre le compte" (si actif et non admin)
- Bouton "Réactiver le compte" (si suspendu)
- Affichage de l'état de suspension avec détails

### Page Adhérent (`/admin/adherents/{adherent}`)
Boutons dans l'en-tête :
- 🔑 Réinitialiser mot de passe
- 🚫 Suspendre le compte (si actif)
- ✅ Réactiver (si suspendu)

### Modals

#### Modal Réinitialisation
- Confirmation de l'action
- Option d'envoi par email
- Affichage du nouveau mot de passe généré
- Bouton de copie

#### Modal Suspension
- Champ obligatoire pour la raison
- Message d'avertissement
- Confirmation requise

#### Modal Réactivation
- Affichage des infos de suspension
- Confirmation simple

## 🔐 Sécurité

### Restrictions
- ❌ Un admin ne peut pas suspendre son propre compte
- ❌ Un admin ne peut pas suspendre un autre admin
- ✅ Permissions basées sur les policies Laravel
- ✅ Validation des données en backend
- ✅ Protection CSRF

### Traçabilité
- Enregistrement de qui a effectué l'action
- Date et heure de suspension
- Raison de la suspension
- Historique complet dans les logs

## 📝 Utilisation

### Pour réinitialiser un mot de passe

1. Aller sur la page de l'utilisateur/adhérent
2. Cliquer sur "Réinitialiser le mot de passe"
3. Confirmer l'action
4. Noter le nouveau mot de passe affiché
5. Communiquer le mot de passe à l'utilisateur

### Pour suspendre un compte

1. Aller sur la page de l'utilisateur/adhérent
2. Cliquer sur "Suspendre le compte"
3. Entrer la raison de la suspension
4. Confirmer
5. Le compte est immédiatement bloqué

### Pour réactiver un compte

1. Aller sur la page de l'utilisateur/adhérent suspendu
2. Cliquer sur "Réactiver le compte"
3. Vérifier les informations de suspension
4. Confirmer
5. Le compte est immédiatement réactivé

## 🚀 Améliorations futures possibles

- [ ] Envoi automatique d'emails lors des actions
- [ ] Historique complet des suspensions/réactivations
- [ ] Suspension temporaire avec date d'expiration
- [ ] Notification aux utilisateurs concernés
- [ ] Export des logs de gestion de compte
- [ ] Dashboard des comptes suspendus
- [ ] Statistiques sur les suspensions

## 📱 Responsive Design

Toutes les interfaces sont optimisées pour :
- 💻 Desktop
- 📱 Tablette
- 📱 Mobile

## 🔧 Fichiers modifiés/créés

### Migrations
- `2025_10_24_155813_add_suspension_fields_to_users_table.php`
- `2025_10_24_160042_add_suspension_fields_to_adherents_table.php`

### Modèles
- `app/Models/User.php` - Ajout méthodes de gestion
- `app/Models/Adherent.php` - Ajout méthodes de gestion

### Contrôleurs
- `app/Http/Controllers/Admin/AccountManagementController.php` - Nouveau

### Routes
- `routes/web.php` - Ajout groupe `account-management`

### Vues
- `resources/views/backoffice/partials/account-management-modals.blade.php` - Nouveau
- `resources/views/backoffice/users/show.blade.php` - Modifié
- `resources/views/backoffice/adherents/show.blade.php` - Modifié

## 📞 Support

Pour toute question ou problème, contactez l'équipe de développement.

---

**Dernière mise à jour:** 24 octobre 2025
**Version:** 1.0.0
