@extends('layouts.app')

@section('title', 'Demande de pret')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b">Nouvelle demande de pret</h2>

    {{-- Barre verte info épargne --}}
    <div class="rounded-lg p-3 mb-4 text-white text-sm" style="background-color:#22c55e">
        Epargne actuelle : <strong>{{ number_format($membre->solde_epargne, 0, ',', ' ') }} FCFA</strong>
        — Plafond max : <strong>{{ number_format($membre->solde_epargne * 3, 0, ',', ' ') }} FCFA</strong>
    </div>

    <form method="POST" action="{{ route('prets.store') }}" id="pretForm">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Montant souhaite (FCFA)</label>
            <input type="number" name="montant_demande" id="montant" value="{{ old('montant_demande') }}"
                oninput="calculer()"
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            @error('montant_demande')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Duree souhaitee</label>
            <select name="duree_mois" id="duree" onchange="calculer()"
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                <option value="3">3 mois</option>
                <option value="6">6 mois</option>
                <option value="9">9 mois</option>
                <option value="12" selected>12 mois</option>
                <option value="18">18 mois</option>
                <option value="24">24 mois</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Motif du pret</label>
            <input type="text" name="motif" value="{{ old('motif') }}"
                placeholder="Achat de materiel commercial"
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            @error('motif')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description detaillee</label>
            <textarea name="description" rows="4"
                placeholder="Je souhaite acquerir du materiel pour developper mon activite commerciale..."
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">{{ old('description') }}</textarea>
        </div>

        {{-- Récapitulatif bleu --}}
        <div class="rounded-lg p-4 mb-6 text-white" style="background-color:#2563eb">
            <div class="grid grid-cols-4 gap-2 text-sm">
                <div>
                    <p class="text-blue-200 text-xs">Montant</p>
                    <p class="font-bold" id="recap_montant">0 FCFA</p>
                </div>
                <div>
                    <p class="text-blue-200 text-xs">Interets (5%)</p>
                    <p class="font-bold" id="recap_interet">0 FCFA</p>
                </div>
                <div>
                    <p class="text-blue-200 text-xs">Total</p>
                    <p class="font-bold" id="recap_total">0 FCFA</p>
                </div>
                <div>
                    <p class="text-blue-200 text-xs">Mensualite</p>
                    <p class="font-bold" id="recap_mensualite">0 FCFA</p>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="text-white px-6 py-2 rounded font-medium text-sm" style="background-color:#2563eb">
                Soumettre la demande
            </button>
            <a href="{{ route('prets.index') }}"
                class="bg-gray-200 text-gray-700 px-6 py-2 rounded font-medium text-sm">Annuler</a>
        </div>
    </form>
</div>

<script>
function calculer() {
    const montant = parseFloat(document.getElementById('montant').value) || 0;
    const duree = parseInt(document.getElementById('duree').value) || 12;
    const taux = 0.05;
    const interet = montant * taux;
    const total = montant + interet;
    const mensualite = total / duree;

    document.getElementById('recap_montant').textContent = formatNum(montant) + ' FCFA';
    document.getElementById('recap_interet').textContent = formatNum(interet) + ' FCFA';
    document.getElementById('recap_total').textContent = formatNum(total) + ' FCFA';
    document.getElementById('recap_mensualite').textContent = formatNum(mensualite) + ' FCFA';
}

function formatNum(n) {
    return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}
</script>
@endsection
