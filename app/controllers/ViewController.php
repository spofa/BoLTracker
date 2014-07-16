<?php

class ViewController extends BaseController {
	protected $layout = 'layouts.master';

	public function dashboard() {
		if (Sentry::check()) {
			$this->layout->content = View::make('dashboard');
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