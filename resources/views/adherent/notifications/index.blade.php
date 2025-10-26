@extends('layouts.adherent-modern')

@section('title', 'Toutes mes activités')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">
            <i class="fas fa-list-ul me-2 text-primary"></i>Toutes mes Activités
        </h1>
        <p class="text-muted mb-0">Historique complet de vos actions sur la plateforme</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" id="toggleFilters">
            <i class="fas fa-filter me-1"></i>Filtres
        </button>
        <a href="{{ route('adherent.dashboard') }}" class="btn btn-primary">
            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
        </a>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4" id="filtersCard" style="display: none;">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Rechercher</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Rechercher dans les activités...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Type d'activité</label>
                <select class="form-select" id="typeFilter">
                    <option value="">Toutes</option>
                    <option value="paiement">Paiements</option>
                    <option value="credit">Crédits</option>
                    <option value="retrait">Retraits</option>
                    <option value="document">Documents</option>
                    <option value="ayant_droit">Ayants Droit</option>
                    <option value="adhesion">Adhésions</option>
                    <option value="notification">Notifications</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Période</label>
                <select class="form-select" id="dateFilter">
                    <option value="">Toutes</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                    <option value="year">Cette année</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-outline-danger w-100" id="clearFilters">
                    <i class="fas fa-times me-1"></i>Effacer
                </button>
            </div>
        </div>
    </div>
</div>

