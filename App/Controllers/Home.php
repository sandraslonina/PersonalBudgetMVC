<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Models\Balance;
use \App\Models\HomeThisMonth;
use DateTime;


#[\AllowDynamicProperties]
class Home extends \Core\Controller
{

    public function indexAction()
    {
        if (isset($_SESSION['user_id']))
        {
            $balance = new HomeThisMonth();

            $resultPeriodTime = $balance ->periodTime();
            $startOfPeriodTime = $resultPeriodTime[0];
            $endOfPeriodTime = $resultPeriodTime[1];

            $incomeCategories = $balance -> selectIncomesCategory();
            $i=0;
            $generalSumOfIncomes = 0;
            foreach ($incomeCategories as $singleCategory)
            {
                $incomes = $balance -> selectUserIncomes($startOfPeriodTime,$endOfPeriodTime,$singleCategory['id']);
                $sumOfIncomes[$i]=0;

                foreach ($incomes as $income)
                {
                    $sumOfIncomes[$i]+=$income['amount'];
                }
                $generalSumOfIncomes += $sumOfIncomes[$i];

                $i++;
            }

            $expenseCategories = $balance -> selectExpensesCategory();
            $i=0;
            $generalSumOfExpenses = 0;
            foreach ($expenseCategories as $singleCategory)
            {
                $expenses = $balance -> selectUserExpenses($startOfPeriodTime,$endOfPeriodTime,$singleCategory['id']);
                $sumOfExpenses[$i]=0;

                foreach ($expenses as $expense)
                {
                    $sumOfExpenses[$i]+=$expense['amount'];
                }
                $generalSumOfExpenses += $sumOfExpenses[$i];

                $i++;
            }

            $balance = $generalSumOfIncomes-$generalSumOfExpenses;
            
            $generalSumOfIncomes = number_format($generalSumOfIncomes, 2, '.', '');
            $generalSumOfExpenses = number_format($generalSumOfExpenses, 2, '.', '');
            $balance = number_format($balance, 2, '.', '');



        View::renderTemplate('Home/index.html', [
            'incomeCategories' => $incomeCategories,
            'sumOfIncomes' => $sumOfIncomes,
            'generalSumOfIncomes' => $generalSumOfIncomes,
            'expenseCategories' => $expenseCategories,
            'sumOfExpenses' => $sumOfExpenses,
            'generalSumOfExpenses' => $generalSumOfExpenses,
            'balance' => $balance,

        ]);
        }

        else 

        View::renderTemplate('Home/index.html');

    }
}