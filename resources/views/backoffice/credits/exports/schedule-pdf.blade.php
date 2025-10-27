<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Échéancier - Crédit {{ $credit->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #667eea; padding-bottom: 15px; }
        .header h1 { color: #667eea; font-size: 20px; margin: 0; }
        .info { background: #f8f9fa; padding: 10px; margin: 15px 0; border-left: 4px solid #667eea; }
        .info div { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #667eea; color: white; padding: 10px 5px; text-align: left; font-size: 9px; }
        td { padding: 8px 5px; border-bottom: 1px solid #ddd; font-size: 9px; }
        tr:nth-child(even) { background: #f8f9fa; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 8px; font-weight: bold; color: white; }
        .bg-success { background: #28a745; }
        .bg-warning { background: #ffc107; color: #333; }
        .bg-danger { background: #dc3545; }
        .footer { margin-top: 30px; text-align: center; font-size: 8px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .totals { background: #fff3cd; padding: 15px; margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 ÉCHÉANCIER DE CRÉDIT</h1>
        <p>SIFCash-Burkina Faso - Généré le {{ $date }}</p>
    </div>

    <div class="info">
        <div><strong>Crédit N° :</strong> {{ $credit->id }}</div>
        <div><strong>Adhérent :</strong> {{ $credit->adherent->nom_complet ?? 'N/A' }}</div>
        <div><strong>Montant accordé :</strong> {{ number_format($credit->montant_accorde, 0, ',', ' ') }} FCFA</div>
        <div><strong>Taux :</strong> {{ number_format($credit->taux, 2) }}% | <strong>Durée :</strong> {{ $credit->duree }} mois</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Date Échéance</th>
                <th>Montant Attendu</th>
                <th>Montant Payé</th>
                <th>Pénalité</th>
                <th>Reste à Payer</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAttendu = 0; $totalPaye = 0; $totalPenalite = 0; @endphp
            @foreach($credit->echeances as $index => $echeance)
            @php
                $totalAttendu += $echeance->montant_attendu;
                $totalPaye += $echeance->montant_paye;
                $totalPenalite += $echeance->penalite_appliquee ?? 0;
                $reste = $echeance->montant_attendu - $echeance->montant_paye;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($echeance->date_echeance)->format('d/m/Y') }}</td>
                <td>{{ number_format($echeance->montant_attendu, 0, ',', ' ') }}</td>
                <td>{{ number_format($echeance->montant_paye, 0, ',', ' ') }}</td>
                <td>{{ number_format($echeance->penalite_appliquee ?? 0, 0, ',', ' ') }}</td>
                <td>{{ number_format($reste, 0, ',', ' ') }}</td>
                <td>
                    <span class="badge bg-{{ $echeance->statut == 'payé' ? 'success' : ($echeance->statut == 'en_retard' ? 'danger' : 'warning') }}">
                        {{ ucfirst($echeance->statut) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div>TOTAL ATTENDU : {{ number_format($totalAttendu, 0, ',', ' ') }} FCFA</div>
        <div>TOTAL PAYÉ : {{ number_format($totalPaye, 0, ',', ' ') }} FCFA</div>
        <div>TOTAL PÉNALITÉS : {{ number_format($totalPenalite, 0, ',', ' ') }} FCFA</div>
        <div>RESTE À PAYER : {{ number_format($totalAttendu - $totalPaye, 0, ',', ' ') }} FCFA</div>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} SIFCash-Burkina Faso - Document confidentiel</p>
    </div>
</body>
</html>
