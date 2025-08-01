<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\WebController;

require __DIR__.'/company.php';
require __DIR__.'/user.php';
require __DIR__.'/manager.php';



Route::get('/', [WebController::class, 'index'])->name('web.index');