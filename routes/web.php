<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get('/login', function () {
    return view('auth/login');
});

Route::get('/register', function () {
    return view('auth/signup');
});

Route::get('/profile', function () {

    if(!session()->has('data')){
        return view('welcome');
    }
    else{
        return view('profile');
    }

});


Route::get('/logout', function () {

    session()->forget('data');
    return view('welcome');
});


Route::get('/sendEmail', function () {
    return view('auth/security/sendEmail');
});


Route::post('/auth', [AuthController::class, 'authenticate'])->name('auth.biometric');

Route::post('/auth/signup', [AuthController::class, 'signup'])->name('auth.signup');

Route::post('/sendOTP', [AuthController::class, 'sendOTP'])->name('auth.otpSend');

Route::post('/validateOTP', [AuthController::class, 'sendOTP'])->name('auth.otpValidate');

