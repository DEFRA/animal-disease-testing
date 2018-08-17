<?php

namespace ahvla\entity\pvsUser;

use ahvla\entity\pvsPractice\PvsPractice;
use Illuminate\Database\Eloquent\Model as Eloquent;

class PvsUser extends Eloquent
{
    protected $table = 'pvs_users';

    /**
     * @return PvsPractice|mixed
     */
    public function getPractice()
    {
        return PvsPractice::where('id','=', $this->practice_id)
            ->get()
            ->first();
    }
}