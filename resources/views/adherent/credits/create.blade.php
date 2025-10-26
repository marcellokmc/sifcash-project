@extends('layouts.adherent-modern')

@section('title', 'Demander un Crédit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Demander un Crédit</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('adherent.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('adherent.credits.index') }}">Mes Crédits</a></li>
                        <li class="breadcrumb-item active">Nouvelle Demande</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Formulaire de demande de crédit</h4>
                    <p class="card-title-desc">Remplissez le formulaire ci-dessous pour soumettre votre demande de crédit.</p>
                    
                    @if($conditions->isNotEmpty())
                        <div class="alert alert-info">
                            <h5 class="alert-heading">Conditions d'éligibilité</h5>
                            <ul class="mb-0">
                                @foreach($conditions as $condition)
                                    <li>{{ $condition->description }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('adherent.credits.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="montant" class="form-label">Montant demandé *</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('montant') is-invalid @enderror" 
                                               id="montant" name="montant" value="{{ old('montant') }}" 
                                               min="0" step="0.01" required>
                                        <span class="input-group-text">FCFA</span>
                                        @error('montant')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duree" class="form-label">Durée (en mois) *</label>
                                    <input type="number" class="form-control @error('duree') is-invalid @enderror" 
                                           id="duree" name="duree" value="{{ old('duree') }}" 
                                           min="1" required>
                                    @error('duree')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="motif" class="form-label">Motif du crédit *</label>
                            <textarea class="form-control @error('motif') is-invalid @enderror" 
                                      id="motif" name="motif" rows="3" required>{{ old('motif') }}</textarea>
                            @error('motif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="garanties" class="form-label">Garanties proposées</label>
                            <textarea class="form-control @error('garanties') is-invalid @enderror" 
                                      id="garanties" name="garanties" rows="2">{{ old('garanties') }}</textarea>
                            <small class="form-text text-muted">Décrivez les garanties que vous pouvez fournir pour ce crédit (optionnel).</small>
                            @error('garanties')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="documents" class="form-label">Documents justificatifs</label>
                            <input type="file" class="form-control @error('documents') is-invalid @enderror" 
                                   id="documents" name="documents[]" multiple>
                            <small class="form-text text-muted">Vous pouvez joindre plusieurs fichiers (PDF, JPG, PNG). Taille maximale : 5 Mo par fichier.</small>
                            @error('documents.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('adherent.credits.index') }}" class="btn btn-light">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-send-plane-line align-middle me-1"></i> Soumettre la demande
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
<script>
    // Validation côté client
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Validation du montant
            const montant = document.getElementById('montant');
            if (montant.value <= 0) {
                isValid = false;
                montant.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'Le montant doit être supérieur à 0';
                montant.parentNode.appendChild(errorDiv);
            }
            
            // Validation de la durée
            const duree = document.getElementById('duree');
            if (duree.value < 1) {
                isValid = false;
                duree.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'La durée doit être d\'au moins 1 mois';
                duree.parentNode.appendChild(errorDiv);
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>
@endpush
