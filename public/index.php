<?php

/**
 * Front controller — the single entry point of the app.
 *
 * This file runs on EVERY request.
 * - If the request targets a real file (CSS, JS, image...), we return false
 *   so the PHP dev server can serve it as-is.
 * - Otherwise, we hand the request to the router, which decides what to show.
 */

use Router\ApplicationRouter;

// Composer autoloader: loads the router library classes without manual require.
require __DIR__ . '/../vendor/autoload.php';

session_start();

$path = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path !== __DIR__ . '/' && file_exists($path)) {
    return false;
}

new ApplicationRouter()->run();
