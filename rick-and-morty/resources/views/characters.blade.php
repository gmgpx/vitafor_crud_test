@extends('layouts.app')
@section('content')
    <h1>Meus Personagens</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($characters->isEmpty())
        <p>Nenhum personagem salvo.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Espécie</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($characters as $character)
                    <tr>
                        <td>{{ $character->name }}</td>
                        <td>{{ $character->species }}</td>
                        <td>
                            <a href="{{ route('characters.show', $character->id) }}" class="btn btn-info btn-sm">Detalhes</a>
                            <form action="{{ route('characters.destroy', $character->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection