<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\Membre;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CotisationController extends Controller
{
    public function index(Request $request)
    {
        $query = Cotisation::with('membre');
        if ($request->membre_id) {
            $query->where('membre_id', $request->membre_id);
        }
        if ($request->mois) {
            $query->where('mois', $request->mois);
        }
        if ($request->statut) {
            $query->where('statut', $request->statut);
        }
        $cotisations = $query->orderBy('annee', 'desc')->orderBy('mois', 'desc')->paginate(10);
        $membres = Membre::all();
        return view('cotisations.index', compact('cotisations', 'membres'));
    }

    public function create()
    {
        $membres = Membre::all();
        return view('cotisations.create', compact('membres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'membre_id' => 'required|exists:membres,id',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|min:2000',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|in:payee,en_retard',
            'date_paiement' => 'required|date',
        ]);

        $cotisation = Cotisation::create($request->all());

        // Mettre à jour le solde du membre
        $membre = Membre::find($request->membre_id);
        $membre->increment('solde_epargne', $request->montant);

        // Enregistrer la transaction
        Transaction::create([
            'membre_id' => $request->membre_id,
            'type' => 'cotisation',
            'montant' => $request->montant,
            'description' => 'Cotisation ' . $request->mois . '/' . $request->annee,
            'date_transaction' => $request->date_paiement,
        ]);

        return redirect()->route('cotisations.index')->with('success', 'Cotisation enregistrée avec succès.');
    }

    public function show(Cotisation $cotisation)
    {
        return view('cotisations.show', compact('cotisation'));
    }

    public function edit(Cotisation $cotisation)
    {
        $membres = Membre::all();
        return view('cotisations.edit', compact('cotisation', 'membres'));
    }

    public function update(Request $request, Cotisation $cotisation)
    {
        $request->validate([
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|min:2000',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|in:payee,en_retard',
            'date_paiement' => 'required|date',
        ]);

        $ancienMontant = $cotisation->montant;
        $cotisation->update($request->all());

        // Recalculer le solde
        $membre = $cotisation->membre;
        $membre->decrement('solde_epargne', $ancienMontant);
        $membre->increment('solde_epargne', $request->montant);

        return redirect()->route('cotisations.index')->with('success', 'Cotisation modifiée avec succès.');
    }

    public function destroy(Cotisation $cotisation)
    {
        $membre = $cotisation->membre;
        $membre->decrement('solde_epargne', $cotisation->montant);
        $cotisation->delete();
        return redirect()->route('cotisations.index')->with('success', 'Cotisation supprimée avec succès.');
    }
}
