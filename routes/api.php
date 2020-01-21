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

Route::get('user/{id}', 'UserController@show');
Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
Route::post('add', 'UserController@store');
Route::post('reset', 'Auth\ResetPasswordController@reset');
Route::get('detail', 'UserController@details');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::get('password/form', 'Auth\ResetPasswordController@showResetForm');


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('users', 'UserController@index1');
    Route::get('staff', 'UserController@index2');
    Route::get('role/{id}', 'UserController@updateRole');
    Route::get('user/{id}', 'UserController@show');
    Route::put('user/{id}', 'UserController@update');
    Route::delete('user/{id}', 'UserController@destroy');
    Route::put('/password', 'UserController@change');
    Route::get('logout', 'Auth\LoginController@logout');

    Route::post('book', 'BookController@store');
    Route::get('books', 'BookController@index');
    Route::get('date/{date}', 'BookController@date');
    Route::get('book/{id}', 'BookController@show');
    Route::post('availableHours', 'BookController@availableHours');
    Route::delete('book/{plateNum}', 'BookController@delete');
    Route::post('searchcar', 'BookController@search');

    Route::post('service', 'ServiceController@store');
    Route::post('services', 'ServiceController@view');
    Route::post('id', 'ServiceController@id');
    Route::post('update', 'ServiceController@update');
    Route::get('delete/{id}', 'ServiceController@delete');
    //Route::get('search/{plateNum}', 'ServiceController@search');

    Route::post('item', 'ModelIntervalController@store');
    Route::get('list', 'ModelIntervalController@list');
    Route::get('delItems/{id}', 'ModelIntervalController@delItems');
    Route::get('items/{model}', 'ModelIntervalController@items');
    Route::post('search', 'ModelIntervalController@search');
    Route::post('save', 'ModelIntervalController@save');
    Route::get('itemid/{id}', 'ModelIntervalController@itemid');
    Route::post('updateItem', 'ModelIntervalController@updateItem');
    Route::post('updateModel', 'ModelIntervalController@updateModel');
    Route::get('deleteModel/{id}', 'ModelIntervalController@deleteModel');

    Route::post('set', 'ModelIntervalController@storecacheProcess');
    Route::get('get', 'ModelIntervalController@getcacheProcess');
    Route::get('delete', 'ModelIntervalController@deletecacheProcess');
});
