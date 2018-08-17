<?php

namespace ahvla\entity\informationMessages;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * InformationMessage class
 */
class InformationMessage extends Eloquent {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */	
	protected $table = 'information_messages';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'title', 'content', 'type'];
}
