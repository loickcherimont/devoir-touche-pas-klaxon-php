<?php

namespace App\Model;

use Core\AbstractModel;

/**
 * AgencyModel
 *
 * Data access for agencies.
 */
class AgencyModel extends AbstractModel
{ 

    /**
     * Returns every agency from the database.
     *
     * @return array<array<string, mixed>> All agencies as associative arrays
     */
    public function getAllAgencies(): array
    {
        return $this->findAll('SELECT * FROM agencies');
    }
}
