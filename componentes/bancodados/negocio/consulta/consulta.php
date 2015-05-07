<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Consulta a banco de dados
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 18/01/2008 Atualizado: 18/01/2008
*/

class consulta {
	
	private $id_consulta; //Código de identificação de consulta
	
    private $nome; //Nome da consulta
	
	private $clausula_order; //Clausula Order 
	
	private $clausula_group; //Clausula Group 
	
	private $froms; // Lista de tabelas do from
	
	private $apelidos; // Lista de apelidos de Clausula Select
	
	private $wheres; //Lista de clausulas where
	
	private $campos; //Lista de campos de consulta 
	
	private $erros; //Contém vetor com lista de erros ocorridos
	
	private $filtro_fixo = "";

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
   	 * @desc Retorna id de consulta
   	 * @return integer de ID de Consulta
  	*/
	function getIdConsulta(){
		return $this->id_consulta;	
	}
	
	/**
   	 * @desc Retorna lista de campos de consulta
   	 * @return array de Lista de Campos
  	*/
	function getCampos(){
		return $this->campos;
	}	 
	
	/**
   	 * @desc Configura lista de campos de consulta
   	 * @param array de Lista de Campos
  	*/
	function setCampos($campos){
		$this->campos = $campos;
	}
	
	/**
   	 * @desc Retorna lista de origens (froms)  de consulta
   	 * @return array de Lista de origens (froms)  de consulta
  	*/
	function getFroms(){
		return $this->froms;
	}	 
	
	/**
   	 * @desc Configura lista de origens (froms)  de consulta
   	 * @param array de Lista de origens (froms)  de consulta
  	*/
	function setFroms($froms){
		$this->froms = $froms;
	}
		 
	
	/**
   	 * @desc Retorna Clausula Group
   	 * @return string de Clausula Group
  	*/
	function getClausulaGroup(){
		return $this->clausula_group;
	}	 
	
	/**
   	 * @desc Configura Clausula Group
   	 * @param string de Clausula Group
  	*/
	function setClausulaGroup($clausula_group){
		$this->clausula_group = $clausula_group;
	}	 
	
	/**
   	 * @desc Retorna Clausula Order
   	 * @return string de Clausula Order
  	*/
	function getClausulaOrder(){
		return $this->clausula_order;
	}	 
	
	/**
   	 * @desc Configura Clausula Group
   	 * @param string de Clausula Group
  	*/
	function setClausulaOrder($clausula_order){
		$this->clausula_order = $clausula_order;
	}	

	/**
   	 * @desc Configura Filtro Fixo
   	 * @param string de Filtro Fixo
  	*/
	function setFiltroFixo($filtro_fixo){
		$this->filtro_fixo = $filtro_fixo;
	}
	
