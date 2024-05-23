<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $permissions = Permission::orderBy('id', 'desc')->get();
        return Inertia::render('Backend/Permissions/Index', ['permissionsData' => $permissions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('Backend/Permissions/Edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|unique:permissions',
            'permission' => 'required|unique:permissions',
        ]);
        Permission::create($validated);
        return redirect()->back()->with('success', 'Permission created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {

        return Inertia::render('Backend/Permissions/Edit', ['permission' => $permission]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {

        $validated = $request->validate([
            'name' => 'required|'.Rule::unique(Permission::class)->ignore($permission->id),
            'permission' => 'required|'.Rule::unique(Permission::class)->ignore($permission->id),
        ]);

        $permission->update($validated);

        return redirect()->back()->with('success', 'Permission updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {

        $permission->delete();
        return redirect()->back()->with('success', 'Permission deleted successfully!');
    }
}
