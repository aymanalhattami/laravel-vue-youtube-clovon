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
Route::get('api/users/search', [\App\Http\Controllers\Admin\UserController::class, 'search']);
Route::post('api/users', [\App\Http\Controllers\Admin\UserController::class, 'store']);
Route::put('api/users/{user}/change-role', [\App\Http\Controllers\Admin\UserController::class, 'changeRole']);
Route::put('api/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update']);
Route::delete('api/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'delete']);

Route::get('{view}', \App\Http\Controllers\ApplicationController::class)->where('view', '(.*)');