@php
    // Collecte de toutes les activités de l'adhérent (version complète)
    $activites = collect();
    $adherent = auth()->user()->adherent;
    
    if($adherent) {
        // Paiements
        $adherent->paiements()->latest()->get()->each(function($paiement) use ($activites) {
            $activites->push([
                'id' => 'paiement_' . $paiement->id,
                'type' => 'paiement',
                'action' => 'Versement effectué',
                'description' => 'Montant: ' . number_format($paiement->montant, 0, ',', ' ') . ' FCFA',
                'details' => 'Mode: ' . $paiement->mode_paiement . ' | Réf: ' . ($paiement->reference_paiement ?? 'N/A'),
                'icon' => 'fas fa-plus-circle',
                'color' => $paiement->statut === 'validé' ? 'success' : ($paiement->statut === 'rejeté' ? 'danger' : 'warning'),
                'statut' => $paiement->statut,
                'date' => $paiement->created_at,
                'searchable' => $paiement->montant . ' ' . $paiement->mode_paiement . ' versement paiement'
            ]);
        });
        
        // Crédits
        $adherent->credits()->latest()->get()->each(function($credit) use ($activites) {
            $status_text = [
                'en_attente' => 'Demande de crédit soumise',
                'approuvé' => 'Crédit approuvé',
                'rejeté' => 'Demande de crédit rejetée'
            ];
            
            $activites->push([
                'id' => 'credit_' . $credit->id,
                'type' => 'credit',
                'action' => $status_text[$credit->statut] ?? 'Crédit mis à jour',
                'description' => 'Montant: ' . number_format($credit->montant_demande, 0, ',', ' ') . ' FCFA',
                'details' => 'Durée: ' . $credit->duree_mois . ' mois | Taux: ' . $credit->taux_interet . '%',
                'icon' => 'fas fa-hand-holding-usd',
                'color' => $credit->statut === 'approuvé' ? 'success' : ($credit->statut === 'rejeté' ? 'danger' : 'warning'),
                'statut' => $credit->statut,
                'date' => $credit->updated_at,
                'searchable' => $credit->montant_demande . ' credit pret emprunt financement'
            ]);
        });
        
        // Retraits
        $adherent->demandeRetraits()->latest()->get()->each(function($retrait) use ($activites) {
            $status_text = [
                'en_attente' => 'Demande de retrait soumise',
                'approuvé' => 'Retrait approuvé',
                'rejeté' => 'Retrait rejeté'
            ];
            
            $activites->push([
                'id' => 'retrait_' . $retrait->id,
                'type' => 'retrait',
                'action' => $status_text[$retrait->statut] ?? 'Retrait mis à jour',
                'description' => 'Montant: ' . number_format($retrait->montant_demande, 0, ',', ' ') . ' FCFA',
                'details' => 'Type: ' . $retrait->type_retrait . ' | Mode: ' . $retrait->mode_retrait,
                'icon' => 'fas fa-money-bill-wave',
                'color' => $retrait->statut === 'approuvé' ? 'info' : ($retrait->statut === 'rejeté' ? 'danger' : 'warning'),
                'statut' => $retrait->statut,
                'date' => $retrait->updated_at,
                'searchable' => $retrait->montant_demande . ' retrait sortie encaissement'
            ]);
        });
        
        // Documents
        $adherent->documents()->with('typeDocument')->latest()->get()->each(function($document) use ($activites) {
            $status_text = [
                'soumis' => 'Document soumis',
                'validé' => 'Document validé',
                'rejeté' => 'Document rejeté'
            ];
            
            $activites->push([
                'id' => 'document_' . $document->id,
                'type' => 'document',
                'action' => $status_text[$document->statut] ?? 'Document mis à jour',
                'description' => $document->typeDocument->nom ?? 'Document',
                'details' => 'Fichier: ' . ($document->chemin_fichier ? basename($document->chemin_fichier) : 'N/A'),
                'icon' => 'fas fa-file-alt',
                'color' => $document->statut === 'validé' ? 'success' : ($document->statut === 'rejeté' ? 'danger' : 'info'),
                'statut' => $document->statut,
                'date' => $document->updated_at,
                'searchable' => ($document->typeDocument->nom ?? '') . ' document fichier justificatif'
            ]);
        });
        
        // Ayants droit
        $adherent->ayantsDroit()->latest()->get()->each(function($ayantDroit) use ($activites) {
            $status_text = [
                'en_attente' => 'Ayant droit ajouté',
                'validé' => 'Ayant droit validé',
                'rejeté' => 'Ayant droit rejeté'
            ];
            
            $activites->push([
                'id' => 'ayant_droit_' . $ayantDroit->id,
                'type' => 'ayant_droit',
                'action' => $status_text[$ayantDroit->statut_validation] ?? 'Ayant droit mis à jour',
                'description' => $ayantDroit->prenom . ' ' . $ayantDroit->nom,
                'details' => 'Lien: ' . $ayantDroit->lien_parente . ' | Bénéficiaire: ' . $ayantDroit->type_beneficiaire,
                'icon' => 'fas fa-user-plus',
                'color' => $ayantDroit->statut_validation === 'validé' ? 'success' : ($ayantDroit->statut_validation === 'rejeté' ? 'danger' : 'warning'),
                'statut' => $ayantDroit->statut_validation,
                'date' => $ayantDroit->updated_at,
                'searchable' => $ayantDroit->prenom . ' ' . $ayantDroit->nom . ' ' . $ayantDroit->lien_parente . ' beneficiaire ayant droit'
            ]);
        });
        
        // Adhésions
        $adherent->adhesions()->with('plan')->latest()->get()->each(function($adhesion) use ($activites) {
            $activites->push([
                'id' => 'adhesion_' . $adhesion->id,
                'type' => 'adhesion',
                'action' => 'Nouvelle adhésion',
                'description' => $adhesion->plan->nom ?? 'Plan d’épargne',
                'details' => 'Montant: ' . number_format($adhesion->montant_souscrit, 0, ',', ' ') . ' FCFA | N°: ' . $adhesion->numero_adhesion,
                'icon' => 'fas fa-handshake',
                'color' => $adhesion->statut === 'actif' ? 'primary' : ($adhesion->statut === 'suspendu' ? 'warning' : 'secondary'),
                'statut' => $adhesion->statut,
                'date' => $adhesion->created_at,
                'searchable' => ($adhesion->plan->nom ?? '') . ' ' . $adhesion->numero_adhesion . ' adhesion plan epargne souscription'
            ]);
        });
        
        // Notifications réelles (actions admin/agent)
        \DB::table('notifications')->where('user_id', auth()->id())->latest('created_at')->get()->each(function($notification) use ($activites) {
            $typeColors = [
                'info' => 'info',
                'success' => 'success', 
                'warning' => 'warning',
                'error' => 'danger',
                'alert' => 'danger'
            ];
            
            $activites->push([
                'id' => 'notification_' . $notification->id,
                'type' => 'notification',
                'action' => $notification->titre,
                'description' => \Str::limit($notification->message, 80),
                'details' => 'Type: ' . ucfirst($notification->type) . ' | ' . ($notification->lu ? 'Lue' : 'Non lue'),
                'icon' => 'fas fa-bell',
                'color' => $typeColors[$notification->type] ?? 'secondary',
                'statut' => $notification->lu ? 'lue' : 'non_lue',
                'date' => \Carbon\Carbon::parse($notification->created_at),
                'searchable' => strtolower($notification->titre . ' ' . $notification->message . ' notification admin agent'),
                'notification_id' => $notification->id,
                'message_complet' => $notification->message,
                'lu' => $notification->lu
            ]);
        });
    }
    
    // Trier par date décroissante
    $toutesActivites = $activites->sortByDesc('date');
