<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (!Auth::user()->hasPermission('permission.view')) {
            abort(403);
        }

        $permissions = Permission::orderBy('id', 'desc')->get();
        return Inertia::render('Backend/Permissions/Index', ['permissionsData' => $permissions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->hasPermission('permission.create')) {
            abort(403);
        }

        return Inertia::render('Backend/Permissions/Edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermission('permission.create')) {
            abort(403);
        }

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

        if (!Auth::user()->hasPermission('permission.update')) {
            abort(403);
        }

        return Inertia::render('Backend/Permissions/Edit', ['permission' => $permission]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {

        if (!Auth::user()->hasPermission('permission.update')) {
            abort(403);
        }

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

        if (!Auth::user()->hasPermission('permission.delete')) {
            abort(403);
        }

        $permission->delete();
        return redirect()->back()->with('success', 'Permission deleted successfully!');
    }
}
