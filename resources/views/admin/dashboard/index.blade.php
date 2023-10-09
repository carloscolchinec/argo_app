@extends('layouts.adminlte_admin')

@section('title', 'Dashboard')

@section('content')
    <div class="content">
        <section class="content-header">
            <h1>Dashboard</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Clientes</span>
                            <span class="info-box-number">{{ $totalClients }}</span>
                        </div>
                        <a href="{{ route('admin.clientes.index') }}" class="small-box-footer">Ver detalles <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-wifi"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Routers</span>
                            <span class="info-box-number">{{ $totalRouters }}</span>
                        </div>
                        <a href="{{ route('admin.routers.index') }}" class="small-box-footer">Ver detalles <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-network-wired"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Planes</span>
                            <span class="info-box-number">{{ $totalPlans }}</span>
                        </div>
                        <a href="{{ route('admin.planes.index') }}" class="small-box-footer">Ver detalles <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
