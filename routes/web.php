<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
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
// Route::get('/payments', [PaymentController::class , 'index'])->middleware('auth');
Route::get('/', [MemberController::class , 'index'])->middleware('auth');
Route::get('/transactions', [MemberController::class , 'membersTransactions'])->middleware('auth');
Route::get('/login', [UserController::class , 'login'])->name('login')->middleware('guest');
Route::get('/logout', [UserController::class, 'logout'])->middleware('auth');

Route::get('/account/forgot-password', [UserController::class , 'showForgotPassword']);
Route::post('/account/reset-password/init', [UserController::class , 'initPasswordReset']);
Route::get('/account/reset-password/{key}', [UserController::class , 'passwordReset']);
Route::post('/account/reset-password', [UserController::class , 'completePasswordReset']);


Route::post('/account/reset-password', [UserController::class , 'completePasswordReset']);
Route::get('/account/profile', [UserController::class , 'showProfile'])->middleware('auth');
Route::put('/account/profile/{user}', [UserController::class , 'updateProfile'])->middleware('auth');
Route::get('/account/profile/edit', [UserController::class , 'editProfile'])->middleware('auth');
Route::put('/account/profile/change-password/{user}', [UserController::class , 'updatePassword'])->middleware('auth');
Route::get('/account/profile/change-password', [UserController::class , 'changePassword'])->middleware('auth');
