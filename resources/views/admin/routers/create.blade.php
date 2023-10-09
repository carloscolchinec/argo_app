@extends('layouts.adminlte_admin')

@section('title', 'Crear Router')

@section('content')
<div class="container-fluid py-3">
    <h2 class="mb-4">Crear Nuevo Router</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.routers.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name_router" placeholder="Nombre del router" required>
                </div>
                <div class="form-group">
                    <label for="ip">IP del Router</label>
                    <input type="text" class="form-control" id="ip" name="ip_router" placeholder="Ip del Router" required>
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea class="form-control" id="description" name="description_router" placeholder="Descripcion del router" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="interface_clients">Interfaz de Clientes</label>
                    <input type="text" class="form-control" id="interface_clients" name="interface_clients" placeholder="Interfaz de SALIDA DE INTERNET"  required>
                </div>
                <div class="form-group">
                    <label for="user">Usuario</label>
                    <input type="text" class="form-control" id="user" name="user_router" placeholder="Usuario" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="pass_router" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary">Crear Router</button>
                <a href="{{ route('admin.routers.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
