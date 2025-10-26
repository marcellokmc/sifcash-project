@extends('backoffice.layouts.app')

@section('title', 'Modifier le compte épargne #' . $epargne->numero_compte)

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
            <i class="fas fa-edit text-primary"></i> Modifier le compte épargne #{{ $epargne->numero_compte }}
        </h1>
        <div>
            <a href="{{ route('admin.epargnes.show', $epargne) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-1"></i> Voir le détail
            </a>
            <a href="{{ route('admin.epargnes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('admin.epargnes.update', $epargne) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-1"></i> Informations de l'adhérent
                            </h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Adhérent</label>
                                <input type="text" 
                                       class="form-control-plaintext" 
                                       value="{{ $epargne->adherent->nom_complet }} ({{ $epargne->adherent->matricule }})" 
                                       readonly>
                                <input type="hidden" name="adherent_id" value="{{ $epargne->adherent_id }}">
                                <div class="form-text">
                                    <strong>Matricule:</strong> {{ $epargne->adherent->matricule }}
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
                                        @foreach(\App\Models\Epargne::TYPES_EPARGNE as $key => $label)
                                            <option value="{{ $key }}" {{ old('type_epargne', $epargne->type_epargne) == $key ? 'selected' : '' }}>
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
                                           value="{{ old('date_ouverture', $epargne->date_ouverture->format('Y-m-d')) }}" 
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
                                               value="{{ old('montant_initial', $epargne->montant_initial) }}" 
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
                                               value="{{ old('taux_interet', $epargne->taux_interet) }}" 
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
                                <label for="statut" class="form-label">Statut du compte *</label>
                                <select class="form-select @error('statut') is-invalid @enderror" 
                                        id="statut" 
                                        name="statut" 
                                        required>
                                    @foreach(\App\Models\Epargne::STATUTS as $key => $label)
                                        <option value="{{ $key }}" {{ old('statut', $epargne->statut) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3">{{ old('notes', $epargne->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.epargnes.show', $epargne) }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Mettre à jour
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
        // Initialisation de Select2 si nécessaire
        $('.select2').select2({
            width: '100%',
            placeholder: 'Sélectionnez une option...',
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
    });
</script>
@endpush
