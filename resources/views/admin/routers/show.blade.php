@extends('layouts.adminlte_admin')

@section('title', 'Detalles del Router')

@section('content')
<div class="container-fluid py-3">
    <h2 class="mb-4">Detalles del Router</h2>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="font-weight-bold mb-3">Información del Router</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><span class="font-weight-bold">IP:</span> {{ $router->ip_router }}</li>
                        <li class="list-group-item"><span class="font-weight-bold">Nombre:</span> {{ $router->name_router }}</li>
                        <li class="list-group-item"><span class="font-weight-bold">Descripción:</span> {{ $router->description_router }}</li>
                        <li class="list-group-item"><span class="font-weight-bold">Usuario:</span> {{ $router->user_router }}</li>
                        <li class="list-group-item"><span class="font-weight-bold">Contraseña:</span> {{ $router->pass_router }}</li>
                        
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.routers.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection
