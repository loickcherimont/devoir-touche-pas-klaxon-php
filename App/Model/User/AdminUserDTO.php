<?php

namespace App\Model\User;

/**
 * AdminUserDTO
 *
 * Immutable data transfer object for carrying one user of admin dashboard
 * listing in read-only.
 *
 */
final class AdminUserDTO
{
	/**
	 * @param int           $id     Id of the user
	 * @param string        $prenom  First name of the user
	 * @param string        $nom     Last name of the user
	 * @param string        $email   Email address of the user
	 * @param UserRole|null $role   Role of the user, or null when unknown
	 */
	public function __construct(
		public readonly int $id,
		public readonly string $prenom,
		public readonly string $nom,
		public readonly string $email,
		public readonly ?UserRole $role
	) {}
}