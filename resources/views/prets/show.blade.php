@extends('layouts.app')

@section('title', 'Détail du Prêt')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Membre</p>
                <p class="font-semibold">{{ $pret->membre->prenom }} {{ $pret->membre->nom }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Statut</p>
                <span class="px-2 py-1 rounded text-xs
                    {{ $pret->statut === 'approuve' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $pret->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $pret->statut === 'rejete' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $pret->statut === 'rembourse' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $pret->statut)) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500">Montant demandé</p>
                <p class="font-semibold">{{ number_format($pret->montant_demande, 0, ',', ' ') }} FCFA</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Montant accordé</p>
                <p class="font-semibold">{{ $pret->montant_accorde ? number_format($pret->montant_accorde, 0, ',', ' ').' FCFA' : '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Taux d'intérêt</p>
                <p class="font-semibold">{{ $pret->taux_interet ? $pret->taux_interet.'%' : '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Durée</p>
                <p class="font-semibold">{{ $pret->duree_mois ? $pret->duree_mois.' mois' : '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Montant total à rembourser</p>
                <p class="font-semibold text-orange-600">{{ $pret->montant_total ? number_format($pret->montant_total, 0, ',', ' ').' FCFA' : '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Capital restant</p>
                <p class="font-semibold text-red-600">{{ $pret->capital_restant ? number_format($pret->capital_restant, 0, ',', ' ').' FCFA' : '-' }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500">Motif</p>
                <p class="font-semibold">{{ $pret->motif }}</p>
            </div>
            @if($pret->motif_rejet)
            <div class="col-span-2">
                <p class="text-sm text-gray-500">Motif du rejet</p>
                <p class="font-semibold text-red-600">{{ $pret->motif_rejet }}</p>
            </div>
            @endif
        </div>

        @if($pret->statut === 'approuve')
        <div class="mt-4">
            <a href="{{ route('remboursements.create', ['pret_id' => $pret->id]) }}"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                + Enregistrer un remboursement
            </a>
        </div>
        @endif
    </div>

    <!-- Remboursements -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">📋 Historique des remboursements</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Montant</th>
                    <th class="px-4 py-2 text-left">Capital restant</th>
                    <th class="px-4 py-2 text-left">Commentaire</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pret->remboursements as $remboursement)
                <tr>
                    <td class="px-4 py-2">{{ $remboursement->date_remboursement->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 font-semibold text-green-600">{{ number_format($remboursement->montant, 0, ',', ' ') }} FCFA</td>
                    <td class="px-4 py-2 text-red-600">{{ number_format($remboursement->capital_restant, 0, ',', ' ') }} FCFA</td>
                    <td class="px-4 py-2 text-gray-500">{{ $remboursement->commentaire ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400">Aucun remboursement.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
