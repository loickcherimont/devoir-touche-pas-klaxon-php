<?php

namespace App\Model\User;

/**
 * UserRole
 *
 * The only roles a user account can hold, matching the values allowed
 * in the `role` column of the `users` table.
 *
 * Prefer this enum over raw strings:
 * - a role value is written in exactly one place;
 * - `tryFrom()` validates what comes from the database instead of
 *   trusting it, and returns null for an unknown value;
 * - a typo on a case is a fatal error instead of a silent blank page.
 */
enum UserRole: string
{
	case Admin = 'admin';
	case User = 'user';
}
