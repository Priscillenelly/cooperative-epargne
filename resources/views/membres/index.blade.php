@extends('layouts.app')

@section('title', 'Gestion des membres')

@section('content')

{{-- Barre recherche + bouton --}}
<div class="flex items-center justify-between mb-4">
    <form method="GET">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Rechercher un membre..."
            class="border border-gray-300 rounded px-3 py-2 text-sm w-64 focus:ring-2 focus:ring-blue-400">
    </form>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('membres.create') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-medium">+ Ajouter</a>
    @endif
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-4">
    <div class="bg-white rounded-lg p-4 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500">Membres actifs</p>
        <p class="text-2xl font-bold text-gray-800">{{ App\Models\Membre::count() }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Nouveaux ce mois</p>
        <p class="text-2xl font-bold text-gray-800">{{ App\Models\Membre::whereMonth('created_at', now()->month)->count() }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-red-400">
        <p class="text-xs text-gray-500">Inactifs</p>
        <p class="text-2xl font-bold text-gray-800">0</p>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-xs">
            <tr>
                <th class="px-4 py-3 text-left font-semibold">Membre</th>
                <th class="px-4 py-3 text-left font-semibold">Email</th>
                <th class="px-4 py-3 text-left font-semibold">Telephone</th>
                <th class="px-4 py-3 text-left font-semibold">Adhesion</th>
                <th class="px-4 py-3 text-left font-semibold">Statut</th>
                <th class="px-4 py-3 text-left font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($membres as $membre)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $membre->prenom }} {{ $membre->nom }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $membre->user->email ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $membre->telephone ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $membre->date_adhesion->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <span class="inline-block w-10 h-5 rounded" style="background-color:#22c55e"></span>
                </td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('membres.show', $membre) }}"
                        class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">Voir</a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('membres.edit', $membre) }}"
                        class="bg-orange-400 text-white text-xs px-3 py-1 rounded hover:bg-orange-500">Edit</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Aucun membre trouvé.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 flex items-center justify-between border-t text-sm text-gray-500">
        <span>Affichage {{ $membres->firstItem() ?? 0 }}-{{ $membres->lastItem() ?? 0 }} de {{ $membres->total() }}</span>
        {{ $membres->links() }}
    </div>
</div>

@endsection
