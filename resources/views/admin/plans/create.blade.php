@extends('layouts.adminlte_admin')

@section('title', 'Crear Plan')

@section('content')
    <div class="container-fluid py-3">
        <h2>Crear Nuevo Plan</h2>
        <form action="{{ route('admin.planes.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nombre del Plan</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="price">Precio</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection
