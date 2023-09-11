<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;
use DateTime;


class HomeThisMonth extends \Core\Model
{
    public $errors = [];

    public function periodTime()
    {
            $startOfPeriodTime = date('Y-m-01');
            $endOfPeriodTime = date('Y-m-01',strtotime('+1 month',time()));
    
        return array($startOfPeriodTime,$endOfPeriodTime);
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
