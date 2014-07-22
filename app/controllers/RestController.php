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

		$script = new Script();
		$script->owner_id = Input::get('id');
		$script->hwid = Input::get("hwid");
		$script->script_name = preg_replace("/[^a-z0-9.]+/i", "", Input::get("scriptName"));
		$script->save();

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

	public function getScriptruns($scriptName) {

		if ($scriptName == "all") {
			$scriptNames = Script::groupBy('script_name')->groupBy(DB::raw('DAY(created_at)'))->get(array('script_name', 'created_at'));
			$scripts = Script::get(array('script_name', 'created_at'));
			$datesArray = array();
			$finalArray = array();

			foreach($scriptNames as $scriptName) {
				array_push($datesArray, array(
					'period' => date('Y-m-d', strtotime($scriptName->created_at)),
					$scriptName->script_name => 0
				));
			}

			foreach ($scripts as $script) {
				

				$tempDate = date('Y-m-d', strtotime($script->created_at));

				for ($i = 0; $i < count($datesArray); $i++) { 
					$scriptName = (string)$script->script_name;

					if ($tempDate == $datesArray[$i]['period'] && array_key_exists($scriptName, $datesArray[$i])) {
						$datesArray[$i][$scriptName]++;  
					}
				}
			}
			// $datesArray is the first mentioned array of values.
			// finalArray is an empty array that I want to push the final values into.
			for ($i = 0; $i < count($datesArray) - 1; $i++) { 

				for ($j = $i; $j < count($datesArray); $j++) { 

					$key1 = array_keys($datesArray[$i]);
					$key2 = array_keys($datesArray[$j]);
					// First check to see if they have the same date, if they don't then no merging!
					if ($datesArray[$i]['period'] == $datesArray[$j]['period']) {
						if ($j == $i) {
							array_push($finalArray, array(
								'period' => $datesArray[$i]['period'],
								$key1[1] => $datesArray[$i][$key1[1]]
							));
						} else {
							if ($key1[1] == $key2[1]) {
								array_push($finalArray, array(
									'period' => $datesArray[$i]['period'], 
									$key1[1] => $datesArray[$i][$key1[1]] + $datesArray[$j][$key2[1]]
								));
							} else {
								array_push($finalArray, array(
									'period' => $datesArray[$i]['period'],
									$key1[1] => $datesArray[$i][$key1[1]],
									$key2[1] => $datesArray[$j][$key2[1]]	
								));
							}
						}
					}

				}
			}

			return $finalArray;

		} else {
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
		}

		return $datesArray;
	}

	public function getUniqueusers($scriptName) {
		$uniqueUsers = Script::where('created_at','>',Carbon::today()->subWeek())->where("script_name", '=', $scriptName)->where("owner_id", "=", Sentry::getUser()->id)->groupBy("hwid")->get();

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

	public function getUniqueruns($scriptName) {
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

	public function postCreatescript() {
		$nameExists = UserScript::where('script_name', '=', Input::get('scriptName'))->first();

		if ($nameExists) {
			Session::flash('error', "There is already a script with that name.");
			return Redirect::back();
		}

		$newScript = new UserScript;
		$newScript->script_name = Input::get('scriptName');
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

		$script = UserScript::where('script_name', "=", $scriptName)->first();

		if ($script->owner_id == Sentry::getUser()->id) {
			$script->script_name = Input::get('scriptName');
			$script->save();
			Session::flash('success', 'You updated the script: ' . $scriptName . " to: " . $script->script_name);
			return Redirect::back();
		} else {
			Session::flash('error', 'You are not authorized to do that.');
			return Redirect::back();
		}
	}

}

?>
