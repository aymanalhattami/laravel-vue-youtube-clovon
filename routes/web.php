<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('api/users', [\App\Http\Controllers\Admin\UserController::class, 'index']);
Route::post('api/users', [\App\Http\Controllers\Admin\UserController::class, 'store']);
Route::put('api/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update']);

Route::get('{view}', \App\Http\Controllers\ApplicationController::class)->where('view', '(.*)');
