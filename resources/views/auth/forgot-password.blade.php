@extends('auth.auth-layout')
@section('title', 'Rénitialisation du mot de passe')


@section('auth-form')
    <h1>Mot de passe oublie?</h1>
    <p class="account-subtitle">Entrer votre email pour obtenier le lien de reunitialisation</p>
    <form action="{{ route('password.request') }}" method="POST">
        @csrf
        <div class="form-group">
            <input class="form-control" type="email" placeholder="Email" name="email" value="{{ old('email') }}">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
        </div>
        <div class="form-group mb-0">
            <button class="btn btn-primary btn-block" type="submit">Recevoir le lien</button>
        </div>
    </form>
    <div class="text-center dont-have">Vous vous souvenez de votre mot de passe? <a href="{{ route('login') }}">Se
            connecter</a></div>
@endsection
