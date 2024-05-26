<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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


    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

//Service Management
Route::middleware(['auth', 'role:admin,service_provider'])->group(function () {
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/form/{id?}', [CategoryController::class, 'createOrEdit'])->name('categories.form');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    // Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::middleware(['auth', 'role:admin,service_provider'])->group(function () {
    Route::get('services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('services/form/{id?}', [ServiceController::class, 'createOrEdit'])->name('services.form');
    Route::post('services', [ServiceController::class, 'store'])->name('services.store');
    // Route::get('services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('services/{id}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
});


require __DIR__.'/auth.php';
