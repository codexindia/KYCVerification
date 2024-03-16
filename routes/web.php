<?php

use App\Http\Controllers\BankManager;
use App\Http\Controllers\CompanyManager;
use App\Http\Controllers\PDFManager;
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
    Route::get('/aadhar_details', 'aadhar_details')->name('aadhar_details');
    Route::post('/aadhar_details/SendOTP', 'aadhar_otp')->name('aadhar_otp');
    Route::get('/Aadhar/Validate_otp/{pagerec}', 'aadhar_validate_otp')->name('aadhar_validate_otp');
    Route::post('/Aadhar/Validate_otp', 'aadhar_otp_submit')->name('aadhar_otp_submit');
});
Route::controller(BankManager::class)->prefix('step-3')->group(function () {
    Route::get('/bank_data', 'bank_data')->name('bank_data_page');
    Route::post('/bank_data', 'bank_data_submit')->name('bank_data_submit');
});
Route::controller(BankManager::class)->prefix('step-4')->group(function () {
    Route::get('/final', 'final_page')->name('final_page');
  
});
Route::controller(CompanyManager::class)->prefix('step-2')->group(function () {
    Route::get('/company_details', 'company_details')->name('company_details');
    Route::post('/company_details/submit', 'company_details_submit')->name('company_details_submit');
});
Route::controller(PDFManager::class)->prefix('pdf')->group(function () {
    Route::get('/download/ackw', 'download_ackw')->name('download_ackw');
    Route::get('/download/officeCopy/{id}', 'download_officeCopy')->name('download_officeCopy');
});