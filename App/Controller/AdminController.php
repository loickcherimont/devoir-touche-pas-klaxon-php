<?php

namespace App\Controller;

use App\Model\Agency\AgencyModel;
use App\Model\Trip\TripModel;
use App\Model\User\UserModel;
use App\Model\User\UserRole;
use Core\Database;
use PDOException;
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
	private TripModel $tripModel;

	public function __construct()
	{
		$this->userModel = new UserModel(Database::getInstance()->connection());
		$this->agencyModel = new AgencyModel(Database::getInstance()->connection());
		$this->tripModel = new TripModel(Database::getInstance()->connection());
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
	 * rejects empty and duplicate values, inserts the agency and re-renders
	 * the dashboard so the updated listing plus an error or success message are
	 * shown on the same page.
	 *
	 * Anyone who is not an admin is redirected to the login page instead of
	 * being told the route exists.
	 *
	 * @param Request  $request  Incoming HTTP request containing the agency name.
	 * @param Response $response Outgoing HTTP response to fill with the page.
	 * @return Response The dashboard, or a redirect to /login when the role is
	 *                  not admin.
	 */
	public function createAgency(Request $request, Response $response): Response
	{
		if (!$this->hasRole(UserRole::Admin)) {
			return $this->redirect($response, '/login', Response::HTTP_FOUND);
		}

		$nom = trim((string) $request->request->get('nom'));

		if ($nom === '') {
			return $this->renderDashboard($response, 'Le nom de l\'agence ne peut pas être vide.');
		}

		if ($this->agencyModel->getAgencyByNom($nom) !== null) {
			return $this->renderDashboard($response, 'Une agence portant ce nom existe déjà.');
		}

		$this->agencyModel->saveAgency($nom);

		return $this->renderDashboard($response, null, 'Agence créée avec succès');
	}

	/**
	 * Handles the GET /admin/agencies/delete/:id action: deletes the agency,
	 * then re-renders the dashboard to show the updated listing. Keeping the
	 * URL on the :id means a refresh replays the same delete, which is a no-op
	 * once the agency is gone — acceptable under the GET debt noted below.
	 *
	 * An agency still referenced by a trip cannot be deleted: the foreign key
	 * rejects the delete with an integrity-constraint violation (SQLSTATE
	 * 23000), which is caught here to re-render the dashboard with a readable
	 * error. Any other database failure is rethrown instead of being masked.
	 *
	 * Note: a destructive action reachable with a plain GET suffers the same
	 * documented debt as /trips/delete/:id — a POST route plus a CSRF token
	 * would be the safe production version.
	 *
	 * Anyone who is not an admin is redirected to the login page instead of
	 * being told the route exists.
	 *
	 * @param Request  $request  Incoming HTTP request (unused for now).
	 * @param Response $response Outgoing HTTP response to fill with the page.
	 * @param int      $id       Agency identifier from the URL.
	 * @return Response The dashboard with an error or a success message, or a
	 *                  redirect to /login when the role is not admin.
	 */
	public function deleteAgency(Request $request, Response $response, int $id): Response
	{
		if (!$this->hasRole(UserRole::Admin)) {
			return $this->redirect($response, '/login', Response::HTTP_FOUND);
		}

		try {
			$this->agencyModel->deleteAgencyById($id);
		} catch (PDOException $e) {
			if ($e->getCode() !== '23000') {
				throw $e;
			}

			return $this->renderDashboard(
				$response,
				'Cette agence est utilisée par au moins un trajet et ne peut pas être supprimée.'
			);
		}

		return $this->renderDashboard($response, null, "L'agence a bien été supprimée");
	}

	/**
	 * Renders the dashboard with the agencies and users listings.
	 *
	 * @param Response     $response Outgoing HTTP response to fill with the page.
	 * @param string|null $error    Message to display above the agency form,
	 *                               or null for a clean page.
	 * @return Response The rendered dashboard.
	 */
	private function renderDashboard(Response $response, ?string $error = null, ?string $success = null): Response
	{
		return $this->render('dashboard', [
			'users' => $this->userModel->getAllNonAdminUsers(),
			'agencies' => $this->agencyModel->getAllAgencies(),
			'trips' => $this->tripModel->getAllTripsForAdmin(),
			'error' => $error,
			'success' => $success
		]);
	}
}
