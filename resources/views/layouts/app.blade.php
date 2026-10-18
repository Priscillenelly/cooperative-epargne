<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoopEpargne</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-52 flex-shrink-0 flex flex-col" style="background-color:#1a2332">
        <div class="p-4">
            <div class="text-white font-bold text-sm py-2 px-3 rounded text-center" style="background-color:#2563eb">
                CoopEpargne
            </div>
        </div>
        <nav class="flex-1 px-3 py-2 space-y-1 text-sm">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 px-3 py-2 rounded text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : '' }}">
                ⊞ Dashboard
            </a>
            <a href="{{ route('membres.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('membres.*') ? 'bg-blue-600 text-white' : '' }}">
                ● Membres
            </a>
            <a href="{{ route('cotisations.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('cotisations.*') ? 'bg-blue-600 text-white' : '' }}">
                ◎ Cotisations
            </a>
            <a href="{{ route('prets.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('prets.*') ? 'bg-blue-600 text-white' : '' }}">
                ○ Prets
            </a>
            <a href="{{ route('remboursements.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('remboursements.*') ? 'bg-blue-600 text-white' : '' }}">
                ↔ Remboursements
            </a>
           <a href="{{ route('transactions.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded text-gray-400 hover:bg-blue-600 hover:text-white">
                ⇌ Transactions
            </a>
        </nav>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Header -->
        <header class="bg-white border-b px-6 py-3 flex items-center justify-between">
           <h1 class="text-lg font-bold text-gray-800">@yield('title')</h1>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="ml-3">
                    @csrf
                    <button class="text-xs text-red-500 hover:text-red-700">Déconnexion</button>
                </form>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-auto p-6">
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded text-sm">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
