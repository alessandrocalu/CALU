<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Conversação com Banco de Dados SQL Server
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 20/03/2007 Atualizado: 20/03/2007
*/
class sqlServer extends bancoDados {
 
  /**
   * @desc Contrutor de Classe sqlServer
  */
  function sqlServer(){
    $this->bancoDados();    	
  }

  /**
   * @desc Busca conexão com servidor SQL Server
   * @return boolean de Operação com sucesso
  */
  function conecta(){
    if (!($this->conexao = mssql_connect ($this->servidor,$this->usuario,$this->senha))){ 
	  mssql_select_db($this->nomeBanco, $this->conexao);
      $this->erros[] = "Não foi possível estabelecer a coneção!"; 
      $this->erros[] = "Descrição do erro:".mssql_get_last_message();
      return 0;
    }
     return 1;
  }
  
  /**
   * @desc Encerra conexão com servidor SQL Server
   * @return boolean de Operação com sucesso 
  */
  function desconecta(){
    //Encerra conexão 
    mssql_close($this->conexao);
    return 1;
  }
  
  /**
   * @desc Envia comando Insert para servidor
   * @return boolean de Operação com sucesso 
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
      if (!($this->resultado = mssql_query($this->sql))){
        $this->erros[] = "Não foi possível salvar as suas informações!";
        $this->erros[] = "Descrição do erro:".mssql_get_last_message();
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
      try {
        if (!($this->resultado = mssql_query($this->sql))){
          $this->erros[] = "Não foi possível salvar as suas informações!";
          $this->erros[] = "Descrição do erro:".mssql_get_last_message();
          $this->erros[] = "SQL: ".$this->sql;
          $this->limpaConsulta();
          return 0;
        } else {
          $this->limpaConsulta();
          return 1;
        }
      } catch (Exception $e) {
         $this->erros[] = "Erro ao gravar dados: ".$e->getMessage()."!";
         $this->limpaConsulta();
         return 0;
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
      if (!($this->resultado = mssql_query($this->sql))){
        $this->erros[] = "Não foi possível excluir registro!";
        $this->erros[] = "Descrição do erro:".mssql_get_last_message();
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
	
	if ($_GET["query"]){
		echo $this->sql."<BR>";
	}	
    
    if (!$this->conexao){ 
      $this->conecta();
    }  

    if ($this->conexao){
      if (!($this->resultado = mssql_query($this->sql))){
        $this->erros[] =  "Não foi possível realizar esta pesquisa!";
        $this->erros[] = "Descrição do erro:".mssql_get_last_message();
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
   * @desc Envia comando SQL livre
   * @return boolean de Operação com sucesso 
  */    
  function enviaSQL($sql_livre){
	$this->erros = array();  
    $this->sql = $sql_livre;
	if ($_GET["query"]){
		echo $this->sql."<BR>";
	}	
    
    if (!$this->conexao){ 
      $this->conecta();
    }  

    if ($this->conexao){
      if (!($this->resultado = mssql_query($this->sql))){
        $this->erros[] =  "Não foi possível realizar esta pesquisa!";
        $this->erros[] = "Descrição do erro:".mssql_get_last_message();
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
	$this->erros = array();  
  	if (!$this->conexao){ 
      $this->conecta();
    }  
 
    if ($this->conexao){
      if (!($this->resultado = mssql_query($this->sql))){
        $this->erros[] =  "Não foi possível realizar esta nova pesquisa!";
        $this->erros[] = "Descrição do erro:".mssql_get_last_message();
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
      mssql_free_result($this->resultado);
    }
    return 1;
  }
   
  /**
   * @desc Conta linhas de cache de consulta SQL
   * @return integer de Quantidade de linhas 
  */    
  function contaSelect(){
    if ($this->resultado){
      return mssql_num_rows($this->resultado);
    }
    return 0;
  }

  /**
   * @desc Recupera próxima linha de cache de consulta SQL
   * @return array de Linha de cache de consulta SQL 
  */
  function linhaSelect(){
    if ($this->resultado){
      return mssql_fetch_array($this->resultado);
    }
    return 0 ;
  }
  
  /**
   * @desc Função que Retorna lista de tabelas de banco de dados
   * @return array de Lista de tabelas (codigo,nome)
  */
  function getListaTabelaBD(){
  	if ((!is_array($this->listaTabelaBD)) || (!count($this->listaTabelaBD))){
  		$this->setSelect("id, name");
  		$this->setFrom("sysobjects");
  		$this->setWhere("xtype = 'U'");
    	$this->setGroup("");
    	$this->setOrder("name");
  		$this->enviaSelect();
  		while ($dados = $this->linhaSelect()){
  			$this->listaTabelaBD[] = array("codigo" => $dados[0], "nome" => $dados[1]);
  		}
  	}	
  	
    return $this->listaTabelaBD;
  }
  
  /**
   * @desc Função que Retorna lista de campos de tabela de banco de dados
   * @return array de Lista de tabelas (codigo,nome)
  */
  function getListaCampoTabelaBD($codigo){
  	$lista = array();
  	//56 = inteiro (1)
  	//167 = varchar (2)
  	//61 = data (4)
  	if ($codigo){
  		$lista = array();
  		$this->setSelect("colid, name, xtype, length");
  		$this->setFrom("syscolumns");
  		$this->setWhere("id = '".$codigo."'");
    	$this->setGroup("");
    	$this->setOrder("colid");
  		$this->enviaSelect();
  		while ($dados = $this->linhaSelect()){
  			$tipo = 1;
  			if ($dados[2] == 167){
  				$tipo = 2;
  			}
  			if ($dados[2] == 61){
  				$tipo = 4;
  			}
  			$lista[] = array("codigo" => $dados[0], "nome" => $dados[1], "tipo" => $tipo , "tamanho" => $dados[3]);
  		}
  	}	
  	
    return $lista;
  }
  
}