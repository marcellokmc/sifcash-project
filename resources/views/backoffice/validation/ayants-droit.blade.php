@extends('backoffice.layouts.app')

@section('title', 'Validation des Ayants Droit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ayants Droit en Attente de Validation</h5>
                    <a href="{{ route('admin.adherents.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left"></i> Retour aux adhérents
                    </a>
                </div>
                <div class="card-body">
                    @if($ayantsDroit->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted">Aucun ayant droit en attente de validation</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach($ayantsDroit as $ayant)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0">{{ $ayant->nom_complet }}</h6>
                                        <small class="text-muted">Adhérent: {{ $ayant->adherent->membre_id }}</small>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="40%">Adhérent:</th>
                                                <td>{{ $ayant->adherent->nom_complet }}</td>
                                            </tr>
                                            <tr>
                                                <th>Lien de parenté:</th>
                                                <td>{{ $ayant->lien_parente }}</td>
                                            </tr>
                                            @if($ayant->date_naissance)
                                            <tr>
                                                <th>Date de naissance:</th>
                                                <td>{{ $ayant->date_naissance->format('d/m/Y') }}</td>
                                            </tr>
                                            @endif
                                            @if($ayant->contact)
                                            <tr>
                                                <th>Contact:</th>
                                                <td>{{ $ayant->contact }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Type bénéficiaire:</th>
                                                <td>
                                                    @if($ayant->type_beneficiaire == 'vie')
                                                        <span class="badge bg-info">Vie</span>
                                                    @else
                                                        <span class="badge bg-secondary">Décès</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="card-footer">
                                        <div class="btn-group btn-group-sm">
                                            <form action="{{ route('admin.ayants-droit.validate', $ayant) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> Valider
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#rejectAyantModal{{ $ayant->id }}">
                                                <i class="fas fa-times"></i> Rejeter
                                            </button>
                                        </div>

                                        <!-- Modal de rejet -->
                                        <div class="modal fade" id="rejectAyantModal{{ $ayant->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Rejet de l'ayant droit</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.ayants-droit.reject', $ayant) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="motif_rejet" class="form-label">Motif du rejet *</label>
                                                                <textarea class="form-control" id="motif_rejet" name="motif_rejet" 
                                                                          rows="3" required placeholder="Expliquez le motif du rejet..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection