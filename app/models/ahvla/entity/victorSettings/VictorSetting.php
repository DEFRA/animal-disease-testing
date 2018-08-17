<?php

namespace ahvla\entity\victorSettings;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * VictorSetting class
 */
class VictorSetting extends Eloquent {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */	
	protected $table = 'victor_settings';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'displayLoginPageMessage',
			'disableLogin',
			'numPreviouslyDisallowedPasswords',
			'numDaysTilPasswordExpires',
			'numWrongPasswordsBeforeSuspension',
			'numDaysOfSuspension',
			'forgotPasswordMaxRequests',
			'forgotPasswordMinutesSuspended',
	];
}
