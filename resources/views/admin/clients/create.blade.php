@extends('layouts.adminlte_admin')

@section('title', 'Nuevo Cliente')

@section('content')
    <div class="container-fluid py-3">
        <h2>Nuevo Cliente</h2>
        <form action="{{ route('admin.clientes.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nombres:</label>
                <input type="text" name="name_client" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="lastname">Apellidos:</label>
                <input type="text" name="lastname_client" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="ni">Identificación:</label>
                <input type="text" name="ni_client" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="ip">IP del Cliente:</label>
                <input type="text" name="ip_client" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="plan">Plan:</label>
                <select name="id_plan" class="form-control" required>
                    <option value="" disabled selected>Seleccione un plan</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id_plan }}">{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="router">Router:</label>
                <select name="id_router" class="form-control" required>
                    <option value="" disabled selected>Seleccione un router</option>
                    @foreach($routers as $router)
                        <option value="{{ $router->id_router }}">{{ $router->name_router }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection
