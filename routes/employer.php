<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employer\EmployerController;



Route::prefix('employer')->group(function () {
    
    Route::get('/index', [EmployerController::class, 'index'])->name('company.index');    
    Route::get('/', [EmployerController::class, 'index'])->name('company.index'); 
    Route::get('/dashboard', [EmployerController::class, 'dashboard'])->name('company.dashboard');  


});