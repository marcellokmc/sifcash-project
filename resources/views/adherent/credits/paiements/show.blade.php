@extends('layouts.adherent-modern')

@section('title', 'Détail du Paiement de Crédit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('adherent.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('adherent.credits.index') }}">Mes Crédits</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('adherent.credits.paiements.index') }}">Paiements</a></li>
                        <li class="breadcrumb-item active">Détail du Paiement</li>
                    </ol>
                </div>
                <h4 class="page-title">Détail du Paiement de Crédit</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Informations du Paiement</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            @switch($paiement->statut)
                                @case('valide')
                                    <span class="badge bg-success fs-6">Validé</span>
                                    @break
                                @case('en_attente')
                                    <span class="badge bg-warning fs-6">En attente</span>
                                    @break
                                @case('rejete')
                                    <span class="badge bg-danger fs-6">Rejeté</span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark fs-6">{{ ucfirst($paiement->statut) }}</span>
                            @endswitch
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Montant Payé</label>
                                <p class="text-success fs-5">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pénalité</label>
                                @if($paiement->penalite > 0)
                                <p class="text-danger fs-5">{{ number_format($paiement->penalite, 0, ',', ' ') }} FCFA</p>
                                @else
                                <p class="text-muted">Aucune pénalité</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Date de Paiement</label>
                                <p>{{ $paiement->date_paiement->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mode de Paiement</label>
                                <p>{{ ucfirst($paiement->mode ?? 'N/A') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($paiement->reference)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Référence</label>
                                <p class="font-monospace">{{ $paiement->reference }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($paiement->motif_rejet)
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <h6><i class="mdi mdi-alert-circle"></i> Motif du Rejet</h6>
                                <p class="mb-0">{{ $paiement->motif_rejet }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($paiement->echeance)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Échéance Associée</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Date d'Échéance</label>
                                <p>{{ $paiement->echeance->date_echeance->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Date d'Échéance</label>
                                <p>{{ $paiement->echeance->date_echeance->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Montant Attendu</label>
                                <p>{{ number_format($paiement->echeance->montant_attendu, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Montant Payé</label>
                                <p>{{ number_format($paiement->echeance->montant_paye, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pénalité Appliquée</label>
                                <p>{{ number_format($paiement->echeance->penalite_appliquee, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Statut</label>
                                <p>
                                    @switch($paiement->echeance->statut)
                                        @case('payé')
                                            <span class="badge bg-success">Payé</span>
                                            @break
                                        @case('en_retard')
                                            <span class="badge bg-danger">En retard</span>
                                            @break
                                        @case('à_venir')
                                            <span class="badge bg-warning">À venir</span>
                                            @break
                                        @default
                                            <span class="badge bg-light text-dark">{{ ucfirst($paiement->echeance->statut) }}</span>
                                    @endswitch
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($paiement->preuves && $paiement->preuves->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Preuves de Paiement</h5>
                    <div class="row">
                        @foreach($paiement->preuves as $preuve)
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 text-center">
                                @if($preuve->type === 'pdf')
                                    <i class="mdi mdi-file-pdf-box fs-1 text-danger"></i>
                                    <p class="mt-2">{{ $preuve->original_name }}</p>
                                    <a href="{{ route('adherent.credits.paiements.download-preuve', $preuve) }}" class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-download"></i> Télécharger
                                    </a>
                                @else
                                    <img src="{{ route('adherent.credits.paiements.download-preuve', $preuve) }}"
                                         alt="{{ $preuve->original_name }}"
                                         class="img-fluid rounded"
                                         style="max-height: 150px;">
                                    <p class="mt-2">{{ $preuve->original_name }}</p>
                                    <a href="{{ route('adherent.credits.paiements.download-preuve', $preuve) }}" class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-download"></i> Télécharger
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations du Crédit</h5>
                    <div class="border rounded p-3">
                        <h6 class="text-primary">Crédit #{{ $paiement->credit->id }}</h6>
                        <p class="mb-1"><strong>Montant accordé:</strong> {{ number_format($paiement->credit->montant_accorde, 0, ',', ' ') }} FCFA</p>
                        <p class="mb-1"><strong>Durée:</strong> {{ $paiement->credit->duree }} mois</p>
                        <p class="mb-1"><strong>Taux:</strong> {{ $paiement->credit->taux }}%</p>
                        <p class="mb-0"><strong>Statut:</strong>
                            <span class="badge bg-{{ $paiement->credit->etat === 'actif' ? 'success' : 'warning' }}">
                                {{ ucfirst($paiement->credit->etat) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('adherent.credits.paiements.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left"></i> Retour à la liste
                        </a>

                        <a href="{{ route('adherent.credits.show', $paiement->credit) }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-credit-card"></i> Voir le crédit
                        </a>
                    </div>
                </div>
            </div>

            @if($paiement->statut === 'valide')
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success">
                        <h6><i class="mdi mdi-check-circle"></i> Paiement validé</h6>
                        <p class="mb-0">Ce paiement a été validé et enregistré dans le système.</p>
                    </div>
                </div>
            </div>
            @endif

            @if($paiement->statut === 'en_attente')
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="mdi mdi-clock"></i> En attente de validation</h6>
                        <p class="mb-0">Votre paiement est en cours de validation par nos équipes.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
