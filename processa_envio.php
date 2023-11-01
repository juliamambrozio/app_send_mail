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



$mail = new PHPMailer(true);
try {
        //Server settings
        $mail->SMTPDebug = 2;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host= 'smtp.gmail.com'; //acessando serviço smtp
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username   = 'user@example.com'; //e-mail da conta que enviará               
        $mail->Password   = 'secret'; //senha secundária gerada pelo e-mail                  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('ju@gmail.com', 'Jú');
        $mail->addAddress('ju@gmail.com', 'Júlia');     //Add a recipient
        //$mail->addReplyTo('info@example.com', 'Information'); //pode mudar a pessoa que receberá a resposta do destinatário para o remetente
        //$mail->addCC('cc@example.com'); destinatários de cópias
        //$mail->addBCC('bcc@example.com'); cópia oculta

        //ADICIONAR ANEXOS
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //ASSUNTO DO E-MAIL
        $mail->isHTML(true);                                 
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
} catch (Exception $e) {
        echo "Não foi possivel enviar este e-mail! Por favor tente novamente mais tarde.";
        echo 'Detalhes do erro: ' . $mail->ErrorInfo;
}


//print_r($mensagem);
?>