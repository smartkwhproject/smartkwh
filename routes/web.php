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
    return 'Welcome to Localhost Default Page from Smartpower Backend Devedit in visual code!';
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
        'prefix' => 'user',
    ], function ($router) {
        $router->get('view', 'UserController@view');
        $router->post('create', 'UserController@create');
        $router->post('delete', 'UserController@delete');
        $router->post('update', 'UserController@update');
        //$router->get('kmean', 'UserController@kmeans');
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

    // localhost:8000/api/dashboard
    $router->group([
        'prefix' => 'dashboard',
    ], function ($router) {
        $router->get('dashboard', 'DashboardController@dashboard');
        //$router->get('history', 'DashboardController@history');
        $router->post('history', 'DashboardController@history');
    });

    //localhost:8000/api/gedung
    $router->group([
        'prefix' => 'gedung',
    ], function ($router) {
        $router->get('view', 'GedungController@view');
        $router->post('create', 'GedungController@create');
        $router->post('delete', 'GedungController@delete');
        $router->post('update', 'GedungController@update');

    });

    // localhost:8000/api/block
    $router->group([
        'prefix' => 'blok',
    ], function ($router) {
        $router->get('view', 'BlokController@view');
        $router->post('create', 'BlokController@create');
        $router->post('delete', 'BlokController@delete');
        $router->post('update', 'BlokController@update');
        $router->get('getBlockByBuildingId/{buildingId}', 'BlokController@getBlockByBuildingId');
    });

    // localhost:8000/api/transaksi_mcb
    $router->group([
        'prefix' => 'transaksi_mcb',
    ], function ($router) {
        $router->get('viewall', 'TransaksiMcbController@viewall');
        $router->get('viewpage', 'TransaksiMcbController@viewpage');
        $router->post('create', 'TransaksiMcbController@create');
        $router->post('delete', 'TransaksiMcbController@delete');
        $router->post('update', 'TransaksiMcbController@update');
        // $router->get('caracteristic', 'TransaksiMcbController@caracteristic');
        // $router->get('statistic', 'TransaksiMcbController@generateStatisticData');
    });

    $router->group([
        'prefix' => 'kmeans',
    ], function ($router) {
        $router->get('dataSets', 'KmeansController@dataSetKMean');
        // $router->get('caracteristic', 'TransaksiMcbController@caracteristic');
        // $router->get('statistic', 'TransaksiMcbController@generateStatisticData');
    });
    $router->group([
        'prefix' => 'tempfinal',
    ], function ($router) {
        $router->get('view', 'TempFinalController@view');
        $router->post('create', 'TempFinalController@create');
    });

    //localhost:8000/api/tes
    $router->get('/tes', function () {
        return 'Hello from API Tes';
    });

});
