@extends('layouts.adherent-modern')

@section('title', 'Demander un Retrait')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('adherent.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('adherent.retraits.index') }}">Mes Retraits</a></li>
                        <li class="breadcrumb-item active">Nouvelle Demande</li>
                    </ol>
                </div>
                <h4 class="page-title">Demander un Retrait</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('adherent.retraits.store') }}" method="POST" id="retrait-form">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="adhesion_id" class="form-label">Adhésion <span class="text-danger">*</span></label>
                                    <select class="form-select @error('adhesion_id') is-invalid @enderror" id="adhesion_id" name="adhesion_id" required>
                                        <option value="">Sélectionner une adhésion</option>
                                        @foreach($adhesions as $adhesion)
                                        <option value="{{ $adhesion->id }}"
                                                data-plan="{{ $adhesion->plan->nom }}"
                                                data-solde="{{ $adhesion->solde_disponible }}"
                                                data-montant="{{ $adhesion->montant_souscrit }}"
                                                {{ old('adhesion_id') == $adhesion->id ? 'selected' : '' }}>
                                            {{ $adhesion->numero_adhesion }} - {{ $adhesion->plan->nom }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('adhesion_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type_retrait" class="form-label">Type de Retrait <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type_retrait') is-invalid @enderror" id="type_retrait" name="type_retrait" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="a_terme" {{ old('type_retrait') == 'a_terme' ? 'selected' : '' }}>Retrait à terme</option>
                                        <option value="anticipe" {{ old('type_retrait') == 'anticipe' ? 'selected' : '' }}>Retrait anticipé</option>
                                    </select>
                                    @error('type_retrait')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="montant_demande" class="form-label">Montant Demandé <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('montant_demande') is-invalid @enderror"
                                               id="montant_demande" name="montant_demande" step="0.01" min="0.01"
                                               value="{{ old('montant_demande') }}" required>
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                    <div class="form-text">Solde disponible: <span id="solde-disponible" class="fw-semibold">0 FCFA</span></div>
                                    @error('montant_demande')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mode_retrait" class="form-label">Mode de Retrait <span class="text-danger">*</span></label>
                                    <select class="form-select @error('mode_retrait') is-invalid @enderror" id="mode_retrait" name="mode_retrait" required>
                                        <option value="">Sélectionner un mode</option>
                                        <option value="mobile_money" {{ old('mode_retrait') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                        <option value="virement" {{ old('mode_retrait') == 'virement' ? 'selected' : '' }}>Virement Bancaire</option>
                                        <option value="cheque" {{ old('mode_retrait') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                                        <option value="especes" {{ old('mode_retrait') == 'especes' ? 'selected' : '' }}>Espèces</option>
                                    </select>
                                    @error('mode_retrait')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Champs conditionnels selon le mode de retrait -->
                        <div id="mobile-money-fields" class="row" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_mobile" class="form-label">Numéro Mobile Money</label>
                                    <input type="text" class="form-control @error('informations_retrait.numero_mobile') is-invalid @enderror"
                                           id="numero_mobile" name="informations_retrait[numero_mobile]"
                                           value="{{ old('informations_retrait.numero_mobile') }}" placeholder="Ex: 1234567890">
                                    @error('informations_retrait.numero_mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="operateur_mobile" class="form-label">Opérateur</label>
                                    <select class="form-select @error('informations_retrait.operateur_mobile') is-invalid @enderror"
                                            id="operateur_mobile" name="informations_retrait[operateur_mobile]">
                                        <option value="">Sélectionner un opérateur</option>
                                        <option value="orange" {{ old('informations_retrait.operateur_mobile') == 'orange' ? 'selected' : '' }}>Orange Money</option>
                                        <option value="mtn" {{ old('informations_retrait.operateur_mobile') == 'mtn' ? 'selected' : '' }}>Telecel Money</option>
                                        <option value="moov" {{ old('informations_retrait.operateur_mobile') == 'moov' ? 'selected' : '' }}>Moov Money</option>
                                    </select>
                                    @error('informations_retrait.operateur_mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="virement-fields" class="row" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_compte" class="form-label">Numéro de Compte</label>
                                    <input type="text" class="form-control @error('informations_retrait.numero_compte') is-invalid @enderror"
                                           id="numero_compte" name="informations_retrait[numero_compte]"
                                           value="{{ old('informations_retrait.numero_compte') }}">
                                    @error('informations_retrait.numero_compte')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom_banque" class="form-label">Nom de la Banque</label>
                                    <input type="text" class="form-control @error('informations_retrait.nom_banque') is-invalid @enderror"
                                           id="nom_banque" name="informations_retrait[nom_banque]"
                                           value="{{ old('informations_retrait.nom_banque') }}">
                                    @error('informations_retrait.nom_banque')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="cheque-fields" class="row" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom_beneficiaire" class="form-label">Nom du Bénéficiaire</label>
                                    <input type="text" class="form-control @error('informations_retrait.nom_beneficiaire') is-invalid @enderror"
                                           id="nom_beneficiaire" name="informations_retrait[nom_beneficiaire]"
                                           value="{{ old('informations_retrait.nom_beneficiaire') }}">
                                    @error('informations_retrait.nom_beneficiaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="motif" class="form-label">Motif du Retrait <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('motif') is-invalid @enderror"
                                      id="motif" name="motif" rows="3"
                                      placeholder="Expliquez la raison de votre demande de retrait..." required>{{ old('motif') }}</textarea>
                            @error('motif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('adherent.retraits.index') }}" class="btn btn-light me-2">
                                <i class="mdi mdi-arrow-left"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-send"></i> Soumettre la Demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations Importantes</h5>
                    <div class="alert alert-info">
                        <h6><i class="mdi mdi-information"></i> Conditions de Retrait</h6>
                        <ul class="mb-0">
                            <li>Le montant doit être inférieur ou égal au solde disponible</li>
                            <li>Les retraits sont traités dans les 24-48h</li>
                            <li>Des frais peuvent s'appliquer selon le type de retrait</li>
                            <li>Vous recevrez une notification une fois traité</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Résumé de l'Adhésion</h5>
                    <div id="adhesion-summary" class="text-muted">
                        <p>Sélectionnez une adhésion pour voir les détails</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Calcul des Frais</h5>
                    <div id="frais-calculation" class="text-muted">
                        <p>Sélectionnez un montant pour voir les frais</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modeRetraitSelect = document.getElementById('mode_retrait');
    const adhesionSelect = document.getElementById('adhesion_id');
    const montantInput = document.getElementById('montant_demande');
    const adhesionSummary = document.getElementById('adhesion-summary');
    const fraisCalculation = document.getElementById('frais-calculation');

    // Gestion des champs conditionnels
    modeRetraitSelect.addEventListener('change', function() {
        const selectedMode = this.value;

        // Masquer tous les champs conditionnels
        document.getElementById('mobile-money-fields').style.display = 'none';
        document.getElementById('virement-fields').style.display = 'none';
        document.getElementById('cheque-fields').style.display = 'none';

        // Afficher les champs appropriés
        switch(selectedMode) {
            case 'mobile_money':
                document.getElementById('mobile-money-fields').style.display = 'block';
                break;
            case 'virement':
                document.getElementById('virement-fields').style.display = 'block';
                break;
            case 'cheque':
                document.getElementById('cheque-fields').style.display = 'block';
                break;
        }
    });

    // Gestion du résumé d'adhésion
    adhesionSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const plan = selectedOption.dataset.plan;
            const solde = parseFloat(selectedOption.dataset.solde);
            const montant = parseFloat(selectedOption.dataset.montant);

            adhesionSummary.innerHTML = `
                <div class="border rounded p-3">
                    <h6 class="text-primary">${plan}</h6>
                    <p class="mb-1"><strong>Montant souscrit:</strong> ${montant.toLocaleString()} FCFA</p>
                    <p class="mb-1"><strong>Solde disponible:</strong> <span class="text-success">${solde.toLocaleString()} FCFA</span></p>
                    <p class="mb-0"><strong>Numéro:</strong> ${selectedOption.textContent.split(' - ')[0]}</p>
                </div>
            `;

            // Mettre à jour le solde disponible dans le formulaire
            document.getElementById('solde-disponible').textContent = solde.toLocaleString() + ' FCFA';

            // Définir le montant maximum
            montantInput.max = solde;
        } else {
            adhesionSummary.innerHTML = '<p class="text-muted">Sélectionnez une adhésion pour voir les détails</p>';
            document.getElementById('solde-disponible').textContent = '0 FCFA';
        }
    });

    // Calcul des frais
    montantInput.addEventListener('input', function() {
        const montant = parseFloat(this.value);
        const adhesionOption = adhesionSelect.options[adhesionSelect.selectedIndex];

        if (montant > 0 && adhesionOption.value) {
            // Simulation du calcul des frais (à adapter selon vos règles métier)
            const fraisRetrait = montant * 0.01; // 1% de frais
            const montantNet = montant - fraisRetrait;

            fraisCalculation.innerHTML = `
                <div class="border rounded p-3">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Montant demandé</small>
                            <p class="mb-0 fw-semibold">${montant.toLocaleString()} FCFA</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Frais de retrait</small>
                            <p class="mb-0 fw-semibold text-danger">${fraisRetrait.toLocaleString()} FCFA</p>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <small class="text-muted">Montant net reçu</small>
                        <p class="mb-0 fs-5 fw-bold text-success">${montantNet.toLocaleString()} FCFA</p>
                    </div>
                </div>
            `;
        } else {
            fraisCalculation.innerHTML = '<p class="text-muted">Sélectionnez un montant pour voir les frais</p>';
        }
    });

    // Validation du formulaire
    document.getElementById('retrait-form').addEventListener('submit', function(e) {
        const montant = parseFloat(montantInput.value);
        const adhesionOption = adhesionSelect.options[adhesionSelect.selectedIndex];
        const soldeDisponible = parseFloat(adhesionOption.dataset.solde);

        if (montant > soldeDisponible) {
            e.preventDefault();
            alert('Le montant demandé ne peut pas dépasser le solde disponible (' + soldeDisponible.toLocaleString() + ' FCFA)');
            return false;
        }
    });
});
</script>
@endpush
