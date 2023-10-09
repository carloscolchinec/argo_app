<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CnTbAdmin;
use App\Models\CnTbClient;
use App\Models\CnTbPlan;
use App\Models\CnTbRouter;
use Illuminate\Support\Facades\Hash;


class CnTbAdminController extends Controller
{
    public function index()
    {
        $admins = CnTbAdmin::all();
        return view('admins.index', compact('admins'));
    }

    public function dashboard()
{
    $totalClients = CnTbClient::count();
    $totalRouters = CnTbRouter::count();
    $totalPlans = CnTbPlan::count();

    return view('admin.dashboard.index', compact('totalClients', 'totalRouters', 'totalPlans'));
}


    public function create()
    {
        return view('admins.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['password_admin'] = Hash::make($request->password_admin); // Hash the password
        CnTbAdmin::create($data);
        return redirect()->route('admins.index')->with('success', 'Admin creado exitosamente.');
    }

    public function edit($id)
    {
        $admin = CnTbAdmin::findOrFail($id);
        return view('admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = CnTbAdmin::findOrFail($id);
        $admin->update($request->all());
        return redirect()->route('admins.index')->with('success', 'Admin actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $admin = CnTbAdmin::findOrFail($id);
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Admin eliminado exitosamente.');
    }

    // Función para el formulario de inicio de sesión
    public function showLoginForm()
    {
        return view('admin.login.index');
    }

    // Función para el inicio de sesión
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
    
            if (auth()->guard('admin')->attempt($credentials)) {
                return redirect()->intended(route('admin.dashboard'));
            }
    
            return redirect()->route('admin.login')->with('error', 'Credenciales incorrectas.');
        } catch (\Exception $e) {
            // Aquí puedes manejar la excepción como desees, por ejemplo, mostrar un mensaje de error
            return redirect()->route('admin.login')->with('error', 'Error en el inicio de sesión: ' . $e->getMessage());
        }
    }
    

    // Función para cerrar sesión
    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
