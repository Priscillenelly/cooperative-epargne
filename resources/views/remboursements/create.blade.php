@extends('layouts.app')

@section('title', 'Enregistrer un remboursement')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b">Nouveau remboursement</h2>

    {{-- Barre bleue recap prêt --}}
    <div class="rounded-lg p-4 mb-4 text-white" style="background-color:#2563eb">
        <div class="grid grid-cols-4 gap-2 text-sm">
            <div>
                <p class="text-blue-200 text-xs">Montant accorde</p>
                <p class="font-bold" id="info_accorde">— FCFA</p>
            </div>
            <div>
                <p class="text-blue-200 text-xs">Total du</p>
                <p class="font-bold" id="info_total">— FCFA</p>
            </div>
            <div>
                <p class="text-blue-200 text-xs">Deja rembourse</p>
                <p class="font-bold" id="info_deja">— FCFA</p>
            </div>
            <div>
                <p class="text-blue-200 text-xs">Reste</p>
                <p class="font-bold" id="info_reste">— FCFA</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('remboursements.store') }}" id="rembForm">
        @csrf
        <input type="hidden" name="pret_id" id="pret_id" value="{{ $pret->id }}">

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Pret concerne</label>
            <select name="pret_id" id="pret_select" onchange="updatePret()"
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                @foreach(\App\Models\Pret::with('membre')->where('statut','approuve')->get() as $p)
                    <option value="{{ $p->id }}"
                        data-accorde="{{ $p->montant_accorde }}"
                        data-total="{{ $p->montant_total }}"
                        data-capital="{{ $p->capital_restant }}"
                        {{ $p->id == $pret->id ? 'selected' : '' }}>
                        Pret #PR-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }} — {{ $p->membre->prenom }} {{ $p->membre->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Montant a rembourser (FCFA)</label>
            <input type="number" name="montant" id="montant" oninput="calculer()"
                value="{{ old('montant') }}" step="0.01"
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            @error('montant')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Date du remboursement</label>
            <input type="date" name="date_remboursement" value="{{ old('date_remboursement', date('Y-m-d')) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            @error('date_remboursement')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Note (optionnel)</label>
            <textarea name="commentaire" rows="3"
                placeholder="Paiement du mois de novembre 2024."
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">{{ old('commentaire') }}</textarea>
        </div>

        {{-- Barre verte recap --}}
        <div class="rounded-lg p-4 mb-6 text-white" style="background-color:#22c55e">
            <div class="grid grid-cols-3 gap-2 text-sm">
                <div>
                    <p class="text-green-100 text-xs">Rembourse</p>
                    <p class="font-bold" id="recap_rembourse">0 FCFA</p>
                </div>
                <div>
                    <p class="text-green-100 text-xs">Reste</p>
                    <p class="font-bold" id="recap_reste">0 FCFA</p>
                </div>
                <div>
                    <p class="text-green-100 text-xs">Progression</p>
                    <p class="font-bold" id="recap_progression">0%</p>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="text-white px-6 py-2 rounded font-medium text-sm" style="background-color:#2563eb">Enregistrer</button>
            <a href="{{ route('remboursements.index') }}"
                class="bg-gray-200 text-gray-700 px-6 py-2 rounded font-medium text-sm">Annuler</a>
        </div>
    </form>
</div>

<script>
const prets = @json(\App\Models\Pret::with('membre')->where('statut','approuve')->get()->keyBy('id'));

function formatNum(n) {
    return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

function updatePret() {
    const select = document.getElementById('pret_select');
    const opt = select.options[select.selectedIndex];
    const accorde = parseFloat(opt.dataset.accorde) || 0;
    const total = parseFloat(opt.dataset.total) || 0;
    const capital = parseFloat(opt.dataset.capital) || 0;
    const deja = total - capital;

    document.getElementById('info_accorde').textContent = formatNum(accorde) + ' FCFA';
    document.getElementById('info_total').textContent = formatNum(total) + ' FCFA';
    document.getElementById('info_deja').textContent = formatNum(deja) + ' FCFA';
    document.getElementById('info_reste').textContent = formatNum(capital) + ' FCFA';

    document.getElementById('pret_id').value = select.value;
    calculer();
}

function calculer() {
    const select = document.getElementById('pret_select');
    const opt = select.options[select.selectedIndex];
    const total = parseFloat(opt.dataset.total) || 0;
    const capital = parseFloat(opt.dataset.capital) || 0;
    const deja = total - capital;
    const montant = parseFloat(document.getElementById('montant').value) || 0;

    const nouveauDeja = deja + montant;
    const nouveauReste = Math.max(0, capital - montant);
    const progression = total > 0 ? ((nouveauDeja / total) * 100).toFixed(1) : 0;

    document.getElementById('recap_rembourse').textContent = formatNum(nouveauDeja) + ' FCFA';
    document.getElementById('recap_reste').textContent = formatNum(nouveauReste) + ' FCFA';
    document.getElementById('recap_progression').textContent = progression + '%';
}

// Init
updatePret();
</script>
@endsection
