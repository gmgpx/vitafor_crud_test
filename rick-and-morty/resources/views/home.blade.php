@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-center">Personagens do Rick and Morty</h1>

    <div class="row">
        @foreach ($characters as $character)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ $character['image'] }}" class="card-img-top" alt="{{ $character['name'] }}" style="height: 300px; object-fit: cover; hover: transform scale(1.05); transition: transform 0.3s;">
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title">{{ $character['name'] }}</h3>
                        <p class="card-text"><strong>Espécie: </strong>{{ $character['species'] }}</p>

                        @auth
                            <form action="{{ route('characters.store') }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="name" value="{{ $character['name'] }}">
                                <input type="hidden" name="species" value="{{ $character['species'] }}">
                                <input type="hidden" name="image" value="{{ $character['image'] }}">
                                <input type="hidden" name="url" value="{{ $character['url'] }}">
                            </form>
                        @endauth

                        <a href="{{ route('characters.show', $character['id']) }}" class="btn btn-primary w-100 mt-2">Detalhes</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
