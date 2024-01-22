<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
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

Route::post('/users/authenticate', [UserController::class , 'authenticate']);
Route::get('/', [NotificationController::class , 'index'])->middleware('auth');
Route::get('/login', [UserController::class , 'login'])->name('login')->middleware('guest');
Route::get('/logout', [UserController::class, 'logout'])->middleware('auth');

Route::get('/account/forgot-password', [UserController::class , 'showForgotPassword']);
Route::post('/account/reset-password/init', [UserController::class , 'initPasswordReset']);
Route::get('/account/reset-password/{key}', [UserController::class , 'passwordReset']);
Route::post('/account/reset-password', [UserController::class , 'completePasswordReset']);
