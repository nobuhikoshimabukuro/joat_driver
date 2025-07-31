<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\user\UserController;

Route::prefix('user')->group(function () {
    
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    

});