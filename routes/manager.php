<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Manager\Master\MCompanyController;

Route::prefix('manager')->group(function () {
    
    Route::get('/', [ManagerController::class, 'index'])->name('manager.index');
    

});

Route::prefix('manager')->group(function () {
    
    Route::get('/index', [ManagerController::class, 'index'])->name('manager.index');
    Route::get('/', [ManagerController::class, 'index'])->name('manager.index'); 
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');  


    Route::get('/master/m_company', [MCompanyController::class, 'index'])->name('manager.master.m_company');


});