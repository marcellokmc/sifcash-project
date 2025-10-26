@extends('backoffice.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Modifier la pénalité de retrait anticipé</h1>
        <a href="{{ route('admin.penalites.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.penalites.update', $penalite) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="pourcentage_capital_requis" class="form-label">
                        Pourcentage du capital requis (%)
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" 
                               class="form-control @error('pourcentage_capital_requis') is-invalid @enderror" 
                               id="pourcentage_capital_requis" 
                               name="pourcentage_capital_requis" 
                               value="{{ old('pourcentage_capital_requis', $penalite->pourcentage_capital_requis) }}" 
                               min="0" 
                               max="100" 
                               step="0.01" 
                               required>
                        <span class="input-group-text">%</span>
                        @error('pourcentage_capital_requis')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="duree_preavis_jours" class="form-label">
                        Durée du préavis (en jours)
                        <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           class="form-control @error('duree_preavis_jours') is-invalid @enderror" 
                           id="duree_preavis_jours" 
                           name="duree_preavis_jours" 
                           value="{{ old('duree_preavis_jours', $penalite->duree_preavis_jours) }}" 
                           min="0" 
                           required>
                    @error('duree_preavis_jours')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="taux_penalite" class="form-label">
                        Taux de pénalité (%)
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" 
                               class="form-control @error('taux_penalite') is-invalid @enderror" 
                               id="taux_penalite" 
                               name="taux_penalite" 
                               value="{{ old('taux_penalite', $penalite->taux_penalite) }}" 
                               min="0" 
                               max="100" 
                               step="0.01" 
                               required>
                        <span class="input-group-text">%</span>
                        @error('taux_penalite')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="actif" 
                           name="actif" 
                           value="1" 
                           {{ $penalite->actif ? 'checked' : '' }}>
                    <label class="form-check-label" for="actif">
                        Activer cette pénalité
                    </label>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection