<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Service;
use App\Models\Category;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->hasPermission('service.view')) {
            abort(403);
        }
        
        $services = Service::with('category')->get();
        return Inertia::render('Backend/Services/Index', ['services' => $services]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->hasPermission('service.create')) {
            abort(403);
        }

        $services = Service::all();
        $categories = Category::select('id', 'category_name as label')->get();
        $providers = Provider::select('id', 'company_name as label')->where('status', 1)->get();
        return Inertia::render('Backend/Services/Edit', ['services' => $services, 'categories' => $categories, 'providers' => $providers]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermission('service.create')) {
            abort(403);
        }

        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'provider_id' => 'required|exists:providers,id',
        ]);

        $service = Service::create([
            'service_name' => $request->service_name, 
            'description' => $request->description, 
            'duration' => $request->duration, 
            'price' => $request->price,
            'provider_id' => $request->provider_id,
        ]);    

        $service->category()->attach($request->category_id);
        // $service->provider()->attach($request->provider_id);

        return redirect()->back()->with('success', 'Service created successfully.');
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
    public function edit($id)
    {
        if (!Auth::user()->hasPermission('service.update')) {
            abort(403);
        }

        $service = Service::findOrFail($id)->with('category')->first();
        $categories = Category::select('id', 'category_name as label')->get();
        $providers = Provider::select('id', 'company_name as label')->where('status', 1)->get();
        return Inertia::render('Backend/Services/Edit', ['service' => $service, 'categories' => $categories, 'providers' => $providers]);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        if (!Auth::user()->hasPermission('service.update')) {
            abort(403);
        }

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
        if (!Auth::user()->hasPermission('service.delete')) {
            abort(403);
        }

        $service = Service::findOrFail($id);
        $service->category()->detach();
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
