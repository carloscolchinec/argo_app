@extends('layouts.adminlte_user')

@section('title', 'Inicio de Sesión')

@section('content')

<style>
    /* Estilos generales de la aplicación */
.app-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background-color: #f7f7f7;
    padding: 10px; /* Agregar un espacio alrededor del contenedor en dispositivos móviles */
}

/* Estilos para el formulario */
.login-form {
    max-width: 400px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin: 10px; /* Agregar margen en dispositivos móviles */
}

/* Estilos para el input */
.text-input {
    width: 100%;
    padding: 12px;
    margin-bottom: 10px;
    border: 2px solid #dce4ec;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
    outline: none;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.text-input:focus {
    border-color: #007BFF;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

/* Estilos para el botón */
.login-button {
    background-color: #007BFF;
    color: #fff;
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.3s;
}

.login-button:hover {
    background-color: #0056b3;
}

/* Estilos para el título */
.title {
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

/* Estilos para el logo */
.logo {
    max-width: 100%;
    margin: 0 auto;
    display: block;
}

/* Estilos para dispositivos móviles */
@media (max-width: 768px) {
    .login-form {
        margin: 0; /* Eliminar margen en dispositivos móviles */
    }
}

.alert {
    margin: 10px;
}

</style>

<div class="app-container">
    <div class="login-form">
        <img src="{{ asset('assets/img/Argo.png') }}" alt="Logo de la empresa" class="logo">
        <p class="title"><ion-icon name="lock-closed-outline" style="font-size: 32px; vertical-align: middle;"></ion-icon> Inicio de Sesión</p>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ion-badge color="danger">Error</ion-badge>
                
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                
            </div>
        @endif
        <form id="login-form" class="ons-form" action="{{ route('user.login') }}" method="POST">
            @csrf
            <input type="text" class="text-input" name="ni_client" id="niUsername" placeholder="Identificación" required>
            <button type="submit" class="login-button">Iniciar Sesión</button>
        </form>
    </div>
</div>


@endsection
