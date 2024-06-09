<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->hasPermission('setting.view')) {
            abort(403);
        }

        $general = Setting::select('name', 'value', 'tab_name')->where('tab_name', 'general')->get();
        $payment = Setting::select('name', 'value', 'tab_name')->where('tab_name', 'payment')->get();
        $roles = Role::select('id', 'name as label')->get();
        return Inertia::render('Backend/Settings/Index', ['general' => $general, 'payment' => $payment, 'roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if($request->tab_name == 'general'){
            $request->validate([
                'tab_name' => 'required|string|max:30',
                'site_title' => 'required|string|max:30',
                'stie_tagline' => 'required|string|max:30',
                'dark_logo' => 'required | image | max:2048',
                'light_logo' => 'required | image | max:2048',
                'site_favicon' => 'required | image | max:2048',
                'default_role' => 'required | exists:roles,id',
                'date_format' => 'required',
            ]);
        }
        
        $tab_name = $request->tab_name;

        unset($request['tab_name']);

        foreach ($request->all() as $key => $value) {

            $setting = Setting::firstOrNew(['name' => $key]);

            if($key == 'dark_logo'){
                if ($request->hasFile('dark_logo')) {
                    $setting->value = $request->file('dark_logo')->store('images', 'public');

                    $oldLogo = Setting::where('name', 'dark_logo')->first();
                    if($oldLogo){
                        Storage::disk('public')->delete($oldLogo->value);
                    }
                    
                }
            }else if(trim($key) == 'light_logo'){
                if ($request->hasFile('light_logo')) {
                    $oldFav = Setting::where('name', 'light_logo')->first();
                    if($oldFav){
                        Storage::disk('public')->delete($oldFav->value);
                    }
                    $setting->value = $request->file('light_logo')->store('images', 'public');
                }
            }else if(trim($key) == 'site_favicon'){
                if ($request->hasFile('site_favicon')) {
                    $oldFav = Setting::where('name', 'site_favicon')->first();
                    if($oldFav){
                        Storage::disk('public')->delete($oldFav->value);
                    }
                    $setting->value = $request->file('site_favicon')->store('images', 'public');
                }
            }
            else{
                $setting->value = $value;
            }

            if($setting->value){
                $setting->tab_name = $tab_name;
                $setting->save();
            }
        }

        return redirect()->back()->with('success', 'Setting saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
