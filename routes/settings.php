<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
//    Route::redirect('settings', '/settings/profile');
//
//    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//
//    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
//
//    Route::put('settings/password', [PasswordController::class, 'update'])
//        ->middleware('throttle:6,1')
//        ->name('password.update');
//
//    Route::get('settings/appearance', function () {
//        return Inertia::render('settings/Appearance');
//    })->name('appearance');
});
