<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Conversação com Banco de Dados
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 20/03/2007 Atualizado: 20/03/2007
 * @abstract
*/
class bancoDados {
  
  protected $sql;  //Contém clausula SQL montada para envio a Servidor de dados 
  
  protected $insert; //Contém lista de campos separados por virgula da tabela para insert
  
  protected $values; //Contém valores de campos separados por vírgula da tabela para insert 
  
  protected $update; //Contém campos = valores para update em tabela 
  
  protected $where; //Contém clausula where de consulta SQL, Update ou Delete 
  
  protected $from; //Contém cláusula from para consulta SQL ou Delete
  
  protected $select; //Contém cláusula select para consulta SQL
  
  protected $order; //Contém cláusula order para consulta SQL
  
  protected $group; //Contém cláusula Group By para consulta SQL
  
  protected $banco; //Contém  ponteiro para banco de conexão
  
  protected $tipo; //Contém tipo do banco de dados (MySQL, SQL Server ou Firebird) 
  
  protected $conexao; //Contém ponteiro para conexão com banco de dados 

  protected $resultado; //Contém ponteiro para resultado de consulta SQL

  protected $erros; //Contém vetor com lista de erros ocorridos durante comunicação 
  
  protected $nomeBanco; //Contém nome do Banco de Conexão

  protected $usuario; //Contém nome do usuário de Conexão  

  protected $senha; //Contém senha de usuário de Conexão
 
  protected $servidor; //Contém servidor de Banco de Dados
  
  protected $listaTabelaBD = array(); //Lista de tabelas de Banco de Dados
  
  /**
   * @desc Contrutor de Classe bancoDados
  */
  function bancoDados(){
	global $tipoBancoConf,$usuarioBancoConf,$senhaBancoConf,
	       $servidorBancoConf,$nomeBancoConf; 
    $this->limpaConsulta();
    
    $this->tipo = $tipoBancoConf;
    $this->usuario = $usuarioBancoConf;
    $this->senha = $senhaBancoConf;
    $this->servidor = $servidorBancoConf;
    $this->nomeBanco = $nomeBancoConf;
    $this->conexao = 0; 
  }	
  
  /**
   * @desc Limpa parâmetros e resultados de última Consulta 
  */  
  function limpaConsulta(){
    $this->insert = "";
    $this->values = "";
    $this->update = "";
    $this->where = "";
    $this->from = "";
    $this->select = "";
  }
  
  /**
   * @desc Abstrai função de Busca conexão com servidor de Banco de Dados
  */
  function conecta(){
  	return 0;
  } 	
  
  /**
   * @desc Abstrai função Encerra conexão com servidor de Banco de Dados
  */
  function desconecta(){
    return 0;
  } 
  
  /**
   * @desc Abstrai função que Envia comando Insert para servidor
   * @return int de id de insert ou 0 para não sucesso
  */
  function enviaInsert(){
    return 0;  	
  }
  
  /**
   * @desc Abstrai função que Envia comando Update para servidor
  */
  function enviaUpdate(){
    return 0;  	
  } 		  
  
  /**
   * @desc Abstrai função que Envia comando Delete para servidor
  */
  function enviaDelete(){
    return 0;  	
  } 		  
  
  /**
   * @desc Abstrai função que Envia comando Select para servidor
  */
  function enviaSelect(){
  	return 0;
  }
  
  
  /**
   * @desc Abstrai função que Envia mesmo comando Select novamente para servidor
  */
  function enviaSelectNovamente(){
  	return 0;
  } 	
  
  /**
   * @desc Abstrai função que Apaga cache de consulta SQL
  */
  function limpaSelect(){
    return 0;
  }
   
  /**
   * @desc Abstrai função que Conta linhas de cache de consulta SQL
  */
  function contaSelect(){
    return 0;
  }

  /**
   * @desc Abstrai função que Conta linhas de cache de consulta SQL
  */
  function linhaSelect(){
    return 0 ;
  }
  
  /**
   * @desc Abstrai função que Retorna lista de tabelas de banco de dados
  */
  function getListaTabelaBD(){
    return 0;
  }
  
  /**
   * @desc Abstrai função que Retorna lista de campos de tabela de banco de dados
  */
  function getListaCampoTabelaBD(){
    return 0;
  }
  
  /**
   * @desc Recupera coluna de cache de consulta SQL
   * @return array de Coluna de cache de consulta SQL 
  */
  function colunaSelect($numCampo){
    $result = array();
    while ($dados = $this->linhaSelect()){
      $result[] = $dados[$numCampo];
    }
    $this->limpaSelect();
    $this->enviaSelectNovamente();
    return $result;
  }
  
  /**
   * @desc Recupera array multidimencinal de cache de consulta SQL
   * @return array multidimencinal de cache de consulta SQL 
  */
  function resultadoSelect(){
    $result = array();
    while ($dados = $this->linhaSelect()){
      $result[] = $dados;
    }
    $this->limpaSelect();
    $this->enviaSelectNovamente();
    return $result;
  }
  
