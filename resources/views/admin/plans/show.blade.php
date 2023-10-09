@extends('layouts.adminlte_admin')

@section('title', 'Detalles del Plan')

@section('content')
    <div class="container-fluid py-3">
        <h2>Detalles del Plan</h2>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información del Plan</h3>
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong> {{ $plan->name }}</p>
                <p><strong>Precio:</strong> {{ $plan->price }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.planes.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
@endsection
