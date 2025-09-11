<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\Manager\Master\MLicenseController;

use App\Original\CommonAjax;

Route::get('/', [WebController::class, 'index'])->name('web.index');

// 住所から郵便番号取得
Route::post('/search_postal_code_for_address', [CommonAjax::class, 'SearchPostalCodeForAddress'])->name('search_postal_code_for_address');
// 郵便番号から住所取得
Route::post('/search_address_for_postal_code', [CommonAjax::class, 'SearchAddressForPostalCode'])->name('search_address_for_postal_code');

