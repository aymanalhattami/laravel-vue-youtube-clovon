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
Route::delete('api/users', [\App\Http\Controllers\Admin\UserController::class, 'bulkDelete']);

Route::get('api/appointments', [\App\Http\Controllers\Admin\AppointmentController::class, 'index']);
Route::post('api/appointments', [\App\Http\Controllers\Admin\AppointmentController::class, 'store']);
Route::get('api/appointments/status', [\App\Http\Controllers\Admin\AppointmentStatusController::class, 'index']);

Route::get('api/clients', [\App\Http\Controllers\Admin\ClientController::class, 'index']);

Route::get('{view}', \App\Http\Controllers\ApplicationController::class)->where('view', '(.*)');
