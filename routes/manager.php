<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Manager\Master\MEmployerController;
use App\Http\Controllers\Manager\Master\MEmployerUserController;
use App\Http\Controllers\Manager\Master\MLicenseController;
use App\Http\Controllers\Manager\Master\MAddressController;


Route::get('/test', [ManagerController::class, 'test'])->name('manager.test');
Route::post('/excel_upload', [ManagerController::class, 'excel_upload'])->name('manager.excel_upload');


Route::get('/', [ManagerController::class, 'index'])->name('manager.index');
Route::get('/index', [ManagerController::class, 'index'])->name('manager.index');

Route::get('/login', [ManagerController::class, 'login'])->name('manager.login');
Route::get('/logout', [ManagerController::class, 'logout'])->name('manager.logout');
Route::post('/login_check', [ManagerController::class, 'login_check'])->name('manager.login_check');

// ダッシュボード
Route::get('/dashboard', [ManagerController::class, 'dashboard'])
    ->name('manager.dashboard')
    ->middleware('manager.auth');

// マスタ管理（事業者）
Route::get('/master/m_employer', [MEmployerController::class, 'index'])
    ->name('manager.master.m_employer')
    ->middleware('manager.auth');

Route::get('/master/m_employer/entry', [MEmployerController::class, 'entry'])
    ->name('manager.master.m_employer.entry')
    ->middleware('manager.auth');

Route::post('/master/m_employer/save', [MEmployerController::class, 'save'])
    ->name('manager.master.m_employer.save');

Route::post('/master/m_employer/delete', [MEmployerController::class, 'delete'])
    ->name('manager.master.m_employer.delete');  
    

// マスタ管理（資格免許）
Route::get('/master/m_license', [MLicenseController::class, 'index'])
    ->name('manager.master.m_license')
    ->middleware('manager.auth');

Route::get('/master/m_license/entry', [MLicenseController::class, 'entry'])
    ->name('manager.master.m_license.entry')
    ->middleware('manager.auth');

Route::post('/master/m_license/save', [MLicenseController::class, 'save'])
    ->name('manager.master.m_license.save');    

Route::post('/master/m_license/delete', [MLicenseController::class, 'delete'])
    ->name('manager.master.m_license.delete');  
    
    
    
// マスタ管理（住所）
Route::get('/master/m_address', [MAddressController::class, 'index'])
->name('manager.master.m_address')
->middleware('manager.auth');

Route::post('/master/m_address/save', [MAddressController::class, 'save'])
->name('manager.master.m_address.save');    





// マスタ管理（事業者ユーザー）
Route::get('/master/m_employer_user', [MEmployerUserController::class, 'index'])
    ->name('manager.master.m_employer_user')
    ->middleware('manager.auth');
