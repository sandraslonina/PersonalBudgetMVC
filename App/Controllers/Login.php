<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

#[\AllowDynamicProperties]
/**
 * Summary of Login
 */
class Login extends \Core\Controller
{

    /**
     * Summary of newAction
     * @return void
     */
    public function newAction()
    {
        if (!isset($_SESSION['user_id']))
            View::renderTemplate('Login/new.html');
        else
            View::renderTemplate('Home/index.html');
    }

    /**
     * Summary of createAction
     * @return void
     */
    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);

        if ($user) {

            Auth::login($user, $remember_me);

            Flash::addMessage('Logowanie zakończone sukcesem');

            $this->redirect(Auth::getReturnToPage());

        } else {
            Flash::addMessage('Logowanie nie powiodło się, spróbuj ponownie', Flash::WARNING);

            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email'],
                'remember_me' => $remember_me
            ]);
        }
    }

    /**
     * Summary of destroyAction
     * @return void
     */
    public function destroyAction()
    {
        Auth::logout();

        $this->redirect('/login/show-logout-message');
    }


    /**
     * Show a "logged out" flash message and redirect to the homepage. Necessary to use the flash messages
     * as they use the session and at the end of the logout method (destroyAction) the session is destroyed
     * so a new action needs to be called in order to use the session.
     *
     * @return void
     */
    public function showLogoutMessageAction()
    {
        Flash::addMessage('Wylogowano');

        $this->redirect('/');
    }
}