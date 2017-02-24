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

Route::resource('makers', 'MakerController',['except'=>['create','edit']]);
Route::resource('vehicles', 'VehicleController',['only'=>['index','show']]);
Route::resource('makers.vehicles', 'MakerVehiclesController',['except'=>['edit','create']]);
