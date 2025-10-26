@extends('backoffice.layouts.app')

@section('title', 'Détails du compte épargne #' . $epargne->numero_compte)

@push('styles')
<style>
    .transaction-depot { border-left: 4px solid #1cc88a; }
    .transaction-retrait { border-left: 4px solid #e74a3b; }
    .transaction-interet { border-left: 4px solid #36b9cc; }
    .transaction-frais { border-left: 4px solid #f6c23e; }
    .transaction-virement { border-left: 4px solid #4e73df; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-piggy-bank text-primary"></i> Compte épargne #{{ $epargne->numero_compte }}
        </h1>
        <div>
            <a href="{{ route('admin.epargnes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
            <a href="{{ route('admin.epargnes.edit', $epargne) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Modifier
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations du compte -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du compte</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Adhérent</h6>
                        <p class="mb-0">
                            <a href="{{ route('admin.adherents.show', $epargne->adherent) }}">
                                {{ $epargne->adherent->nom_complet }}
                            </a>
                        </p>
                        <small class="text-muted">{{ $epargne->adherent->membre_id }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Type d'épargne</h6>
                        <p class="mb-0">{{ $epargne->type_epargne_formatted }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Date d'ouverture</h6>
                        <p class="mb-0">{{ $epargne->date_ouverture->format('d/m/Y') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Dernière opération</h6>
                        <p class="mb-0">
                            {{ $epargne->date_derniere_operation ? $epargne->date_derniere_operation->format('d/m/Y H:i') : 'Aucune opération' }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Statut</h6>
                        <p class="mb-0">
                            <span class="badge bg-{{ $epargne->statut === 'actif' ? 'success' : ($epargne->statut === 'inactif' ? 'secondary' : 'warning') }}">
                                {{ $epargne->statut_formatted }}
                            </span>
                        </p>
                    </div>
                    
                    @if($epargne->notes)
                        <div class="mb-3">
                            <h6 class="text-muted">Notes</h6>
                            <p class="mb-0">{{ $epargne->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Solde et opérations rapides -->
        <div class="col-lg-8 mb-4">
            <div class="row">
                <!-- Carte de solde -->
                <div class="col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Solde actuel</div>
                                    <div class="h5 mb-0 font-weight-bold text-dark">
                                        {{ number_format($epargne->solde_actuel, 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-wallet fa-2x text-secondary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Intérêts cumulés -->
                <div class="col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Intérêts cumulés</div>
                                    <div class="h5 mb-0 font-weight-bold text-dark">
                                        {{ number_format($epargne->interet_cumule, 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-percent fa-2x text-secondary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action rapide -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                        </div>
                        <div class="card-body text-center">
                            <button type="button" 
                                    class="btn btn-success me-2 mb-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#depotModal"
                                    data-epargne-id="{{ $epargne->id }}">
                                <i class="fas fa-plus-circle me-1"></i> Dépôt
                            </button>
                            
                            <button type="button" 
                                    class="btn btn-danger me-2 mb-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#retraitModal"
                                    data-epargne-id="{{ $epargne->id }}"
                                    {{ $epargne->solde_actuel <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-minus-circle me-1"></i> Retrait
                            </button>
                            
                            <form action="{{ route('admin.epargnes.calculer-interets', $epargne) }}" 
                                  method="POST" 
                                  class="d-inline-block mb-2"
                                  onsubmit="return confirm('Voulez-vous vraiment calculer les intérêts pour ce compte ?')">
                                @csrf
                                <button type="submit" class="btn btn-info me-2">
                                    <i class="fas fa-calculator me-1"></i> Calculer les intérêts
                                </button>
                            </form>
                            
                            @if($epargne->statut === 'actif')
                                <form action="{{ route('admin.epargnes.destroy', $epargne) }}" 
                                      method="POST" 
                                      class="d-inline-block"
                                      onsubmit="return confirm('Voulez-vous vraiment désactiver ce compte ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-lock me-1"></i> Désactiver
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.epargnes.update', $epargne) }}" 
                                      method="POST" 
                                      class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut" value="actif">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-unlock me-1"></i> Activer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dernières transactions -->
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Dernières transactions</h6>
                    <a href="#" class="btn btn-sm btn-primary">
                        Voir tout <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if($epargne->transactions->isEmpty())
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> Aucune transaction enregistrée.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($epargne->transactions->take(5) as $transaction)
                                <div class="list-group-item list-group-item-action transaction-{{ $transaction->type_operation }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <span class="badge bg-{{ $transaction->type_operation_class }}">
                                                {{ $transaction->type_operation_formatted }}
                                            </span>
                                            <small class="text-muted ms-2">
                                                {{ $transaction->moyen_paiement_formatted }}
                                            </small>
                                        </h6>
                                        <small>{{ $transaction->date_operation->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1">
                                            {{ $transaction->notes }}
                                            @if($transaction->reference)
                                                <br><small class="text-muted">Réf: {{ $transaction->reference }}</small>
                                            @endif
                                        </p>
                                        <div class="text-{{ $transaction->type_operation === 'depot' || $transaction->type_operation === 'interet' ? 'success' : 'danger' }} fw-bold">
                                            {{ $transaction->montant_formatted }}
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        Solde: {{ $transaction->solde_apres_operation_formatted }} | 
                                        Par: {{ $transaction->auteur->name ?? 'Système' }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de dépôt -->
@include('backoffice.epargnes._depot')

<!-- Modal de retrait -->
@include('backoffice.epargnes._retrait')

@endsection

@push('scripts')
<script>
    // Initialisation des tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Gestion des modales de dépôt/retrait
        var depotModal = document.getElementById('depotModal');
        var retraitModal = document.getElementById('retraitModal');
        
        if (depotModal) {
            depotModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var epargneId = button.getAttribute('data-epargne-id') || {{ $epargne->id }};
                var modal = this;
                modal.querySelector('form').action = '/admin/epargnes/' + epargneId + '/depot';
                modal.querySelector('input[name="date_operation"]').valueAsDate = new Date();
            });
        }
        
        if (retraitModal) {
            retraitModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var epargneId = button.getAttribute('data-epargne-id') || {{ $epargne->id }};
                var modal = this;
                modal.querySelector('form').action = '/admin/epargnes/' + epargneId + '/retrait';
                modal.querySelector('input[name="date_operation"]').valueAsDate = new Date();
            });
        }
        
        // Définir la date d'opération par défaut
        document.querySelectorAll('input[name="date_operation"]').forEach(function(input) {
            if (!input.value) {
                input.valueAsDate = new Date();
            }
        });
    });
</script>
@endpush
