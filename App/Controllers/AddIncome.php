<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Income;
use \App\Flash;
use \App\Date;


class AddIncome extends \Core\Controller
{

    public function newAction()
    {
        if (isset($_SESSION['user_id']))
            View::renderTemplate('AddIncome/new.html', [
                'date' => Date::getCurrentDate(),
                'incomesCategory' => Income::selectIncomesCategory()
            ]);
        else 
            View::renderTemplate('Home/index.html');
    }

    public function createAction()
    {

        $income = new Income($_POST);
        
        if ($income ->save()) {

            

            header('Location://'.$_SERVER['HTTP_HOST'].'/addIncome/success', true, 303);
            Flash::addMessage('Dodano nowy przychód');
            exit();

        } else {

            View::renderTemplate('AddIncome/new.html', [
                'income' => $income
            ]);

        }
        
    }

    public function successAction()
    {
        View::renderTemplate('AddIncome/success.html'); 
    }


}
