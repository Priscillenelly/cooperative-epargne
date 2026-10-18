@extends('layouts.app')

@section('title', 'Ajouter un membre')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-base font-bold text-gray-800 mb-6">Informations du nouveau membre</h2>

    <form method="POST" action="{{ route('membres.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input type="text" name="nom" value="{{ old('nom') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prenom</label>
                <input type="text" name="prenom" value="{{ old('prenom') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('prenom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telephone</label>
                <input type="text" name="telephone" value="{{ old('telephone') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date naissance</label>
                <input type="date" name="date_naissance" value="{{ old('date_naissance') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lieu naissance</label>
                <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                <input type="text" name="adresse" value="{{ old('adresse') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date adhesion</label>
                <input type="date" name="date_adhesion" value="{{ old('date_adhesion', date('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('date_adhesion')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Biographie (optionnel)</label>
            <textarea name="biographie" rows="3" placeholder="Membre fondateur, commercant actif..."
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('biographie') }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Photo de profil</label>
            <div class="flex items-center gap-3">
                <input type="file" name="photo"
                    class="border border-gray-300 rounded px-3 py-2 text-sm text-gray-500 w-64">
                <span class="text-xs text-gray-400">PNG ou JPG, max 2MB</span>
            </div>
        </div>

        {{-- Compte utilisateur caché --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Compte utilisateur</label>
            <select name="user_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">-- Sélectionner --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
            @error('user_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-medium">Enregistrer</button>
            <a href="{{ route('membres.index') }}"
                class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 font-medium">Annuler</a>
        </div>
    </form>
</div>
@endsection
