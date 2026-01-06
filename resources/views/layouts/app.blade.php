<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CinemaHub') - Mozi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #0f0f0f;
            color: #e5e5e5;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        nav {
            background: #1a1a1a;
            border-bottom: 1px solid #2a2a2a;
            padding: 1rem 0;
        }
        nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            color: #e5e5e5;
            text-decoration: none;
            padding: 0.5rem 1rem;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #3b82f6;
        }
        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2563eb;
        }
        .btn-secondary {
            background: #374151;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
        .logout-btn {
            background: none;
            border: 1px solid #dc2626;
            color: #ef4444;
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s;
        }
        .logout-btn:hover {
            background: #dc2626;
            color: white;
        }
        main {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }
      
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 6px;
        }
        .alert-success {
            background: #065f46;
            color: #d1fae5;
        }
        .alert-error {
            background: #991b1b;
            color: #fecaca;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav>
        <div class="container">
            <a href="{{ route('home') }}" style="font-size: 1.5rem; font-weight: bold;">🎬 GépészMozi</a>
            <div class="nav-links">
                <a href="{{ route('home') }}">Főoldal</a>
                <a href="{{ route('movies.index') }}">Filmek</a>
                <a href="{{ route('screenings.index') }}">Műsor</a>
                @auth
                    <a href="{{ route('tickets.my-tickets') }}"> Jegyeim</a>
                    <a href="{{ route('profile.edit') }}"> Profilom</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <a href="{{ route('admin.movies.index') }}">Filmek (Admin)</a>
                        <a href="{{ route('admin.screenings.index') }}">Vetítések (Admin)</a>
                        <a href="{{ route('admin.bookings.index') }}">Foglalások</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Kilépés</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Belépés</a>
                    <a href="{{ route('register') }}">Regisztráció</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </div>
    </main>

 

    @stack('scripts')
</body>
</html>