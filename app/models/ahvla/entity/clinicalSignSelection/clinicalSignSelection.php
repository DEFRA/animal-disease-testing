<?php

namespace ahvla\entity\clinicalSignSelection;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ClinicalSignSelection extends Eloquent {

    protected $table = 'clinical_sign_selections';

    public function submission()
    {
        return $this->belongsTo('ahvla\entity\submission\Submission');
    }
}