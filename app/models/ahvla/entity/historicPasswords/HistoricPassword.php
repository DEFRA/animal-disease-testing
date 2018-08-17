<?php

namespace ahvla\entity\historicPasswords;

use Illuminate\Database\Eloquent\Model;

class HistoricPassword extends Model
{
    protected $table = "historic_passwords";

    protected $fillable = ['password'];

    public function user()
    {
        return $this->belongsTo('packages\cartalyst\sentry\User');
    }
}