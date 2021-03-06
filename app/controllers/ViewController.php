<?php

class ViewController extends BaseController {
	protected $layout = 'layouts.master';

	public function dashboard() {
		if (Sentry::check()) {

			$scripts = UserScript::where('owner_id', '=', Sentry::getUser()->id)->get();

			$endDate = date('Y-m-d');
			$startDate = date('Y-m-d', strtotime("-6 day"));

			// Make sure to send along to script names for the menu!

			$this->layout->content = View::make('dashboard')->with(array(
				"scripts" => $scripts,
				"startDate" => $startDate,
				"endDate" => $endDate
			));
		} else {
			return Redirect::to('/auth/login');
		}
	}

	public function login() {
		if (Sentry::check()) {
			return Redirect::to('dashboard');
		} else {
			return View::make('login');
		}
	}

	public function register() {
		if (Sentry::check()) {
			return Redirect::to('dashboard');
		} else {
			return View::make('register');
		}
	}

	public function newscript() {
		if (Sentry::check()) {

			$this->layout->content = View::make('newscript');
		} else {
			return Redirect::to('/auth/login');
		}
	}

	public function script($scriptName) {
		if (Sentry::check()) {
			$endDate = date('Y-m-d');
			$startDate = date('Y-m-d', strtotime("-6 day"));

			$this->layout->with('scriptName', $scriptName);
			// Make sure to send along to script names for the menu!
			$this->layout->content = View::make('script')->with(array(
				"scriptName" => $scriptName,
				"startDate" => $startDate,
				"endDate" => $endDate
			));
		} else {
			return Redirect::to('/auth/login');
		}
	}

	public function editscript() {
		if (Sentry::check()) {
			$scripts = UserScript::where('owner_id', '=', Sentry::getUser()->id)->get();

			$this->layout->content = View::make('editscripts')->with('scripts', $scripts);
		} else {	
			return Redirect::to('/auth/login');
		}
	}

	public function tutorial() {
		if (Sentry::check()) {
			$this->layout->content = View::make('tutorial');
		} else {
			return Redirect::to('/auth/login');
		}
	}

}

?>