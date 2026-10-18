@extends('layouts.app')

@section('title', 'Enregistrer une cotisation')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-base font-bold text-gray-800 mb-6 pb-2 border-b">Nouvelle cotisation</h2>

    <form method="POST" action="{{ route('cotisations.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Membre</label>
            <select name="membre_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                <option value="">-- Sélectionner --</option>
                @foreach($membres as $membre)
                    <option value="{{ $membre->id }}" {{ old('membre_id') == $membre->id ? 'selected' : '' }}>
                        {{ $membre->prenom }} {{ $membre->nom }} — #{{ str_pad($membre->id, 3, '0', STR_PAD_LEFT) }}
                    </option>
                @endforeach
            </select>
            @error('membre_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Mois</label>
            <select name="mois" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                @foreach(['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'] as $i => $moisNom)
                    <option value="{{ $i+1 }}" {{ old('mois', date('n')) == $i+1 ? 'selected' : '' }}>{{ $moisNom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Annee</label>
            <input type="number" name="annee" value="{{ old('annee', date('Y')) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Montant (FCFA)</label>
            <input type="number" name="montant" value="{{ old('montant') }}" step="0.01"
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            @error('montant')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Date de paiement</label>
            <input type="date" name="date_paiement" value="{{ old('date_paiement', date('Y-m-d')) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <div class="flex items-center gap-2">
                <select name="statut" id="statut" onchange="updateBadge()"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                    <option value="payee" {{ old('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
                    <option value="en_retard" {{ old('statut') == 'en_retard' ? 'selected' : '' }}>En retard</option>
                </select>
                <div id="badge" class="w-10 h-6 rounded flex-shrink-0" style="background-color:#22c55e"></div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-medium">Enregistrer</button>
            <a href="{{ route('cotisations.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 font-medium">Annuler</a>
        </div>
    </form>
</div>

<script>
function updateBadge() {
    const statut = document.getElementById('statut').value;
    const badge = document.getElementById('badge');
    badge.style.backgroundColor = statut === 'payee' ? '#22c55e' : '#ef4444';
}
</script>
@endsection
