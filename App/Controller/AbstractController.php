<?php

namespace App\Controller;

use App\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * AbstractController
 *
 * Base class for every controller.
 * It provides the shared render() helper so the
 * "setContent + return $response" pattern is never duplicated (DRY).
 */
abstract class AbstractController
{
    /**
     * Renders a template inside the shared page and returns the HTTP response.
     *
     * @param string               $template Template file name without .php extension
     * @param array<string, mixed> $data     Variables made available to the template
     * @return Response The response containing the rendered page
     */
    protected function render(string $template, array $data = []): Response
    {
        $response = new Response();
        $response->setContent(View::page($template, $data));

        return $response;
    }

    /**
     * Turns a response into an HTTP redirect (302 Found) to the given URL.
     *
     * @param Response $response The HTTP response to turn into a redirect.
     * @param string   $url      The destination URL (ex: '/').
     * @return Response The redirect response.
     */
    protected function redirect(Response $response, string $url): Response
    {
        $response->headers->set('Location', $url);
        return $response->setStatusCode(Response::HTTP_FOUND);
    }

    /**
     * Creates a JSON response for the small API endpoints of the application.
     *
     * @param array<string, int|string> $data Response data.
     */
    protected function json(array $data, int $statusCode = Response::HTTP_OK): Response
    {
        return new Response(
            json_encode($data, JSON_THROW_ON_ERROR),
            $statusCode,
            ['Content-Type' => 'application/json'],
        );
    }

    /**
     * Tells whether the current user is authenticated.
     *
     * @return bool True when a user is logged in, false otherwise.
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['auth_logged_in']);
    }
}
