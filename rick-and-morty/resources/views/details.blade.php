@extends('layouts.app')

@section('content')
    <h1 class="mb-4">{{ $character['name'] }}</h1>
    <div class="card p-4">
        <div class="row align-items-center">
            <div class="col-md-4 text-center">
                <img src="{{ $character['image'] }}" alt="{{ $character['name'] }}" class="rounded-circle img-fluid" style="max-width: 250px;">
            </div>
            <div class="col-md-8">
                <p><strong>Espécie:</strong> {{ $character['species'] }}</p>

                @if($fromDb)
                    <p><strong>URL:</strong> <a href="{{ $character['url'] }}" target="_blank">{{ $character['url'] }}</a></p>
                    <p><strong>Criado em:</strong> {{ $character['created_at'] }}</p>
                    <p><strong>Atualizado em:</strong> {{ $character['updated_at'] }}</p>
                @else
                    <p><strong>Status:</strong> {{ $character['status'] }}</p>
                    <p><strong>Gênero:</strong> {{ $character['gender'] }}</p>
                    <p><strong>Origem:</strong> {{ $character['origin']['name'] }}</p>
                    <p><strong>Localização:</strong> {{ $character['location']['name'] }}</p>
                @endif

                <div class="text-end mt-4 d-flex justify-content-end gap-2">
                    @if($fromDb)
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Excluir</button>
                        <a href="{{ route('characters.index') }}" class="btn btn-primary">Voltar</a>
                    @else
                        @auth
                            <form action="{{ route('characters.store') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="name" value="{{ $character['name'] }}">
                                <input type="hidden" name="species" value="{{ $character['species'] }}">
                                <input type="hidden" name="image" value="{{ $character['image'] }}">
                                <input type="hidden" name="url" value="{{ $character['url'] }}">
                                <button type="submit" class="btn btn-success">Salvar</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-secondary">Faça login para salvar personagens</a>
                        @endauth
                        <a href="{{ route('home') }}" class="btn btn-primary">Voltar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