	/**
     * @desc Contrutor de Classe Consulta
     * @param $id_consulta de Identificação de Consulta
     * @return boolean de Sucesso em criação de objeto
    */
	function consulta($id_consulta){
		global $negocio;
		$dados = $negocio->buscaDadosConsulta($id_consulta); 
		if ($dados){  
			$this->id_consulta = $dados[0]["id_consulta"];
			$this->nome = $dados[0]["nome"];
			$this->clausula_order = $dados[0]["clausula_order"];
			$this->clausula_group = $dados[0]["clausula_group"];
			
			//Origens de Consulta
			$dados = $negocio->buscaDadosFromsConsulta($this->id_consulta);
			if ($dados){  
				$this->froms = $dados;
				for ($i=0; $i < count($this->froms);$i++){
					$this->apelidos[$this->froms[$i]["tabela"]] = $this->froms[$i]["apelido"];
				}	
			}
			
			//Campos de Consulta
			$dados = $negocio->buscaDadosCamposConsulta($this->id_consulta); 
			if ($dados){ 
				$this->campos = $dados;
				for ($i = 0; $i < count($this->campos) ;$i++){ 
					$dados = $negocio->buscaDadosCampoTabela($this->campos[$i]["campo"]);
					if ($dados){ 
						$this->campos[$i]["id_tabela_campo"] = $dados[0]["id_tabela_campo"];
						$this->campos[$i]["tabela"] = $dados[0]["tabela"];
						$this->campos[$i]["nome"] = $dados[0]["nome"];
						$this->campos[$i]["chave_primaria"] = $dados[0]["chave_primaria"];
						$this->campos[$i]["tipo"] = $dados[0]["tipo"];
						$dados_tipo = $negocio->buscaDadosTipoCampoTabela($this->campos[$i]["tipo"]);
						if ($dados_tipo){ 
							$this->campos[$i]["nome_tipo"] = $dados_tipo[0]["tipo"];
						}
						$this->campos[$i]["tamanho"] = $dados[0]["tamanho"];
						$this->campos[$i]["not_null"] = $dados[0]["not_null"];
						$this->campos[$i]["descricao"] = $dados[0]["descricao"];
					}
				}	
			}
			
			//Clausulas Where 
			$dados = $negocio->buscaDadosWheresConsulta($this->id_consulta); 
			if ($dados){ 
				$this->wheres = $dados;
				for ($i = 0; $i < count($this->wheres) ;$i++){ 
					//Tipo de where
					$dados = $negocio->buscaDadosTipoWhere($this->wheres[$i]["tipo"]);
					if ($dados){ 
						$this->wheres[$i]["nome_tipo"] = $dados[0]["nome"];
						$this->wheres[$i]["expressao"] = $dados[0]["expressao"];
					}
			
					//Campos de Where
					$dados = $negocio->buscaDadosCamposWhere($this->wheres[$i]["id_consulta_where"]);
					if ($dados){ 
						$this->wheres[$i]["campos"] = $dados; 
						for ($j = 0; $j < count($this->wheres[$i]["campos"]) ;$j++){ 	
							$dados_campo = $negocio->buscaDadosCampoTabela($this->wheres[$i]["campos"][$j]["campo"]);
							if ($dados_campo){ 
								$this->wheres[$i]["campos"][$j]["nome"] = $dados_campo[0]["nome"];	
								$this->wheres[$i]["campos"][$j]["tabela"] = $dados_campo[0]["tabela"];	
							}
						}	
					}
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
   	 * @desc Retorna filtro de consulta segundo tipos de filtros GETs e POSTs
   	 * @return string de Clausula Where
  	*/
	function montaFiltro(){
		global $funcoes;
		//Monta Filtros
		$filtros = "";
			
		for ($i = 0; $i < count($this->wheres) ;$i++){ 
			$ancoras = $funcoes->procuraAncoras($this->wheres[$i]["expressao"]);
			$valores = array();
			$conta_valor = 0;
		    for ($j = 0; $j < count($ancoras); $j++){
				if (strpos("_".$ancoras[$j],"campo")){
					for ($k = 0; $k < count($this->wheres[$i]["campos"]); $k++){
						if ($this->wheres[$i]["campos"][$k]["ancora"] == $ancoras[$j]){
							break;
						}
					}	
					$valores[$j] = $this->apelidos[$this->wheres[$i]["campos"][$k]["tabela"]].".".$this->wheres[$i]["campos"][$k]["nome"];
				}	
				else
				{
					
					$valores[$j] = "-111999111";
					
					// 0 - False ---- 1 - True
					if ($this->wheres[$i]["nome_tipo"] == "FalseTrue"){
						$valores[$j] = "0";
					}
						
					if ($this->wheres[$i]["nome_tipo"] == "between"){					
						$valores[$j] = "1969-12-31 00:00:00";					
					}
					
					//Procura por get de valor a partir de Valor;
					if ($_GET[$this->wheres[$i]["valor"]]){
						$valores[$j] = $_GET[$this->wheres[$i]["valor"]];
						continue;
					}
					
					//Procura por post de valor a partir de Valor;
					if ($_POST[$this->wheres[$i]["valor"]]){
						$valores[$j] = $_POST[$this->wheres[$i]["valor"]];
						continue;
					}
					
					//Procura por get de valor a partir de Valor (tipo between);
					if (($this->wheres[$i]["nome_tipo"] == "between") && ($_GET[$this->wheres[$i]["valor"].'0']) && ($_GET[$this->wheres[$i]["valor"].'0'] <> '00/00/0000')
					     && ($_GET[$this->wheres[$i]["valor"].'1']) && ($_GET[$this->wheres[$i]["valor"].'1'] <> '00/00/0000')) {		
						if ($ancoras[$j] == "valor0"){   
							$valores[$j] = $funcoes->formataDataBanco($_GET[$this->wheres[$i]["valor"].'0'])." 00:00:00";
						}	
						if ($ancoras[$j] == "valor1"){   
							$valores[$j] = $funcoes->formataDataBanco($_GET[$this->wheres[$i]["valor"].'1'])." 23:59:59";
						}	
						continue;
					}
					
					//Procura por post de valor a partir de Valor (tipo between);
					if (($this->wheres[$i]["nome_tipo"] == "between") && ($_POST[$this->wheres[$i]["valor"].'0']) && ($_POST[$this->wheres[$i]["valor"].'0'] <> '00/00/0000')
					     && ($_POST[$this->wheres[$i]["valor"].'1']) && ($_POST[$this->wheres[$i]["valor"].'1'] <> '00/00/0000')) {		
						if ($ancoras[$j] == "valor0"){   
							$valores[$j] = $funcoes->formataDataBanco($_POST[$this->wheres[$i]["valor"].'0'])." 00:00:00";
						}	
						if ($ancoras[$j] == "valor1"){   
							$valores[$j] = $funcoes->formataDataBanco($_POST[$this->wheres[$i]["valor"].'1'])." 23:59:59";
						}	
						continue;
					}
					
					//Procura por post de valor com Prefixo de id_consulta_where+Ancora;
					if ($_POST[$this->wheres[$i]["id_consulta_where"]."_".$ancoras[$j]]){
						$valores[$j] = $_POST[$this->wheres[$i]["id_consulta_where"]."_".$ancoras[$j]];
						continue;
					}	
					
					//Filtro defualt Chave
					if ($this->wheres[$i]["valor"] == ":chave"){
						$valores[$j] = $_GET["chave"];
						if (!$valores[$j]){
							$valores[$j] = $_POST["chave"];
						}
					}
					
					//Filtro defualt Ator	
					if ($this->wheres[$i]["valor"] == ":ator"){
						if ($_SESSION["navegador_ator"]) {
							$valores[$j] = $_SESSION["navegador_ator"];
						}
						else
						{
							$valores[$j] = -1;
						}	
					}	

					//Filtro defualt Mês
					if ($this->wheres[$i]["valor"] == ":mes"){
						//Procura por um filtro de Ano e Mês  :valorX_ano
						if ($_POST[$this->wheres[$i]["id_consulta_where"]."_valor0_ano"] && 
						    $_POST[$this->wheres[$i]["id_consulta_where"]."_valor0_mes"]){

							$ano = $_POST[$this->wheres[$i]["id_consulta_where"]."_valor0_ano"];	
							$mes = $_POST[$this->wheres[$i]["id_consulta_where"]."_valor0_mes"];
							if ($mes < 10){
								$mes = '0'.$mes;
							}
							if ($ancoras[$j] == "valor0"){   
								$valores[$j] = $ano."-".$mes."-01 00:00:00";
							}	
							if ($ancoras[$j] == "valor1"){   
								$valores[$j] = $ano."-".$mes."-".date("t",strtotime($ano."-".$mes."-01"))." 23:59:59";
							}	
						}
						else
						{
							if ($ancoras[$j] == "valor0"){   
								$valores[$j] = date("Y-m")."-01 00:00:00";
							}	
							if ($ancoras[$j] == "valor1"){   
								$valores[$j] = date("Y-m")."-".date("t")." 23:59:59";
							}	
						}	
					}	
					
					//Filtro defualt Dia
					if ($this->wheres[$i]["valor"] == ":dia"){
						//Procura por um filtro de Data :valorX_data
						if ($_POST[$this->wheres[$i]["id_consulta_where"]."_valor0_data"]){
							if ($ancoras[$j] == "valor0"){   
								$valores[$j] = $funcoes->formataDataBanco($_POST[$this->wheres[$i]["id_consulta_where"]."_valor0_data"]);
							}	
							if ($ancoras[$j] == "valor1"){ 
								
								if ($_POST[$this->wheres[$i]["id_consulta_where"]."_valor1_data"]){
									$valores[$j] = $funcoes->formataDataBanco($_POST[$this->wheres[$i]["id_consulta_where"]."_valor1_data"])." 23:59:59";
								}
								else
								{
									$valores[$j] = $funcoes->formataDataBanco($_POST[$this->wheres[$i]["id_consulta_where"]."_valor0_data"])." 23:59:59";
								}	
							}
						}
						else
						{	
							//Filtra por data atual
							if ($ancoras[$j] == "valor0"){   
								$valores[$j] = date("Y-m-d")." 00:00:00";
							}	
							if ($ancoras[$j] == "valor1"){   
								$valores[$j] = date("Y-m-d")." 23:59:59";
							}	
						}	
					}	
					
					//Filtro defualt DataHora
					if ($this->wheres[$i]["valor"] == ":dataHora"){
						//Procura por um filtro de Data :valorX_dia e Hora  :valorX_hora
						if ($_POST[$this->wheres[$i]["id_consulta_where"]."_valor0_dia"] &&
						    $_POST[$this->wheres[$i]["id_consulta_where"]."_valor0_hora"]){
							if ($ancoras[$j] == "valor0"){   
								$valores[$j] = "'".$funcoes->formataDataBanco($_POST[$this->wheres[$i]["id_consulta_where"]."_valor0_dia"])." ".
								                   $_POST[$this->wheres[$i]["id_consulta_where"]."_valor0_hora"]."'";
							}	
						}
						else
						{	
							//Filtra por data atual
							if ($ancoras[$j] == "valor0"){   
								$valores[$j] = "'".date("Y-m-d")." 00:00'";
							}	
						}	
					}
						
				}
			}
			
			//Valor fixo, independente de entrada de dados
			if ($this->wheres[$i]["nome_tipo"] == "fixo")
			{
				$filtro = " ".$this->wheres[$i]["valor"];
			}
			else
			{
				$filtro = " ".$funcoes->substituiAncoras($this->wheres[$i]["expressao"],$ancoras,$valores);
			}
			
			//Campo se, se preencheu filtra, se não ignora 
			if (strpos("_".$this->wheres[$i]["valor"],"se_") && (!($_POST[$this->wheres[$i]["valor"]])) && 
			   (!($_GET[$this->wheres[$i]["valor"]]))){
				
				if ($this->wheres[$i]["nome_tipo"] == "igualOr"){
					$filtro = "  (1 = 0) ";
				}
				elseif ($this->wheres[$i]["nome_tipo"] != "between"){
					$filtro = "  (1 = 1) ";
				}
				elseif ( (!$_POST[$this->wheres[$i]["valor"].'0']) || (!$_POST[$this->wheres[$i]["valor"].'0']) || 
				         ($_POST[$this->wheres[$i]["valor"].'0'] == '00/00/0000') || ($_POST[$this->wheres[$i]["valor"].'1'] == '00/00/0000')){
					$filtro = "  (1 = 1) ";
				}
			}
			
			//Apenda filtro
			$filtros .= $filtro;
		}
		
		//Sem filtros
		if(!$filtros)
		{
			$filtros = " 0 = 0 ";
		}
		
		//Acrescenta filtro fixo de consulta
		if ($this->filtro_fixo)
		{
			$filtros .= " and ".$this->filtro_fixo;
		}
	
		return $filtros;
	} 
	
	/**
   	 * @desc Retorna clausula FROM de consulta
   	 * @return string  de Clausula From
  	*/
	function montaFrom(){
		//Monta Froms
		$tabela = " ";
		for ($i = 0; $i < count($this->froms) ;$i++){ 
			$tabela .= $this->froms[$i]["clausula_join"]." ";
		}
		return $tabela;	
	}
	
	/**
   	 * @desc Retorna campos de consulta para select
   	 * @return string  de Clausula Select
  	*/
	function montaCampos(){
		//Monta Campos
		$campos = " ";
		for ($i = 0; $i < count($this->campos) ;$i++){ 
			for ($j = 0; $j < count($this->froms) ;$j++){ 
				if ($this->froms[$j]["id_consulta_from"] == $this->campos[$i]["origem"]){
					break;
				}
			}
			if ($i){
				$campos .= ",";
			}
			if ($this->campos[$i]["expressao"]){
				$campos .= $this->campos[$i]["expressao"]." ";
			}
			else
			{
				$campos .= $this->froms[$j]["apelido"].".".$this->campos[$i]["nome"]." ";
			}	
			 
		}
		return $campos;
	}

	/**
   	 * @desc Retorna ordem de consulta
   	 * @return string  de Clausula Order By
  	*/
	function montaOrdem(){
		global $funcoes, $ordem;
		//Monta Froms
		$order_by = "";
		if ($ordem)
		{
			//Ordenação por sequencia de campos
			$campo = $ordem;
			
			//Ordenação de campo de consulta
			for ($j = 0; $j < count($this->campos) ;$j++){ 
				if ($this->campos[$j]["ordem"] == $campo){
					$campo = $j;	
					break;
				}
			}
			
			for ($j = 0; $j < count($this->froms) ;$j++){ 
				if ($this->froms[$j]["id_consulta_from"] == $this->campos[$campo]["origem"]){
					break;
				}
			}
			if ($this->campos[$campo]["expressao"]){
				$order_by .= $this->campos[$campo]["expressao"];
			}
			else
			{
				$order_by .= $this->froms[$j]["apelido"].".".$this->campos[$campo]["nome"];
			}	
			if ($this->clausula_order && $order_by)
			{
				if ($this->clausula_order == $order_by){
					$order_by = '';
				}
				else
				{
					$order_by .= ',';
				}	
			}
		}
		$order_by .= $this->clausula_order;
		return $order_by;	
	}
	
	
	/**
   	 * @desc Retorna group by de consulta
   	 * @return string  de Clausula Group By
  	*/
	function montaGrupo(){
		//Monta Group By
		$grupo = "";
		$grupo .= $this->clausula_group;
		return $grupo;	
	}
	
	
	/**
   	 * @desc Retorna dados de consulta (realiza select em banco de dados)
   	 * @return array de Dados
  	*/
	function consultaSelect($identifica_origem = 1){ 
		global $negocio;
		$campos = $this->montaCampos();	
		$tabela = $this->montaFrom();
		$filtros = $this->montaFiltro();
		$order_by = $this->montaOrdem();
		$grupo = $this->montaGrupo();
		if ($dados_brutos = $negocio->carregaDados($campos,$tabela,$filtros,$order_by,$grupo)){
			//Nomeia dados Consulta
			$dados_tratados = array();
			while (list($indice,$valor) = each($dados_brutos) ) {
				for ($i=0;$i < count($this->campos);$i++){
					$dados_tratados[$indice][($identifica_origem?$this->campos[$i]["tabela"]."_":"").$this->campos[$i]["nome"]] = $valor[$i];
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
   	 * @desc Retorna total de registros de consulta 
   	 * @param array $camposFiltros para customização de pesquisa
   	 * @param array $valoresFiltros para customização de pesquisa
     * @param string $logico de operação logica ("and" ou "or") em customização
     * @param string $filtroFixo de Filtro fixo a ser adicionado em pesquisa 
   	 * @return int Total ou -1 caso ocorra falha
  	*/
	function consultaSelectCount($camposFiltros = 0, $valoresFiltros = 0, $logico = " and ", $filtroFixo = ""){ 
		global $negocio;
					
		$campos = " count(*) as Result ";	
		$tabela = $this->montaFrom();
		if ($camposFiltros && $valoresFiltros){
			$filtros = $this->montaFiltroUpdate($camposFiltros, $valoresFiltros, $logico);
		}
		else
		{
			$filtros = $this->montaFiltro();
		}	

		if ($filtroFixo){
			$filtros = " (".$filtros.") and ".$filtroFixo;
		}
		
		if ($dados_brutos = $negocio->carregaDados($campos,$tabela,$filtros)){
			return $dados_brutos[0][0];
		}
		
		if ($erros = $negocio->getErros()) {
			$this->erros = $erros;
			return -1;	
		}	
		return -1;
	}	
	
	
	/**
   	 * @desc Realiza update em tabela principal de consulta
   	 * @param array $campos para customização de update
   	 * @param array $valores para customização de update
   	 * @param array $camposFiltros para customização de pesquisa
   	 * @param array $valoresFiltros para customização de pesquisa
     * @param string $logico de operação logica ("and" ou "or") em customização
     * @param boolean $debug de Apresentação de scripts sqls 
   	 * @return Sucesso em ação
  	*/
	function consultaUpdate($campos = 0, $valores = 0, $camposFiltros = 0, $valoresFiltros = 0, $logico = " and ", $debug = 0){ 
		global $negocio;
		$camposValores = $this->montaCamposValoresUpdate($campos,$valores);	  
		$tabela = $this->montaFromInsertUpdate();
		$filtros = $this->montaFiltroUpdate($camposFiltros, $valoresFiltros, $logico);
		
		if ($negocio->carregaUpdate($camposValores,$tabela,$filtros, $debug)){ 
			return 1;
		} 
		
		if ($erros = $negocio->getErros()) {
			$this->erros = $erros;
			return 0;	
		}	
		return 0;
	}	
	
	/**
   	 * @desc Retorna from em tabela principal de consulta para Insert, Update e Delete
   	 * @return string de Clausula From para Insert e Update de Consulta
  	*/
	function montaFromInsertUpdate(){
		//Monta Froms
		$tabela = "";
		if (strpos($this->froms[0]["clausula_join"]," ")){
			$tabela .= " ".substr($this->froms[0]["clausula_join"],0,strpos($this->froms[0]["clausula_join"]," "));
		}
		else
		{
			$tabela .= " ".$this->froms[0]["clausula_join"];
		}	
		return $tabela;	
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
				if ($i){
					$camposValores .= ",";
				}
				$camposValores .= $campos[$i]." = ".$valores[$i]." ";
			} 
		}
		else
		{
			$vars = $_POST;
			
			//Monta Campos = Valores de tabela principal de consulta 
			$camposValores = "";
			for ($i = 0; $i < count($this->campos) ;$i++){ 
				if (($this->froms[0]["id_consulta_from"] == $this->campos[$i]["origem"]) &&
				    (!$this->campos[$i]["expressao"]) &&
				    $vars[$this->campos[$i]["nome"]] ) {
					if (!$camposValores){
						$camposValores .= ",";
					}
					$camposValores .= $this->campos[$i]["nome"]." = '".$vars[$this->campos[$i]["nome"]]."' ";
				}	
			}
		}
		return $camposValores;
	}
	
	
	/**
   	 * @desc Retorna filtro de update de consulta
   	 * @param array $camposFiltros para customização de filtro
   	 * @param array $valoresFiltros para customização de filtro
     * @param string $logico de operação logica ("and" ou "or") em customização
   	 * @return string de Clausula Where de Update
  	*/
	function montaFiltroUpdate($camposFiltros = 0, $valoresFiltros = 0, $logico = " and " ){
		$filtros = "";
		if (is_array($camposFiltros) && is_array($valoresFiltros)){
			for ($i = 0; $i < count($camposFiltros) ;$i++){ 
				if ($i){
					$filtros .= $logico;
				}
				$filtros .= $camposFiltros[$i]." = ".$valoresFiltros[$i]." ";
			} 
		}
		else
		{
			//Usa filtros de consulta para realizar update 
			$filtros = $this->montaFiltro();
		}
		if(!$filtros)
		{
			$filtros = " 1 = 0 ";
		}
		return $filtros;
	} 

	
	/**
   	 * @desc Realiza insert de consulta em banco de dados
   	 * @param array $camposInsert para customização de insert
   	 * @param array $valoresInsert para customização de insert
   	 * @param boolean $debug de Apresentação de scripts sqls
   	 * @return Sucesso em ação
  	*/
	function consultaInsert($camposInsert = 0, $valoresInsert = 0, $debug = 0){ 
		global $negocio;
		$campos = $this->montaCamposInsert($camposInsert);   
		$tabela = $this->montaFromInsertUpdate();
		$valores = $this->montaValoresInsert($valoresInsert); 

		if ($negocio->carregaInsert($campos,$tabela,$valores,$debug)){
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
		else
		{
			$vars = $_POST;
			
			//Monta Campos
			$campos = "";
			for ($i = 0; $i < count($this->campos) ;$i++){ 
				if (($this->froms[0]["id_consulta_from"] == $this->campos[$i]["origem"]) &&
				    (!$this->campos[$i]["expressao"]) &&
				    $vars[$this->campos[$i]["nome"]] ) {
					if (!$campos){
						$campos .= ",";
					}
					$campos .= $this->campos[$i]["nome"];
				}	
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
		else
		{
			$vars = $_POST;
			
			//Monta Campos
			$valores = "";
			for ($i = 0; $i < count($this->campos) ;$i++){ 
				if (($this->froms[0]["id_consulta_from"] == $this->campos[$i]["origem"]) &&
				    (!$this->campos[$i]["expressao"]) &&
				    $vars[$this->campos[$i]["nome"]] ) {
					if (!$campos){
						$valores .= ",";
					}
			$valores .= "'".$vars[$this->campos[$i]["nome"]]."'";
				}	
			}
		}
		return $valores;
	}

	/**
   	 * @desc Realiza delete de consulta em banco de dados
   	 * @param array $camposFiltros para customização de filtro
   	 * @param array $valoresFiltros para customização de filtro
     * @param string $logico de operação logica ("and" ou "or") em customização
   	 * @param $debug de Apresentação de scripts sqls
   	 * @return Sucesso em ação
  	*/
	function consultaDelete($camposFiltros = 0, $valoresFiltros = 0, $logico = " and ", $debug = 0){ 
		global $negocio;
		$tabela = $this->montaFromInsertUpdate();
		$filtros = $this->montaFiltroUpdate($camposFiltros, $valoresFiltros, $logico);
		
		if ($negocio->carregaDelete($tabela,$filtros,$debug)){
			return 1;
		}
		
		if ($erros = $negocio->getErros()) {
			$this->erros = $erros;
			return 0;	
		}	
		return 0;
	}	
	
}