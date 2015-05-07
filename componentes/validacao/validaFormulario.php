<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe para validação de formulários
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 22/03/2007 Atualizado: 22/03/2007
*/
class validaFormulario {
  var $valor;
  var $tipo;
  var $erros;
  var $temErros;
  
  /**
   * @desc Contrutor de Classe de Validação de Formulários 
  */
  function validaFormulario (){
    $this->valor = "";
    $this->tipo = "Texto";  
    $this->limpaErros();
  }
  
  /**
   * @desc Limpa cache de erros para nova operação 
  */
  function limpaErros(){
    $this->erros = array();
    $this->temErros = 0;
  }
  
  /**
   * @desc Criptografa senhas
   * @return string $r de Senha Criptografada
   * @param string $s de Senha
  */
  function encripta($s){
    $r = Null;
      $x = 0;
    for ($x = 0; $x <= strlen($s) -1;$x++)
      $r .= (chr(ord($s{$x})^(159-($x *10))));
    return $r;
  }
  
  /**
   * @desc Compara dois valores 
   * @return integer de Comparação verdadeira(1) ou falsa(0)
   * @param string $valor de Valor para comparar
   * @param string $confere de Valor padrão
  */    
  function confereValor($valor,$confere){
    if ($valor == $confere){
      return 1;
    } else {
      return 0;
    }
  }

  /**
   * @desc Valida campo enviado por formulário 
   * @return integer de Comparação verdadeira(1) ou falsa(0)
   * @param string $nome de Nome do Campo
   * @param string $texto de Valor do Campo
   * @param string $tipo de Tipo do Campo
   * @param integer $requerido de Campo requerido (0/1)
  */
  function validaCampo($nome, $texto,$tipo = "Texto", $requerido = 0){
    
    $this->valor = $texto;
    $this->tipo = $tipo;
           
    if (($requerido and (!$this->valor)) and  
        ( ($tipo != "CheckBox") and ($tipo != "Radio") )) {
      $this->erros[] = " Campo ".$nome." precisa ter algum valor! ";
      $this->temErros = 1;
      return 0;
    }
    
    if ($tipo == "Password") {
      return md5($this->valor);
    }

    if ($tipo == "Numero") {
      $this->valor = (0 + $this->valor);
      return $this->valor;
    }

    if ($tipo == "CheckBox") {
      if (($requerido) and (!$this->valor)) {
        $this->erros[] = "Confirme a ".$nome."! ";
        $this->temErros = 1;
        return 0;
      }
      if (!$this->valor) {
        return 0;
      }  	  
    }

    if ($tipo == "Radio") {
      if (($requerido) and (!$this->valor)) {
        $this->erros[] = "Escolha uma opção de ".$nome."! ";
        $this->temErros = 1;
        return 0;
      }
      if (!$this->valor) {
        return 0;
      }  
    }
    return $this->valor;
  }
  
  /**
   * @desc Retorna erros ocorridos 
   * @return array de Lista de Erros
  */    
  function confereErros(){
    if ($this->temErros){
      return $this->erros;
    } else {
      return 0;
    }
    $this->limpaErros();
  }
  
}