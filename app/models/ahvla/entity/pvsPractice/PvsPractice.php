<?php

namespace ahvla\entity\pvsPractice;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentry\Users\Eloquent\User;
use Illuminate\Database\Eloquent\SoftDeletingTrait;


class PvsPractice extends Model{

    use SoftDeletingTrait;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $table = 'pvs_practices';

    /**
     * Many-to-many connection to users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany('Cartalyst\Sentry\Users\Eloquent\User', 'pvs_users', 'practice_id', 'user_id');
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getLimsCode()
    {
        return $this->lims_code;
    }

    /**
     * Gets the first created user for a practice
     *
     * @return User
     */
    public function getFirstUser() {
        return $this->users()->orderBy('id', 'asc')->first();
    }

    /**
     * Gets the first created, active user for a practice
     *
     * @return User
     */
    public function getFirstActiveUser() {
        return $this->users()->where('activated', '=', '1')->orderBy('id', 'asc')->first();
    }
}