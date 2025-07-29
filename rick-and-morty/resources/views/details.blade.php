@extends('layouts.app')
@section('content')
    <h1>{{ $character['name'] }}</h1>
    <div class="card">
        <img src="{{ $character['image'] }}" class="card-img-top" alt="{{ $character['name'] }}" style="max-width: 300px;">
        <div class="card-body">
            <p><strong>Espécie:</strong> {{ $character['species'] }}</p>
            <p><strong>Status:</strong> {{ $character['status'] }}</p>
            <p><strong>Gênero:</strong> {{ $character['gender'] }}</p>
            <p><strong>Origem:</strong> {{ $character['origin']['name'] }}</p>
            <p><strong>Localização:</strong> {{ $character['location']['name'] }}</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
@endsection