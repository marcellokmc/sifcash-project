<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Contrat d'Adhésion - {{ $adherent->membre_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 7.5px;
            line-height: 1.25;
            margin: 0;
            padding: 12px 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        
        .logo-img {
            max-height: 45px;
            width: auto;
            margin-bottom: 6px;
        }
        
        .logo {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 4px;
        }
        
        .sub-title {
            font-size: 7px;
            color: #7f8c8d;
        }
        
        .contract-title {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0 8px 0;
            text-transform: uppercase;
        }
        
        .section {
            margin-bottom: 8px;
        }
        
        .section-title {
            font-size: 9px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 2px;
            margin-bottom: 5px;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 8px;
        }
        
        .info-table td {
            padding: 2px 4px;
            vertical-align: top;
        }
        
        .info-table .label {
            font-weight: bold;
            width: 35%;
            color: #2c3e50;
            font-size: 7px;
        }
        
        .info-table .value {
            width: 65%;
            font-size: 7px;
        }
        
        .two-columns {
            display: table;
            width: 100%;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        
        .signature-section {
            position: fixed;
            bottom: 25mm;
            left: 20px;
            right: 20px;
            display: table;
            width: calc(100% - 40px);
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 8px;
            font-size: 7.5px;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin: 15px auto 4px auto;
            height: 1px;
            width: 60%;
        }
        
        .footer {
            position: fixed;
            bottom: 12px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 6px;
            color: #7f8c8d;
            border-top: 1px solid #bdc3c7;
            padding-top: 4px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .badge {
            background-color: #3498db;
            color: white;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 6px;
        }
        
        .badge.success {
            background-color: #27ae60;
        }
        
        .badge.warning {
            background-color: #f39c12;
        }
        
        .important {
            background-color: #ecf0f1;
            padding: 6px;
            border-left: 2px solid #3498db;
            margin: 6px 0;
            font-size: 7.5px;
        }
        
        p {
            margin: 3px 0;
        }
        
        ul {
            margin: 3px 0;
            padding-left: 15px;
        }
        
        ul li {
            margin: 1px 0;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <img src="{{ public_path('img/SIF logo .jpg') }}" alt="SIFcash-Burkina" class="logo-img">
        <div class="logo">SIFCash-Burkina</div>
        <div class="sub-title">Société d'Investissement Financier</div>
        <div class="sub-title">Ouagadougou, Burkina Faso</div>
    </div>

    <!-- Titre du contrat -->
    <div class="contract-title">
        Contrat d'Adhésion
    </div>

    <!-- Informations de base -->
    <div class="section">
        <div class="section-title">Informations du Contrat</div>
        <div class="two-columns">
            <div class="column">
                <table class="info-table">
                    <tr>
                        <td class="label">Numéro de membre :</td>
                        <td class="value"><strong>{{ $adherent->membre_id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Date d'adhésion :</td>
                        <td class="value"><strong>{{ $adherent->created_at->format('d/m/Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Date d'activation :</td>
                        <td class="value">
                            @if($adherent->date_activation)
                                {{ $adherent->date_activation->format('d/m/Y H:i') }}
                            @else
                                En attente d'activation
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Statut du compte :</td>
                        <td class="value">
                            @if($adherent->isActif())
                                <span class="badge success">Actif</span>
                            @elseif($adherent->isEnAttente())
                                <span class="badge warning">En attente</span>
                            @else
                                <span class="badge">Inactif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="column">
                <table class="info-table">
                    <tr>
                        <td class="label">Agence de rattachement :</td>
                        <td class="value">
                            @if($adherent->agence)
                                <strong>{{ $adherent->agence->nom }}</strong><br>
                                <small>{{ $adherent->agence->ville ?? $adherent->agence->departement }}</small>
                            @else
                                <span class="badge warning">Non affecté</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Code agence :</td>
                        <td class="value">
                            @if($adherent->agence)
                                <strong>{{ $adherent->agence->code ?? 'N/A' }}</strong>
                            @else
                                <span class="badge warning">Non affecté</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Contact agence :</td>
                        <td class="value">
                            @if($adherent->agence && $adherent->agence->contact)
                                {{ $adherent->agence->contact }}
                            @else
                                Non renseigné
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Informations personnelles -->
    <div class="section">
        <div class="section-title">Informations Personnelles de l'Adhérent</div>
        <div class="two-columns">
            <div class="column">
                <table class="info-table">
                    <tr>
                        <td class="label">Nom complet :</td>
                        <td class="value">{{ $adherent->nom_complet }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date de naissance :</td>
                        <td class="value">{{ $adherent->date_naissance->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Lieu de naissance :</td>
                        <td class="value">{{ $adherent->lieu_naissance }}</td>
                    </tr>
                    <tr>
                        <td class="label">Situation familiale :</td>
                        <td class="value">{{ ucfirst($adherent->situation_famille) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Profession :</td>
                        <td class="value">{{ $adherent->profession }}</td>
                    </tr>
                </table>
            </div>
            <div class="column">
                <table class="info-table">
                    <tr>
                        <td class="label">Téléphone :</td>
                        <td class="value">{{ $adherent->telephone }}</td>
                    </tr>
                    <tr>
                        <td class="label">Téléphone secondaire :</td>
                        <td class="value">{{ $adherent->telephone_secondaire ?? 'Non renseigné' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email :</td>
                        <td class="value">{{ $adherent->email }}</td>
                    </tr>
                    <tr>
                        <td class="label">Adresse :</td>
                        <td class="value">{{ $adherent->adresse }}</td>
                    </tr>
                    <tr>
                        <td class="label">Résidence :</td>
                        <td class="value">{{ $adherent->residence }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Contacts d'urgence -->
    <div class="section">
        <div class="section-title">Contacts d'Urgence</div>
        <div class="two-columns">
            <div class="column">
                <strong style="font-size: 9px;">Contact Principal :</strong>
                <table class="info-table">
                    <tr>
                        <td class="label">Nom :</td>
                        <td class="value">{{ $adherent->contact_urgence_nom ?? 'Non renseigné' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Lien :</td>
                        <td class="value">{{ ucfirst($adherent->contact_urgence_lien_parente ?? 'Non renseigné') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Téléphone :</td>
                        <td class="value">{{ $adherent->contact_urgence_telephone ?? 'Non renseigné' }}</td>
                    </tr>
                </table>
            </div>
            <div class="column">
                <strong style="font-size: 9px;">Contact Secondaire :</strong>
                <table class="info-table">
                    <tr>
                        <td class="label">Nom :</td>
                        <td class="value">{{ $adherent->contact_urgence_secondaire_nom ?? 'Non renseigné' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Lien :</td>
                        <td class="value">{{ ucfirst($adherent->contact_urgence_secondaire_lien_parente ?? 'Non renseigné') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Téléphone :</td>
                        <td class="value">{{ $adherent->contact_urgence_secondaire_telephone ?? 'Non renseigné' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Adhésions -->
    @if($adherent->adhesions->isNotEmpty())
    <div class="section">
        <div class="section-title">Adhésions aux Plans</div>
        @foreach($adherent->adhesions as $adhesion)
        <table class="info-table">
            <tr>
                <td class="label">Plan :</td>
                <td class="value"><strong>{{ $adhesion->plan->nom ?? 'Plan non défini' }}</strong></td>
            </tr>
            <tr>
                <td class="label">Date d'adhésion :</td>
                <td class="value">{{ $adhesion->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Statut :</td>
                <td class="value">
                    @if($adhesion->statut == 'active')
                        <span class="badge success">Active</span>
                    @elseif($adhesion->statut == 'suspendue')
                        <span class="badge warning">Suspendue</span>
                    @else
                        <span class="badge">{{ ucfirst($adhesion->statut) }}</span>
                    @endif
                </td>
            </tr>
        </table>
        @endforeach
    </div>
    @endif

    <!-- Clauses du contrat -->
    <div class="section">
        <div class="section-title">Conditions Générales d'Adhésion</div>
        
        <div class="important">
            <strong>Article 1 - Objet du contrat :</strong> Le présent contrat définit les conditions d'adhésion de {{ $adherent->nom_complet }} à la Société d'Investissement Financier (SIFCash-Burkina).
        </div>

        <p><strong>Article 2 - Engagements de l'adhérent :</strong> L'adhérent s'engage à respecter le règlement intérieur de SIFCash-Burkina, fournir des informations exactes et à jour, signaler tout changement de situation personnelle et honorer ses engagements financiers.</p>

        <p><strong>Article 3 - Engagements de SIFCash-Burkina :</strong> SIFCash-Burkina s'engage à protéger la confidentialité des données personnelles, offrir des services financiers de qualité, respecter les termes des plans d'adhésion souscrits et informer l'adhérent de toute modification des conditions.</p>

        <p><strong>Article 4 - Conditions d'épargne :</strong> Les dépôts d'épargne sont sécurisés et rémunérés selon les taux en vigueur. L'adhérent peut effectuer des retraits partiels après une période minimum de détention de 3 mois. Les retraits anticipés sont soumis à conditions et peuvent entraîner une réduction des intérêts accumulés. Un retrait total clôture l'adhésion au plan d'épargne concerné.</p>

        <p><strong>Article 5 - Durée du contrat :</strong> Ce contrat prend effet à la date d'activation du compte et reste valable tant que l'adhésion est active.</p>

        <p><strong>Article 6 - Résiliation :</strong> Le contrat peut être résilié par l'une ou l'autre des parties moyennant un préavis de 30 jours. En cas de résiliation, l'adhérent peut récupérer son épargne selon les modalités définies à l'article 4.</p>
    </div>

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            <strong>L'Adhérent</strong>
            <div class="signature-line"></div>
            <p>{{ $adherent->nom_complet }}</p>
            <p>Date : ________________</p>
        </div>
        <div class="signature-box">
            <strong>SIFCash-Burkina</strong>
            <div class="signature-line"></div>
            <p>Le Directeur Général</p>
            <p>Date : {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        SIFCash-Burkina - Société d'Investissement Financier<br>
        Contrat d'Adhésion - Membre {{ $adherent->membre_id }} - Généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>