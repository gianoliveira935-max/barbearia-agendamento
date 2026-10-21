<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
   
    public function index()
    {
        $services = Service::orderBy('name')->get();
        return view('services.index', compact('services'));
    }

   
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'price' => 'required|numeric|min:0',
        ]);

        Service::create([
            'name' => $data['name'],
            'duration_minutes' => $data['duration_minutes'],
            'price_cents' => (int) round($data['price'] * 100), // reais -> centavos
        ]);

        return redirect()->route('services.index')->with('success', 'Serviço cadastrado!');
    }

   
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Serviço excluído!');
    }
}