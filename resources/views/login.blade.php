@extends('layouts.app')

@section('body-class', 'login-page')

@section('content')

<div class="login-card">
    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <div class="login-block">
            <img class="login-logo" src="{{ asset('assets/logo_color copySVG.svg') }}" alt="Grupo Neduer">
        </div>

        @include('partials.messages')

        <div class="login-block form-field">
            <label for="document">Usuario</label>
            <input id="document" name="document" type="text" maxlength="30" minlength="6" required>
        </div>
        <div class="login-block">
            <button class="login-submit" type="submit">Ingresar</button>
        </div>
    </form>
</div>

@endSection