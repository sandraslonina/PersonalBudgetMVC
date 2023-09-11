<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;


#[\AllowDynamicProperties]
class Profile extends Authenticated
{


    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }


    public function showAction()
    {
        View::renderTemplate('Profile/show.html', [
            'user' => $this->user
        ]);
    }


    public function editAction()
    {
        View::renderTemplate('Profile/edit.html', [
            'user' => $this->user
        ]);
    }


    public function updateAction()
    {
        if ($this->user->updateProfile($_POST)) {

            Flash::addMessage('Zapisano zmiany');

            $this->redirect('/profile/show');

        } else {

            View::renderTemplate('Profile/edit.html', [
                'user' => $this->user
            ]);

        }
    }
}