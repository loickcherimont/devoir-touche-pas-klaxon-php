<?php

namespace App\Controller;

use App\Model\Trip\TripModel;
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
     * Renders the home page with upcoming trips.
     *
     * @param Request  $request  Incoming HTTP request (unused for now).
     * @param Response $response Outgoing HTTP response to fill with the page.
     * @return Response The response containing the rendered home page.
     */
    public function index(Request $request, Response $response): Response
    {
        $trips = $this->tripModel->getTrips();

        return $this->render('home', ['trips' => $trips]);
    }
}
