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

//Generate Application Key
$router->get('/key', function () {
    return str_random(32);
});

$router->group([
    'prefix'    => 'api',
    'namespace' => 'Api',
], function ($router) {

    // localhost:8000/api/user
    $router->group([
        'prefix'     => 'user',
        'middleware' => 'auth',
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

    // localhost:8000/api/role
    $router->group([
        'prefix' => 'role',
    ], function ($router) {
        $router->get('view', 'RoleController@view');
        $router->post('create', 'RoleController@create');
        $router->post('delete', 'RoleController@delete');
        $router->post('update', 'RoleController@update');
    });

    // localhost:8000/api/auth
    $router->group([
        'prefix' => 'auth',
    ], function ($router) {
        $router->post('login', 'AuthController@login');

    });

    //localhost:8000/api/category_mcb
    $router->group([
        'prefix' => 'category_mcb',
    ], function ($router) {
        $router->get('view', 'Category_McbController@view');
        $router->post('create', 'Category_McbController@create');
        $router->post('delete', 'Category_McbController@delete');
        $router->post('update', 'Category_McbController@update');

    });

    //localhost:8000/api/building
    $router->group([
        'prefix' => 'building',
    ], function ($router) {
        $router->get('view', 'BuildingController@view');
        $router->post('create', 'BuildingController@create');
        $router->post('delete', 'BuildingController@delete');
        $router->post('update', 'BuildingController@update');

    });

    //localhost:8000/api/specification_mcb
    $router->group([
        'prefix' => 'specification_mcb',
    ], function ($router) {
        $router->get('view', 'Specification_McbController@view');
        $router->post('create', 'Specification_McbController@create');
        $router->post('delete', 'Specification_McbController@delete');
        $router->post('update', 'Specification_McbController@update');

    });

    // localhost:8000/api/block
    $router->group([
        'prefix' => 'block',
    ], function ($router) {
        $router->get('view', 'BlockController@view');
        $router->post('create', 'BlockController@create');
        $router->post('delete', 'BlockController@delete');
        $router->post('update', 'BlockController@update');
    });

    // localhost:8000/api/mcb
    $router->group([
        'prefix' => 'mcb',
    ], function ($router) {
        $router->get('view', 'McbController@view');
        $router->post('create', 'McbController@create');
        $router->post('delete', 'McbController@delete');
        $router->post('update', 'McbController@update');
    });

    // localhost:8000/api/mcb_transaction
    $router->group([
        'prefix' => 'mcb_transaction',
    ], function ($router) {
        $router->get('view', 'Mcb_TransactionController@view');
        $router->post('create', 'Mcb_TransactionController@create');
        $router->post('delete', 'Mcb_TransactionController@delete');
        $router->post('update', 'Mcb_TransactionController@update');
        $router->get('getMcbTransaction', 'Mcb_TransactionController@getMcbTransaction');
    });

    //localhost:8000/api/tes
    $router->get('/tes', function () {
        return 'Hello from API Tes';
    });

});
