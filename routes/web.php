<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');

$router->group(['prefix' => 'photos'], function () use ($router){
    $router->get('/', 'PhotosController@index');
    $router->get('/{id}', 'PhotosController@detail');
    $router->group(['middleware' => 'auth'], function () use ($router)
    {
        $router->post('/', 'PhotosController@uploadPhotos');
        $router->put('/{id}', 'PhotosController@updatePhotos');
        $router->post('/{id}/like', 'PhotosController@likePhotos');
        $router->post('/{id}/unlike', 'PhotosController@unlikePhotos');
    });
});
