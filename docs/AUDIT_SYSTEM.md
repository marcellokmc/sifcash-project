# Système d'Audit Complet - Documentation

## Vue d'ensemble

Le système d'audit amélioré permet à l'administrateur de suivre toutes les activités du système, y compris :
- **Paiements** : Qui a soumis, qui a validé/rejeté
- **Retraits** : Demandes soumises, approuvées ou rejetées
- **Adhésions** : Créations, activations, clôtures
- **Affectations** : Assignations d'agents aux adhérents
- **Validations** : Toutes les actions de validation/approbation/rejet
- **Modifications** : Changements sur tous les modèles audités

## Fonctionnalités principales

### 1. Tracking automatique
Le système capture automatiquement :
- L'utilisateur qui effectue l'action
- L'utilisateur ciblé par l'action (si applicable)
- La catégorie d'action (paiement, retrait, adhésion, etc.)
- Le type d'action (création, modification, suppression, validation, etc.)
- Les anciennes et nouvelles valeurs
- L'adresse IP, l'URL et le User Agent
- Une description lisible de l'action
- La date et l'heure précises

### 2. Interface de consultation avancée

#### Filtres disponibles :
- **Par catégorie** : paiement, retrait, adhésion, affectation, etc.
- **Par utilisateur** : Filtrer par l'auteur de l'action
- **Par utilisateur cible** : Voir les actions effectuées sur un utilisateur spécifique
- **Par type d'action** : création, modification, suppression, validation, approbation, rejet
- **Par type de modèle** : Paiement, DemandeRetrait, Adhesion, etc.
- **Par période** : Date de début et de fin
- **Par recherche** : Recherche dans les descriptions

#### Fonctionnalités :
- **Export CSV** : Exporter les audits filtrés
- **Statistiques** : Vue d'ensemble des activités
- **Détails complets** : Modal avec toutes les informations techniques

### 3. Statistiques

Accessible via `/admin/audit/stats`, affiche :
- Nombre total d'audits
- Audits d'aujourd'hui, cette semaine, ce mois
- Répartition par catégorie d'action
- Top 10 des actions les plus fréquentes
- Top 10 des utilisateurs les plus actifs

### 4. Export CSV

Accessible via `/admin/audit/export`, permet d'exporter :
- Tous les audits ou audits filtrés
- Format CSV avec tous les champs importants
- Nom de fichier avec timestamp

## Configuration technique

### Modèles audités automatiquement

Les modèles suivants utilisent le trait `Auditable` :
- `Paiement`
- `DemandeRetrait`
- `Adhesion`
- `Adherent`

Les événements suivants sont capturés automatiquement :
- `created` : Création d'un enregistrement
- `updated` : Modification d'un enregistrement
- `deleted` : Suppression d'un enregistrement

### Middleware AuditActions

Le middleware capture les actions HTTP importantes :
- POST, PUT, PATCH, DELETE
- Détermine automatiquement la catégorie et la description
- Normalise les noms d'actions pour cohérence

### Champs de la table audits

```sql
- id
- user_id (auteur de l'action)
- target_user_id (utilisateur ciblé)
- action (created, updated, deleted, validated, approved, rejected)
- action_category (paiement, retrait, adhesion, affectation, etc.)
- model_type (classe du modèle)
- model_id (ID du modèle)
- ancienne_valeur (JSON)
- nouvelle_valeur (JSON)
- ip (adresse IP)
- url (URL complète)
- user_agent (navigateur/client)
- description (description lisible)
- created_at (timestamp)
```

## Exemples d'utilisation

### 1. Auditer une action personnalisée

```php
$paiement->audit('validated', 'Paiement validé manuellement par admin', $targetUserId);
```

### 2. Consulter les audits d'un utilisateur

```php
$audits = Audit::where('user_id', $userId)
    ->latest()
    ->paginate(20);
```

### 3. Voir qui a validé un paiement

```php
$audits = Audit::paiements()
    ->validations()
    ->where('model_id', $paiementId)
    ->with('user')
    ->get();
```

### 4. Statistiques par catégorie

```php
$stats = Audit::selectRaw('action_category, COUNT(*) as count')
    ->groupBy('action_category')
    ->pluck('count', 'action_category');
```

## Sécurité et performance

### Sécurité
- Seuls les utilisateurs authentifiés peuvent effectuer des actions auditées
- Les seeders et jobs automatiques ne sont pas audités
- Les mots de passe et tokens sont exclus des audits
- Accès aux audits réservé aux administrateurs

### Performance
- Index sur user_id, model_type, model_id
- Index sur created_at pour les requêtes temporelles
- Index sur action_category pour les filtres
- Pagination activée sur toutes les listes

## Migration

Pour appliquer les améliorations :

```bash
php artisan migrate
```

Cela ajoutera les nouveaux champs :
- `url`
- `user_agent`
- `description`
- `action_category`
- `target_user_id`

## Routes disponibles

- `GET /admin/audit` - Liste des audits
- `GET /admin/audit/stats` - Statistiques
- `GET /admin/audit/export` - Export CSV
- `GET /admin/audit/{audit}` - Détails d'un audit

## Maintenance

### Nettoyage des anciens audits

Pour éviter une croissance excessive de la table audits, envisagez de mettre en place un job de nettoyage :

```php
// Supprimer les audits de plus de 1 an
Audit::where('created_at', '<', now()->subYear())->delete();
```

### Sauvegarde

Sauvegardez régulièrement la table audits, elle contient des informations critiques pour la conformité et la traçabilité.

## Questions fréquentes

**Q: Pourquoi certaines actions ne sont pas auditées ?**
R: Les actions sans utilisateur authentifié (seeders, jobs automatiques) ne sont pas auditées par défaut.

**Q: Comment voir qui a approuvé une demande de retrait ?**
R: Utilisez le filtre "Catégorie: retrait" + "Action: approved" et recherchez l'ID du retrait.

**Q: Les audits peuvent-ils être supprimés ?**
R: Non, il n'y a pas de route de suppression. Seul un administrateur avec accès direct à la base de données peut les supprimer.

**Q: Comment auditer une action personnalisée ?**
R: Utilisez la méthode `audit()` sur le modèle : `$model->audit('custom_action', 'Description', $targetUserId)`.
