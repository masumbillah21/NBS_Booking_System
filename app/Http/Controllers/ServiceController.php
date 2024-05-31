<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with('category')->get();
        return Inertia::render('Backend/Services/Index', ['services' => $services]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();
        $categories = Category::select('id', 'category_name as label')->get();
        $providers = [['id' => 1, 'label' => 'No provider'], ['id' => 2, 'label' => 'No provider']]; //When service provider model is added
        return Inertia::render('Backend/Services/Edit', ['services' => $services, 'categories' => $categories, 'providers' => $providers]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'provider_id' => 'required',
        ]);

        $service = Service::create(['service_name' => $request->service_name, 'description' => $request->description, 'duration' => $request->duration, 'price' => $request->price]);    

        $service->category()->attach($request->category_id);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return Inertia::render('Backend/Services/Edit', ['service'=> $service]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id)->with('category')->first();
        $categories = Category::select('id', 'category_name as label')->get();
        $providers = [['id' => 1, 'label' => 'No provider'], ['id' => 2, 'label' => 'No provider']]; //When service provider model is added
        return Inertia::render('Backend/Services/Edit', ['service' => $service, 'categories' => $categories, 'providers' => $providers]);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'provider_id' => 'required',
        ]);

        $service->update(['service_name' => $request->service_name, 'description' => $request->description, 'duration' => $request->duration, 'price' => $request->price]);
        $service->category()->sync($request->category_id);
        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->category()->detach();
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
