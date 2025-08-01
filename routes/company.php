<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\CompanyController;

Route::prefix('company')->group(function () {
    
    Route::get('/index', [CompanyController::class, 'index'])->name('company.index');    
    Route::get('/', [CompanyController::class, 'index'])->name('company.index'); 
    Route::get('/dashboard', [CompanyController::class, 'dashboard'])->name('company.dashboard');  


});