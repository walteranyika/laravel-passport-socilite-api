<?php

use Illuminate\Http\Request;



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function (){
    Route::get('/restaurants', 'MainApiController@getRestaurants');
    Route::post('/meals', 'MainApiController@getMeals');
    Route::post('/pay/order', 'MainApiController@addOrder');
    Route::get('/orders', 'MainApiController@getLatestOrder');
    Route::get('/driver/location', 'MainApiController@customer_driver_location');

    Route::post('/order/notification', 'MainApiController@restaurant_order_notification');
    Route::get('/ready/orders', 'MainApiController@driver_get_ready_orders');

    Route::post('/pick/order', 'MainApiController@driver_pick_orders');

    Route::get('/get/latest/order', 'MainApiController@driver_get_latest_orders');

    Route::post('/complete/order', 'MainApiController@driver_complete_orders');
    Route::post('/driver/update/location', 'MainApiController@driver_update_location');
    Route::get('/get/revenue', 'MainApiController@driver_get_revenue');
    Route::post('/user/create', 'MainApiController@createUser');

});