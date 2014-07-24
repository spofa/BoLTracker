<?php

class UniqueRun extends Eloquent {
		/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'unique_runs';

	public function getDatesAttribute($value) {
    	$this->attributes['created_at'] = Carbon::createFromFormat('m/d/Y', $value);
   	}
}

?>