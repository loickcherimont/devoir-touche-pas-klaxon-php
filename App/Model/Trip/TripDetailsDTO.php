<?php

namespace App\Model\Trip;

/**
 * Public details of a trip author, displayed to authenticated users.
 *
 * This DTO deliberately contains only the columns needed by the modal.
 */
final class TripDetailsDTO
{
    public function __construct(
        public readonly string $authorFirstName,
        public readonly string $authorLastName,
        public readonly string $phone,
        public readonly string $email,
        public readonly int $availableSeats,
    ) {}
}
