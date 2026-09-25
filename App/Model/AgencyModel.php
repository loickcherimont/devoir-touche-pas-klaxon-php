<?php

namespace App\Model;

use Core\AbstractModel;

/**
 * AgencyModel
 *
 * Data access for users.
 */
class AgencyModel extends AbstractModel
{ 

    /**
     * Returns the all agencies from database.
     *
     * @return array<string, mixed>|false All agencies as an associative array, or false when no agencies found
     */
    public function getAllAgencies(): array|false
    {
        return $this->findAll('SELECT * FROM agencies');
    }
}
