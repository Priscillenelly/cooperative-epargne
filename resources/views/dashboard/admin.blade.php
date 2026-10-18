@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')

{{-- Bannière --}}
<div class="rounded-lg p-5 mb-6 text-white" style="background-color:#2563eb">
    <h2 class="text-lg font-bold">Bonjour, Administrateur !</h2>
    <p class="text-sm text-blue-100">Voici un aperçu de votre coopérative au {{ now()->format('d F Y') }}.</p>
</div>

{{-- Cartes stats --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg p-4 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500">Membres actifs</p>
        <p class="text-2xl font-bold text-gray-800">{{ $totalMembres }}</p>
        <p class="text-xs text-green-500">+0 ce mois</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Total epargne</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalEpargne, 0, ',', ' ') }} F</p>
        <p class="text-xs text-green-500">+0 F ce mois</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-orange-400">
        <p class="text-xs text-gray-500">Prets en cours</p>
        <p class="text-2xl font-bold text-gray-800">{{ $pretsEnCours }}</p>
        <p class="text-xs text-gray-400">{{ $pretsEnAttente->count() }} en attente</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-red-400">
        <p class="text-xs text-gray-500">Cotis. en retard</p>
        <p class="text-2xl font-bold text-gray-800">{{ $membresEnRetard->count() }}</p>
        <p class="text-xs text-gray-400">a regulariser</p>
    </div>
</div>

{{-- Alerte --}}
@if($pretsEnAttente->count() > 0 || $membresEnRetard->count() > 0)
<div class="rounded-lg p-4 mb-6 text-sm text-white" style="background-color:#f59e0b">
    {{ $pretsEnAttente->count() }} demandes de pret en attente d'approbation
    • {{ $membresEnRetard->count() }} cotisations en retard
</div>
@endif

{{-- Tables --}}
<div class="grid grid-cols-2 gap-6 mb-6">
    {{-- Prêts en attente --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="font-semibold text-gray-800 mb-3">Prets en attente</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 text-xs border-b">
                    <th class="text-left py-2">Membre</th>
                    <th class="text-left py-2">Montant</th>
                    <th class="text-left py-2">Demande le</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($pretsEnAttente->take(5) as $pret)
                <tr class="border-b text-gray-700">
                    <td class="py-2">{{ $pret->membre->prenom }} {{ $pret->membre->nom }}</td>
                    <td class="py-2">{{ number_format($pret->montant_demande, 0, ',', ' ') }} FCFA</td>
                    <td class="py-2">{{ $pret->created_at->format('d/m/Y') }}</td>
                    <td class="py-2">
                        <a href="{{ route('prets.edit', $pret) }}"
                           class="bg-blue-600 text-white text-xs px-2 py-1 rounded hover:bg-blue-700">Traiter</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4 text-gray-400 text-center">Aucun prêt en attente ✅</td></tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('prets.index') }}" class="text-blue-600 text-xs mt-2 block hover:underline">Voir tous les prets →</a>
    </div>

    {{-- Derniers membres --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="font-semibold text-gray-800 mb-3">Derniers membres inscrits</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 text-xs border-b">
                    <th class="text-left py-2">Membre</th>
                    <th class="text-left py-2">Adhesion</th>
                    <th class="text-left py-2">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse(\App\Models\Membre::latest()->take(5)->get() as $membre)
                <tr class="border-b text-gray-700">
                    <td class="py-2">{{ $membre->prenom }} {{ $membre->nom }}</td>
                    <td class="py-2">{{ $membre->date_adhesion->format('d/m/Y') }}</td>
                    <td class="py-2">
                        <span class="inline-block w-8 h-4 rounded" style="background-color:#22c55e"></span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="py-4 text-gray-400 text-center">Aucun membre.</td></tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('membres.index') }}" class="text-blue-600 text-xs mt-2 block hover:underline">Voir tous les membres →</a>
    </div>
</div>

{{-- Graphique cotisations --}}
<div class="bg-white rounded-lg shadow p-4">
    <h3 class="font-semibold text-gray-800 mb-4">Cotisations mensuelles — {{ now()->year }} (FCFA)</h3>
    <div class="flex items-end gap-2 h-32">
        @foreach($cotisationsParMois as $cot)
        <div class="flex flex-col items-center flex-1">
            <span class="text-xs text-gray-500 mb-1">{{ $cot->total ? number_format($cot->total/1000, 0) : 0 }}</span>
            <div class="w-full rounded-t" style="background-color:#2563eb; height:{{ $cot->total ? min(100, $cot->total/10000) : 5 }}px"></div>
            <span class="text-xs text-gray-400 mt-1">{{ ['Jan','Fev','Mar','Avr','Mai','Jun','Jul','Aou','Sep','Oct','Nov','Dec'][$cot->mois-1] }}</span>
        </div>
        @endforeach
    </div>
</div>

@endsection
