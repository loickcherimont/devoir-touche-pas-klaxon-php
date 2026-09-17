<?php

namespace App\Controller;

use App\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Home page controller: renders the landing page.
 */
class HomeController
{
	/**
	 * Renders the home page.
	 *
	 * @param Request  $request  Incoming HTTP request (unused for now).
	 * @param Response $response Outgoing HTTP response to fill with the page.
	 * @return Response The response containing the rendered home page.
	 */
	public function index(Request $request, Response $response): Response
	{
		$response->setContent(View::page('home'));

		return $response;
	}
}