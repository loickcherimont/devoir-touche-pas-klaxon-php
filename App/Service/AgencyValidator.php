<?php

namespace App\Service;

use App\Model\Agency\AgencyModel;

/**
 * Validates the agency fields submitted by the admin dashboard forms.
 */
final class AgencyValidator
{
	/**
	 * Validates an agency name, rejecting duplicates except on the edited agency.
	 *
	 * @param string      $nom         The submitted agency name
	 * @param int         $id          Id of the agency being updated
	 * @param AgencyModel $agencyModel Used to look up existing agencies
	 * @return string|null An error message, or null when the name is valid
	 */
	public function validate(string $nom, int $id, AgencyModel $agencyModel): ?string
	{
		if ($nom === '') {
			return "Vous devez fournir un nom d'agence.";
		}

		$existingAgency = $agencyModel->getAgencyByNom($nom);

		if ($existingAgency !== null && $existingAgency->id !== $id) {
			return 'Une agence existe déjà avec ce nom. Essayez avec un nom différent.';
		}

		return null;
	}
}