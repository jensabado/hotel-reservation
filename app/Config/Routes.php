<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['as' => 'home']);
$routes->get('accomodation', 'Home::accomodation');
$routes->get('admin/home', 'AdminController::index');


$routes->group('', ['filter' => 'user_filter:guest_user'], static function($routes) {
    $routes->get('register', 'Authentication::register', ['as' => 'user.register.form']);
    $routes->get('login', 'Authentication::index', ['as' => 'user.login.form']);
    $routes->post('register', 'Authentication::register_submit',['as', 'user.register.submit']);
    $routes->post('login', 'Authentication::login_submit',['as', 'user.login.submit']);
});

$routes->group('', ['filter' => 'user_filter:auth_user'], static function($routes) {
    $routes->get('logout', 'Authentication::logout', ['as' => 'user_logout']);
});