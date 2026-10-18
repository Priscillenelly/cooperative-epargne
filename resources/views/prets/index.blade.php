@extends('layouts.app')

@section('title', 'Gestion des prets')

@section('content')

{{-- Filtres --}}
<div class="flex items-center gap-3 mb-4">
    <form method="GET" class="flex items-center gap-2 flex-1">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
            class="border border-gray-300 rounded px-3 py-2 text-sm w-48 focus:ring-2 focus:ring-blue-400">
        <a href="{{ route('prets.index') }}"
            class="px-4 py-2 rounded text-sm font-medium text-white {{ !request('statut') ? 'bg-blue-600' : 'bg-blue-400' }}">Tous</a>
        <a href="{{ route('prets.index', ['statut' => 'en_attente']) }}"
            class="px-4 py-2 rounded text-sm font-medium text-white {{ request('statut') == 'en_attente' ? 'bg-orange-600' : 'bg-orange-400' }}">En attente</a>
        <a href="{{ route('prets.index', ['statut' => 'approuve']) }}"
            class="px-4 py-2 rounded text-sm font-medium text-white {{ request('statut') == 'approuve' ? 'bg-green-600' : 'bg-green-400' }}">Approuves</a>
        <a href="{{ route('prets.index', ['statut' => 'rejete']) }}"
            class="px-4 py-2 rounded text-sm font-medium text-white {{ request('statut') == 'rejete' ? 'bg-red-600' : 'bg-red-400' }}">Rejetes</a>
        <a href="{{ route('prets.index', ['statut' => 'rembourse']) }}"
            class="px-4 py-2 rounded text-sm font-medium text-white {{ request('statut') == 'rembourse' ? 'bg-gray-600' : 'bg-gray-400' }}">Rembourses</a>
    </form>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-lg p-4 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500">Total prets</p>
        <p class="text-2xl font-bold text-gray-800">{{ $prets->total() }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-orange-400">
        <p class="text-xs text-gray-500">En attente</p>
        <p class="text-2xl font-bold text-gray-800">{{ App\Models\Pret::where('statut','en_attente')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Approuves</p>
        <p class="text-2xl font-bold text-gray-800">{{ App\Models\Pret::where('statut','approuve')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-gray-400">
        <p class="text-xs text-gray-500">Rembourses</p>
        <p class="text-2xl font-bold text-gray-800">{{ App\Models\Pret::where('statut','rembourse')->count() }}</p>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-xs">
            <tr>
                <th class="px-4 py-3 text-left font-semibold">Membre</th>
                <th class="px-4 py-3 text-left font-semibold">Montant</th>
                <th class="px-4 py-3 text-left font-semibold">Taux</th>
                <th class="px-4 py-3 text-left font-semibold">Duree</th>
                <th class="px-4 py-3 text-left font-semibold">Date demande</th>
                <th class="px-4 py-3 text-left font-semibold">Statut</th>
                <th class="px-4 py-3 text-left font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($prets as $pret)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $pret->membre->prenom }} {{ $pret->membre->nom }}</td>
                <td class="px-4 py-3">{{ number_format($pret->montant_demande, 0, ',', ' ') }} FCFA</td>
                <td class="px-4 py-3">{{ $pret->taux_interet ? $pret->taux_interet.'%' : '—' }}</td>
                <td class="px-4 py-3">{{ $pret->duree_mois ? $pret->duree_mois.' mois' : '—' }}</td>
                <td class="px-4 py-3">{{ $pret->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    @if($pret->statut === 'approuve')
                        <span class="inline-block w-16 h-5 rounded" style="background-color:#22c55e"></span>
                    @elseif($pret->statut === 'en_attente')
                        <span class="inline-block w-16 h-5 rounded" style="background-color:#f97316"></span>
                    @elseif($pret->statut === 'rejete')
                        <span class="inline-block w-16 h-5 rounded" style="background-color:#ef4444"></span>
                    @else
                        <span class="inline-block w-16 h-5 rounded" style="background-color:#6b7280"></span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if(auth()->user()->isAdmin() && $pret->statut === 'en_attente')
                        <a href="{{ route('prets.edit', $pret) }}"
                            class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">Traiter</a>
                    @elseif($pret->statut === 'approuve')
                        <a href="{{ route('prets.show', $pret) }}"
                            class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">Voir</a>
                    @else
                        <a href="{{ route('prets.show', $pret) }}"
                            class="bg-gray-400 text-white text-xs px-3 py-1 rounded hover:bg-gray-500">Voir</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-400">Aucun prêt trouvé.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 flex items-center justify-between border-t text-sm text-gray-500">
        <span>Affichage {{ $prets->firstItem() }}-{{ $prets->lastItem() }} de {{ $prets->total() }}</span>
        {{ $prets->links() }}
    </div>
</div>

@endsection
