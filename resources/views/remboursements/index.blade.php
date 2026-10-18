@extends('layouts.app')

@section('title', 'Gestion des remboursements')

@section('content')

{{-- Barre recherche + bouton --}}
<div class="flex items-center justify-between mb-4">
    <input type="text" placeholder="Rechercher..."
        class="border border-gray-300 rounded px-3 py-2 text-sm w-64 focus:ring-2 focus:ring-blue-400">
    @if(auth()->user()->isAdmin())
    <a href="{{ route('remboursements.create', ['pret_id' => \App\Models\Pret::where('statut','approuve')->first()?->id]) }}"
        class="text-white px-4 py-2 rounded text-sm font-medium" style="background-color:#2563eb">+ Enregistrer</a>
    @endif
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-lg p-4 border-l-4 border-blue-500 shadow">
        <p class="text-xs text-gray-500">Prets actifs</p>
        <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Pret::where('statut','approuve')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-red-400 shadow">
        <p class="text-xs text-gray-500">Reste a payer</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format(\App\Models\Pret::where('statut','approuve')->sum('capital_restant'), 0, ',', ' ') }} F</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-green-500 shadow">
        <p class="text-xs text-gray-500">Rembourse/mois</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format(\App\Models\Remboursement::whereMonth('date_remboursement', now()->month)->sum('montant'), 0, ',', ' ') }} F</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-gray-400 shadow">
        <p class="text-xs text-gray-500">Prets soldes</p>
        <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Pret::where('statut','rembourse')->count() }}</p>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="border-b">
            <tr class="text-gray-700 text-xs">
                <th class="px-4 py-3 text-left font-bold">Membre</th>
                <th class="px-4 py-3 text-left font-bold">Pret</th>
                <th class="px-4 py-3 text-left font-bold">Mensualite</th>
                <th class="px-4 py-3 text-left font-bold">Date</th>
                <th class="px-4 py-3 text-left font-bold">Reste</th>
                <th class="px-4 py-3 text-left font-bold">Progression</th>
                <th class="px-4 py-3 text-left font-bold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($prets as $pret)
            @php
                $mensualite = $pret->duree_mois > 0 ? $pret->montant_total / $pret->duree_mois : 0;
                $dernierRemb = $pret->remboursements->sortByDesc('date_remboursement')->first();
                $progression = $pret->montant_total > 0
                    ? round((($pret->montant_total - $pret->capital_restant) / $pret->montant_total) * 100)
                    : 0;
                $progressionText = $progression == 0 ? '0% debut' : ($progression == 100 ? '100% solde' : $progression.'% complete');
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $pret->membre->prenom }} {{ $pret->membre->nom }}</td>
                <td class="px-4 py-3 text-gray-600">#PR-{{ str_pad($pret->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td class="px-4 py-3 text-gray-700">{{ number_format($mensualite, 0, ',', ' ') }} FCFA</td>
                <td class="px-4 py-3 text-gray-600">{{ $dernierRemb ? $dernierRemb->date_remboursement->format('d/m/Y') : '—' }}</td>
                <td class="px-4 py-3 text-gray-700">{{ number_format($pret->capital_restant, 0, ',', ' ') }} FCFA</td>
                <td class="px-4 py-3 text-gray-600">{{ $progressionText }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('remboursements.create', ['pret_id' => $pret->id]) }}"
                        class="inline-block px-3 py-1 rounded text-white text-xs font-medium" style="background-color:#22c55e">
                        Payer
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-400">Aucun remboursement.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 flex items-center justify-between border-t text-xs text-gray-500">
        <span>Affichage {{ $prets->firstItem() ?? 0 }}-{{ $prets->lastItem() ?? 0 }} de {{ $prets->total() }}</span>
        {{ $prets->links() }}
    </div>
</div>

@endsection
