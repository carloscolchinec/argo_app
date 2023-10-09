<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CnTbRouter;

use App\Libs\RouterosAPI;

class CnTbRouterController extends Controller
{
    public function index()
    {
        $routers = CnTbRouter::all();

        foreach ($routers as $router) {
            $router->isActive = $this->pingRouter($router);
        }

        return view('admin.routers.index', compact('routers'));
    }

    private function pingRouter($routerData)
    {
        $API = new RouterosAPI();

        if ($API->connect($routerData->ip_router, $routerData->user_router, $routerData->pass_router)) {
            $pingCommand = "/ping";
            $pingParams = array(
                "address" => $routerData->ip_router,
                "count" => "1"
            );

            // Realizar un solo intento de ping
            $response = $API->comm($pingCommand, $pingParams);

            $API->disconnect();

            // Verificar si el ping fue exitoso
            return $response[0]['received'] == 1;
        } else {
            return false;
        }
    }

    public function show($id)
    {
        $router = CnTbRouter::findOrFail($id);
        return view('admin.routers.show', compact('router'));
    }

    public function create()
    {
        return view('admin.routers.create');
    }

    public function store(Request $request)
    {
        CnTbRouter::create($request->all());
        return redirect()->route('admin.routers.index')->with('success', 'Router creado exitosamente.');
    }

    public function edit($id)
    {
        $router = CnTbRouter::findOrFail($id);
        return view('admin.routers.edit', compact('router'));
    }

    public function update(Request $request, $id)
    {
        $router = CnTbRouter::findOrFail($id);
        $router->update($request->all());
        return redirect()->route('admin.routers.index')->with('success', 'Router actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $router = CnTbRouter::findOrFail($id);
        $router->delete();
        return redirect()->route('admin.routers.index')->with('success', 'Router eliminado exitosamente.');
    }
}
