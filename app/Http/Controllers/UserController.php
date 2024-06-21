<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->hasPermission('user.view')) {
            abort(403);
        }
        
        $users = User::all();
        return Inertia::render('Backend/Users/Index', ['usersData' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->hasPermission('user.create')) {
            abort(403);
        }

        $roles = Role::select('id', 'name as label')->get();
        return Inertia::render('Backend/Users/Edit', ['roles' => $roles]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!Auth::user()->hasPermission('user.create')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => 'required|min:8',
            'role' => 'required|exists:roles,id',
            'designation' => 'string|max:30',
            'status' => 'required|in:1,0'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'designation' => $request->designation,
            'status' => $request->status,
            'role_id' => $request->role
        ]);
        return redirect()->route('users.index');
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
    public function edit(string $id)
    {
        if (!Auth::user()->hasPermission('user.update')) {
            abort(403);
        }

        $roles = Role::select('id', 'name as label')->get();
        $user = User::find($id)->load('roles');
        return Inertia::render('Backend/Users/Edit', ['userData' => $user, 'roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::user()->hasPermission('user.update')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|string|lowercase|email|max:255|'.Rule::unique(User::class)->ignore($id),
            'role' => 'required|exists:roles,id',
            'password' => 'nullable|min:8',
            'designation' => 'string|max:30',
            'status' => 'required|in:1,0'
        ]);
        $user = User::find($id);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'designation' => $request->designation,
            'status' => $request->status,
            'role_id' => $request->role
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::user()->hasPermission('user.delete')) {
            abort(403);
        }
        
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index');
    }
}
