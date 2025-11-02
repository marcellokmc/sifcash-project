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
            // Adhérents (uniquement ceux qui lui sont affectés)
            'view_adherents',
            'create_adherents',
            'edit_adherents',
            
            // Ayants droit (des adhérents affectés)
            'view_ayants_droits',
            'create_ayants_droits',
            'edit_ayants_droits',
            
            // Documents (des adhérents affectés)
            'view_documents',
            'upload_documents',
            'download_documents',
            
            // Plans
            'view_plans',
            
            // Adhésions (des adhérents affectés)
            'view_adhesions',
            'create_adhesions',
            
            // Paiements (des adhérents affectés)
            'view_payments',
            'create_payments',
            
            // Crédits (des adhérents affectés)
            'view_credits',
            'create_credits',
            
            // Épargnes (des adhérents affectés)
            'view_epargnes',
            'create_epargnes',
            'depot_epargnes',
            
            // Dashboard
            'view_dashboard',
            'view_statistics',
            
            // Recherche
            'global_search',
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
            // Utilisateurs (de son agence uniquement)
            'view_users',
            'create_users',
            'edit_users',
            'toggle_user_status',
            'view_user_activity',
            'view_user_logs',
            'suspend_users',
            'activate_users',
            
            // Adhérents (de son agence)
            'view_adherents',
            'create_adherents',
            'edit_adherents',
            'validate_adherents',
            'toggle_adherent_status',
            'suspend_adherents',
            'activate_adherents',
            'manage_adherent_affectation',
            
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
            
            // Types de documents
            'view_type_documents',
            'create_type_documents',
            'edit_type_documents',
            
            // Plans
            'view_plans',
            'create_plans',
            'edit_plans',
            'toggle_plan_status',
            
            // Adhésions
            'view_adhesions',
            'create_adhesions',
            'edit_adhesions',
            'validate_adhesions',
            'renew_adhesions',
            
            // Paiements
            'view_payments',
            'create_payments',
            'edit_payments',
            'validate_payments',
            'reject_payments',
            'download_payment_proof',
            
            // Crédits
            'view_credits',
            'create_credits',
            'edit_credits',
            'approve_credits',
            'reject_credits',
            'validate_credits',
            'view_credit_schedule',
            'record_credit_payment',
            'export_credit_contract',
            
            // Paiements de crédit
            'view_credit_payments',
            'create_credit_payments',
            'validate_credit_payments',
            'view_credit_payment_proofs',
            'download_credit_payment_proofs',
            
            // Échéances
            'view_echeances',
            'edit_echeances',
            
            // Conditions d'éligibilité crédit
            'view_credit_eligibility',
            'create_credit_eligibility',
            'edit_credit_eligibility',
            'toggle_credit_eligibility',
            
            // Retraits
            'view_withdrawals',
            'create_withdrawals',
            'edit_withdrawals',
            'validate_withdrawals',
            'reject_withdrawals',
            'process_withdrawals',
            
            // Pénalités
            'view_penalties',
            'edit_penalties',
            
            // Épargnes
            'view_epargnes',
            'create_epargnes',
            'edit_epargnes',
            'depot_epargnes',
            'retrait_epargnes',
            'calculate_interests',
            'export_epargnes',
            
            // Agences
            'view_agences',
            
            // Notifications
            'view_notifications',
            'create_notifications',
            'mark_notifications_read',
            
            // Audits & Logs
            'view_audit',
            'view_logs',
            'view_connection_logs',
            'export_connection_logs',
            
            // Rapports
            'view_reports',
            'view_statistics',
            'view_dashboard',
            'export_reports',
            
            // Recherche
            'global_search',
        ])->pluck('id')->toArray();

        $chefServiceRole->permissions()->sync($chefServicePermissions);
        $this->command->info('Permissions assignées au rôle Chef de Service (' . count($chefServicePermissions) . ')');
    }

    /**
     * Assigner les permissions pour le rôle Superviseur
     * (mêmes permissions que Chef de Service)
     */
    private function assignSuperviseurPermissions()
    {
        $superviseurRole = Role::where('name', 'superviseur')->first();
        
        if (!$superviseurRole) {
            return;
        }

        // Le superviseur a les mêmes permissions que le chef de service
        $superviseurPermissions = Permission::whereIn('name', [
            // Utilisateurs (de son agence uniquement)
            'view_users',
            'create_users',
            'edit_users',
            'toggle_user_status',
            'view_user_activity',
            'view_user_logs',
            'suspend_users',
            'activate_users',
            
            // Adhérents (de son agence)
            'view_adherents',
            'create_adherents',
            'edit_adherents',
            'validate_adherents',
            'toggle_adherent_status',
            'suspend_adherents',
            'activate_adherents',
            'manage_adherent_affectation',
            
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
            
            // Types de documents
            'view_type_documents',
            'create_type_documents',
            'edit_type_documents',
            
            // Plans
            'view_plans',
            'create_plans',
            'edit_plans',
            'toggle_plan_status',
            
            // Adhésions
            'view_adhesions',
            'create_adhesions',
            'edit_adhesions',
            'validate_adhesions',
            'renew_adhesions',
            
            // Paiements
            'view_payments',
            'create_payments',
            'edit_payments',
            'validate_payments',
            'reject_payments',
            'download_payment_proof',
            
            // Crédits
            'view_credits',
            'create_credits',
            'edit_credits',
            'approve_credits',
            'reject_credits',
            'validate_credits',
            'view_credit_schedule',
            'record_credit_payment',
            'export_credit_contract',
            
            // Paiements de crédit
            'view_credit_payments',
            'create_credit_payments',
            'validate_credit_payments',
            'view_credit_payment_proofs',
            'download_credit_payment_proofs',
            
            // Échéances
            'view_echeances',
            'edit_echeances',
            
            // Conditions d'éligibilité crédit
            'view_credit_eligibility',
            'create_credit_eligibility',
            'edit_credit_eligibility',
            'toggle_credit_eligibility',
            
            // Retraits
            'view_withdrawals',
            'create_withdrawals',
            'edit_withdrawals',
            'validate_withdrawals',
            'reject_withdrawals',
            'process_withdrawals',
            
            // Pénalités
            'view_penalties',
            'edit_penalties',
            
            // Épargnes
            'view_epargnes',
            'create_epargnes',
            'edit_epargnes',
            'depot_epargnes',
            'retrait_epargnes',
            'calculate_interests',
            'export_epargnes',
            
            // Agences
            'view_agences',
            
            // Notifications
            'view_notifications',
            'create_notifications',
            'mark_notifications_read',
            
            // Audits & Logs
            'view_audit',
            'view_logs',
            'view_connection_logs',
            'export_connection_logs',
            
            // Rapports
            'view_reports',
            'view_statistics',
            'view_dashboard',
            'export_reports',
            
            // Recherche
            'global_search',
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
