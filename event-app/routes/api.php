<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("events", "\App\Http\Controllers\EventController@index");
Route::get("events/{id}", "\App\Http\Controllers\EventController@show");
Route::delete("events/{id}", "\App\Http\Controllers\EventController@destroy");


Route::get("news", "\App\Http\Controllers\NewsController@index");
Route::get("news/{id}", "\App\Http\Controllers\NewsController@show");
Route::middleware('auth:api')->post("news", "\App\Http\Controllers\NewsController@store");
Route::middleware('auth:api')->put("news/{id}", "\App\Http\Controllers\NewsController@show");
Route::middleware('auth:api')->delete("news/{id}", "\App\Http\Controllers\NewsController@destroy");
