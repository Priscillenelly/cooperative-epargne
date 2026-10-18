<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoopEpargne — Inscription</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
<div class="min-h-screen flex items-center justify-center py-10">
    <div class="bg-white rounded-xl shadow-lg p-10 w-full max-w-2xl">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">Creer votre compte</h2>
        <p class="text-sm text-center text-gray-500 mb-6">Rejoignez la cooperative d'epargne</p>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telephone</label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prenom</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input type="password" name="password"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                    <input type="date" name="date_naissance" value="{{ old('date_naissance') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="membre">Membre</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 mb-6">
                <input type="checkbox" name="terms" id="terms" class="rounded" required>
                <label for="terms" class="text-sm text-gray-600">J'accepte les conditions d'utilisation</label>
            </div>

            <button type="submit"
                class="w-full py-3 rounded-lg text-white font-semibold hover:bg-blue-700 transition text-sm" style="background-color:#2563eb">
                Creer mon compte
            </button>

            <p class="text-center text-sm text-gray-500 mt-4">
                Deja inscrit ?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Se connecter</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>
