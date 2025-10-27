<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Paiements - Crédit {{ $credit->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #667eea; padding-bottom: 15px; }
        .header h1 { color: #667eea; font-size: 20px; margin: 0; }
        .info { background: #f8f9fa; padding: 10px; margin: 15px 0; border-left: 4px solid #11998e; }
        .info div { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #11998e; color: white; padding: 10px 5px; text-align: left; font-size: 9px; }
        td { padding: 8px 5px; border-bottom: 1px solid #ddd; font-size: 9px; }
        tr:nth-child(even) { background: #f8f9fa; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 8px; font-weight: bold; }
        .bg-success { background: #28a745; color: white; }
        .bg-warning { background: #ffc107; color: #333; }
        .footer { margin-top: 30px; text-align: center; font-size: 8px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .totals { background: #d4edda; padding: 15px; margin-top: 20px; font-weight: bold; border-left: 4px solid #28a745; }
    </style>
</head>
<body>
    <div class="header">
        <h1>💰 HISTORIQUE DES PAIEMENTS</h1>
        <p>SIFCash-Burkina Faso - Généré le {{ $date }}</p>
    </div>

    <div class="info">
        <div><strong>Crédit N° :</strong> {{ $credit->id }}</div>
        <div><strong>Adhérent :</strong> {{ $credit->adherent->nom_complet ?? 'N/A' }}</div>
        <div><strong>Montant accordé :</strong> {{ number_format($credit->montant_accorde, 0, ',', ' ') }} FCFA</div>
        <div><strong>Nombre de paiements :</strong> {{ $credit->paiements->count() }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Échéance</th>
                <th>Montant</th>
                <th>Pénalité</th>
                <th>Total</th>
                <th>Mode</th>
                <th>Référence</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalPaiements = 0; 
                $totalPenalites = 0; 
            @endphp
            @forelse($credit->paiements as $paiement)
            @php
                $totalPaiements += $paiement->montant;
                $totalPenalites += $paiement->penalite ?? 0;
            @endphp
            <tr>
                <td>{{ $paiement->date_paiement }}</td>
                <td>{{ $paiement->echeance ? \Carbon\Carbon::parse($paiement->echeance->date_echeance)->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                <td>{{ number_format($paiement->penalite ?? 0, 0, ',', ' ') }}</td>
                <td><strong>{{ number_format($paiement->montant + ($paiement->penalite ?? 0), 0, ',', ' ') }}</strong></td>
                <td>{{ $paiement->mode ?? 'N/A' }}</td>
                <td>{{ $paiement->reference ?? '-' }}</td>
                <td>
                    <span class="badge bg-{{ $paiement->statut == 'valide' ? 'success' : 'warning' }}">
                        {{ ucfirst($paiement->statut ?? 'en_attente') }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 30px; color: #999;">
                    Aucun paiement enregistré pour ce crédit
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($credit->paiements->count() > 0)
    <div class="totals">
        <div>TOTAL DES PAIEMENTS : {{ number_format($totalPaiements, 0, ',', ' ') }} FCFA</div>
        <div>TOTAL DES PÉNALITÉS : {{ number_format($totalPenalites, 0, ',', ' ') }} FCFA</div>
        <div style="font-size: 12px; margin-top: 5px; color: #11998e;">TOTAL GÉNÉRAL : {{ number_format($totalPaiements + $totalPenalites, 0, ',', ' ') }} FCFA</div>
    </div>
    @endif

    <div class="footer">
        <p>© {{ date('Y') }} SIFCash-Burkina Faso - Document confidentiel</p>
        <p>Tous les montants sont exprimés en Francs CFA (FCFA)</p>
    </div>
</body>
</html>
