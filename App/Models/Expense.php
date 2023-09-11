<?php

namespace App\Models;

use AllowDynamicProperties;
use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;
use DateTime;
use DateInterval;

#[AllowDynamicProperties]
class Expense extends \Core\Model
{

    public $errors = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }


    public function save()
    {
        $this->validate();

        $id = (int)$_SESSION['user_id'];

        if (empty($this->errors)) {


            $sql = 'INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
                    VALUES (:id, :category, :paymentMethod, :amount, :date, :comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':category', $this->category, PDO::PARAM_STR);
            $stmt->bindValue(':paymentMethod', $this->paymentMethod, PDO::PARAM_STR);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);

            return $stmt->execute();
            
        }

        return false;
    }

    public function validate()
    {
        //Sprawdź poprawność kwoty
        $this->amount = str_replace(',','.',$this->amount);

        if ($this->amount == '') {
            $this->errors[] = 'Kwota jest wymagana!';
        }

        if(is_numeric($this->amount)==false) {
            $this->errors[] = 'Wpisz poprawny format liczby!';
        }
        else if($this->amount<0) {
            $this->errors[] = 'Kwota nie może być ujemna';
        }
        else {
            $this->amount = number_format($this->amount, 2, '.', '');
        }
        
        


        //Sprawdź poprawność daty
        if ($this->date == '') {
            $this->errors[] = 'Data jest wymagana!';
        }


		$dateObject= new DateTime($this->date);
		$day= $dateObject->format("d");
		$month= $dateObject->format("m");
		$year= $dateObject->format("Y");

		if(checkdate($month, $day, $year)==false) {
			$this->errors[] ="Wpisz poprawny format daty!";
		}
        //walidacja kategorii
        if (!isset($this->category))
		{
            $this->errors[] = 'Wybierz kategorię!';
		}	
        else if($this->category == '') {
            $this->errors[] = 'Kategoria jest wymagana!';
        }
        else if(preg_match('/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ0-9+-]*$/', $this->category) == false) {
            $this->errors[] = 'Kategoria może składać się tylko z liter i cyfr';
        }

        //walidacja metody płatności
        if (!isset($this->paymentMethod))
		{
            $this->errors[] = 'Wybierz metodę płatności!';
		}	
        else if($this->paymentMethod == '') {
            $this->errors[] = 'Metoda płatności jest wymagana!';
        }
        else if(preg_match('/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ0-9+-]*$/', $this->paymentMethod) == false) {
            $this->errors[] = 'Metoda płatności może składać się tylko z liter i cyfr';
        }



        //walidacja komentarza
        if (isset($this->comment)) {

            if (preg_match('/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ0-9+_ -.,]*$/', $this->comment) == false) {
                $this->errors[] = 'To pole może składać się tylko z liter, cyfr, plusów, minusów oraz spacji';
            }

        }

    }


    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    public static function selectExpensesCategory()
    {
        $id = $_SESSION['user_id'];

        $sql = "SELECT id, name
                FROM expenses_category_assigned_to_users
                WHERE user_id = :id";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }   

    public static function selectMethodsPayment()
    {
        $id = $_SESSION['user_id'];

        $sql = "SELECT id, name
                FROM payment_methods_assigned_to_users
                WHERE user_id = :id";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }   

 


    public static function getMonthlyCategoryExpense($category,$date)
    {
        $dateObject = new DateTime($date);
        $beginMonth = $dateObject->format('Y-m-01');
        $endMonth = $dateObject->format('Y-m-t');

        $db = static::getDB();

        $sql = "SELECT SUM(amount) AS monthlySum FROM `expenses` 
                WHERE  expense_category_assigned_to_user_id = :id
                AND date_of_expense BETWEEN :beginMonth AND :endMonth";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id',             $category,      PDO::PARAM_INT);
        $stmt->bindValue(':beginMonth',     $beginMonth,    PDO::PARAM_STR);
        $stmt->bindValue(':endMonth',       $endMonth,      PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }
        else {
            return $result['monthlySum'];
        }
    }

}
