<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Conversação com Banco de Dados MySQL
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 20/03/2007 Atualizado: 20/03/2007
*/
class mySQL extends bancoDados {

  /**
   * @desc Contrutor de Classe mySQL
  */
  function mySQL(){
	$this->bancoDados(); 
  }
  
  /**
   * @desc Busca conexão com servidor MySQL
   * @return boolean de Operação com sucesso 
  */
  function conecta(){
    // Busca conexão com servidor MySQL
    if (!($this->conexao = mysql_connect($this->servidor,$this->usuario, $this->senha))){
      $this->erros[] = "Não foi possível estabelecer a coneção!"; 
      $this->erros[] = "Descrição do erro:".mysql_error();
      return 0;
    }
    
    // Busca conexão com banco de dados
    if (!($this->banco = mysql_select_db($this->nomeBanco,$this->conexao))){
      $this->erros [] = "Não foi possível estabelecer a coneção!"; 
      $this->erros [] = "Descrição do erro:".mysql_error();
      return 0;
    }  
    
    return 1;
  }
  
  /**
   * @desc Encerra conexão com servidor MySQL
   * @return boolean de Operação com sucesso 
  */
  function desconecta(){
    //Encerra conexão 
    mysql_close($this->conexao);
    return 1;
  }
  
  /**
   * @desc Envia comando Insert para servidor
   * @return int de ID de insertm ou false 
  */
  function enviaInsert(){
	$this->erros = array();    
    $this->sql = "Insert into ".$this->from." ( ".$this->insert." ) values( ".$this->values." )";
    
    if (!$this->conexao){
      $this->conecta();
    }
	
	if ($_GET["query"]){
		echo $this->sql."<BR>";
	}
    
    if ($this->conexao){
      if (!($this->resultado = mysql_query($this->sql))){
        $this->erros[] = "Não foi possível salvar as suas informações!";
        $this->erros[] = "Descrição do erro:".mysql_error();
        $this->erros[] = "SQL: ".$this->sql;
        $this->limpaConsulta();
        return 0;
      } else {
      	$ret = mysql_insert_id($this->conexao); 
      	$this->limpaConsulta();
        return $ret;
      }
    }
  }
  
  /**
   * @desc Envia comando Update para servidor
   * @return boolean de Operação com sucesso 
  */
  function enviaUpdate(){
	$this->erros = array();    
    $this->sql = "Update ".$this->from." set ".$this->update." where ".$this->where;
    
    if (!$this->conexao){
       $this->conecta();
    }
	
	if ($_GET["query"]){
		echo $this->sql."<BR>";
	}
    
    if ($this->conexao){
      if (!($this->resultado = mysql_query($this->sql))){
        $this->erros[] = "Não foi possível salvar as suas informações!";
        $this->erros[] = "Descrição do erro:".mysql_error();
        $this->erros[] = "SQL: ".$this->sql;
        $this->limpaConsulta();
        return 0;
      } else {
      	$this->limpaConsulta();
        return 1;
      }
    }
  }
  
  /**
   * @desc Envia comando Delete para servidor
   * @return boolean de Operação com sucesso 
  */ 
  function enviaDelete(){
	$this->erros = array();    
    $this->sql ="Delete from ".$this->from." where ".$this->where;
    
    if (!$this->conexao){
      $this->conecta();
    }
    if ($this->conexao){
      if (!($this->resultado = mysql_query($this->sql))){
        $this->erros[] = "Não foi possível excluir registro!";
        $this->erros[] = "Descrição do erro:".mysql_error();
        $this->erros[] = "SQL: ".$this->sql;
        $this->limpaConsulta();
        return 0;
      } else {
      	$this->limpaConsulta();
        return 1;
      }
    }
  }
  
  
  /**
   * @desc Envia comando Select para servidor
   * @return boolean de Operação com sucesso 
  */  
  function enviaSelect(){
	$this->erros = array();  
    $this->sql = "Select ".$this->select." from ".$this->from;
    if ($this->where) {
      $this->sql .= " where ".$this->where; 
    } 	
    if ($this->group) {
      $this->sql .= " group by ".$this->group;
    }
    if ($this->order) {
      $this->sql .= " order by ".$this->order;
    }
    
    if (!$this->conexao){ 
      $this->conecta();
    }  
	if ($this->conexao){
      if (!($this->resultado = mysql_query($this->sql))){
        $this->erros[] = "Não foi possível realizar esta pesquisa!";
        $this->erros[] = "Descrição do erro:".mysql_error();
        $this->erros[] = "SQL: ".$this->sql;
        $this->limpaConsulta();
        return 0;
      } else {
        $this->limpaConsulta();
        return 1;
      }
    }
  }
  
  /**
   * @desc Envia mesmo comando Select novamente para servidor
   * @return boolean de Operação com sucesso 
  */ 
  function enviaSelectNovamente(){
      
    if (!$this->conexao){ 
      $this->conecta();
    }  
    
  	if (isset($_GET["query"])){
		echo $this->sql."<BR>";
	}

    if ($this->conexao){
      if (!($this->resultado = mysql_query($this->sql))){
        $this->erros[] = "Não foi possível realizar esta pesquisa!";
        $this->erros[] = "Descrição do erro:".mysql_error();
        $this->erros[] = "SQL: ".$this->sql;
        return 0;
      } else {
        return 1;
      }
    }
  }
      
  /**
   * @desc Apaga cache de consulta SQL
   * @return boolean de Operação com sucesso 
  */  
  function limpaSelect(){
    if ($this->resultado){
      mysql_free_result($this->resultado);
    }
    return 1;
  }
  
  /**
   * @desc Conta linhas de cache de consulta SQL
   * @return integer de Quantidade de linhas 
  */  
  function contaSelect(){
    if ($this->resultado){
      $i = 0;
      while ($this->linhaSelect()) {
        $i = $i + 1;
      }
      $this->enviaSelectNovamente();
      return $i;
    }
    return 0;
  }
  
  /**
   * @desc Recupera próxima linha de cache de consulta SQL
   * @return array de Linha de cache de consulta SQL 
  */ 
  function linhaSelect(){
    if ($this->resultado){
      return mysql_fetch_array($this->resultado);
    }
    return 0;
  }

}