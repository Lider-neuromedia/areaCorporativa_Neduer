<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Grupo Neduer</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,400;0,700;1,100;1,400;1,700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/styles.css')}}">

</head>

<body class="@yield('body-class')" style="background-image: url({{ url('assets/textura.png') }})">

    @yield('content')

    {{-- @if (Session::has('user'))

    <span>{{ App\User::find(Session::get('user'))->name }}</span>

    <a href="{{ url('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Cerrar Sesión
    </a>

    <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    @endif --}}

    <script src="{{asset('js/scripts.js')}}"></script>

</body>

</html>