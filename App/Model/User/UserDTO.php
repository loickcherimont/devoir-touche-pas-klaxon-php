<?php

namespace App\Model\User;

final class UserDTO
{
	public function __construct(
		public readonly int $id,
		public readonly string $prenom,
		public readonly string $nom,
		public readonly string $email,
		public readonly string $telephone,
	){}
}