@extends('layouts.adminlte_admin')

@section('title', 'Clientes')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
    <div class="container-fluid py-3">
        <h2>Listado de Clientes</h2>
        <a href="{{ route('admin.clientes.create') }}" class="btn btn-custom mb-3">Nuevo Cliente</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="clientesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombres y Apellidos</th>
                                <th>Identificación</th>
                                <th>IP del Cliente</th>
                                <th>Plan</th>
                                <th>Router</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $client->id_client }}</td>
                                    <td>{{ $client->name_client . ' ' . $client->lastname_client }}</td>
                                    <td>{{ $client->ni_client }}</td>
                                    <td>{{ $client->ip_client }}</td>
                                    <td>
                                        @if ($client->plan)
                                            {{ $client->plan->name }}
                                        @else
                                            No Asignado
                                        @endif
                                    </td>
                                    <td>{{ $client->router->name_router }}</td>
                                    <td>
                                        @if ($client->isActive)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.clientes.show', $client->id_client) }}"
                                            class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.clientes.edit', $client->id_client) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.clientes.destroy', $client->id_client) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('¿Estás seguro?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#clientesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                pageLength: 6
            });
        });
    </script>
@endsection
