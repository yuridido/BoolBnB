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
Route::apiResource('apartments','API\ApartmentController');
Route::get('images','API\GetImagesController@index')->name('images.api');
Route::get('services','API\GetServices@index')->name('services.api');
Route::get('services/all','API\GetServices@getAll')->name('services.api');
Route::get('stats','API\StatsController@index')->name('stats.api');
Route::get('unread','API\StatsController@unreadMessages');

