<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\UserController;

Route::prefix('user')->group(function () {
    
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/login', [UserController::class, 'login'])->name('user.login');
    Route::post('/login_check', [UserController::class, 'login_check'])->name('user.login_check');
    Route::get('/top_menu', [UserController::class, 'top_menu'])->name('user.top_menu');
    

});