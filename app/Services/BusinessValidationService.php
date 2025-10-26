<?php

namespace App\Services;

use App\Models\Adherent;
use App\Models\Credit;
use App\Models\Adhesion;
use App\Models\Plan;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class BusinessValidationService
{
    /**
     * Valider l'éligibilité d'un adhérent pour un crédit
     */
    public function validateCreditEligibility(Adherent $adherent, float $montantDemande): array
    {
        $errors = [];
        $warnings = [];

        // 1. Vérifier le statut du compte
        if (!$adherent->isActif()) {
            $errors[] = 'Le compte adhérent doit être actif pour demander un crédit.';
        }

        // 2. Vérifier l'historique de crédit
        $activeCredits = $adherent->credits()
            ->whereIn('statut', ['en_attente', 'approuvé', 'actif'])
            ->count();

        if ($activeCredits >= 3) {
            $errors[] = 'Maximum 3 crédits simultanés autorisés.';
        }

        // 3. Vérifier les impayés
        $overduePayments = $this->getOverduePayments($adherent);
        if ($overduePayments->count() > 0) {
            $errors[] = 'Des paiements sont en retard. Régularisez votre situation avant de demander un nouveau crédit.';
        }

        // 4. Vérifier la capacité d'épargne
        $totalEpargne = $adherent->getSoldeEpargneAttribute();
        $minEpargneRequired = $montantDemande * 0.1; // 10% du montant demandé

        if ($totalEpargne < $minEpargneRequired) {
            $errors[] = sprintf(
                'Épargne insuffisante. Minimum requis: %s FCFA (vous avez: %s FCFA)',
                number_format($minEpargneRequired, 0, ',', ' '),
                number_format($totalEpargne, 0, ',', ' ')
            );
        }

        // 5. Vérifier les documents obligatoires
        $requiredDocs = $this->getRequiredDocuments($adherent);
        if ($requiredDocs->count() > 0) {
            $warnings[] = 'Documents manquants: ' . $requiredDocs->implode(', ');
        }

        // 6. Vérifier l'ancienneté du compte
        $accountAge = $adherent->created_at->diffInMonths(now());
        if ($accountAge < 3) {
            $warnings[] = 'Votre compte a moins de 3 mois d\'ancienneté. Cela pourrait affecter l\'évaluation de votre dossier.';
        }

        return [
            'eligible' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'score' => $this->calculateCreditScore($adherent, $montantDemande),
        ];
    }

    /**
     * Valider une demande d'adhésion
     */
    public function validateAdhesionRequest(Adherent $adherent, Plan $plan, float $montantSouscrit): array
    {
        $errors = [];
        $warnings = [];

        // 1. Vérifier si le plan est actif
        if (!$plan->actif) {
            $errors[] = 'Ce plan n\'est plus disponible.';
        }

        // 2. Vérifier les montants min/max
        if ($montantSouscrit < $plan->montant_min) {
            $errors[] = sprintf(
                'Montant minimum requis: %s FCFA',
                number_format($plan->montant_min, 0, ',', ' ')
            );
        }

        if ($plan->montant_max && $montantSouscrit > $plan->montant_max) {
            $errors[] = sprintf(
                'Montant maximum autorisé: %s FCFA',
                number_format($plan->montant_max, 0, ',', ' ')
            );
        }

        // 3. Vérifier les adhésions existantes
        $existingAdhesions = $adherent->adhesions()
            ->where('plan_id', $plan->id)
            ->whereIn('statut', ['actif', 'en_attente_activation'])
            ->count();

        if ($existingAdhesions > 0) {
            $errors[] = 'Vous avez déjà une adhésion active ou en attente sur ce plan.';
        }

        // 4. Vérifier la limite d'adhésions totales
        $totalActiveAdhesions = $adherent->adhesions()
            ->whereIn('statut', ['actif', 'en_attente_activation'])
            ->count();

        if ($totalActiveAdhesions >= 5) {
            $errors[] = 'Maximum 5 adhésions simultanées autorisées.';
        }

        // 5. Vérifier les conditions spéciales du plan
        if ($plan->conditions_eligibilite) {
            $conditionsMet = $this->checkPlanConditions($adherent, $plan);
            if (!$conditionsMet['eligible']) {
                $errors = array_merge($errors, $conditionsMet['errors']);
            }
        }

        return [
            'eligible' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Valider une demande de retrait
     */
    public function validateWithdrawalRequest(Adherent $adherent, float $montantDemande, string $typeRetrait): array
    {
        $errors = [];
        $warnings = [];

        // 1. Vérifier le solde disponible
        $soldeDisponible = $adherent->getSoldeEpargneAttribute();
        if ($montantDemande > $soldeDisponible) {
            $errors[] = sprintf(
                'Solde insuffisant. Disponible: %s FCFA',
                number_format($soldeDisponible, 0, ',', ' ')
            );
        }

        // 2. Vérifier les conditions de retrait selon le type
        if ($typeRetrait === 'partiel') {
            $montantMinimal = 10000; // Montant minimum pour retrait partiel
            if ($montantDemande < $montantMinimal) {
                $errors[] = sprintf(
                    'Montant minimum pour retrait partiel: %s FCFA',
                    number_format($montantMinimal, 0, ',', ' ')
                );
            }

            // Vérifier qu'il reste un solde minimum après retrait
            $soldeRestant = $soldeDisponible - $montantDemande;
            $soldeMinimum = 5000;
            if ($soldeRestant < $soldeMinimum) {
                $warnings[] = sprintf(
                    'Il doit rester au minimum %s FCFA sur votre compte après le retrait.',
                    number_format($soldeMinimum, 0, ',', ' ')
                );
            }
        }

        // 3. Vérifier les retraits récents (limiter la fréquence)
        $recentWithdrawals = $adherent->demandeRetraits()
            ->where('created_at', '>', now()->subDays(30))
            ->where('statut', '!=', 'rejeté')
            ->count();

        if ($recentWithdrawals >= 3) {
            $warnings[] = 'Vous avez effectué plusieurs retraits ce mois-ci. Cela pourrait ralentir le traitement.';
        }

        // 4. Calculer les pénalités éventuelles
        $penalites = $this->calculateWithdrawalPenalties($adherent, $montantDemande, $typeRetrait);
        if ($penalites > 0) {
            $warnings[] = sprintf(
                'Pénalités de retrait anticipé: %s FCFA',
                number_format($penalites, 0, ',', ' ')
            );
        }

        return [
            'eligible' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'penalties' => $penalites,
            'net_amount' => $montantDemande - $penalites,
        ];
    }

    /**
     * Calculer le score de crédit d'un adhérent
     */
    private function calculateCreditScore(Adherent $adherent, float $montantDemande): int
    {
        $score = 500; // Score de base

        // Facteur 1: Ancienneté du compte (max +100 points)
        $accountAge = $adherent->created_at->diffInMonths(now());
        $score += min(100, $accountAge * 5);

        // Facteur 2: Historique de paiement (max +150 points)
        $paymentHistory = $this->getPaymentHistoryScore($adherent);
        $score += $paymentHistory;

        // Facteur 3: Ratio épargne/demande (max +100 points)
        $epargneRatio = $adherent->getSoldeEpargneAttribute() / $montantDemande;
        $score += min(100, $epargneRatio * 50);

        // Facteur 4: Stabilité des adhésions (max +50 points)
        $activeAdhesions = $adherent->adhesions()->where('statut', 'actif')->count();
        $score += min(50, $activeAdhesions * 10);

        // Pénalités
        $overduePayments = $this->getOverduePayments($adherent);
        $score -= $overduePayments->count() * 50;

        return max(300, min(850, $score)); // Score entre 300 et 850
    }

    /**
     * Obtenir les paiements en retard
     */
    private function getOverduePayments(Adherent $adherent): Collection
    {
        // Logique pour récupérer les paiements en retard
        // À adapter selon votre modèle de données
        return collect([]);
    }

    /**
     * Obtenir les documents requis manquants
     */
    private function getRequiredDocuments(Adherent $adherent): Collection
    {
        // Vérifier quels documents obligatoires manquent
        $required = ['cni', 'justificatif_domicile', 'fiche_salaire'];
        $existing = $adherent->documents()
            ->where('statut', 'validé')
            ->pluck('type')
            ->toArray();

        return collect($required)->diff($existing);
    }

    /**
     * Vérifier les conditions spéciales d'un plan
     */
    private function checkPlanConditions(Adherent $adherent, Plan $plan): array
    {
        // Implémentation des règles métier spécifiques à chaque plan
        // Exemple: âge minimum, profession requise, etc.
        return ['eligible' => true, 'errors' => []];
    }

    /**
     * Calculer les pénalités de retrait
     */
    private function calculateWithdrawalPenalties(Adherent $adherent, float $montant, string $type): float
    {
        // Logique de calcul des pénalités selon les règles métier
        if ($type === 'anticipé') {
            return $montant * 0.02; // 2% de pénalité
        }

        return 0;
    }

    /**
     * Calculer le score d'historique de paiement
     */
    private function getPaymentHistoryScore(Adherent $adherent): int
    {
        // Analyser l'historique des paiements pour attribuer un score
        // À implémenter selon votre modèle de données
        return 100; // Score par défaut
    }

    /**
     * Valider les données d'un adhérent avant activation
     */
    public function validateAdherentActivation(Adherent $adherent): array
    {
        $errors = [];
        $warnings = [];

        // Vérifier la complétude du profil
        $requiredFields = ['nom', 'prenom', 'date_naissance', 'telephone', 'email', 'adresse'];
        foreach ($requiredFields as $field) {
            if (empty($adherent->$field)) {
                $errors[] = "Le champ '$field' est requis.";
            }
        }

        // Vérifier les documents
        $requiredDocs = $this->getRequiredDocuments($adherent);
        if ($requiredDocs->count() > 0) {
            $errors[] = 'Documents manquants: ' . $requiredDocs->implode(', ');
        }

        // Vérifier l'âge minimum
        if ($adherent->date_naissance && $adherent->date_naissance->diffInYears(now()) < 18) {
            $errors[] = 'L\'adhérent doit être majeur (18 ans minimum).';
        }

        return [
            'can_activate' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }
}