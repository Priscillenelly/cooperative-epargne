@extends('layouts.app')

@section('title', 'Traitement pret — ' . $pret->membre->prenom . ' ' . $pret->membre->nom)

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    {{-- En-tête demande --}}
    <div class="mb-6 pb-4 border-b">
        <h2 class="text-base font-bold text-gray-800 mb-1">Demande #PR-{{ date('Y') }}-{{ str_pad($pret->id, 3, '0', STR_PAD_LEFT) }}</h2>
        <p class="text-xs text-gray-500 mb-3">Soumis le {{ $pret->created_at->format('d/m/Y') }} • En attente</p>
        <div class="flex items-center gap-6">
            <span class="inline-block px-6 py-2 rounded text-white text-sm" style="background-color:#f97316">En attente</span>
            <div class="text-sm"><p class="text-gray-500 text-xs">Membre</p><p class="font-bold">{{ $pret->membre->prenom }} {{ $pret->membre->nom }}</p></div>
            <div class="text-sm"><p class="text-gray-500 text-xs">Montant</p><p class="font-bold">{{ number_format($pret->montant_demande, 0, ',', ' ') }} FCFA</p></div>
            <div class="text-sm"><p class="text-gray-500 text-xs">Duree</p><p class="font-bold">{{ $pret->duree_mois ? $pret->duree_mois.' mois' : '—' }}</p></div>
            <div class="text-sm"><p class="text-gray-500 text-xs">Motif</p><p class="font-bold">{{ $pret->motif }}</p></div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        {{-- Profil membre --}}
        <div class="border rounded-lg p-4">
            <h3 class="font-semibold text-gray-800 mb-4">Profil du membre</h3>
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0"
                    style="background-color:#2563eb">
                    {{ strtoupper(substr($pret->membre->prenom,0,1).substr($pret->membre->nom,0,1)) }}
                </div>
                <div class="text-sm space-y-2">
                    <div class="flex gap-4">
                        <span class="text-gray-500 w-24">Nom</span>
                        <span class="font-semibold">{{ $pret->membre->prenom }} {{ $pret->membre->nom }}</span>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-gray-500 w-24">Adhesion</span>
                        <span class="font-semibold">{{ $pret->membre->date_adhesion->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-gray-500 w-24">Cotisations</span>
                        <span class="font-semibold">{{ $pret->membre->cotisations->count() }} mois</span>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-gray-500 w-24">Epargne totale</span>
                        <span class="font-semibold">{{ number_format($pret->membre->solde_epargne, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-gray-500 w-24">Prets actifs</span>
                        <span class="font-semibold">{{ $pret->membre->prets->where('statut','approuve')->count() }}</span>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-gray-500 w-24">Eligible</span>
                        @php $eligible = $pret->membre->cotisations->count() >= 3; @endphp
                        <span class="font-semibold {{ $eligible ? 'text-green-600' : 'text-red-600' }}">
                            {{ $eligible ? 'OUI ✓' : 'NON ✗' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Décision --}}
        <div class="border rounded-lg p-4">
            <h3 class="font-semibold text-gray-800 mb-4">Decision</h3>
            <form method="POST" action="{{ route('prets.update', $pret) }}" id="pretForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="statut" id="statut_input" value="approuve">

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant accorde (FCFA)</label>
                    <input type="number" name="montant_accorde" value="{{ old('montant_accorde', $pret->montant_demande) }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Taux d'interet (%)</label>
                        <input type="number" name="taux_interet" value="{{ old('taux_interet', 5) }}" step="0.1"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duree (mois)</label>
                        <input type="number" name="duree_mois" value="{{ old('duree_mois', 6) }}"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                    <textarea name="motif_rejet" rows="3" placeholder="Demande validee apres verification du profil."
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
            </form>
        </div>
    </div>

    {{-- Boutons --}}
    <div class="flex gap-4 mb-6">
        <button onclick="soumettre('approuve')"
            class="px-8 py-3 rounded text-white font-semibold text-sm" style="background-color:#22c55e">Approuver</button>
        <button onclick="soumettre('rejete')"
            class="px-8 py-3 rounded text-white font-semibold text-sm" style="background-color:#ef4444">Rejeter</button>
    </div>

    {{-- Historique --}}
    <div class="border rounded-lg p-4">
        <h3 class="font-semibold text-gray-800 mb-3">Historique des prets</h3>
        @php $autresPrets = $pret->membre->prets->where('id','!=',$pret->id); @endphp
        @forelse($autresPrets as $autrePret)
        <div class="flex justify-between items-center py-2 border-b text-sm">
            <span>{{ number_format($autrePret->montant_demande, 0, ',', ' ') }} FCFA</span>
            <span>{{ $autrePret->created_at->format('d/m/Y') }}</span>
            <span class="px-2 py-1 rounded text-xs text-white" style="background-color:{{ $autrePret->statut === 'approuve' ? '#22c55e' : ($autrePret->statut === 'rejete' ? '#ef4444' : '#f97316') }}">
                {{ ucfirst($autrePret->statut) }}
            </span>
        </div>
        @empty
        <p class="text-gray-400 text-sm">Aucun pret anterieur pour ce membre.</p>
        @endforelse
    </div>
</div>

<script>
function soumettre(statut) {
    document.getElementById('statut_input').value = statut;
    document.getElementById('pretForm').submit();
}
</script>
@endsection
