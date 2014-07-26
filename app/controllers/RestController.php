<?php

use Carbon\Carbon;

class RestController extends BaseController {

	public function getNewplayer() {
		$scriptCheck = ActiveScript::where('hwid', '=', Input::get("hwid"))->where('script_name', '=', preg_replace("/[^a-z0-9.]+/i", "", Input::get("scriptName")))->where("running", "=", "1")->get();
		
		if (!is_null($scriptCheck)) {
			foreach ($scriptCheck as $player) {
				$player->running = 0;
				$player->save();
			}    
		}

		// check for record for today
		$scriptRun = ScriptRun::where('day', '>', Carbon::today()->subDay())->where('script_name', '=', Input::get('scriptName'))->first();

		if ($scriptRun) {
			$scriptRun->increment('runs');
			echo 'Adding a Script run. <br>';
		} else {
			$newScript = new ScriptRun;
			$newScript->owner_id = Input::get('id');
			$newScript->script_name = preg_replace("/[^a-z0-9.]+/i", "", Input::get("scriptName"));
			$newScript->runs = 1;
			$newScript->day = Carbon::today();
			$newScript->save();
			echo 'Creating new script record. <br>';
		}

		// Check for matching HWID in hwid database
		$hwid = Hwid::where('day','>', Carbon::today()->subDay())->where('hwid', '=', Input::get('hwid'))->where('script_name', '=', Input::get('scriptName'))->first();
		// If no record shows up proceed.
		if (!$hwid) {
			// Insert their HWID into the db.
			$hwid = new Hwid;
			$hwid->owner_id = Input::get('id');
			$hwid->script_name = preg_replace("/[^a-z0-9.]+/i", "", Input::get("scriptName"));
			$hwid->hwid = Input::get('hwid');
			$hwid->day = Carbon::today();
			$hwid->country = GeoIP::getLocation()['country'];
			echo GeoIP::getLocation()['country'] . '<br>';
			$hwid->save();
			// Get the record for today..
			$unique = UniqueRun::where('day','>', Carbon::today()->subDay())->where('script_name', '=', Input::get('scriptName'))->first();
			// If it exists add one to the runs.
			if ($unique) {
				$unique->increment('runs');
				echo 'Adding a run. <br>';
			} else {
				// Otherwise create a new record. 
				$newUser = new UniqueRun;
				$newUser->owner_id = Input::get('id');
				$newUser->script_name = preg_replace("/[^a-z0-9.]+/i", "", Input::get("scriptName"));
				$newUser->runs = 1;
				$newUser->day = Carbon::today();
				$newUser->save();
				echo 'Creating new record. <br>';
			}
		} else {
			echo 'This hwid has ran once today. <br>';
		}

		$activeScript = new ActiveScript();
		$activeScript->owner_id = Input::get('id');
		$activeScript->hwid = Input::get("hwid");
		$activeScript->script_name = preg_replace("/[^a-z0-9.]+/i", "", Input::get("scriptName"));
		$activeScript->running = 1;
		$activeScript->save(); 

		return 'success';
	} 

	public function getDeleteplayer() {
		$activeScript = ActiveScript::where('hwid', '=', Input::get("hwid"))->where('script_name', '=', preg_replace("/[^a-z0-9.]+/i", "", Input::get("scriptName")))->first();
		$activeScript->running = 0;
		$activeScript->save();
		return 'success';
	}

	/*
	 * This gets the scripts runs by day. Needs to be reworked like uniqueruns.
	 */
	public function getOldscriptruns($scriptName) {
		$scriptDates = Script::where('created_at','>',Carbon::today()->subWeek())->where('script_name', '=', $scriptName)->where("owner_id", "=", Sentry::getUser()->id)->groupBy(DB::raw('DAY(created_at)'))->get(array('script_name', 'created_at'));
		$scripts = Script::where('created_at','>',Carbon::today()->subWeek())->where('script_name', '=', $scriptName)->where("owner_id", "=", Sentry::getUser()->id)->get(array('script_name', 'created_at'));
		$datesArray = array();

		foreach($scriptDates as $dates) {
			$canRun = true;

			foreach($datesArray as $date) {
				if (in_array(date('Y-m-d', strtotime($dates->created_at)), $date)) {
					$canRun = false;
				}
			}
			if ($canRun) {
				array_push($datesArray, array(
					'period' => date('Y-m-d', strtotime($dates->created_at)),
					$dates->script_name => 0
				));
			}	
		}
		
		foreach ($scripts as $script) {
			
			$tempDate = date('Y-m-d', strtotime($script->created_at));

			for ($i = 0; $i < count($datesArray); $i++) { 
				if ($tempDate == $datesArray[$i]['period']) {
					$datesArray[$i][$script->script_name]++;  
				}
			}
		}

		return $datesArray;
	}

	public function getScriptruns($scriptName) {
		$scriptRuns = ScriptRun::where('day', '>', Carbon::today()->subWeek())->where('script_name', '=', $scriptName)->get();
		$datesArray = array();

		foreach ($scriptRuns as $script) {
			array_push($datesArray, array(
				'period' => $script->day,
				$script->script_name => $script->runs
			));
		}

		return $datesArray;
	}

