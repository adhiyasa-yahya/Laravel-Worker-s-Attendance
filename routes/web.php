<?php

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

Route::get('/absen', 'PageController@index');
Route::get('/employe/find/{nik}', 'PageController@find_nik');
Route::get('/absen/datang/{nik}', 'PageController@absen_in');
Route::get('/absen/pulang/{nik}', 'PageController@absen_out');

Route::get('/presen/find/{nik}', 'PageController@find_absen');
Route::get('/monitoring', 'PageController@monitoring_absen');
Route::get('/fetch_absens', 'PageController@_fetch_absens')->name('datatable.fetch_absen');


