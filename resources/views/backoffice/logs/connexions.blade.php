@extends('backoffice.layouts.app')

@section('title', '📊 Logs de Connexions - SIF Admin')
@section('page-title', '📊 Journaux de Connexion')

@section('styles')
<style>
    .logs-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
        background-size: 300% 300%;
        animation: gradient-shift 8s ease infinite;
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    
    .logs-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .modern-stat-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        position: relative;
        height: 100%;
    }
    
    .modern-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        transition: height 0.3s ease;
    }
    
    .modern-stat-card.success::before { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .modern-stat-card.danger::before { background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); }
    .modern-stat-card.info::before { background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); }
    .modern-stat-card.primary::before { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .modern-stat-card.warning::before { background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); }
    
    .modern-stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .modern-stat-card:hover::before {
        height: 12px;
    }
    
    .stat-icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }
    
    .stat-icon-circle.success { background: linear-gradient(135deg, rgba(17, 153, 142, 0.1) 0%, rgba(56, 239, 125, 0.1) 100%); }
    .stat-icon-circle.danger { background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(248, 113, 113, 0.1) 100%); }
    .stat-icon-circle.info { background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%); }
    .stat-icon-circle.primary { background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); }
    .stat-icon-circle.warning { background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(249, 115, 22, 0.1) 100%); }
    
    .modern-stat-card:hover .stat-icon-circle {
        transform: scale(1.1) rotate(10deg);
    }
    
    .logs-table-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .logs-table-header {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        padding: 2rem;
        color: white;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    
    .filter-card {
        background: rgba(255,255,255,0.95);
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
    }
    
    .modern-table {
        border: none;
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 1rem;
        font-weight: 600;
        text-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    
    .modern-table tbody tr {
        border: none;
        transition: all 0.3s ease;
    }
    
    .modern-table tbody tr:hover {
        background: rgba(102, 126, 234, 0.05);
        transform: scale(1.001);
    }
    
    .modern-table tbody td {
        border: none;
        padding: 1.2rem 1rem;
        vertical-align: middle;
    }
    
    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        color: white;
        margin-right: 0.75rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .action-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    
    .pagination-modern .page-link {
        border: none;
        border-radius: 10px;
        margin: 0 0.25rem;
        padding: 0.75rem 1rem;
        color: #667eea;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .pagination-modern .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .pagination-modern .page-link:hover {
        background: rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }
    
    /* Classes pour les badges de statut */
    .bg-success-light {
        background-color: rgba(17, 153, 142, 0.1) !important;
    }
    
    .bg-danger-light {
        background-color: rgba(239, 68, 68, 0.1) !important;
    }
    
    .bg-success-subtle {
        background-color: rgba(17, 153, 142, 0.1) !important;
        color: #11998e !important;
    }
    
    .bg-danger-subtle {
        background-color: rgba(239, 68, 68, 0.1) !important;
        color: #ef4444 !important;
    }
    
    .text-success {
        color: #11998e !important;
    }
    
    .text-danger {
        color: #ef4444 !important;
    }
    
    /* Animation pour les éléments qui apparaissent */
    .modern-table tbody tr {
        animation: fadeInUp 0.5s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Effet de survol sur les lignes du tableau */
    .modern-table tbody tr:hover .user-avatar {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    
    .modern-table tbody tr:hover .action-badge {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }
</style>
@endsection

@section('content')
<!-- Header moderne -->
<div class="logs-header mb-4">
    <div class="container-fluid position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-2" style="text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                    📈 Journaux de Connexion
                </h1>
                <p class="lead mb-0" style="opacity: 0.9;">
                    🔍 Monitoring et analyse des connexions utilisateurs - {{ now()->format('d/m/Y') }}
                </p>
            </div>
            <div class="col-lg-4 text-end">
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <div class="text-center">
                        <div class="h4 fw-bold mb-0">{{ $stats['today_logins'] ?? 0 }}</div>
                        <small style="opacity: 0.8;">📅 Aujourd'hui</small>
                    </div>
                    <div class="text-center">
                        <div class="h4 fw-bold mb-0">{{ $logs->total() }}</div>
                        <small style="opacity: 0.8;">📊 Total Logs</small>
                    </div>
                    <button class="btn btn-light btn-lg rounded-pill px-4" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt me-2"></i>Actualiser
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques modernes -->
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="modern-stat-card success">
            <div class="card-body p-4 text-center">
                <div class="stat-icon-circle success mx-auto">
                    <i class="fas fa-sign-in-alt" style="font-size: 1.8rem; color: #11998e;"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: #11998e;">{{ number_format($stats['successful_logins'] ?? 0) }}</h2>
                <h6 class="text-muted mb-2">✅ Connexions Réussies</h6>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" style="width: 85%; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="modern-stat-card danger">
            <div class="card-body p-4 text-center">
                <div class="stat-icon-circle danger mx-auto">
                    <i class="fas fa-exclamation-triangle" style="font-size: 1.8rem; color: #ef4444;"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: #ef4444;">{{ number_format($stats['failed_logins'] ?? 0) }}</h2>
                <h6 class="text-muted mb-2">❌ Échecs de Connexion</h6>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" style="width: {{ min(($stats['failed_logins'] ?? 0) * 2, 100) }}%; background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="modern-stat-card info">
            <div class="card-body p-4 text-center">
                <div class="stat-icon-circle info mx-auto">
                    <i class="fas fa-sign-out-alt" style="font-size: 1.8rem; color: #06b6d4;"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: #06b6d4;">{{ number_format($stats['logouts'] ?? 0) }}</h2>
                <h6 class="text-muted mb-2">🚪 Déconnexions</h6>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" style="width: 70%; background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="modern-stat-card primary">
            <div class="card-body p-4 text-center">
                <div class="stat-icon-circle primary mx-auto">
                    <i class="fas fa-users" style="font-size: 1.8rem; color: #667eea;"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: #667eea;">{{ number_format($stats['active_users'] ?? 0) }}</h2>
                <h6 class="text-muted mb-2">👥 Utilisateurs Actifs (30j)</h6>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" style="width: 90%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Filtres Moderne -->
<div class="filter-card card mb-4">
    <div class="card-body p-4">
        <form method="GET" id="filterForm">
            <!-- Ligne de recherche principale -->
            <div class="row align-items-center mb-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0" style="color: #667eea;">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" id="searchInput" class="form-control border-0 shadow-sm" 
                               placeholder="🔍 Rechercher par utilisateur, email, IP ou navigateur..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <span class="text-muted">📋 {{ $logs->total() }} entrées</span>
                        <div class="vr mx-2"></div>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="toggleAdvancedFilters()">
                            <i class="fas fa-sliders-h me-1"></i>Filtres avancés
                        </button>
                        <a href="{{ route('admin.logs.connexions.export', request()->query()) }}" class="btn btn-sm btn-outline-info rounded-pill">
                            <i class="fas fa-download me-1"></i>Export CSV
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Filtres avancés (cachés par défaut) -->
            <div id="advancedFilters" class="row g-3" style="display: none;">
                <div class="col-md-3">
                    <label class="form-label text-muted small">Utilisateur</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">Tous les utilisateurs</option>
                        @if(isset($users))
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label text-muted small">Action</label>
                    <select name="action" class="form-select form-select-sm">
                        <option value="">Toutes les actions</option>
                        <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>🔓 Connexion</option>
                        <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>🚪 Déconnexion</option>
                        <option value="failed_login" {{ request('action') == 'failed_login' ? 'selected' : '' }}>❌ Échec</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label text-muted small">Date début</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label text-muted small">Date fin</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fas fa-filter me-1"></i>Appliquer
                    </button>
                    @if(request()->anyFilled(['search', 'user_id', 'action', 'date_from', 'date_to']))
                        <a href="{{ route('admin.logs.connexions') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="fas fa-times me-1"></i>Réinitialiser
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tableau moderne des logs -->
<div class="logs-table-card">
    <div class="logs-table-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="fw-bold mb-2">
                    <i class="fas fa-list-alt me-3"></i>📋 Journal des Connexions
                </h3>
                <p class="mb-0 opacity-75">
                    Suivi détaillé des activités de connexion utilisateurs
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="fas fa-clock me-1"></i>{{ now()->format('H:i') }}
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="fas fa-calendar me-1"></i>{{ now()->format('d/m') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="modern-table table mb-0">
                <thead>
                    <tr>
                        <th>
                            <i class="fas fa-user me-2"></i>Utilisateur
                        </th>
                        <th>
                            <i class="fas fa-cogs me-2"></i>Action
                        </th>
                        <th>
                            <i class="fas fa-globe me-2"></i>Adresse IP
                        </th>
                        <th>
                            <i class="fas fa-browser me-2"></i>Navigateur
                        </th>
                        <th>
                            <i class="fas fa-clock me-2"></i>Date/Heure
                        </th>
                        <th>
                            <i class="fas fa-check-circle me-2"></i>Statut
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @php
                                    $colors = ['#667eea', '#11998e', '#ef4444', '#06b6d4', '#f59e0b'];
                                    $userName = $log->user ? $log->user->name : 'Utilisateur supprimé';
                                    $userEmail = $log->user ? $log->user->email : 'Non disponible';
                                    $color = $colors[crc32($userName) % count($colors)];
                                @endphp
                                <div class="user-avatar" style="background: {{ $color }};">
                                    {{ strtoupper(substr($userName, 0, 2)) }}
                                </div>
                                <div>
                                    @if($log->user)
                                        <a href="{{ route('admin.users.show', $log->user->id) }}" class="fw-semibold text-dark text-decoration-none" style="transition: color 0.3s;" onmouseover="this.style.color='#667eea'" onmouseout="this.style.color='#333'">
                                            {{ $userName }}
                                        </a>
                                    @else
                                        <div class="fw-semibold text-muted">{{ $userName }}</div>
                                    @endif
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>{{ Str::limit($userEmail, 25) }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($log->action == 'login')
                                <span class="action-badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                                    <i class="fas fa-sign-in-alt me-1"></i>🔓 Connexion
                                </span>
                            @elseif($log->action == 'logout')
                                <span class="action-badge" style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); color: white;">
                                    <i class="fas fa-sign-out-alt me-1"></i>🚪 Déconnexion
                                </span>
                            @else
                                <span class="action-badge" style="background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); color: white;">
                                    <i class="fas fa-exclamation-triangle me-1"></i>❌ Échec
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <code class="bg-light px-2 py-1 rounded" style="font-size: 0.875rem;">
                                    {{ $log->ip_address }}
                                </code>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @php
                                    $agent = strtolower($log->user_agent ?? '');
                                    $browserIcon = 'fa-browser';
                                    if(str_contains($agent, 'chrome')) $browserIcon = 'fab fa-chrome';
                                    elseif(str_contains($agent, 'firefox')) $browserIcon = 'fab fa-firefox-browser';
                                    elseif(str_contains($agent, 'safari')) $browserIcon = 'fab fa-safari';
                                    elseif(str_contains($agent, 'edge')) $browserIcon = 'fab fa-edge';
                                @endphp
                                <i class="{{ $browserIcon }} text-muted me-2"></i>
                                <span class="text-muted" style="font-size: 0.875rem;">
                                    {{ Str::limit($log->user_agent ?? '🌐 Navigateur inconnu', 35) }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="text-center">
                                <div class="fw-semibold text-dark mb-1">
                                    <i class="fas fa-calendar-alt text-primary me-1"></i>
                                    {{ $log->created_at->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $log->created_at->format('H:i:s') }}
                                    <span class="badge bg-light text-dark ms-1" style="font-size: 0.7rem;">
                                        {{ $log->created_at->diffForHumans() }}
                                    </span>
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="text-center">
                                @if($log->success ?? ($log->action == 'login'))
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="bg-success-light rounded-circle p-2 me-2">
                                            <i class="fas fa-check-circle text-success"></i>
                                        </div>
                                        <span class="badge bg-success-subtle text-success">✅ Réussi</span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="bg-danger-light rounded-circle p-2 me-2">
                                            <i class="fas fa-times-circle text-danger"></i>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger">❌ Échec</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-inbox" style="font-size: 4rem; color: #e9ecef;"></i>
                                </div>
                                <h5 class="text-muted mb-2">📭 Aucune connexion trouvée</h5>
                                <p class="text-muted mb-4">Il n'y a aucun log de connexion correspondant à vos critères.</p>
                                @if(request('search'))
                                    <a href="{{ url()->current() }}" class="btn btn-outline-primary rounded-pill px-4">
                                        <i class="fas fa-undo me-2"></i>Voir tous les logs
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination moderne -->
@if($logs->hasPages())
<div class="mt-4 p-4 bg-white rounded-3 shadow-sm">
    {{ $logs->links('custom-pagination') }}
</div>
@endif
</div>
@endsection

@section('scripts')
<script>

// Animation d'apparition des cartes
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes statistiques
    const statCards = document.querySelectorAll('.modern-stat-card');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });
    
    // Animation du tableau
    setTimeout(() => {
        const tableCard = document.querySelector('.logs-table-card');
        if(tableCard) {
            tableCard.style.opacity = '0';
            tableCard.style.transform = 'translateY(50px)';
            tableCard.style.transition = 'all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            
            setTimeout(() => {
                tableCard.style.opacity = '1';
                tableCard.style.transform = 'translateY(0)';
            }, 100);
        }
    }, 600);
    
    // Effet parallaxe sur le header
    const header = document.querySelector('.logs-header');
    if(header) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.3;
            header.style.transform = `translateY(${rate}px)`;
        });
    }
    
    // Auto refresh des stats toutes les 30 secondes (optionnel)
    /*
    setInterval(() => {
        const refreshBtn = document.querySelector('[onclick="window.location.reload()"]');
        if(refreshBtn && !document.hidden) {
            // Mise à jour silencieuse des statistiques via AJAX
            console.log('Auto-refresh des stats');
        }
    }, 30000);
    */
});

