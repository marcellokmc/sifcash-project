# 🎂 Fonctionnalité de Notifications d'Anniversaire

## Description

Cette fonctionnalité envoie automatiquement des notifications de joyeux anniversaire aux adhérents le jour de leur anniversaire.

## Fonctionnement

### Commande Artisan

La commande `birthdays:send-notifications` vérifie quotidiennement les adhérents dont c'est l'anniversaire et leur envoie une notification personnalisée.

```bash
php artisan birthdays:send-notifications
```

### Caractéristiques

✅ **Détection automatique** : Vérifie les dates de naissance (jour et mois)
✅ **Cas 29/02** : Si année non bissextile, souhaite le 28/02 aux nés le 29/02
✅ **Filtrage** : Ne notifie que les adhérents avec un compte actif
✅ **Prévention des doublons** : Ne renvoie pas de notification si déjà envoyée le même jour
✅ **Message personnalisé** : Inclut le prénom de l'adhérent et son âge
✅ **Canaux** : Notification in‑app + email optionnel (configurable)
✅ **Mode simulation** : `--dry-run` pour vérifier sans écrire

### Planification

La commande s'exécute automatiquement tous les jours à l'heure configurée (par défaut 06:00) via le scheduler Laravel.

Configuration dans `app/Console/Kernel.php` :
```php
$schedule->command('birthdays:send-notifications')->dailyAt(config('birthday.send_time', '06:00'));
```

Paramètres `config/birthday.php` (surchargés par .env):
- BIRTHDAY_EMAIL_ENABLED=true|false
- BIRTHDAY_SEND_TIME=06:00

### Activation du Scheduler

Pour que les tâches planifiées s'exécutent automatiquement, vous devez ajouter cette ligne cron sur votre serveur :

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Ou sur Windows avec le Planificateur de tâches :
- Créer une tâche qui s'exécute toutes les minutes
- Action : `php C:\path\to\project\artisan schedule:run`

### Test Manuel

Pour tester la fonctionnalité manuellement :

1. Modifier la date de naissance d'un adhérent pour qu'elle corresponde à aujourd'hui :
```bash
php artisan tinker
>>> $adherent = App\Models\Adherent::first();
>>> $adherent->update(['date_naissance' => now()->subYears(30)]);
>>> exit
```

2. Exécuter en mode simulation (aucune écriture) :
```bash
php artisan birthdays:send-notifications --dry-run
```

3. Exécuter réellement (limiter à N envois) :
```bash
php artisan birthdays:send-notifications --limit=5
```

3. Vérifier les notifications dans l'interface adhérent

### Format de la Notification

**Titre** : 🎂 Joyeux Anniversaire !

**Message** :
```
Chèr(e) [Prénom],

🎉 Toute l'équipe de SIFCash-Burkina vous souhaite un très joyeux anniversaire ! 

🎂 Vous célébrez aujourd'hui vos [X] ans. Que cette nouvelle année vous apporte santé, bonheur et prospérité !

Merci de votre confiance et de votre fidélité.

Bien à vous,
L'équipe SIFCash-Burkina 🎁
```

### Type de Notification

Le type de notification est `anniversaire`, qui a été ajouté à l'enum de la table `notifications`.

### Base de Données

La migration `2025_10_22_094127_add_anniversaire_type_to_notifications_table.php` ajoute le type `anniversaire` aux valeurs possibles du champ `type` dans la table `notifications`.

## Logs et Monitoring

La commande affiche des informations dans la console :
- 🎉 Début de la recherche
- ✅ Notification envoyée avec le nom et l'âge
- ⚠️ Notification déjà envoyée (si doublon)
- ℹ️ Aucun anniversaire aujourd'hui
- ✨ Résumé du nombre de notifications envoyées

## Maintenance

### Désactiver temporairement

Commentez la ligne dans `app/Console/Kernel.php` :
```php
// $schedule->command('birthdays:send-notifications')->dailyAt('06:00');
```

### Changer l'heure d'envoi

Modifiez l'heure dans `app/Console/Kernel.php` :
```php
$schedule->command('birthdays:send-notifications')->dailyAt('08:00'); // 8h au lieu de 6h
```

## Dépannage

### La commande ne s'exécute pas automatiquement
- Vérifiez que le cron/tâche planifiée est bien configuré
- Testez manuellement : `php artisan schedule:run`
- Vérifiez les logs Laravel : `storage/logs/laravel.log`

### Les notifications ne sont pas créées
- Vérifiez que les adhérents ont un compte actif
- Vérifiez que la relation `user` existe pour l'adhérent
- Vérifiez les dates de naissance dans la base de données

### Erreur de type ENUM
- Assurez-vous que la migration a été exécutée : `php artisan migrate`
- Vérifiez que le type `anniversaire` est bien dans l'enum de la table notifications
