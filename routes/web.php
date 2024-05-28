<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicesProviderController;
use App\Http\Controllers\ServicesProvidersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Frontend/Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Backend/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/calender', function(){
        return Inertia::render('CalendarView', [
            'masum' => 'Test Data'
        ]);
    })->name('calender');


    // Services Provider route
    Route::resource('/services-provider',ServicesProvidersController::class);


    // Route::get('services-provider',[ServicesProviderController::class,'index'])->name('servicesProvider.index');
    // Route::get('services-provider/create',[ServicesProviderController::class,'create'])->name('servicesProvider.create');
    // Route::post('services-provider',[ServicesProviderController::class,'store']);
    // Route::delete('/services-provider/{servicesProvider}',[ServicesProviderController::class,'destroy']);



    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__.'/auth.php';
