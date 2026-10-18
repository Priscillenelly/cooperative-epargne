@extends('layouts.app')

@section('title', 'Fiche — ' . $membre->prenom . ' ' . $membre->nom)

@section('content')

{{-- Header membre --}}
<div class="bg-white rounded-lg shadow p-5 mb-4 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0"
            style="background-color:#2563eb">
            {{ strtoupper(substr($membre->prenom,0,1).substr($membre->nom,0,1)) }}
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $membre->prenom }} {{ $membre->nom }}</h2>
            <p class="text-sm text-gray-500">{{ $membre->user->email ?? '—' }} • {{ $membre->telephone ?? '—' }} • Membre depuis {{ $membre->date_adhesion->format('d/m/Y') }}</p>
            <span class="inline-block mt-1 px-3 py-1 rounded text-xs text-white" style="background-color:#22c55e">Actif</span>
        </div>
    </div>
    @if(auth()->user()->isAdmin())
    <div class="flex gap-2">
        <a href="{{ route('membres.edit', $membre) }}"
            class="px-4 py-2 rounded text-white text-sm font-medium" style="background-color:#f97316">Modifier</a>
        <form method="POST" action="{{ route('membres.destroy', $membre) }}" onsubmit="return confirm('Supprimer ?')">
            @csrf @method('DELETE')
            <button class="px-4 py-2 rounded text-white text-sm font-medium" style="background-color:#ef4444">Supprimer</button>
        </form>
    </div>
    @endif
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-0 mb-4 bg-white rounded-lg shadow overflow-hidden">
    <div class="p-4 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500">Epargne totale</p>
        <p class="text-xl font-bold text-gray-800">{{ number_format($membre->solde_epargne, 0, ',', ' ') }} FCFA</p>
    </div>
    <div class="p-4 border-l-4 border-orange-400">
        <p class="text-xs text-gray-500">Prets en cours</p>
        <p class="text-xl font-bold text-gray-800">{{ $membre->prets->whereIn('statut',['approuve','en_attente'])->count() }}</p>
    </div>
    <div class="p-4 border-l-4 border-red-400">
        <p class="text-xs text-gray-500">Retards</p>
        <p class="text-xl font-bold text-gray-800">{{ $membre->cotisations->where('statut','en_retard')->count() }}</p>
    </div>
    <div class="p-4 border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Anciennete</p>
        @php
            $mois = $membre->date_adhesion->diffInMonths(now());
        @endphp
        <p class="text-xl font-bold text-gray-800">{{ $mois }} mois</p>
    </div>
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    {{-- Infos personnelles --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="font-semibold text-gray-800 mb-3">Informations personnelles</h3>
        <table class="w-full text-sm">
            <tr class="border-b"><td class="py-2 text-gray-500 w-32">Email</td><td class="py-2 font-semibold">{{ $membre->user->email ?? '—' }}</td></tr>
            <tr class="border-b"><td class="py-2 text-gray-500">Telephone</td><td class="py-2 font-semibold">{{ $membre->telephone ?? '—' }}</td></tr>
            <tr class="border-b"><td class="py-2 text-gray-500">Date naissance</td><td class="py-2 font-semibold">{{ $membre->date_naissance ? $membre->date_naissance->format('d/m/Y') : '—' }}</td></tr>
            <tr class="border-b"><td class="py-2 text-gray-500">Adresse</td><td class="py-2 font-semibold">{{ $membre->adresse ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Date adhesion</td><td class="py-2 font-semibold">{{ $membre->date_adhesion->format('d/m/Y') }}</td></tr>
        </table>
    </div>

    {{-- Dernières cotisations --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="font-semibold text-gray-800 mb-3">Dernieres cotisations</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 text-xs border-b">
                    <th class="text-left py-2 font-bold">Mois</th>
                    <th class="text-left py-2 font-bold">Montant</th>
                    <th class="text-left py-2 font-bold">Statut</th>
                    <th class="text-left py-2 font-bold">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($membre->cotisations->sortByDesc('created_at')->take(5) as $cotisation)
                <tr class="border-b">
                    <td class="py-2">{{ ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'][$cotisation->mois-1] }} {{ $cotisation->annee }}</td>
                    <td class="py-2 font-semibold">{{ number_format($cotisation->montant, 0, ',', ' ') }} FCFA</td>
                    <td class="py-2">
                        <span class="inline-block w-10 h-4 rounded" style="background-color:{{ $cotisation->statut === 'payee' ? '#22c55e' : '#ef4444' }}"></span>
                    </td>
                    <td class="py-2 text-gray-500">{{ $cotisation->date_paiement ? $cotisation->date_paiement->format('d/m/Y') : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4 text-center text-gray-400">Aucune cotisation.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Prêts --}}
<div class="bg-white rounded-lg shadow p-4">
    <h3 class="font-semibold text-gray-800 mb-3">Prets en cours</h3>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-gray-500 text-xs border-b">
                <th class="text-left py-2 font-bold">Montant accorde</th>
                <th class="text-left py-2 font-bold">Taux</th>
                <th class="text-left py-2 font-bold">Duree</th>
                <th class="text-left py-2 font-bold">Reste a payer</th>
                <th class="text-left py-2 font-bold">Statut</th>
                <th class="text-left py-2 font-bold">Approbation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($membre->prets->whereIn('statut',['approuve','en_attente']) as $pret)
            <tr class="border-b">
                <td class="py-2 font-semibold">{{ $pret->montant_accorde ? number_format($pret->montant_accorde, 0, ',', ' ').' FCFA' : '—' }}</td>
                <td class="py-2">{{ $pret->taux_interet ? (int)$pret->taux_interet.'%' : '—' }}</td>
                <td class="py-2">{{ $pret->duree_mois ? $pret->duree_mois.' mois' : '—' }}</td>
                <td class="py-2">{{ $pret->capital_restant ? number_format($pret->capital_restant, 0, ',', ' ').' FCFA' : '—' }}</td>
                <td class="py-2">
                    <span class="inline-block w-10 h-4 rounded" style="background-color:{{ $pret->statut === 'approuve' ? '#22c55e' : '#f97316' }}"></span>
                </td>
                <td class="py-2 text-gray-500">{{ $pret->updated_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-4 text-center text-gray-400">Aucun pret.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
