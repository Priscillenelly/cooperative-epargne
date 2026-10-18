@extends('layouts.app')

@section('title', 'Historique des transactions')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-lg p-4 border-l-4 border-blue-500 shadow">
        <p class="text-xs text-gray-500">Total transactions</p>
        <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Transaction::count() }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-green-500 shadow">
        <p class="text-xs text-gray-500">Cotisations</p>
        <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Transaction::where('type','cotisation')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-orange-400 shadow">
        <p class="text-xs text-gray-500">Prets</p>
        <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Transaction::where('type','pret')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 border-l-4 border-purple-400 shadow">
        <p class="text-xs text-gray-500">Remboursements</p>
        <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Transaction::where('type','remboursement')->count() }}</p>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="border-b">
            <tr class="text-gray-700 text-xs">
                <th class="px-4 py-3 text-left font-bold">Date</th>
                <th class="px-4 py-3 text-left font-bold">Membre</th>
                <th class="px-4 py-3 text-left font-bold">Type</th>
                <th class="px-4 py-3 text-left font-bold">Montant</th>
                <th class="px-4 py-3 text-left font-bold">Description</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($transactions as $transaction)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $transaction->date_transaction->format('d/m/Y') }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $transaction->membre->prenom }} {{ $transaction->membre->nom }}</td>
                <td class="px-4 py-3">
                    @if($transaction->type === 'cotisation')
                        <span class="px-2 py-1 rounded text-xs text-white" style="background-color:#22c55e">Cotisation</span>
                    @elseif($transaction->type === 'pret')
                        <span class="px-2 py-1 rounded text-xs text-white" style="background-color:#f97316">Pret</span>
                    @else
                        <span class="px-2 py-1 rounded text-xs text-white" style="background-color:#8b5cf6">Remboursement</span>
                    @endif
                </td>
                <td class="px-4 py-3 font-semibold text-gray-800">{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA</td>
                <td class="px-4 py-3 text-gray-500">{{ $transaction->description ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucune transaction.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 flex items-center justify-between border-t text-xs text-gray-500">
        <span>Affichage {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} de {{ $transactions->total() }}</span>
        {{ $transactions->links() }}
    </div>
</div>

@endsection
