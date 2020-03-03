<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\Controller;
use App\Models\City;
use App\Models\Way;

//Route::apiResource('city', 'api\CityController');
Route::middleware('auth:api')->get('/user',function (Request $request){
    return $request->user();
});

// Resource Points
Route::namespace('api')->name('api.')->group(function(){
    Route::prefix('points')->name('points.')->group(function(){
        Route::post('/', 'PointController@store')->name('create');
        Route::get('/', 'PointController@index')->name('list-all');
        Route::get('/{point}', 'PointController@show')->name('find');
        Route::put('/{point}', 'PointController@update')->name('update');
        Route::post('/{point}/add-neighbor', 'PointController@addNeighbor')->name('add-neighbor');
        Route::delete('/{point}', 'PointController@delete')->name('delete');
    });
    Route::prefix('path')->name('path.')->group(function(){
        Route::get('near/{from}/{to}', 'LineController@near')->name('near');
    });
});