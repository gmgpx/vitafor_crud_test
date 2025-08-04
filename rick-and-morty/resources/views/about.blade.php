@extends('layouts.app')

@section('content')
    <h1>Sobre</h1>
    <p><strong>Gustavo Monteiro</strong></p>
    <p><strong>Email:</strong> gugapires92@gmail.com</p>
    <p><strong>Telefone:</strong> (15) 99683-4032</p>
    <p>
        <a href="https://gmgpx.vercel.app" target="_blank" rel="noopener noreferrer">
            Portfolio
        </a>
    </p>
    <p>
        <a href="https://www.linkedin.com/in/gustavo-monteiro-727450210" target="_blank" rel="noopener noreferrer">
            LinkedIn
        </a>
    </p>
    <a href="{{ route('home') }}" class="btn btn-primary">Voltar</a>
@endsection