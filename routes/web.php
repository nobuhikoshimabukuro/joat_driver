<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\WebController;

use App\Http\Controllers\Manager\Master\MLicenseController;


use App\Original\CommonAjax;

require __DIR__.'/employer.php';
require __DIR__.'/user.php';
require __DIR__.'/manager.php';


Route::get('/', [WebController::class, 'index'])->name('web.index');

Route::get('/test', [MLicenseController::class, 'index'])->name('manager.master.m_employer');


Route::get('/m', function () {
    return redirect(route('manager.index'));
});

Route::get('/e', function () {
    return redirect(route('employer.index'));
});

Route::get('/u', function () {
    return redirect(route('user.index'));
});






// 住所から郵便番号取得
Route::post('/search_postal_code_for_address', [CommonAjax::class, 'SearchPostalCodeForAddress'])->name('search_postal_code_for_address');
// 郵便番号から住所取得
Route::post('/search_address_for_postal_code', [CommonAjax::class, 'SearchAddressForPostalCode'])->name('search_address_for_postal_code');