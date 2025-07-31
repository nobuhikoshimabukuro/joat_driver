<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\manager\ManagerController;

Route::prefix('manager')->group(function () {
    
    Route::get('/', [ManagerController::class, 'index'])->name('manager.index');
    

});