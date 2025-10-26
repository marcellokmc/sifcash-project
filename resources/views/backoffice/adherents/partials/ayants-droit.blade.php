@if($adherent->ayantsDroit->isEmpty())
    <div class="text-center py-4">
        <i class="fas fa-users fa-3x text-muted mb-3"></i>
        <p class="text-muted">Aucun ayant droit enregistré</p>
    </div>
@else
    <div class="row">
        @foreach($adherent->ayantsDroit as $ayant)
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
                        @if($ayant->validateur)
                        <tr>
                            <th>Validé par:</th>
                            <td>{{ $ayant->validateur->name }} le {{ $ayant->updated_at->format('d/m/Y') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="card-footer">
                    @if($ayant->isEnAttente())
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
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif