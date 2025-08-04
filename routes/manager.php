<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Manager\ManagerController;

use App\Http\Controllers\Manager\Master\MEmployerController;
use App\Http\Controllers\Manager\Master\MEmployerUserController;



Route::prefix('manager')->group(function () {
    
    Route::get('/index', [ManagerController::class, 'index'])->name('manager.index');
    Route::get('/', [ManagerController::class, 'index'])->name('manager.index'); 
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');  


    Route::get('/master/m_employer', [MEmployerController::class, 'index'])->name('manager.master.m_employer');
    Route::get('/master/m_employer/entry', [MEmployerController::class, 'entry'])->name('manager.master.m_employer.entry');


    Route::get('/master/m_employer_user', [MEmployerUserController::class, 'index'])->name('manager.master.m_employer_user');
    
});