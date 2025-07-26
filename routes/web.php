<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('packages', PackageController::class)
    ->middleware(['auth', 'verified']);

Route::resource('clients', ClientController::class)
    ->middleware(['auth', 'verified']);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