	/*
	 * Gets the unique total users. Reworked.
	 */
	public function getUniqueusers($scriptName) {
		$uniqueUsers = Hwid::where("script_name", '=', $scriptName)->where("owner_id", "=", Sentry::getUser()->id)->groupBy("hwid")->get(array('hwid'));

		return count($uniqueUsers);
	}

	public function getActiveusers($scriptName) {
		$scriptDates = ActiveScript::where('created_at','>',Carbon::today()->subWeek())->where('script_name', '=', $scriptName)->where("owner_id", "=", Sentry::getUser()->id)->where("running", "=", "1")->groupBy(DB::raw('DAY(created_at)'))->get(array('script_name', 'created_at'));
		$scripts = ActiveScript::where('script_name', '=', $scriptName)->where("owner_id", "=", Sentry::getUser()->id)->where("running", "=", "1")->get(array('script_name', 'created_at'));
		$datesArray = array();

		foreach($scriptDates as $dates) {
			array_push($datesArray, array(
					'period' => date('Y-m-d', strtotime($dates->created_at)),
					$dates->script_name => 0
				));
		}
		
		foreach ($scripts as $script) {
			
			$tempDate = date('Y-m-d', strtotime($script->created_at));

			for ($i = 0; $i < count($datesArray); $i++) { 
				if ($tempDate == $datesArray[$i]['period']) {
					$datesArray[$i][$script->script_name]++;  
				}
			}
		}

		return $datesArray;
	}
	/*
	 * Deprecated: use the getUniqueruns now.
	 */
	public function getOlduniqueruns($scriptName) {
		$scriptDates = Script::where('created_at','>',Carbon::today()->subWeek())->where('script_name', '=', $scriptName)->where("owner_id", "=", Sentry::getUser()->id)->groupBy("hwid")->groupBy(DB::raw('DAY(created_at)'))->get(array('script_name', 'created_at'));
		$scripts = Script::where('created_at','>',Carbon::today()->subWeek())->where('script_name', '=', $scriptName)->groupBy("hwid")->where("owner_id", "=", Sentry::getUser()->id)->get(array('script_name', 'created_at'));
		$datesArray = array();

		foreach($scriptDates as $dates) {
			$canRun = true;

			foreach($datesArray as $date) {
				if (in_array(date('Y-m-d', strtotime($dates->created_at)), $date)) {
					$canRun = false;
				}
			}
			if ($canRun) {
				array_push($datesArray, array(
					'period' => date('Y-m-d', strtotime($dates->created_at)),
					$dates->script_name => 0
				));
			}	
		}
		
		foreach ($scripts as $script) {
			
			$tempDate = date('Y-m-d', strtotime($script->created_at));

			for ($i = 0; $i < count($datesArray); $i++) { 
				if ($tempDate == $datesArray[$i]['period']) {
					$datesArray[$i][$script->script_name]++;  
				}
			}
		}

		return $datesArray;
	}

	/*
	 * New Unique runs.
	 */

	public function getUniqueruns($scriptName) {
		$scripts = UniqueRun::where('day','>', Carbon::today()->subWeek())->where('script_name', '=', $scriptName)->get();
		$datesArray = array();

		foreach ($scripts as $script) {
			array_push($datesArray, array(
				'period' => $script->day,
				$script->script_name => $script->runs
			));
		}

		return $datesArray;
	}

	public function getTotaluniqueusers() {
		$unique = Hwid::where("owner_id", "=", Sentry::getUser()->id)->groupBy("hwid")->get(array('hwid'));

		return count($unique);
	}

	public function getTotalruns() {
		$users = ScriptRun::where('owner_id', '=', Sentry::getUser()->id)->get(array("runs"));
		$count = 0;

		foreach ($users as $user) {
			$count += $user->runs;
		}

		return $count;
	}

	public function getTotalunique() {
		$users = UniqueRun::where('owner_id', '=', Sentry::getUser()->id)->get(array("runs"));
		$count = 0;

		foreach ($users as $user) {
			$count += $user->runs;
		}

		return $count;
	}

	public function postCreatescript() {
		$nameExists = UserScript::where('script_name', '=', Input::get('scriptName'))->first();

		if ($nameExists) {
			Session::flash('error', "There is already a script with that name.");
			return Redirect::back();
		}

		$newScript = new UserScript;
		$newScript->script_name = strip_tags(Input::get('scriptName'));
		$newScript->owner_id = Input::get('id');
		$newScript->save();

		Session::flash('success', "You created the script: " . Input::get('scriptName'));
		return Redirect::back();
	}

	public function getDeletescript($scriptName) {
		$script = UserScript::where('script_name', "=", $scriptName)->first();

		if ($script->owner_id == Sentry::getUser()->id) {
			$script->delete();
			Session::flash('success', 'You deleted the script: ' . $scriptName);
			return Redirect::back();
		} else {
			Session::flash('error', 'You are not authorized to do that.');
			return Redirect::back();
		}
	}

