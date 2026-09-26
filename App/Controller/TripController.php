<?php

namespace App\Controller;

use App\DateTimeFormatter;
use App\Model\AgencyModel;
use App\Model\Trip\TripDTO;
use App\Model\Trip\TripModel;
use App\Model\User\UserDTO;
use App\Model\User\UserModel;
use Core\Database;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TripController
 *
 * Manages the trip creation page. Access is restricted to logged-in users.
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

		$createTripDTO = new TripDTO(
			(int) $request->request->get('depart_id'),
			(int) $request->request->get('destination_id'),
			DateTimeFormatter::getDatetimeFormat(
				(string) $request->request->get('date_depart'),
				(string) $request->request->get('heure_depart'),
			),
			DateTimeFormatter::getDatetimeFormat(
				(string) $request->request->get('date_arrivee'),
				(string) $request->request->get('heure_arrivee'),
			),
			(int) $request->request->get('places_disponibles')
		);

		$this->tripModel->saveTrip($createTripDTO, (int) $_SESSION['auth_user_id']);
		return $this->redirect($response, '/');
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
		$user = $this->userModel->getUserById((int) $_SESSION['auth_user_id']);

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