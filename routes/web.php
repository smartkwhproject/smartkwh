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
    return 'Hello';
});

$router->group([
    'prefix'    => 'api',
    'namespace' => 'Api',
], function ($router) {

    // localhost:8000/api/user
    $router->group([
        'prefix' => 'user',
    ], function ($router) {
        $router->get('view', 'UserController@view');
        $router->post('create', 'UserController@create');
        $router->post('delete', 'UserController@delete');
        $router->post('update', 'UserController@update');

    });

    // localhost:8000/api/group
    $router->group([
        'prefix' => 'group',
    ], function ($router) {
        $router->get('view', 'GroupController@view');
        $router->post('create', 'GroupController@create');
        $router->post('delete', 'GroupController@delete');
        $router->post('update', 'GroupController@update');

    });

    $router->get('/tes', function () {
        return 'Hello from API Tes';
    });
});
