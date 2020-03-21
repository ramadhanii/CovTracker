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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(["prefix" => "user"], function () use ($router) {
    $router->post('register',   "UserController@register");
    $router->post('suspect',    "UserController@suspect");
    $router->post('pass',       "UserController@pass");
});

$router->post('update/fcm',     "UserController@fcmUpdate");

$router->group(["prefix" => "auth"], function () use ($router) {
    $router->get('/',       "AuthController@session");
    $router->post('login',  "AuthController@login");
});

