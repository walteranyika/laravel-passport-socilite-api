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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/restaurants', 'RestaurantController@show_restaurants')->name('restaurants');

Route::get('/remove/{meal}', 'RestaurantController@remove_meal')->name('remove');
Route::get('/restaurant/{restaurant}', 'RestaurantController@show_meals')->name('details');

Route::get('/restaurant', 'RestaurantController@add_hotel')->name('add-restaurant');
Route::post('/restaurant', 'RestaurantController@save_hotel')->name('save-restaurant');

Route::get('/meal', 'RestaurantController@add_meal')->name('add-meal');
Route::post('/meal', 'RestaurantController@save_meal')->name('save-meal');