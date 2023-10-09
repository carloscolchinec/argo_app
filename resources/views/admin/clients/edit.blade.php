@extends('layouts.adminlte_admin')

@section('title', 'Editar Cliente')

@section('content')
    <div class="container-fluid py-3">
        <h2>Editar Cliente</h2>
        <form action="{{ route('admin.clientes.update', $client->id_client) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nombres</label>
                <input type="text" class="form-control" id="name" name="name_client" value="{{ $client->name_client }}" required>
            </div>
            <div class="form-group">
                <label for="lastname">Apellidos</label>
                <input type="text" class="form-control" id="lastname" name="lastname_client" value="{{ $client->lastname_client }}" required>
            </div>
            <div class="form-group">
                <label for="ni">Identificación</label>
                <input type="text" class="form-control" id="ni" name="ni_client" value="{{ $client->ni_client }}" required>
            </div>
            <div class="form-group">
                <label for="ip">IP del Cliente</label>
                <input type="text" class="form-control" id="ip" name="ip_client" value="{{ $client->ip_client }}" required>
            </div>
            <div class="form-group">
                <label for="plan">Plan</label>
                <select class="form-control" id="plan" name="id_plan" required>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id_plan }}" {{ $client->id_plan == $plan->id_plan ? 'selected' : '' }}>
                            {{ $plan->name }} - ${{ $plan->price }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="router">Router</label>
                <select class="form-control" id="router" name="id_router" required>
                    @foreach($routers as $router)
                        <option value="{{ $router->id_router }}" {{ $client->id_router == $router->id_router ? 'selected' : '' }}>
                            {{ $router->name_router }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
