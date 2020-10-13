<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/', [UserController::class, 'index']);
Route::match(['get', 'post'],'/login', [UserController::class, 'logIn']);
Route::get('/logout', [UserController::class, 'logOut']);
Route::match(['get', 'post'],'/signup', [UserController::class, 'signUp']);
Route::get('/welcome', function () {
    return view('welcome');
});
