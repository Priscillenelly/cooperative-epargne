@extends('layouts.app')

@section('title', 'Mon espace')

@section('content')
@if(!$membre)
    <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
        Votre profil membre n'est pas encore créé. Contactez l'administrateur.
    </div>
@else

{{-- Bannière --}}
<div class="rounded-lg p-5 mb-4 text-white flex items-center justify-between" style="background-color:#2563eb">
    <div>
        <h2 class="text-lg font-bold">Bonjour, {{ $membre->prenom }} {{ $membre->nom }} !</h2>
        <p class="text-sm text-blue-100">Votre situation au {{ now()->format('d F Y') }}.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-blue-600 bg-white text-sm">
            {{ strtoupper(substr($membre->prenom,0,1).substr($membre->nom,0,1)) }}
        </div>
        <div>
            <p class="text-sm font-medium">{{ $membre->prenom }} {{ $membre->nom }}</p>
            <p class="text-xs text-blue-200">Membre actif</p>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-lg p-4 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500">Mon epargne</p>
        <p class="text-xl font-bold text-gray-800">{{ number_format($membre->solde_epargne, 0, ',', ' ') }} F</p>
        <p class="text-xs text-green-500">+0 F ce mois</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-orange-400">
        <p class="text-xs text-gray-500">Pret en cours</p>
        @php $pretActif = $membre->prets->where('statut','approuve')->first(); @endphp
        <p class="text-xl font-bold text-gray-800">{{ $membre->prets->whereIn('statut',['approuve','en_attente'])->count() }}</p>
        <p class="text-xs text-gray-400">{{ $pretActif ? number_format($pretActif->capital_restant,0,',',' ').' F restants' : 'Aucun pret actif' }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Mois de cotisation</p>
        <p class="text-xl font-bold text-gray-800">{{ $membre->cotisations->count() }}</p>
        <p class="text-xs text-gray-400">Depuis {{ $membre->date_adhesion->format('M Y') }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-red-400">
        <p class="text-xs text-gray-500">Cotisations en retard</p>
        <p class="text-xl font-bold text-gray-800">{{ $membre->cotisations->where('statut','en_retard')->count() }}</p>
        <p class="text-xs text-green-500">{{ $membre->cotisations->where('statut','en_retard')->count() == 0 ? 'Tout est a jour !' : 'A regulariser' }}</p>
    </div>
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    {{-- Cotisations --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="font-semibold text-gray-800 mb-3">Mes dernieres cotisations</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 text-xs border-b">
                    <th class="text-left py-2">Mois</th>
                    <th class="text-left py-2">Montant</th>
                    <th class="text-left py-2">Statut</th>
                    <th class="text-left py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($membre->cotisations->sortByDesc('created_at')->take(5) as $cotisation)
                <tr class="border-b text-gray-700">
                    <td class="py-2">{{ ['Jan','Fev','Mar','Avr','Mai','Jun','Jul','Aou','Sep','Oct','Nov','Dec'][$cotisation->mois-1] }} {{ $cotisation->annee }}</td>
                    <td class="py-2">{{ number_format($cotisation->montant,0,',',' ') }} FCFA</td>
                    <td class="py-2">
                        <span class="inline-block w-10 h-4 rounded" style="background-color:{{ $cotisation->statut === 'payee' ? '#22c55e' : '#ef4444' }}"></span>
                    </td>
                    <td class="py-2">{{ $cotisation->date_paiement ? $cotisation->date_paiement->format('d/m/Y') : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4 text-gray-400 text-center">Aucune cotisation.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Prêt en cours --}}
    <div>
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <h3 class="font-semibold text-gray-800 mb-3">Mon pret en cours</h3>
            @if($pretActif)
                <div class="rounded-lg p-4 text-white text-sm mb-3" style="background-color:#2563eb">
                    <div class="grid grid-cols-2 gap-2">
                        <div><p class="text-blue-200 text-xs">Montant accorde</p><p class="font-bold">{{ number_format($pretActif->montant_accorde,0,',',' ') }} FCFA</p></div>
                        <div><p class="text-blue-200 text-xs">Taux</p><p class="font-bold">{{ $pretActif->taux_interet }}%</p></div>
                        <div><p class="text-blue-200 text-xs">Duree</p><p class="font-bold">{{ $pretActif->duree_mois }} mois</p></div>
                        <div><p class="text-blue-200 text-xs">Approbation</p><p class="font-bold">{{ $pretActif->updated_at->format('d/m/Y') }}</p></div>
                    </div>
                </div>
                <h4 class="font-medium text-gray-700 mb-2 text-sm">Progression du remboursement</h4>
                @php
                    $progression = $pretActif->montant_total > 0
                        ? round((($pretActif->montant_total - $pretActif->capital_restant) / $pretActif->montant_total) * 100)
                        : 0;
                @endphp
                <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                    <div class="h-4 rounded-full" style="width:{{ $progression }}%; background-color:#2563eb"></div>
                </div>
                <p class="text-xs text-gray-500">{{ $progression }}% rembourse</p>
                <p class="text-xs text-gray-500 mt-1">
                    Montant rembourse : {{ number_format($pretActif->montant_total - $pretActif->capital_restant, 0,',',' ') }} FCFA
                    • Reste : {{ number_format($pretActif->capital_restant,0,',',' ') }} FCFA
                </p>
            @else
                <p class="text-gray-400 text-sm">Aucun pret en cours.</p>
            @endif
        </div>

        {{-- Bouton demande prêt --}}
        <div class="rounded-lg p-4 text-white flex items-center justify-between" style="background-color:#22c55e">
            <p class="text-sm">Votre historique vous permet de demander un nouveau pret. Plafond : {{ number_format($membre->solde_epargne * 3, 0, ',', ' ') }} FCFA</p>
            <a href="{{ route('prets.create') }}" class="bg-white text-green-700 font-bold text-sm px-4 py-2 rounded ml-3 whitespace-nowrap">Demander</a>
        </div>
    </div>
</div>

{{-- Notifications --}}
<div class="bg-white rounded-lg shadow p-4">
    <h3 class="font-semibold text-gray-800 mb-3">Notifications recentes</h3>
    @forelse($membre->transactions->sortByDesc('created_at')->take(5) as $transaction)
    <div class="flex items-center gap-3 py-2 border-b text-sm">
        <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color:{{ $transaction->type === 'remboursement' ? '#22c55e' : ($transaction->type === 'cotisation' ? '#22c55e' : '#f97316') }}"></span>
        <span class="text-gray-400 text-xs">{{ $transaction->date_transaction->format('d/m/Y') }}</span>
        <span class="text-gray-700">{{ $transaction->description }}</span>
    </div>
    @empty
    <p class="text-gray-400 text-sm">Aucune notification.</p>
    @endforelse
</div>

@endif
@endsection
