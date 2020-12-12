<?php

use App\Http\Controllers\PartnerInformationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartnerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::resource('partner', PartnerController::class)->only(['index', 'create', 'store']);
    Route::resource('partner-information', PartnerInformationController::class)->only(['index', 'create', 'store']);
});


require __DIR__ . '/auth.php';
