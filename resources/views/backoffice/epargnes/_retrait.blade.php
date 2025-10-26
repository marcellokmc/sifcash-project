<!-- Modal de retrait -->
<div class="modal fade" id="retraitModal" tabindex="-1" aria-labelledby="retraitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="#">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="retraitModalLabel">
                        <i class="fas fa-minus-circle me-1"></i> Effectuer un retrait
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="montant_retrait" class="form-label">Montant (FCFA) *</label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control @error('montant') is-invalid @enderror" 
                                   id="montant_retrait" 
                                   name="montant" 
                                   value="{{ old('montant') }}" 
                                   min="100" 
                                   step="100" 
                                   max="{{ $epargne->solde_actuel ?? 0 }}" 
                                   required>
                            <span class="input-group-text">FCFA</span>
                            @error('montant')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">
                            Solde disponible: {{ number_format($epargne->solde_actuel ?? 0, 0, ',', ' ') }} FCFA
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="date_operation_retrait" class="form-label">Date de l'opération *</label>
                        <input type="date" 
                               class="form-control @error('date_operation') is-invalid @enderror" 
                               id="date_operation_retrait" 
                               name="date_operation" 
                               value="{{ old('date_operation') }}" 
                               required>
                        @error('date_operation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="moyen_paiement_retrait" class="form-label">Moyen de paiement *</label>
                        <select class="form-select @error('moyen_paiement') is-invalid @enderror" 
                                id="moyen_paiement_retrait" 
                                name="moyen_paiement" 
                                required>
                            <option value="">Sélectionnez un moyen de paiement</option>
                            @foreach(\App\Models\TransactionEpargne::MOYENS_PAIEMENT as $key => $label)
                                @if($key !== 'interet') {{-- On ne permet pas de sélectionner 'intérêt' pour un retrait --}}
                                    <option value="{{ $key }}" {{ old('moyen_paiement') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('moyen_paiement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="reference_retrait" class="form-label">Référence</label>
                        <input type="text" 
                               class="form-control @error('reference') is-invalid @enderror" 
                               id="reference_retrait" 
                               name="reference" 
                               value="{{ old('reference') }}" 
                               placeholder="N° de chèque, référence virement, etc.">
                        @error('reference')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-0">
                        <label for="notes_retrait" class="form-label">Motif du retrait</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes_retrait" 
                                  name="notes" 
                                  rows="2" 
                                  placeholder="Raison du retrait"
                                  required>{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-1"></i> Enregistrer le retrait
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
