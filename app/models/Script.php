<?php

class Script extends Eloquent {
		/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'scripts';

	public function getDatesAttribute($value) {
    	$this->attributes['created_at'] = Carbon::createFromFormat('m/d/Y', $value);
   	}
}

?>