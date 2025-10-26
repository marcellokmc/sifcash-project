@extends('backoffice.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des pénalités de retrait anticipé</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pourcentage capital requis</th>
                            <th>Durée préavis (jours)</th>
                            <th>Taux de pénalité</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penalites as $penalite)
                            <tr>
                                <td>{{ $penalite->pourcentage_capital_requis }}%</td>
                                <td>{{ $penalite->duree_preavis_jours }} jours</td>
                                <td>{{ $penalite->taux_penalite }}%</td>
                                <td>
                                    @if($penalite->actif)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.penalites.edit', $penalite) }}" 
                                       class="btn btn-sm btn-primary"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($penalite->actif)
                                        <form action="{{ route('admin.penalites.deactivate', $penalite) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir désactiver cette pénalité ?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning" title="Désactiver">
                                                <i class="fas fa-toggle-off"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.penalites.activate', $penalite) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir activer cette pénalité ?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Activer">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucune pénalité de retrait anticipé trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $penalites->links() }}
            </div>
        </div>
    </div>
</div>
@endsection