@extends('layouts.app')

@section('title', 'Modifier une Cotisation')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('cotisations.update', $cotisation) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Membre</label>
                <input type="text" value="{{ $cotisation->membre->prenom }} {{ $cotisation->membre->nom }}" disabled class="w-full border rounded px-3 py-2 text-sm bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mois</label>
                <select name="mois" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('mois', $cotisation->mois) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                <input type="number" name="annee" value="{{ old('annee', $cotisation->annee) }}" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Montant (FCFA)</label>
                <input type="number" name="montant" value="{{ old('montant', $cotisation->montant) }}" step="0.01" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                    <option value="payee" {{ old('statut', $cotisation->statut) == 'payee' ? 'selected' : '' }}>Payée</option>
                    <option value="en_retard" {{ old('statut', $cotisation->statut) == 'en_retard' ? 'selected' : '' }}>En retard</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date de paiement</label>
                <input type="date" name="date_paiement" value="{{ old('date_paiement', $cotisation->date_paiement->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
            <a href="{{ route('cotisations.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">Annuler</a>
        </div>
    </form>
</div>
@endsection
