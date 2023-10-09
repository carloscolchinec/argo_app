@extends('layouts.adminlte_admin')

@section('title', 'Editar Plan')

@section('content')
    <div class="container-fluid py-3">
        <h2>Editar Plan</h2>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Edición</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.planes.update', $plan->id_plan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Nombre del Plan</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $plan->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Precio</label>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" value="{{ $plan->price }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Plan</button>
                </form>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.planes.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
@endsection
