<?php

namespace App\Http\Controllers;

use App\Models\Pret;
use App\Models\Membre;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PretController extends Controller
{
    public function index(Request $request)
    {
        $query = Pret::with('membre');
        if ($request->statut) {
            $query->where('statut', $request->statut);
        }
        if ($request->membre_id) {
            $query->where('membre_id', $request->membre_id);
        }
        $prets = $query->orderBy('created_at', 'desc')->paginate(10);
        $membres = Membre::all();
        return view('prets.index', compact('prets', 'membres'));
    }

    public function create()
    {
        $membre = auth()->user()->membre;
        return view('prets.create', compact('membre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'montant_demande' => 'required|numeric|min:0',
            'motif' => 'required|string|max:500',
        ]);

        $membre = auth()->user()->membre;

        // Vérifier qu'il n'a pas de prêt actif
        if ($membre->pretActif()) {
            return back()->with('error', 'Vous avez déjà un prêt actif.');
        }

        // Vérifier 3 cotisations consécutives
        $cotisations = $membre->cotisations()->orderBy('annee', 'desc')->orderBy('mois', 'desc')->count();
        if ($cotisations < 3) {
            return back()->with('error', 'Vous devez avoir au moins 3 cotisations pour demander un prêt.');
        }

        // Vérifier le montant maximum
        $montantMax = $membre->solde_epargne * 3;
        if ($request->montant_demande > $montantMax) {
            return back()->with('error', 'Le montant maximum est ' . $montantMax . ' FCFA.');
        }

        Pret::create([
            'membre_id' => $membre->id,
            'montant_demande' => $request->montant_demande,
            'motif' => $request->motif,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('prets.index')->with('success', 'Demande de prêt soumise avec succès.');
    }

    public function show(Pret $pret)
    {
        $pret->load('membre', 'remboursements');
        return view('prets.show', compact('pret'));
    }

    public function edit(Pret $pret)
    {
        return view('prets.edit', compact('pret'));
    }

    public function update(Request $request, Pret $pret)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,approuve,rejete,rembourse',
            'montant_accorde' => 'nullable|numeric|min:0',
            'taux_interet' => 'nullable|numeric|min:0',
            'duree_mois' => 'nullable|integer|min:1',
            'motif_rejet' => 'nullable|string',
        ]);

        if ($request->statut === 'approuve') {
            $montantTotal = $request->montant_accorde * (1 + $request->taux_interet / 100);
            $pret->update([
                'statut' => 'approuve',
                'montant_accorde' => $request->montant_accorde,
                'taux_interet' => $request->taux_interet,
                'duree_mois' => $request->duree_mois,
                'montant_total' => $montantTotal,
                'capital_restant' => $montantTotal,
            ]);

            Transaction::create([
                'membre_id' => $pret->membre_id,
                'type' => 'pret',
                'montant' => $request->montant_accorde,
                'description' => 'Prêt approuvé',
                'date_transaction' => now(),
            ]);
        } elseif ($request->statut === 'rejete') {
            $pret->update([
                'statut' => 'rejete',
                'motif_rejet' => $request->motif_rejet,
            ]);
        }

        return redirect()->route('prets.index')->with('success', 'Prêt mis à jour avec succès.');
    }

    public function destroy(Pret $pret)
    {
        $pret->delete();
        return redirect()->route('prets.index')->with('success', 'Prêt supprimé avec succès.');
    }
}
