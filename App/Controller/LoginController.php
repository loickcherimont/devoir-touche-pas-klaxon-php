<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Login page controller: renders the login form.
 */
class LoginController extends AbstractController
{
	/**
	 * Renders the login page.
	 *
	 * @param Request  $request  Incoming HTTP request (unused for now).
	 * @param Response $response Outgoing HTTP response to fill with the page.
	 * @return Response The response containing the rendered login page.
	 */
	public function index(Request $request, Response $response): Response
	{
        return $this->render('login');
	}
}