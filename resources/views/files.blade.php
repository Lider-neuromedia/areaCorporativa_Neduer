@extends('layouts.app')

@section('body-class', 'files-page')

@section('content')

<div class="files-page-content" id="files-refresh-app">
    <img class="files-page-logo" src="{{ asset('assets/logo_color copySVG.svg') }}" alt="Grupo Neduer">

    <div v-if="isLoading" class="files-page-loading">
        <img src="{{ asset('assets/loading2.gif') }}" alt="Cargando...">
        <span>Actualizando Documentos<br>(esta operación puede tardar unos minutos)</span>
    </div>

    <div v-if="!isLoading && message" class="files-page-message">
        @{{message}}
    </div>

    <button @@click="refreshFiles" class="files-page-action" :disabled="isLoading">
        Actualizar Documentos
    </button>
</div>

@endsection