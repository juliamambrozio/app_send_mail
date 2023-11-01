<?php

//INCLUINDO ARQUIVOS

require "./bibliotecas/PHPMailer/Exception.php";
require "./bibliotecas/PHPMailer/PHPMailer.php";
require "./bibliotecas/PHPMailer/POP3.php";//recebimento de e-mail
require "./bibliotecas/PHPMailer/SMTP.php";//Envio de e-mail

//NAMESPACE
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//print_r($_POST);

class Mensagem{
    private $para = null;
    private $assunto = null;
    private $mensagem = null;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
      $this->$atributo = $valor; //atribuindo valor aos atributos (linha 29)
    }

    public function mensagemValida(){
        if(empty($this->para) || empty($this->mensagem) || empty($this->assunto)){ //verificando se está vázio
            return false;

        }else{
            return true;
        }
    }
}

$mensagem = new Mensagem();
$mensagem->__set('para', $_POST['para']); //índices definidos pelos names, o valor dos atributos da classs recebem o valor inserido no name (POST)
$mensagem->__set('assunto', $_POST['assunto']);
$mensagem->__set('mensagem', $_POST['mensagem']);

if(!$mensagem->mensagemValida()){ //se o resultado é true, entra no true do if
    echo 'Mensagem não é válida';
    die(); //acaba com o processamento do script
}

require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'user@example.com';                     //SMTP username
    $mail->Password   = 'secret';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Não foi possível enviar este e-mail! Por favor tente novamente mais tarde! {$mail->ErrorInfo}";
}

//print_r($mensagem);
?>