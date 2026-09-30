<?php

namespace App\Service;

use App\DateTimeFormatter;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;

/**
 * Validates the trip form fields shared by the creation and update pages.
 */
final class TripValidator
{
	/**
	 * Validates the submitted agencies and dates, returning the first error met.
	 *
	 * @param Request $request Incoming HTTP request containing the trip data
	 * @return string|null An error message, or null when everything is valid
	 */
	public function validate(Request $request): ?string
	{
		return $this->validateAgencies($request)
			?? $this->validateDatesTimes($request);
	}

	/**
	 * Validates the two agency selects.
	 *
	 * @param Request $request Incoming HTTP request
	 * @return string|null An error message when the agencies are invalid, null otherwise
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
	 * @param Request $request Incoming HTTP request
	 * @return string|null An error message when the dates are invalid, null otherwise
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
		} catch (\Exception $exception) {
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