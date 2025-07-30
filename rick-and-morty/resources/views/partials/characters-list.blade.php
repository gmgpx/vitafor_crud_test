@if ($error)
    <div class="alert alert-warning text-center">{{ $error }}</div>
@endif

<div class="row" id="characters-list">
    @php
        $statusLabels = ['alive' => 'Vivo', 'dead' => 'Morto', 'unknown' => 'Desconhecido'];
        $speciesLabels = [
            'Human' => 'Humano',
            'Alien' => 'Alienígena',
            'Humanoid' => 'Humanóide',
            'Robot' => 'Robô',
            'Mythological Creature' => 'Criatura Mitológica',
            'Cronenberg' => 'Cronenberg',
        ];
        $genderLabels = [
            'female' => 'Feminino',
            'male' => 'Masculino',
            'genderless' => 'Sem Gênero',
            'unknown' => 'Desconhecido',
        ];
    @endphp

    @foreach ($characters as $character)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <a href="{{ route('characters.show', $character['id']) }}">
                    <img src="{{ $character['image'] }}" class="card-img-top character-img" alt="{{ $character['name'] }}">
                </a>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $character['name'] }}</h5>
                    <p class="card-text"><strong>Espécie:</strong> {{ $speciesLabels[$character['species']] ?? $character['species'] }}</p>
                    <p class="card-text"><strong>Status:</strong> {{ $statusLabels[strtolower($character['status'])] ?? $character['status'] }}</p>
                    <p class="card-text"><strong>Gênero:</strong> {{ $genderLabels[strtolower($character['gender'])] ?? $character['gender'] }}</p>

                    <div class="mt-auto">
                        @auth
                            <form action="{{ route('characters.store') }}" method="POST" class="mb-2">
                                @csrf
                                <input type="hidden" name="name" value="{{ $character['name'] }}">
                                <input type="hidden" name="species" value="{{ $character['species'] }}">
                                <input type="hidden" name="image" value="{{ $character['image'] }}">
                                <input type="hidden" name="url" value="{{ $character['url'] }}">
                                <button type="submit" class="btn btn-success w-100">Salvar</button>
                            </form>
                        @endauth
                        <a href="{{ route('characters.show', $character['id']) }}" class="btn btn-primary w-100">Detalhes</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@if (isset($info['pages']) && $info['pages'] > 1)
    @php
        $maxPagesToShow = 5;
        $totalPages = $info['pages'];
        $currentPage = $page;

        $startPage = max(1, $currentPage - floor($maxPagesToShow / 2));
        $endPage = $startPage + $maxPagesToShow - 1;

        if ($endPage > $totalPages) {
            $endPage = $totalPages;
            $startPage = max(1, $endPage - $maxPagesToShow + 1);
        }

        $pageRange = range($startPage, $endPage);
    @endphp

    <nav class="mt-4" aria-label="Navegação de páginas" id="pagination-nav">
        <ul class="pagination justify-content-center">
            @if ($currentPage > 1)
                <li class="page-item">
                    <a href="#" class="page-link" data-page="{{ $currentPage - 1 }}">Anterior</a>
                </li>
            @endif

            @foreach ($pageRange as $i)
                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                    <a href="#" class="page-link" data-page="{{ $i }}">{{ $i }}</a>
                </li>
            @endforeach

            @if ($currentPage < $totalPages)
                <li class="page-item">
                    <a href="#" class="page-link" data-page="{{ $currentPage + 1 }}">Próxima</a>
                </li>
            @endif
        </ul>
    </nav>
@endif
