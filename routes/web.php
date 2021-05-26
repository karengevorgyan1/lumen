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
$router->get('/key', function() {
    return \Illuminate\Support\Str::random(32);
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    /*project query*/
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/projects', 'ProjectController@index');
        $router->post('/projects', 'ProjectController@store');
        $router->post('/projects/{id}', 'ProjectController@dublicat');
        $router->put('/projects/{id}', 'ProjectController@update');
        $router->put('/projectsName/{id}', 'ProjectController@updateName');
        $router->delete('/projects/{id}', 'ProjectController@destroy');
    });
});


