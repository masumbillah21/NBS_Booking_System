<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Service;
use App\Models\Category;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Helper\AppointmentTimeSlot;
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
        
        $provider_id = Auth::user()->provider_id;
        $services = Service::with('category', 'provider')->where('provider_id', $provider_id)->latest()->get();
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

        $timeSlots = AppointmentTimeSlot::slots();
        $cate_id = Provider::select('category_id')->where('status', 1)->first();
        
        $categories = Category::select('id', 'category_name as label')->where('parent_id', $cate_id->category_id)->get();
        return Inertia::render('Backend/Services/Edit', compact('timeSlots', 'categories'));
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
        ]);

        Service::create([
            'service_name' => $request->service_name, 
            'description' => $request->description, 
            'duration' => $request->duration, 
            'price' => $request->price,
            'provider_id' => Auth::user()->provider_id,
            'category_id' => $request->category_id
        ]);    

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
        $timeSlots = AppointmentTimeSlot::slots();
        $cate_id = Provider::select('category_id')->where('status', 1)->first(); 
        $categories = Category::select('id', 'category_name as label')->where('parent_id', $cate_id->category_id)->get();
        return Inertia::render('Backend/Services/Edit', compact('service', 'timeSlots', 'categories'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        if (!Auth::user()->hasPermission('service.update')) {
            abort(403);
        }

        $validation = $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $service->update($validation );
        
        return redirect()->back()->with('success', 'Service updated successfully.');
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
        $service->delete();
        return redirect()->back()->with('success', 'Service deleted successfully.');
    }
}
