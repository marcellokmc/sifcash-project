<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Récupérer le rôle admin
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $this->command->warn('Le rôle admin n\'existe pas. Veuillez d\'abord exécuter RoleSeeder.');
            return;
        }

        // Récupérer toutes les permissions
        $allPermissions = Permission::all();

        if ($allPermissions->isEmpty()) {
            $this->command->warn('Aucune permission trouvée. Veuillez d\'abord exécuter PermissionSeeder.');
            return;
        }

        // Assigner toutes les permissions à l'administrateur
        $adminRole->permissions()->sync($allPermissions->pluck('id')->toArray());

        $this->command->info('Toutes les permissions (' . $allPermissions->count() . ') ont été assignées au rôle admin.');

        // Configuration des permissions pour les autres rôles
        $this->assignAgentPermissions();
        $this->assignChefServicePermissions();
        $this->assignSuperviseurPermissions();
        $this->assignComptablePermissions();
    }

    /**
     * Assigner les permissions pour le rôle Agent
     */
    private function assignAgentPermissions()
    {
        $agentRole = Role::where('name', 'agent')->first();
        
        if (!$agentRole) {
            return;
        }

        $agentPermissions = Permission::whereIn('name', [
            // Adhérents
            'view_adherents',
            'create_adherents',
            'edit_adherents',
            'validate_adherents',
            
            // Ayants droit
            'view_ayants_droits',
            'create_ayants_droits',
            'edit_ayants_droits',
            
            // Documents
            'view_documents',
            'upload_documents',
            'validate_documents',
            'download_documents',
            
            // Plans
            'view_plans',
            
            // Adhésions
            'view_adhesions',
            'create_adhesions',
            'validate_adhesions',
            
            // Paiements
            'view_payments',
            'create_payments',
            'validate_payments',
            
            // Crédits
            'view_credits',
            'create_credits',
            
            // Dashboard
            'view_dashboard',
            'view_statistics',
        ])->pluck('id')->toArray();

        $agentRole->permissions()->sync($agentPermissions);
        $this->command->info('Permissions assignées au rôle Agent (' . count($agentPermissions) . ')');
    }

    /**
     * Assigner les permissions pour le rôle Chef de Service
     */
    private function assignChefServicePermissions()
    {
        $chefServiceRole = Role::where('name', 'chef_service')->first();
        
        if (!$chefServiceRole) {
            return;
        }

        $chefServicePermissions = Permission::whereIn('name', [
            // Utilisateurs
            'view_users',
            'create_users',
            'edit_users',
            'toggle_user_status',
            'view_user_activity',
            
            // Adhérents
            'view_adherents',
            'create_adherents',
            'edit_adherents',
            'validate_adherents',
            'toggle_adherent_status',
            
            // Ayants droit
            'view_ayants_droits',
            'create_ayants_droits',
            'edit_ayants_droits',
            'delete_ayants_droits',
            
            // Documents
            'view_documents',
            'upload_documents',
            'validate_documents',
            'delete_documents',
            'download_documents',
            
            // Plans
            'view_plans',
            'create_plans',
            'edit_plans',
            
            // Adhésions
            'view_adhesions',
            'create_adhesions',
            'edit_adhesions',
            'validate_adhesions',
            
            // Paiements
            'view_payments',
            'create_payments',
            'edit_payments',
            'validate_payments',
            'reject_payments',
            
            // Crédits
            'view_credits',
            'create_credits',
            'edit_credits',
            'approve_credits',
            'reject_credits',
            'validate_credits',
            
            // Retraits
            'view_withdrawals',
            'validate_withdrawals',
            'reject_withdrawals',
            'process_withdrawals',
            
            // Agences
            'view_agences',
            
            // Rapports
            'view_reports',
            'view_statistics',
            'view_dashboard',
            
            // Logs
            'view_connection_logs',
        ])->pluck('id')->toArray();

        $chefServiceRole->permissions()->sync($chefServicePermissions);
        $this->command->info('Permissions assignées au rôle Chef de Service (' . count($chefServicePermissions) . ')');
    }

    /**
     * Assigner les permissions pour le rôle Superviseur
     */
    private function assignSuperviseurPermissions()
    {
        $superviseurRole = Role::where('name', 'superviseur')->first();
        
        if (!$superviseurRole) {
            return;
        }

        $superviseurPermissions = Permission::whereIn('name', [
            // Adhérents
            'view_adherents',
            'edit_adherents',
            'validate_adherents',
            
            // Documents
            'view_documents',
            'validate_documents',
            'download_documents',
            
            // Plans
            'view_plans',
            
            // Adhésions
            'view_adhesions',
            'validate_adhesions',
            
            // Paiements
            'view_payments',
            'validate_payments',
            
            // Crédits
            'view_credits',
            'approve_credits',
            'reject_credits',
            'validate_credits',
            
            // Retraits
            'view_withdrawals',
            'validate_withdrawals',
            'reject_withdrawals',
            
            // Rapports
            'view_reports',
            'view_statistics',
            'view_dashboard',
            
            // Logs
            'view_audit',
            'view_connection_logs',
        ])->pluck('id')->toArray();

        $superviseurRole->permissions()->sync($superviseurPermissions);
        $this->command->info('Permissions assignées au rôle Superviseur (' . count($superviseurPermissions) . ')');
    }

    /**
     * Assigner les permissions pour le rôle Comptable
     */
    private function assignComptablePermissions()
    {
        $comptableRole = Role::where('name', 'comptable')->first();
        
        if (!$comptableRole) {
            return;
        }

        $comptablePermissions = Permission::whereIn('name', [
            // Adhérents (lecture uniquement)
            'view_adherents',
            
            // Paiements
            'view_payments',
            'create_payments',
            'validate_payments',
            'reject_payments',
            'refund_payments',
            'download_payment_proof',
            
            // Crédits
            'view_credits',
            'view_credit_schedule',
            'record_credit_payment',
            
            // Paiements de crédit
            'view_credit_payments',
            'create_credit_payments',
            'validate_credit_payments',
            
            // Retraits
            'view_withdrawals',
            'validate_withdrawals',
            'reject_withdrawals',
            'process_withdrawals',
            
            // Épargnes
            'view_epargnes',
            'create_epargnes',
            'edit_epargnes',
            'depot_epargnes',
            'retrait_epargnes',
            'calculate_interests',
            'export_epargnes',
            
            // Rapports
            'view_reports',
            'export_reports',
            'view_statistics',
            'view_dashboard',
            
            // Logs
            'view_connection_logs',
            'export_connection_logs',
        ])->pluck('id')->toArray();

        $comptableRole->permissions()->sync($comptablePermissions);
        $this->command->info('Permissions assignées au rôle Comptable (' . count($comptablePermissions) . ')');
    }
}
