<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->hasPermission('role.view')) {
            abort(403);
        }

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
        if (!Auth::user()->hasPermission('role.create')) {
            abort(403);
        }

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
        if (!Auth::user()->hasPermission('role.create')) {
            abort(403);
        }

        try{
            $request->validate([
                'name' => 'required',
                'permissions' => 'required|array'
            ]);
            
            DB::beginTransaction();
    
            $role = Role::create([
                'name' => $request->name,
            ]);
            

            $role->permissions()->attach($request->permissions);


            DB::commit();
            
            return redirect()->back()->with('success', 'Role created successfully');

        }catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->validator->errors()->all());
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('Role failed to update: ' . $e->getMessage());
        }

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
        if (!Auth::user()->hasPermission('role.update')) {
            abort(403);
        }
        
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
        if (!Auth::user()->hasPermission('role.update')) {
            abort(403);
        }

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
        if (!Auth::user()->hasPermission('role.delete')) {
            abort(403);
        }

        $role->permissions()->detach();
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted successfully');
    }
}
