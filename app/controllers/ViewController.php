<?php

class ViewController extends BaseController {
	protected $layout = 'layouts.master';

	public function dashboard() {
		if (Sentry::check()) {

			$scripts = UserScript::where('owner_id', '=', Sentry::getUser()->id)->get();
			
			$endDate = date('Y-m-d');
			$startDate = date('Y-m-d', strtotime("-7 day"));

			$this->layout->content = View::make('dashboard')->with(array(
				"scripts" => $scripts,
				"startDate" => $startDate,
				"endDate" => $endDate
 				));
		} else {
			return Redirect::to('auth/login');
		}
	}

	public function login() {
		if (Sentry::check()) {
			return Redirect::to('dashboard');
		} else {
			return View::make('login');
		}
	}

}

?>