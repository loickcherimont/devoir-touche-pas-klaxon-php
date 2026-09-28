<?php

namespace App\Controller;

use App\DateTimeFormatter;
use App\Model\Agency\AgencyModel;
use App\Model\Trip\TripDataDTO;
use App\Model\Trip\TripModel;
use App\Model\Trip\TripToUpdateDetailsDTO;
use App\Model\User\UserDTO;
use App\Model\User\UserModel;
use Core\Database;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TripController
 *
 * Manages the trip features: creation page, update page and deletion
 * (both restricted to the author of the trip), plus the small JSON API
 * used by the details modal. Every action is restricted to logged-in users.
 */
class TripController extends AbstractController
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

		return $this->renderCreateTrip($response);
	}

	/**
	 * Returns the details of one trip for the details modal.
	 *
	 * @param int $id Trip identifier from the URL.
	 * @return Response The trip details as JSON, 401 when anonymous,
	 *                  404 when the trip does not exist.
	 */
	public function findDetailsById(int $id): Response
	{
		if (!$this->isLoggedIn()) {
			return $this->json(['message' => 'Authentification requise.'], Response::HTTP_UNAUTHORIZED);
		}

		$trip = $this->tripModel->findDetailsById($id);

		if ($trip === null) {
			return $this->json(['message' => 'Trajet introuvable.'], Response::HTTP_NOT_FOUND);
		}

		return $this->json([
			'authorFirstName' => $trip->authorFirstName,
			'authorLastName' => $trip->authorLastName,
			'phone' => $trip->phone,
			'email' => $trip->email,
			'availableSeats' => $trip->availableSeats,
		]);
	}

	/**
	 * Handles the POST /trips/new form: validates the data, inserts the trip
	 * in the database, then redirects to the homepage.
	 *
	 * @param Request  $request  Incoming HTTP request containing the trip data.
	 * @param Response $response Outgoing HTTP response to redirect.
	 * @return Response The redirect response, or the creation page with an error.
	 */
	public function create(Request $request, Response $response): Response
	{
		if (!$this->isLoggedIn()) {
			return $this->redirect($response, '/login');
		}

		$error = $this->validateAgencies($request)
			?? $this->validateDatesTimes($request);

		if ($error !== null) {
			return $this->renderCreateTrip($response, $error);
		}

		$tripData = $this->buildTripData($request);

		$this->tripModel->saveTrip($tripData, (int) $this->currentUserId());

		return $this->redirect($response, '/');
	}

	/**
	 * Handles the GET /trips/update/:id form: displays the update form
	 * pre-filled with the current values of the trip.
	 *
	 * Only the author of the trip is allowed to update it: any other logged-in
	 * user is redirected to the homepage.
	 *
	 * @param Response $response Outgoing HTTP response to fill with the page.
	 * @param int      $id       Trip identifier from the URL.
	 * @return Response The rendered update page, a 404 when the trip does not
	 *                  exist, or a redirect to /login or /.
	 */
	public function getUpdatePage(Response $response, int $id): Response
	{
		$redirect = $this->redirectAnonymousVisitor($response);

		if ($redirect !== null) {
			return $redirect;
		}

		$tripToUpdate = $this->tripModel->findTripToUpdateDetailsById($id);

		if ($tripToUpdate === null) {
			return $this->tripNotFound();
		}

		if (!$this->isTripOwner($tripToUpdate->ownerId)) {
			return $this->redirect($response, '/');
		}

		return $this->renderUpdateTrip($response, $tripToUpdate);
	}

	/**
	 * Handles the POST /trips/update/:id form: validates the submitted data,
	 * updates the trip in the database, then redirects to the homepage.
	 *
	 * Only the author of the trip is allowed to update it: any other logged-in
	 * user is redirected to the homepage.
	 *
	 * @param Request  $request  Incoming HTTP request containing the trip data.
	 * @param Response $response Outgoing HTTP response to redirect.
	 * @param int      $id       Trip identifier from the URL.
	 * @return Response The redirect response, the update page with an error,
	 *                  a 404 when the trip does not exist, or a redirect to
	 *                  /login or /.
	 */
	public function update(Request $request, Response $response, int $id): Response
	{
		$redirect = $this->redirectAnonymousVisitor($response);

		if ($redirect !== null) {
			return $redirect;
		}

		$tripToUpdate = $this->tripModel->findTripToUpdateDetailsById($id);

		if ($tripToUpdate === null) {
			return $this->tripNotFound();
		}

		if (!$this->isTripOwner($tripToUpdate->ownerId)) {
			return $this->redirect($response, '/');
		}

		$error = $this->validateAgencies($request)
			?? $this->validateDatesTimes($request);

		if ($error !== null) {
			return $this->renderUpdateTrip(
				$response,
				$tripToUpdate,
				"Une erreur s'est produite durant la mise à jour du trajet. Veuillez réessayer."
			);
		}

		$this->tripModel->updateTripById($this->buildTripData($request), $id);

		return $this->redirect($response, '/');
	}

	/**
	 * Handles the GET /trips/delete/:id action: deletes the trip, but only
	 * when it belongs to the logged-in user.
	 *
	 * The ownership check lives inside the SQL query
	 * (see TripModel::deleteTripById()), so this action only guards the
	 * authentication, then calls the model once. A trip that does not exist,
	 * or that belongs to somebody else, simply redirects to the homepage —
	 * the same visible outcome as an unauthorized update.
	 *
	 * Note: a destructive action reachable with a plain GET can also be
	 * triggered by a link prefetch or a third-party page. A POST route plus
	 * a CSRF token would be the safe production version.
	 *
	 * @param Request  $request  Incoming HTTP request (unused for now).
	 * @param Response $response Outgoing HTTP response to redirect.
	 * @param int      $id       Trip identifier from the URL.
	 * @return Response The redirect response, or a redirect to /login.
	 */
	public function delete(Request $request, Response $response, int $id): Response
	{
		$redirect = $this->redirectAnonymousVisitor($response);

		if ($redirect !== null) {
			return $redirect;
		}

		$this->tripModel->deleteTripById($id, (int) $this->currentUserId());

		return $this->redirect($response, '/');
	}

	/**
	 * Tells whether the logged-in user is the owner of the given trip.
	 *
	 * @param int $ownerId Id of the user who created the trip.
	 * @return bool True when the current user owns the trip, false otherwise.
	 */
	private function isTripOwner(int $ownerId): bool
	{
		return $this->currentUserId() === $ownerId;
	}

	/**
	 * Builds the trip to save from the submitted form fields.
	 * Shared by the creation and the update forms, which post the same fields.
	 *
	 * @param Request $request Incoming HTTP request containing the trip data.
	 * @return TripDataDTO The trip values submitted by the form.
	 */
	private function buildTripData(Request $request): TripDataDTO
	{
		return new TripDataDTO(
			agenceDepartId: (int) $request->request->get('depart_id'),
			agenceArriveeId: (int) $request->request->get('destination_id'),
			gdhDepart: DateTimeFormatter::getDatetimeFormat(
				(string) $request->request->get('date_depart'),
				(string) $request->request->get('heure_depart'),
			),
			gdhArrivee: DateTimeFormatter::getDatetimeFormat(
				(string) $request->request->get('date_arrivee'),
				(string) $request->request->get('heure_arrivee'),
			),
			placesDisponibles: (int) $request->request->get('places_disponibles')
		);
	}

	/**
	 * Redirects anonymous visitors to the login page.
	 *
	 * @param Response $response Outgoing HTTP response to redirect.
	 * @return Response|null The redirect response, or null when the visitor is
	 *                        logged in and the action can continue.
	 */
	private function redirectAnonymousVisitor(Response $response): ?Response
	{
		if ($this->isLoggedIn()) {
			return null;
		}

		return $this->redirect($response, '/login');
	}

	/**
	 * Builds the 404 response returned when the requested trip does not exist.
	 *
	 * @return Response The 404 response.
	 */
	private function tripNotFound(): Response
	{
		return new Response('Trajet introuvable.', Response::HTTP_NOT_FOUND);
	}

	/**
	 * Renders the trip creation page with the logged-in user's info,
	 * the agency list and an optional error message (shared by GET and POST).
	 *
	 * @param Response   $response Outgoing HTTP response to fill with the page.
	 * @param string|null $error   Optional error message to display.
	 * @return Response The rendered trip creation page, or a redirect to /login.
	 */
	private function renderCreateTrip(Response $response, ?string $error = null): Response
	{
		$user = $this->userModel->getUserById((int) $this->currentUserId());

		if ($user === false) {
			return $this->redirect($response, '/login');
		}

		$userInfos = new UserDTO(
			(int) $user['id'],
			(string) $user['prenom'],
			(string) $user['nom'],
			(string) $user['email'],
			(string) $user['telephone']
		);

		$data = [
			'userInfos' => $userInfos,
			'agencies' => $this->agencyModel->getAllAgencies(),
		];

		if ($error !== null) {
			$data['error'] = $error;
		}

		return $this->render('create-trip', $data);
	}

	/**
	 * Renders the trip update page with the current trip values, the agency
	 * list and an optional error message (shared by GET and POST).
	 *
	 * @param Response               $response    Outgoing HTTP response to fill with the page.
	 * @param TripToUpdateDetailsDTO $tripToUpdate The trip to update.
	 * @param string|null            $error       Optional error message to display.
	 * @return Response The rendered trip update page.
	 */
	private function renderUpdateTrip(
		Response $response,
		TripToUpdateDetailsDTO $tripToUpdate,
		?string $error = null
	): Response {
		$data = [
			'trip' => $tripToUpdate,
			'agencies' => $this->agencyModel->getAllAgencies(),
		];

		if ($error !== null) {
			$data['error'] = $error;
		}

		return $this->render('update-trip', $data);
	}

	/**
	 * Validates the two agency selects.
	 *
	 * @param Request $request Incoming HTTP request.
	 * @return string|null An error message when the agencies are invalid, null otherwise.
	 */
	private function validateAgencies(Request $request): ?string
	{
		$depart = (int) $request->request->get('depart_id');
		$destination = (int) $request->request->get('destination_id');

		if ($depart === 0 || $destination === 0) {
			return "L'agence de départ et l'agence d'arrivée sont obligatoires.";
		}

		if ($depart === $destination) {
			return 'Les agences doivent être différentes.';
		}

		return null;
	}

	/**
	 * Validates the departure and arrival date/time: both in the future,
	 * arrival strictly after departure.
	 *
	 * @param Request $request Incoming HTTP request.
	 * @return string|null An error message when the dates are invalid, null otherwise.
	 */
	private function validateDatesTimes(Request $request): ?string
	{
		$dateDepart = (string) $request->request->get('date_depart');
		$heureDepart = (string) $request->request->get('heure_depart');
		$dateArrivee = (string) $request->request->get('date_arrivee');
		$heureArrivee = (string) $request->request->get('heure_arrivee');

		if ($dateDepart === '' || $heureDepart === '' || $dateArrivee === '' || $heureArrivee === '') {
			return "Le départ et l'arrivée (date et heure) sont obligatoires.";
		}

		try {
			$gdhDepart = DateTimeFormatter::getDatetimeFormat($dateDepart, $heureDepart);
			$gdhArrivee = DateTimeFormatter::getDatetimeFormat($dateArrivee, $heureArrivee);
		} catch (\Exception $e) {
			return 'Les dates saisies ne sont pas valides.';
		}

		if ($gdhDepart <= new DateTimeImmutable()) {
			return "La date et l'heure de départ doivent être postérieures à maintenant.";
		}

		if ($gdhDepart >= $gdhArrivee) {
			return "La date et l'heure d'arrivée doivent être postérieures à celles du départ.";
		}

		return null;
	}
}
