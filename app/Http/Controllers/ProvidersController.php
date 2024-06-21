<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Provider;
use App\Helper\ImageHelper;
use Illuminate\Http\Request;
use App\Helper\GenerateUniqueSlug;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProvidersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->hasPermission('provider.view')) {
            abort(403);
        }
        
        return inertia()->render('Backend/Providers/index', [
            'providerData' => Provider::with('category')->orderBy('id', 'desc')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->hasPermission('provider.create')) {
            abort(403);
        }
        $categories = Category::select('id', 'category_name as label')->where('parent_id', null)->get();
        return inertia()->render('Backend/Providers/Edit', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermission('provider.create')) {
            abort(403);
        }
        
        $request->validate([
            'company_name' => 'required|string|max:256',
            'category_id' => 'required|exists:categories,id',
            'email' => 'required|email|string|max:255',
            'phone_number' => 'required|string',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean'
        ]);

        $image_path = '';
        if ($request->hasFile('logo')) {
            $image_path = $request->file('logo')->store('images', 'public');
        }

        $slug = GenerateUniqueSlug::slug($request->company_name, Provider::class);

        Provider::create([
            'company_name' => $request->company_name,
            'category_id' => $request->category_id,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'description' => $request->description,
            'address' => $request->address,
            'logo' => $image_path,
            'status' => $request->status,
            'slug' => $slug
        ]);

        return redirect()->back()->with('success', 'Services Provider created successfully!');
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
        if (!Auth::user()->hasPermission('provider.update')) {
            abort(403);
        }
        $categories = Category::select('id', 'category_name as label')->where('parent_id', null)->get();
        $providers = Provider::find($id);
        return inertia()->render('Backend/Providers/Edit', compact('categories', 'providers'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request)
    {

        if (!Auth::user()->hasPermission('provider.update')) {
            abort(403);
        }
       
        $serviceProvider = Provider::findOrFail($request->id);
    
        $validated = $request->validate([
            'company_name' => 'required|string|max:256',
            'category_id' => 'required|exists:categories,id',
            'email' => 'required|email|string|max:255',
            'phone_number' => 'required|string',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required'
        ]);
        
        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($serviceProvider->logo);
            $image_path = $request->file('logo')->store('images', 'public');
            $validated['logo'] = $image_path;
        }else{
            unset($validated['logo']);
        }

        if($request->company_name != $serviceProvider->company_name){
            $validated['slug'] = GenerateUniqueSlug::slug($request->company_name, Provider::class);
        }

        $serviceProvider->update($validated);
    
        return redirect()->back()->with('success', 'Services Provider updated successfully!');
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::user()->hasPermission('provider.delete')) {
            abort(403);
        }

        $serviceProvider = Provider::findOrFail($id);
        ImageHelper::imageDelete($serviceProvider->logo);
        $serviceProvider->delete();
        return redirect()->back()->with('success', 'Service Provider deleted successfully!');
    }
    
}
