@extends('layouts.app')

@section('content')
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
        $originLabels = [ 'unknown' => 'Desconhecida' ];
    @endphp

    <h1 class="mb-4">
        <i class="bi bi-person-circle me-2"></i>{{ $character['name'] }}
    </h1>

    <div class="card p-4 shadow">
        <div class="row align-items-center">
            <div class="col-md-4 text-center mb-3">
                <img src="{{ $character['image'] }}" alt="{{ $character['name'] }}"
                     class="rounded-circle img-fluid shadow-sm" style="max-width: 250px;">
            </div>

            <div class="col-md-8">
                <p><i class="bi bi-box me-2"></i><strong>Espécie:</strong>
                    {{ $speciesLabels[$character['species']] ?? $character['species'] }}
                </p>

                @if($fromDb)
                    <p><i class="bi bi-link-45deg me-2"></i><strong>URL:</strong>
                        <a href="{{ $character['url'] }}" target="_blank">{{ $character['url'] }}</a>
                    </p>
                    <p><i class="bi bi-calendar-check me-2"></i><strong>Criado em:</strong> {{ $character['created_at'] }}</p>
                    <p><i class="bi bi-calendar-event me-2"></i><strong>Atualizado em:</strong> {{ $character['updated_at'] }}</p>
                @else
                    <p><i class="bi bi-heart-pulse me-2"></i><strong>Status:</strong>
                        {{ $statusLabels[strtolower($character['status'])] ?? $character['status'] }}
                    </p>
                    <p><i class="bi bi-gender-ambiguous me-2"></i><strong>Gênero:</strong>
                        {{ $genderLabels[strtolower($character['gender'])] ?? $character['gender'] }}
                    </p>
                    <p><i class="bi bi-globe-americas me-2"></i><strong>Origem:</strong>
                        {{ $originLabels[strtolower($character['origin']['name'])] ?? $character['origin']['name'] }}
                    </p>
                    <p><i class="bi bi-geo-alt me-2"></i><strong>Localização:</strong>
                        {{ $character['location']['name'] }}
                    </p>
                @endif

                <div class="text-end mt-4 d-flex justify-content-end gap-2 flex-wrap">
                    @if($fromDb)
                        <form action="{{ route('characters.destroy', $character['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Tem certeza que deseja excluir?')">
                                <i class="bi bi-trash me-1"></i>Excluir
                            </button>
                        </form>
                        <a href="{{ route('characters.index') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Voltar
                        </a>
                    @else
                        @auth
                            <form action="{{ route('characters.store') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="name" value="{{ $character['name'] }}">
                                <input type="hidden" name="species" value="{{ $character['species'] }}">
                                <input type="hidden" name="image" value="{{ $character['image'] }}">
                                <input type="hidden" name="url" value="{{ $character['url'] }}">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-bookmark-plus me-1"></i>Salvar
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Faça login para salvar na coleção
                            </a>
                        @endauth
                        <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Voltar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
