<?php

namespace App\Controller;

use App\Model\User\UserModel;
use App\Model\User\UserRole;
use Core\Database;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Login page controller: renders the login form.
 */
class LoginController extends AbstractController
{

	private UserModel $userModel;

	public function __construct()
	{
		$this->userModel = new UserModel(Database::getInstance()->connection());
	}

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

	/**
	 * Handles the POST /login form: checks the credentials and, if valid, stores
	 * the user and its role in the session, then redirects to the page matching
	 * that role (dashboard for an admin, home page otherwise).
	 *
	 * A user whose `role` is unknown to the UserRole enum is rejected here: the
	 * application never grants an access it cannot name.
	 *
	 * @param Request  $request  Incoming HTTP request containing email and pass.
	 * @param Response $response Outgoing HTTP response to redirect.
	 * @return Response The redirect response, or the login page with an error.
	 */
	public function login(Request $request, Response $response): Response
	{
		$user = $this->userModel->getUserByEmail((string) $request->request->get('email'));

		if ($user !== false && password_verify((string) $request->request->get('pass'), (string) $user['mot_de_passe'])) {

			$role = UserRole::tryFrom((string) $user['role']);

			if ($role === null) {
				// Role unknown in database: deny the login rather than granting an undefined access.
				return $this->render('login', ['error' => 'Email ou mot de passe incorrect']);
			}

			session_regenerate_id(true);

			$_SESSION['auth_user_id'] = $user['id'];
			$_SESSION['auth_logged_in'] = true;
			$_SESSION['first_name'] = $user['prenom'];
			$_SESSION['last_name'] = $user['nom'];
			$_SESSION['role'] = $role->value;

			// An admin starts on the dashboard, a user on the home page.
			return $this->redirect($response, $role === UserRole::Admin ? '/admin' : '/', Response::HTTP_FOUND);
		}

		return $this->render('login', ['error' => 'Email ou mot de passe incorrect']);
	}

	/**
	 * Destroys the current session then redirects to the homepage.
	 *
	 * @param Request  $request  Incoming HTTP request (unused for now).
	 * @param Response $response Outgoing HTTP response to redirect.
	 * @return Response The redirect response.
	 */
	public function logout(Request $request, Response $response): Response
	{
		session_destroy();
		return $this->redirect($response, '/', Response::HTTP_FOUND);
	}
}
