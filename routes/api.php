<?php

use Illuminate\Http\Request;

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

Route::get('/', 'StatesController@index');

Route::group(['prefix' => 'auth/'], function() {
    Route::post('/login', 'UsersController@login');
    Route::delete('/logout', 'UsersController@logout');
});

Route::middleware('hastoken')->get('/users/{id}', 'UsersController@users');
