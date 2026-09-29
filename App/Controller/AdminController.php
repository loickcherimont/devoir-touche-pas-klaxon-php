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

		return $this->renderDashboard($response);
	}

	/**
	 * Handles the POST /admin/agencies/new form: trims the submitted name,
	 * rejects empty and duplicate values, inserts the agency and redirects to
	 * the dashboard so the updated listing is rendered (Post/Redirect/Get).
	 *
	 * Anyone who is not an admin is redirected to the login page instead of
	 * being told the route exists.
	 *
	 * @param Request  $request  Incoming HTTP request containing the agency name.
	 * @param Response $response Outgoing HTTP response to redirect or to fill.
	 * @return Response The redirect to /admin, the dashboard with an error, or
	 *                  a redirect to /login when the role is not admin.
	 */
	public function createAgency(Request $request, Response $response): Response
	{
		if (!$this->hasRole(UserRole::Admin)) {
			return $this->redirect($response, '/login', Response::HTTP_FOUND);
		}

		$nom = trim((string) $request->request->get('nom'));

		if ($nom === '') {
			return $this->redirect($response, '/admin#agencies');
		}

		if ($this->agencyModel->getAgencyByNom($nom) !== false) {
			return $this->renderDashboard($response, 'Une agence portant ce nom existe déjà.');
		}

		$this->agencyModel->saveAgency($nom);

		return $this->redirect($response, '/admin#agencies');
	}

	/**
	 * Renders the dashboard with the agencies and users listings.
	 *
	 * @param Response     $response Outgoing HTTP response to fill with the page.
	 * @param string|null $error    Message to display above the agency form,
	 *                               or null for a clean page.
	 * @return Response The rendered dashboard.
	 */
	private function renderDashboard(Response $response, ?string $error = null): Response
	{
		return $this->render('dashboard', [
			'users' => $this->userModel->getAllNonAdminUsers(),
			'agencies' => $this->agencyModel->getAllAgencies(),
			'error' => $error,
		]);
	}
}
