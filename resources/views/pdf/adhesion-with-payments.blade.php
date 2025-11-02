<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adhésion #{{ $adhesion->id }} avec Paiements</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            color: #333;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 8px;
        }
        .logo-img {
            max-height: 40px;
            width: auto;
            margin-bottom: 5px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 13px;
        }
        .header p {
            margin: 2px 0;
            color: #666;
            font-size: 7px;
        }
        .section {
            margin-bottom: 10px;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 5px;
            margin-bottom: 6px;
            font-size: 9px;
            font-weight: bold;
            color: #007bff;
            border-left: 3px solid #007bff;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            padding: 4px 5px;
            font-weight: bold;
            background-color: #f8f9fa;
            font-size: 7px;
        }
        .info-value {
            display: table-cell;
            padding: 4px 5px;
            border-bottom: 1px solid #e9ecef;
            font-size: 7px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table thead {
            background-color: #007bff;
            color: white;
        }
        table.data-table th {
            padding: 5px 3px;
            text-align: left;
            font-size: 7px;
            font-weight: bold;
        }
        table.data-table td {
            padding: 4px 3px;
            border-bottom: 1px solid #e9ecef;
            font-size: 7px;
        }
        table.data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 6px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .summary-box {
            background-color: #e7f3ff;
            padding: 8px;
            border-radius: 3px;
            margin: 10px 0;
            border: 1px solid #007bff;
        }
        .summary-box h3 {
            margin: 0 0 5px 0;
            color: #007bff;
            font-size: 9px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px dashed #ccc;
            font-size: 7px;
        }
        .summary-item:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 8px;
            margin-top: 3px;
            padding-top: 5px;
        }
        .footer {
            position: fixed;
            bottom: 10mm;
            left: 0;
            right: 0;
            padding-top: 8px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            font-size: 6px;
            color: #999;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('img/SIF logo .jpg') }}" alt="SIFcash-Burkina" class="logo-img">
        <h1>Adhésion avec Historique des Paiements</h1>
        <p>Adhésion #{{ $adhesion->id }} - {{ $adhesion->numero_adhesion }}</p>
        <p>Date d'édition : {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Informations de l'Adhérent</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nom complet</div>
                <div class="info-value">{{ $adhesion->adherent->nom ?? 'N/A' }} {{ $adhesion->adherent->prenom ?? '' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Matricule</div>
                <div class="info-value">{{ $adhesion->adherent->matricule ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Téléphone</div>
                <div class="info-value">{{ $adhesion->adherent->telephone ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Détails de l'Adhésion</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Plan</div>
                <div class="info-value">{{ $adhesion->plan->nom ?? 'Plan supprimé' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Montant souscrit</div>
                <div class="info-value" style="font-weight: bold; color: #007bff;">{{ number_format($adhesion->montant_souscrit, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="info-row">
                <div class="info-label">Période</div>
                <div class="info-value">
                    Du {{ $adhesion->date_debut ? \Carbon\Carbon::parse($adhesion->date_debut)->format('d/m/Y') : 'N/A' }}
                    au {{ $adhesion->date_fin ? \Carbon\Carbon::parse($adhesion->date_fin)->format('d/m/Y') : 'N/A' }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Statut</div>
                <div class="info-value">{{ ucfirst(str_replace('_', ' ', $adhesion->statut)) }}</div>
            </div>
        </div>
    </div>

    @php
        $paiementsValides = $adhesion->paiements->where('statut', 'validé');
        $totalPaiements = $paiementsValides->sum('montant');
        $paiementsEnAttente = $adhesion->paiements->where('statut', 'en_attente')->count();
    @endphp

    <div class="summary-box">
        <h3>Résumé Financier</h3>
        <div class="summary-item">
            <span>Montant souscrit:</span>
            <span>{{ number_format($adhesion->montant_souscrit, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="summary-item">
            <span>Total des paiements validés:</span>
            <span>{{ number_format($totalPaiements, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="summary-item">
            <span>Retrait anticipé disponible:</span>
            <span style="color: #007bff;">{{ number_format($retraitAnticipe, 0, ',', ' ') }} FCFA</span>
        </div>
        @if($paiementsEnAttente > 0)
        <div class="summary-item">
            <span>Paiements en attente:</span>
            <span style="color: #ffc107;">{{ $paiementsEnAttente }}</span>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Historique des Paiements ({{ $adhesion->paiements->count() }})</div>
        
        @if($adhesion->paiements->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 15%;">Date</th>
                        <th style="width: 15%;">Montant</th>
                        <th style="width: 15%;">Catégorie</th>
                        <th style="width: 15%;">Mode</th>
                        <th style="width: 15%;">Statut</th>
                        <th style="width: 15%;">Validé par</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($adhesion->paiements->sortByDesc('date_soumission') as $paiement)
                    <tr>
                        <td>#{{ $paiement->id }}</td>
                        <td>{{ $paiement->date_soumission ? $paiement->date_soumission->format('d/m/Y') : 'N/A' }}</td>
                        <td style="font-weight: bold;">{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                        <td>{{ ucfirst($paiement->categorie) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) }}</td>
                        <td>
                            @if($paiement->statut === 'validé')
                                <span class="badge badge-success">Validé</span>
                            @elseif($paiement->statut === 'rejeté')
                                <span class="badge badge-danger">Rejeté</span>
                            @else
                                <span class="badge badge-warning">En attente</span>
                            @endif
                        </td>
                        <td>{{ $paiement->validatedByAgent->name ?? '-' }}</td>
                    </tr>
                    @if($paiement->details->count() > 0)
                    <tr>
                        <td colspan="7" style="background-color: #f8f9fa; padding: 5px 10px;">
                            <small>
                                <strong>Détails:</strong>
                                @foreach($paiement->details as $detail)
                                    {{ ucfirst($detail->type_frais) }}: {{ number_format($detail->montant, 0, ',', ' ') }} FCFA
                                    @if(!$loop->last) | @endif
                                @endforeach
                            </small>
                        </td>
                    </tr>
                    @endif
                    @if($paiement->motif_rejet)
                    <tr>
                        <td colspan="7" style="background-color: #ffe6e6; padding: 5px 10px;">
                            <small style="color: #dc3545;"><strong>Motif de rejet:</strong> {{ $paiement->motif_rejet }}</small>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; padding: 20px; color: #999;">Aucun paiement enregistré pour cette adhésion.</p>
        @endif
    </div>

    <div class="footer">
        <p>Document généré automatiquement le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
        <p>© {{ date('Y') }} - Système de gestion des adhésions et paiements</p>
        <p style="margin-top: 10px; font-style: italic;">
            Note: Le retrait anticipé correspond à la somme des paiements validés, hors intérêts et frais de dossier/entretien.
        </p>
    </div>
</body>
</html>
