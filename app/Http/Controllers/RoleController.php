<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$this->authorize('view', Role::class);

        $roles = Role::with('permissions')->get();
    
        return Inertia::render('Backend/Roles/Index', [
            'rolesData' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissionList = Permission::pluck('name', 'id');

        return Inertia::render('Backend/Roles/Edit', [
            'permissionList' => $permissionList
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required',
            'permissions' => 'required|array'
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        $role->permissions()->attach($request->permissions);

        return redirect()->back()->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        
        $permissionList = Permission::pluck('name', 'id');

        return Inertia::render('Backend/Roles/Edit', [
            'role' => $role->load('permissions:id'),
            'permissionList' => $permissionList
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            $validate = $request->validate([
                'name' => 'required',
                'permissions' => 'required|array'
            ]);
        
            DB::beginTransaction();
            
            $role->update($validate);
            $role->permissions()->detach();
            $role->permissions()->attach($request->permissions);
        
            DB::commit();
            return redirect()->back()->with('success', 'Role updated successfully');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->validator->errors()->all());
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('Role failed to update: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->permissions()->detach();
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted successfully');
    }
}
