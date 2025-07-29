@extends('layouts.app')
@section('content')
    <h1>Personagens do Rick and Morty</h1>
    <div class="row">
        @foreach ($characters as $character)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $character['image'] }}" class="card-img-top" alt="{{ $character['name'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $character['name'] }}</h5>
                        <p class="card-text">Espécie: {{ $character['species'] }}</p>
                        @auth
                            <form action="{{ route('characters.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="name" value="{{ $character['name'] }}">
                                <input type="hidden" name="species" value="{{ $character['species'] }}">
                                <input type="hidden" name="image" value="{{ $character['image'] }}">
                                <input type="hidden" name="url" value="{{ $character['url'] }}">
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Login para salvar</a>
                        @endauth
                        <a href="{{ route('characters.show', $character['id']) }}" class="btn btn-info mt-2">Detalhes</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection