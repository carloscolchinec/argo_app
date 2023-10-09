@extends('layouts.adminlte_admin')

@section('title', 'Detalles del Cliente')

@section('content')
    <div class="container-fluid py-3">
        <h2 class="mb-4">Detalles del Cliente</h2>
        <div class="card">
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-4">
                            <p class="font-weight-bold">Información Personal:</p>
                            <p><span><strong>Cliente:</strong></span> {{ $client->name_client . ' ' . $client->lastname_client }}</p>
                            <p><span><strong>Identificación:</strong></span> {{ $client->ni_client }}</p>
                            <p><span><strong>IP del Cliente:</strong></span> {{ $client->ip_client }}</p>
                        </div>
                        <div>
                            <p class="font-weight-bold">Plan de Internet:</p>
                            <p><span><strong>Nombre:</strong></span> {{ $client->plan->name }}</p>
                            <p><span><strong>Precio:</strong></span> {{ $client->plan->price }}</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-4">
                            <p class="font-weight-bold">Información del Router:</p>
                            <p><span><strong>Nombre:</strong></span> {{ $client->router->name_router }}</p>
                            <p><span><strong>Descripción:</strong></span> {{ $client->router->description_router }}</p>
                        </div>
                        <!-- Agregar más detalles si es necesario -->
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
@endsection
