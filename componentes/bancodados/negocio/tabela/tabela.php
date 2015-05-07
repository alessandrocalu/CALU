<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Dicionário de Dados
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 18/01/2008 Atualizado: 18/01/2008
*/

class tabela {
	
	var $id_tabela; //Código de identificação de tela
	
	var $nome; //Nome da tabela
	
	var $descricao; //Descrição da tabela
	
	var $campos; //Lista de campos de tabelar 
	
	var $chave; //Dados de campo Chave
	
	var $erros; //Contém vetor com lista de erros ocorridos 
	
	
	
	/**
   	 * @desc Limpa lista de Erros ocorridos 
  	*/  
	function limpaErros(){
		$this->erros = array();  	
	}
  
	/**
   	 * @desc Retorna lista de erros ocorridos 
   	 * @return array de Lista de Erros
  	*/
	function getErros(){
		return $this->erros;
	}	 
	
		
		
	/**
     * @desc Contrutor de Classe tela
     * @param $nome_tabela de Nome de Tabela que se deseja
    */
	function tabela($nome_tabela){
		global $negocio;
		if ($dados = $negocio->carregaDados(" id_tabela, nome, descricao ","calu_tabela"," nome = '".$nome_tabela."'")){
			$this->id_tabela = $dados[0][0];
			$this->nome = $dados[0][1];
			$this->descricao = $dados[0][2];
			if ($dados = $negocio->carregaDados(" id_tabela_campo, nome, chave_primaria, tipo, tamanho, not_null, descricao ","calu_tabela_campo"," tabela = ".$this->id_tabela)){	
				$this->campos = array();
				while (list($indice,$valor) = each($dados) ) {
					$this->campos[$indice]["id_tabela_campo"] = $valor[0];
					$this->campos[$indice]["nome"] = $valor[1];
					$this->campos[$indice]["pk"] = $valor[2];
					$this->campos[$indice]["tipo"] = $valor[3];
					$this->campos[$indice]["tamanho"] = $valor[4];
					$this->campos[$indice]["descricao"] = $valor[5];
					
					if ($this->campos[$indice]["pk"]){
						$this->chave = $this->campos[$indice]; 
					}
				}
			}	
			else
			{
				if ($erros = $negocio->getErros()) {
					$this->erros = $erros;
					return 0;	
				} 

			}
		}	
		else
		{
			if ($erros = $negocio->getErros()) {
				$this->erros = $erros;
				return 0;	
			} 
		}
		return 1;
	}
	
	
	/**
     * @desc Carrega dados de tabela segundo filtro
     * @param $campoFiltro de Campo de Filtro
     * @param $valorFiltro de Valor de Filtro
     * @param $tipoFiltro de Tipo de Filtro
     * @param $valorOrdem de Valor de Order By
     * @return array de Dados de tabela carregados
    */
	function carregar($campoFiltro = "",$valorFiltro = "",$tipoFiltro = " = ",$valorOrdem = "", $filtroFixo = "" ){
		global $negocio;
		$filtro = " 0 = 0 ";
		if ($campoFiltro && $valorFiltro){
			$filtro = $campoFiltro.$tipoFiltro.$valorFiltro; 
		}

		$campos = $this->getStrCampos(',');
		if (!$campos){
			$campos = " * ";
		}
		
		if ($filtroFixo)
		{
			$filtro .= " and ".$filtroFixo;
		}
		if ($dados_brutos = $negocio->carregaDados($campos,$this->nome,$filtro,$valorOrdem)){	
			$this->dados_tratados = array();
			while (list($indice,$valor) = each($dados_brutos) ) {
				for ($i=0;$i < count($this->campos);$i++){
					$dados_tratados[$indice][$this->campos[$i]["nome"]] = $valor[$i];
				}	
			}
			return $dados_tratados;
		}

		if ($erros = $negocio->getErros()) {
			$this->erros = $erros;
			return 0;	
		} 
		return 0;
	}
	
	
	/**
   	 * @desc Realiza insert de tabela em banco de dados
   	 * @param string $campos para customização de insert
   	 * @param string $valores para customização de insert
   	 * @param boolean $debug de Apresentação de scripts sqls
   	 * @return Sucesso em ação
  	*/
	function insert($camposInsert, $valoresInsert, $debug = 0){ 
		global $negocio;
		
		$campos = $this->montaCamposInsert($camposInsert);   
		$valores = $this->montaValoresInsert($valoresInsert); 
		
		if ($negocio->carregaInsert($campos,$this->nome,$valores,$debug)){
			return 1;
		}
		
		if ($erros = $negocio->getErros()) {
			$this->erros = $erros;
			return 0;	
		}	
		return 0;
	}
	
