<?php

namespace App\Model\User;

/**
 * UserDTO
 *
 * Immutable data transfer object carrying the user information
 * needed by the templates, without exposing the mot_de_passe column.
 */
final class UserDTO
{
	/**
	 * @param int    $id        Id of the user
	 * @param string $prenom    First name
	 * @param string $nom       Last name
	 * @param string $email     Email address
	 * @param string $telephone Phone number
	 */
	public function __construct(
		public readonly int $id,
		public readonly string $prenom,
		public readonly string $nom,
		public readonly string $email,
		public readonly string $telephone,
	){}
}