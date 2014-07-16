<?php

class RestController extends BaseController {

	public function getNewplayer() {
		$scriptCheck = ActiveScript::where('hwid', '=', Input::get("hwid"))->where('script_name', '=', Input::get("scriptName"))->where("running", "=", "1")->get();
		
		if (!is_null($scriptCheck)) {
			foreach ($scriptCheck as $player) {
				$player->running = 0;
				$player->save();
			}    
		}

		$script = new Script();
		$script->owner_id = Input::get('id');
		$script->hwid = Input::get("hwid");
		$script->script_name = Input::get("scriptName");
		$script->save();

		$activeScript = new ActiveScript();
		$activeScript->owner_id = Input::get('id');
		$activeScript->hwid = Input::get("hwid");
		$activeScript->script_name = Input::get("scriptName");
		$activeScript->running = 1;
		$activeScript->save(); 

		return 'success';
	} 

	public function getDeleteplayer() {
		$activeScript = ActiveScript::where('hwid', '=', Input::get("hwid"))->where('script_name', '=', Input::get("scriptName"))->first();
		$activeScript->running = 0;
		$activeScript->save();
		return 'success';
	}

	public function getScriptruns($scriptName) {
		$scriptDates = Script::where('script_name', '=', $scriptName)->groupBy(DB::raw('DAY(created_at)'))->get(array('script_name', 'created_at'));
		$scripts = Script::where('script_name', '=', $scriptName)->get(array('script_name', 'created_at'));
		$datesArray = array();

		foreach($scriptDates as $dates) {
			array_push($datesArray, array(date('Y-m-d', strtotime($dates->created_at)), 0));
		}
		
		foreach ($scripts as $script) {
			
			$tempDate = date('Y-m-d', strtotime($script->created_at));

			for ($i = 0; $i < count($datesArray); $i++) { 
				if ($tempDate == $datesArray[$i][0]) {
					$datesArray[$i][1]++;  
				}
			}
		}

		return $datesArray;

	}

}

?>
