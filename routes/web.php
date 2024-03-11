<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KYCManager;
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
    return view('basic');
});
Route::controller(KYCManager::class)->group(function () {
    Route::post('/submit/1', 'basic_submit')->name('basic_submit');
});
Route::controller(KYCManager::class)->prefix('OTP')->group(function () {
    Route::get('/Validate/{phone_number}', 'otp_page')->name('otp_page');
    Route::post('/Validate/submit', 'otp_validate')->name('otp_validate');
});
Route::controller(KYCManager::class)->prefix('step-2')->group(function () {
    Route::get('/user_detailes', 'user_detailes')->name('user_detailes');
  //  Route::post('/Validate/submit', 'otp_validate')->name('otp_validate');
});
