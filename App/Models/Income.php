<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;
use DateTime;

#[\AllowDynamicProperties]
class Income extends \Core\Model
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


            $sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
                    VALUES (:id, :category, :amount, :date, :comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':category', $this->category, PDO::PARAM_STR);
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
        else if(preg_match('/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ0-9+ -]*$/', $this->category) == false) {
            $this->errors[] = 'Kategoria może składać się tylko z liter i cyfr';
        }




        //walidacja komentarza
        if (isset($this->comment)) {

            if (preg_match('/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ0-9+_ -.,]*$/', $this->comment) == false) {
                $this->errors[] = 'To pole może składać się tylko z liter, cyfr, plusów, minusów oraz spacji';
            }

        }

    }



    /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
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

    public static function selectIncomesCategory()
    {
        $id = $_SESSION['user_id'];

        $sql = "SELECT id, name
                FROM incomes_category_assigned_to_users
                WHERE user_id = :id";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }   

    public static function selectUserIncomes()
    {
        $id = $_SESSION['user_id'];

		$startOfCurrentMonth = date('Y-m-01');
		$startOfNextMonth = date('Y-m-01',strtotime('+1 month',time()));

        $sql = "SELECT *
                FROM incomes
                WHERE user_id = :id AND date_of_income >= :startOfCurrentMonth AND date_of_income < :startOfNextMonth";


        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':startOfCurrentMonth', $startOfCurrentMonth, PDO::PARAM_STR);
        $stmt->bindValue(':startOfNextMonth', $startOfNextMonth, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll();
    }   




}
