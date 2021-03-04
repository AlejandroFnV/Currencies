<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\AjaxMonedaController;
use App\Http\Controllers\AjaxController;

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

Route::get('backend', [BackendController::class, 'main'])->name('backend.main');
Route::resource('backend/currency', MonedaController::class, ['names' => 'backend.currency']);

//AJAX
Route::get('moneda', [AjaxController::class, 'moneda'])->name('moneda');
Route::resource('ajaxmoneda', AjaxMonedaController::class, ['names' => 'ajaxmoneda']);