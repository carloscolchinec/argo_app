<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CnTbClient;
use App\Models\CnTbPlan;
use App\Models\CnTbRouter;
use App\Libs\RouterosAPI;

use Carbon\Carbon;


use Illuminate\Support\Facades\Auth;





class CnTbClientController extends Controller
{
    public function index()
    {
        $clients = CnTbClient::with(['router', 'plan'])->get();
    
        foreach ($clients as $client) {
            $client->isActive = $this->pingRouter($client->router, $client->ip_client);
        }
    
        return view('admin.clients.index', compact('clients'));
    }
    

    
    



   



    public function create()
    {
        $plans = CnTbPlan::all();
        $routers = CnTbRouter::all();
        return view('admin.clients.create', compact('plans', 'routers'));
    }


    public function show($id)
    {
        $client = CnTbClient::findOrFail($id);
        return view('admin.clients.show', compact('client'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['fecha_suscripcion'] = Carbon::now(); // Obtén la fecha y hora actual

        CnTbClient::create($data);

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function edit($id)
    {
        $client = CnTbClient::findOrFail($id);
        $plans = CnTbPlan::all(); // Cargar datos de los planes
        $routers = CnTbRouter::all(); // Cargar datos de los routers
        return view('admin.clients.edit', compact('client', 'plans', 'routers'));
    }

    public function update(Request $request, $id)
    {
        $client = CnTbClient::findOrFail($id);

        $client->update([
            'id_router' => $request->input('id_router'),
            'id_plan' => $request->input('id_plan'),
            'name_client' => $request->input('name_client'),
            'lastname_client' => $request->input('lastname_client'),
            'ni_client' => $request->input('ni_client'),
            'ip_client' => $request->input('ip_client'),
        ]);

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }


    public function destroy($id)
    {
        $client = CnTbClient::findOrFail($id);
        $client->delete();
        return redirect()->route('admin.clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }

    // Private Functions

    private function pingRouter($routerData, $ip_client)
    {
        $API = new RouterOSAPI();
    
        if ($API->connect($routerData->ip_router, $routerData->user_router, $routerData->pass_router)) {
            $pingCommand = "/ping";
            $pingParams = array(
                "address" => $ip_client,
                "count" => "1"
            );
    
            // Realizar un solo intento de ping
            $response = $API->comm($pingCommand, $pingParams);
    
            $API->disconnect();
    
            // Verificar si el ping fue exitoso
            return $response[0]['received'] == 1;
        } else {
            // Si no se puede conectar al router, marcar como desconectado en la base de datos
            $routerClients = CnTbClient::where('id_router', $routerData['id_router'])->get();
            foreach ($routerClients as $client) {
                $client->update(['is_connected' => false]); // Marcar como desconectado en la base de datos
            }
    
            return false;
        }
    }
}
