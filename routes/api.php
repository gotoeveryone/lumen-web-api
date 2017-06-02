<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', 'StatesController@index');
$app->group(['prefix' => 'v1'], function($app) {
    $app->get('/', 'StatesController@index');

    $app->group(['prefix' => 'auth/'], function($app) {
        $app->post('/login', 'AuthController@login');
        $app->delete('/logout', 'AuthController@logout');
    });

    $app->group(['middleware' => 'auth'], function($app) {
        $app->get('/users', 'UsersController@index');
    });
});
