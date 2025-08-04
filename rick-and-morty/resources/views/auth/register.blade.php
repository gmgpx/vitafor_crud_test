@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-body px-4 py-4">
            <div class="text-center">
                <h5>{{ __('Cadastre-se como Colecionador da Terra C-137') }}</h5>
            </div>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Nome') }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input id="name" type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        </div>
                        @error('name')
                            <div class="text-danger mt-1">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email">
                        </div>
                        @error('email')
                            <div class="text-danger mt-1">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Senha') }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password">
                        </div>
                        @error('password')
                            <div class="text-danger mt-1">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                        <small class="text-muted">A senha deve conter pelo menos 8 caracteres</small>
                    </div>

                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">{{ __('Confirme a senha') }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" required autocomplete="new-password">
                        </div>
                        <small class="text-muted">Confirme a senha digitada acima</small>
                    </div>

                    <div class="d-grid mb-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> {{ __('Me cadastrar') }}
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="mb-0">
                            {{ __('Já tem uma conta?') }}
                            <a href="{{ route('login') }}">{{ __('Faça login aqui') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
