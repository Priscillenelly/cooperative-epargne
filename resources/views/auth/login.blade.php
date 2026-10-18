<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoopEpargne — Connexion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans">
<div class="flex h-screen">

    <!-- Gauche bleue -->
    <div class="w-2/5 flex flex-col justify-between p-10 text-white" style="background-color:#2563eb">
        <div>
            <h1 class="text-3xl font-bold mb-2">CoopEpargne</h1>
            <p class="text-lg font-semibold text-blue-100">Gestion de votre cooperative d'epargne</p>
            <p class="text-sm text-blue-200 mt-3">Membres, cotisations, prets et remboursements en toute simplicite.</p>
        </div>

        <!-- Cercles décoratifs -->
        <div class="relative my-8">
            <div class="w-32 h-32 rounded-full border-2 border-blue-400 opacity-40 absolute"></div>
            <div class="w-48 h-48 rounded-full border-2 border-blue-400 opacity-30 absolute -top-8 -left-8"></div>
            <div class="w-64 h-64 rounded-full border-2 border-blue-400 opacity-20 absolute -top-16 -left-16"></div>
            <div class="w-16 h-16 rounded-full bg-blue-400 opacity-20 ml-4 mt-4"></div>
        </div>

        <!-- Cartes stats -->
        <div class="flex gap-3 mt-8">
            <div class="bg-white bg-opacity-20 rounded-lg p-4 flex-1 text-center">
                <p class="text-sm font-medium">Membres</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-4 flex-1 text-center">
                <p class="text-sm font-medium">Taux remb.</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-4 flex-1 text-center">
                <p class="text-sm font-medium">Epargnes</p>
            </div>
        </div>
    </div>

    <!-- Droite formulaire -->
    <div class="flex-1 flex items-center justify-center bg-gray-50">
        <div class="bg-white rounded-xl shadow-lg p-10 w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">Connexion</h2>
            <p class="text-sm text-center text-gray-500 mb-6">Connectez-vous a votre espace</p>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="admin@coopepargne.ci">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Mot de passe</label>
                    <input type="password" name="password"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded">
                        Se souvenir de moi
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Mot de passe oublie ?</a>
                </div>
                <button type="submit"
                    class="w-full py-3 rounded-lg text-white font-semibold hover:bg-blue-700 transition" style="background-color:#2563eb">
                    Se connecter
                </button>
                <p class="text-center text-sm text-gray-500 mt-4">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">S'inscrire</a>
                </p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
