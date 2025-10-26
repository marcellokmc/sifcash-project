@extends('layouts.adherent-modern')

@section('title', 'Souscrire à un plan')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary mb-1">
                <i class="fas fa-handshake me-2"></i>Souscrire à un plan
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('adherent.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('adherent.adhesions.index') }}">Mes adhésions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nouvelle souscription</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('adherent.adhesions.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    @include('components.alerts')

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Nouvelle adhésion à un plan
                    </h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('adherent.adhesions.store') }}">
                        @csrf
                        
                        <!-- Sélection du plan -->
                        <div class="mb-3">
                            <label for="plan_id" class="form-label">Plan d'épargne <span class="text-danger">*</span></label>
                            <select name="plan_id" id="plan_id" class="form-select @error('plan_id') is-invalid @enderror" required>
                                <option value="">-- Choisissez un plan --</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->nom }} - Taux: {{ $plan->taux_interet }}% 
                                        ({{ number_format($plan->montant_min, 0, ',', ' ') }} - {{ number_format($plan->montant_max, 0, ',', ' ') }} FCFA)
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Sélectionnez le plan qui correspond à vos objectifs d'épargne
                            </div>
                        </div>

                        <!-- Montant souscrit -->
                        <div class="mb-3">
                            <label for="montant_souscrit" class="form-label">Montant de souscription (FCFA) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                <input type="number" name="montant_souscrit" id="montant_souscrit" 
                                       class="form-control @error('montant_souscrit') is-invalid @enderror" 
                                       value="{{ old('montant_souscrit') }}" 
                                       min="1" step="1" required 
                                       placeholder="Montant en FCFA">
                                <span class="input-group-text">FCFA</span>
                            </div>
                            @error('montant_souscrit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Le montant doit être compris dans les limites du plan sélectionné
                            </div>
                        </div>

                        <!-- Date de début -->
                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date de début <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="date_debut" id="date_debut" 
                                       class="form-control @error('date_debut') is-invalid @enderror" 
                                       value="{{ old('date_debut', date('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d') }}" required>
                            </div>
                            @error('date_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Date à partir de laquelle votre adhésion sera active
                            </div>
                        </div>

                        <!-- Option renouvelable -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="renouvelable" id="renouvelable" value="1" {{ old('renouvelable') ? 'checked' : '' }}>
                                <label class="form-check-label" for="renouvelable">
                                    <strong>Adhésion renouvelable automatiquement</strong>
                                </label>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Si cochée, votre adhésion sera renouvelée automatiquement à l'échéance
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('adherent.adhesions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Confirmer la souscription
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
