<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Redirect;

class ServicesProvidersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia()->render('Backend/Services-Provider/index', [
            'servicesProvider' => ServiceProvider::latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia()->render('Backend/Services-Provider/edit', [
            'users' => User::select('id', 'name as label')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:256',
            'email' => 'required|email|string|max:255',
            'phone_number' => 'required|string',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'logo' => 'required|image',
            'status' => 'required'
        ]);

        $img = $request->file('logo');
        $t = time();
        $file_name = $img->getClientOriginalName();
        $img_name = "{$t}-{$file_name}";
        $img_url = "uploads/{$img_name}";
        $img->move(public_path('uploads'), $img_name);

        $validated['logo'] = $img_url;

        ServiceProvider::create($validated);

        return redirect()->route('services-provider.index')->with('success', 'Services Provider created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id )
    {
       
        return inertia()->render('Backend/Services-Provider/edit', [
            'serviceProvider' => ServiceProvider::find($id),
            'users' => User::select('id', 'name as label')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:256',
            'email' => 'required|email|string|max:255',
            'phone_number' => 'required|string',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'logo' => 'sometimes|image',
            'status' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $img = $request->file('logo');
            $t = time();
            $file_name = $img->getClientOriginalName();
            $img_name = "{$t}-{$file_name}";
            $img_url = "uploads/{$img_name}";
            $img->move(public_path('uploads'), $img_name);
            $validated['logo'] = $img_url;
        }

        ServiceProvider::find($request->id)->update($validated);

        return redirect()->route('services-provider.index')->with('success', 'Services Provider updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ServiceProvider::find($id)->delete();
        return redirect()->back()->with('success', 'Service Provider deleted successfully!');
    }
}