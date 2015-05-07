<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe para manipulação de sessão de resultados 
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 22/03/2007 Atualizado: 22/03/2007
*/
class  resultados {
  private $nome; //Nome de estrutura de dados em sessão	
  private $campos; //Array com nomes de campos de estrutura de dados em sessão
  private $linha; //Array com linha de dados de sessão
  private $coluna; //Array com coluna de dados de sessão
  private $dados; //Array multidimencional com dados de sessão  
  public $erros; //Array com lista de erros ororridos 
  
  /**
   * @desc Contrói objeto da Classe resultados
  */
  function resultados(){
  	$this->nome = "";
    $this->linha = array();
    $this->coluna = array();
    $this->dados = array();
    $this->limpaErros();
  }
  
  /**
   * @desc Configura nome de estrutura de dados de sessão
   * @param string $nome com nome de Estrutura
  */
  function setNome($nome){
    $this->nome = $nome;
    $this->limpaErros();
  }
  
  /**
   * @desc Configura campos de estrutura de dados de sessão
   * @param array $campos com lista de campos de Estrutura
  */
  function setCampos($campos){
    $this->campos = $campos;
    $_SESSION["resultadosEstrutura".$this->nome] = $this->campos;
    $this->limpaErros();
  }
  
  /**
   * @desc Retorna lista de campos de estrutura de dados de sessão
   * @return array com lista de campos de Estrutura
  */
  function getCampos(){
  	$this->campos = array(); 
  	if ($_SESSION["resultadosEstrutura".$this->nome]) {
  	  $this->campos = $_SESSION["resultadosEstrutura".$this->nome];
  	} else {
  	  $this->erros[] = "Estrutura de Campos não encontrada!";	
  	}   
  	return $this->campos;
  }	
  
  /**
   * @desc Limpa estrutara de dados em sessão
  */
  function limpaResultados(){
    if ($this->getCampos()) {
      for ($i = 0;$i < count($this->campos);$i++){
        unset($_SESSION["resultados".$this->nome.$this->campos[$i]]);
      }
      unset($_SESSION["resultadosEstrutura".$this->nome]);
    }
    $this->limpaErros();
  }
  
  /**
   * @desc Adiciona linha em estrutura de dados de sessão
   * @param array $linha com linha de dados 
  */
  function adicionaResultado($linha){
    if ($this->getCampos()) {
      if ($this->contaResultados()) {	
        $numeroLinha = $this->procuraResultado($this->campos[0], $linha[0]);
        for ($contador = 0; $contador < count($this->campos); $contador++){
          $coluna = $this->getColunaResultados($this->campos[$contador]);
          if ($numeroLinha == -1) { 
            $coluna[] = $linha[$contador];
          } else {
          	$coluna[$numeroLinha] = $linha[$contador];
          } 	  
          $_SESSION["resultados".$this->nome.$this->campos[$contador]] = $coluna;
        }
      } else {  
        //Inclue primeiro registro
        for ($contador = 0;$contador < count($this->campos);$contador++){
          $this->coluna = array($linha[$contador]);
          $_SESSION["resultados".$this->nome.$this->campos[$contador]] = $this->coluna;
        }
      }  
    } 
  }
  
  /**
   * @desc Apaga linha em estrutura de dados de sessão
   * @param integer $chave com Chave de linha de dados 
  */
  function apagaResultado($chave){
    if ($this->getCampos()) {
      $numeroLinha = $this->procuraResultado($this->campos[0], $chave);	
      if ($numeroLinha != -1){
        for ($contadorColuna = 0; $contadorColuna < count($this->campos); $contadorColuna++){
          $colunaNova = array();
          $colunaVelha = $_SESSION["resultados".$this->nome.$this->campos[$contadorColuna]];
          for ($contadorLinha = 0; $contadorLinha < count($colunaVelha); $contadorLinha++){
            if ($contadorLinha != $numeroLinha) {
              $colunaNova[] = $colunaVelha[$contadorLinha];
            }
          }
          $_SESSION["resultados".$this->nome.$this->campos[$contadorColuna]] = $colunaNova;
        }
      } else {
      	$this->erros[] = "Linha de dados não encontrada! Chave: ".$chave;	
      } 	
    }
  }
  