  /**
   * @desc Retorna array com páginas de Listagem de Consulta SQL
   * @param string $campo de Campo de pesquisa "$campo in ()"
   * @param integer $numCampo de Número de campo chave para pesquisa
   * @param integer $linhas de Número de linhas para exibir
   * @param string $tipoChave de Tipo de chave (Numero,Texto)
   * @return array de Clausulas in (a,b,c,-1)
  */
  function resultadoPaginadoSelect($campo,$numCampo,$linhas,$tipoChave = "Numero"){
    $result = array();
    $contador = 0;
    while ($dados = $this->linhaSelect()){
      if ($contador == 0){
        $comando = $campo." in (";
      }
      $contador = $contador + 1;
      if ($contador == $linhas){
        if ($tipoChave == "Texto"){
          $comando .= "'".$dados[$numCampo]."')";
        }
        if ($tipoChave == "Numero"){
          $comando .= $dados[$numCampo].")";
        }
        $result[] = $comando;
        $contador = 0;
      }else{
        if ($tipoChave == "Texto"){
          $comando .= "'".$dados[$numCampo]."',";
        }
        if ($tipoChave == "Numero"){
          $comando .= $dados[$numCampo].",";
        }
      }
    }
    if (($contador < $linhas) && ($contador != 0)) {
      if ($tipoChave == "Texto"){
        $comando .= "'nenhum')";
      }
      if ($tipoChave == "Numero"){
        $comando .= "-1)";
      }
      $result[] = $comando;
    }
    $this->limpaSelect();
    $this->enviaSelectNovamente();
    return $result;
  }
  
  /**
   * @desc Adiciona Campo em Lista de Insert
   * @param string $campo de Nome de campo para Insert
   * @param string $valor de Valor de campo para Insert
  */
  function adicionaCampoInsert($campo,$valor){
    if ($valor) {
      if ($this->insert != '')
        $this->insert .= ",".$campo;
      else
        $this->insert = $campo;
      
      if ($this->values != '')
        $this->values .= ",".$valor;
      else
        $this->values = $valor;
    }
  }
  
  /**
   * @desc Adiciona Campo em Lista de Update
   * @param string $campo de Nome de campo para Update
   * @param string $valor de Valor de campo para Update
  */
  function adicionaCampoUpdate($campo,$valor){
    if ($this->update != '')
      $this->update .= ",".$campo." = ".$valor;
    else
      $this->update = $campo." = ".$valor;
  }

  /**
   * @desc Adiciona Campo em Lista de Where
   * @param string $campo de Nome de campo para Where
   * @param string $valor de Valor de campo para Where
   * @param string $tipo de Tipo de campo para Where
   * (igualNormal, igualString, likeFinal, likeMeio)
  */
  function adicionaCampoWhere($campo,$valor,$tipo){
    if (!($this->where)) {
      $this->where = "(0=0) ";
    }

    if ($valor) {
      $clausula = "";
      if ($tipo == "igualNormal") {
        $clausula = $campo." = ".$valor;
      }
      if ($tipo == "igualString") {
        $clausula = $campo." = '".$valor."'";
      }
      if ($tipo == "likeFinal") {
        $clausula = $campo." like '".$valor."%'";
      }
      if ($tipo == "likeMeio") {
        $clausula = $campo." like '%".$valor."%'";
      }
      $this->where = $this->where." and ".$clausula;
    }
  }

  /**
   * @desc Preenche Clausula Where
   * @param string $texto de Clausula Where
  */
  function setWhere($texto){
    $this->where = $texto;
  }
  
  /**
   * @desc Preenche Clausula From
   * @param string $texto de Clausula From
  */
  function setFrom($texto){
    $this->from = $texto;
  }

  /**
   * @desc Preenche Clausula Select
   * @param string $texto de Clausula Select
  */
  function setSelect($texto){
    $this->select = $texto;
  }

  /**
   * @desc Preenche Clausula Order By
   * @param string $texto de Clausula Order By
  */
  function setOrder($texto){
    $this->order = $texto;
  }
  
  /**
   * @desc Preenche Clausula Group By
   * @param string $texto de Clausula Group By
  */
  function setGroup($texto){
    $this->group = $texto;
  }
  
  /**
   * @desc Preenche Clausula Insert (nome de campos)
   * @param string $texto de Clausula Insert (nome de campos)
  */
  function setInsert($texto){
    $this->insert = $texto;
  }

  /**
   * @desc Preenche Clausula Insert (values)
   * @param string $texto de Clausula Insert (values)
  */
  function setValues($texto){
    $this->values = $texto;
  }

  /**
   * @desc Preenche Clausula Update
   * @param string $texto de Clausula Update
  */
  function setUpdate($texto){
    $this->update = $texto;
  }
  
  /**
   * @desc Retorna lista de erros ocorridos 
   * @return array de Lista de Erros
  */
  function getErros(){
    return $this->erros;
  }
   		  
}