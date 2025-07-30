@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-center">Meus Personagens</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($characters->isEmpty())
        <p class="text-center">Nenhum personagem salvo.</p>
    @else
        <div class="row">
            @foreach ($characters as $character)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ $character->image }}" class="card-img-top" alt="{{ $character->name }}"
                             style="height: 300px; object-fit: cover; transition: transform 0.3s;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $character->name }}</h5>
                            <p class="card-text"><strong>Espécie:</strong> {{ $character->species }}</p>

                            <div class="mt-auto d-flex flex-column gap-2">
                                <a href="{{ route('characters.show', $character->id) }}" class="btn btn-info w-100">Detalhes</a>

                                <form action="{{ route('characters.destroy', $character->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">Excluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
