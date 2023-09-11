<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Balance;
use \App\Flash;
use DateTime;

class ViewBalanceSheet extends \Core\Controller
{
    public function indexAction()
    {
        if (isset($_SESSION['user_id']))
            View::renderTemplate('ViewBalanceSheet/index.html');
        else 
            View::renderTemplate('Login/new.html');
    }

    public function createAction()
    {
        if (isset($_POST['timePeriod'])||isset($_POST['startPeriod']))
        {
            $balance = new Balance($_POST);
            $balance ->periodTime();
            $balance ->setBalance();

            View::renderTemplate('ViewBalanceSheet/index.html', [
                'timePeriod' => $_POST['timePeriod'],
                'balance' => $balance
            ]);
        }
        else
        {
            View::renderTemplate('ViewBalanceSheet/index.html');
        }
    }

}