	/**
   	 * @desc Retorna campos de consulta para Insert
   	 * @return string de Clausula Insert
  	*/
	function montaCamposInsert($camposInsert = 0){
		//Monta Campos e Valores
		$campos = "";
		if (is_array($camposInsert)){
			for ($i = 0; $i < count($camposInsert) ;$i++){ 
				if ($i){
					$campos .= ",";
				}
				$campos .= $camposInsert[$i];
			} 
		}
		return $campos;
	}
	
	/**
   	 * @desc Retorna valores de consulta para Insert
   	 * @return string de Clausula Insert
  	*/
	function montaValoresInsert($valoresInsert = 0){
		//Monta Campos e Valores
		$valores = "";
		if (is_array($valoresInsert)){
			for ($i = 0; $i < count($valoresInsert) ;$i++){ 
				if ($i){
					$valores .= ",";
				}
				$valores .= $valoresInsert[$i];
			} 
		}
		return $valores;
	}	
	
	/**
   	 * @desc Realiza update em tabela em banco de dados
   	 * @param array $campos para customização de update
   	 * @param array $valores para customização de update
   	 * @param integer $valor_chave de valor de chave
     * @param boolean $debug de Apresentação de scripts sqls 
   	 * @return Sucesso em ação
  	*/
	function update($campos, $valores, $valor_chave, $filtros = "", $debug = 0){ 
		global $negocio;
		$camposValores = $this->montaCamposValoresUpdate($campos,$valores);	  
		$tabela = $this->nome;
		if (!$filtros){
			$filtros = $this->chave["nome"]." = '".$valor_chave."'";
		}	
		
		if ($negocio->carregaUpdate($camposValores,$tabela, $filtros, $debug)){ 
			return 1;
		} 
		
		if ($erros = $negocio->getErros()) {
			$this->erros = $erros;
			return 0;	
		}	
		return 0;
	}

	/**
   	 * @desc Retorna campos e valores de consulta para Update
   	 * @param array $campos para customização de update
   	 * @param array $valores para customização de update
   	 * @return string de Clausula Update
  	*/
	function montaCamposValoresUpdate($campos = 0,$valores = 0){
		//Monta Campos e Valores
		$camposValores = "";
		if (is_array($campos) && is_array($valores)){
			for ($i = 0; $i < count($campos) ;$i++){ 
				if ($i)
				{
					$camposValores .= ",";
				}
				$camposValores .= $campos[$i]." = ".$valores[$i]." ";
			} 
		}
		return $camposValores;
	}
	
	/**
   	 * @desc Realiza delete de tabela em banco de dados
   	 * @param string $valor_chave de Valor de Chave
   	 * @param $debug de Apresentação de scripts sqls
   	 * @return Sucesso em ação
  	*/
	function delete($valor_chave, $filtros = "", $debug = 0){ 
		global $negocio;
		if (!$filtros){
			$filtros = $this->chave["nome"]." = '".$valor_chave."'";
		}	
		
		if ($negocio->carregaDelete($this->nome,$filtros,$debug)){
			return 1;
		}
		
		if ($erros = $negocio->getErros()) {
			$this->erros = $erros;
			return 0;	
		}	
		return 0;
	}	
	
	
	/**
   	 * @desc Retorna o nome da Tabela 
     * @return string de Nome de Tabela
    */
	function getNome(){
		return $this->nome; 
	}
	
	/**
   	 * @desc Retorna lista nome de campos separados por caracter
   	 * @param string $caracter de separador
     * @return string de lista de nome de campos
    */
	function getStrCampos($caracter){
		$strCampos = "";
		if ($this->campos) {
			for ($i=0;$i< count($this->campos) ;$i++){
				if ($i){
					$strCampos .= $caracter;
				}
			
				//Para campos do tipo DATETIME 
				if ($this->campos[$i]["tipo"] == 4)
				{
					$strCampos .= "convert(char(11),".$this->campos[$i]["nome"].",103)+convert(char(5),".$this->campos[$i]["nome"].",108) as ".$this->campos[$i]["nome"];
				}
				else
				{
					$strCampos .= $this->campos[$i]["nome"];
				}
							
			}
		}		
		return $strCampos;	
	}

	
}