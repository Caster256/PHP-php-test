<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/account', 'AccountController@index');
Route::post('/account/edit', 'AccountController@edit');
Route::delete('/account/delData', 'AccountController@delete');
Route::get('/account/export/{file_name}', 'AccountController@download');
Route::post('/account/export', 'AccountController@export');
