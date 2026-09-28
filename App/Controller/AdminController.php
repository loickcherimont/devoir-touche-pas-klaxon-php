<?php

namespace App\Controller;

use App\Model\Agency\AgencyModel;
use App\Model\User\UserModel;
use App\Model\User\UserRole;
use Core\Database;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminController — renders the role-protected dashboard.
 *
 * Access control lives here and nowhere else: every action of this controller
 * must start with the same hasRole() test, so a new action can never be
 * reachable by a regular user by accident.
 */
class AdminController extends AbstractController
{
	private UserModel $userModel;
	private AgencyModel $agencyModel;

	public function __construct()
	{
		$this->userModel = new UserModel(Database::getInstance()->connection());
		$this->agencyModel = new AgencyModel(Database::getInstance()->connection());
	}

	/**
	 * Renders the admin dashboard.
	 *
	 * Anyone who is not an admin is redirected to the login page instead of
	 * being told the page exists.
	 *
	 * @param Request  $request  Incoming HTTP request (unused for now).
	 * @param Response $response Outgoing HTTP response to fill with the page.
	 * @return Response The dashboard, or a redirect when the role is not admin.
	 */
	public function index(Request $request, Response $response): Response
	{
		if (!$this->hasRole(UserRole::Admin)) {
			return $this->redirect($response, '/login', Response::HTTP_FOUND);
		}

		return $this->render('dashboard', [
			'users' => $this->userModel->getAllNonAdminUsers(),
			'agencies' => $this->agencyModel->getAllAgenciesForAdmin(),
		]);
	}
}
