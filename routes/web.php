<?php

use App\Http\Controllers\Frontend\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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



//Admin User Auth
Route::get('admin/login','Auth\AdminLoginController@showLoginForm');
Route::post('admin/login','Auth\AdminLoginController@login')-> name('admin.login');
Route::post('admin/logout','Auth\AdminLoginController@logout')-> name('admin.logout');



//User Auth
Auth::routes();

Route::middleware('auth')->namespace('Frontend')->group(function(){
    Route::get('/' ,'PageController@home')->name('home');

    Route::get('/profile', 'PageController@profile')->name('profile');


    Route::get('/update-password','PageController@updatePassword')->name('update-password');
    Route::post('/update-password', 'PageController@updatePasswordStore')->name('update-password.store');

    Route::get('/wallet','pageController@wallet')->name('wallet');

    Route::get('/transfer','pageController@transfer')-> name('transfer');
    Route::get('/transfer/confirm','pageController@transferConfirm')-> name('transferConfirm');
    Route::post('/transfer/complete','pageController@transferComplete')-> name('transferComplete');

    Route::get('/transaction', 'PageController@transaction')->name('transaction');
    Route::get('/transaction/{trx_id}', 'PageController@transactionDetail')->name('transactionDetail');

    Route::get('/to-account-verify','pageController@toAccountVerify');
    Route::get('/password-check','pageController@passwordCheck');
    Route::get('/transfer-hash','pageController@transferHash');

    Route::get('/receive-qr','pageController@receiveQr');
    Route::get('/scan-and-pay','pageController@scanAndPay');

    
});

