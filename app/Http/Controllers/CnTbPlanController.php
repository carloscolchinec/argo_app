<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CnTbPlan;

class CnTbPlanController extends Controller
{
    public function index()
    {
        $plans = CnTbPlan::all();
        return view('admin.plans.index', compact('plans'));
    }


    public function show($id)
{
    $plan = CnTbPlan::findOrFail($id);
    return view('admin.plans.show', compact('plan'));
}


    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        CnTbPlan::create($request->all());
        return redirect()->route('admin.planes.index')->with('success', 'Plan creado exitosamente.');
    }

    public function edit($id)
    {
        $plan = CnTbPlan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = CnTbPlan::findOrFail($id);
        $plan->update($request->all());
        return redirect()->route('admin.planes.index')->with('success', 'Plan actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $plan = CnTbPlan::findOrFail($id);
        $plan->delete();
        return redirect()->route('admin.planes.index')->with('success', 'Plan eliminado exitosamente.');
    }
}
