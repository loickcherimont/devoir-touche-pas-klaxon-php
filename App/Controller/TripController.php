<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TripController
 *
 * Manages the trip creation page. Access is restricted to logged-in users.
 */
class TripController extends AbstractController
{

	/**
	 * Renders the trip creation page for authenticated users,
	 * otherwise redirects anonymous visitors to the login page.
	 *
	 * @param Request  $request  Incoming HTTP request (unused for now).
	 * @param Response $response Outgoing HTTP response to fill with the page.
	 * @return Response The rendered trip creation page, or a redirect to /login.
	 */
	public function index(Request $request, Response $response): Response
	{
		if (!$this->isLoggedIn()) {
			return $this->redirect($response, '/login');
		}

		return $this->render('create-trip');
	}
}
