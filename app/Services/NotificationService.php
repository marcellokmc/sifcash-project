<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Créer une notification pour un adhérent
     */
    public static function creerNotification($userId, $titre, $message, $type = 'info')
    {
        DB::table('notifications')->insert([
            'user_id' => $userId,
            'titre' => $titre,
            'message' => $message,
            'type' => $type,
            'lu' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Notification pour validation de paiement
     */
    public static function paiementValide($adherentId, $montant, $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
        
        self::creerNotification(
            $adherentId,
            '✅ Paiement validé',
            "Votre versement de " . number_format($montant, 0, ',', ' ') . " FCFA a été validé par {$agent}. Les fonds sont maintenant disponibles sur votre compte.",
            'success'
        );
    }

    /**
     * Notification pour rejet de paiement
     */
    public static function paiementRejete($adherentId, $montant, $motif = '', $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
        $messageMotif = $motif ? " Motif : {$motif}" : '';
        
        self::creerNotification(
            $adherentId,
            '❌ Paiement rejeté',
            "Votre versement de " . number_format($montant, 0, ',', ' ') . " FCFA a été rejeté par {$agent}.{$messageMotif} Veuillez contacter le service client pour plus d'informations.",
            'error'
        );
    }

    /**
     * Notification pour approbation de crédit
     */
    public static function creditApprouve($adherentId, $montant, $duree, $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
        
        self::creerNotification(
            $adherentId,
            '🎉 Crédit approuvé !',
            "Félicitations ! Votre demande de crédit de " . number_format($montant, 0, ',', ' ') . " FCFA sur {$duree} mois a été approuvée par {$agent}. Les fonds seront disponibles sous peu.",
            'success'
        );
    }

    /**
     * Notification pour rejet de crédit
     */
    public static function creditRejete($adherentId, $montant, $motif = '', $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
        $messageMotif = $motif ? " Motif : {$motif}" : '';
        
        self::creerNotification(
            $adherentId,
            '❌ Demande de crédit rejetée',
            "Votre demande de crédit de " . number_format($montant, 0, ',', ' ') . " FCFA a été rejetée par {$agent}.{$messageMotif} Vous pouvez soumettre une nouvelle demande en respectant les critères.",
            'warning'
        );
    }

    /**
     * Notification pour validation de retrait
     */
    public static function retraitApprouve($adherentId, $montant, $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
        
        self::creerNotification(
            $adherentId,
            '💰 Retrait approuvé',
            "Votre demande de retrait de " . number_format($montant, 0, ',', ' ') . " FCFA a été approuvée par {$agent}. Vous pouvez récupérer vos fonds selon les modalités convenues.",
            'info'
        );
    }

    /**
     * Notification pour rejet de retrait
     */
    public static function retraitRejete($adherentId, $montant, $motif = '', $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
        $messageMotif = $motif ? " Motif : {$motif}" : '';
        
        self::creerNotification(
            $adherentId,
            '❌ Retrait rejeté',
            "Votre demande de retrait de " . number_format($montant, 0, ',', ' ') . " FCFA a été rejetée par {$agent}.{$messageMotif}",
            'warning'
        );
    }

    /**
     * Notification pour validation de document
     */
    public static function documentValide($adherentId, $typeDocument, $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
        
        self::creerNotification(
            $adherentId,
            '✅ Document validé',
            "Votre document '{$typeDocument}' a été validé par {$agent}. Votre dossier progresse vers la finalisation.",
            'success'
        );
    }

    /**
     * Notification pour rejet de document
     */
    public static function documentRejete($adherentId, $typeDocument, $motif = '', $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
        $messageMotif = $motif ? " Motif : {$motif}" : '';
        
        self::creerNotification(
            $adherentId,
            '❌ Document rejeté',
            "Votre document '{$typeDocument}' a été rejeté par {$agent}.{$messageMotif} Veuillez soumettre un nouveau document conforme.",
            'error'
        );
    }

    /**
     * Notification pour validation d'ayant droit
     */
    public static function ayantDroitValide($adherentId, $nomBeneficiaire, $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
        
        self::creerNotification(
            $adherentId,
            '✅ Ayant droit validé',
            "L'ayant droit '{$nomBeneficiaire}' a été validé par {$agent}. Cette personne est maintenant officiellement enregistrée comme bénéficiaire sur votre compte.",
            'success'
        );
    }

    /**
     * Notification pour rejet d'ayant droit
     */
    public static function ayantDroitRejete($adherentId, $nomBeneficiaire, $motif = '', $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un agent');
        $messageMotif = $motif ? " Motif : {$motif}" : '';
        
        self::creerNotification(
            $adherentId,
            '❌ Ayant droit rejeté',
            "L'ayant droit '{$nomBeneficiaire}' a été rejeté par {$agent}.{$messageMotif} Veuillez vérifier les informations et soumettre à nouveau.",
            'warning'
        );
    }

    /**
     * Notification pour activation de compte
     */
    public static function compteActive($adherentId, $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un administrateur');
        
        self::creerNotification(
            $adherentId,
            '🎉 Compte activé !',
            "Félicitations ! Votre compte SIFCash-Burkina a été activé par {$agent}. Vous avez maintenant accès à tous nos services d'épargne et de crédit.",
            'success'
        );
    }

    /**
     * Notification pour suspension de compte
     */
    public static function compteSuspendu($adherentId, $motif = '', $agentNom = null)
    {
        $agent = $agentNom ?? (Auth::user()->name ?? 'Un administrateur');
        $messageMotif = $motif ? " Motif : {$motif}" : '';
        
        self::creerNotification(
            $adherentId,
            '⚠️ Compte suspendu',
            "Votre compte a été suspendu par {$agent}.{$messageMotif} Veuillez contacter notre service client pour résoudre cette situation.",
            'alert'
        );
    }

    /**
     * Notification générale d'information
     */
    public static function infoGenerale($adherentId, $titre, $message, $type = 'info')
    {
        self::creerNotification($adherentId, $titre, $message, $type);
    }

    /**
     * Marquer une notification comme lue
     */
    public static function marquerCommeLue($notificationId, $userId)
    {
        DB::table('notifications')
            ->where('id', $notificationId)
            ->where('user_id', $userId)
            ->update([
                'lu' => true,
                'updated_at' => now()
            ]);
    }

    /**
     * Compter les notifications non lues
     */
    public static function compterNonLues($userId)
    {
        return DB::table('notifications')
            ->where('user_id', $userId)
            ->where('lu', false)
            ->count();
    }

    /**
     * Récupérer toutes les notifications d'un utilisateur
     */
    public static function obtenirNotifications($userId, $limite = 50)
    {
        return DB::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limite)
            ->get();
    }
}
