@extends('layouts.adminlte_admin')

@section('title', 'Routers')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
    <div class="container-fluid py-3">
        <h2>Listado de Routers</h2>
        <a href="{{ route('admin.routers.create') }}" class="btn btn-custom mb-3">Nuevo Router</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
       <div class="card">
        <div class="card-body">
            <table id="routersTable" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>IP</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($routers as $router)
                        <tr>
                            <td>{{ $router->id_router }}</td>
                            <td>{{ $router->name_router }}</td>
                            <td>{{ $router->description_router }}</td>
                            <td>{{ $router->ip_router }}</td>
                            <td>
                                @if($router->isActive)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.routers.show', $router->id_router) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.routers.edit', $router->id_router) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.routers.destroy', $router->id_router) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">
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
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#routersTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                pageLength: 6
            });
        });
    </script>
@endsection
