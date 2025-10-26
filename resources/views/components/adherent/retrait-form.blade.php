@props(['adhesions' => [], 'retrait' => null])

<div class="card">
    <div class="card-body">
        <form action="{{ $retrait ? route('adherent.retraits.update', $retrait) : route('adherent.retraits.store') }}"
              method="POST" id="retrait-form">
            @csrf
            @if($retrait)
                @method('PUT')
            @endif

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
                                    {{ old('adhesion_id', $retrait->adhesion_id ?? '') == $adhesion->id ? 'selected' : '' }}>
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
                            <option value="partiel" {{ old('type_retrait', $retrait->type_retrait ?? '') == 'partiel' ? 'selected' : '' }}>Retrait Partiel</option>
                            <option value="total" {{ old('type_retrait', $retrait->type_retrait ?? '') == 'total' ? 'selected' : '' }}>Retrait Total</option>
                            <option value="urgence" {{ old('type_retrait', $retrait->type_retrait ?? '') == 'urgence' ? 'selected' : '' }}>Retrait d'Urgence</option>
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
                                   value="{{ old('montant_demande', $retrait->montant_demande ?? '') }}" required>
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
                            <option value="mobile_money" {{ old('mode_retrait', $retrait->mode_retrait ?? '') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="virement" {{ old('mode_retrait', $retrait->mode_retrait ?? '') == 'virement' ? 'selected' : '' }}>Virement Bancaire</option>
                            <option value="cheque" {{ old('mode_retrait', $retrait->mode_retrait ?? '') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                            <option value="especes" {{ old('mode_retrait', $retrait->mode_retrait ?? '') == 'especes' ? 'selected' : '' }}>Espèces</option>
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
                               value="{{ old('informations_retrait.numero_mobile', $retrait->informations_retrait['numero_mobile'] ?? '') }}" placeholder="Ex: 1234567890">
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
                            <option value="orange" {{ old('informations_retrait.operateur_mobile', $retrait->informations_retrait['operateur_mobile'] ?? '') == 'orange' ? 'selected' : '' }}>Orange Money</option>
                            <option value="mtn" {{ old('informations_retrait.operateur_mobile', $retrait->informations_retrait['operateur_mobile'] ?? '') == 'mtn' ? 'selected' : '' }}>MTN Mobile Money</option>
                            <option value="moov" {{ old('informations_retrait.operateur_mobile', $retrait->informations_retrait['operateur_mobile'] ?? '') == 'moov' ? 'selected' : '' }}>Moov Money</option>
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
                               value="{{ old('informations_retrait.numero_compte', $retrait->informations_retrait['numero_compte'] ?? '') }}">
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
                               value="{{ old('informations_retrait.nom_banque', $retrait->informations_retrait['nom_banque'] ?? '') }}">
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
                               value="{{ old('informations_retrait.nom_beneficiaire', $retrait->informations_retrait['nom_beneficiaire'] ?? '') }}">
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
                          placeholder="Expliquez la raison de votre demande de retrait..." required>{{ old('motif', $retrait->motif ?? '') }}</textarea>
                @error('motif')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('adherent.retraits.index') }}" class="btn btn-light me-2">
                    <i class="mdi mdi-arrow-left"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-send"></i> {{ $retrait ? 'Mettre à jour' : 'Soumettre la Demande' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modeRetraitSelect = document.getElementById('mode_retrait');
    const adhesionSelect = document.getElementById('adhesion_id');
    const montantInput = document.getElementById('montant_demande');

    // Gestion des champs conditionnels
    function toggleFields() {
        const selectedMode = modeRetraitSelect.value;

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
    }

    modeRetraitSelect.addEventListener('change', toggleFields);

    // Gestion du solde disponible
    adhesionSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const solde = parseFloat(selectedOption.dataset.solde);
            document.getElementById('solde-disponible').textContent = solde.toLocaleString() + ' FCFA';
            montantInput.max = solde;
        } else {
            document.getElementById('solde-disponible').textContent = '0 FCFA';
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

    // Initialiser l'affichage des champs
    toggleFields();

    // Initialiser le solde si une adhésion est déjà sélectionnée
    if (adhesionSelect.value) {
        adhesionSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
