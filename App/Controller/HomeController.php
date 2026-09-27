<?php

namespace App\Controller;

use App\Model\Trip\TripModel;
use App\Model\Trip\TripResponseDTO;
use App\Model\User\UserRole;
use Core\Database;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Home page controller: renders the landing page.
 */
class HomeController extends AbstractController
{
    private TripModel $tripModel;

    /**
     * The router instantiates controllers without arguments,
     * so each controller builds its own dependencies here.
     */
    public function __construct()
    {
        $this->tripModel = new TripModel(Database::getInstance()->connection());
    }

    /**
     * Renders the home page with upcoming trips, or redirects an admin
     * to the dashboard.
     *
     * @param Request  $request  Incoming HTTP request (unused for now).
     * @param Response $response Outgoing HTTP response to fill with the page.
     * @return Response The home page, or a redirect for an admin.
     */
	public function index(Request $request, Response $response): Response
	{
		// The controller decides which page a role lands on, never the template:
		// an admin has no reason to see the public trip listing.
		if ($this->hasRole(UserRole::Admin)) {
			return $this->redirect($response, '/admin', Response::HTTP_FOUND);
		}

		$trips = $this->tripModel->getTrips();

        $tripsResponse = array_map(
            fn (array $trip): TripResponseDTO => $this->toTripResponse($trip),
            $trips
        );

        return $this->render('home', ['trips' => $tripsResponse]);
    }

    /**
     * Turns a trip row coming from the database into a TripResponseDTO,
     * adding whether the logged-in user is its author.
     *
     * @param array<string, mixed> $trip One trip row returned by TripModel::getTrips().
     * @return TripResponseDTO The trip as displayed in the home page listing.
     */
    private function toTripResponse(array $trip): TripResponseDTO
    {
        return new TripResponseDTO(
            id: (int) $trip['id'],
            departureAgency: (string) $trip['depart'],
            departureDate: (string) $trip['date_depart'],
            departureTime: (string) $trip['heure_depart'],
            destination: (string) $trip['destination'],
            arrivalDate: (string) $trip['date_arrivee'],
            arrivalTime: (string) $trip['heure_arrivee'],
            availableSeats: (int) $trip['places_disponibles'],
            userIsOwner: $this->currentUserId() === (int) $trip['users_id']
        );
    }
}
