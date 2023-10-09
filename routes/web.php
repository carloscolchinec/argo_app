<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CnTbAdminController;
use App\Http\Controllers\CnTbClientController;
use App\Http\Controllers\CnTbRouterController;
use App\Http\Controllers\CnTbPlanController;
use App\Http\Controllers\InformationClientController;



Route::get('/admin/login', [CnTbAdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [CnTbAdminController::class, 'login'])->name('admin.login_form');

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [InformationClientController::class, 'showFormLogin'])->name('user.login_form');
Route::post('/login', [InformationClientController::class, 'login'])->name('user.login');
Route::get('/dashboard', [InformationClientController::class, 'dashboard'])->name('user.dashboard');
Route::get('/traffic', [InformationClientController::class, 'getTrafficData'])->name('user.traffic');
Route::get('/top', [InformationClientController::class, 'getTop10ASN'])->name('user.top10asn');



Route::group(['middleware' => 'auth:admin'], function () {

    
    Route::get('/admin/dashboard', [CnTbAdminController::class, 'dashboard'])->name('admin.dashboard');

    // Rutas para clientes
    Route::get('/admin/clientes', [CnTbClientController::class, 'index'])->name('admin.clientes.index');
    Route::get('/admin/clientes/crear', [CnTbClientController::class, 'create'])->name('admin.clientes.create');
    Route::get('/admin/clientes/{id}', [CnTbClientController::class, 'show'])->name('admin.clientes.show');
    Route::post('/admin/clientes/crear', [CnTbClientController::class, 'store'])->name('admin.clientes.store');
    Route::get('/admin/clientes/{id}/editar', [CnTbClientController::class, 'edit'])->name('admin.clientes.edit');
    Route::put('/admin/clientes/{id}/editar', [CnTbClientController::class, 'update'])->name('admin.clientes.update');
    Route::delete('/admin/clientes/{id}', [CnTbClientController::class, 'destroy'])->name('admin.clientes.destroy');


    // Rutas para Routers
    Route::get('/admin/routers', [CnTbRouterController::class, 'index'])->name('admin.routers.index');
    Route::get('/admin/routers/crear', [CnTbRouterController::class, 'create'])->name('admin.routers.create');
    Route::get('/admin/routers/{id}', [CnTbRouterController::class, 'show'])->name('admin.routers.show');
    Route::post('/admin/routers', [CnTbRouterController::class, 'store'])->name('admin.routers.store');
    Route::get('/admin/routers/{id}/editar', [CnTbRouterController::class, 'edit'])->name('admin.routers.edit');
    Route::put('/admin/routers/{id}', [CnTbRouterController::class, 'update'])->name('admin.routers.update');
    Route::delete('/admin/routers/{id}', [CnTbRouterController::class, 'destroy'])->name('admin.routers.destroy');

    // Rutas para Planes
    Route::get('/admin/planes', [CnTbPlanController::class, 'index'])->name('admin.planes.index');
    Route::get('/admin/planes/crear', [CnTbPlanController::class, 'create'])->name('admin.planes.create');
    Route::get('/admin/planes/{id}', [CnTbPlanController::class, 'show'])->name('admin.planes.show');
    Route::post('/admin/planes', [CnTbPlanController::class, 'store'])->name('admin.planes.store');
    Route::get('/admin/planes/{id}/editar', [CnTbPlanController::class, 'edit'])->name('admin.planes.edit');
    Route::put('/admin/planes/{id}', [CnTbPlanController::class, 'update'])->name('admin.planes.update');
    Route::delete('/admin/planes/{id}', [CnTbPlanController::class, 'destroy'])->name('admin.planes.destroy');


    

    Route::get('/admin/logout', [CnTbAdminController::class, 'logout'])->name('admin.logout');
    Route::resource('admins', CnTbAdminController::class);
});

    Route::redirect('/admin', '/admin/dashboard');