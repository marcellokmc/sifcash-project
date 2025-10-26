@extends('backoffice.layouts.app')

@section('title', 'Affectation Adhérent')
@section('page-title', 'Affecter Agence et Agent')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.adherents.index') }}">Adhérents</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.adherents.show', $adherent) }}">{{ $adherent->nom_complet }}</a></li>
        <li class="breadcrumb-item active">Affectation</li>
    </ol>
</nav>

<div class="row">
    <!-- Informations adhérent -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user"></i> Adhérent
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-user-circle fa-4x text-primary"></i>
                </div>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">ID Membre:</td>
                        <td><strong>{{ $adherent->membre_id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nom complet:</td>
                        <td><strong>{{ $adherent->nom_complet }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Téléphone:</td>
                        <td>{{ $adherent->telephone }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email:</td>
                        <td>{{ $adherent->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Statut:</td>
                        <td>
                            @if($adherent->statut_compte == 'actif')
                                <span class="badge bg-success">Actif</span>
                            @elseif($adherent->statut_compte == 'en_attente_de_verification')
                                <span class="badge bg-warning">En attente</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Affectation actuelle -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-info-circle"></i> Affectation Actuelle
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted d-block mb-1">Agence:</label>
                    @if($adherent->agence)
                        <div class="d-flex align-items-center">
                            <i class="fas fa-building text-primary me-2"></i>
                            <strong>{{ $adherent->agence->nom }}</strong>
                        </div>
                        <small class="text-muted">{{ $adherent->agence->ville }}</small>
                    @else
                        <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Non affecté</span>
                    @endif
                </div>

                <div>
                    <label class="text-muted d-block mb-1">Agent gestionnaire:</label>
                    @if($adherent->agentGestionnaire)
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-tie text-success me-2"></i>
                            <strong>{{ $adherent->agentGestionnaire->name }}</strong>
                        </div>
                        <small class="text-muted">{{ $adherent->agentGestionnaire->email }}</small>
                    @else
                        <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Non affecté</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire d'affectation -->
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit"></i> Nouvelle Affectation
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.adherents.affecter', $adherent) }}" method="POST">
                    @csrf

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> Vous pouvez affecter cet adhérent à une agence et/ou à un agent gestionnaire.
                        L'agent gestionnaire sera responsable du suivi de ce compte.
                    </div>

                    <!-- Sélection agence -->
                    <div class="mb-4">
                        <label for="agence_id" class="form-label">
                            <i class="fas fa-building text-primary"></i> Agence de rattachement
                        </label>
                        <select name="agence_id" id="agence_id" 
                                class="form-select @error('agence_id') is-invalid @enderror">
                            <option value="">-- Sélectionner une agence --</option>
                            @foreach($agences as $agence)
                                <option value="{{ $agence->id }}" 
                                        {{ old('agence_id', $adherent->agence_id) == $agence->id ? 'selected' : '' }}>
                                    {{ $agence->nom }} - {{ $agence->ville }}
                                </option>
                            @endforeach
                        </select>
                        @error('agence_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            L'agence de rattachement détermine où l'adhérent effectuera ses opérations.
                        </small>
                    </div>

                    <!-- Sélection agent gestionnaire -->
                    <div class="mb-4">
                        <label for="agent_gestionnaire_id" class="form-label">
                            <i class="fas fa-user-tie text-success"></i> Agent gestionnaire
                            <span id="agentCount" class="badge bg-secondary ms-2">{{ count($agents) }} disponible(s)</span>
                        </label>
                        <select name="agent_gestionnaire_id" id="agent_gestionnaire_id" 
                                class="form-select @error('agent_gestionnaire_id') is-invalid @enderror">
                            <option value="">-- Sélectionner un agent ({{ count($agents) }} disponible(s)) --</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" 
                                        data-role="{{ $agent->role }}"
                                        data-agence="{{ $agent->agence_id ?? '' }}"
                                        {{ old('agent_gestionnaire_id', $adherent->agent_gestionnaire_id) == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }} 
                                    @if($agent->agence)
                                        ({{ $agent->agence->nom }})
                                    @endif
                                    - {{ ucfirst(str_replace('_', ' ', $agent->role)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('agent_gestionnaire_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted" id="agentHelpText">
                            L'agent gestionnaire sera responsable du suivi et de la gestion de ce compte adhérent.
                        </small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> La modification de l'affectation prendra effet immédiatement.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.adherents.show', $adherent) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Enregistrer l'affectation
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Guide -->
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-question-circle"></i> Guide d'affectation
                </h6>
            </div>
            <div class="card-body">
                <h6 class="text-primary"><i class="fas fa-building"></i> Agence</h6>
                <ul>
                    <li>L'agence détermine le lieu principal de gestion du compte</li>
                    <li>Les opérations bancaires seront effectuées dans cette agence</li>
                    <li>Les rapports seront associés à cette agence</li>
                </ul>

                <h6 class="text-success mt-3"><i class="fas fa-user-tie"></i> Agent gestionnaire</h6>
                <ul>
                    <li>L'agent sera notifié des activités importantes de ce compte</li>
                    <li>Il aura la responsabilité du suivi et de la relation client</li>
                    <li>Il peut voir les statistiques des adhérents qui lui sont affectés</li>
                </ul>

                <div class="alert alert-info mt-3 mb-0">
                    <strong>Astuce :</strong> Il est recommandé d'affecter l'adhérent à un agent de la même agence pour faciliter la coordination.
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const agenceSelect = document.getElementById('agence_id');
    const agentSelect = document.getElementById('agent_gestionnaire_id');
    const agentCountBadge = document.getElementById('agentCount');
    const agentHelpText = document.getElementById('agentHelpText');
    
    console.log('Total agents dans le select:', agentSelect.options.length - 1); // -1 pour exclure l'option vide
    
    // Fonction pour mettre à jour le compteur d'agents visibles
    function updateAgentCount() {
        let visibleCount = 0;
        Array.from(agentSelect.options).forEach(option => {
            if (option.value !== '' && option.style.display !== 'none') {
                visibleCount++;
            }
        });
        
        agentCountBadge.textContent = visibleCount + ' disponible(s)';
        agentSelect.options[0].text = '-- Sélectionner un agent (' + visibleCount + ' disponible(s)) --';
        
        if (visibleCount === 0) {
            agentHelpText.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Aucun agent disponible pour cette agence.';
            agentHelpText.classList.add('text-warning');
        } else {
            agentHelpText.innerHTML = "L'agent gestionnaire sera responsable du suivi et de la gestion de ce compte adhérent.";
            agentHelpText.classList.remove('text-warning');
        }
    }
    
    // Filtrer les agents par agence sélectionnée
    agenceSelect.addEventListener('change', function() {
        const selectedAgence = this.value;
        
        console.log('Agence sélectionnée:', selectedAgence);
        
        Array.from(agentSelect.options).forEach(option => {
            if (option.value === '') {
                option.style.display = '';
                return;
            }
            
            const agentAgence = option.dataset.agence;
            console.log('Agent:', option.text, 'Agence ID:', agentAgence);
            
            // Si aucune agence n'est sélectionnée, afficher tous les agents
            if (!selectedAgence) {
                option.style.display = '';
            }
            // Si l'agent appartient à l'agence sélectionnée, l'afficher
            else if (agentAgence === selectedAgence) {
                option.style.display = '';
            }
            // Sinon, masquer l'option
            else {
                option.style.display = 'none';
            }
        });
        
        // Mettre à jour le compteur
        updateAgentCount();
        
        // Réinitialiser la sélection de l'agent si elle n'est plus visible
        if (agentSelect.selectedOptions[0] && agentSelect.selectedOptions[0].style.display === 'none') {
            agentSelect.value = '';
        }
    });
    
    // Initialiser le compteur au chargement
    updateAgentCount();
});
</script>
@endpush
@endsection
