<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Gestion utilisateurs
            ['name' => 'view_users', 'description' => 'Voir la liste des utilisateurs'],
            ['name' => 'create_users', 'description' => 'Créer des utilisateurs'],
            ['name' => 'edit_users', 'description' => 'Modifier des utilisateurs'],
            ['name' => 'delete_users', 'description' => 'Supprimer des utilisateurs'],
            ['name' => 'view_user_activity', 'description' => 'Voir l\'activité des utilisateurs'],
            ['name' => 'toggle_user_status', 'description' => 'Activer/Désactiver des utilisateurs'],
            ['name' => 'view_user_logs', 'description' => 'Voir les logs des utilisateurs'],
            ['name' => 'reset_user_password', 'description' => 'Réinitialiser le mot de passe des utilisateurs'],
            ['name' => 'suspend_users', 'description' => 'Suspendre des utilisateurs'],
            ['name' => 'activate_users', 'description' => 'Réactiver des utilisateurs'],

            // Gestion rôles & permissions
            ['name' => 'view_roles', 'description' => 'Voir les rôles'],
            ['name' => 'create_roles', 'description' => 'Créer des rôles'],
            ['name' => 'edit_roles', 'description' => 'Modifier des rôles'],
            ['name' => 'delete_roles', 'description' => 'Supprimer des rôles'],
            ['name' => 'assign_roles', 'description' => 'Assigner des rôles aux utilisateurs'],
            
            ['name' => 'view_permissions', 'description' => 'Voir les permissions'],
            ['name' => 'create_permissions', 'description' => 'Créer des permissions'],
            ['name' => 'edit_permissions', 'description' => 'Modifier des permissions'],
            ['name' => 'delete_permissions', 'description' => 'Supprimer des permissions'],
            ['name' => 'manage_permissions', 'description' => 'Gérer les permissions'],

            // Gestion adhérents
            ['name' => 'view_adherents', 'description' => 'Voir la liste des adhérents'],
            ['name' => 'create_adherents', 'description' => 'Créer des adhérents'],
            ['name' => 'edit_adherents', 'description' => 'Modifier des adhérents'],
            ['name' => 'delete_adherents', 'description' => 'Supprimer des adhérents'],
            ['name' => 'validate_adherents', 'description' => 'Valider les adhérents'],
            ['name' => 'toggle_adherent_status', 'description' => 'Activer/Désactiver des adhérents'],
            ['name' => 'reset_adherent_password', 'description' => 'Réinitialiser le mot de passe des adhérents'],
            ['name' => 'suspend_adherents', 'description' => 'Suspendre des adhérents'],
            ['name' => 'activate_adherents', 'description' => 'Réactiver des adhérents'],
            ['name' => 'manage_adherent_affectation', 'description' => 'Gérer l\'affectation des adhérents'],

            // Ayants droit
            ['name' => 'view_ayants_droits', 'description' => 'Voir les ayants droit'],
            ['name' => 'create_ayants_droits', 'description' => 'Créer des ayants droit'],
            ['name' => 'edit_ayants_droits', 'description' => 'Modifier des ayants droit'],
            ['name' => 'delete_ayants_droits', 'description' => 'Supprimer des ayants droit'],

            // Documents adhérents
            ['name' => 'view_documents', 'description' => 'Voir les documents'],
            ['name' => 'create_documents', 'description' => 'Créer des documents'],
            ['name' => 'upload_documents', 'description' => 'Uploader des documents'],
            ['name' => 'validate_documents', 'description' => 'Valider les documents'],
            ['name' => 'delete_documents', 'description' => 'Supprimer des documents'],
            ['name' => 'download_documents', 'description' => 'Télécharger des documents'],

            // Types de documents
            ['name' => 'view_type_documents', 'description' => 'Voir les types de documents'],
            ['name' => 'create_type_documents', 'description' => 'Créer des types de documents'],
            ['name' => 'edit_type_documents', 'description' => 'Modifier des types de documents'],
            ['name' => 'delete_type_documents', 'description' => 'Supprimer des types de documents'],

            // Plans
            ['name' => 'view_plans', 'description' => 'Voir les plans'],
            ['name' => 'create_plans', 'description' => 'Créer des plans'],
            ['name' => 'edit_plans', 'description' => 'Modifier des plans'],
            ['name' => 'delete_plans', 'description' => 'Supprimer des plans'],
            ['name' => 'toggle_plan_status', 'description' => 'Activer/Désactiver des plans'],

            // Adhésions
            ['name' => 'view_adhesions', 'description' => 'Voir les adhésions'],
            ['name' => 'create_adhesions', 'description' => 'Créer des adhésions'],
            ['name' => 'edit_adhesions', 'description' => 'Modifier des adhésions'],
            ['name' => 'delete_adhesions', 'description' => 'Supprimer des adhésions'],
            ['name' => 'validate_adhesions', 'description' => 'Valider les adhésions'],
            ['name' => 'renew_adhesions', 'description' => 'Renouveler les adhésions'],

            // Paiements
            ['name' => 'view_payments', 'description' => 'Voir les paiements'],
            ['name' => 'create_payments', 'description' => 'Enregistrer des paiements'],
            ['name' => 'edit_payments', 'description' => 'Modifier des paiements'],
            ['name' => 'delete_payments', 'description' => 'Supprimer des paiements'],
            ['name' => 'validate_payments', 'description' => 'Valider les paiements'],
            ['name' => 'reject_payments', 'description' => 'Rejeter les paiements'],
            ['name' => 'refund_payments', 'description' => 'Rembourser des paiements'],
            ['name' => 'download_payment_proof', 'description' => 'Télécharger les preuves de paiement'],

            // Crédits
            ['name' => 'view_credits', 'description' => 'Voir les crédits'],
            ['name' => 'create_credits', 'description' => 'Créer des crédits'],
            ['name' => 'edit_credits', 'description' => 'Modifier des crédits'],
            ['name' => 'delete_credits', 'description' => 'Supprimer des crédits'],
            ['name' => 'approve_credits', 'description' => 'Approuver les demandes de crédit'],
            ['name' => 'reject_credits', 'description' => 'Rejeter des demandes de crédit'],
            ['name' => 'validate_credits', 'description' => 'Valider les crédits'],
            ['name' => 'view_credit_schedule', 'description' => 'Voir l\'échéancier des crédits'],
            ['name' => 'record_credit_payment', 'description' => 'Enregistrer un paiement de crédit'],
            ['name' => 'export_credit_contract', 'description' => 'Exporter le contrat de crédit'],

            // Paiements de crédit
            ['name' => 'view_credit_payments', 'description' => 'Voir les paiements de crédit'],
            ['name' => 'create_credit_payments', 'description' => 'Créer des paiements de crédit'],
            ['name' => 'validate_credit_payments', 'description' => 'Valider les paiements de crédit'],
            ['name' => 'view_credit_payment_proofs', 'description' => 'Voir les preuves de paiement crédit'],
            ['name' => 'download_credit_payment_proofs', 'description' => 'Télécharger les preuves de paiement crédit'],

            // Échéances de crédit
            ['name' => 'view_echeances', 'description' => 'Voir les échéances'],
            ['name' => 'edit_echeances', 'description' => 'Modifier les échéances'],

            // Conditions d'éligibilité crédit
            ['name' => 'view_credit_eligibility', 'description' => 'Voir les conditions d\'éligibilité crédit'],
            ['name' => 'create_credit_eligibility', 'description' => 'Créer des conditions d\'éligibilité crédit'],
            ['name' => 'edit_credit_eligibility', 'description' => 'Modifier les conditions d\'éligibilité crédit'],
            ['name' => 'toggle_credit_eligibility', 'description' => 'Activer/Désactiver les conditions d\'éligibilité'],

            // Retraits
            ['name' => 'view_withdrawals', 'description' => 'Voir les demandes de retrait'],
            ['name' => 'create_withdrawals', 'description' => 'Créer des demandes de retrait'],
            ['name' => 'edit_withdrawals', 'description' => 'Modifier des demandes de retrait'],
            ['name' => 'delete_withdrawals', 'description' => 'Supprimer des demandes de retrait'],
            ['name' => 'validate_withdrawals', 'description' => 'Valider les demandes de retrait'],
            ['name' => 'reject_withdrawals', 'description' => 'Rejeter les demandes de retrait'],
            ['name' => 'process_withdrawals', 'description' => 'Traiter les demandes de retrait'],

            // Pénalités de retrait anticipé
            ['name' => 'view_penalties', 'description' => 'Voir les pénalités'],
            ['name' => 'edit_penalties', 'description' => 'Modifier les pénalités'],
            ['name' => 'activate_penalties', 'description' => 'Activer les pénalités'],
            ['name' => 'deactivate_penalties', 'description' => 'Désactiver les pénalités'],

            // Épargnes
            ['name' => 'view_epargnes', 'description' => 'Voir les épargnes'],
            ['name' => 'create_epargnes', 'description' => 'Créer des épargnes'],
            ['name' => 'edit_epargnes', 'description' => 'Modifier des épargnes'],
            ['name' => 'delete_epargnes', 'description' => 'Supprimer des épargnes'],
            ['name' => 'depot_epargnes', 'description' => 'Effectuer des dépôts d\'épargne'],
            ['name' => 'retrait_epargnes', 'description' => 'Effectuer des retraits d\'épargne'],
            ['name' => 'calculate_interests', 'description' => 'Calculer les intérêts d\'épargne'],
            ['name' => 'export_epargnes', 'description' => 'Exporter les épargnes'],

            // Agences
            ['name' => 'view_agences', 'description' => 'Voir les agences'],
            ['name' => 'create_agences', 'description' => 'Créer des agences'],
            ['name' => 'edit_agences', 'description' => 'Modifier des agences'],
            ['name' => 'delete_agences', 'description' => 'Supprimer des agences'],
            ['name' => 'toggle_agence_status', 'description' => 'Activer/Désactiver des agences'],

            // Notifications
            ['name' => 'view_notifications', 'description' => 'Voir les notifications'],
            ['name' => 'create_notifications', 'description' => 'Créer des notifications'],
            ['name' => 'mark_notifications_read', 'description' => 'Marquer les notifications comme lues'],
            ['name' => 'delete_notifications', 'description' => 'Supprimer des notifications'],

            // Audits & Logs
            ['name' => 'view_audit', 'description' => 'Voir les journaux d\'audit'],
            ['name' => 'view_logs', 'description' => 'Voir les logs système'],
            ['name' => 'view_connection_logs', 'description' => 'Voir les logs de connexion'],
            ['name' => 'export_connection_logs', 'description' => 'Exporter les logs de connexion'],

            // Rapports & Statistiques
            ['name' => 'view_reports', 'description' => 'Voir les rapports'],
            ['name' => 'view_statistics', 'description' => 'Voir les statistiques'],
            ['name' => 'view_dashboard', 'description' => 'Accéder au dashboard'],
            ['name' => 'export_reports', 'description' => 'Exporter les rapports'],

            // Recherche globale
            ['name' => 'global_search', 'description' => 'Effectuer une recherche globale'],

            // Paramètres
            ['name' => 'view_settings', 'description' => 'Voir les paramètres'],
            ['name' => 'edit_settings', 'description' => 'Modifier les paramètres'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}