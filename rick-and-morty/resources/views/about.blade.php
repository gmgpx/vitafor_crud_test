@extends('layouts.app')
@section('content')
    <h1>Sobre</h1>
    <p><strong>Nome:</strong> [Seu Nome]</p>
    <p><strong>GitHub:</strong> <a href="[Seu GitHub]">[Seu GitHub]</a></p>
    <p><strong>LinkedIn:</strong> <a href="[Seu LinkedIn]">[Seu LinkedIn]</a></p>
    <p><strong>Email:</strong> [Seu Email]</p>
    <p><strong>Portfolio:</strong> <a href="[Seu Portfolio]">[Seu Portfolio]</a></p>
    <p>Desenvolvedor com experiência em JavaScript, React, Laravel e outras tecnologias.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Voltar</a>
@endsection