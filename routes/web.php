<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProvidersController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return Inertia::render('Frontend/Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});



Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    

    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('settings', SettingController::class);

    //Service Management
    Route::resource('categories', CategoryController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('appointments', AppointmentController::class);
    // Services Provider route
    Route::resource('providers', ProvidersController::class);

    // Appointment for Customer
    Route::resource('customer',CustomerController::class);
    Route::put('/customer/{id}/cancel', [CustomerController::class, 'cancel'])->name('customer.cancel');
    //Route::put('/customer/{id}/reappointment', [CustomerController::class, 'reappointment'])->name('customer.reappointment');

    Route::get('/customer/{id}/reappointment', [CustomerController::class, 'reappointmentCreate'])->name('customer.reappointment.create');

    Route::post('/customer/{id}/reappointment', [CustomerController::class, 'reappointment'])->name('customer.reappointment.store');

    //Route::put('/customer/{id}/reappointment', [CustomerController::class, 'reappointment'])->name('customer.reappointment');

    // Feedback for customer
    Route::get('/feedback/create/{appointment}', [CustomerController::class, 'createFeedback'])->name('feedback.create');
    Route::post('/feedback', [CustomerController::class, 'storeFeedback'])->name('feedback.store');
   
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});


require __DIR__.'/auth.php';
