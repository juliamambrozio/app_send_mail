<?php

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

if($mensagem->mensagemValida()){ //se o resultado é true, entra no true do if
    echo 'Mensagem é válida';
} else{
    echo 'Não é válida';
}

//print_r($mensagem);
?>