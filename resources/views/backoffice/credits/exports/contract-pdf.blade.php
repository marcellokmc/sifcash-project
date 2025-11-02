<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contrat de Crédit - {{ $credit->id }}</title>
    <style>
        @page { margin: 15mm 12mm; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9.5px; color: #1a1a1a; line-height: 1.35; }
        .header { margin-bottom: 8px; border-bottom: 2px solid #2563eb; padding-bottom: 8px; }
        .brand { width: 100%; }
        .brand td { vertical-align: middle; padding: 4px 0; }
        .brand .logo { width: 60px; }
        .brand .title { color: #1e40af; font-size: 18px; font-weight: 700; letter-spacing: -0.5px; }
        .brand .subtitle { color: #475569; font-size: 8.5px; margin-top: 2px; }
        .contract-badge { background: #2563eb; color: #fff; padding: 3px 10px; border-radius: 10px; font-size: 8px; font-weight: 600; display: inline-block; margin-top: 3px; }
        .kpis { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .kpis td { padding: 6px 8px; border: 1px solid #e2e8f0; background: #f8fafc; text-align: center; }
        .kpis .label { color: #64748b; font-weight: 600; font-size: 8px; text-transform: uppercase; letter-spacing: 0.3px; display: block; margin-bottom: 3px; }
        .kpis .value { color: #1e293b; font-weight: 700; font-size: 11px; display: block; }
        .section { margin-top: 8px; page-break-inside: avoid; }
        .section h3 { margin: 0 0 6px 0; color: #1e40af; font-size: 11.5px; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 4px; font-weight: 700; }
        .two-col { width: 100%; border-collapse: collapse; }
        .two-col td { vertical-align: top; width: 50%; padding-right: 10px; }
        .info { width: 100%; border-collapse: collapse; background: #fff; }
        .info tr { border-bottom: 1px solid #f1f5f9; }
        .info tr:last-child { border-bottom: none; }
        .info td { padding: 4px 0; }
        .info .label { width: 45%; color: #475569; font-weight: 600; font-size: 9.5px; }
        .info .value { width: 55%; color: #1e293b; font-weight: 500; font-size: 10px; }
        .box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 8px; margin-top: 6px; }
        .box h4 { margin: 0 0 5px 0; color: #334155; font-size: 10px; font-weight: 700; }
        .box p { margin: 0; line-height: 1.4; color: #475569; font-size: 9px; }
        .highlight { background: #fef3c7; padding: 6px 10px; border-left: 3px solid #f59e0b; margin: 6px 0; border-radius: 3px; font-size: 8.5px; }
        .highlight strong { color: #92400e; }
        .note { margin-top: 5px; color: #475569; line-height: 1.4; text-align: justify; font-size: 8.5px; }
        .signature { position: absolute; bottom: 50mm; left: 0; right: 0; page-break-inside: avoid; }
        .sig-table { width: 100%; table-layout: fixed; margin-top: 12px; }
        .sig-table td { text-align: center; padding: 0 15px; }
        .sig-line { border-top: 1.5px solid #334155; width: 70%; height: 1px; margin: 18px auto 6px; }
        .sig-label { font-weight: 600; color: #334155; font-size: 9px; }
        .sig-name { color: #64748b; font-size: 8px; margin-top: 2px; }
        .sig-decl { background:#f8fafc; border:1px solid #e2e8f0; padding:8px; border-radius:4px; margin-bottom:12px; font-size:8px; color:#64748b; line-height:1.35; }
        .footer { position: fixed; bottom: 8mm; left: 0; right: 0; text-align: center; font-size: 7px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 6px; }
    </style>
</head>
<body>
    @php
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
    @endphp

    <div class="header">
        <table class="brand">
            <tr>
                <td class="logo">
                    @if($logo)
                        <img src="{{ $logo }}" alt="Logo" style="height:60px;">
                    @endif
                </td>
                <td>
                    <div class="title">SIFCash - Burkina Faso</div>
                    <div class="subtitle">Établissement de Microfinance agréé par le Ministère des Finances</div>
                    <div class="contract-badge">CONTRAT DE CRÉDIT N° {{ str_pad($credit->id, 6, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td style="text-align:right; color:#64748b; font-size:9px;">
                    <strong style="color:#1e293b;">Membre: {{ $credit->adherent->membre_id ?? 'N/A' }}</strong><br>
                    Émis le: {{ $date }}<br>
                    Statut: <strong style="color:#16a34a;">{{ ucfirst($credit->statut) }}</strong>
                </td>
            </tr>
        </table>

        <table class="kpis">
            <tr>
                <td>
                    <span class="label">Montant accordé</span>
                    <span class="value">{{ number_format($credit->montant_accorde, 0, ',', ' ') }} FCFA</span>
                </td>
                <td>
                    <span class="label">Type</span>
                    <span class="value">{{ ucfirst($credit->type_credit ?? 'Personnel') }}</span>
                </td>
                <td>
                    <span class="label">Durée</span>
                    <span class="value">{{ $credit->duree }} mois</span>
                </td>
                <td>
                    <span class="label">Périodicité</span>
                    <span class="value">{{ ucfirst($credit->periodicite) }}</span>
                </td>
                <td>
                    <span class="label">Statut</span>
                    <span class="value">{{ ucfirst($credit->statut) }}</span>
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
                        <tr><td class="label">Référence</td><td class="value">#{{ str_pad($credit->id, 6, '0', STR_PAD_LEFT) }}</td></tr>
                        <tr><td class="label">Type de crédit</td><td class="value">{{ ucfirst($credit->type_credit ?? 'Personnel') }}</td></tr>
                        <tr><td class="label">Date de demande</td><td class="value">{{ \Carbon\Carbon::parse($credit->date_demande)->format('d/m/Y') }}</td></tr>
                        @if($credit->date_validation)
                        <tr><td class="label">Date d'approbation</td><td class="value">{{ \Carbon\Carbon::parse($credit->date_validation)->format('d/m/Y') }}</td></tr>
                        @endif
                        @if($credit->date_debut_remboursement)
                        <tr><td class="label">Début remboursement</td><td class="value">{{ \Carbon\Carbon::parse($credit->date_debut_remboursement)->format('d/m/Y') }}</td></tr>
                        @endif
                        <tr><td class="label">Montant demandé</td><td class="value">{{ number_format($credit->montant_demande, 0, ',', ' ') }} FCFA</td></tr>
                        <tr><td class="label">Montant accordé</td><td class="value" style="color:#16a34a; font-weight:700;">{{ number_format($credit->montant_accorde, 0, ',', ' ') }} FCFA</td></tr>
                    </table>
                </td>
                <td>
                    <h3>👤 Bénéficiaire</h3>
                    <table class="info">
                        <tr><td class="label">Nom complet</td><td class="value">{{ $credit->adherent->nom_complet ?? 'N/A' }}</td></tr>
                        <tr><td class="label">Téléphone</td><td class="value">{{ $credit->adherent->telephone ?? 'N/A' }}</td></tr>
                        <tr><td class="label">Email</td><td class="value">{{ $credit->adherent->email ?? 'N/A' }}</td></tr>
                        <tr><td class="label">Adresse</td><td class="value">{{ $credit->adherent->adresse ?? 'N/A' }}</td></tr>
                        @if($credit->adherent->profession)
                        <tr><td class="label">Profession</td><td class="value">{{ $credit->adherent->profession }}</td></tr>
                        @endif
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
            <p>{{ $credit->motif ?? 'Non spécifié' }}</p>
            @if($credit->garanties)
            <h4 style="margin-top:8px;">Garanties</h4>
            <p>{{ $credit->garanties }}</p>
            @endif
        </div>
        
        <div class="box">
            <h4>Remboursement</h4>
            <p>
                Périodicité <strong>{{ $credit->periodicite }}</strong> sur <strong>{{ $credit->duree }} mois</strong>.
                Modalités finales et première échéance {{ $credit->date_debut_remboursement ? '(' . \Carbon\Carbon::parse($credit->date_debut_remboursement)->format('d/m/Y') . ')' : '' }} à définir en agence.
                <br><strong>Clauses :</strong> Pénalités en cas de retard. Remboursement anticipé autorisé sans pénalité.
            </p>
        </div>
    </div>

    <div class="signature">
        <div class="sig-decl">
            <strong style="color:#1e293b;">Déclaration :</strong> Je soussigné(e) <strong>{{ $credit->adherent->nom_complet ?? '______________________' }}</strong>,
            reconnais avoir pris connaissance des clauses du présent contrat et m'engage à rembourser le montant de <strong>{{ number_format($credit->montant_accorde, 0, ',', ' ') }} FCFA</strong>
            selon les modalités à finaliser en agence.
        </div>
        
        <table class="sig-table">
            <tr>
                <td>
                    <div class="sig-line"></div>
                    <div class="sig-label">Le Bénéficiaire</div>
                    <div class="sig-name">{{ $credit->adherent->nom_complet ?? '' }}</div>
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
        © {{ date('Y') }} SIFCash - Burkina Faso • Siège social : Ouagadougou, Burkina Faso • Document contractuel confidentiel
    </div>
</body>
</html>
