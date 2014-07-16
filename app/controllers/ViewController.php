<?php

class ViewController extends BaseController {
	protected $layout = 'layouts.master';

	public function dashboard() {
		$layout->content = View::make('dashboard');
	}

	public function login() {
		return View::make('login');
	}

}

?>