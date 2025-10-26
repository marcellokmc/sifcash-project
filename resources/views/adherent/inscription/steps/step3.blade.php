@extends('layouts.adherent-modern')

@section('title', 'Inscription - Étape 3')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">Inscription Adhérent - Étape 3/3</h4>
                    <p class="text-muted mb-0">Upload des documents</p>
                    
                    <!-- Barre de progression -->
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 100%;" 
                             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-success"><i class="fas fa-check"></i> Profil</small>
                        <small class="text-success"><i class="fas fa-check"></i> Ayants Droit</small>
                        <small class="text-primary">Documents</small>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        // Initialize all variables at the top
                        $missingTypes = collect(session('missing_types', []));
                        $missingIds = $missingTypes->pluck('id')->toArray();
                        $typesDocuments = $typesDocuments ?? collect();
                    @endphp

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                            @if($missingTypes->isNotEmpty())
                                <ul class="mt-2 mb-0">
                                    @foreach($missingTypes as $mt)
                                        <li>
                                            <a href="#doc-{{ $mt['id'] }}" class="text-danger text-decoration-underline">
                                                {{ $mt['nom'] }} (aller au formulaire)
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('adherent.inscription.documents') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Information :</strong> Veuillez uploader les documents requis pour finaliser votre inscription.
                            Les documents doivent être en couleur, lisibles et en cours de validité.
                            <br>
                            <strong>Important :</strong> Sélectionnez <u>un seul</u> document d'identité (CNI, Passeport, Permis, etc.). Les fichiers recto/verso peuvent être requis selon le type.
                        </div>

                        <div class="row">
                            @foreach($typesDocuments as $typeDoc)
                                @php
                                    $name = mb_strtolower($typeDoc->nom ?? '');
                                    $isIdentity = $name && (str_contains($name, 'identit') || str_contains($name, 'cni') || str_contains($name, 'passeport') || str_contains($name, 'permis'));
                                    $isMissing = isset($typeDoc->id) && in_array($typeDoc->id, $missingIds);
                                @endphp
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 {{ $isMissing ? 'border-danger' : '' }}" id="doc-{{ $typeDoc->id }}"
                                     data-identity="{{ $isIdentity ? '1' : '0' }}"
                                     data-doc-id="{{ $typeDoc->id }}"
                                     data-recto-req="{{ $typeDoc->recto_requis ? '1' : '0' }}"
                                     data-verso-req="{{ $typeDoc->verso_requis ? '1' : '0' }}">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ $typeDoc->nom }}
                                            @if($isMissing)
                                                <span class="badge bg-danger ms-2">Manquant</span>
                                            @endif
                                        </h6>
                                        @if($isIdentity)
                                            <div class="form-check m-0">
                                                <input class="form-check-input identity-radio" type="radio" name="identity_choice" value="{{ $typeDoc->id }}" @checked(old('identity_choice') == $typeDoc->id) aria-label="Choisir ce document d'identité">
                                            </div>
                                        @endif
                                        @if($typeDoc->description)
                                            <small class="text-muted">{{ $typeDoc->description }}</small>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Fichier Recto *
                                                @if($typeDoc->recto_requis)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="file" class="form-control identity-file @error('documents.'.$typeDoc->id.'.recto') is-invalid @enderror" 
                                                   name="documents[{{ $typeDoc->id }}][recto]" 
                                                   accept=".jpg,.jpeg,.png,.pdf" {{ (!$isIdentity && $typeDoc->recto_requis) ? 'required' : '' }}>
                                            @error('documents.'.$typeDoc->id.'.recto')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                Formats: JPG, JPEG, PNG, PDF (Max: 5MB)
                                            </div>
                                        </div>

                                        @if($typeDoc->verso_requis)
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Fichier Verso *
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" class="form-control identity-file @error('documents.'.$typeDoc->id.'.verso') is-invalid @enderror" 
                                                   name="documents[{{ $typeDoc->id }}][verso]" 
                                                   accept=".jpg,.jpeg,.png,.pdf" {{ !$isIdentity ? 'required' : '' }}>
                                            @error('documents.'.$typeDoc->id.'.verso')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                Formats: JPG, JPEG, PNG, PDF (Max: 5MB)
                                            </div>
                                        </div>
                                        @endif

                                        <div class="alert alert-warning py-2">
                                            <small>
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <strong>Conseils :</strong>
                                                <ul class="mb-0 mt-1">
                                                    <li>Document en couleur et lisible</li>
                                                    <li>Photo nette sans reflet</li>
                                                    <li>Toutes informations visibles</li>
                                                    <li>Document en cours de validité</li>
                                                </ul>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Presque terminé !</strong> Après l'upload de vos documents, votre dossier sera soumis à validation.
                            Vous recevrez une notification une fois votre compte activé.
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="{{ route('adherent.inscription', ['step' => 2]) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Retour
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-2"></i> Finaliser l'inscription
                                </button>
                            </div>
                        </div>
                    </form>
                    <script>
                        (function(){
                            function applyIdentitySelection() {
                                const selected = document.querySelector('input.identity-radio:checked');
                                const identityCards = document.querySelectorAll('.card[data-identity="1"]');
                                identityCards.forEach(card => {
                                    const recto = card.querySelector('input.identity-file[name^="documents"][name$="[recto]"]');
                                    const verso = card.querySelector('input.identity-file[name^="documents"][name$="[verso]"]');
                                    const rectoReq = card.getAttribute('data-recto-req') === '1';
                                    const versoReq = card.getAttribute('data-verso-req') === '1';
                                    const isSelected = selected && selected.value === card.getAttribute('data-doc-id');
                                    if (recto) {
                                        recto.disabled = !isSelected;
                                        recto.required = isSelected && rectoReq;
                                    }
                                    if (verso) {
                                        verso.disabled = !isSelected;
                                        verso.required = isSelected && versoReq;
                                    }
                                });
                            }
                            document.addEventListener('change', function(e){
                                if (e.target && e.target.classList.contains('identity-radio')) {
                                    applyIdentitySelection();
                                }
                            });
                            // Au chargement initial
                            applyIdentitySelection();
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
