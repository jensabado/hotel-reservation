<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['as' => 'home']);
$routes->get('accomodation', 'Home::accomodation');

// USER SIDE 
$routes->group('', ['filter' => 'user_filter:guest_user'], static function ($routes) {
    $routes->get('register', 'Authentication::register', ['as' => 'user.register.form']);
    $routes->get('login', 'Authentication::index', ['as' => 'user.login.form']);
    $routes->get('forgot-password', 'Authentication::forgot_password', ['as' => 'user.forgot-password.form']);
    $routes->post('register', 'Authentication::register_submit', ['as', 'user.register.submit']);
    $routes->post('login', 'Authentication::login_submit', ['as', 'user.login.submit']);
    $routes->post('forgot-password', 'Authentication::forgot_password_submit', ['as' => 'user.forgot-password.submit']);
    $routes->get('reset-password/(:any)', 'Authentication::reset_password/$1', ['as' => 'user.reset-password']);
    $routes->post('reset-password', 'Authentication::reset_password_submit', ['as' => 'user.reset-password.submit']);
});

$routes->group('', ['filter' => 'user_filter:auth_user'], static function ($routes) {
    $routes->get('logout', 'Authentication::logout', ['as' => 'user_logout']);
    $routes->get('book-now/(:any)', 'Home::book_now/$1', ['as' => 'user.book']);
});

// ADMIN SIDE
$routes->group('', ['filter' => 'admin_filter:guest_admin'], static function ($routes) {
    $routes->get('admin/login', 'Authentication::admin_login', ['as' => 'admin.login.form']);
    $routes->post('admin/login', 'Authentication::admin_login_submit', ['as' => 'admin.login.submit']);
});

$routes->group('', ['filter' => 'admin_filter:auth_admin'], static function ($routes) {
    $routes->get('admin/home', 'AdminController::index', ['as' => 'admin.home']);
    $routes->get('admin/logout', 'Authentication::admin_logout', ['as' => 'admin_logout']);
    // room
    $routes->get('admin/room', 'AdminController::room', ['as' => 'admin.room']);
    $routes->post('admin/room/data', 'AdminController::room_datatable', ['as' => 'admin.room.datatable']);
    $routes->get('admin/room/add', 'AdminController::add_room', ['as' => 'admin.add.room']);
    $routes->post('admin/room/add', 'AdminController::add_room_submit', ['as' => 'admin.add.room.submit']);
    $routes->get('admin/room/edit/(:any)', 'AdminController::edit_room/$1', ['as' => 'admin.edit.room']);
    $routes->post('admin/room/edit', 'AdminController::edit_room_submit', ['as' => 'admin.edit.room.submit']);
    $routes->post('admin/room', 'AdminController::delete_room_submit', ['as' => 'admin.delete.room.submit']);
    // room-types
    $routes->get('admin/room-types', 'AdminController::room_types', ['as' => 'admin.room_types']);
    $routes->post('admin/room-types/data', 'AdminController::room_type_datatable', ['as' => 'admin.room-types.datatable']);
    $routes->get('admin/room-types/add', 'AdminController::add_room_type', ['as' => 'admin.add.room_type']);
    $routes->post('admin/room-type/add', 'AdminController::add_room_type_submit', ['as' => 'admin.add.room_type.submit']);
    $routes->get('admin/room-types/edit/(:any)', 'AdminController::edit_room_type/$1', ['as' => 'admin.edit.room_type']);
    $routes->post('admin/room-types/edit', 'AdminController::edit_room_type_submit', ['as' => 'admin.edit.room_type.submit']);
    $routes->post('admin/room-types', 'AdminController::delete_room_type_submit', ['as' => 'admin.delete.room_type.submit']);
});