  /**
   * @desc Retorna quantidade de linhas gravadas em sessão de estrutura
   * @return integer de Quantidade de linhas
  */
  function contaResultados(){
    if ($this->getCampos()) {
      return count($this->getColunaResultados($this->campos[0]));
    } else {
      return 0;
    }
  }
  
  /**
   * @desc Limpa lista de erros
  */
  function limpaErros(){
  	$this->erros = array();
  }
  
  /**
   * @desc Retorna coluna de dados de estrutura em sessão
   * @return array com Coluna de dados
  */
  function getColunaResultados($campo){
  	$this->coluna = array();
    if ($_SESSION["resultados".$this->nome.$campo]){
      $this->coluna = $_SESSION["resultados".$this->nome.$campo];
      return $this->coluna;
    } else {
      $this->erros[] = "Coluna de dados não encontrada! Campo: ".$campo;	
    } 	
    return $this->coluna;
  }
  
  /**
   * @desc Retorna linha de dados de estrutura em sessão
   * @param $campo array de Campos para pesquisa
   * @param $valor array de Valores para pesquisa
   * @return integer com Número de linha com primeiro resultado
  */
  function procuraResultado($campo, $valor){
  	$numeroLinha = 0; 
  	$total = $this->contaResultados();
    for ($numeroLinha = 0; (($numeroLinha < $total) and ($verifica == 0)); $numeroLinha++){
      $verifica = 0;
      for ($contador = 0; (($contador < count($campos)) and ($verifica == 0)); $contador++){
      	if ($this->getColunaResultados($campos[$contador])){
      	  $verifica = 1;	
      	  if ($this->coluna[$numeroLinha] != $valor[$contador]){
      	  	$verifica = 0;
      	  }	 	
      	} 	
      } 	     
    }
    if ($verifica) {
      return $numeroLinha;
    } else {
      return -1;	
    } 	  
  }
 
  /**
   * @desc Retorna linha de dados de estrutura em sessão
   * @return array com Linha de dados
  */
  function getLinhaResultados($chave){
    $this->linha = array();
    if ($this->getCampos()) {
      $numeroLinha = $this->procuraResultado(array($this->campos[0]), array($chave));
      if ($numeroLinha != -1){	
        for ($contador = 0;($contador < count($this->campos));$contador++){
          if ($this->getColunaResultados($campos[$contador])){
            $this->linha[] = $this->coluna[$numeroLinha];	
          } else {
            $this->linha[] = "";
          }
        }
      } else {
      	$this->erros[] = "Linha de dados não encontrada! Chave: ".$chave;
      } 	
    } 
    return $this->linha;
  }
  
  /**
   * @desc Retorna lista de dados de estrutura em sessão
   * @return array multidimencional com Dados
  */
  function getResultados(){
  	$this->dados = array();
    if ($this->getCampos()) {
      for ($contador = 0; $contador < count($this->contaResultados());$contador++){
      	$this->getColunaResultados($this->campos[0]);
        $this->dados[] = $this->getLinhaResultados($this->coluna[$contador]);
        return $this->dados;
      }
    }
    return $this->dados;
  }
  
  /**
   * @desc Retorna proxima chave de resultado
   * @param integer $atual de linha atual
   * @return integer de chave de proxima linha
  */
  function proximoResultado($atual){
    $proximo = $atual;
    if ($this->getCampos()) {
      $numeroLinha = $this->procuraResultado($this->campos[0], $atual);
      $total = $this->contaResultado();	
      if ($numeroLinha != -1){
        if (($numeroLinha < $total-1) and ($total > 1)) {
          $numeroLinha = $numeroLinha + 1;
          $this->getColunaResultados($this->campos[0]);
          $proximo = $this->coluna[$numeroLinha];
        }
      }
    } 
    return $proximo;
  }
  
  /**
   * @desc Retorna anterior chave de resultado
   * @param integer $atual de linha atual
   * @return integer de chave de linha anterior
  */
  function anteriorResultado($atual){
    $anterior = $atual;
    if ($this->getCampos()) {
      $numeroLinha = $this->procuraResultado($this->campos[0], $atual);
      $total = $this->contaResultado();	
      if ($numeroLinha != -1){
		if ($numeroLinha > 0){
          $numeroLinha = $numeroLinha + 1;
          $this->getColunaResultados($this->campos[0]);
          $anterior = $this->coluna[$numeroLinha];
        }
      }
    }
    return $anterior;
  }

}