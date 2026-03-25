@extends('layouts.adherent-modern')

@section('title', 'Inscription - Étape 2')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">Inscription Adhérent - Étape 2/3</h4>
                    <p class="text-muted mb-0">Gestion des ayants droit</p>
                    
                    <!-- Barre de progression -->
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 66%;" 
                             aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-success"><i class="fas fa-check"></i> Profil</small>
                        <small class="text-primary">Ayants Droit</small>
                        <small class="text-muted">Documents</small>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('adherent.inscription.ayants-droit') }}" method="POST">
                        @csrf

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Information :</strong> Vous pouvez ajouter jusqu'à 2 ayants droit (bénéficiaires vie et/ou décès).
                            Ces informations devront être validées par un agent.
                        </div>

                        <div id="ayants-droit-container">
                            @php
                                $oldAyants = old('ayants_droit');
                                if (is_null($oldAyants) && isset($adherent) && $adherent->ayantsDroit->count() > 0) {
                                    $oldAyants = $adherent->ayantsDroit->map(function($a) {
                                        return [
                                            'nom' => $a->nom,
                                            'prenom' => $a->prenom,
                                            'date_naissance' => $a->date_naissance ? $a->date_naissance->format('Y-m-d') : null,
                                            'lien_parente' => $a->lien_parente,
                                            'contact' => $a->contact,
                                            'type_beneficiaire' => $a->type_beneficiaire,
                                        ];
                                    })->toArray();
                                }
                                $oldAyants = $oldAyants ?? [];
                                $ayantCount = count($oldAyants) > 0 ? count($oldAyants) : 1;
                            @endphp

                            @for($i = 0; $i < $ayantCount; $i++)
                            <div class="card mb-4 ayant-droit-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Ayant Droit #{{ $i + 1 }}</h6>
                                    @if($i > 0)
                                    <button type="button" class="btn btn-sm btn-danger remove-ayant" data-index="{{ $i }}">
                                        <i class="fas fa-times"></i> Supprimer
                                    </button>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom *</label>
                                                <input type="text" class="form-control @error('ayants_droit.'.$i.'.nom') is-invalid @enderror" 
                                                       name="ayants_droit[{{ $i }}][nom]" 
                                                       value="{{ $oldAyants[$i]['nom'] ?? '' }}" required>
                                                @error('ayants_droit.'.$i.'.nom')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Prénom *</label>
                                                <input type="text" class="form-control @error('ayants_droit.'.$i.'.prenom') is-invalid @enderror" 
                                                       name="ayants_droit[{{ $i }}][prenom]" 
                                                       value="{{ $oldAyants[$i]['prenom'] ?? '' }}" required>
                                                @error('ayants_droit.'.$i.'.prenom')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date de Naissance</label>
                                                <input type="date" class="form-control @error('ayants_droit.'.$i.'.date_naissance') is-invalid @enderror" 
                                                       name="ayants_droit[{{ $i }}][date_naissance]" 
                                                       value="{{ $oldAyants[$i]['date_naissance'] ?? '' }}">
                                                @error('ayants_droit.'.$i.'.date_naissance')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Lien de Parenté *</label>
                                                <input type="text" class="form-control @error('ayants_droit.'.$i.'.lien_parente') is-invalid @enderror" 
                                                       name="ayants_droit[{{ $i }}][lien_parente]" 
                                                       value="{{ $oldAyants[$i]['lien_parente'] ?? '' }}" 
                                                       placeholder="Ex: Conjoint, Enfant, Parent..." required>
                                                @error('ayants_droit.'.$i.'.lien_parente')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact</label>
                                                <input type="text" class="form-control @error('ayants_droit.'.$i.'.contact') is-invalid @enderror" 
                                                       name="ayants_droit[{{ $i }}][contact]" 
                                                       value="{{ $oldAyants[$i]['contact'] ?? '' }}">
                                                @error('ayants_droit.'.$i.'.contact')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Type de Bénéficiaire *</label>
                                                <select class="form-select @error('ayants_droit.'.$i.'.type_beneficiaire') is-invalid @enderror" 
                                                        name="ayants_droit[{{ $i }}][type_beneficiaire]" required>
                                                    <option value="">Choisir...</option>
                                                    <option value="vie" {{ ($oldAyants[$i]['type_beneficiaire'] ?? '') == 'vie' ? 'selected' : '' }}>Bénéficiaire Vie</option>
                                                    <option value="deces" {{ ($oldAyants[$i]['type_beneficiaire'] ?? '') == 'deces' ? 'selected' : '' }}>Bénéficiaire Décès</option>
                                                </select>
                                                @error('ayants_droit.'.$i.'.type_beneficiaire')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>

                        <div class="mb-4">
                            <button type="button" id="add-ayant" class="btn btn-outline-primary btn-sm" 
                                    {{ $ayantCount >= 2 ? 'disabled' : '' }}>
                                <i class="fas fa-plus"></i> Ajouter un autre ayant droit dans votre espace une fois l'inscription terminée
                            </button>
                            <small class="text-muted ms-2">Maximum 2 ayants droit</small>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="{{ route('adherent.inscription', ['step' => 1]) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Retour
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    Continuer vers les documents <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let ayantCount = {{ $ayantCount }};
    const maxAyants = 2;
    const container = document.getElementById('ayants-droit-container');
    const addButton = document.getElementById('add-ayant');

    // Ajouter un ayant droit
    addButton.addEventListener('click', function() {
        if (ayantCount >= maxAyants) return;

        const newIndex = ayantCount;
        const newCard = document.createElement('div');
        newCard.className = 'card mb-4 ayant-droit-card';
        newCard.innerHTML = `
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Ayant Droit #${newIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger remove-ayant" data-index="${newIndex}">
                    <i class="fas fa-times"></i> Supprimer
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nom *</label>
                            <input type="text" class="form-control" name="ayants_droit[${newIndex}][nom]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Prénom *</label>
                            <input type="text" class="form-control" name="ayants_droit[${newIndex}][prenom]" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Date de Naissance</label>
                            <input type="date" class="form-control" name="ayants_droit[${newIndex}][date_naissance]">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lien de Parenté *</label>
                            <input type="text" class="form-control" name="ayants_droit[${newIndex}][lien_parente]" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Contact</label>
                            <input type="text" class="form-control" name="ayants_droit[${newIndex}][contact]">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Type de Bénéficiaire *</label>
                            <select class="form-select" name="ayants_droit[${newIndex}][type_beneficiaire]" required>
                                <option value="">Choisir...</option>
                                <option value="vie">Bénéficiaire Vie</option>
                                <option value="deces">Bénéficiaire Décès</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        `;

        container.appendChild(newCard);
        ayantCount++;

        if (ayantCount >= maxAyants) {
            addButton.disabled = true;
        }

        // Ajouter l'événement de suppression
        newCard.querySelector('.remove-ayant').addEventListener('click', function() {
            newCard.remove();
            ayantCount--;
            addButton.disabled = false;
            renumberAyants();
        });
    });

    // Supprimer un ayant droit
    document.querySelectorAll('.remove-ayant').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.ayant-droit-card');
            card.remove();
            ayantCount--;
            addButton.disabled = false;
            renumberAyants();
        });
    });

    // Renuméroter les ayants droit
    function renumberAyants() {
        document.querySelectorAll('.ayant-droit-card').forEach((card, index) => {
            card.querySelector('.card-header h6').textContent = `Ayant Droit #${index + 1}`;
        });
    }
});
</script>
@endsection
