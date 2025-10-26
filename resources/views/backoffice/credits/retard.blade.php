@extends('backoffice.layouts.app')

@section('title', 'Crédits en retard de paiement')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-exclamation-triangle text-warning"></i> Crédits en retard de paiement
        </h1>
        <div>
            <a href="{{ route('admin.credits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            @if($credits->isEmpty())
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i> Aucun crédit en retard de paiement pour le moment.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Référence</th>
                                <th>Adhérent</th>
                                <th>Téléphone</th>
                                <th>Montant</th>
                                <th>Échéances en retard</th>
                                <th>Dernier incident</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($credits as $credit)
                                @php
                                    $echeancesEnRetard = $credit->echeances->filter(function($echeance) {
                                        return $echeance->date_echeance < now() && $echeance->statut != 'paye';
                                    });
                                    $derniereEcheance = $echeancesEnRetard->sortByDesc('date_echeance')->first();
                                    $joursRetard = $derniereEcheance ? now()->diffInDays($derniereEcheance->date_echeance) : 0;
                                @endphp
                                <tr>
                                    <td>#{{ str_pad($credit->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <a href="{{ route('admin.adherents.show', $credit->adherent) }}">
                                            {{ $credit->adherent->nom_complet }}
                                        </a>
                                    </td>
                                    <td>{{ $credit->adherent->telephone }}</td>
                                    <td>{{ number_format($credit->montant_accorde, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $echeancesEnRetard->count() }} échéance(s)
                                        </span>
                                    </td>
                                    <td>
                                        @if($derniereEcheance)
                                            {{ $derniereEcheance->date_echeance->format('d/m/Y') }}
                                            <span class="text-danger">
                                                ({{ $joursRetard }} jour{{ $joursRetard > 1 ? 's' : '' }} de retard)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.credits.show', $credit) }}" 
                                               class="btn btn-sm btn-primary"
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.credits.show', $credit) }}" 
                                               class="btn btn-sm btn-success"
                                               title="Enregistrer un paiement">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </a>
                                            <a href="#" 
                                               class="btn btn-sm btn-warning"
                                               title="Envoyer un rappel"
                                               onclick="return confirm('Envoyer un rappel à cet adhérent ?')">
                                                <i class="fas fa-bell"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $credits->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour les détails de l'échéance -->
<div class="modal fade" id="echeanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de l'échéance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" id="echeanceDetails">
                <!-- Contenu chargé dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fonction pour afficher les détails d'une échéance dans une modal
    function showEcheanceDetails(echeanceId) {
        fetch(`/admin/credits/echeances/${echeanceId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('echeanceDetails').innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById('echeanceModal'));
                modal.show();
            });
    }
    
    // Initialisation des tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
