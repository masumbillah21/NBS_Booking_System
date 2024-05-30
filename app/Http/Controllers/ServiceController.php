<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with('category', 'provider')->get();
        return Inertia::render('Services/Index', ['services' => $services]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createOrEdit($id)
    {
        $service = $id ? Service::findOrFail($id) : new Service();
        $categories = Category::all();
        // $providers = ServiceProvider::all(); //When service provider model is added

        return Inertia::render('Services/Form', [
            'service' => $service,
            'categories' => $categories,
            'providers' => [], //$providers
            'isEdit' => $id ? true : false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'servise_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'provider_id' => 'required|exists:service_providers,id',
        ]);

        $service = Service::create($request->all());    

        $service->category()->attach($request->category_id);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*
    public function edit(Service $service, $id)
    {
        $service = Service::findOrFail($id);
        $categories = Category::all();
        // $providers = ServiceProvider::all(); //When service provider model is added
        return Inertia::render('Services/Edit', ['service' => $service, 'categories' => $categories, 'providers' => []]); //$providers]);
    }
    */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'provider_id' => 'required|exists:service_providers,id',
        ]);

        $service = Service::findOrFail($id);
        $service->update($request->all());
        $service->category()->sync($request->category_id);
        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service, $id)
    {
        $service = Service::findOrFail($id);
        $service->category()->detach();
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
