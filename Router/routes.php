<?php

/**
 * Routes — the application routing table.
 *
 * Loaded by the front controller (public/index.php) whenever the request
 * is not a static file. Maps each URL to a controller method:
 * "GET /login" → LoginController@index.
 */

use Buki\Router\Router;

// Create a new router instance.
$router = new Router(
	[
		'paths' => ['controllers' => ROOT_PATH . '/App/Controller'],
		'namespaces' => ['controllers' => 'App\\Controller']
	]
);

$router->get('/', 'HomeController@index');

// Declare a GET route: when the user opens /login, run the render method.
$router->get('/logout', 'LoginController@logout');
$router->get('/login', 'LoginController@index');
$router->post('/login', 'LoginController@login');

// Let the router search its routes for the current request.
$router->run();
