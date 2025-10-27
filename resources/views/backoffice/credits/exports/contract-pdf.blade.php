<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contrat de Crédit - {{ $credit->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 30px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #667eea;
            font-size: 22px;
            margin: 0 0 10px 0;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
            margin: 5px 0;
        }
        .info-block {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
        }
        .info-row {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #667eea;
            display: inline-block;
            width: 180px;
        }
        .value {
            color: #333;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #667eea;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #667eea;
        }
        .content {
            text-align: justify;
            line-height: 1.6;
            margin: 15px 0;
        }
        .signature-block {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-row {
            display: table;
            width: 100%;
            margin-top: 40px;
        }
        .signature-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin: 60px auto 10px auto;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .highlight {
            background: #fff3cd;
            padding: 2px 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📄 SIFCash-Burkina FASO</h1>
        <div class="subtitle">Système d'Information Financière</div>
        <div class="subtitle" style="font-weight: bold; margin-top: 10px;">CONTRAT DE CRÉDIT N° {{ $credit->id }}</div>
        <div class="subtitle">Établi le {{ $date }}</div>
    </div>

    <div class="section-title">1. INFORMATIONS DU CRÉDIT</div>
    <div class="info-block">
        <div class="info-row">
            <span class="label">Numéro de crédit :</span>
            <span class="value">{{ $credit->id }}</span>
        </div>
        <div class="info-row">
            <span class="label">Date de demande :</span>
            <span class="value">{{ \Carbon\Carbon::parse($credit->date_demande)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Montant demandé :</span>
            <span class="value highlight">{{ number_format($credit->montant_demande, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="info-row">
            <span class="label">Montant accordé :</span>
            <span class="value highlight">{{ number_format($credit->montant_accorde, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="info-row">
            <span class="label">Taux d'intérêt :</span>
            <span class="value">{{ number_format($credit->taux, 2) }}%</span>
        </div>
        <div class="info-row">
            <span class="label">Durée :</span>
            <span class="value">{{ $credit->duree }} mois</span>
        </div>
        <div class="info-row">
            <span class="label">Périodicité :</span>
            <span class="value">{{ ucfirst($credit->periodicite) }}</span>
        </div>
        <div class="info-row">
            <span class="label">Frais de dossier :</span>
            <span class="value">{{ number_format($credit->frais_dossier, 0, ',', ' ') }} FCFA</span>
        </div>
        @if($credit->date_debut_remboursement)
        <div class="info-row">
            <span class="label">Début remboursement :</span>
            <span class="value">{{ \Carbon\Carbon::parse($credit->date_debut_remboursement)->format('d/m/Y') }}</span>
        </div>
        @endif
    </div>

    <div class="section-title">2. INFORMATIONS DU BÉNÉFICIAIRE</div>
    <div class="info-block">
        <div class="info-row">
            <span class="label">Nom complet :</span>
            <span class="value">{{ $credit->adherent->nom_complet ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">ID Membre :</span>
            <span class="value">{{ $credit->adherent->membre_id ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Téléphone :</span>
            <span class="value">{{ $credit->adherent->telephone ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Email :</span>
            <span class="value">{{ $credit->adherent->email ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Adresse :</span>
            <span class="value">{{ $credit->adherent->adresse ?? 'N/A' }}</span>
        </div>
    </div>

    <div class="section-title">3. CONDITIONS DU CONTRAT</div>
    <div class="content">
        <p><strong>3.1 Objet du crédit</strong></p>
        <p>{{ $credit->motif ?? 'Non spécifié' }}</p>

        @if($credit->garanties)
        <p><strong>3.2 Garanties</strong></p>
        <p>{{ $credit->garanties }}</p>
        @endif

        <p><strong>3.3 Modalités de remboursement</strong></p>
        <p>
            Le bénéficiaire s'engage à rembourser le montant du crédit selon l'échéancier établi, 
            avec une périodicité {{ $credit->periodicite }} sur une durée de {{ $credit->duree }} mois.
            Le taux d'intérêt appliqué est de {{ number_format($credit->taux, 2) }}%.
        </p>

        <p><strong>3.4 Pénalités de retard</strong></p>
        <p>
            En cas de retard de paiement, des pénalités pourront être appliquées conformément 
            au règlement intérieur de la SIF.
        </p>

        <p><strong>3.5 Remboursement anticipé</strong></p>
        <p>
            Le bénéficiaire peut procéder au remboursement anticipé du crédit sans pénalités.
        </p>
    </div>

    <div class="signature-block">
        <div class="section-title">4. SIGNATURES</div>
        <div class="signature-row">
            <div class="signature-cell">
                <p><strong>Le Bénéficiaire</strong></p>
                <p>{{ $credit->adherent->nom_complet ?? '' }}</p>
                <div class="signature-line"></div>
                <p>Date : ________________</p>
            </div>
            <div class="signature-cell">
                <p><strong>Pour la SIFCash-Burkina Faso</strong></p>
                <p>Le Responsable des Crédits</p>
                <div class="signature-line"></div>
                <p>Date : ________________</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} SIFCash-Burkina Faso - Système d'Information Financière</p>
        <p>Document contractuel confidentiel - Usage strictement interne</p>
    </div>
</body>
</html>
