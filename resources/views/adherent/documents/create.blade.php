@extends('layouts.adherent-modern')

@section('title', 'Uploader un Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Uploader un Document</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('adherent.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="type_document_id" class="form-label">Type de Document *</label>
                            <select class="form-select @error('type_document_id') is-invalid @enderror" 
                                    id="type_document_id" name="type_document_id" required>
                                <option value="">Choisir un type de document...</option>
                                @foreach($typesDocuments as $typeDoc)
                                    <option value="{{ $typeDoc->id }}" 
                                            {{ old('type_document_id') == $typeDoc->id ? 'selected' : '' }}
                                            data-recto="{{ $typeDoc->recto_requis }}"
                                            data-verso="{{ $typeDoc->verso_requis }}">
                                        {{ $typeDoc->nom }}
                                        @if($typeDoc->description)
                                            - {{ $typeDoc->description }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('type_document_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fichier_recto" class="form-label">Fichier Recto *</label>
                            <input type="file" class="form-control @error('fichier_recto') is-invalid @enderror" 
                                   id="fichier_recto" name="fichier_recto" accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="form-text">
                                Formats acceptés : JPG, JPEG, PNG, PDF (Max: 5MB)
                            </div>
                            @error('fichier_recto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="verso-field" style="display: none;">
                            <label for="fichier_verso" class="form-label">Fichier Verso</label>
                            <input type="file" class="form-control @error('fichier_verso') is-invalid @enderror" 
                                   id="fichier_verso" name="fichier_verso" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text">
                                Formats acceptés : JPG, JPEG, PNG, PDF (Max: 5MB)
                            </div>
                            @error('fichier_verso')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <strong>Conseils :</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Assurez-vous que le document est lisible et en couleur</li>
                                    <li>La photo doit être nette et sans reflet</li>
                                    <li>Toutes les informations doivent être visibles</li>
                                    <li>Le document doit être en cours de validité</li>
                                </ul>
                            </small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Uploader le Document
                            </button>
                            <a href="{{ route('adherent.documents.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type_document_id');
    const versoField = document.getElementById('verso-field');
    const versoInput = document.getElementById('fichier_verso');

    function toggleVersoField() {
        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const versoRequis = selectedOption.getAttribute('data-verso') === '1';
        
        if (versoRequis) {
            versoField.style.display = 'block';
            versoInput.setAttribute('required', 'required');
        } else {
            versoField.style.display = 'none';
            versoInput.removeAttribute('required');
        }
    }

    typeSelect.addEventListener('change', toggleVersoField);
    toggleVersoField(); // Initial call
});
</script>
@endsection
