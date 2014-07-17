<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


/*
 * Routes for front-end related work
 */
Route::get('/', function()
{
	return Redirect::to('dashboard');
});

// Login route.
Route::get('auth/login', array('as' => 'login', 'uses' => 'ViewController@login'));
// Dashboard route.
Route::get('dashboard', array('as' => 'dashboard', 'uses' => 'ViewController@dashboard'));
// Script view route.
Route::get('script/{scriptName}', 'ViewController@script');

/*
 * Auth related routes
 */

// Auth REST route.
Route::controller('auth', 'AuthController');


/*
 * REST Route
 */
Route::controller('rest', 'RestController');