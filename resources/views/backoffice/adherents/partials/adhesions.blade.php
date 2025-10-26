@if($adherent->adhesions->isEmpty())
    <div class="text-center py-4">
        <i class="fas fa-id-card fa-3x text-muted mb-3"></i>
        <p class="text-muted">Aucune adhésion enregistrée</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Date Début</th>
                    <th>Date Fin</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Date Paiement</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adherent->adhesions as $adhesion)
                <tr>
                    <td>
                        <strong>{{ $adhesion->plan->nom ?? 'N/A' }}</strong>
                        @if($adhesion->plan->description ?? false)
                            <br><small class="text-muted">{{ $adhesion->plan->description }}</small>
                        @endif
                    </td>
                    <td>{{ $adhesion->date_debut ? $adhesion->date_debut->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $adhesion->date_fin ? $adhesion->date_fin->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $adhesion->montant ? number_format($adhesion->montant, 0, ',', ' ') . ' FCFA' : 'N/A' }}</td>
                    <td>
                        @if($adhesion->statut == 'actif')
                            <span class="badge bg-success">Actif</span>
                        @elseif($adhesion->statut == 'en_attente')
                            <span class="badge bg-warning">En attente</span>
                        @else
                            <span class="badge bg-danger">Expiré</span>
                        @endif
                    </td>
                    <td>{{ $adhesion->date_paiement ? $adhesion->date_paiement->format('d/m/Y') : 'En attente' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif