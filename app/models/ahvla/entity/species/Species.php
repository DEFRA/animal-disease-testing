<?php

namespace ahvla\entity\species;

use Illuminate\Database\Eloquent\Model as Eloquent;

/*
 * Represents Species class, e.g. Cow, Pig ...etc.
 *
 * @author Kai Chan <kai.chan@wtg.co.uk>
 */

class Species extends Eloquent
{

    protected $table = 'species';

    /**
     * @param string $lims_code
     * @param string $description
     * @return Species
     */
    public static function newObject($lims_code, $description)
    {
        $species = new Species();
        $species->lims_code = $lims_code;
        $species->description = $description;
        return $species;
    }

    public function getLimsCode()
    {
        return $this->lims_code;
    }

    public function getDescription()
    {
        return $this->description;
    }


}