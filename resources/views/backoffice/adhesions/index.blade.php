@extends('backoffice.layouts.app')

@section('title', 'Gestion des Adhésions')
@section('page-title', 'Gestion des Adhésions')

@section('content')
@include('components.backoffice.alerts')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Gestion des Adhésions</h1>
        <p class="text-muted mb-0">Liste de toutes les adhésions aux plans d'épargne</p>
    </div>
    <div>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.adhesions.index') }}" 
               class="btn {{ !request('statut') ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="fas fa-list me-1"></i>Toutes
            </a>
            <a href="{{ route('admin.adhesions.index', ['statut' => 'actif']) }}" 
               class="btn {{ request('statut') === 'actif' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="fas fa-check-circle me-1"></i>Actives
                @if(isset($stats['actives']) && $stats['actives'] > 0)
                    <span class="badge bg-light text-dark ms-1">{{ $stats['actives'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.adhesions.index', ['statut' => 'en_attente_activation']) }}" 
               class="btn {{ request('statut') === 'en_attente_activation' ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="fas fa-clock me-1"></i>En attente
                @if(isset($stats['en_attente']) && $stats['en_attente'] > 0)
                    <span class="badge bg-light text-dark ms-1">{{ $stats['en_attente'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.adhesions.index', ['statut' => 'suspendue']) }}" 
               class="btn {{ request('statut') === 'suspendue' ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="fas fa-pause-circle me-1"></i>Suspendues
            </a>
            <a href="{{ route('admin.adhesions.index', ['statut' => 'terminee']) }}" 
               class="btn {{ request('statut') === 'terminee' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                <i class="fas fa-stop-circle me-1"></i>Terminées
            </a>
        </div>
    </div>
</div>

<!-- Statistiques rapides -->
@if(isset($stats))
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total</h6>
                        <h3 class="mb-0">{{ $adhesions->total() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-handshake fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Actives</h6>
                        <h3 class="mb-0">{{ $stats['actives'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">En attente</h6>
                        <h3 class="mb-0">{{ $stats['en_attente'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Closes</h6>
                        <h3 class="mb-0">{{ $stats['closes'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-stop-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Liste des adhésions -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-handshake me-2 text-primary"></i>
            Liste des Adhésions
            @if(request('statut'))
                - {{ ucfirst(str_replace('_', ' ', request('statut'))) }}
            @endif
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Adhérent</th>
                        <th>Plan</th>
                        <th>Montant souscrit</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Statut</th>
                        <th>Renouvelable</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adhesions as $adhesion)
                    <tr>
                        <td><strong>#{{ $adhesion->id }}</strong></td>
                        <td>
                            <div>
                                <strong>{{ $adhesion->adherent->nom ?? 'N/A' }} {{ $adhesion->adherent->prenom ?? '' }}</strong>
                                @if($adhesion->adherent->telephone)
                                    <br><small class="text-muted">{{ $adhesion->adherent->telephone }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $adhesion->plan->nom ?? 'Plan supprimé' }}</strong>
                                @if($adhesion->plan)
                                    <br><small class="text-muted">{{ $adhesion->plan->taux_interet }}% - {{ ucfirst($adhesion->plan->periodicite) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <strong class="text-primary">{{ number_format($adhesion->montant_souscrit, 0, ',', ' ') }} FCFA</strong>
                        </td>
                        <td>
                            {{ $adhesion->date_debut ? \Carbon\Carbon::parse($adhesion->date_debut)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td>
                            {{ $adhesion->date_fin ? \Carbon\Carbon::parse($adhesion->date_fin)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td>
                            @php
                                $statutClass = match($adhesion->statut) {
                                    'actif' => 'bg-success',
                                    'en_attente_activation' => 'bg-warning text-dark',
                                    'suspendue' => 'bg-warning text-dark', 
                                    'terminee' => 'bg-secondary',
                                    'annulee' => 'bg-danger',
                                    default => 'bg-info'
                                };
                            @endphp
                            <span class="badge {{ $statutClass }}">
                                {{ ucfirst(str_replace('_', ' ', $adhesion->statut)) }}
                            </span>
                        </td>
                        <td>
                            @if($adhesion->renouvelable)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Oui
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-times me-1"></i>Non
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.adhesions.show', $adhesion) }}" 
                                   class="btn btn-sm btn-outline-primary"
                                   title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @can('update', $adhesion)
                                    @if($adhesion->statut === 'en_attente_activation')
                                        <form method="POST" action="{{ route('admin.adhesions.activate', $adhesion) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-success"
                                                    title="Activer"
                                                    onclick="return confirm('Activer cette adhésion ?')">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @elseif($adhesion->statut === 'actif')
                                        <form method="POST" action="{{ route('admin.adhesions.suspend', $adhesion) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-warning"
                                                    title="Suspendre"
                                                    onclick="return confirm('Suspendre cette adhésion ?')">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        </form>
                                    @elseif($adhesion->statut === 'suspendue')
                                        <form method="POST" action="{{ route('admin.adhesions.resume', $adhesion) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-info"
                                                    title="Reprendre"
                                                    onclick="return confirm('Reprendre cette adhésion ?')">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-center">
                                <i class="fas fa-handshake text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">Aucune adhésion trouvée</h5>
                                <p class="text-muted">
                                    @if(request('statut'))
                                        Aucune adhésion avec le statut "{{ request('statut') }}" n'a été trouvée.
                                    @else
                                        Les adhésions aux plans d'épargne apparaîtront ici.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($adhesions->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Affichage de {{ $adhesions->firstItem() ?? 0 }} à {{ $adhesions->lastItem() ?? 0 }} 
                sur {{ $adhesions->total() }} adhésions
            </div>
            <div>
                {{ $adhesions->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh des statistiques toutes les 30 secondes
    setTimeout(function() {
        location.reload();
    }, 30000);
});
</script>
@endpush