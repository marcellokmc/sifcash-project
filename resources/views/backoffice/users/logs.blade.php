@extends('backoffice.layouts.app')

@section('title', 'Logs de Connexion')
@section('page-title', 'Logs de Connexion - ' . $user->name)

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Historique des Connexions</h6>
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Retour au profil
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date/Heure</th>
                        <th>Action</th>
                        <th>Adresse IP</th>
                        <th>User Agent</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>
                            <span class="badge bg-{{ $log->action === 'login' ? 'success' : ($log->action === 'logout' ? 'warning' : 'danger') }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td>
                            <code>{{ $log->ip_address }}</code>
                        </td>
                        <td>
                            <small title="{{ $log->user_agent }}">
                                {{ Str::limit($log->user_agent, 60) }}
                            </small>
                        </td>
                        <td>
                            @if($log->action === 'login')
                                <i class="fas fa-check-circle text-success" title="Connexion réussie"></i>
                            @elseif($log->action === 'logout')
                                <i class="fas fa-sign-out-alt text-warning" title="Déconnexion"></i>
                            @else
                                <i class="fas fa-times-circle text-danger" title="Échec de connexion"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucun log de connexion trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Affichage de {{ $logs->firstItem() }} à {{ $logs->lastItem() }} sur {{ $logs->total() }} résultats
            </div>
            <nav>
                {{ $logs->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statistiques</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Connexions réussies
                        <span class="badge bg-success rounded-pill">
                            {{ $logs->where('action', 'login')->count() }}
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Déconnexions
                        <span class="badge bg-warning rounded-pill">
                            {{ $logs->where('action', 'logout')->count() }}
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Échecs de connexion
                        <span class="badge bg-danger rounded-pill">
                            {{ $logs->where('action', 'failed_login')->count() }}
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Total des logs
                        <span class="badge bg-primary rounded-pill">
                            {{ $logs->total() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informations de Sécurité</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Conseils de sécurité :</strong>
                    <ul class="mb-0 mt-2">
                        <li>Vérifiez régulièrement les logs de connexion pour détecter toute activité suspecte</li>
                        <li>Les adresses IP inhabituelles peuvent indiquer un accès non autorisé</li>
                        <li>Encouragez l'utilisation de mots de passe forts et uniques</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection