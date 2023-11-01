<?php


require "bibliotecas/PHPMailer/Exception.php";
require "bibliotecas/PHPMailer/PHPMailer.php";
require "bibliotecas/PHPMailer/POP3.php";//recebimento de e-mail
require "bibliotecas/PHPMailer/SMTP.php";//Envio de e-mail
//print_r($_POST);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mensagem{
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    public $status = array('codigo_status' => null, 'descricao_status' => null);

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
    header('Location: index.php?falta_campos_preenchidos'); //acaba com o processamento do script
}



$mail = new PHPMailer(true);
try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host= 'smtp.gmail.com'; //acessando serviço smtp
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username   = ''; //e-mail da conta que enviará               
        $mail->Password   = ''; //senha secundária gerada pelo e-mail                  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('', '');
        $mail->addAddress($mensagem->__get('para'));     //Add a recipient
        //$mail->addReplyTo('info@example.com', 'Information'); //pode mudar a pessoa que receberá a resposta do destinatário para o remetente
        //$mail->addCC('cc@example.com'); destinatários de cópias
        //$mail->addBCC('bcc@example.com'); cópia oculta

        //ADICIONAR ANEXOS
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //ASSUNTO DO E-MAIL
        $mail->isHTML(true);                                 
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = 'É necessário utilizar um client que suporte HTML para ter acesso total ao conteúdo dessa mensagem';

        $mail->send();

        $mensagem->status['codigo_status'] = 1; //para deixar o visual mais atrativo, quando a informação for certa receberá um e se não for receberá dois
        $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso';
        
        } catch (Exception $e) {

        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = 'Não foi possivel enviar este e-mail! Por favor tente novamente mais tarde.';
        //echo 'Detalhes do erro: ' . $mail->ErrorInfo;
}


//print_r($mensagem);
?>

<html>
    <head>
    <meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
    <div class="container">
			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

			<div class="row">
				<div class="col-md-12">

					<?php if($mensagem->status['codigo_status'] == 1) { ?>

						<div class="container">
							<h1 class="display-4 text-success">Sucesso</h1>
							<p><?= $mensagem->status['descricao_status'] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>

					<?php } ?>

					<?php if($mensagem->status['codigo_status'] == 2) { ?>

						<div class="container">
							<h1 class="display-4 text-danger">Ops!</h1>
							<p><?= $mensagem->status['descricao_status'] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>

					<?php } ?>

				</div>
			</div>
		</div>
    </body>
</html>