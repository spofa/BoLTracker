<?php

class AuthController extends BaseController {

  public function postAuthenticate() {
    try
    {
        // Login credentials
        $credentials = array(
            'email'    => Input::get('email'),
            'password' => Input::get('password'),
        );

        // Authenticate the user
        $user = Sentry::authenticate($credentials, Input::get('remember-me'));
    }
    catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
    {
        Session::flash('error', 'Login field is required.');
        return Redirect::back();
    }
    catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
    {
        Session::flash('error', 'Password field is required.');
        return Redirect::back();
    }
    catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
    {
        Session::flash('error', 'Wrong password, try again.');
        return Redirect::back();
    }
    catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
    {
        Session::flash('error', 'User was not found.');
        return Redirect::back();
    }
    catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
    {
        Session::flash('error', 'User is not activated.');
        return Redirect::back();
    }

    // The following is only required if the throttling is enabled
    catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
    {
        Session::flash('error', 'User is suspended.');
        return Redirect::back();
    }
    catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
    {
        Session::flash('error', 'User is banned.');
        return Redirect::back();
    }

    return Redirect::to('dashboard');

  }

  public function getRegister() {

    try
    {
        // Let's register a user.
        $user = Sentry::register(array(
            'email'    => 'bilbao@gmx.ru',
            'password' => 'randompw',
        ));

        // Let's get the activation code
        $activationCode = $user->getActivationCode();

        // Send activation code to the user so he can activate the account
    }
    catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
    {
        echo 'Login field is required.';
    }
    catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
    {
        echo 'Password field is required.';
    }
    catch (Cartalyst\Sentry\Users\UserExistsException $e)
    {
        echo 'User with this login already exists.';
    }
  }

  public function getLogout() {
    Sentry::logout();
  }

}