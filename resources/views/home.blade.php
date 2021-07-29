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
    <article id="files-list-app">
        <h2 class="board-documents-title">Seguridad social</h2>

        <div v-if="isLoading" class="board-documents-loading">
            <img src="{{ asset('assets/loading2.gif') }}" alt="Cargando...">
            <span>Cargando Documentos ...</span>
        </div>

        <div v-if="!isLoading && files.length == 0" class="board-documents-loading">
            <span>No hay documentos para mostrar</span>
        </div>

        <ul v-if="!isLoading && files.length > 0" class="board-documents-list">
            <li v-for="(file, index) in files" :key="index">
                <img src="{{ asset('assets/doc.svg') }}" alt="Documento Logo">
                <span class="document-name">@{{ file.month }}</span>
                <a :href="file.download_url" download>Descargar</a>
            </li>
        </ul>
    </article>
</main>

@endsection