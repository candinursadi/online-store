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

$router->group(['prefix' => 'product'], function () use ($router) {
    $router->get('/', ['uses' => 'ProductController@get_data', "as" => 'product.data.get']);
});

$router->group(['middleware' => ['user','log']], function () use ($router) {
    $router->group(['prefix' => 'cart'], function () use ($router) {
        $router->post('/', ['uses' => 'CartController@get_data', "as" => 'cart.data.get']);
        $router->post('/add', ['uses' => 'CartController@add_data', "as" => 'cart.data.add']);
        $router->post('/payment', ['uses' => 'CartController@payment', "as" => 'cart.payment']);
    });
});