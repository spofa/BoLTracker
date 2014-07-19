<?php

	View::composer('layouts.master', function($view)
	{
		if (Sentry::check()) {
			$scripts = UserScript::where('owner_id', '=', Sentry::getUser()->id)->get();

		    $view->with('scripts', $scripts);
		}
	});

?>