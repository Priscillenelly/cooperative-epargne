@extends('layouts.app')

@section('title', 'Modifier un Membre')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('membres.update', $membre) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input type="text" name="nom" value="{{ old('nom', $membre->nom) }}" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom', $membre->prenom) }}" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                @error('prenom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                <input type="date" name="date_naissance" value="{{ old('date_naissance', $membre->date_naissance?->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $membre->telephone) }}" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'adhésion</label>
                <input type="date" name="date_adhesion" value="{{ old('date_adhesion', $membre->date_adhesion->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                @error('date_adhesion')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                <input type="text" name="adresse" value="{{ old('adresse', $membre->adresse) }}" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Photo (optionnel)</label>
                @if($membre->photo)
                    <img src="{{ Storage::url($membre->photo) }}" class="w-16 h-16 rounded-full mb-2 object-cover">
                @endif
                <input type="file" name="photo" class="w-full border rounded px-3 py-2 text-sm">
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
            <a href="{{ route('membres.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">Annuler</a>
        </div>
    </form>
</div>
@endsection
