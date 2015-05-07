<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Conversa��o com Banco de Dados Firebird
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 20/03/2007 Atualizado: 20/03/2007
*/
class firebird extends bancoDados {
 
  /**
   * @desc Contrutor de Classe firebird
  */
  function firebird(){
	$this->bancoDados(); 
  }
  
  /**
   * @desc Busca conex�o com servidor Firebird
   * @return boolean de Opera��o com sucesso 
  */
  function conecta(){
    // Busca conex�o com Interbase
    if (!($this->conexao = ibase_connect ($this->servidor.":".$this->nomeBanco,$this->usuario,$this->senha,"WIN1252", 0, 3))){
      $this->erros[] = "N�o foi poss�vel estabelecer a cone��o!"; 
      $this->erros[] = "Descri��o do erro:".ibase_errmsg();
      return 0;
    }
    
    return 1;
  }
  
  /**
   * @desc Encerra conex�o com servidor Firebird
   * @return boolean de Opera��o com sucesso 
  */
  function desconecta(){
    //Encerra conex�o   
    ibase_close($this->conexao);
    return 1;
  }
  
  /**
   * @desc Envia comando Insert para servidor
   * @return boolean de Opera��o com sucesso 
  */
  function enviaInsert(){
	$this->erros = array();   
    $this->sql = "Insert into ".$this->from." ( ".$this->insert." ) values( ".$this->values." )";
    
    if (!$this->conexao){
      $this->conecta();
    }
    
    if ($this->conexao){
      ibase_trans(IBASE_COMMITTED,$this->conexao); //Abrimos a transa��o....
      @ibase_query($this->sql); //O uso do "@" � para silenciar prov�veis erros
      if(ibase_errmsg()) {
        $this->erros[] = "N�o foi poss�vel salvar as suas informa��es!";
        $this->erros[] = "Descri��o do erro:".ibase_errmsg();
        $this->erros[] = "SQL: ".$this->sql;
        ibase_rollback($this->conexao);
        $this->limpaConsulta();
        return 0;
      } else {
        ibase_commit($this->conexao); //Comitamos a transa��o
        $this->limpaConsulta();
        return 1;
      }
    }
  }
  
  /**
   * @desc Envia comando Update para servidor
   * @return boolean de Opera��o com sucesso 
  */
  function enviaUpdate(){
	$this->erros = array();   
    $this->sql = "Update ".$this->from." set ".$this->update." where ".$this->where;
    
    if (!$this->conexao){
      $this->conecta();
    }
    
    if ($this->conexao){
      ibase_trans(IBASE_COMMITTED,$this->conexao); //Abrimos a transa��o....
      @ibase_query($this->sql); //O uso do "@" � para silenciar prov�veis erros
      if(ibase_errmsg()) {
        $this->erros[] = "N�o foi poss�vel salvar as suas informa��es!";
        $this->erros[] = "Descri��o do erro:".ibase_errmsg();
        $this->erros[] = "SQL: ".$this->sql;
        ibase_rollback($this->conexao);
        $this->limpaConsulta();
        return 0;
      } else {
        ibase_commit($this->conexao); //Comitamos a transa��o
        $this->limpaConsulta();
        return 1;
      }
    }
  }
  
  /**
   * @desc Envia comando Delete para servidor
   * @return boolean de Opera��o com sucesso 
  */
  function enviaDelete(){
	$this->erros = array();   
    $this->sql ="Delete from".$this->from." where ".$this->where;
    
    if (!$this->conexao){
      $this->conecta();
    }

    if ($this->conexao){
      ibase_trans(IBASE_COMMITTED,$this->conexao); //Abrimos a transa��o....
      @ibase_query($this->sql); //O uso do "@" � para silenciar prov�veis erros

      if(ibase_errmsg()) {
        $this->erros[] = "N�o foi poss�vel excluir registro!";
        $this->erros[] = "Descri��o do erro:".ibase_errmsg();
        $this->erros[] = "SQL: ".$this->sql;
        ibase_rollback($this->conexao);
        $this->limpaConsulta();
        return 0;
      } else {
      	ibase_commit($this->conexao); //Comitamos a transa��o
      	$this->limpaConsulta();
        return 1;
      }
    }
  }
    
  /**
   * @desc Envia comando Select para servidor
   * @return boolean de Opera��o com sucesso 
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
      $this->resultado = @ibase_query($this->conexao,$this->sql);
      if (ibase_errmsg()) {
        $this->erros[] = "N�o foi poss�vel realizar esta pesquisa!";
        $this->erros[] = "Descri��o do erro:".ibase_errmsg();
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
   * @return boolean de Opera��o com sucesso 
  */ 
  function enviaSelectNovamente(){
	$this->erros = array();   
    if (!$this->conexao){ 
      $this->conecta();
    }  

    if ($this->conexao){
      $this->resultado = @ibase_query($this->conexao,$this->sql);
      if (ibase_errmsg()) {
        $this->erros[] = "N�o foi poss�vel realizar esta pesquisa!";
        $this->erros[] = "Descri��o do erro:".ibase_errmsg();
        $this->erros[] = "SQL: ".$this->sql;
        return 0;
	  } else {
	    return 1;
      }
    }  
  }
  
  /**
   * @desc Apaga cache de consulta SQL
   * @return boolean de Opera��o com sucesso 
  */          
  function limpaSelect(){
    if ($this->conexao){
      ibase_free_result($this->resultado);
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
      while (ibase_fetch_row($this->resultado)) {
        $i++;
      }
	  $this->enviaSelectNovamente();
      return $i;
    }
	return 0;
  }
  
  /**
   * @desc Recupera pr�xima linha de cache de consulta SQL
   * @return array de Linha de cache de consulta SQL 
  */ 
  function linhaSelect(){
	if ($this->resultado){
      return @ibase_fetch_row($this->resultado);
	}
	return 0;
  }
}