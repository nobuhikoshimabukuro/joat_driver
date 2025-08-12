<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employer\EmployerController;




Route::get('/index', [EmployerController::class, 'index'])->name('employer.index');    
Route::get('/', [EmployerController::class, 'index'])->name('employer.index'); 
Route::get('/dashboard', [EmployerController::class, 'dashboard'])->name('employer.dashboard');  