// Fonction pour basculer les filtres avancés
function toggleAdvancedFilters() {
    const advancedFilters = document.getElementById('advancedFilters');
    const isVisible = advancedFilters.style.display !== 'none';
    
    if (isVisible) {
        advancedFilters.style.display = 'none';
    } else {
        advancedFilters.style.display = 'block';
        // Animation d'apparition
        advancedFilters.style.opacity = '0';
        advancedFilters.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            advancedFilters.style.transition = 'all 0.3s ease';
            advancedFilters.style.opacity = '1';
            advancedFilters.style.transform = 'translateY(0)';
        }, 10);
    }
    
    // Changer l'icône du bouton
    const button = event.target.closest('button');
    const icon = button.querySelector('i');
    icon.className = isVisible ? 'fas fa-sliders-h me-1' : 'fas fa-times me-1';
    button.innerHTML = isVisible ? '<i class="fas fa-sliders-h me-1"></i>Filtres avancés' : '<i class="fas fa-times me-1"></i>Masquer filtres';
}

// Recherche en temps réel (débounced)
let searchTimeout;
const searchInput = document.getElementById('searchInput');
if(searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchValue = this.value.trim();
        
        // Visual feedback
        const inputGroup = this.closest('.input-group');
        inputGroup.style.transform = 'scale(1.02)';
        inputGroup.style.boxShadow = '0 5px 20px rgba(102, 126, 234, 0.2)';
        
        setTimeout(() => {
            inputGroup.style.transform = 'scale(1)';
            inputGroup.style.boxShadow = 'none';
        }, 300);
        
        searchTimeout = setTimeout(() => {
            // Auto-submit après 800ms de pause
            if(searchValue.length > 2 || searchValue.length === 0) {
                document.getElementById('filterForm').submit();
            }
        }, 800);
    });
    
    // Feedback visuel lors du focus
    searchInput.addEventListener('focus', function() {
        this.closest('.input-group').style.transition = 'all 0.3s ease';
        this.closest('.input-group').style.borderColor = '#667eea';
        this.closest('.input-group').style.boxShadow = '0 0 0 0.2rem rgba(102, 126, 234, 0.25)';
    });
    
    searchInput.addEventListener('blur', function() {
        this.closest('.input-group').style.borderColor = '';
        this.closest('.input-group').style.boxShadow = '';
    });
}

// Auto-submit des select de filtrage
document.querySelectorAll('select[name="user_id"], select[name="action"], input[type="date"]').forEach(function(element) {
    element.addEventListener('change', function() {
        // Effet visuel
        this.style.transform = 'scale(1.05)';
        this.style.transition = 'transform 0.2s ease';
        
        setTimeout(() => {
            this.style.transform = 'scale(1)';
            document.getElementById('filterForm').submit();
        }, 200);
    });
});

// Affichage automatique des filtres avancés si des filtres sont actifs
document.addEventListener('DOMContentLoaded', function() {
    const hasActiveFilters = {{ request()->anyFilled(['user_id', 'action', 'date_from', 'date_to']) ? 'true' : 'false' }};
    if (hasActiveFilters) {
        document.getElementById('advancedFilters').style.display = 'block';
        const button = document.querySelector('button[onclick="toggleAdvancedFilters()"]');
        button.innerHTML = '<i class="fas fa-times me-1"></i>Masquer filtres';
    }
});
</script>
@endsection
