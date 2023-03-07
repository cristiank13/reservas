<?php

namespace Models;

use Models\Model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('Model.php');

class Usuario extends Model
{

    Const TABLA = "usuarios";
    Const ID = "idusuario";

    protected static function dataPrivate()
    {   
        return [];
    }

}
/*
require '../Utilities/PHPMailer/src/Exception.php';
require '../Utilities/PHPMailer/src/PHPMailer.php';
require '../Utilities/PHPMailer/src/SMTP.php';


// Crear una instancia de PHPMailer
$mail = new PHPMailer();

try {
    //Server settings
    $mail->SMTPDebug = 2;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'cristiank13k@gmail.com';                     //SMTP username
    $mail->Password   = 'pikecrisk13k';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('cristiank13k@gmail.com', 'Mailer');
    $mail->addAddress('cristian.agudelo@cerok.com', 'cristian.agudelo@cerok.com');           

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

/*
$u = new Usuario();

$total = $u->getTodos();
print_r($total[1]);


/*
$u->setatributos([
    'login' => '32423',
    'clave' => '43534',
    'email' => 'dfsdf'
]);

var_dump($u->update());*/

