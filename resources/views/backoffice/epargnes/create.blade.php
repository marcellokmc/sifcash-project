@extends('backoffice.layouts.app')

@section('title', 'Créer un nouveau compte épargne')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px 10px;
        border: 1px solid #d1d3e2;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-plus-circle text-primary"></i> Créer un nouveau compte épargne
        </h1>
        <div>
            <a href="{{ route('admin.epargnes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('admin.epargnes.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-1"></i> Informations de l'adhérent
                            </h5>
                            
                            <div class="mb-3">
                                <label for="adherent_id" class="form-label">Adhérent *</label>
                                <select class="form-select select2 @error('adherent_id') is-invalid @enderror" 
                                        id="adherent_id" 
                                        name="adherent_id" 
                                        required
                                        data-placeholder="Rechercher un adhérent...">
                                    <option value=""></option>
                                    @foreach($adherents as $adherent)
                                        <option value="{{ $adherent->id }}" 
                                                data-matricule="{{ $adherent->matricule }}"
                                                {{ old('adherent_id') == $adherent->id ? 'selected' : '' }}>
                                            {{ $adherent->nom_complet }} ({{ $adherent->matricule }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('adherent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="adherent-info"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-piggy-bank me-1"></i> Détails du compte épargne
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="type_epargne" class="form-label">Type d'épargne *</label>
                                    <select class="form-select @error('type_epargne') is-invalid @enderror" 
                                            id="type_epargne" 
                                            name="type_epargne" 
                                            required>
                                        <option value="">Sélectionnez un type</option>
                                        @foreach(\App\Models\Epargne::TYPES_EPARGNE as $key => $label)
                                            <option value="{{ $key }}" {{ old('type_epargne') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_epargne')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="date_ouverture" class="form-label">Date d'ouverture *</label>
                                    <input type="date" 
                                           class="form-control @error('date_ouverture') is-invalid @enderror" 
                                           id="date_ouverture" 
                                           name="date_ouverture" 
                                           value="{{ old('date_ouverture', now()->format('Y-m-d')) }}" 
                                           required>
                                    @error('date_ouverture')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="montant_initial" class="form-label">Montant initial (FCFA) *</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('montant_initial') is-invalid @enderror" 
                                               id="montant_initial" 
                                               name="montant_initial" 
                                               value="{{ old('montant_initial', 0) }}" 
                                               min="0" 
                                               step="100" 
                                               required>
                                        <span class="input-group-text">FCFA</span>
                                        @error('montant_initial')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="taux_interet" class="form-label">Taux d'intérêt annuel (%) *</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('taux_interet') is-invalid @enderror" 
                                               id="taux_interet" 
                                               name="taux_interet" 
                                               value="{{ old('taux_interet', 5.5) }}" 
                                               min="0" 
                                               max="100" 
                                               step="0.1" 
                                               required>
                                        <span class="input-group-text">%</span>
                                        @error('taux_interet')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-undo me-1"></i> Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialisation de Select2
        $('.select2').select2({
            width: '100%',
            placeholder: 'Rechercher un adhérent...',
            allowClear: true,
            language: {
                noResults: function() {
                    return "Aucun résultat trouvé";
                },
                searching: function() {
                    return "Recherche en cours...";
                },
                inputTooShort: function(args) {
                    return "Saisissez au moins " + args.minimum + " caractères";
                }
            }
        });
        
        // Mettre à jour les informations de l'adhérent sélectionné
        function updateAdherentInfo() {
            const selectedOption = $('#adherent_id option:selected');
            const matricule = selectedOption.data('matricule');
            
            if (selectedOption.val()) {
                // Ici, vous pouvez ajouter une requête AJAX pour récupérer plus d'informations sur l'adhérent
                // et les afficher dans #adherent-info
                $('#adherent-info').html(`
                    <strong>Matricule:</strong> ${matricule}
                `);
                
                // Vérifier si l'adhérent a déjà un compte épargne de ce type
                const typeEpargne = $('#type_epargne').val();
                if (typeEpargne) {
                    checkExistingEpargne(selectedOption.val(), typeEpargne);
                }
            } else {
                $('#adherent-info').html('');
            }
        }
        
        // Vérifier si l'adhérent a déjà un compte épargne de ce type
        function checkExistingEpargne(adherentId, typeEpargne) {
            // Ici, vous pouvez ajouter une requête AJAX pour vérifier si l'adhérent a déjà un compte de ce type
            // Pour l'instant, on se contente d'afficher un message
            if (adherentId && typeEpargne) {
                const typeLibelle = $('#type_epargne option:selected').text();
                $('#adherent-info').append(`<div class="mt-2"><i class="fas fa-info-circle text-info"></i> Vérification des comptes existants pour ${typeLibelle}...</div>`);
                
                // Simuler une requête AJAX
                setTimeout(() => {
                    // Dans une vraie implémentation, vous feriez une requête AJAX ici
                    // Par exemple :
                    /*
                    $.get(`/api/adherents/${adherentId}/has-epargne-type/${typeEpargne}`, function(response) {
                        if (response.exists) {
                            $('#adherent-info').append(`
                                <div class="alert alert-warning mt-2">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    Cet adhérent a déjà un compte épargne de type "${typeLibelle}".
                                </div>
                            `);
                        }
                    });
                    */
                }, 500);
            }
        }
        
        // Événements
        $('#adherent_id').on('change', updateAdherentInfo);
        $('#type_epargne').on('change', function() {
            const adherentId = $('#adherent_id').val();
            if (adherentId) {
                checkExistingEpargne(adherentId, $(this).val());
            }
        });
        
        // Initialisation
        updateAdherentInfo();
    });
</script>
@endpush
