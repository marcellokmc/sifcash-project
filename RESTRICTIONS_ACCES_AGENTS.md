# Restrictions d'accès pour les agents

## Vue d'ensemble

Les agents et chefs de service ont maintenant un accès **restreint** aux données. Ils ne peuvent voir et gérer que les ressources liées aux **adhérents qui leur sont affectés**.

## Modifications apportées

### 1. Création du trait `FiltersByAgentAdherents`

**Fichier:** `app/Traits/FiltersByAgentAdherents.php`

Ce trait réutilisable contient des méthodes pour :
- Filtrer automatiquement les requêtes par adhérents affectés
- Vérifier si un agent peut accéder à un adhérent spécifique
- Récupérer la liste des IDs d'adhérents affectés à un agent

**Méthodes principales :**
- `applyAgentFilter($query, $adherentRelation)` : Applique le filtre sur une requête
- `getAgentAdherentIds()` : Retourne les IDs des adhérents affectés
- `canAccessAdherent($adherentId)` : Vérifie l'accès à un adhérent

### 2. Filtrage des contrôleurs

Les contrôleurs suivants ont été modifiés pour appliquer le filtrage par agent :

#### a. **AdherentController**
- ✅ Les agents ne voient que leurs adhérents affectés
- ✅ Le middleware `filter.adherents.by.agent` est appliqué sur les routes

#### b. **CreditController**
- ✅ Les agents ne voient que les crédits de leurs adhérents
- ✅ Filtrage appliqué dans la méthode `index()`

#### c. **EpargneController**
- ✅ Les agents ne voient que les épargnes de leurs adhérents
- ✅ Filtrage appliqué dans la méthode `index()`
- ✅ La liste des adhérents dans les filtres est également restreinte

#### d. **AdhesionController**
- ✅ Les agents ne voient que les adhésions de leurs adhérents
- ✅ Filtrage appliqué dans la méthode `index()`

#### e. **PaiementController**
- ✅ Les agents ne voient que les paiements de leurs adhérents
- ✅ Filtrage appliqué dans la méthode `index()`

#### f. **DemandeRetraitController**
- ✅ Les agents ne voient que les demandes de retrait de leurs adhérents
- ✅ Filtrage appliqué dans la méthode `index()`

#### g. **NotificationController**
- ✅ Les agents ne voient que les notifications des utilisateurs liés à leurs adhérents
- ✅ Filtrage basé sur la relation user_id des adhérents affectés

### 3. Restrictions d'accès aux routes

#### **Affectations** - Accès bloqué pour les agents
Routes restreintes aux rôles `admin` et `chef_service` uniquement :
- `/admin/affectations` - Liste des affectations
- `/admin/affectations/affecter-masse` - Affectation en masse
- `/admin/affectations/retirer-agent` - Retirer un agent
- `/admin/affectations/par-agence` - Vue par agence
- `/admin/affectations/par-agent` - Vue par agent
- `/admin/affectations/non-affectes` - Adhérents non affectés

#### **Audits et Logs** - Accès bloqué pour les agents
Routes restreintes aux rôles `admin` et `chef_service` uniquement :
- `/admin/audit` - Liste des audits
- `/admin/audit/stats` - Statistiques d'audit
- `/admin/audit/export` - Export des audits
- `/admin/logs/connexions` - Logs de connexion
- `/admin/logs/connexions/export` - Export des logs

### 4. Middleware `FilterAdherentsByAgent`

**Fichier:** `app/Http/Middleware/FilterAdherentsByAgent.php`
**Alias:** `filter.adherents.by.agent`

Ce middleware :
- Détecte si l'utilisateur est un agent ou chef de service
- Ajoute `agent_filter` dans la requête avec l'ID de l'agent
- Est appliqué sur les routes de gestion des adhérents

## Comment ça fonctionne

### Pour les Administrateurs
- ✅ Accès complet à toutes les données
- ✅ Aucun filtrage appliqué
- ✅ Peuvent gérer les affectations
- ✅ Accès aux audits et logs

### Pour les Chefs de Service
- ✅ Ne voient que les données des adhérents de leur agence (si implémenté)
- ✅ Peuvent gérer les affectations
- ✅ Accès aux audits et logs
- ⚠️ **Note:** Le filtrage par agence peut être ajouté si nécessaire

### Pour les Agents
- ✅ Ne voient QUE les adhérents qui leur sont affectés
- ✅ Ne peuvent gérer QUE les ressources (crédits, épargnes, etc.) de leurs adhérents
- ✅ Reçoivent UNIQUEMENT les notifications liées à leurs adhérents
- ❌ **Pas d'accès** aux pages d'affectation
- ❌ **Pas d'accès** aux audits et logs
- ❌ **Pas d'accès** aux adhérents non affectés