	public function postUpdatescript() {
		$nameExists = UserScript::where('script_name', '=', Input::get('scriptName'))->first();

		if ($nameExists) {
			Session::flash('error', "There is already a script with that name.");
			return Redirect::back();
		}

		$script = UserScript::where('script_name', "=", Input::get('oldScriptName'))->first();

		if ($script->owner_id == Sentry::getUser()->id) {
			$script->script_name = strip_tags(Input::get('scriptName'));
			$script->save();
			Session::flash('success', 'You updated the script: ' . Input::get('oldScriptName') . " to: " . $script->script_name);
			return Redirect::back();
		} else {
			Session::flash('error', 'You are not authorized to do that.');
			return Redirect::back();
		}
	}

	/*
	 * This converts the old database records into the new version. This is only to be used when merging the data.
	 */ 
	public function getConvert() {

		if (Sentry::getUser()->id == 1) {

			$scriptNames = UserScript::all();

			foreach ($scriptNames as $scriptName) {

				$scriptDates = Script::where('created_at','>',Carbon::today()->subWeek())->where('script_name', '=', $scriptName->script_name)->where("owner_id", "=", $scriptName->owner_id)->groupBy("hwid")->groupBy(DB::raw('DAY(created_at)'))->get(array('script_name', 'hwid', 'created_at'));
				$scripts = Script::where('created_at','>',Carbon::today()->subWeek())->where('script_name', '=', $scriptName->script_name)->groupBy("hwid")->where("owner_id", "=", $scriptName->owner_id)->get(array('script_name', 'hwid', 'created_at'));
				$scriptHwid = Script::where('created_at', '>', Carbon::today()->subWeek())->groupBy('hwid')->get();
				$datesArray = array();

				foreach($scriptDates as $dates) {
					$canRun = true;

					foreach($datesArray as $date) {
						if (in_array(date('Y-m-d', strtotime($dates->created_at)), $date)) {
							$canRun = false;
						}
					}
					if ($canRun) {
						array_push($datesArray, array(
							'period' => date('Y-m-d', strtotime($dates->created_at)),
							$dates->script_name => 0,
							'hwid' => $dates->hwid
						));
					}	
				}
				
				foreach ($scripts as $script) {
					
					$tempDate = date('Y-m-d', strtotime($script->created_at));

					for ($i = 0; $i < count($datesArray); $i++) { 
						if ($tempDate == $datesArray[$i]['period']) {
							$datesArray[$i][$script->script_name]++;  
						}
					}
				}

				echo var_dump($datesArray);

				for ($i = 0; $i < count($datesArray); $i++) { 
					$unique = new UniqueRun;
					$unique->owner_id = $scriptName->owner_id;
					$unique->script_name = $scriptName->script_name;
					$unique->runs = $datesArray[$i][$scriptName->script_name];
					$unique->day = $datesArray[$i]['period'];
					$unique->save();
				}

			}

			foreach ($scriptHwid as $script) {
				$hwid = new Hwid;
				$hwid->owner_id = $script->owner_id;
				$hwid->script_name = $script->script_name;
				$hwid->hwid = $script->hwid;
				$hwid->day = date('Y-m-d', strtotime($script->created_at));
				$hwid->save();
			}
		} else {
			return "You are not authorized for this action.";
		}

	}

	public function getConvertruns() {

		if (Sentry::getUser()->id == 1) {
			$scriptNames = UserScript::all();

			foreach ($scriptNames as $scriptName) {

				$scriptDates = Script::where('created_at','>',Carbon::today()->subWeek())->where('script_name', '=', $scriptName->script_name)->where("owner_id", "=", $scriptName->owner_id)->groupBy(DB::raw('DAY(created_at)'))->get(array('script_name', 'created_at'));
				$scripts = Script::where('created_at','>',Carbon::today()->subWeek())->where('script_name', '=', $scriptName->script_name)->where("owner_id", "=", $scriptName->owner_id)->get(array('script_name', 'created_at'));
				$datesArray = array();

				foreach($scriptDates as $dates) {
					$canRun = true;

					foreach($datesArray as $date) {
						if (in_array(date('Y-m-d', strtotime($dates->created_at)), $date)) {
							$canRun = false;
						}
					}
					if ($canRun) {
						array_push($datesArray, array(
							'period' => date('Y-m-d', strtotime($dates->created_at)),
							$dates->script_name => 0
						));
					}	
				}
				
				foreach ($scripts as $script) {
					
					$tempDate = date('Y-m-d', strtotime($script->created_at));

					for ($i = 0; $i < count($datesArray); $i++) { 
						if ($tempDate == $datesArray[$i]['period']) {
							$datesArray[$i][$script->script_name]++;  
						}
					}
				}

				echo var_dump($datesArray);

				for ($i = 0; $i < count($datesArray); $i++) { 
					$scriptRuns = new ScriptRun;
					$scriptRuns->owner_id = $scriptName->owner_id;
					$scriptRuns->script_name = $scriptName->script_name;
					$scriptRuns->runs = $datesArray[$i][$scriptName->script_name];
					$scriptRuns->day = $datesArray[$i]['period'];
					$scriptRuns->save();
				}

			}
		} else {
			return "You are not authorized for this action.";
		}
	}

}

?>
