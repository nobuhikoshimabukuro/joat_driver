<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\driver\DriverController;

Route::prefix('driver')->group(function () {
    
    Route::get('/', [DriverController::class, 'index'])->name('driver.index');
    Route::get('/login', [DriverController::class, 'login'])->name('driver.login');
    Route::post('/login_check', [DriverController::class, 'login_check'])->name('driver.login_check');
    Route::get('/top_menu', [DriverController::class, 'top_menu'])->name('driver.top_menu');


});