## Flux de travail pour un agent

1. **Connexion** : L'agent se connecte avec son compte
2. **Dashboard** : Voit uniquement les statistiques de ses adhérents
3. **Adhérents** : Liste filtrée de ses adhérents affectés uniquement
4. **Crédits** : Voit/gère les crédits de ses adhérents
5. **Épargnes** : Voit/gère les épargnes de ses adhérents
6. **Adhésions** : Voit/gère les adhésions de ses adhérents
7. **Paiements** : Valide/rejette les paiements de ses adhérents
8. **Retraits** : Traite les demandes de retrait de ses adhérents
9. **Notifications** : Reçoit les notifications pour les événements de ses adhérents

## Table de relation

La relation agent-adhérent est gérée via la table pivot `adherent_agent` :

```sql
adherent_agent
├── adherent_id (FK vers adherents)
├── agent_id (FK vers users)
├── is_principal (boolean)
├── notes (text)
├── affecte_le (timestamp)
├── affecte_par (FK vers users)
└── timestamps
```

## Vérification des accès

Pour vérifier que le filtrage fonctionne :

1. **Connectez-vous en tant qu'agent**
2. **Testez chaque section :**
   - Adhérents : `/admin/adherents`
   - Crédits : `/admin/credits`
   - Épargnes : `/admin/epargnes`
   - Adhésions : `/admin/adhesions`
   - Paiements : `/admin/paiements`
   - Retraits : `/admin/retraits`
   - Notifications : `/admin/notifications`

3. **Tentez d'accéder aux pages interdites :**
   - Affectations : `/admin/affectations` → Devrait être bloqué
   - Audits : `/admin/audit` → Devrait être bloqué
   - Logs : `/admin/logs/connexions` → Devrait être bloqué

## Points importants

### Sécurité
- ✅ Les filtres sont appliqués au niveau du contrôleur (côté serveur)
- ✅ Impossible de contourner via l'URL
- ✅ Les policies Laravel vérifient également les autorisations

### Performance
- ✅ Les requêtes sont optimisées avec `whereHas`
- ✅ Pas de chargement de données inutiles
- ✅ Utilisation des index sur la table `adherent_agent`

### Notifications
Les agents reçoivent des notifications pour :
- ✅ Nouvelle demande de crédit d'un de leurs adhérents
- ✅ Nouveau paiement soumis par un de leurs adhérents
- ✅ Nouvelle demande de retrait d'un de leurs adhérents
- ✅ Validation/rejet de documents d'un de leurs adhérents
- ❌ PAS de notifications pour les adhérents non affectés

## Commandes à exécuter après modification

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## Fichiers modifiés

### Nouveaux fichiers
- `app/Traits/FiltersByAgentAdherents.php`

### Fichiers modifiés
- `app/Http/Controllers/CreditController.php`
- `app/Http/Controllers/EpargneController.php`
- `app/Http/Controllers/AdhesionController.php`
- `app/Http/Controllers/PaiementController.php`
- `app/Http/Controllers/DemandeRetraitController.php`
- `app/Http/Controllers/NotificationController.php`
- `bootstrap/app.php`
- `routes/web.php`

## Tests recommandés

### Test 1 : Connexion agent
```
1. Se connecter avec un compte agent
2. Vérifier que seuls les adhérents affectés apparaissent
3. Tenter d'accéder à un adhérent non affecté via URL directe
   → Devrait être bloqué ou retourner 404
```

### Test 2 : Gestion des crédits
```
1. Agent voit uniquement les crédits de ses adhérents
2. Peut approuver/rejeter ces crédits
3. Ne voit pas les crédits des autres adhérents
```

### Test 3 : Notifications
```
1. Adhérent affecté soumet une demande de crédit
   → Agent reçoit une notification
2. Adhérent non affecté soumet une demande
   → Agent ne reçoit PAS de notification
```

### Test 4 : Accès aux affectations
```
1. Agent tente d'accéder à /admin/affectations
   → Erreur 403 (Accès refusé)
2. Admin/Chef de service accède à /admin/affectations
   → Accès autorisé
```

## Support et maintenance

En cas de problème :
1. Vérifier que les caches sont effacés
2. Vérifier que l'agent a bien des adhérents affectés dans `adherent_agent`
3. Vérifier les logs Laravel : `storage/logs/laravel.log`
4. S'assurer que le rôle de l'utilisateur est correctement défini

## Évolutions futures possibles

- [ ] Filtrage par agence pour les chefs de service
- [ ] Statistiques personnalisées par agent
- [ ] Dashboard agent avec KPI de ses adhérents
- [ ] Export filtré pour les agents
- [ ] Historique des actions par agent
