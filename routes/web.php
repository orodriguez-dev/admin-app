<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\InventoryForecastController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class);
    Route::resource('vendors', VendorController::class);

    // La ruta GET que carga la página Vue/Inertia
    Route::get('inventory/prediction', [InventoryForecastController::class, 'index'])->name('forecast.index');
    // La ruta POST que el formulario Inertia llama
    Route::post('inventory/prediction/calculate', [InventoryForecastController::class, 'calculate'])->name('forecast.calculate');
});

require __DIR__.'/settings.php';
