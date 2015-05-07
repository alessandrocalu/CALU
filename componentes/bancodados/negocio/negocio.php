<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio 
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio {
  
	var $banco; //Mantem comunicação com Banco de Dados
  
	var $tela; //Mantem interface com usuário
  
	var $consulta; //Mantem View de consulta de dados
  
	var $tabela; //Mantem dicionário de dados  
  
	var $erros; //Contém vetor com lista de erros ocorridos  
  
	var $dados; //Contém dados de consulta
  
	var $mensagem; //Mensagem exibida em tela
 
	/**
	 * @desc Contrutor de Classe negocio
	*/
	function negocio(){
		global $tipoBancoConf;  
		if ($tipoBancoConf == "MySQL") {
			$this->banco = new mySQL;     
		}       
        if ($tipoBancoConf == "SQLServer") {
          $this->banco = new sqlServer;         
        }       
        if ($tipoBancoConf == "Firebird") {
          $this->banco = new firebird;          
        } 
	} 
  
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
	 * @desc Verifica acesso
	 * @param string de Login de Usuario
	 * @param string de Senha de Usuario
	 * @param int de Tipo de Usuario
	 * @return integer Id de usuario
	*/
	function verificaLogin($login, $senha, $tipo = 0){
		global $funcoes;
        $this->banco->setSelect("id, login, senha, tipo, nome, local");
		$this->banco->setFrom("calu_usuario");
		$filtro_tipo = "";
		if ($tipo){
			$filtro_tipo = " and tipo = ".$tipo;
		}
		$this->banco->setWhere("login = "."'".$login."' ".$filtro_tipo);
		$this->banco->setOrder("login");
        $this->banco->enviaSelect();
        if ($erros = $this->banco->getErros()) {
			$this->erros = $erros;
			return 0;     
        } 
        if (!($dados = $this->banco->linhaSelect())){
			$this->erros[] = 'Usuário não encontrado!';   
			return 0;             
        }       
        if (!strcmp($senha,$dados[2])) {
			//Passa dados para a sessão
			$_SESSION["navegador_id_usuario"] = $dados[0];
			$_SESSION["navegador_login_usuario"] = stripslashes($dados[1]);
			$_SESSION["navegador_tipo_usuario"] = $dados[3];
			$_SESSION["navegador_nome_usuario"] = $dados[4];          
			$_SESSION["navegador_local"] = $dados[5];    
			return $dados[0];
        }        
        return 0;
	}
  
	/**
	 * @desc Carrega dados de tabela de banco de dados
	 * @param  $campos de campos de select
	 * @param  $tabela de nome da tabela
	 * @param  $filtro de filtro de pesquisa
	 * @param  $order_by de Ordem By de pesquisa
	 * @param  $group_by de Group By de pesquisa
	 * @return array de Lista de Dados
	*/
	function carregaDados($campos, $tabela, $filtro = " 0=0 ", $order_by = "", $group_by = ""){
		//Faz consulta SQL em banco de dados 
        $this->banco->setSelect($campos);
        $this->banco->setFrom($tabela);
        $this->banco->setWhere($filtro);
		$this->banco->setOrder($order_by);
		$this->banco->setGroup($group_by);
		$this->banco->enviaSelect();
		if ($erros = $this->banco->getErros()) {
			$this->erros = $erros;
            return 0;       
		} 
		$conta = $this->banco->contaSelect();
		if ($conta == 0){
            $this->erros[] = "Nenhum registro encontrado!";
           return 0;       
        }
        return $this->banco->resultadoSelect();
	}
  
	/**
	 * @desc Realiza update em banco de dados
     * @param $camposValores de campos = valores de update
     * @param $tabela de nome da tabela
     * @param $filtro de filtro de Update
     * @param $debug de Apresentação de scripts de sqls
    */
	function carregaUpdate($camposValores, $tabela, $filtro = " 0=1 ", $debug = 1){
		if ($debug) {
			echo $camposValores."<BR>"; 
            echo $tabela."<BR>";
            echo $filtro."<BR>";
                  
            return 1;
		}     
          
        //Faz consulta SQL em banco de dados 
        $this->banco->setUpdate($camposValores);
        $this->banco->setFrom($tabela);
        $this->banco->setWhere($filtro);
        $this->banco->enviaUpdate();
        if ($erros = $this->banco->getErros()) {
			$this->erros = $erros;
            return 0;       
        } 
        return 1;
	} 
  
    /**
     * @desc Realiza insert em banco de dados
     * @param  $campos de campos de insert
     * @param  $tabela nome da tabela
     * @param  $valores Valores de insert
     * @param $debug de Apresentação de scripts de sqls
    */
    function carregaInsert($campos, $tabela, $valores, $debug = 1){
		if ($debug) {
			echo $campos."<BR>";
            echo $tabela."<BR>";
            echo $valores."<BR>";
          
            return 1;
        }
          
          
        //Faz consulta SQL em banco de dados 
        $this->banco->setInsert($campos);
        $this->banco->setFrom($tabela);
        $this->banco->setValues($valores);
        $this->banco->enviaInsert();
        if ($erros = $this->banco->getErros()) {
			$this->erros = $erros;
            return 0;       
        } 
        return 1;
	} 
  
	/**
	 * @desc Realiza delete em banco de dados
	 * @param  $tabela nome da tabela
	 * @param  $filtro filtro de pesquisa
     * @param $debug de Apresentação de scripts de sqls
	*/
	function carregaDelete($tabela, $filtro, $debug = 1){
		if ($debug) {
			echo $tabela."<BR>";
            echo $filtro."<BR>";
          
            return 1;
        }
                  
        //Faz consulta SQL em banco de dados 
        $this->banco->setFrom(" ".$tabela." ");
        $this->banco->setWhere($filtro);
        $this->banco->enviaDelete();
        if ($erros = $this->banco->getErros()) {
			$this->erros = $erros;
            return 0;       
        } 
        return 1;
	} 
  
	/**
     * @desc Mostrar local
     * @param  $local de Tela que deve ser mostrada
     * @return string de link de local
    */
    function mostraLocal($local)
    {
		global $result;
		$this->tela = new tela($local);
        if ($erros = $this->tela->getErros()) {
			$this->erros = $erros;
            return 0;       
        } 
          
        //Cria Consulta
        $this->consulta = new consulta($this->tela->getConsulta());   
          
        //Verifica customização de Select
        if (isset($_GET["select"])) {
			eval('$result = $this->select_'.$this->getNomeTela().'();');              
        }
 
        //Verifica customização Update
        if (isset($_GET["update"])) {
			eval('$result = $this->update_'.$this->getNomeTela().'();');              
        }
          
        //Verifica customização Insert
        if (isset($_GET["insert"])) {
			eval('$result = $this->insert_'.$this->getNomeTela().'();');              
        }
          
        //Verifica customização Delete
        if (isset($_GET["delete"])) {
			eval('$result = $this->delete_'.$this->getNomeTela().'();');              
        }
		
		if ($this->tela->getXajax() && (!$_GET["query"]) && (!$_POST["visual"] == 1)){
			require_once("./componentes/bancodados/negocio/negocio_xajax.php");  
		}
          
        //Retorna Consulta  
        return './componentes/bancodados/negocio/interface/'.$this->tela->getNomeTipo().'/'.$this->tela->getInterface();
	}
	
	/**
	 * @desc Registra auditoria de acesso a tela
	 * @param array $dados_auditoria de Dados de Auditoria
	*/
	function grava_auditoria($dados_auditoria){
		$retorno_auditoria = array();
		if ($dados_auditoria["tela"] && $dados_auditoria["usuario"] && $dados_auditoria["data_inicio_local"] && $dados_auditoria["data_fim_local"]){
			$campos = "tela,usuario,data_inicio,data_fim";
			$valores = $dados_auditoria["tela"].",".$dados_auditoria["usuario"].",'".$dados_auditoria["data_inicio_local"]."','".$dados_auditoria["data_fim_local"]."'";
			$this->carregaInsert($campos,"calu_auditoria",$valores,0);
			
			//Recupera codigo de acesso tela incluido
			if ($dados = $this->carregaDados("max(id_auditoria)","calu_auditoria"," (tela = ".$dados_auditoria["tela"].") and (usuario = ".$dados_auditoria["usuario"].") and (data_inicio = '".$dados_auditoria["data_inicio_local"]."') and (data_fim = '".$dados_auditoria["data_fim_local"]."') ")){
				if ($dados[0][0]) {
					$codigo_auditoria = $dados[0][0];	
				}
			}
			$campos = "auditoria,nome,valor,tipo";
			//Grava GETs de auditoria
			$gets = $dados_auditoria["get"];
			reset($gets);
			foreach ($gets as $chave_get => $valor_get){
				$valores = $codigo_auditoria.",'".$chave_get."','".$valor_get."','get'";
				$this->carregaInsert($campos,"calu_auditoria_var",$valores,0);
			}
			
			//Grava POSTs de auditoria
			$posts = $dados_auditoria["post"];
			reset($posts);
			foreach ($posts as $chave_post => $valor_post){
				$valores = $codigo_auditoria.",'".$chave_post."','".$valor_post."','post'";
				$this->carregaInsert($campos,"calu_auditoria_var",$valores,0);
			}
			
			//Grava SESSIONs de auditoria (somente as de prefixo navegador)
			$session = $dados_auditoria["session"];
			reset($session);
			foreach ($session as $chave_session => $valor_session){
				if (strpos("_".$chave_session, "navegador"))
				{
					$valores = $codigo_auditoria.",'".$chave_session."','".$valor_session."','session'";
					$this->carregaInsert($campos,"calu_auditoria_var",$valores,0);
				}	
			}
			
			//Atualiza dados de tela
			//Recupera dados atuais de tela
			if ($dados = $this->carregaDados("num_acesso, tempo_acesso","calu_tela"," (id_tela = ".$dados_auditoria["tela"].") ")){
				if ($dados[0][0]) {
					$num_acesso = $dados[0][0];
					$tempo_acesso = $dados[0][1];	
				}
			}
			
			//Atualiza Numero de Acesso e Tempo de Acesso
			$num_acesso++;
			$tempo_diferenca = (strtotime($dados_auditoria["data_fim_local"]) - strtotime($dados_auditoria["data_inicio_local"]));
			$retorno_auditoria["tempo_diferenca"] = $tempo_diferenca;
			if ($tempo_diferenca > 0){
				$tempo_acesso += $tempo_diferenca; 
			}
			else
			{
				$tempo_acesso++;
			}
			$retorno_auditoria["num_acesso"] = $num_acesso;
			$retorno_auditoria["tempo_acesso"] = $tempo_acesso;
			if ($num_acesso){
				$retorno_auditoria["media_segundos"] = round($tempo_acesso/$num_acesso);
			}
			else
			{
				$retorno_auditoria["media_segundos"] = 0;
			}	
			
			//Atualiza dados de tela
			$camposValores = " num_acesso = ".$num_acesso.", tempo_acesso = ".$tempo_acesso;
			$this->carregaUpdate($camposValores, "calu_tela", " id_tela = ".$dados_auditoria["tela"], 0);
		}	
		return $retorno_auditoria;
	}
	
	/**
	 * @desc Retorna dados de tabela TIPODOMINIO
	 * @param $id_dominio Identificador de Dominio
	 * @return array de Dados de regitro de dominio
	*/
	function buscaDadosTipoDominio($id_dominio){
		$result = 0;
		if (!isset($this->registro_tipodominio[$id_dominio])){ 
			if (!isset($this->tabela_tipo_dominio)){
				$this->tabela_tipo_dominio = new tabela("TIPODOMINIO"); 
			}	
	        if (isset($this->tabela_tipo_dominio)){
	            $this->registro_tipodominio[$id_dominio] = $this->tabela_tipo_dominio->carregar("id_dominio",$id_dominio," = ","nome"); 
				$result = $this->registro_tipodominio[$id_dominio];
	        }
	        if ($erros = $this->tabela_tipo_dominio->getErros()) {
	            $this->erros = $erros;
	        }  
		}
		else
		{
			$result = $this->registro_tipodominio[$id_dominio];
		}
        return $result;       
	}
	
	/**
	 * @desc Retorna dados de tabela DOMINIO
	 * @param $id_dominio Identificador de Dominio
	 * @return array de Dados de regitro de dominio
	*/
	function buscaDadosDominio($id_dominio){
		$result = 0;
		if (!isset($this->dados_dominio[$id_dominio])){ 
			if (!isset($this->dominio)){
				$this->dominio = new tabela("DOMINIO"); 
			}	
	        if ($this->dominio){
	            $this->dados_dominio[$id_dominio] = $this->dominio->carregar("id_dominio",$id_dominio," = ","codigo"); 
				$result = $this->dados_dominio[$id_dominio];
	        }
	        if ($erros = $this->dominio->getErros()) {
	            $this->erros = $erros;
	        }  
		}
		else
		{
			$result = $this->dados_dominio[$id_dominio];
		}
        return $result;       
	}
  
	/**
	 * @desc Retorna dados de tabela calu_tela
	 * @param $nome_tela de Nome de Tela que se deseja
	 * @param $id_tela de tela que se dejeja (opcional)
	 * @return array de Dados de regitro de
	*/
	function buscaDadosTela($nome_tela, $id_tela = 0){
		if (!isset($this->calu_tela)){
			$this->calu_tela = new tabela("calu_tela"); 
		}	
        if (isset($this->calu_tela)){
			//Apresenta tela 
            if ($id_tela){
				return $this->calu_tela->carregar("id_tela"," ".$id_tela." and (tipos like '%,".$_SESSION["navegador_tipo_usuario"].",%') ");
            }
			return $this->calu_tela->carregar("nome","'".$nome_tela."' and (tipos like '%,".$_SESSION["navegador_tipo_usuario"].",%') ");
        }
        if ($erros = $this->calu_tela->getErros()) {
			$this->erros = $erros;
        }  
		return 0;      
	}
  
	/**
   	 * @desc Retorna dados de tabela calu_tipo_tela
     * @param $id_tipo_tela Identificador de Tipo de Tela que se deseja
     * @return array de Dados de regitro de
    */
	function buscaDadosTipoTela($id_tipo_tela){
		$result = 0;
		if (!isset($this->dados_calu_tipo_tela[$id_tipo_tela])){
			if (!isset($this->calu_tipo_tela)){
				$this->calu_tipo_tela = new tabela("calu_tipo_tela"); 
			}	
	        if (isset($this->calu_tipo_tela)){
				$this->dados_calu_tipo_tela[$id_tipo_tela] = $this->calu_tipo_tela->carregar("id_tipo_tela",$id_tipo_tela); 
				$result = $this->dados_calu_tipo_tela[$id_tipo_tela];
	        }
	         if ($erros = $this->calu_tipo_tela->getErros()) {
	                $this->erros = $erros;
	         }
		}
		else
		{
			$result = $this->dados_calu_tipo_tela[$id_tipo_tela];
		}
		return $result;      
	}
  
    /**
     * @desc Retorna dados de tabela calu_tela_acao
     * @param $id_tela Identificador de Tela que se deseja
     * @return array de Dados de regitro de
    */
    function buscaDadosAcoesTela($id_tela){
		$result = 0;
		if (!isset($this->dados_calu_tela_acao[$id_tela])){
			if (!isset($this->calu_tela_acao)){
				$this->calu_tela_acao = new tabela("calu_tela_acao"); 
			}	
	        if (isset($this->calu_tela_acao)){
				$this->dados_calu_tela_acao[$id_tela] = $this->calu_tela_acao->carregar("tela",$id_tela," = ","ordem"); 
				$result = $this->dados_calu_tela_acao[$id_tela];
	        }
	        if ($erros = $this->calu_tela_acao->getErros()) {
				$this->erros = $erros;
	        }
		}
		else
		{
			$result = $this->dados_calu_tela_acao[$id_tela];
		}	  
        return $result;      
	}
    
    /**
     * @desc Retorna dados de tabela calu_tipo_acao
     * @param $id_tipo_tela Identificador de Tipo de Tela que se deseja
     * @return array de Dados de regitro de
    */
    function buscaDadosTipoAcao($id_tipo_acao){
		$result = 0;
		if (!isset($this->dados_calu_tipo_acao[$id_tipo_acao])){ 
			if (!isset($this->calu_tipo_acao)){
				$this->calu_tipo_acao = new tabela("calu_tipo_acao"); 
			}
	        if (isset($this->calu_tipo_acao)){
				$this->dados_calu_tipo_acao[$id_tipo_acao] = $this->calu_tipo_acao->carregar("id_tipo_acao",$id_tipo_acao); 
				$result= $this->dados_calu_tipo_acao[$id_tipo_acao];
	        }
	        if ($erros = $this->calu_tipo_acao->getErros()) {
				$this->erros = $erros;
	        }  
		}
		else
		{
			$result= $this->dados_calu_tipo_acao[$id_tipo_acao];
		}	
        return $result;      
	}
  
    /**
     * @desc Retorna dados de tabela calu_tela_campo
     * @param $id_tela Identificador de Tela que se deseja
     * @return array de Dados de regitro de
    */
    function buscaDadosCamposTela($id_tela){
    	global $funcoes;
		$result = 0;
		if (!isset($this->dados_calu_tela_campo[$id_tela])){
			if (!isset($this->calu_tela_campo)){
				$this->calu_tela_campo = new tabela("calu_tela_campo"); 
			}	
	        if (isset($this->calu_tela_campo)){
				$this->dados_calu_tela_campo[$id_tela] = $this->calu_tela_campo->carregar("tela",$id_tela," = ","ordem"); 
				$result = $this->dados_calu_tela_campo[$id_tela];
	        }
	        if ($erros = $this->calu_tela_campo->getErros()) {
				$this->erros = $erros;
	        }  
		}
		else
		{
			$result = $this->dados_calu_tela_campo[$id_tela];
		}	
        return $result;      
	}
    
    /**
     * @desc Retorna dados de tabela calu_tipo_comportamento_campo
     * @param $id_tipo_tela Identificador de Tipo de Comportamento que se deseja
     * @return array de Dados de regitro de
    */
    function buscaDadosComportamentoCampoTela($id_tipo_comportamento_campo){
		$result = 0;
		if (!isset($this->dados_calu_tipo_comportamento_campo[$id_tipo_comportamento_campo])){
			if (!isset($this->calu_tipo_comportamento_campo)){
				$this->calu_tipo_comportamento_campo = new tabela("calu_tipo_comportamento_campo"); 
			}	
	        if (isset($this->calu_tipo_comportamento_campo)){
				$this->dados_calu_tipo_comportamento_campo[$id_tipo_comportamento_campo] = $this->calu_tipo_comportamento_campo->carregar("id_tipo_comportamento_campo",$id_tipo_comportamento_campo); 
				$result = $this->dados_calu_tipo_comportamento_campo[$id_tipo_comportamento_campo];
	        }
	        if ($erros = $this->calu_tipo_comportamento_campo->getErros()) {
				$this->erros = $erros;
	        }
		}
		else
		{
			$result = $this->dados_calu_tipo_comportamento_campo[$id_tipo_comportamento_campo];
		}	  
        return $result;      
	}
  
    /**
     * @desc Retorna dados de tabela calu_campo_ligacao
     * @param $id_tipo_tela Identificador de Campo de tela
     * @return array de Dados de regitro de ligação
    */
    function buscaDadosLigacoesCampoTela($campo_tela){
		$result = 0;
		if (!isset($this->dados_calu_campo_ligacao[$campo_tela])){
			if (!isset($this->calu_campo_ligacao)){
				$this->calu_campo_ligacao = new tabela("calu_campo_ligacao"); 
			}	
	        if (isset($this->calu_campo_ligacao)){
				$this->dados_calu_campo_ligacao[$campo_tela] = $this->calu_campo_ligacao->carregar("campo_tela",$campo_tela," = ","ordem"); 
				$result = $this->dados_calu_campo_ligacao[$campo_tela];
	        }
	        if ($erros = $this->calu_campo_ligacao->getErros()) {
	                $this->erros = $erros;
	        }  
		}
		else
		{
			$result = $this->dados_calu_campo_ligacao[$campo_tela];
		}	
        return $result;      
	}
  
    /**
     * @desc Retorna dados de tabela calu_consulta_from
     * @param $id_consulta Identificador de Consutla
     * @return array de Dados de regitro de Froms de Consulta
    */
    function buscaDadosFromsConsulta($id_consulta){
		$result = 0;
		if (!isset($this->dados_calu_consulta_from[$id_consulta])){
			if (!isset($this->calu_consulta_from)){
				$this->calu_consulta_from = new tabela("calu_consulta_from"); 
			}	
	        if (isset($this->calu_consulta_from)){
				$this->dados_calu_consulta_from[$id_consulta] = $this->calu_consulta_from->carregar("consulta",$id_consulta," = ","ordem");
				$result = $this->dados_calu_consulta_from[$id_consulta]; 
	        }
	        if ($erros = $this->calu_consulta_from->getErros()) {
				$this->erros = $erros;
	        }  
		}
		else
		{
			$result = $this->dados_calu_consulta_from[$id_consulta];
		}	
        return $result;      
	}
  
    /**
     * @desc Retorna dados de tabela calu_consulta_campo
     * @param $id_consulta Identificador de Consutla
     * @return array de Dados de regitro de Campos de Consulta
    */
    function buscaDadosCamposConsulta($id_consulta){
		$result = 0;
		if (!isset($this->dados_calu_consulta_campo[$id_consulta])){
			if (!isset($this->calu_consulta_campo)){
				$this->calu_consulta_campo = new tabela("calu_consulta_campo"); 
			}	
	        if (isset($this->calu_consulta_campo)){
				$this->dados_calu_consulta_campo[$id_consulta] = $this->calu_consulta_campo->carregar("consulta",$id_consulta," = ","ordem"); 
				$result = $this->dados_calu_consulta_campo[$id_consulta];
	        }
	        if ($erros = $this->calu_consulta_campo->getErros()) {
				$this->erros = $erros;
	        }  
		}
		else
		{
			$result = $this->dados_calu_consulta_campo[$id_consulta];
		}	
        return $result;      
	}
  
    /**
     * @desc Retorna dados de tabela calu_consulta_where
     * @param $id_consulta Identificador de Consutla
     * @return array de Dados de regitro de Clausula Where
    */
    function buscaDadosWheresConsulta($id_consulta){
		$result = 0;
		if (!isset($this->dados_calu_consulta_where[$id_consulta])){
			if (!isset($this->calu_consulta_where)){
				$this->calu_consulta_where = new tabela("calu_consulta_where"); 
			}	
	        if (isset($this->calu_consulta_where)){
				$this->dados_calu_consulta_where[$id_consulta] = $this->calu_consulta_where->carregar("consulta",$id_consulta, " = ", "ordem"); 
				$result = $this->dados_calu_consulta_where[$id_consulta];
	        }
			if ($erros = $this->calu_consulta_where->getErros()) {
				$this->erros = $erros;
	        }  
		}
		else
		{
			$result = $this->dados_calu_consulta_where[$id_consulta];
		}	
        return $result;      
	}
  
    /**
     * @desc Retorna dados de tabela calu_where_campo
     * @param $id_consulta Identificador de Clausula Where
     * @return array de Dados de regitro de Campos de Where
    */
    function buscaDadosCamposWhere($id_consulta_where){
		$result = 0;
		if (!isset($this->dados_calu_where_campo[$id_consulta_where])){
			if (!isset($this->calu_where_campo)){
				$this->calu_where_campo = new tabela("calu_where_campo"); 
			}	
	        if (isset($this->calu_where_campo)){
				$this->dados_calu_where_campo[$id_consulta_where] = $this->calu_where_campo->carregar("clausula_where",$id_consulta_where); 
				$result = $this->dados_calu_where_campo[$id_consulta_where];
	        }
	        if ($erros = $this->calu_where_campo->getErros()) {
				$this->erros = $erros;
	        }  
		}
		else
		{
			$result = $this->dados_calu_where_campo[$id_consulta_where];
		}	
        return $result;      
    }
    
	/**
     * @desc Retorna dados de tabela calu_tabela
     * @param $id_tabela Identificador de Tabela
     * @return array de Dados de regitro de Tabela
    */
    function buscaDadosTabela($id_tabela){
		$result = 0;
		if (!isset($this->dados_calu_tabela[$id_tabela])){
			if (!isset($this->calu_tabela)){
				$this->calu_tabela = new tabela("calu_tabela"); 
		    }
	        if (isset($this->calu_tabela)){
				$this->dados_calu_tabela[$id_tabela] = $this->calu_tabela->carregar("id_tabela",$id_tabela); 
				$result = $this->dados_calu_tabela[$id_tabela];
	        }
	        if ($erros = $this->calu_tabela->getErros()) {
				$this->erros = $erros;
	        }  
		}
		else
		{
			$result = $this->dados_calu_tabela[$id_tabela];
		}	
        return $result;       
	}
  
    /**
     * @desc Retorna dados de tabela calu_tabela_campo
     * @param $id_consulta Identificador de Campo
     * @return array de Dados de regitro de Campo
    */
    function buscaDadosCampoTabela($id_tabela_campo){
		$result = 0;
		if (!isset($this->dados_calu_tabela_campo[$id_tabela_campo])){
			if (!isset($this->calu_tabela_campo)){
				$this->calu_tabela_campo = new tabela("calu_tabela_campo"); 
		    }
	        if (isset($this->calu_tabela_campo)){
				$this->dados_calu_tabela_campo[$id_tabela_campo] = $this->calu_tabela_campo->carregar("id_tabela_campo",$id_tabela_campo); 
				$result = $this->dados_calu_tabela_campo[$id_tabela_campo];
	        }
	        if ($erros = $this->calu_tabela_campo->getErros()) {
				$this->erros = $erros;
	        }  
		}
		else
		{
			$result = $this->dados_calu_tabela_campo[$id_tabela_campo];
		}	
        return $result;       
	}
    
    /**
     * @desc Retorna dados de tabela calu_consulta
     * @param $nome_tela de Identificação de Consulta que se deseja
     * @return array de Dados de regitro de Consulta
    */
    function buscaDadosConsulta($id_consulta = 0){
		$result = 0;
		if(!$id_consulta)
		{
			$id_consulta = $this->consulta->getIdConsulta();
		}
		
		if (!isset($this->dados_calu_consulta[$id_consulta])){
			if (!isset($this->calu_consulta)){
				$this->calu_consulta = new tabela("calu_consulta"); 
			}	
	        if (isset($this->calu_consulta)){
				//Apresenta tela 
				$this->dados_calu_consulta[$id_consulta] = $this->calu_consulta->carregar("id_consulta",$id_consulta); 
				$result = $this->dados_calu_consulta[$id_consulta];
	        }
	        if ($erros = $this->calu_consulta->getErros()) {
				$this->erros = $erros;
	        }  
		}	
		else
		{
			$result = $this->dados_calu_consulta[$id_consulta];
		}
        return $result;      
	}
	
    /**
     * @desc Retorna dados de tabela calu_tipo_campo
     * @param $nome_tela de Identificação de Tipo de Campo
     * @return array de Dados de regitro de Tipo de Campo
    */
    function buscaDadosTipoCampoTabela($id_tipo_campo){
		$result = 0;
		if (!isset($this->dados_calu_tipo_campo[$id_tipo_campo])){
			if (!isset($this->calu_tipo_campo)){
				$this->calu_tipo_campo = new tabela("calu_tipo_campo"); 
			}	
	        if (isset($this->calu_tipo_campo)){
				//Apresenta tela 
	            $this->dados_calu_tipo_campo[$id_tipo_campo] = $this->calu_tipo_campo->carregar("id_tipo_campo",$id_tipo_campo); 
				$result = $this->dados_calu_tipo_campo[$id_tipo_campo];
	        }
	        if ($erros = $this->calu_tipo_campo->getErros()) {
				$this->erros = $erros;
	        }  
		}
		else
		{
			$result = $this->dados_calu_tipo_campo[$id_tipo_campo];
		}	
        return $result;   
	}
  
    /**
     * @desc Retorna dados de tabela calu_tipo_where
     * @param $id_tipo_where de Identificação de Tipo de Where
     * @return array de Dados de regitro de Tipo de Where
    */
    function buscaDadosTipoWhere($id_tipo_where = 0){
		if ($id_tipo_where){
	    	$result = 0;
			if (!isset($this->dados_calu_tipo_where[$id_tipo_where])){
				if (!isset($this->calu_tipo_where)){
					$this->calu_tipo_where = new tabela("calu_tipo_where"); 
				}	
		        if (isset($this->calu_tipo_where)){
					//Apresenta tela 
					$this->dados_calu_tipo_where[$id_tipo_where] = $this->calu_tipo_where->carregar("id_tipo_where",$id_tipo_where); 
					$result = $this->dados_calu_tipo_where[$id_tipo_where];
		        }
		        if ($erros = $this->calu_tipo_where->getErros()) {
					$this->erros = $erros;
		        }  
			}
			else
			{
				$result = $this->dados_calu_tipo_where[$id_tipo_where];
			}
		}
		else
		{
			$result = 0;
			if (!isset($this->dados_calu_tipo_where["todos"])){
				if (!isset($this->calu_tipo_where)){
					$this->calu_tipo_where = new tabela("calu_tipo_where"); 
				}	
		        if (isset($this->calu_tipo_where)){
					//Apresenta tela 
					$this->dados_calu_tipo_where["todos"] = $this->calu_tipo_where->carregar("","","","id_tipo_where"); 
					$result = $this->dados_calu_tipo_where;
		        }
		        if ($erros = $this->calu_tipo_where->getErros()) {
					$this->erros = $erros;
		        }  
			}
			else
			{
				$result = $this->dados_calu_tipo_where["todos"];
			}
		}		
        return $result;       
    }
    
    /**
     * @desc Retorna lista de tabelas de Banco de Dados
     * @return array (codigo, nome)
    */
    function buscaListaTabelaBD(){
    	return $this->banco->getListaTabelaBD();
    }
    
	/**
     * @desc Retorna lista de campos de uma tabela
     * @param int $codigo de Codigo interno de tabela
     * @return array (codigo, nome, tipo, tamanho)
    */
    function buscaListaCampoTabelaBD($codigo){
    	return $this->banco->getListaCampoTabelaBD($codigo);
    }
  
    /**
     * @desc Retorna a descrição do tipo de campo
     * @param int $id_tipo_campo de identificação de tipo de campo
     * @return string de nome de tipo de campo
    */
    function nomeTipoCampo($id_tipo_campo){
		$result = "";
		if (!isset($this->nome_tipo_campo[$id_tipo_campo])){
			$dados = $this->buscaDadosTipoCampoTabela($id_tipo_campo);
			if ($dados){
                $this->nome_tipo_campo[$id_tipo_campo] = $dados[0]["tipo"];
				$result = $this->nome_tipo_campo[$id_tipo_campo];
			}
		}
		else
		{
			$result = $this->nome_tipo_campo[$id_tipo_campo];
		}	
		return $result;
    }
  
    /**
     * @desc Retorna a descrição do tipo de where
     * @param int $id_tipo_campo de identificação de tipo de where
     * @return string de nome de tipo de where
    */
    function nomeTipoWhere($id_tipo_where){
		$result = "";
		if (!isset($this->nome_tipo_where[$id_tipo_where])){
			$dados = $this->buscaDadosTipoWhere($id_tipo_where);
	        if ($dados){
				$this->nome_tipo_where[$id_tipo_where] = $dados[0]["nome"];
				$result = $this->nome_tipo_where[$id_tipo_where];
	        }
		}
		else
		{
			$result = $this->nome_tipo_where[$id_tipo_where];
		}
		return $result;
    }
    
	/**
     * @desc Retorna lista de tipo de where
     * @return array("values","labels")
    */
    function listaTipoWhere(){
    	global $funcoes;
    	$dados = $this->buscaDadosTipoWhere();
    	if ($dados["todos"]){
	        for ($i = 0; $i < count($dados["todos"]); $i++){
            	$values[] = $dados["todos"][$i]["id_tipo_where"];
                $labels[] = $dados["todos"][$i]["nome"];
            }
            return array("values" => $values,"labels" => $labels);
        }
        return 0;
    }
  
    /**
     * @desc Retorna a descrição do tipo de tela
     * @param int $id_tipo_campo de identificação de tipo de tela
     * @return string de nome de tipo de tela
    */
    function nomeTipoTela($id_tipo_tela){
		$result = "";
		if (!$this->nome_tipo_tela[$id_tipo_tela]){
			$dados = $this->buscaDadosTipoTela($id_tipo_tela);	
	        if ($dados){
				$this->nome_tipo_tela[$id_tipo_tela] = $dados[0]["tipo"];
				$result = $this->nome_tipo_tela[$id_tipo_tela];
	        }
		}
		else
		{
			$result = $this->nome_tipo_tela[$id_tipo_tela];
		}	
        return $result;
    }
	
	/**
     * @desc Retorna a descrição do tipo de acao
     * @param int $id_tipo_campo de identificação de tipo de ação
     * @return string de nome de tipo de ação
    */
    function nomeTipoAcao($id_tipo_acao){
		$result = "";
		if (!$this->nome_tipo_acao[$id_tipo_acao]){
			$dados = $this->buscaDadosTipoAcao($id_tipo_acao);
			if ($dados){
                $this->nome_tipo_acao[$id_tipo_acao] = $dados[0]["tipo"];
				$result = $this->nome_tipo_acao[$id_tipo_acao];
			}
		}
		else
		{
			$result = $this->nome_tipo_acao[$id_tipo_acao];
		}	
		return $result;
    }
	
	/**
	 * @desc Retorna lista de Tabela(codigo,nome) com link Selecionado 
	 * @return array de lista
	*/
    function listaTabelaLink($id_tabela_campo, $filtroFixo = ""){
    	if (!$this->dados_tabela_link[$id_tabela_campo]){
    		//Descobre nome de tabela de id_campo
			$dados_campo = $this->buscaDadosCampoTabela($id_tabela_campo);
			$dados_tabela = $this->buscaDadosTabela($dados_campo[0]['tabela']);
			$tabela = new tabela($dados_tabela[0]["nome"]);
			$this->dados_tabela_link[$id_tabela_campo]["dados"] = $tabela->carregar("",""," = ", $dados_campo[0]["nome"], $filtroFixo);
			$this->dados_tabela_link[$id_tabela_campo]["campo_nome"] = $dados_campo[0]["nome"];
			$this->dados_tabela_link[$id_tabela_campo]["chave_nome"] = $tabela->chave["nome"];
    	}
		
    	$dados = $this->dados_tabela_link[$id_tabela_campo]["dados"];
    	$codigo = $this->dados_tabela_link[$id_tabela_campo]["chave_nome"];
    	$nome = $this->dados_tabela_link[$id_tabela_campo]["campo_nome"];
		for ($i = 0; $i < count($dados); $i++){
	        $values[] = $dados[$i][$codigo];
			$labels[] = $dados[$i][$nome];
		}
        return array("values" => $values,"labels" => $labels);
    }
  
    /**
     * @desc Retorna botões de açoes de Tabela
     * @param int $tabela de identificação de tabela
     * @return string de html de botões
    */
    function botoesAcoesTabela($tabela){
		$botoes = "";  
        //Campos 
        $botoes .= "&nbsp<a href=\"?local=lista_campo_tabela&chave=".$tabela."\"><img src=\"./images/show.gif\"   title='Exibe Campos'/></a>";  
        //Apagar Tabalea
        $botoes .= "&nbsp<a href=\"?local=lista_tabela&delete=apaga_tabela&tabela=".$tabela."\"><img src=\"./images/del.gif\"   title='Apaga Tabela'/></a>";  
        return $botoes;
    }
  
    /**
     * @desc Retorna botões de açoes de Campo Tabela
     * @param int $tabela de identificação de campo tabela
     * @return string de html de botões
    */
    function botoesAcoesCampoTabela($campo_tabela){
    	global $chave;
    	$botoes = "";
    	$botoes .= "<a href=\"?local=cad_campo_tabela&se_campo=".$campo_tabela."&chave=".$chave."\"><img src=\"./images/edit.gif\" title='Edita Campo'/></a>";
    	$botoes .= "&nbsp<a href=\"?local=lista_campo_tabela&delete=apaga_campo&chave=".$chave."&se_campo=".$campo_tabela."\"><img src=\"./images/del.gif\"   title='Apaga Tabela'/></a>";
		return $botoes;
    }
  
    /**
     * @desc Retorna botões de açoes de Consulta
     * @param int $consulta de identificação de consulta
     * @return string de html de botões
    */
    function botoesAcoesConsulta($consulta){
        $botoes = "";
        //Froms 
        $botoes .= "<a href=\"?local=lista_from_consulta&chave=".$consulta."\"><img src=\"./images/from.gif\"   title='Exibe Froms'/></a>";  
        //Wheres 
        $botoes .= "&nbsp<a href=\"?local=lista_where_consulta&chave=".$consulta."\"><img src=\"./images/where.gif\"   title='Exibe Wheres'/></a>";  
        //Campos 
        $botoes .= "&nbsp<a href=\"?local=lista_campo_consulta&chave=".$consulta."\"><img src=\"./images/show.gif\"   title='Exibe Campos'/></a>";  
        //Editar Consulta
        $botoes .= "&nbsp<a href=\"?local=cad_consulta&se_campo=".$consulta."\"><img src=\"./images/edit.gif\"   title='Edita Consulta'/></a>";
        //Apagar Consulta
        $botoes .= "&nbsp<a href=\"?local=lista_consulta&delete=apaga_consulta&se_campo=".$consulta."\"><img src=\"./images/del.gif\"   title='Apaga Consulta'/></a>"; 
        
        return $botoes; 
    }
  
    /**
     * @desc Retorna botões de açoes de From de Consulta
     * @param int $consulta de identificação de From de consulta
     * @return string de html de botões
    */
    function botoesAcoesFromConsulta($consulta_from){
    	global $chave;
        $botoes = "";
        //Editar From Consulta
        $botoes .= "&nbsp<a href=\"?local=cad_from_consulta&chave=".$chave."&se_campo=".$consulta_from."\"><img src=\"./images/edit.gif\"   title='Edita From de Consulta'/></a>";
        $botoes .= "&nbsp<a href=\"?local=lista_from_consulta&delete=apaga_from&se_campo=".$consulta_from."&chave=".$chave."\"><img src=\"./images/del.gif\"   title='Apaga From de Consulta'/></a>";  
        return $botoes;
    }
    
    /**
     * @desc Retorna botões de açoes de Where de Consulta
     * @param int $consulta de identificação de Where de consulta
     * @return string de html de botões
    */
    function botoesAcoesWhereConsulta($consulta_where){
    	global $chave;
        $botoes = "";
        //Editar Where Consulta
        $botoes .= "&nbsp<a href=\"?local=cad_where_consulta&se_campo=".$consulta_where."&chave=".$chave."\"><img src=\"./images/edit.gif\"   title='Edita Where de Consulta'/></a>";
		//Apagar
        $botoes .= "&nbsp<a href=\"?local=lista_where_consulta&delete=apaga_where&se_campo=".$consulta_where."&chave=".$chave."\"><img src=\"./images/del.gif\"  title='Apaga Where de Consulta'/></a>";
        return $botoes;
    }
  
    /**
     * @desc Retorna botões de açoes de Campo de Consulta
     * @param int $consulta_campo de identificação de Campo de consulta
     * @param int $consulta_ligacao de identificação de Campo de ligação
     * @return string de html de botões
    */
    function botoesAcoesCampoConsulta($consulta_campo, $consulta_ligacao){
        $botoes = "";
        //Campos Ligação
        $botoes .= "&nbsp<a href=\"?local=lista_ligacao_campo_consulta&chave=".$consulta_ligacao."\"><img src=\"./images/show.gif\"   title='Exibe Ligação Tela'/></a>";  
        //Editar Where Consulta
        $botoes .= "&nbsp<a href=\"?local=cad_campo_consulta&chave=".$consulta_campo."\"><img src=\"./images/edit.gif\"   title='Edita Campo de Consulta'/></a>";  
        return $botoes;
    }
  
    /**
     * @desc Retorna botões de açoes de Tela
     * @param int $consulta de identificação de Tela
     * @return string de html de botões
    */
    function botoesAcoesTela($tela){
        //Busca local tela 
        $local_tela = "";
        $dados_tela = $this->buscaDadosTela("",$tela);
        if ($dados_tela){
                $local_tela = $dados_tela[0]['nome'];
        }
                  
        $botoes = "";
        //View Tela 
        $botoes .= "<a href=\"?local=".$local_tela."\"><img src=\"./images/view.gif\"   title='Visualiza Tela'/></a>";  
        //Ações 
        $botoes .= "&nbsp<a href=\"?local=lista_acao_tela&chave=".$tela."\"><img src=\"./images/run.gif\"   title='Exibe Ações'/></a>";  
        //Campos 
        $botoes .= "&nbsp<a href=\"?local=lista_campo_tela&chave=".$tela."\"><img src=\"./images/show.gif\"   title='Exibe Campos'/></a>";  
        //Editar Tela
        $botoes .= "&nbsp<a href=\"?local=cad_tela&chave=".$tela."\"><img src=\"./images/edit.gif\"   title='Edita Tela'/></a>";  
        
        return $botoes;
    }
	
	/**
     * @desc Retorna botões de açoes de Ação Tela
     * @param int $consulta de identificação de Ação de Tela
     * @return string de html de botões
    */
    function botoesAcoesAcaoTela($acao_tela){
        $botoes = "";
        //Editar Ação Tela
        $botoes .= "&nbsp<a href=\"?local=cad_acao_tela&chave=".$acao_tela."\"><img src=\"./images/edit.gif\"   title='Edita Ação de Tela'/></a>";  
        
        return $botoes;
    }
  
    /**
     * @desc Retorna botões de açoes de Menu
     * @param int $consulta de identificação de Menu
     * @return string de html de botões
    */
    function botoesAcoesMenu($menu){
        //Busca local tela 
        $link_menu = "";
        $dados_menu = $this->getItensMenu($menu);
        if ($dados_menu){
                $link_menu = $dados_menu[0]['link'];
        }
                  
        $botoes = "";
        //View Tela 
        $botoes .= "<a href=\"".$link_menu."\"><img src=\"./images/view.gif\"   title='Visualiza Menu'/></a>";  

        //Editar Menu
        $botoes .= "&nbsp<a href=\"?local=cad_menu&chave=".$menu."\"><img src=\"./images/edit.gif\"   title='Edita Menu'/></a>";  
        
        return $botoes;
    }
    
	/**
     * @desc Retorna o ID da tela 
     * @return string de ID de Tela
    */
    function getCodigoTela(){
        return $this->tela->getTela(); 
    }
  
    /**
     * @desc Retorna o nome da tela 
     * @return string de Nome de Tela
    */
    function getNomeTela(){
        return $this->tela->getNome(); 
    }
    
    /**
     * @desc Retorna a descrição da Tela 
     * @return string de Descrição da Tela
    */
    function getDescricaoTela(){
        return $this->tela->getDescricao()." :: ".$_SESSION["navegador_nome_usuario"]; 
    }
    
    /**
     * @desc Retorna a descrição da Tela 
     * @return string de Descrição da Tela
    */
    function getTituloTela(){
        return $this->tela->getDescricao(); 
    }
    
    /**
     * @desc Retorna flag sessão de Tela 
     * @return string de Sessão de Tela
    */
    function getSessaoTela(){
        return $this->tela->getSessao(); 
    }
    
	/**
     * @desc Retorna flag xajax de Tela 
     * @return string de Xajax de Tela
    */
    function getXajaxTela(){
        return $this->tela->getXajax(); 
    }
    
	/**
     * @desc Retorna Tipo de Tela 
     * @return int de Tipo de Tela
    */
    function getTipoTela(){
        return $this->tela->getTipo(); 
    }	
    
    /**
     * @desc Retorna a Lista de Ações da Tela 
     * @return array de Lista de Ações
    */
    function getAcoesTela(){
        return $this->tela->getAcoes(); 
    }
    
    /**
     * @desc Retorna a Lista de Campos da Tela 
     * @return array de Lista de Campos
    */
    function getCamposTela($grupo = ""){
         return $this->tela->getCampos($grupo);
    }
    
    /**
     * @desc Retorna Itens de Menu
     * @param $id_opcao_menu de opção específica de Menu (opcional)
     * @return string de Descrição da Tela
    */
    function getItensMenu($id_opcao_menu = 0){
		if (!isset($this->calu_opcao_menu)){
			$this->calu_opcao_menu = new tabela("calu_opcao_menu"); 
		}	
        if (isset($this->calu_opcao_menu)){
			if ($id_opcao_menu){
				return $this->calu_opcao_menu->carregar("id_opcao_menu"," ".$id_opcao_menu." "," = ", "ordem"); 
			}
			return $this->calu_opcao_menu->carregar("tipos","'%,".$_SESSION["navegador_tipo_usuario"].",%'"," like ", "ordem"); 
        }
        if ($erros = $this->calu_opcao_menu->getErros()) {
			$this->erros = $erros;
        }  
        return 0;      
    }
  
    /**
     * @desc Retorna Dados de Consulta
     * @return array de Lista de Dados
    */
    function getDadosConsulta($identifica_origem = 1){  
        return $this->consulta->consultaSelect($identifica_origem); 
    }
    
    /**
     * @desc Retorna Dados de Tela
     * @return array de Lista de Campos e Dados
    */
    function getDadosTela(){
        return $this->tela->consultaDados();
    }
    
    /**
     * @desc Retorna Mensagem de Tela
     * @return string de Mensagem de tela
    */
    function getMensagem(){
        return $this->mensagem;
    }
    
	/**
     * @desc Realiza customização de Insert de Lista  de tabela
    */
	function insert_lista_tabela(){
		if ($_GET['insert'] == 'nova'){
			unset($_GET['insert']);
			$vars = $_POST;

			$campos = array("nome","descricao");
			$valores = array("'".$vars["se_nome"]."'","'".$vars["se_descricao"]."'");
			$this->consulta->consultaInsert($campos,$valores,0);
			$this->mensagem = "Tabela criada! ";
		}
		
		if ($_GET['insert'] == 'importa_bd'){
			unset($_GET['insert']);
			$vars = $_POST;
			
			$nome_tabela = "";
			$lista_tabelas_bd = $this->buscaListaTabelaBD();
			for ($i = 0; $i < count($lista_tabelas_bd); $i++){
				if ($lista_tabelas_bd[$i]["codigo"] == $vars["se_tabela_bd"]){
					$nome_tabela = $lista_tabelas_bd[$i]["nome"];
				}
			}
			
			if($nome_tabela){
				//Inclui tabela em dicionário
				$campos = array("nome","descricao");
				$valores = array("'".$nome_tabela."'","'".$vars["se_descricao"]."'");
				$this->consulta->consultaInsert($campos,$valores,0);
				if ($dados = $this->carregaDados("id_tabela","calu_tabela"," (nome = '".$nome_tabela."') ")){
					if ($dados[0][0]) {
						$tabela = $dados[0][0];	
					}       
				}
				if (!$tabela){
					$this->mensagem = "Erro ao cadastrar tabela! ";
					return 0;
				}
				//$_GET["query"] = 1;
				//Configurar inclusão de campos
				$lista_campos_tabela_bd = $this->buscaListaCampoTabelaBD($vars["se_tabela_bd"]);
				//$funcoes->dump($lista_campos_tabela_bd);
            	$campos = "chave_primaria,descricao,nome,not_null,tabela,tamanho,tipo";
            	for($i = 0; $i < count($lista_campos_tabela_bd) ;$i++) {
            		$valores = (!$i?1:0).
            				   ",'".$lista_campos_tabela_bd[$i]["nome"].
            		           "','".$lista_campos_tabela_bd[$i]["nome"]."',0,".$tabela.
            				   ",".$lista_campos_tabela_bd[$i]["tamanho"].
            				   ",".$lista_campos_tabela_bd[$i]["tipo"];
					$this->carregaInsert($campos,"calu_tabela_campo",$valores,0); 
            	}                       
				$this->mensagem = "Tabela importada! ";
			}
			else
			{
				$this->mensagem = "Erro ao importar tabela! ";
			}	
		}
	}
	
	/**
     * @desc Realiza customização de Insert de Lista  de consulta
    */
	function insert_lista_consulta(){
		if ($_GET['insert'] == 'nova'){
			unset($_GET['insert']);
			$vars = $_POST;

			$campos = array("nome","clausula_group","clausula_order");
			$valores = array("'".$vars["se_nome"]."'","'".$vars["se_clausula_group"]."'","'".$vars["se_clausula_order"]."'");
			$this->consulta->consultaInsert($campos,$valores,0);
			$this->mensagem = "Consulta criada! ";
		}
	}
	
	/**
     * @desc Realiza customização de Insert de Lista from de consulta
    */
	function insert_lista_from_consulta(){
		if ($_GET['insert'] == 'novo'){
			unset($_GET['insert']);
			$vars = $_POST;

			$campos = array("apelido","clausula_join","ordem","tabela","consulta");
			$valores = array("'".$vars["se_apelido"]."'","'".$vars["se_clausula_join"]."'",$vars["se_ordem"],$vars["se_tabela"],$vars["chave"]);
			$this->consulta->consultaInsert($campos,$valores,0);
			$this->mensagem = "From de Consulta criado! ";
		}
	}
	
	/**
     * @desc Realiza customização de Insert de Lista where de consulta
    */
	function insert_lista_where_consulta(){
		if ($_GET['insert'] == 'novo'){
			unset($_GET['insert']);
			$vars = $_POST;
			$campos = array("consulta","ordem","tipo","valor");
			$valores = array($vars["chave"],$vars["se_ordem"],$vars["se_tipo"],"'".$vars["se_valor"]."'");
			$this->consulta->consultaInsert($campos,$valores,0);
			//Criar Campo de Where
			if ($vars["se_id_tabela_campo"]){
				//Verifica se campo existe
				$codigo_campo = 0;
				if ($dados = $this->carregaDados("id_tabela_campo","calu_tabela_campo"," (id_tabela_campo = ".$vars["se_id_tabela_campo"].") ")){
					if ($dados[0][0]) {
						$codigo_campo = $dados[0][0];
					}
				}
				if (!$codigo_campo){
					$this->mensagem = "Campo indicado não existe! ";
					return 0;
				}
				
				//Busca clausula where inserida
				if ($dados = $this->carregaDados("max(id_consulta_where)","calu_consulta_where"," (valor = '".$vars["se_valor"]."') and (consulta = ".$vars["chave"].") ")){
					if ($dados[0][0]) {
						$clausula_where = $dados[0][0];	
					}
				}
				$campos = "clausula_where,campo,ancora,consulta";
            	$valores = $clausula_where.",".$vars["se_id_tabela_campo"].",'campo0',".$vars["chave"];
				$this->carregaInsert($campos,"calu_where_campo",$valores,0); 
			}
			$this->mensagem = "Where de Consulta criado! ";
		}
	}
	
	/**
     * @desc Realiza customização de Update de Lista de Campos de tabela
    */
	function update_lista_campo_tabela(){
		global $funcoes;
		
		//Criar campos consulta
		if ($_GET["update"] == 2){

			unset($_GET["update"]);
			$_GET["chave"] = $_POST["chave"];
			
			$vars = $_POST;
		
			//Descobrir origem
			$tabela_origem = $_POST["chave"];
			$consulta = $_POST["se_consulta"];
			if (!$tabela_origem || !$consulta){
				if (!$tabela_origem){
					$this->mensagem = "Tabela não identificada! ";
				}
				if (!$consulta){
					$this->mensagem = "Consulta não informada! ";
				}
				return 0;
			}
			$origem = 0;
			if ($dados = $this->carregaDados("id_consulta_from","calu_consulta_from"," (tabela = ".$tabela_origem.") and (consulta = ".$consulta.") ")){
				if ($dados[0][0]) {
					$origem = $dados[0][0];	
				}       
			}
			if (!$origem){
				$this->mensagem = "Tabela não identificada em consulta! ";
				return 0;
			}

			//Configurar inclusão de campos
            $campos = "campo,origem,consulta,ordem";
            $cad_ordem = 10;
            foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"id_tabela_campo_") && ($key != "id_tabela_campo_todos") )
                {
					$codigo = substr($key,16);
					$valores = $codigo.",".$origem.",".$consulta.",".$cad_ordem;
					$this->carregaInsert($campos,"calu_consulta_campo",$valores,0); 
					$cad_ordem += 10;
                }
            }                       
            if ($cad_ordem == 10){
				$this->mensagem = "Selecione os campos que deseja incluir na consulta! ";
			}
			else
			{
				$this->mensagem = "Campos de consulta criados! ";
			}	
			unset($_GET["update"]);
		}       
        
		//Criar campos tela         
		if ($_GET["update"] == 3){

			unset($_GET["update"]);
			$_GET["chave"] = $_POST["chave"];
			
			$vars = $_POST;
		
			//Descobrir origem
			$tela = $_POST["se_tela"];
			$grupo = $_POST["se_grupo"];
			if (!$tela  || !$grupo){
				if (!$tela){
					$this->mensagem = "Tela não informada! ";
				}
				if (!$grupo){
					$this->mensagem = "Grupo não informado! ";
				}
				return 0;
			}
			

			//Configurar inclusão de campos
            $campos_tela = "tela,comportamento,nome,rotulo,classe,tamanho,maxlength,ordem,grupo,tipos";
			$campos_ligacao = "campo_tabela,campo_tela,ordem,tela";
            $cad_ordem = 11;
            foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"id_tabela_campo_") && ($key != "id_tabela_campo_todos") )
                {
					$codigo = substr($key,16);
					//Define nome e rotulo de campo
					$nome = "nome";
					$rotulo = "rotulo";
					$tamanho = 1;
					if ($dados = $this->carregaDados("nome,descricao,tamanho","calu_tabela_campo"," (id_tabela_campo = ".$codigo.") ")){
						if ($dados[0][0]) {
							$nome = $dados[0][0];	
						}
						if ($dados[0][1]) {
							$rotulo = $dados[0][1];	
						}       
						if ($dados[0][2]) {
							$tamanho = $dados[0][2];	
						}       
					}
					
					if (($grupo == "filtro") || ($grupo > 0)){
						$nome = "se_".$nome;
					}
					
					$valores_tela = $tela.",11,'".$nome."','".$rotulo."','result_cliente',".$tamanho.",".$tamanho.",".$cad_ordem.",'".$grupo."',',1,'";
					$this->carregaInsert($campos_tela,"calu_tela_campo",$valores_tela,0); 
				
					if (($grupo == "tabela") || ($grupo == "cadastro") || 
					    ($grupo == "tabelasub") || ($grupo == "tabelasuper") ||
						($grupo == "totalsuper") || ($grupo == "verificasub") ){
						//Define ligação
						$campo_tela = 0;
						if ($dados = $this->carregaDados("max(id_tela_campo)","calu_tela_campo"," (nome = '".$nome."') ")){
							if ($dados[0][0]) {
								$campo_tela = $dados[0][0];	
							}
						}
						if ($campo_tela){	
							$valores_ligacao = $codigo.",".$campo_tela.",10,".$tela;
							$this->carregaInsert($campos_ligacao,"calu_campo_ligacao",$valores_ligacao,0); 
						}	
					}	
					
					$cad_ordem += 10;
                }
            }                       
            if ($cad_ordem == 11){
				$this->mensagem = "Selecione os campos que deseja incluir na tela! ";
			}
			else
			{
				$this->mensagem = "Campos de tela e ligação criados! ";
			}	
			unset($_GET["update"]);
		} 
		
		//Cadastro/Edição de campo tabela
		if ($_GET["update"] == 5){
			if ($_POST['nome'] && $_POST['tipo'] && $_POST['tamanho'] && $_POST['descricao'] && $_POST['chave']){
				$campos = array("tabela","nome","tipo","tamanho","descricao","chave_primaria","not_null");
				$chave_primaria = ($_POST['chave_primaria']?1:0);
				$not_null = ($_POST['not_null']?1:0);
				$valores = array($_POST['chave'],"'".$_POST['nome']."'",$_POST['tipo'],$_POST['tamanho'],"'".$_POST['descricao']."'",$chave_primaria,$not_null);
				if ($_POST['id_tabela_campo']){
					$camposFiltros = array();
					$camposValores = array();
					$codigo = $_POST['id_tabela_campo'];
					$camposFiltros[] = "id_tabela_campo";
					$camposValores[] = $codigo;
					$logico = " or ";
					$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
					$this->mensagem = "Campo de tabela atualizado! ";
				}
				else
				{
					$this->consulta->consultaInsert($campos,$valores,0);
					$this->mensagem = "Campo de tabela criado! ";
				}	
			}
			else
			{
				$this->mensagem = "Campo não criado/atualizado, dados incompletos! ";
			}
			unset($_GET["update"]);
		}

		return 1;
    }
    
    /**
     * @desc Realiza customização de Update de Lista de consulta
    */
    function update_lista_consulta(){
    	//Cadastro/Edição de consulta
		if ($_GET["update"] == "atualiza_consulta"){
			if ($_POST['nome'] && $_POST['clausula_order']){
				$campos = array("nome","clausula_order","clausula_group");
				$valores = array("'".$_POST['nome']."'","'".$_POST['clausula_order']."'",($_POST['clausula_group']?"'".$_POST['clausula_group']."'":"NULL"));
				if ($_POST['id_consulta']){
					$camposFiltros = array();
					$camposValores = array();
					$codigo = $_POST['id_consulta'];
					$camposFiltros[] = "id_consulta";
					$camposValores[] = $codigo;
					$logico = " or ";
					$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
					$this->mensagem = "Consulta atualizada! ";
				}
				else
				{
					$this->consulta->consultaInsert($campos,$valores,0);
					$this->mensagem = "Consulta criada! ";
				}	
			}
			else
			{
				$this->mensagem = "Consulta não criada/atualizada, dados incompletos! ";
			}
			unset($_GET["update"]);
		}

		return 1;
    }
    
    /**
     * @desc Realiza customização de Update de Lista de Froms de consulta
    */
    function update_lista_from_consulta(){
    	//Cadastro/Edição de from de consulta
		if ($_GET["update"] == "atualiza_from"){
			if ($_POST['cad_apelido'] && $_POST['cad_tabela'] && $_POST['cad_clausula_join'] && $_POST['cad_ordem'] && $_POST['chave']){
				$campos = array("apelido","tabela","clausula_join","ordem","consulta");
				$valores = array("'".$_POST['cad_apelido']."'",$_POST['cad_tabela'],"'".$_POST['cad_clausula_join']."'",$_POST['cad_ordem'],$_POST['chave']);
				if ($_POST['id_consulta_from']){
					$camposFiltros = array();
					$camposValores = array();
					$codigo = $_POST['id_consulta_from'];
					$camposFiltros[] = "id_consulta_from";
					$camposValores[] = $codigo;
					$logico = " or ";
					$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
					$this->mensagem = "From de Consulta atualizado! ";
				}
				else
				{
					$this->consulta->consultaInsert($campos,$valores,0);
					$this->mensagem = "From de Consulta criado! ";
				}	
			}
			else
			{
				$this->mensagem = "From não criado/atualizado, dados incompletos! ";
			}
			unset($_GET["update"]);
		}

		return 1;
    }
    
    /**
     * @desc Realiza customização de Update de Lista de Wheres de consulta
    */
    function update_lista_where_consulta(){
    	//Cadastro/Edição de where de consulta
		if ($_GET["update"] == "atualiza_where"){
			unset($_GET['update']);
			$vars = $_POST;
			
			if ($vars["chave"] && $vars["cad_ordem"] && $vars["cad_tipo"]){
				$campos = array("consulta","ordem","tipo","valor");
				$valores = array($vars["chave"],$vars["cad_ordem"],$vars["cad_tipo"],"'".$vars["cad_valor"]."'");
				if ($_POST['id_consulta_where']){
					$camposFiltros = array();
					$camposValores = array();
					$codigo = $_POST['id_consulta_where'];
					$camposFiltros[] = "id_consulta_where";
					$camposValores[] = $codigo;
					$logico = " or ";
					$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0);
					
					//Apaga campo anterior
					$this->carregaDelete("calu_where_campo", " clausula_where = '".$codigo."' ",0);
					
					//Atualiza campo
					if ($vars["cad_campo"]){
						//Verifica se campo existe
						$codigo_campo = 0;
						if ($dados = $this->carregaDados("id_tabela_campo","calu_tabela_campo"," (id_tabela_campo = ".$vars["cad_campo"].") ")){
							if ($dados[0][0]) {
								$codigo_campo = $dados[0][0];
							}
						}
						if (!$codigo_campo){
							$this->mensagem = "Campo indicado não existe! ";
							return 0;
						}
				
						$clausula_where = $codigo;	
						$campos = "clausula_where,campo,ancora,consulta";
            			$valores = $clausula_where.",".$vars["cad_campo"].",'campo0',".$vars["chave"];
						$this->carregaInsert($campos,"calu_where_campo",$valores,0); 
					}
					
					$this->mensagem = "Where de Consulta atualizado! ";
				}
				else
				{
					$this->consulta->consultaInsert($campos,$valores,0);
					$this->mensagem = "Where de Consulta criado! ";
				}	
			}
			else
			{
				$this->mensagem = "Where não criado/atualizado, dados incompletos! ";
			}
			unset($_GET["update"]);
		}

		return 1;
    }
    
    /**
     * @desc Realiza customização de Delete de Lista  de tabela
    */
	function delete_lista_tabela(){
		if ($_GET['delete'] == 'apaga_tabela'){
			unset($_GET['delete']);
			$vars = $_GET;

			$camposFiltros = array("id_tabela");
			$valoresFiltros = array("'".$vars["tabela"]."'");
			$this->consulta->consultaDelete($camposFiltros,$valoresFiltros);
			$this->mensagem = "Tabela apagada! ";
		}
	}
    
    /**
     * @desc Realiza customização de Delete de Lista de campo de tabela
    */
	function delete_lista_campo_tabela(){
		if ($_GET['delete'] == 'apaga_campo'){
			unset($_GET['delete']);
			$vars = $_GET;
			$camposFiltros = array("id_tabela_campo");
			$valoresFiltros = array("'".$vars["se_campo"]."'");
			$this->consulta->consultaDelete($camposFiltros,$valoresFiltros);
			$this->mensagem = "Campo apagado! ";
			unset($_GET['se_campo']);
		}
	}
	
	/**
     * @desc Realiza customização de Delete de Lista de where de consulta
    */
	function delete_lista_consulta(){
		if ($_GET['delete'] == 'apaga_consulta'){
			unset($_GET['delete']);
			$vars = $_GET;
			$camposFiltros = array("id_consulta");
			$valoresFiltros = array("'".$vars["se_campo"]."'");
			$this->consulta->consultaDelete($camposFiltros,$valoresFiltros);
			$this->mensagem = "Consulta apagada! ";
			unset($_GET['se_campo']);
		}
	}
	
    /**
     * @desc Realiza customização de Delete de Lista de from de consulta
    */
	function delete_lista_from_consulta(){
		if ($_GET['delete'] == 'apaga_from'){
			unset($_GET['delete']);
			$vars = $_GET;
			$camposFiltros = array("id_consulta_from");
			$valoresFiltros = array("'".$vars["se_campo"]."'");
			$this->consulta->consultaDelete($camposFiltros,$valoresFiltros);
			$this->mensagem = "From apagado! ";
			unset($_GET['se_campo']);
		}
	}

    /**
     * @desc Realiza customização de Delete de Lista de where de consulta
    */
	function delete_lista_where_consulta(){
		global $funcoes;

		if ($_GET['delete'] == 'apaga_where'){
			unset($_GET['delete']);
			$vars = $_GET;
			$camposFiltros = array("id_consulta_where");
			$valoresFiltros = array("'".$vars["se_campo"]."'");
			$this->consulta->consultaDelete($camposFiltros,$valoresFiltros);
			$this->mensagem = "Where apagado! ";
			unset($_GET['se_campo']);
		}
	}
	
	/**
	 * @desc Roda SQL livre
	*/
	function insert_sql_livre(){
		global $funcoes;
		$vars = $_POST;
	    if (($_GET["insert"] == 1) && $vars["sql"])
	    {
			$sql = stripslashes($vars["sql"]);
			$_POST["sql"] = $sql;
			if ($this->banco->enviaSQL($sql)){
				$dados = array();
				while ($dados = $this->banco->linhaSelect()){
					$result[] = $dados;
				}
				if (count($result)){
					unset($_GET["insert"]);
					return $result;
				}
			}
			else
			{
				$funcoes->dump($this->banco->erros);
			}
			
			unset($_GET["insert"]);
	    }       
        return 1;
	}
	
	/**
	 * @desc Verifica validade de campo em tabela (retorna valor antigo)
	 * @param integer $id_campo identificação de campo tabela
	 * @param string $valor de valor de campo 
	 * @param integer $chave valor de chave de tabela
	 * @return mensagem de erro de verificação
	*/
	function verifica_validade_campo($id_tabela_campo, $valor, $chave, $name = ""){
		$ret = array();
		$dados_campo = $this->buscaDadosCampoTabela($id_tabela_campo);
		$dados_tabela = $this->buscaDadosTabela($dados_campo[0]['tabela']);
		$this->tabela = new tabela($dados_tabela[0]["nome"]);
		$dados = $this->tabela->carregar($this->tabela->chave["nome"],$chave);
		$ret["valor_antigo"] = $dados[0][$dados_campo[0]["nome"]];
		$ret["linha_antiga"] = $dados[0];
		$ret["mensagem"] = '';
		return $ret;
	}
	
	/**
	 * @desc Gravar campo em tabela
	 * @param integer $id_campo identificação de campo tabela
	 * @param string $valor de valor de campo 
	 * @param integer $chave valor de chave de tabela
	*/
	function gravar_campo($id_tabela_campo, $valor, $chave){
		//Descobre nome de tabela de id_campo
		$dados_campo = $this->buscaDadosCampoTabela($id_tabela_campo);
		$dados_tabela = $this->buscaDadosTabela($dados_campo[0]['tabela']);
		$this->tabela = new tabela($dados_tabela[0]["nome"]);
		return $this->tabela->update(array($dados_campo[0]['nome']), array("'".$valor."'"), $chave);
	}	
}