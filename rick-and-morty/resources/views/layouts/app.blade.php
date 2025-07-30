<!DOCTYPE html>
<html lang="pt-BR"> {{-- Ajustado para português do Brasil --}}
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Teste Desenvolvedor Vitafor</title>
    <link rel="icon" type="image/x-icon" href="{{ asset(path: 'favicon.svg') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #35549a;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b1/Rick_and_Morty.svg/800px-Rick_and_Morty.svg.png"
                alt="Rick and Morty" 
                width="200" height="50" 
                >
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('home') }}">Home</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('characters.index') }}">Meus Personagens</a></li>
                    @endauth
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('about') }}">Sobre</a></li>
                </ul>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    @auth
                    <li class="nav-item d-flex align-items-center text-white me-2">
                        {{ Auth::user()->name }}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sair
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                    @else
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('register') }}">Registrar</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
