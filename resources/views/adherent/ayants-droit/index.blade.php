@extends('layouts.adherent-modern')

@section('title', 'Mes Ayants Droit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes Ayants Droit</h5>
                    @php($nonRejectedCount = $ayantsDroit->where('statut_validation', '!=', 'rejeté')->count())
                    @if($nonRejectedCount < 2)
                    <a href="{{ route('adherent.ayants-droit.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Ajouter un Ayant Droit
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($ayantsDroit->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun ayant droit enregistré</p>
                            <a href="{{ route('adherent.ayants-droit.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajouter un Ayant Droit
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($ayantsDroit as $ayant)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ $ayant->nom_complet }}</h6>
                                        <div>
                                            @if($ayant->isValide())
                                                <span class="badge bg-success">Validé</span>
                                            @elseif($ayant->isEnAttente())
                                                <span class="badge bg-warning">En attente</span>
                                            @else
                                                <span class="badge bg-danger">Rejeté</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="40%">Lien de parenté:</th>
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
                                        @if($ayant->isRejete() && !empty($ayant->motif_rejet))
                                            <div class="alert alert-danger mt-2 mb-0 py-2">
                                                <small>
                                                    <strong>Motif de rejet :</strong>
                                                    {{ $ayant->motif_rejet }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer d-flex justify-content-between align-items-center">
                                        <div class="btn-group btn-group-sm">
                                            @if($ayant->isEnAttente())
                                            <a href="{{ route('adherent.ayants-droit.edit', $ayant) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('adherent.ayants-droit.destroy', $ayant) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                        @if($ayant->statut_validation === 'rejeté' && $nonRejectedCount < 2)
                                            <a href="{{ route('adherent.ayants-droit.create') }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-plus"></i> Ajouter un autre
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="alert alert-info mt-3">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <strong>Information :</strong> Vous pouvez avoir maximum 2 ayants droit (hors ayants droit rejetés).
                                Si un ayant droit est rejeté, vous pouvez en proposer un autre.
                                Toute modification doit être validée par un agent.
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
