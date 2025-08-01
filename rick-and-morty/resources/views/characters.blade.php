@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-center">Minha Coleção</h1>

    @guest
        <div class="text-center mt-24">
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="bi bi-box-arrow-in-right me-1"></i> Faça login para acessar sua coleção
            </a>
        </div>
    @endguest

    @auth
        @if ($allGroupsCompleted)
            <div class="text-center my-4">
                <img src="https://www.nicepng.com/png/full/6-61830_rick-and-morty-rick-face-png.png"
                    alt="Conquista: Todos os grupos completos" class="rick-bounce">
                <h4 class="mt-2">Você completou todos os grupos! 🏆</h4>
            </div>
        @endif

        @include('partials.badge-groups', ['badges' => $badges])
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($characters->isEmpty())
            <p class="text-center">Nenhum personagem salvo.</p>
        @else
            <div class="row">
                @foreach ($characters as $character)
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <a href="{{ route('characters.show', $character['id']) }}">
                                <img src="{{ $character->image }}" class="card-img-top"
                                     alt="{{ $character->name }}"
                                     style="height: 300px; object-fit: cover; transition: transform 0.3s;"
                                     title="{{ $character->name }}">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $character->name }}</h5>
                                <div class="mt-auto d-flex flex-column gap-2">
                                    <a href="{{ route('characters.show', $character->id) }}"
                                       class="btn btn-primary w-100">
                                        <i class="bi bi-info-lg me-1"></i>Detalhes
                                    </a>
                                    <form action="{{ route('characters.destroy', $character->id) }}" method="POST"
                                          onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="bi bi-trash me-1"></i>Excluir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endauth
@endsection

<style>
    .rick-bounce {
        max-width: 150px;
        animation: bounce 1s infinite;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-15px);
        }
    }

    @media (max-width: 768px) {
        .badge-head {
            width: 50px;
            height: 50px;
        }

        .badge-group h4 {
            font-size: 16px;
        }

        .card-title {
            font-size: 16px;
        }
    }
</style>
