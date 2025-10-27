@extends('layouts.adherent-modern')

@section('title', 'Soumettre un Paiement')

@section('content')
<div class="container-fluid">
    <!-- Header Mobile-First -->
    <div class="row mb-mobile-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">💸 Nouveau Paiement</h4>
                    <small class="text-muted">
                        <a href="{{ route('adherent.dashboard') }}" class="text-decoration-none">Tableau de bord</a> / 
                        <a href="{{ route('adherent.paiements.index') }}" class="text-decoration-none">Paiements</a> / 
                        Nouveau
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card sif-card-mobile">
                <div class="card-body p-mobile-3">
                    <form action="{{ route('adherent.paiements.store') }}" method="POST" enctype="multipart/form-data" id="paiement-form" class="form-mobile">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="adhesion_id" class="form-label">Adhésion <span class="text-danger">*</span></label>
                                    @if($adhesions->count() > 0)
                                        <select class="form-control @error('adhesion_id') is-invalid @enderror" id="adhesion_id" name="adhesion_id" required>
                                            <option value="">Sélectionner une adhésion</option>
                                            @foreach($adhesions as $adhesion)
                                            <option value="{{ $adhesion->id }}"
                                                    data-plan="{{ $adhesion->plan->nom }}"
                                                    data-montant="{{ $adhesion->montant_souscrit }}"
                                                    {{ old('adhesion_id') == $adhesion->id ? 'selected' : '' }}>
                                                {{ $adhesion->numero_adhesion }} - {{ $adhesion->plan->nom }}
                                            </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            Aucune adhésion active trouvée. 
                                            <a href="{{ route('adherent.adhesions.index') }}" class="alert-link">Créer une adhésion d'abord</a>.
                                        </div>
                                        <select class="form-control" disabled>
                                            <option>Aucune adhésion disponible</option>
                                        </select>
                                    @endif
                                    @error('adhesion_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                    <select class="form-control @error('categorie') is-invalid @enderror" id="categorie" name="categorie" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        <option value="ouverture" {{ old('categorie') == 'ouverture' ? 'selected' : '' }}>Frais d'ouverture</option>
                                        <option value="cotisation" {{ old('categorie') == 'cotisation' ? 'selected' : '' }}>Cotisation</option>
                                        <option value="credit" {{ old('categorie') == 'credit' ? 'selected' : '' }}>Paiement crédit</option>
                                        <option value="autre" {{ old('categorie') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('categorie')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="montant" class="form-label">Montant <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('montant') is-invalid @enderror"
                                               id="montant" name="montant" step="0.01" min="0.01"
                                               value="{{ old('montant') }}" required>
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                    @error('montant')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="mode_paiement" class="form-label">Mode de Paiement <span class="text-danger">*</span></label>
                                    <select class="form-control @error('mode_paiement') is-invalid @enderror" id="mode_paiement" name="mode_paiement" required>
                                        <option value="">Sélectionner un mode</option>
                                        <option value="mobile_money" {{ old('mode_paiement') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                        <option value="virement" {{ old('mode_paiement') == 'virement' ? 'selected' : '' }}>Virement Bancaire</option>
                                        <option value="cheque" {{ old('mode_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                                        <option value="especes" {{ old('mode_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                                    </select>
                                    @error('mode_paiement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Champs conditionnels selon le mode de paiement -->
                        <div id="mobile-money-fields" class="row g-3" style="display: none;">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="reference_paiement" class="form-label">Référence Mobile Money</label>
                                    <input type="text" class="form-control @error('reference_paiement') is-invalid @enderror"
                                           id="reference_paiement" name="reference_paiement"
                                           value="{{ old('reference_paiement') }}" placeholder="Ex: 1234567890">
                                    @error('reference_paiement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="virement-fields" class="row g-3" style="display: none;">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="numero_compte_beneficiaire" class="form-label">Numéro de Compte Bénéficiaire</label>
                                    <input type="text" class="form-control @error('numero_compte_beneficiaire') is-invalid @enderror"
                                           id="numero_compte_beneficiaire" name="numero_compte_beneficiaire"
                                           value="{{ old('numero_compte_beneficiaire') }}">
                                    @error('numero_compte_beneficiaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="banque_emetteur" class="form-label">Banque Émettrice</label>
                                    <input type="text" class="form-control @error('banque_emetteur') is-invalid @enderror"
                                           id="banque_emetteur" name="banque_emetteur"
                                           value="{{ old('banque_emetteur') }}">
                                    @error('banque_emetteur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="cheque-fields" class="row g-3" style="display: none;">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="reference_cheque" class="form-label">Référence du Chèque</label>
                                    <input type="text" class="form-control @error('reference_cheque') is-invalid @enderror"
                                           id="reference_cheque" name="reference_cheque"
                                           value="{{ old('reference_cheque') }}">
                                    @error('reference_cheque')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="preuve" class="form-label">Preuve de Paiement <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('preuve') is-invalid @enderror"
                                   id="preuve" name="preuve" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="form-text">Formats acceptés: PDF, JPG, JPEG, PNG (Max: 5MB)</div>
                            @error('preuve')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optionnel)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3"
                                      placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="btn-group-mobile mt-4">
                            <button type="submit" class="btn btn-primary btn-mobile" {{ $adhesions->count() == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane me-1"></i> Soumettre le Paiement
                            </button>
                            <a href="{{ route('adherent.paiements.index') }}" class="btn btn-outline-secondary btn-mobile">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <!-- Informations Importantes -->
            <div class="card sif-card-mobile mb-mobile-3">
                <div class="card-body p-mobile-3">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info-circle text-info me-2"></i>Informations Importantes
                    </h6>
                    <div class="alert alert-info mb-0">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-clipboard-list me-1"></i> Instructions
                        </h6>
                        <ul class="mb-0 small">
                            <li class="mb-2">Assurez-vous que le montant correspond exactement à votre paiement</li>
                            <li class="mb-2">Joignez une preuve claire et lisible de votre paiement</li>
                            <li class="mb-2">Votre paiement sera validé dans les 24-48h</li>
                            <li>Vous recevrez une notification une fois validé</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Résumé Adhésion -->
            <div class="card sif-card-mobile">
                <div class="card-body p-mobile-3">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-receipt text-primary me-2"></i>Résumé de l'Adhésion
                    </h6>
                    <div id="adhesion-summary" class="text-muted text-center">
                        <i class="fas fa-hand-pointer fa-2x mb-2 d-block"></i>
                        <small>Sélectionnez une adhésion pour voir les détails</small>
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
    const modePaiementSelect = document.getElementById('mode_paiement');
    const adhesionSelect = document.getElementById('adhesion_id');
    const adhesionSummary = document.getElementById('adhesion-summary');

    // Gestion des champs conditionnels
    modePaiementSelect.addEventListener('change', function() {
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
    if (adhesionSelect) {
        adhesionSelect.addEventListener('change', function() {
            console.log('Sélection d\'adhésion changée:', this.value);
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const plan = selectedOption.dataset.plan;
                const montant = selectedOption.dataset.montant;
                
                console.log('Données adhésion:', { plan, montant });

                if (plan && montant) {
                    adhesionSummary.innerHTML = `
                        <div class="border rounded p-3 bg-light text-start">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-file-contract text-primary me-2 fa-lg"></i>
                                <h6 class="text-primary mb-0 fw-bold">${plan}</h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <small class="text-muted d-block mb-1">Montant souscrit</small>
                                    <p class="mb-0 fw-bold text-success">${parseFloat(montant).toLocaleString('fr-FR')} FCFA</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block mb-1">Numéro</small>
                                    <p class="mb-0 fw-bold">${selectedOption.textContent.split(' - ')[0]}</p>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    adhesionSummary.innerHTML = `
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i> Données d'adhésion incomplètes
                        </div>
                    `;
                }
            } else {
                adhesionSummary.innerHTML = `
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-hand-pointer fa-2x mb-2 d-block"></i>
                        <small>Sélectionnez une adhésion pour voir les détails</small>
                    </div>
                `;
            }
        });
    } else {
        console.log('Aucun select d\'adhésion trouvé (probablement aucune adhésion disponible)');
    }

    // Validation du formulaire
    document.getElementById('paiement-form').addEventListener('submit', function(e) {
        const preuve = document.getElementById('preuve').files[0];
        if (preuve && preuve.size > 5 * 1024 * 1024) {
            e.preventDefault();
            alert('Le fichier de preuve ne doit pas dépasser 5MB');
            return false;
        }
    });
});
</script>
@endpush
