<?php

namespace App\Models;


use AllowDynamicProperties;
use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;
use DateTime;


#[\AllowDynamicProperties]
class Balance extends \Core\Model
{


    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function periodTime()
    {
        if (!isset($this->timePeriod))
        {
            $this->timePeriod=5;
        }
    
        $fr_period=$this->timePeriod;
    
        if($this->timePeriod==1)
        {
            $startOfPeriodTime = date('Y-m-01');
            $endOfPeriodTime = date('Y-m-01',strtotime('+1 month',time()));
        }
        elseif ($this->timePeriod==2)
        {
            $startOfPeriodTime = date('Y-m-01',strtotime('-1 month',time()));
            $endOfPeriodTime = date('Y-m-01');
        }
        elseif ($this->timePeriod==3)
        {
            $startOfPeriodTime = date('Y-01-01');
            $endOfPeriodTime = date('Y-01-01',strtotime('+1 Year',time()));
        }
        elseif ($this->timePeriod==4)
        {
            $startOfPeriodTime = date('Y-01-01',strtotime('-1 Year',time()));
            $endOfPeriodTime = date('Y-01-01');
        }
        elseif ($this->timePeriod==5)
        {
            $startOfPeriodTime=$this->startPeriod;
            $endOfPeriodTime=$this->endPeriod;
            $dateObject= new DateTime($endOfPeriodTime);
            $dateObject->modify( '+1 day' );
            $endOfPeriodTime = $dateObject->format('Y-m-d');
            
        }
         
        $this->startOfPeriodTime = $startOfPeriodTime;
        $this->endOfPeriodTime = $endOfPeriodTime;
    }

    public function setBalance()
    {
        $incomeCategories = $this -> selectIncomesCategory();
        $i=0;
        $generalSumOfIncomes = 0;
        foreach ($incomeCategories as $singleCategory)
        {
            $incomes = $this -> selectUserIncomes($this->startOfPeriodTime,$this->endOfPeriodTime,$singleCategory['id']);
            $sumOfIncomes[$i]=0;
            $k=0;
            foreach ($incomes as $income)
            {
                $sumOfIncomes[$i]+=$income['amount'];
                $singleIncomeComment[$i][$k]=$income['income_comment'];
                $singleIncomeDate[$i][$k]=$income['date_of_income'];
                $singleIncomeAmount[$i][$k]=$income['amount'];
                $k++;
            }
            $generalSumOfIncomes += $sumOfIncomes[$i];

            $sumOfIncomes[$i] = number_format($sumOfIncomes[$i], 2, '.', '');
            
            $i++;
        }

        $generalSumOfIncomes = number_format($generalSumOfIncomes, 2, '.', '');

        $expenseCategories = $this -> selectExpensesCategory();
        $i=0;
        $generalSumOfExpenses = 0;
        foreach ($expenseCategories as $singleCategory)
        {
            $expenses = $this -> selectUserExpenses($this->startOfPeriodTime,$this->endOfPeriodTime,$singleCategory['id']);
            $sumOfExpenses[$i]=0;
            $k=0;
            foreach ($expenses as $expense)
            {
                $sumOfExpenses[$i]+=$expense['amount'];
                $singleExpenseComment[$i][$k]=$expense['expense_comment'];
                $singleExpenseDate[$i][$k]=$expense['date_of_expense'];
                $singleExpenseAmount[$i][$k]=$expense['amount'];
                $k++;
            }
            $generalSumOfExpenses += $sumOfExpenses[$i];

            $sumOfExpenses[$i] = number_format($sumOfExpenses[$i], 2, '.', '');

            $i++;
        }

        $generalSumOfExpenses = number_format($generalSumOfExpenses, 2, '.', '');

        $dateObject= new DateTime($this->endOfPeriodTime);
        $dateObject->modify( '-1 day' );
        $workingDate = $dateObject->format('Y-m-d');

        $periodTime = $this->startOfPeriodTime.' -zakres czasu- '.$workingDate;
        
        $balanceAmount = $generalSumOfIncomes-$generalSumOfExpenses;
        $balanceAmount = number_format($balanceAmount, 2, '.', '');

        if($balanceAmount >= 100)
        {
            $commentToBalance= 'Gratulacje!';
            $colorText='text-success';
            
        }
        elseif ($balanceAmount >= 0)
        {
            $commentToBalance= 'Uważaj';
            $colorText='text-warning';
        }
        else
        {
            $commentToBalance= 'Czas zacząć oszczędzać!';
            $colorText='text-danger';
        }
        $balanceSign = '';
        if ($balanceAmount >= 0)
            $balanceSign = '+';

        if (!isset($_POST['timePeriod']))
            $_POST['timePeriod']=5;

            $this->incomeCategories = $incomeCategories;
            $this->sumOfIncomes = $sumOfIncomes;
            $this->generalSumOfIncomes = $generalSumOfIncomes;
            $this->expenseCategories = $expenseCategories;
            $this->sumOfExpenses = $sumOfExpenses;
            $this->generalSumOfExpenses = $generalSumOfExpenses;
            $this->periodTime = $periodTime;
            $this->balanceAmount = $balanceAmount;
            $this->balanceSign = $balanceSign;
            $this->commentToBalance = $commentToBalance;
            $this->colorText = $colorText;

            if (isset($singleIncomeComment))
            {
            $this->singleIncomeComment = $singleIncomeComment;
            $this->singleIncomeDate = $singleIncomeDate;
            $this->singleIncomeAmount = $singleIncomeAmount;
            }
            if (isset($singleExpenseComment))
            {
            $this->singleExpenseComment = $singleExpenseComment;
            $this->singleExpenseDate = $singleExpenseDate;
            $this->singleExpenseAmount = $singleExpenseAmount;
            }
    }


    public static function selectIncomesCategory()
    {
        $id = $_SESSION['user_id'];

        $sql = "
                SELECT id, name
                FROM incomes_category_assigned_to_users
                WHERE user_id = :id
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }   

    public static function selectUserIncomes($startOfPeriodTime,$endOfPeriodTime,$categoryId)
    {
        $id = $_SESSION['user_id'];

        $sql = "
                SELECT *
                FROM incomes
                WHERE user_id = :id AND date_of_income >= :startOfPeriodTime AND date_of_income < :endOfPeriodTime AND income_category_assigned_to_user_id= :categoryId
                ";


        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':startOfPeriodTime', $startOfPeriodTime, PDO::PARAM_STR);
        $stmt->bindValue(':endOfPeriodTime', $endOfPeriodTime, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll();
    }   


    public static function selectExpensesCategory()
    {
        $id = $_SESSION['user_id'];

        $sql = "
                SELECT id, name
                FROM expenses_category_assigned_to_users
                WHERE user_id = :id
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }   

    public static function selectUserExpenses($startOfPeriodTime,$endOfPeriodTime,$categoryId)
    {
        $id = $_SESSION['user_id'];

        $sql = "
                SELECT *
                FROM expenses
                WHERE user_id = :id AND date_of_expense >= :startOfPeriodTime AND date_of_expense < :endOfPeriodTime AND expense_category_assigned_to_user_id= :categoryId
                ";


        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':startOfPeriodTime', $startOfPeriodTime, PDO::PARAM_STR);
        $stmt->bindValue(':endOfPeriodTime', $endOfPeriodTime, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll();
    }   

}
