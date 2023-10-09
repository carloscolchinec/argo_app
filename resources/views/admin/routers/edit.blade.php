@extends('layouts.adminlte_admin')

@section('title', 'Editar Router')

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Editar Router</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.routers.update', $router->id_router) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="ip_router">IP del Router</label>
                            <input type="text" class="form-control" id="ip_router" name="ip_router" value="{{ $router->ip_router }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description_router">Descripción</label>
                            <textarea class="form-control" id="description_router" name="description_router" rows="3">{{ $router->description_router }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="interface_clients">Interfaz de Clientes</label>
                            <input type="text" class="form-control" id="interface_clients" name="interface_clients" placeholder="Interfaz de salida de internet" value="{{ $router->interface_clients }}" required>
                        </div>
                        <div class="form-group">
                            <label for="user_router">Usuario</label>
                            <input type="text" class="form-control" id="user_router" name="user_router" value="{{ $router->user_router }}" required>
                        </div>
                        <div class="form-group">
                            <label for="pass_router">Contraseña</label>
                            <input type="password" class="form-control" id="pass_router" name="pass_router" value="{{ $router->pass_router }}" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            <a href="{{ route('admin.routers.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
