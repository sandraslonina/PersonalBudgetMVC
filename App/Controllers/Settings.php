<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Flash;
use DateTime;

class Settings extends \Core\Controller
{
    public function indexAction()
    {
        if (isset($_SESSION['user_id']))
            View::renderTemplate('Settings/index.html',[
                'incomesCategory'   =>     Income::selectIncomesCategory(),
                'expensesCategory'  =>     Expense::selectExpensesCategory(),
                'paymentMethod'    =>     Expense::selectMethodsPayment()
            ]);
        else 
            View::renderTemplate('Login/new.html');
    }



    





}
