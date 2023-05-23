<?php

namespace App;
use App\Config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;




require '../vendor/phpmailer/src/Exception.php';
require '../vendor/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/src/SMTP.php';



class Mail
{

  /**
   * 
   * Send a message
   * 
   * @param string $to Recipient
   * @param string $subject Subject
   * @param string $text Text-only content of the message
   * @param string $html HTML content of the message
   * 
   * @return mixed
   * 
   */

    public static function send($to, $subject, $text, $html)
    {
        try{
          $mail = new PHPMailer(true);         

        $mail->isSMTP();                                       
                        
        
        $mail->SMTPSecure = 'ssl';  
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth   = true; 
        $mail->Username = 'sandra.example95@gmail.com';
        $mail->Password = Config::PASS;      
        $mail->Port = 465;                       
        
        $mail->CharSet  = 'UTF-8';
        $mail->SetFrom('sandra.example95@gmail.com', 'Budżet Osobisty');
        $mail->addAddress($to);
       

        $mail->Subject = 'Aktywacja konta';
        $mail->Body    = $html;
        $mail->AltBody = $text;
       
        $mail->isHTML(true);     

        $mail->Send();

        // echo 'Message sent';
      } 
      catch (Exception $e) {
      echo 'Message not sent: ', $mail->ErrorInfo;
      }
        
    }
  }