<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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




Auth::routes();

// Route::prefix('admin')->namespace('Logged')->middleware('auth')->group(function(){
//     Route::get('/home', 'HomeController@index')->name('home');
//     Route::resource('posts', 'PostController');
// });
// ritorniamo la view home tramite il controller generale
Route::get('/','HomeController@index')->name('home');
Route::resource('/search','SearchController');
Route::resource('/messages','Logged\MessageController');
Route::resource('/host','Logged\HostController');
// Route::get('/prova', function () {
//     return view('logged.messages');
// });
Route::post('/send-message','SendMessageController@store')->name('send.message');
Route::resource('/view','ViewsController');
Route::get('/apartment', function () {
    return view('apartment');
});
Route::get('/test', function () {
    return view('test');
});
Route::get('/search', function () {
    return view('search.search');
});
Route::get('/apartments/{id}/sponsor', 'Logged\HostController@sponsor')->name('logged.sponsor');

Route::get('/apartments', 'Logged\HostController@index');
Route::get('/apartments/{id}/sponsor','Logged\HostController@sponsor')->name('logged.sponsor');
Route::get('/apartments/{id}/pay/{id_sponsor}/{price}','Logged\HostController@pay')->name('logged.pay');
Route::get('/host/prova/{id}','Logged\HostController@edit')->name('prova.sponsor');

Route::post('apartments/{id}', 'Logged\HostController@checkout')->name('logged.checkout');
Route::get('/test', function () {
    return view('test');
});

Route::patch('host/visibility/{id}', 'Logged\HostController@visibility')->name('logged.visibility');



Route::resource('/images','Logged\ImageController');
