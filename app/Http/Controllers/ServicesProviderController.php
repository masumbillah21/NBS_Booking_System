<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class ServicesProviderController extends Controller
{
    function index(){
        return inertia()->render('Backend/Services-Provider/index',[
            'servicesProvider'=>ServiceProvider::latest()->get()
        ]);
    }

    function create(){
        return inertia()->render('Backend/Services-Provider/create',[
            'users'=>User::all()
        ]);
    }

    function store(Request $request){
      

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
        return Redirect::route('servicesProvider.index');
    }




    

    function destroy(ServiceProvider $serviceProvider){
        $serviceProvider->delete();
        return Redirect::route('servicesProvider.index');
    
       }

    
}
