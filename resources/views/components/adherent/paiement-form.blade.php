@props(['adhesions' => [], 'paiement' => null])

<div class="card">
    <div class="card-body">
        <form action="{{ $paiement ? route('adherent.paiements.update', $paiement) : route('adherent.paiements.store') }}"
              method="POST" enctype="multipart/form-data" id="paiement-form">
            @csrf
            @if($paiement)
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
                                    data-montant="{{ $adhesion->montant_souscrit }}"
                                    {{ old('adhesion_id', $paiement->adhesion_id ?? '') == $adhesion->id ? 'selected' : '' }}>
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
                        <label for="categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                        <select class="form-select @error('categorie') is-invalid @enderror" id="categorie" name="categorie" required>
                            <option value="">Sélectionner une catégorie</option>
                            <option value="ouverture" {{ old('categorie', $paiement->categorie ?? '') == 'ouverture' ? 'selected' : '' }}>Frais d'ouverture</option>
                            <option value="cotisation" {{ old('categorie', $paiement->categorie ?? '') == 'cotisation' ? 'selected' : '' }}>Cotisation</option>
                            <option value="credit" {{ old('categorie', $paiement->categorie ?? '') == 'credit' ? 'selected' : '' }}>Paiement crédit</option>
                            <option value="autre" {{ old('categorie', $paiement->categorie ?? '') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('categorie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('montant') is-invalid @enderror"
                                   id="montant" name="montant" step="0.01" min="0.01"
                                   value="{{ old('montant', $paiement->montant ?? '') }}" required>
                            <span class="input-group-text">FCFA</span>
                        </div>
                        @error('montant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mode_paiement" class="form-label">Mode de Paiement <span class="text-danger">*</span></label>
                        <select class="form-select @error('mode_paiement') is-invalid @enderror" id="mode_paiement" name="mode_paiement" required>
                            <option value="">Sélectionner un mode</option>
                            <option value="mobile_money" {{ old('mode_paiement', $paiement->mode_paiement ?? '') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="virement" {{ old('mode_paiement', $paiement->mode_paiement ?? '') == 'virement' ? 'selected' : '' }}>Virement Bancaire</option>
                            <option value="cheque" {{ old('mode_paiement', $paiement->mode_paiement ?? '') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                            <option value="especes" {{ old('mode_paiement', $paiement->mode_paiement ?? '') == 'especes' ? 'selected' : '' }}>Espèces</option>
                        </select>
                        @error('mode_paiement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Champs conditionnels selon le mode de paiement -->
            <div id="mobile-money-fields" class="row" style="display: none;">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="reference_paiement" class="form-label">Référence Mobile Money</label>
                        <input type="text" class="form-control @error('reference_paiement') is-invalid @enderror"
                               id="reference_paiement" name="reference_paiement"
                               value="{{ old('reference_paiement', $paiement->reference_paiement ?? '') }}" placeholder="Ex: 1234567890">
                        @error('reference_paiement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div id="virement-fields" class="row" style="display: none;">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="numero_compte_beneficiaire" class="form-label">Numéro de Compte Bénéficiaire</label>
                        <input type="text" class="form-control @error('numero_compte_beneficiaire') is-invalid @enderror"
                               id="numero_compte_beneficiaire" name="numero_compte_beneficiaire"
                               value="{{ old('numero_compte_beneficiaire', $paiement->numero_compte_beneficiaire ?? '') }}">
                        @error('numero_compte_beneficiaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="banque_emetteur" class="form-label">Banque Émettrice</label>
                        <input type="text" class="form-control @error('banque_emetteur') is-invalid @enderror"
                               id="banque_emetteur" name="banque_emetteur"
                               value="{{ old('banque_emetteur', $paiement->banque_emetteur ?? '') }}">
                        @error('banque_emetteur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div id="cheque-fields" class="row" style="display: none;">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="reference_cheque" class="form-label">Référence du Chèque</label>
                        <input type="text" class="form-control @error('reference_cheque') is-invalid @enderror"
                               id="reference_cheque" name="reference_cheque"
                               value="{{ old('reference_cheque', $paiement->reference_cheque ?? '') }}">
                        @error('reference_cheque')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="preuve" class="form-label">Preuve de Paiement <span class="text-danger">*</span></label>
                <input type="file" class="form-control @error('preuve') is-invalid @enderror"
                       id="preuve" name="preuve" accept=".pdf,.jpg,.jpeg,.png" {{ $paiement ? '' : 'required' }}>
                <div class="form-text">Formats acceptés: PDF, JPG, JPEG, PNG (Max: 5MB)</div>
                @if($paiement && $paiement->preuve)
                <div class="mt-2">
                    <small class="text-muted">Fichier actuel: {{ basename($paiement->preuve) }}</small>
                </div>
                @endif
                @error('preuve')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes (Optionnel)</label>
                <textarea class="form-control @error('notes') is-invalid @enderror"
                          id="notes" name="notes" rows="3"
                          placeholder="Informations complémentaires...">{{ old('notes', $paiement->notes ?? '') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('adherent.paiements.index') }}" class="btn btn-light me-2">
                    <i class="mdi mdi-arrow-left"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-content-save"></i> {{ $paiement ? 'Mettre à jour' : 'Soumettre le Paiement' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modePaiementSelect = document.getElementById('mode_paiement');

    // Gestion des champs conditionnels
    function toggleFields() {
        const selectedMode = modePaiementSelect.value;

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

    modePaiementSelect.addEventListener('change', toggleFields);

    // Initialiser l'affichage des champs
    toggleFields();
});
</script>
@endpush
