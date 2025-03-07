<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIsController;
use App\Http\Controllers\GoogleAnalyticsController;

use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'home');

Route::controller(APIsController::class)
    ->prefix('APIs')
    ->name('APIs.')
    ->group(function(){

    Route::get('/','index')
        ->name('index');

    Route::get('/create','create')
        ->name('create');

    Route::post('/store','store')
        ->name('store');

    Route::get('/{api}','show')
        ->name('show');


    Route::get('/{api}/edit','edit')
        ->name('edit');

    Route::patch('/{api}','update')
        ->name('update');

    Route::delete('/{api}','destroy')
        ->name('destroy');
    Route::get('/{id}/updateStatus', 'updateStatus')
        ->name('updateStatus');

});

Route::get('/google-analytics', [GoogleAnalyticsController::class, 'index']);

Route::resource('APIs', APIsController::class);
Route::resource('users', UserController::class);