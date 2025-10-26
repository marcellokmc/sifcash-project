<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SIF') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <nav class="bg-white border-b">
        <div class="container mx-auto p-4 flex items-center justify-between">
            <a href="/" class="font-semibold">SIF</a>
            <div class="space-x-4 text-sm">
                @auth
                    <a href="{{ route('adherent.plans.index') }}">Plans</a>
                    <a href="{{ route('adherent.adhesions.index') }}">Mes adhésions</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Connexion</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-6">
        @yield('content')
    </main>
</body>
</html>
