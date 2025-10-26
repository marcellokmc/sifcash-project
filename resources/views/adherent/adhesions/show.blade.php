{{-- resources/views/adherent/adhesions/show.blade.php --}}
@extends('layouts.adherent-modern')

@section('content')
<div class="container mx-auto p-6">
    <a href="{{ route('adherent.adhesions.index') }}" class="text-blue-600 hover:underline">← Retour</a>

    <h1 class="text-2xl font-semibold mb-2">{{ $adhesion->plan->nom }}</h1>
    <div class="space-y-1">
        <p><strong>Montant souscrit:</strong> {{ number_format($adhesion->montant_souscrit, 0, ',', ' ') }} FCFA</p>
        <p><strong>Solde actuel:</strong> {{ number_format($adhesion->solde_actuel, 0, ',', ' ') }} FCFA</p>
        <p><strong>Statut:</strong> {{ ucfirst($adhesion->statut) }}</p>
        <p><strong>Date début:</strong> {{ optional($adhesion->date_debut)->format('d/m/Y') }}</p>
        <p><strong>Date fin:</strong> {{ optional($adhesion->date_fin)->format('d/m/Y') }}</p>
    </div>

    @if(isset($adhesion->renouvellements) && $adhesion->renouvellements->count())
    <div class="mt-6">
        <h2 class="text-xl font-semibold mb-2">Renouvellements</h2>
        <ul class="list-disc ml-6">
            @foreach($adhesion->renouvellements as $r)
                <li>{{ optional($r->date_renouvellement)->format('d/m/Y') }} - {{ number_format($r->montant_souscrit, 0, ',', ' ') }} FCFA</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
