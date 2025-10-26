<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des Adhérents - SIF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #667eea;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #667eea;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-danger { background-color: #dc3545; color: white; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 SIF Burkina Faso</h1>
        <p><strong>Liste des Adhérents</strong></p>
        <p>Généré le {{ $date }}</p>
    </div>

    <div class="summary">
        <div class="summary-item"><strong>Total:</strong> {{ $adherents->count() }} adhérent(s)</div>
        <div class="summary-item"><strong>Actifs:</strong> {{ $adherents->where('statut_compte', 'actif')->count() }}</div>
        <div class="summary-item"><strong>En attente:</strong> {{ $adherents->where('statut_compte', 'en_attente')->count() }}</div>
        <div class="summary-item"><strong>Inactifs:</strong> {{ $adherents->where('statut_compte', 'inactif')->count() }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">ID Membre</th>
                <th style="width: 20%;">Nom Complet</th>
                <th style="width: 12%;">Téléphone</th>
                <th style="width: 15%;">Email</th>
                <th style="width: 13%;">Profession</th>
                <th style="width: 10%;">Statut</th>
                <th style="width: 6%;">AD</th>
                <th style="width: 6%;">Docs</th>
                <th style="width: 8%;">Adhésions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($adherents as $adherent)
            <tr>
                <td>{{ $adherent->membre_id ?? 'N/A' }}</td>
                <td><strong>{{ $adherent->nom_complet }}</strong></td>
                <td>{{ $adherent->telephone }}</td>
                <td>{{ Str::limit($adherent->email ?? 'N/A', 20) }}</td>
                <td>{{ Str::limit($adherent->profession ?? 'N/A', 15) }}</td>
                <td>
                    @php
                        $status = $adherent->statut_compte ?? 'en_attente';
                        $badgeClass = $status === 'actif' ? 'badge-success' : ($status === 'en_attente' ? 'badge-warning' : 'badge-danger');
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                </td>
                <td style="text-align: center;">{{ $adherent->ayantsDroit->count() }}</td>
                <td style="text-align: center;">{{ $adherent->documents->count() }}</td>
                <td style="text-align: center;">{{ $adherent->adhesions->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} SIF Burkina Faso - Système d'Information Financière</p>
        <p>Document confidentiel - Usage interne uniquement</p>
    </div>
</body>
</html>
