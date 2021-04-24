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
Route::middleware('auth:api')->post("events", "\App\Http\Controllers\EventController@store");
Route::middleware('auth:api')->put("events/{event}", "\App\Http\Controllers\EventController@update");
Route::middleware('auth:api')->delete("events/{id}", "\App\Http\Controllers\EventController@destroy");

Route::middleware('auth:api')->post("events/{event}/comment", "\App\Http\Controllers\EventController@comment");

Route::middleware('auth:api')->delete("comments/{id}", "\App\Http\Controllers\CommentController@destroy");


Route::get("news", "\App\Http\Controllers\NewsController@index");
Route::get("news/{id}", "\App\Http\Controllers\NewsController@show");
Route::middleware('auth:api')->post("news", "\App\Http\Controllers\NewsController@store");
Route::middleware('auth:api')->put("news/{news}", "\App\Http\Controllers\NewsController@update");
Route::middleware('auth:api')->delete("news/{id}", "\App\Http\Controllers\NewsController@destroy");
Route::middleware('auth:api')->post("news/{news}/comment", "\App\Http\Controllers\NewsController@comment");

