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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::any('/logout', 'Auth\LoginController@logout');
Route::get('/set-pin', 'Auth\RegisterController@showSetPinForm');
Route::post('/set-pin', 'Auth\RegisterController@setPin');
Route::get('/reset-pin', 'Auth\RegisterController@showResetPinForm');
Route::post('/reset-pin', 'Auth\RegisterController@resetPin');

Route::post('states','HomeController@fetchStates');
Route::post('cities','HomeController@fetchCity');
 Route::post('union-council','HomeController@fetchUnionCouncils');
Route::POST('send-code', 'HomeController@verificationCode');

Route::group(['middleware'=>'auth'], function(){
    Route::get('/', 'HomeController@index');
    Route::get('/dashboard', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
});


// Route::group(['prefix'=>'tokens','middleware'=>'auth'], function(){
//     Route::get('/', 'TokenController@index');
//     Route::get('/create', 'TokenController@create');
//     Route::post('/store', 'TokenController@store');
//     Route::get('/activate/{id}', 'TokenController@activate');
// });


Route::group(['prefix'=>'donors','middleware'=>'auth'], function(){
    //Route::get('/', 'DonorsController@index');
    Route::get('/edit/{id}', 'DonorsController@edit');
    Route::post('/update/{id}', 'DonorsController@update');
});


Route::group(['prefix'=>'requests','middleware'=>'auth'], function(){
    Route::get('/', 'RequestController@index');
    Route::get('/create', 'RequestController@create');
    Route::post('/store', 'RequestController@store');
    Route::get('/edit/{id}', 'RequestController@edit');
    Route::post('/update/{id}', 'RequestController@update');
});