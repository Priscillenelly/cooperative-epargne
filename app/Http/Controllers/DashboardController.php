<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Cotisation;
use App\Models\Pret;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEpargne = Cotisation::sum('montant');
        $totalMembres = Membre::count();
        $pretsEnCours = Pret::where('statut', 'approuve')->count();
        $montantPretsEnCours = Pret::where('statut', 'approuve')->sum('capital_restant');
        $membresEnRetard = Membre::whereHas('cotisations', function($q) {
            $q->where('statut', 'en_retard');
        })->get();
        $pretsEnAttente = Pret::where('statut', 'en_attente')->with('membre')->get();
        $cotisationsParMois = Cotisation::selectRaw('mois, annee, SUM(montant) as total')
            ->groupBy('mois', 'annee')
            ->orderBy('annee')
            ->orderBy('mois')
            ->get();

        if (auth()->user()->isAdmin()) {
            return view('dashboard.admin', compact(
                'totalEpargne', 'totalMembres', 'pretsEnCours',
                'montantPretsEnCours', 'membresEnRetard', 'pretsEnAttente',
                'cotisationsParMois'
            ));
        }

        $membre = auth()->user()->membre;

        if (!$membre) {
            return view('dashboard.membre', ['membre' => null]);
        }

        $membre->load('cotisations', 'prets');
        return view('dashboard.membre', compact('membre'));
    }
}
