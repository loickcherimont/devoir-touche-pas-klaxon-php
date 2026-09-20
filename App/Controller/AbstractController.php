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
}