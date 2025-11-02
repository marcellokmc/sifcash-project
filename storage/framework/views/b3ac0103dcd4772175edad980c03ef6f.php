<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contrat de Crédit - <?php echo e($credit->id); ?></title>
    <style>
        @page { margin: 15mm 12mm; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 8px; color: #1a1a1a; line-height: 1.3; }
        .header { margin-bottom: 6px; border-bottom: 2px solid #2563eb; padding-bottom: 6px; }
        .brand { width: 100%; }
        .brand td { vertical-align: middle; padding: 2px 0; }
        .brand .logo { width: 50px; }
        .brand .title { color: #1e40af; font-size: 14px; font-weight: 700; letter-spacing: -0.5px; }
        .brand .subtitle { color: #475569; font-size: 7px; margin-top: 2px; }
        .contract-badge { background: #2563eb; color: #fff; padding: 2px 8px; border-radius: 8px; font-size: 7px; font-weight: 600; display: inline-block; margin-top: 2px; }
        .kpis { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .kpis td { padding: 4px 6px; border: 1px solid #e2e8f0; background: #f8fafc; text-align: center; }
        .kpis .label { color: #64748b; font-weight: 600; font-size: 6.5px; text-transform: uppercase; letter-spacing: 0.2px; display: block; margin-bottom: 2px; }
        .kpis .value { color: #1e293b; font-weight: 700; font-size: 9px; display: block; }
        .section { margin-top: 6px; page-break-inside: avoid; }
        .section h3 { margin: 0 0 4px 0; color: #1e40af; font-size: 9px; border-bottom: 1px solid #e2e8f0; padding-bottom: 3px; font-weight: 700; }
        .two-col { width: 100%; border-collapse: collapse; }
        .two-col td { vertical-align: top; width: 50%; padding-right: 8px; }
        .info { width: 100%; border-collapse: collapse; background: #fff; }
        .info tr { border-bottom: 1px solid #f1f5f9; }
        .info tr:last-child { border-bottom: none; }
        .info td { padding: 3px 0; }
        .info .label { width: 45%; color: #475569; font-weight: 600; font-size: 7.5px; }
        .info .value { width: 55%; color: #1e293b; font-weight: 500; font-size: 8px; }
        .box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 3px; padding: 6px; margin-top: 4px; }
        .box h4 { margin: 0 0 3px 0; color: #334155; font-size: 8px; font-weight: 700; }
        .box p { margin: 0; line-height: 1.3; color: #475569; font-size: 7.5px; }
        .highlight { background: #fef3c7; padding: 4px 8px; border-left: 2px solid #f59e0b; margin: 4px 0; border-radius: 2px; font-size: 7px; }
        .highlight strong { color: #92400e; }
        .note { margin-top: 4px; color: #475569; line-height: 1.3; text-align: justify; font-size: 7px; }
        .signature { position: fixed; bottom: 15mm; left: 12mm; right: 12mm; page-break-inside: avoid; }
        .sig-table { width: 100%; table-layout: fixed; margin-top: 8px; }
        .sig-table td { text-align: center; padding: 0 10px; }
        .sig-line { border-top: 1px solid #334155; width: 65%; height: 1px; margin: 12px auto 4px; }
        .sig-label { font-weight: 600; color: #334155; font-size: 7.5px; }
        .sig-name { color: #64748b; font-size: 7px; margin-top: 1px; }
        .sig-decl { background:#f8fafc; border:1px solid #e2e8f0; padding:6px; border-radius:3px; margin-bottom:8px; font-size:7px; color:#64748b; line-height:1.3; }
        .footer { position: fixed; bottom: 8mm; left: 0; right: 0; text-align: center; font-size: 6px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 4px; }
    </style>
</head>
<body>
    <?php
        $logo = null;
        $candidates = [
            public_path('img/SIF logo .jpg'),
            public_path('img/SIF logo.jpg'),
            public_path('img/SIF_logo.jpg'),
            public_path('img/logo.png'),
            public_path('img/logo.jpg'),
            public_path('img/logo.jpeg'),
            public_path('img/logo.svg'),
        ];
        foreach ($candidates as $c) { if (file_exists($c)) { $logo = $c; break; } }
    ?>

    <div class="header">
        <table class="brand">
            <tr>
                <td class="logo">
                    <?php if($logo): ?>
                        <img src="<?php echo e($logo); ?>" alt="Logo" style="height:50px;width:auto;">
                    <?php endif; ?>
                </td>
                <td>
                    <div class="title">SIFCash - Burkina Faso</div>
                    <div class="subtitle">Établissement de Microfinance agréé par le Ministère des Finances</div>
                    <div class="contract-badge">CONTRAT DE CRÉDIT N° <?php echo e(str_pad($credit->id, 6, '0', STR_PAD_LEFT)); ?></div>
                </td>
                <td style="text-align:right; color:#64748b; font-size:7.5px;">
                    <strong style="color:#1e293b;">Membre: <?php echo e($credit->adherent->membre_id ?? 'N/A'); ?></strong><br>
                    Émis le: <?php echo e($date); ?><br>
                    Statut: <strong style="color:#16a34a;"><?php echo e(ucfirst($credit->statut)); ?></strong>
                </td>
            </tr>
        </table>

        <table class="kpis">
            <tr>
                <td>
                    <span class="label">Montant accordé</span>
                    <span class="value"><?php echo e(number_format($credit->montant_accorde, 0, ',', ' ')); ?> FCFA</span>
                </td>
                <td>
                    <span class="label">Type</span>
                    <span class="value"><?php echo e(ucfirst($credit->type_credit ?? 'Personnel')); ?></span>
                </td>
                <td>
                    <span class="label">Durée</span>
                    <span class="value"><?php echo e($credit->duree); ?> mois</span>
                </td>
                <td>
                    <span class="label">Périodicité</span>
                    <span class="value"><?php echo e(ucfirst($credit->periodicite)); ?></span>
                </td>
                <td>
                    <span class="label">Statut</span>
                    <span class="value"><?php echo e(ucfirst($credit->statut)); ?></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table class="two-col">
            <tr>
                <td>
                    <h3>📄 Informations du Crédit</h3>
                    <table class="info">
                        <tr><td class="label">Référence</td><td class="value">#<?php echo e(str_pad($credit->id, 6, '0', STR_PAD_LEFT)); ?></td></tr>
                        <tr><td class="label">Type de crédit</td><td class="value"><?php echo e(ucfirst($credit->type_credit ?? 'Personnel')); ?></td></tr>
                        <tr><td class="label">Date de demande</td><td class="value"><?php echo e(\Carbon\Carbon::parse($credit->date_demande)->format('d/m/Y')); ?></td></tr>
                        <?php if($credit->date_validation): ?>
                        <tr><td class="label">Date d'approbation</td><td class="value"><?php echo e(\Carbon\Carbon::parse($credit->date_validation)->format('d/m/Y')); ?></td></tr>
                        <?php endif; ?>
                        <?php if($credit->date_debut_remboursement): ?>
                        <tr><td class="label">Début remboursement</td><td class="value"><?php echo e(\Carbon\Carbon::parse($credit->date_debut_remboursement)->format('d/m/Y')); ?></td></tr>
                        <?php endif; ?>
                        <tr><td class="label">Montant demandé</td><td class="value"><?php echo e(number_format($credit->montant_demande, 0, ',', ' ')); ?> FCFA</td></tr>
                        <tr><td class="label">Montant accordé</td><td class="value" style="color:#16a34a; font-weight:700;"><?php echo e(number_format($credit->montant_accorde, 0, ',', ' ')); ?> FCFA</td></tr>
                    </table>
                </td>
                <td>
                    <h3>👤 Bénéficiaire</h3>
                    <table class="info">
                        <tr><td class="label">Nom complet</td><td class="value"><?php echo e($credit->adherent->nom_complet ?? 'N/A'); ?></td></tr>
                        <tr><td class="label">Téléphone</td><td class="value"><?php echo e($credit->adherent->telephone ?? 'N/A'); ?></td></tr>
                        <tr><td class="label">Email</td><td class="value"><?php echo e($credit->adherent->email ?? 'N/A'); ?></td></tr>
                        <tr><td class="label">Adresse</td><td class="value"><?php echo e($credit->adherent->adresse ?? 'N/A'); ?></td></tr>
                        <?php if($credit->adherent->profession): ?>
                        <tr><td class="label">Profession</td><td class="value"><?php echo e($credit->adherent->profession); ?></td></tr>
                        <?php endif; ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>


    <div class="section">
        <h3>📝 Objet et Conditions du Crédit</h3>
        
        <div class="highlight">
            <strong>🏦 Information :</strong> Pré-approbation de crédit. Conditions finales (taux, frais, échéancier) à définir en agence.
        </div>
        
        <div class="box">
            <h4>Objet du crédit</h4>
            <p><?php echo e($credit->motif ?? 'Non spécifié'); ?></p>
            <?php if($credit->garanties): ?>
            <h4 style="margin-top:8px;">Garanties</h4>
            <p><?php echo e($credit->garanties); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="box">
            <h4>Remboursement</h4>
            <p>
                Périodicité <strong><?php echo e($credit->periodicite); ?></strong> sur <strong><?php echo e($credit->duree); ?> mois</strong>.
                Modalités finales et première échéance <?php echo e($credit->date_debut_remboursement ? '(' . \Carbon\Carbon::parse($credit->date_debut_remboursement)->format('d/m/Y') . ')' : ''); ?> à définir en agence.
                <br><strong>Clauses :</strong> Pénalités en cas de retard. Remboursement anticipé autorisé sans pénalité.
            </p>
        </div>
    </div>

    <div class="signature">
        <div class="sig-decl">
            <strong style="color:#1e293b;">Déclaration :</strong> Je soussigné(e) <strong><?php echo e($credit->adherent->nom_complet ?? '______________________'); ?></strong>,
            reconnais avoir pris connaissance des clauses du présent contrat et m'engage à rembourser le montant de <strong><?php echo e(number_format($credit->montant_accorde, 0, ',', ' ')); ?> FCFA</strong>
            selon les modalités à finaliser en agence.
        </div>
        
        <table class="sig-table">
            <tr>
                <td>
                    <div class="sig-line"></div>
                    <div class="sig-label">Le Bénéficiaire</div>
                    <div class="sig-name"><?php echo e($credit->adherent->nom_complet ?? ''); ?></div>
                </td>
                <td>
                    <div class="sig-line"></div>
                    <div class="sig-label">Pour SIFCash - Burkina Faso</div>
                    <div class="sig-name">Responsable des Crédits</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        © <?php echo e(date('Y')); ?> SIFCash - Burkina Faso • Siège social : Ouagadougou, Burkina Faso • Document contractuel confidentiel
    </div>
</body>
</html>
<?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/credits/exports/contract-pdf.blade.php ENDPATH**/ ?>