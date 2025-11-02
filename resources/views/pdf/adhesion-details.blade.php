<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Adhésion #{{ $adhesion->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
            border-left: 4px solid #007bff;
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
            width: 40%;
            padding: 8px;
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .info-value {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #e9ecef;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
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
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Détails de l'Adhésion</h1>
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
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $adhesion->adherent->user->email ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Détails de l'Adhésion</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Plan d'épargne</div>
                <div class="info-value">{{ $adhesion->plan->nom ?? 'Plan supprimé' }}</div>
            </div>
            @if($adhesion->plan)
            <div class="info-row">
                <div class="info-label">Taux d'intérêt</div>
                <div class="info-value">{{ $adhesion->plan->taux_interet }}%</div>
            </div>
            <div class="info-row">
                <div class="info-label">Périodicité</div>
                <div class="info-value">{{ ucfirst($adhesion->plan->periodicite) }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Montant souscrit</div>
                <div class="info-value" style="font-weight: bold; color: #007bff;">{{ number_format($adhesion->montant_souscrit, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de début</div>
                <div class="info-value">{{ $adhesion->date_debut ? \Carbon\Carbon::parse($adhesion->date_debut)->format('d/m/Y') : 'Non définie' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de fin</div>
                <div class="info-value">{{ $adhesion->date_fin ? \Carbon\Carbon::parse($adhesion->date_fin)->format('d/m/Y') : 'Non définie' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Statut</div>
                <div class="info-value">
                    @php
                        $statutClass = match($adhesion->statut) {
                            'actif' => 'badge-success',
                            'en_attente_activation' => 'badge-warning',
                            'suspendue' => 'badge-warning',
                            'terminee' => 'badge-secondary',
                            'annulee' => 'badge-danger',
                            default => 'badge-info'
                        };
                    @endphp
                    <span class="badge {{ $statutClass }}">
                        {{ ucfirst(str_replace('_', ' ', $adhesion->statut)) }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Renouvelable</div>
                <div class="info-value">{{ $adhesion->renouvelable ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de création</div>
                <div class="info-value">{{ $adhesion->created_at->format('d/m/Y à H:i') }}</div>
            </div>
            @if($adhesion->date_activation)
            <div class="info-row">
                <div class="info-label">Date d'activation</div>
                <div class="info-value">{{ $adhesion->date_activation->format('d/m/Y à H:i') }}</div>
            </div>
            @endif
        </div>
    </div>

    @if($adhesion->notes)
    <div class="section">
        <div class="section-title">Notes</div>
        <p style="padding: 10px; background-color: #f8f9fa;">{{ $adhesion->notes }}</p>
    </div>
    @endif

    @if($adhesion->createdByAgent)
    <div class="section">
        <div class="section-title">Informations de Création</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Créé par</div>
                <div class="info-value">{{ $adhesion->createdByAgent->name ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Document généré automatiquement le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
        <p>© {{ date('Y') }} - Système de gestion des adhésions</p>
    </div>
</body>
</html>
