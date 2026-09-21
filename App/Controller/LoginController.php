<?php

namespace App\Controller;

use App\Model\UserModel;
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

	public function login(Request $request)
	{
		$error = null;
		// Fetch data in DB
		$user = $this->userModel->getUserByEmail(htmlspecialchars($request->request->get('email')));

		if ($user->email === $request->request->get('email') && password_verify($request->request->get('pass'), $user->mot_de_passe)) {

			session_regenerate_id(true);

			$_SESSION['auth_user_id'] = $user->id;
			$_SESSION['auth_logged_in'] = true;
			$_SESSION['first_name'] = $user->prenom;
			$_SESSION['last_name'] = $user->nom;
			header('Location: /');
			exit;
		}
		$error = 'Email ou mot de passe incorrect';
		return $this->render('login', ['error' => $error]);
		// Else -> Error in login
	}

	public function logout()
	{
		session_destroy();
		header('Location: /');
		exit;
	}
}
