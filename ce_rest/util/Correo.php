<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Correo
 *
 * @author ernesto.ruales
 */
require '../vendor/autoload.php';

class Correo {

    //put your code here
    public $error = null;
    private $email = "informacion@epico.gob.ec";
    private $pass = "Ep1c0*2020AD";
    private $host = "smtp.gmail.com";
    private $port = "587";

    public function enviarCorreo($destinatario, $texto, $Subject, $fromName = null) {
        try {
            $this->error = null;
            if (is_null($fromName)) {
                $fromName = "Empresa Publica Municipal para la Gestion de la Innovacion y Competitividad, Epico";
            }
            $this->error = null;

            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->SMTPAuth = true;

            //Temporal
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // tls
            $mail->Host = $this->host; // SMTP a utilizar. Por ej. smtp.elserver.com
            $mail->Username = $this->email; // Correo completo a utilizar
            $mail->Password = $this->pass; // Contrase�a	/
            $mail->Port = $this->port; // Puerto a utilizar 465 587

            $mail->From = $this->email;
            $mail->FromName = $fromName;
            $mail->IsHTML(true); // El correo se envia como HTML
            $mail->Subject = $Subject;
            $correos = explode(";", $destinatario);

            foreach ($correos as &$correo) {
                $mail->AddAddress(trim($correo));
            }
            // $mail->addBCC("$correoDestinatario");
            $mail->Body = $texto;
            $mail->CharSet = 'UTF-8';
            if (!$mail->Send()) {
                $this->error = "Error: " . $mail->ErrorInfo;
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->error = "Error: " . $e->getMessage();
            return false;
        }
    }

    public function enviarCorreoArchivo($destinatario, $texto, $Subject, $archivo, $fromName=null) {
        try {
            $this->error = null;
            if (is_null($fromName)) {
                $fromName = "Empresa Publica Municipal para la Gestion de la Innovacion y Competitividad, Epico";
            }
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;

            //Temporal
            $mail->SMTPSecure = "tls";  // tls
            $mail->Host = $this->host; // SMTP a utilizar. Por ej. smtp.elserver.com
            $mail->Username = $this->email; // Correo completo a utilizar
            $mail->Password = $this->pass; // Contrase�a	/
            $mail->Port = $this->port; // Puerto a utilizar 465 587

            $mail->From = $this->email;
            $mail->FromName = $fromName;
            $mail->IsHTML(true); // El correo se envia como HTML
            $mail->Subject = $Subject;

            $correos = $n = explode(";", $destinatario);
            foreach ($correos as &$correo) {
                $mail->AddAddress(trim($correo));
            }

            // $mail->addBCC("$correoDestinatario");
            $mail->Body = $texto;
            $mail->CharSet = 'UTF-8';
            if (isset($archivo)) {
                $mail->addAttachment($archivo->url, $archivo->nombre);
            }
            if (!$mail->Send()) {
                $this->error = "Error: " . $mail->ErrorInfo;
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->error = "Error: " . $e->getMessage();
            return false;
        }
    }

}
