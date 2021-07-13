@extends('layouts.app')

@section('body-class', 'board-page')

@section('content')

<button class="aside-button" id="aside-button">
    <img src="{{ asset('assets/menu.png') }}" alt="Menú">
</button>

<aside class="board-aside" id="board-aside">
    <img class="board-aside-logo" src="{{ asset('assets/logo_color copySVG.svg') }}" alt="Grupo Neduer">
    <ul class="board-nav">
        <li>
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/doc.svg') }}" alt="">
                Seguiridad Social
            </a>
        </li>
    </ul>
</aside>
<main class="board-main">
    <header class="board-header">
        <div class="board-date">
            <strong>Acceso Corporativo</strong><br>
            {{ $format_date }}
        </div>
        <div class="board-profile">
            <span class="board-profile-initials">{{$user->initials}}</span>
            <strong class=" board-profile-user toggle-profile-menu">{{$user->name}}</strong>
            <span class=" board-profile-arrow toggle-profile-menu">&#94;</span>
            <ul class="board-profile-menu" id="profile-menu">
                <li>
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/usuario.svg') }}" alt="Usuario">
                        Ver mi perfil
                    </a>
                </li>
                <li>
                    <a href="{{ url('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <img src="{{ asset('assets/logout.svg') }}" alt="Logout">
                        Cerrar sesión
                    </a>
                    <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </header>
    <article class="board-welcome">
        <h1>
            Hola, {{$user->name}}<br>
            <small>Bienvenido al área de <strong>acceso corporativo</strong></small>
        </h1>
        <img src="{{ asset('assets/Asset 1 CopySVG.svg') }}" alt="">
    </article>
    <article class="board-documents">
        <h2 class="board-documents-title">Seguridad social</h2>
        <ul class="board-documents-list">
            {{-- <li>
                <img src="{{ asset('assets/doc.svg') }}" alt="">
            <span class="document-name">Mayo</span>
            <a href="#">Descargar</a>
            </li>
            <li>
                <img src="{{ asset('assets/doc.svg') }}" alt="">
                <span class="document-name">Junio</span>
                <a href="#">Descargar</a>
            </li>
            <li>
                <img src="{{ asset('assets/doc.svg') }}" alt="">
                <span class="document-name">Julio</span>
                <a href="#">Descargar</a>
            </li> --}}

            @foreach ($files as $file)
            <li>
                <img src="{{ asset('assets/doc.svg') }}" alt="">
                {{-- <span class="document-name">{{ $file['name'] }}</span> --}}
                <span class="document-name">{{ $file['month'] }}</span>
                <a href="{{ url("files/{$file['path']}") }}" download>
                    Descargar
                </a>
            </li>
            @endforeach

        </ul>
    </article>
</main>

@endsection