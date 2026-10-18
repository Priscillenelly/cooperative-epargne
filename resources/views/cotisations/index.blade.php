@extends('layouts.app')

@section('title', 'Liste des Cotisations')

@section('content')
<div class="flex justify-between items-center mb-6">
    <form method="GET" class="flex gap-2">
        <select name="membre_id" class="border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            <option value="">Tous les membres</option>
            @foreach($membres as $membre)
                <option value="{{ $membre->id }}" {{ request('membre_id') == $membre->id ? 'selected' : '' }}>
                    {{ $membre->prenom }} {{ $membre->nom }}
                </option>
            @endforeach
        </select>
        <select name="mois" class="border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            <option value="">Tous les mois</option>
            @for($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ request('mois') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
        <select name="statut" class="border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            <option value="">Tous les statuts</option>
            <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
            <option value="en_retard" {{ request('statut') == 'en_retard' ? 'selected' : '' }}>En retard</option>
        </select>
        <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Filtrer</button>
    </form>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('cotisations.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">+ Nouvelle cotisation</a>
    @endif
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">Membre</th>
                <th class="px-4 py-3 text-left">Période</th>
                <th class="px-4 py-3 text-left">Montant</th>
                <th class="px-4 py-3 text-left">Date paiement</th>
                <th class="px-4 py-3 text-left">Statut</th>
                @if(auth()->user()->isAdmin())
                <th class="px-4 py-3 text-left">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($cotisations as $cotisation)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $cotisation->membre->prenom }} {{ $cotisation->membre->nom }}</td>
                <td class="px-4 py-3">{{ $cotisation->mois }}/{{ $cotisation->annee }}</td>
                <td class="px-4 py-3 font-semibold text-blue-600">{{ number_format($cotisation->montant, 0, ',', ' ') }} FCFA</td>
                <td class="px-4 py-3 text-gray-500">{{ $cotisation->date_paiement->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs {{ $cotisation->statut === 'payee' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $cotisation->statut === 'payee' ? 'Payée' : 'En retard' }}
                    </span>
                </td>
                @if(auth()->user()->isAdmin())
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('cotisations.edit', $cotisation) }}" class="text-yellow-500 hover:underline">Modifier</a>
                    <form method="POST" action="{{ route('cotisations.destroy', $cotisation) }}" onsubmit="return confirm('Supprimer ?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Supprimer</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Aucune cotisation trouvée.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $cotisations->links() }}</div>
</div>
@endsection
