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
		$script->hwid = Input::get("hwid");
		$script->script_name = Input::get("scriptName");
		$script->save();

		$activeScript = new ActiveScript();
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

}

?>
