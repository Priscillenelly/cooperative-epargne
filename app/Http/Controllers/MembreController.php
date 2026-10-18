<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MembreController extends Controller
{
    public function index(Request $request)
    {
        $query = Membre::with('user');
        if ($request->search) {
            $query->where('nom', 'like', '%'.$request->search.'%')
                  ->orWhere('prenom', 'like', '%'.$request->search.'%');
        }
        $membres = $query->paginate(10);
        return view('membres.index', compact('membres'));
    }

    public function create()
    {
        $users = User::doesntHave('membre')->get();
        return view('membres.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
            'date_adhesion' => 'required|date',
        ]);

        $data = $request->all();
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        Membre::create($data);
        return redirect()->route('membres.index')->with('success', 'Membre ajouté avec succès.');
    }

    public function show(Membre $membre)
    {
        $membre->load('cotisations', 'prets.remboursements', 'transactions');
        return view('membres.show', compact('membre'));
    }

    public function edit(Membre $membre)
    {
        return view('membres.edit', compact('membre'));
    }

    public function update(Request $request, Membre $membre)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
            'date_adhesion' => 'required|date',
        ]);

        $data = $request->except('photo');
        if ($request->hasFile('photo')) {
            if ($membre->photo) Storage::disk('public')->delete($membre->photo);
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $membre->update($data);
        return redirect()->route('membres.index')->with('success', 'Membre modifié avec succès.');
    }

    public function destroy(Membre $membre)
    {
        if ($membre->photo) Storage::disk('public')->delete($membre->photo);
        $membre->delete();
        return redirect()->route('membres.index')->with('success', 'Membre supprimé avec succès.');
    }
}
