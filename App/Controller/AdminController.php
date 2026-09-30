<?php

namespace App\Controller;

use App\Model\Agency\AgencyModel;
use App\Model\Trip\TripModel;
use App\Model\User\UserModel;
use App\Model\User\UserRole;
use App\Security\Flash;
use App\Service\AgencyValidator;
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
	private AgencyValidator $agencyValidator;

	public function __construct()
	{
		$this->userModel = new UserModel(Database::getInstance()->connection());
		$this->agencyModel = new AgencyModel(Database::getInstance()->connection());
		$this->tripModel = new TripModel(Database::getInstance()->connection());
		$this->agencyValidator = new AgencyValidator();
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
	 * Returns one agency as JSON to pre-fill the update modal.
	 *
	 * @param int $id Agency identifier from the URL.
	 * @return Response The agency as JSON, 401 when not admin, 404 when the
	 *                  agency does not exist.
	 */
	public function findAgencyById(int $id): Response
	{
		if (!$this->hasRole(UserRole::Admin)) {
			return $this->json(['message' => 'Authentification requise.'], Response::HTTP_UNAUTHORIZED);
		}

		$agency = $this->agencyModel->getAgencyById($id);

		if ($agency === null) {
			return $this->json(['message' => 'Agence introuvable.'], Response::HTTP_NOT_FOUND);
		}

		return $this->json([
			'id' => $agency->id,
			'nom' => $agency->nom,
		]);
	}

	/**
	 * Handles the POST /admin/agencies/new form: trims the submitted name,
	 * rejects empty and duplicate values, inserts the agency and redirects to
	 * the dashboard so the flash message is shown on the same page.
	 *
	 * Anyone who is not an admin is redirected to the login page instead of
	 * being told the route exists.
	 *
	 * @param Request  $request  Incoming HTTP request containing the agency name.
	 * @param Response $response Outgoing HTTP response to redirect.
	 * @return Response The redirect to the dashboard, or to /login when the
	 *                  role is not admin.
	 */
	public function createAgency(Request $request, Response $response): Response
	{
		if (!$this->hasRole(UserRole::Admin)) {
			return $this->redirect($response, '/login', Response::HTTP_FOUND);
		}

		$csrfRedirect = $this->rejectInvalidCsrf($request, $response, '/admin');

		if ($csrfRedirect !== null) {
			return $csrfRedirect;
		}

		$nom = trim((string) $request->request->get('nom'));

		if ($nom === '') {
			Flash::error('Le nom de l\'agence ne peut pas être vide.');

			return $this->redirect($response, '/admin', Response::HTTP_FOUND);
		}

		if ($this->agencyModel->getAgencyByNom($nom) !== null) {
			Flash::error('Une agence portant ce nom existe déjà.');

			return $this->redirect($response, '/admin', Response::HTTP_FOUND);
		}

		try {
			$this->agencyModel->saveAgency($nom);
		} catch (PDOException $exception) {
			return $this->flashAgencyDatabaseError($exception, $response, '/admin');
		}

		Flash::success('Agence créée avec succès.');

		return $this->redirect($response, '/admin', Response::HTTP_FOUND);
	}

	/**
	 * Handles the POST /admin/agencies/update form of the update modal.
	 *
	 * Reads the id from the hidden form field, rejects an unknown agency and a
	 * duplicate name, then redirects to the dashboard so the flash message is
	 * shown on the same page.
	 *
	 * Anyone who is not an admin is redirected to the login page instead of
	 * being told the route exists.
	 *
	 * @param Request  $request  Incoming HTTP request containing the agency id
	 *                           and the new name.
	 * @param Response $response Outgoing HTTP response to redirect.
	 * @return Response The redirect to the dashboard, or to /login when the
	 *                  role is not admin.
	 */
	public function updateAgency(Request $request, Response $response): Response
	{
		if (!$this->hasRole(UserRole::Admin)) {
			return $this->redirect($response, '/login', Response::HTTP_FOUND);
		}

		$csrfRedirect = $this->rejectInvalidCsrf($request, $response, '/admin');

		if ($csrfRedirect !== null) {
			return $csrfRedirect;
		}

		$id = (int) $request->request->get('id');
		$nom = trim((string) $request->request->get('nom'));
		$agencyToUpdate = $this->agencyModel->getAgencyById($id);

		if ($agencyToUpdate === null) {
			Flash::error('Agence non enregistrée. Réessayez avec une agence existante.');

			return $this->redirect($response, '/admin', Response::HTTP_FOUND);
		}

		$validationError = $this->agencyValidator->validate($nom, $id, $this->agencyModel);

		if ($validationError !== null) {
			Flash::error($validationError);

			return $this->redirect($response, '/admin', Response::HTTP_FOUND);
		}

		try {
			$this->agencyModel->updateAgencyById($nom, $id);
		} catch (PDOException $exception) {
			return $this->flashAgencyDatabaseError($exception, $response, '/admin');
		}

		Flash::success("L'agence a été modifiée avec succès.");

		return $this->redirect($response, '/admin', Response::HTTP_FOUND);
	}

	/**
	 * Handles the POST /admin/agencies/delete/:id action: deletes the agency,
	 * then redirects to the dashboard so the flash message is shown. Keeping
	 * the URL on the :id means a refresh replays the same delete, which is a
	 * no-op once the agency is gone.
	 *
	 * An agency still referenced by a trip cannot be deleted: the foreign key
	 * rejects the delete with an integrity-constraint violation (SQLSTATE
	 * 23000), which is caught here to flash a readable error. Any other
	 * database failure is rethrown instead of being masked.
	 *
	 * Anyone who is not an admin is redirected to the login page instead of
	 * being told the route exists.
	 *
	 * @param Request  $request  Incoming HTTP request carrying the CSRF token.
	 * @param Response $response Outgoing HTTP response to redirect.
	 * @param int      $id       Agency identifier from the URL.
	 * @return Response The redirect to the dashboard, or to /login when the
	 *                  role is not admin.
	 */
	public function deleteAgency(Request $request, Response $response, int $id): Response
	{
		if (!$this->hasRole(UserRole::Admin)) {
			return $this->redirect($response, '/login', Response::HTTP_FOUND);
		}

		$csrfRedirect = $this->rejectInvalidCsrf($request, $response, '/admin');

		if ($csrfRedirect !== null) {
			return $csrfRedirect;
		}

		try {
			$this->agencyModel->deleteAgencyById($id);
		} catch (PDOException $exception) {
			if ($exception->getCode() !== '23000') {
				throw $exception;
			}

			Flash::error('Cette agence est utilisée par au moins un trajet et ne peut pas être supprimée.');

			return $this->redirect($response, '/admin', Response::HTTP_FOUND);
		}

		Flash::success("L'agence a bien été supprimée.");

		return $this->redirect($response, '/admin', Response::HTTP_FOUND);
	}

	/**
	 * Handles the POST /admin/trips/delete/:id action: deletes the trip, then
	 * redirects to the dashboard so the flash message is shown. Keeping the
	 * URL on the :id means a refresh replays the same delete, which is a no-op
	 * once the trip is gone.
	 *
	 * Unlike TripController::delete, this action drops the ownership condition:
	 * the admin can remove any trip, whatever its author. The deletion is
	 * carried by TripModel::deleteTripByAdminId(), whose SQL has no author
	 * clause.
	 *
	 * Anyone who is not an admin is redirected to the login page instead of
	 * being told the route exists.
	 *
	 * @param Request  $request  Incoming HTTP request carrying the CSRF token.
	 * @param Response $response Outgoing HTTP response to redirect.
	 * @param int      $id       Trip identifier from the URL.
	 * @return Response The redirect to the dashboard, or to /login when the
	 *                  role is not admin.
	 */
	public function deleteTrip(Request $request, Response $response, int $id): Response
	{
		if (!$this->hasRole(UserRole::Admin)) {
			return $this->redirect($response, '/login', Response::HTTP_FOUND);
		}

		$csrfRedirect = $this->rejectInvalidCsrf($request, $response, '/admin');

		if ($csrfRedirect !== null) {
			return $csrfRedirect;
		}

		try {
			$this->tripModel->deleteTripByAdminId($id);
		} catch (PDOException $exception) {
			error_log($exception->getMessage());

			Flash::error('Une erreur est survenue lors de la suppression du trajet. Veuillez réessayer.');

			return $this->redirect($response, '/admin', Response::HTTP_FOUND);
		}

		Flash::success('Le trajet a bien été supprimé.');

		return $this->redirect($response, '/admin', Response::HTTP_FOUND);
	}

	/**
	 * Renders the dashboard with the agencies, users and trips listings.
	 *
	 * @param Response $response Outgoing HTTP response to fill with the page.
	 * @return Response The rendered dashboard.
	 */
	private function renderDashboard(Response $response): Response
	{
		return $this->render('dashboard', [
			'users' => $this->userModel->getAllNonAdminUsers(),
			'agencies' => $this->agencyModel->getAllAgencies(),
			'trips' => $this->tripModel->getAllTripsForAdmin(),
		]);
	}

	/**
	 * Turns a database failure on an agency write into an error flash message,
	 * keeping the duplicate-name case readable and logging the rest.
	 *
	 * @param PDOException $exception The failure thrown by the model.
	 * @param string       $redirectUrl Page to send the admin back to.
	 * @return Response The redirect response.
	 */
	private function flashAgencyDatabaseError(PDOException $exception, Response $response, string $redirectUrl): Response
	{
		if ($exception->getCode() === '23000') {
			Flash::error('Une agence portant ce nom existe déjà.');
		} else {
			error_log($exception->getMessage());

			Flash::error('Une erreur est survenue lors de l\'enregistrement de l\'agence. Veuillez réessayer.');
		}

		return $this->redirect($response, $redirectUrl, Response::HTTP_FOUND);
	}
}
