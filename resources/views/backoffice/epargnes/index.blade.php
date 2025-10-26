@extends('backoffice.layouts.app')

@section('title', 'Gestion des comptes épargne')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-piggy-bank text-primary"></i> Gestion des comptes épargne
        </h1>
        <div>
            <a href="{{ route('admin.epargnes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Nouveau compte
            </a>
            <a href="{{ route('admin.epargnes.export') }}" class="btn btn-success">
                <i class="fas fa-file-export me-1"></i> Exporter
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.epargnes.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="adherent_id" class="form-label">Adhérent</label>
                    <select name="adherent_id" id="adherent_id" class="form-select">
                        <option value="">Tous les adhérents</option>
                        @foreach($adherents as $adherent)
                            <option value="{{ $adherent->id }}" {{ request('adherent_id') == $adherent->id ? 'selected' : '' }}>
                                {{ $adherent->nom_complet }} ({{ $adherent->membre_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type_epargne" class="form-label">Type d'épargne</label>
                    <select name="type_epargne" id="type_epargne" class="form-select">
                        <option value="">Tous les types</option>
                        @foreach(\App\Models\Epargne::TYPES_EPARGNE as $key => $label)
                            <option value="{{ $key }}" {{ request('type_epargne') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select name="statut" id="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        @foreach(\App\Models\Epargne::STATUTS as $key => $label)
                            <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des épargnes -->
    <div class="card shadow">
        <div class="card-body">
            @if($epargnes->isEmpty())
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i> Aucun compte épargne trouvé.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>N° Compte</th>
                                <th>Adhérent</th>
                                <th>Type</th>
                                <th>Solde</th>
                                <th>Intérêts</th>
                                <th>Date ouverture</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($epargnes as $epargne)
                                <tr>
                                    <td>{{ $epargne->numero_compte }}</td>
                                    <td>
                                        <a href="{{ route('admin.adherents.show', $epargne->adherent) }}">
                                            {{ $epargne->adherent->nom_complet }}
                                        </a>
                                    </td>
                                    <td>{{ $epargne->type_epargne_formatted }}</td>
                                    <td class="text-end">{{ number_format($epargne->solde_actuel, 0, ',', ' ') }} FCFA</td>
                                    <td class="text-end">{{ number_format($epargne->interet_cumule, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $epargne->date_ouverture->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $epargne->statut === 'actif' ? 'success' : ($epargne->statut === 'inactif' ? 'secondary' : 'warning') }}">
                                            {{ $epargne->statut_formatted }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.epargnes.show', $epargne) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Voir le détail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.epargnes.edit', $epargne) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#depotModal"
                                                    data-epargne-id="{{ $epargne->id }}"
                                                    title="Effectuer un dépôt">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#retraitModal"
                                                    data-epargne-id="{{ $epargne->id }}"
                                                    title="Effectuer un retrait"
                                                    {{ $epargne->solde_actuel <= 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $epargnes->withQueryString()->links() }}
                </div>
            @endif
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
                var epargneId = button.getAttribute('data-epargne-id');
                var modal = this;
                modal.querySelector('form').action = '/admin/epargnes/' + epargneId + '/depot';
                modal.querySelector('input[name="date_operation"]').valueAsDate = new Date();
            });
        }
        
        if (retraitModal) {
            retraitModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var epargneId = button.getAttribute('data-epargne-id');
                var modal = this;
                modal.querySelector('form').action = '/admin/epargnes/' + epargneId + '/retrait';
                modal.querySelector('input[name="date_operation"]').valueAsDate = new Date();
            });
        }
    });
</script>
@endpush