@endphp

<!-- Liste des activités -->
<div class="row" id="activitiesList">
    @if($toutesActivites->count() > 0)
        @foreach($toutesActivites as $activite)
            <div class="col-12 mb-3 activity-item" 
                 data-type="{{ $activite['type'] }}" 
                 data-date="{{ $activite['date']->format('Y-m-d') }}"
                 data-search="{{ strtolower($activite['searchable']) }}">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-{{ $activite['color'] }} rounded-circle p-3 me-3 flex-shrink-0" style="width: 50px; height: 50px;">
                                <i class="{{ $activite['icon'] }} text-white"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0 fw-bold text-truncate me-3">{{ $activite['action'] }}</h6>
                                    <div class="text-end flex-shrink-0">
                                        <span class="badge bg-{{ $activite['color'] }} bg-opacity-25 text-{{ $activite['color'] }} mb-1">
                                            {{ ucfirst($activite['type']) }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $activite['date']->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                                <p class="mb-1 text-dark">{{ $activite['description'] }}</p>
                                <small class="text-muted">{{ $activite['details'] }}</small>
                                
                                @if($activite['type'] === 'notification')
                                    <!-- Affichage spécial pour notifications -->
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <h6 class="mb-2 fw-bold text-primary">
                                            <i class="fas fa-envelope-open me-2"></i>Message complet :
                                        </h6>
                                        <p class="mb-2">{{ $activite['message_complet'] ?? $activite['description'] }}</p>
                                        @if(!$activite['lu'])
                                            <form method="POST" action="{{ route('adherent.notifications.mark-read', $activite['notification_id']) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i>Marquer comme lue
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Déjà lue
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="mt-2">
                                    <span class="badge bg-{{ $activite['color'] }}">{{ ucfirst($activite['statut']) }}</span>
                                    <small class="text-muted ms-2">
                                        <i class="fas fa-history me-1"></i>
                                        {{ $activite['date']->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-history fa-4x text-muted mb-4"></i>
                <h4 class="text-muted mb-3">Aucune activité pour le moment</h4>
                <p class="text-muted mb-4">Vos actions (paiements, crédits, retraits, etc.) apparaîtront ici</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('adherent.paiements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Premier versement
                    </a>
                    <a href="{{ route('adherent.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Retour au dashboard
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@if($toutesActivites->count() > 0)
    <!-- Statistiques en bas -->
    <!-- Statistiques détaillées -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-chart-bar me-2"></i>Résumé des activités
                    </h6>
                    <div class="row text-center">
                        <div class="col-md-4 col-6 mb-3">
                            <div class="fw-bold text-success fs-4">{{ $toutesActivites->where('type', 'paiement')->count() }}</div>
                            <small class="text-muted">Paiements</small>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="fw-bold text-warning fs-4">{{ $toutesActivites->where('type', 'credit')->count() }}</div>
                            <small class="text-muted">Crédits</small>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="fw-bold text-info fs-4">{{ $toutesActivites->where('type', 'retrait')->count() }}</div>
                            <small class="text-muted">Retraits</small>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="fw-bold text-secondary fs-4">{{ $toutesActivites->where('type', 'document')->count() }}</div>
                            <small class="text-muted">Documents</small>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="fw-bold" style="color: #a855f7; font-size: 1.25rem;">{{ $toutesActivites->where('type', 'ayant_droit')->count() }}</div>
                            <small class="text-muted">Ayants Droit</small>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="fw-bold text-primary fs-4">{{ $toutesActivites->where('type', 'adhesion')->count() }}</div>
                            <small class="text-muted">Adhésions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-bell fa-3x mb-3"></i>
                    <h3 class="fw-bold">{{ $toutesActivites->where('type', 'notification')->count() }}</h3>
                    <p class="mb-2">Notifications reçues</p>
                    @php
                        $notificationsNonLues = $toutesActivites->where('type', 'notification')->where('lu', false)->count();
                    @endphp
                    @if($notificationsNonLues > 0)
                        <span class="badge bg-danger">
                            {{ $notificationsNonLues }} non lue{{ $notificationsNonLues > 1 ? 's' : '' }}
                        </span>
                    @else
                        <span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>Toutes lues
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<!-- JavaScript pour les filtres -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleFiltersBtn = document.getElementById('toggleFilters');
    const filtersCard = document.getElementById('filtersCard');
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const dateFilter = document.getElementById('dateFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const activitiesList = document.getElementById('activitiesList');
    const allItems = document.querySelectorAll('.activity-item');
    
    // Toggle filters
    toggleFiltersBtn.addEventListener('click', function() {
        if (filtersCard.style.display === 'none') {
            filtersCard.style.display = 'block';
            toggleFiltersBtn.innerHTML = '<i class="fas fa-times me-1"></i>Masquer';
        } else {
            filtersCard.style.display = 'none';
            toggleFiltersBtn.innerHTML = '<i class="fas fa-filter me-1"></i>Filtres';
        }
    });
    
    // Filter function
    function filterActivities() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = typeFilter.value;
        const selectedDate = dateFilter.value;
        
        const today = new Date();
        const week = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
        const month = new Date(today.getFullYear(), today.getMonth(), 1);
        const year = new Date(today.getFullYear(), 0, 1);
        
        allItems.forEach(item => {
            let show = true;
            
            // Search filter
            if (searchTerm && !item.dataset.search.includes(searchTerm)) {
                show = false;
            }
            
            // Type filter
            if (selectedType && item.dataset.type !== selectedType) {
                show = false;
            }
            
            // Date filter
            if (selectedDate) {
                const itemDate = new Date(item.dataset.date);
                switch(selectedDate) {
                    case 'today':
                        if (itemDate.toDateString() !== today.toDateString()) show = false;
                        break;
                    case 'week':
                        if (itemDate < week) show = false;
                        break;
                    case 'month':
                        if (itemDate < month) show = false;
                        break;
                    case 'year':
                        if (itemDate < year) show = false;
                        break;
                }
            }
            
            item.style.display = show ? 'block' : 'none';
        });
    }
    
    // Event listeners
    searchInput.addEventListener('input', filterActivities);
    typeFilter.addEventListener('change', filterActivities);
    dateFilter.addEventListener('change', filterActivities);
    
    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        typeFilter.value = '';
        dateFilter.value = '';
        allItems.forEach(item => item.style.display = 'block');
    });
});
</script>
@endsection
