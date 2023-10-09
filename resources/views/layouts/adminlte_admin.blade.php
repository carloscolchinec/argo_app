<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Argo Comunnications</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    @yield('styles')

</head>

<style>
    .main-sidebar {
        background: #262f35;
    }

    .navbar {
        background: #262f35;
    }

    .main-footer {
        background: #262f35;
    }
    

    .btn-custom {
        background: #262f35;
        color: #fff;
    }
    .nav-link {
        color: #869099 !important;
    }

    .nav-link:hover {
        color: #FFF !important;
    }

</style>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <div class="text-center py-4">
                    <img src="{{ asset('assets/img/Argo.png') }}" alt="Logo" class="img-fluid"
                        style="max-width: 210px;">
                </div>
            </a>
        
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-4">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <!-- Opción Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt mr-2"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
        
                        <!-- Opción Clientes -->
                        <li class="nav-item">
                            <a href="{{ route('admin.clientes.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-users mr-2"></i>
                                <p>Clientes</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('admin.routers.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-network-wired mr-2"></i>
                                <p>Routers</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.planes.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-sitemap mr-2"></i>
                                <p>Planes</p>
                            </a>
                        </li>
                        

        
                        <li class="nav-item">
                            <a href="{{ route('admin.logout') }}" class="nav-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="nav-icon fas fa-sign-out-alt mr-2"></i>
                                <p>Cerrar Sesión</p>
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="GET"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        
        

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <!-- Include the same content header code here -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

                
            </section>
            <!-- /.content -->

            
        </div>
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                Software Desarrollado por <a href="">COLNET</a>
            </div>
            <strong>&copy; 2023 Todos los derechos reservados.</strong>
        </footer>
        
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    @yield('scripts')
</body>

</html>
