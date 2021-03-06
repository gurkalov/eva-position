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

$router->group([
    'prefix' => 'api/v1',
    'middleware' => ['auth']
], function () use ($router) {
    $router->post('positions', 'PositionController@create');
    $router->post('audios', 'AudioController@create');
    $router->get('profile', 'ProfileController@read');
    $router->put('profile', 'ProfileController@update');
});

$router->group([
    'prefix' => 'api/v1',
    'middleware' => ['auth', 'admin']
], function () use ($router) {
    /**
     * CRUD Routes
     */
    foreach ([
        'shops' => 'ShopController',
        'users' => 'UserController',
        'items' => 'ItemController',
        'routers' => 'RouterController',
        'beacons' => 'BeaconController',
        'locations' => 'LocationController',
    ] as $path => $controller) {
        $router->post($path, $controller . '@create');
        $router->put($path . '/{id}', $controller . '@update');
        $router->delete($path . '/{id}', $controller . '@delete');
    }
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->get('check', function () use ($router) {
        return 'check';
    });

    $router->get('version', function () use ($router) {
        $versionPath = base_path() . '/public/version.txt';
        if (!file_exists($versionPath)) {
            return '';
        }

        return file_get_contents($versionPath);
    });

    $router->get('paths', 'PathController@index');

    $router->get('positions', 'PositionController@index');
    $router->get('positions/{id}', 'PositionController@read');
    $router->get('routers/users', 'RouterController@users');

    $router->get('audios', 'AudioController@index');
    $router->get('audios/{id}', 'AudioController@read');

    /**
     * Auth Routes
     */
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');

    /**
     * CRUD Routes
     */
    foreach ([
        'shops' => 'ShopController',
        'users' => 'UserController',
        'items' => 'ItemController',
        'routers' => 'RouterController',
        'beacons' => 'BeaconController',
        'locations' => 'LocationController',
    ] as $path => $controller) {
        $router->get($path, $controller . '@index');
        $router->get($path . '/{id}', $controller . '@read');
    }

    /**
     * Relations routes
     */
    foreach ([
        'users/{user_id}/items' => 'UserItemController',
        'users/{user_id}/shops' => 'UserShopController',
        'shops/{shop_id}/items' => 'ShopItemController',
    ] as $path => $controller) {
        $router->get($path, $controller . '@index');
        $router->post($path . '/{item_id}', $controller . '@create');
        $router->get($path . '/{item_id}', $controller . '@read');
        $router->delete($path . '/{item_id}', $controller . '@delete');
    }    
});
