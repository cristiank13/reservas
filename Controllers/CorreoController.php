<?php

use PHPMailer\PHPMailer\SMTP;
use Controllers\GeneralController;
use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';

class CorreoController extends GeneralController
{

    private $asunto = '';
    private $contenido = '';
    private $imagenesCorreo = [];
    private $destinos = [];

    public function __construct()
    {
    }

    public function setAsunto(string $asunto)
    {
        $this->asunto = $asunto;
    }

    public function setContenido(string $contenido)
    {
        $this->contenido = $contenido;
    }

    public function setDestinos(array $destinos)
    {
        $this->destinos = $destinos;
    }

    public function setImagenesCorreo(array $imagenes)
    {
        $this->imagenesCorreo = $imagenes;
    }

    public function enviar()
    {

        $respuesta = [
            "message" => "No se pudo enviar el correo.",
            "success" => 0,
            "data"    => []
        ];

        $mail = new PHPMailer(true);

        $correo = 'airstay.es@gmail.com';
        $clave = 'yxqjwapzwyromkyz';
        
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $correo;                     //SMTP username
            $mail->Password   = $clave;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($correo, 'AirStay');

            if (count($this->destinos)) {
                foreach ($this->destinos as $valor) {
                    $mail->addAddress($valor, $valor);
                }
            } else {
                $respuesta["message"] = 'No se an definido destinos para enviar el correo';
                return json_encode($respuesta);
            }

            if (count($this->imagenesCorreo)) {
                foreach ($this->imagenesCorreo as $imagen) {
                    $mail->AddEmbeddedImage($imagen[0], $imagen[1]);
                }
            }


            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $this->asunto;
            $mail->Body    = $this->contenido;

            if ($mail->send()) {
                $respuesta["success"] = 1;
                $respuesta["message"] = 'Envio exitoso';
            }
        } catch (\Exception $e) {
            $respuesta["message"] = 'Error al enviar el correo';
        }
        return json_encode($respuesta);
    }

    public function enviarEmail($atributos)
    {
        $this->setDestinos($atributos["destinos"]);
        $this->setAsunto($atributos["asunto"]);
        $this->setContenido($atributos["contenido"]);
        $this->enviar();
    }
}
