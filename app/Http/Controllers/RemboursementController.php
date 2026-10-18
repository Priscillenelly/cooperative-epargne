<?php

namespace App\Http\Controllers;

use App\Models\Remboursement;
use App\Models\Pret;
use App\Models\Transaction;
use Illuminate\Http\Request;

class RemboursementController extends Controller
{
  public function index(Request $request)
 {
    $prets = \App\Models\Pret::with('membre', 'remboursements')
        ->where('statut', 'approuve')
        ->paginate(12);
    return view('remboursements.index', compact('prets'));
 }
    public function create(Request $request)
    {
        $pret = Pret::findOrFail($request->pret_id);
        return view('remboursements.create', compact('pret'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pret_id' => 'required|exists:prets,id',
            'montant' => 'required|numeric|min:0',
            'date_remboursement' => 'required|date',
            'commentaire' => 'nullable|string',
        ]);

        $pret = Pret::findOrFail($request->pret_id);
        $capitalRestant = $pret->capital_restant - $request->montant;

        Remboursement::create([
            'pret_id' => $request->pret_id,
            'montant' => $request->montant,
            'capital_restant' => max(0, $capitalRestant),
            'date_remboursement' => $request->date_remboursement,
            'commentaire' => $request->commentaire,
        ]);

        // Mettre à jour le capital restant du prêt
        $pret->update(['capital_restant' => max(0, $capitalRestant)]);

        // Marquer le prêt comme remboursé si capital = 0
        if ($capitalRestant <= 0) {
            $pret->update(['statut' => 'rembourse']);
        }

        // Enregistrer la transaction
        Transaction::create([
            'membre_id' => $pret->membre_id,
            'type' => 'remboursement',
            'montant' => $request->montant,
            'description' => 'Remboursement prêt #' . $pret->id,
            'date_transaction' => $request->date_remboursement,
        ]);

        return redirect()->route('remboursements.index', ['pret_id' => $pret->id])
            ->with('success', 'Remboursement enregistré avec succès.');
    }

    public function show(Remboursement $remboursement)
    {
        return view('remboursements.show', compact('remboursement'));
    }

    public function edit(Remboursement $remboursement)
    {
        return view('remboursements.edit', compact('remboursement'));
    }

    public function update(Request $request, Remboursement $remboursement)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0',
            'date_remboursement' => 'required|date',
            'commentaire' => 'nullable|string',
        ]);

        $remboursement->update($request->all());
        return redirect()->route('remboursements.index', ['pret_id' => $remboursement->pret_id])
            ->with('success', 'Remboursement modifié avec succès.');
    }

    public function destroy(Remboursement $remboursement)
    {
        $pret = $remboursement->pret;
        $pret->increment('capital_restant', $remboursement->montant);
        $remboursement->delete();
        return redirect()->route('remboursements.index', ['pret_id' => $pret->id])
            ->with('success', 'Remboursement supprimé avec succès.');
    }
}
