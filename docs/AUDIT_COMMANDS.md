# Commandes de gestion des audits

## Nettoyage des audits

### Nettoyer les audits non pertinents
Supprime les audits des notifications API, heartbeat, et autres actions non importantes :

```bash
php artisan audit:clean
```

Cette commande supprime :
- Les audits de `unreadCount` (API notifications)
- Les audits de `api/notifications`
- Les audits de heartbeat
- Les audits génériques avec `model_type = 'n/a'` et `model_id = 0`

**Note** : Les audits importants (show, validate, approve, reject) sont préservés même avec model_type='n/a'.

### Nettoyer les audits anciens
Pour supprimer les audits de plus d'un an (à exécuter manuellement ou via un job planifié) :

```bash
php artisan tinker
```

Puis dans tinker :
```php
// Supprimer les audits de plus d'1 an
$count = \App\Models\Audit::where('created_at', '<', now()->subYear())->delete();
echo "Supprimé {$count} audits anciens";
```

### Nettoyer les audits d'une période spécifique
```bash
php artisan tinker
```

Puis dans tinker :
```php
// Supprimer les audits entre deux dates
$count = \App\Models\Audit::whereBetween('created_at', ['2024-01-01', '2024-12-31'])->delete();
echo "Supprimé {$count} audits";
```

## Statistiques des audits

### Compter les audits par catégorie
```bash
php artisan tinker
```

Puis dans tinker :
```php
$stats = \App\Models\Audit::selectRaw('action_category, COUNT(*) as count')
    ->groupBy('action_category')
    ->pluck('count', 'action_category');
print_r($stats->toArray());
```

### Top 10 des utilisateurs les plus actifs
```php
$top = \App\Models\Audit::selectRaw('user_id, COUNT(*) as count')
    ->with('user:id,name')
    ->groupBy('user_id')
    ->orderByDesc('count')
    ->limit(10)
    ->get();
    
foreach ($top as $item) {
    echo ($item->user->name ?? 'Système') . ": {$item->count} actions\n";
}
```

### Audits des dernières 24h
```php
$count = \App\Models\Audit::where('created_at', '>', now()->subDay())->count();
echo "Audits des dernières 24h: {$count}";
```

## Vérifications

### Vérifier la taille de la table audits
```bash
php artisan tinker
```

Puis dans tinker :
```php
$count = \App\Models\Audit::count();
$size = \DB::select("SELECT 
    table_name AS 'Table', 
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)' 
    FROM information_schema.tables 
    WHERE table_schema = DATABASE() 
    AND table_name = 'audits'");
echo "Nombre d'audits: {$count}\n";
print_r($size);
```

### Vérifier les audits sans description
```php
$count = \App\Models\Audit::whereNull('description')->count();
echo "Audits sans description: {$count}";
```

### Vérifier les audits avec model_type='n/a'
```php
$count = \App\Models\Audit::where('model_type', 'n/a')->count();
echo "Audits génériques: {$count}";
```

## Export des audits

### Exporter tous les audits en CSV via l'interface
URL : `http://127.0.0.1:8000/admin/audit/export`

### Exporter les audits via CLI (pour backup)
```bash
php artisan tinker
```

Puis dans tinker :
```php
$audits = \App\Models\Audit::with(['user', 'targetUser'])->get();
$file = fopen('audits_backup.csv', 'w');
fputcsv($file, ['ID', 'Date', 'Utilisateur', 'Action', 'Catégorie', 'Modèle', 'Model ID', 'Cible', 'Description']);

foreach ($audits as $audit) {
    fputcsv($file, [
        $audit->id,
        $audit->created_at,
        $audit->user->name ?? 'Système',
        $audit->action,
        $audit->action_category,
        $audit->model_type,
        $audit->model_id,
        $audit->targetUser->name ?? '',
        $audit->description,
    ]);
}

fclose($file);
echo "Export terminé : audits_backup.csv";
```

## Planification du nettoyage automatique

Pour planifier un nettoyage automatique, ajouter dans `app/Console/Kernel.php` :

```php
protected function schedule(Schedule $schedule)
{
    // Nettoyer les audits non pertinents tous les jours à 2h du matin
    $schedule->command('audit:clean')->daily()->at('02:00');
    
    // Supprimer les audits de plus d'1 an tous les mois
    $schedule->call(function () {
        \App\Models\Audit::where('created_at', '<', now()->subYear())->delete();
    })->monthly();
}
```

## Recherche avancée

### Trouver tous les audits d'un utilisateur spécifique
```php
$user = \App\Models\User::where('email', 'user@example.com')->first();
$audits = \App\Models\Audit::where('user_id', $user->id)->latest()->get();
echo "Nombre d'actions: " . $audits->count();
```

### Trouver qui a validé un paiement spécifique
```php
$paiementId = 123;
$audits = \App\Models\Audit::paiements()
    ->validations()
    ->where('model_id', $paiementId)
    ->with('user')
    ->get();

foreach ($audits as $audit) {
    echo "{$audit->user->name} a {$audit->action} le paiement #{$paiementId} le {$audit->created_at}\n";
}
```

### Trouver toutes les actions sur un adhérent
```php
$adherentId = 456;
$audits = \App\Models\Audit::where('model_type', \App\Models\Adherent::class)
    ->where('model_id', $adherentId)
    ->with('user')
    ->latest()
    ->get();

foreach ($audits as $audit) {
    echo "{$audit->created_at}: {$audit->user->name ?? 'Système'} - {$audit->action}\n";
}
```

## Maintenance

### Optimiser la table audits
```bash
php artisan tinker
```

Puis dans tinker :
```php
\DB::statement('OPTIMIZE TABLE audits');
echo "Table audits optimisée";
```

### Réindexer la table audits
```php
\DB::statement('ANALYZE TABLE audits');
echo "Table audits réindexée";
```

## Résolution de problèmes

### Si la table audits devient trop volumineuse
1. Nettoyer les audits non pertinents : `php artisan audit:clean`
2. Supprimer les audits anciens (> 1 an)
3. Optimiser la table
4. Envisager une archive des audits dans une table séparée

### Si les audits ne sont pas créés
1. Vérifier que le middleware `AuditActions` est bien enregistré
2. Vérifier que les modèles utilisent le trait `Auditable`
3. Vérifier qu'un utilisateur est authentifié
4. Consulter les logs Laravel : `storage/logs/laravel.log`

### Si les descriptions sont vides
Les nouvelles actions auront des descriptions. Les anciennes (créées avant la mise à jour) n'en ont pas.
Solution : Elles s'affichent maintenant avec "-" dans l'interface.
