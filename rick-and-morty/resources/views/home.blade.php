@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-center">Galeria de Personagens</h1>

    {{-- Mensagem de erro --}}
    @if (!empty($error))
        <div class="alert alert-warning text-center">{{ $error }}</div>
    @endif

    {{-- Filtros --}}
    <form method="GET" action="{{ route('home') }}" class="row g-3 mb-4" id="filter-form">
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Nome do personagem" value="{{ $filters['name'] ?? '' }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Status</option>
                @php
                    $statusOptions = ['alive' => 'Vivo', 'dead' => 'Morto', 'unknown' => 'Desconhecido'];
                @endphp
                @foreach ($statusOptions as $val => $label)
                    <option value="{{ $val }}" {{ ($filters['status'] ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="species" class="form-select">
                <option value="">Espécie</option>
                @php
                    $speciesOptions = [
                        'Human' => 'Humano',
                        'Alien' => 'Alienígena',
                        'Humanoid' => 'Humanóide',
                        'Robot' => 'Robô',
                        'Mythological Creature' => 'Criatura Mitológica',
                        'Cronenberg' => 'Cronenberg',
                    ];
                @endphp
                @foreach ($speciesOptions as $val => $label)
                    <option value="{{ $val }}" {{ ($filters['species'] ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="gender" class="form-select">
                <option value="">Gênero</option>
                @php
                    $genderOptions = [
                        'female' => 'Feminino',
                        'male' => 'Masculino',
                        'genderless' => 'Sem Gênero',
                        'unknown' => 'Desconhecido',
                    ];
                @endphp
                @foreach ($genderOptions as $val => $label)
                    <option value="{{ $val }}" {{ ($filters['gender'] ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <button type="button" id="btn-clear" class="btn btn-secondary">Limpar</button>
        </div>

    </form>

    <div id="characters-container">
        @include('partials.characters-list', [
            'characters' => $characters,
            'info' => $info,
            'page' => $page,
            'filters' => $filters,
            'error' => $error ?? null,
        ])
    </div>

    <div id="loading-overlay" style="display:none; width: 100%; min-height: 500px; justify-content: center; align-items: center; flex-direction: column; text-align: center; margin-top: 20px;">
        <img src="https://www.freepnglogos.com/uploads/rick-and-morty-png/rick-and-morty-portal-shoes-white-clothing-zavvi-23.png" alt="Carregando..." style="max-width: 200px; animation: float 2s ease-in-out infinite;">
        <p style="margin-top: 15px; font-size: 1.25rem; font-weight: bold; color: #333;">Carregando...</p>
    </div>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .character-img {
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .character-img:hover {
            transform: scale(1.05);
        }
    </style>

    <script>
        const loader = document.getElementById('loading-overlay');
        const container = document.getElementById('characters-container');
        const filterForm = document.getElementById('filter-form');

        function loadCharacters(url) {
            loader.style.display = 'flex';
            container.style.display = 'none';

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = data.html;
                    loader.style.display = 'none';
                    container.style.display = 'block';
                    bindPaginationLinks();
                })
                .catch(() => {
                    loader.style.display = 'none';
                    container.style.display = 'block';
                    alert('Erro ao carregar os personagens.');
                });
        }

        filterForm.addEventListener('submit', e => {
            e.preventDefault();
            const params = new URLSearchParams(new FormData(filterForm)).toString();
            loadCharacters(`{{ route('characters.ajax') }}?${params}`);
            history.pushState(null, '', `?${params}`);
        });

        function bindPaginationLinks() {
            document.querySelectorAll('#pagination-nav a.page-link').forEach(link => {
                link.addEventListener('click', e => {
                    e.preventDefault();
                    const page = e.target.getAttribute('data-page');
                    const params = new URLSearchParams(new FormData(filterForm));
                    params.set('page', page);
                    loadCharacters(`{{ route('characters.ajax') }}?${params.toString()}`);
                    history.pushState(null, '', `?${params.toString()}`);
                });
            });
        }

        bindPaginationLinks();

        window.addEventListener('popstate', () => {
            const params = new URLSearchParams(window.location.search);
            loadCharacters(`{{ route('characters.ajax') }}?${params.toString()}`);
        });

        document.getElementById('btn-clear').addEventListener('click', () => {
        // Limpa os inputs do form
        filterForm.reset();

        // Faz a requisição AJAX sem parâmetros (limpo)
        loadCharacters(`{{ route('characters.ajax') }}`);

        // Atualiza a URL sem parâmetros
        history.pushState(null, '', `{{ route('home') }}`);
        });

    </script>
@endsection
