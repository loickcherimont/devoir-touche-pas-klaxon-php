<?php

/**
 * ApplicationRouter — the application's routing configuration.
 *
 * Loaded by the front controller (public/index.php) whenever the request
 * is not a static file. Maps each URL to a controller method.
 * Example: "GET /login" → LoginController@index.
 */

namespace Router;

use Buki\Router\Router;

final class ApplicationRouter
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router([
            'paths' => ['controllers' => ROOT_PATH . '/App/Controller'],
            'namespaces' => ['controllers' => 'App\\Controller'],
        ]);

        $this->registerRoutes();
    }

    /** Starts route matching for the current HTTP request. */
    public function run(): void
    {
        $this->router->run();
    }

    /** Declares every URL exposed by the application. */
    private function registerRoutes(): void
    {
        $this->router->get('/', 'HomeController@index');
        $this->router->get('/logout', 'LoginController@logout');
        $this->router->get('/login', 'LoginController@index');
        $this->router->get('/trips/new', 'TripController@index');
        $this->router->get('/api/trips/:id', 'TripController@findDetailsById');
        $this->router->get('/trips/update/:id', 'TripController@getUpdatePage');
        // Destructive action kept on GET: the ownership check is server-side,
        // but a POST route + CSRF token would be the safe production version.
        $this->router->get('/trips/delete/:id', 'TripController@delete');
        $this->router->get('/admin', 'AdminController@index');

        $this->router->post('/login', 'LoginController@login');
        $this->router->post('/trips/new', 'TripController@create');
        $this->router->post('/trips/update/:id', 'TripController@update');
    }
}

return new ApplicationRouter();
