<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('EventController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'EventController::index');
// $routes->get('health', 'Home::health');

$routes->group('', ['filter' => 'guest'], static function (RouteCollection $routes): void {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::storeRegistration');
});

$routes->get('logout', 'AuthController::logout', ['filter' => 'auth']);

$routes->get('events', 'EventController::index');
$routes->get('events/(:num)', 'EventController::show/$1');
$routes->get('uploads/(:segment)/(:any)', 'UploadController::show/$1/$2');

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('profile', 'UserController::profile');
    $routes->post('profile', 'UserController::updateProfile');
    $routes->get('my-registrations', 'RegistrationController::mine');
    $routes->post('events/(:num)/register', 'RegistrationController::store/$1');
    $routes->post('registrations/(:num)/payment', 'RegistrationController::uploadPayment/$1');
});

$routes->group('', ['filter' => 'role:admin,organizer'], static function (RouteCollection $routes): void {
    $routes->get('events/create', 'EventController::create');
    $routes->post('events', 'EventController::store');
    $routes->get('events/(:num)/edit', 'EventController::edit/$1');
    $routes->post('events/(:num)', 'EventController::update/$1');
    $routes->post('events/(:num)/delete', 'EventController::delete/$1');
    $routes->get('registrations', 'RegistrationController::index');
    $routes->post('registrations/(:num)/status', 'RegistrationController::updateStatus/$1');
});

$routes->group('admin', ['filter' => 'role:admin'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'DashboardController::admin');
    $routes->get('analytics', 'DashboardController::analytics');
    $routes->get('users', 'UserController::index');
});

$routes->group('organizer', ['filter' => 'role:organizer'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'DashboardController::organizer');
});
