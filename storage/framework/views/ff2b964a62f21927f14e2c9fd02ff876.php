<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Quittance de Déclaration de Paiement</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; }
        .receipt-container { width: 100%; max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; }
        .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { max-width: 100px; margin-bottom: 10px; }
        .receipt-title { font-size: 24px; color: #007bff; text-transform: uppercase; margin: 0; }
        .receipt-number { font-size: 14px; color: #666; }
        .info-section { width: 100%; margin-bottom: 30px; }
        .info-col { width: 48%; display: inline-block; vertical-align: top; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #f8f9fa; text-align: left; padding: 12px; border-bottom: 1px solid #dee2e6; }
        td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        .total-row { font-weight: bold; background: #e9ecef; }
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #888; border-top: 1px solid #eee; padding-top: 10px; }
        .stamp { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h1 class="receipt-title"><?php echo e($paiement->statut === 'validé' ? 'Quittance de Paiement' : 'Bon de dépôt'); ?></h1>
            <p class="receipt-number">N° REF: <?php echo e($paiement->reference_paiement ?? 'P'.str_pad($paiement->id, 6, '0', STR_PAD_LEFT)); ?></p>
        </div>

        <div class="info-section">
            <div class="info-col">
                <strong>ÉMETTEUR (ADHÉRENT):</strong><br>
                <?php echo e($paiement->adherent->nom_complet); ?><br>
                ID: <?php echo e($paiement->adherent->membre_id); ?><br>
                Tél: <?php echo e($paiement->adherent->telephone); ?>

            </div>
            <div class="info-col" style="text-align: right;">
                <strong>DESTINATAIRE:</strong><br>
                SIFCash-Burkina<br>
                Date: <?php echo e($paiement->date_soumission->format('d/m/Y')); ?><br>
                Heure: <?php echo e($paiement->date_soumission->format('H:i')); ?>

            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>DÉSIGNATION DE L'OPÉRATION</th>
                    <th style="text-align: right;">MONTANT (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php echo e(ucfirst($paiement->categorie)); ?> - Adhésion <?php echo e($paiement->adhesion->numero_adhesion); ?><br>
                        <small>Périodicité: <?php echo e(ucfirst($paiement->adhesion->plan->periodicite)); ?></small>
                    </td>
                    <td style="text-align: right;"><?php echo e(number_format($paiement->montant, 0, ',', ' ')); ?> FCFA</td>
                </tr>
                <tr class="total-row">
                    <td style="text-align: right;">TOTAL PAYÉ :</td>
                    <td style="text-align: right;"><?php echo e(number_format($paiement->montant, 0, ',', ' ')); ?> FCFA</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <p><strong>Mode de paiement:</strong> <?php echo e(ucfirst($paiement->mode_paiement)); ?></p>
            <?php if($paiement->reference_paiement): ?>
                <p><strong>Référence:</strong> <?php echo e($paiement->reference_paiement); ?></p>
            <?php endif; ?>
        </div>

        <div class="stamp">
            <p>Fait à Ouagadougou, le <?php echo e(date('d/m/Y')); ?></p>
            <div style="margin-top: 10px; height: 100px;">
                <p>Cachet SIFCash</p>
                <!-- Placeholder pour la signature électronique ou manuelle -->
            </div>
        </div>

        <div class="footer">
            <?php if($paiement->statut === 'validé'): ?>
                <p>Ce document confirme la réception de votre paiement par SIFCash-Burkina.<br>
                Votre compte épargne a été crédité du montant indiqué ci-dessus.</p>
            <?php else: ?>
                <p>Ce document fait office de déclaration de votre versement en espèces.<br>
                Merci de vous présenter à un bureau SIFCash avec ce document pour effectuer le paiement et valider votre dossier.</p>
            <?php endif; ?>
            <p>SIFCash-Burkina - "Construisons l'avenir ensemble"</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Projetsw\sif-project\resources\views/pdf/quittance.blade.php ENDPATH**/ ?>