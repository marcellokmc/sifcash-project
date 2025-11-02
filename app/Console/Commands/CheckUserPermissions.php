<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:check {email : Email de l\'utilisateur}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les permissions d\'un utilisateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Utilisateur non trouvé : {$email}");
            return 1;
        }

        $this->info("👤 Utilisateur : {$user->name}");
        $this->info("📧 Email : {$user->email}");
        $this->info("🎭 Rôle : {$user->role}");
        
        if ($user->agence_id) {
            $agence = $user->agence;
            $this->info("🏢 Agence : {$agence->nom} (ID: {$agence->id})");
        } else {
            $this->info("🏢 Agence : Aucune (Admin global)");
        }
        
        $this->newLine();

        // Vérifier si admin
        if ($user->isAdmin()) {
            $this->info("✨ Cet utilisateur est ADMIN et a TOUTES les permissions");
            $totalPermissions = \App\Models\Permission::count();
            $this->info("Total : {$totalPermissions} permissions disponibles");
            return 0;
        }

        // Obtenir les permissions
        $permissions = $user->getAllPermissions();
        
        $this->info("📋 Permissions ({$permissions->count()}) :");
        $this->newLine();

        // Grouper par catégorie
        $grouped = $this->groupPermissions($permissions);

        foreach ($grouped as $category => $perms) {
            $this->line("<fg=cyan>» {$category}</>");
            foreach ($perms as $perm) {
                $this->line("  • {$perm->name}");
            }
            $this->newLine();
        }

        // Tests de permissions spécifiques
        $this->newLine();
        $this->info("🔍 Tests de Permissions Clés :");
        $this->testPermission($user, 'view_users', 'Voir les utilisateurs');
        $this->testPermission($user, 'create_users', 'Créer des utilisateurs');
        $this->testPermission($user, 'view_adherents', 'Voir les adhérents');
        $this->testPermission($user, 'create_adherents', 'Créer des adhérents');
        $this->testPermission($user, 'delete_adherents', 'Supprimer des adhérents');
        $this->testPermission($user, 'approve_credits', 'Approuver des crédits');
        $this->testPermission($user, 'create_roles', 'Créer des rôles');
        $this->testPermission($user, 'create_agences', 'Créer des agences');

        // Informations de portée
        $this->newLine();
        $this->info("🎯 Portée d'Accès :");
        
        if ($user->isAgent()) {
            $adherentsCount = $user->adherentsGeres()->count();
            $this->line("  • Adhérents affectés : {$adherentsCount}");
            
            if ($adherentsCount > 0) {
                $this->line("  • Liste des adhérents :");
                foreach ($user->adherentsGeres()->limit(5)->get() as $adherent) {
                    $principal = $adherent->pivot->is_principal ? '(Principal)' : '';
                    $this->line("    - {$adherent->nom} {$adherent->prenom} {$principal}");
                }
                if ($adherentsCount > 5) {
                    $this->line("    ... et " . ($adherentsCount - 5) . " autre(s)");
                }
            }
        } elseif ($user->isChefService() || $user->isSuperviseur()) {
            $adherentsCount = \App\Models\Adherent::where('agence_id', $user->agence_id)->count();
            $usersCount = User::where('agence_id', $user->agence_id)->count();
            $this->line("  • Adhérents de son agence : {$adherentsCount}");
            $this->line("  • Utilisateurs de son agence : {$usersCount}");
        }

        return 0;
    }

    /**
     * Tester une permission spécifique
     */
    protected function testPermission(User $user, string $permission, string $label)
    {
        $hasPermission = $user->hasPermissionTo($permission);
        $icon = $hasPermission ? '✓' : '✗';
        $color = $hasPermission ? 'green' : 'red';
        $this->line("  <fg={$color}>{$icon}</> {$label}");
    }

    /**
     * Grouper les permissions par catégorie
     */
    protected function groupPermissions($permissions)
    {
        $categories = [
            'Utilisateurs' => ['user', 'users'],
            'Adhérents' => ['adherent', 'adherents'],
            'Ayants Droit' => ['ayant', 'ayants_droits'],
            'Documents' => ['document', 'documents', 'type_document'],
            'Plans & Adhésions' => ['plan', 'adhesion'],
            'Paiements' => ['payment', 'paiement'],
            'Crédits' => ['credit', 'echeance', 'eligibility'],
            'Épargnes' => ['epargne'],
            'Retraits' => ['withdrawal', 'retrait', 'penalt'],
            'Agences' => ['agence'],
            'Rôles & Permissions' => ['role', 'permission'],
            'Rapports & Stats' => ['report', 'statistic', 'dashboard'],
            'Notifications' => ['notification'],
            'Logs & Audit' => ['log', 'audit', 'connexion'],
            'Autres' => [],
        ];

        $grouped = [];
        $categorized = [];

        foreach ($permissions as $permission) {
            $matched = false;
            foreach ($categories as $category => $keywords) {
                foreach ($keywords as $keyword) {
                    if (str_contains($permission->name, $keyword)) {
                        $grouped[$category][] = $permission;
                        $categorized[] = $permission->id;
                        $matched = true;
                        break 2;
                    }
                }
            }
            
            if (!$matched) {
                $grouped['Autres'][] = $permission;
            }
        }

        // Supprimer la catégorie "Autres" si vide
        if (empty($grouped['Autres'])) {
            unset($grouped['Autres']);
        }

        return $grouped;
    }
}
