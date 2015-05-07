<?
/**
 * @desc Sistema: CALU an link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Tabelas gerais)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_tabelas extends negocio_custom {
	public $id_dominio = 0;
	public $dominioPai = 0;
	public $dominioSuper = 0;
	public $nomeDominioPai = "";
	public $nomeDominioSuper = "";
		
	/**
     * @desc Mostrar local
     * @param  $local de Tela que deve ser mostrada
     * @return string de link de local
    */
	function mostraLocal($local){
		$this->id_dominio = $_POST["id_dominio"];
		if ($dados_tipo_dominio = $this->buscaDadosTipoDominio($this->id_dominio)){
			$this->dominioPai = $dados_tipo_dominio[0]["pai_dominio"];
			$this->dominioSuper = $dados_tipo_dominio[0]["super_dominio"];
			if ($this->dominioPai){
				if ($dados_tipo_dominio = $this->buscaDadosTipoDominio($this->dominioPai)){
					$this->nomeDominioPai = $dados_tipo_dominio[0]["nome"];
				}
			}
			else
			{
				unset($_POST["se_pai_dominio"]);
			}
			if ($this->dominioSuper){
				if ($dados_tipo_dominio = $this->buscaDadosTipoDominio($this->dominioSuper)){
					$this->nomeDominioSuper = $dados_tipo_dominio[0]["nome"];
				}
			}
			else
			{
				unset($_POST["se_super_dominio"]);
			}
		}	
		return parent::mostraLocal($local);
	} 
	
    /**
     * @desc Retorna dados de tabela calu_tela_campo
     * @param $id_tela Identificador de Tela que se deseja
     * @return array de Dados de regitro de
    */
    function buscaDadosCamposTela($id_tela){
    	global $funcoes;
		$dados_calu_tela_campo = parent::buscaDadosCamposTela($id_tela);
		//Configura nome de Campos super e pai
		for ($i = 0; $i < count($dados_calu_tela_campo); $i++){
			if ($dados_calu_tela_campo[$i]["nome"] == "super_dominio"){
				$dados_calu_tela_campo[$i]["rotulo"] = $this->nomeDominioSuper; 
			}
			if ($dados_calu_tela_campo[$i]["nome"] == "pai_dominio"){
				$dados_calu_tela_campo[$i]["rotulo"] = $this->nomeDominioPai; 
			}
		    if ($dados_calu_tela_campo[$i]["nome"] == "se_super_dominio"){
		    	if ($this->dominioSuper)
		    	{
		    		$dados_calu_tela_campo[$i]["elemento"] = $this->dominioSuper; 
					$dados_calu_tela_campo[$i]["rotulo"] = $this->nomeDominioSuper;
		    	}
		    	else
		    	{
		    		$dados_calu_tela_campo[$i]["grupo"] = "invisivel";
		    	}	 
			}
			if ($dados_calu_tela_campo[$i]["nome"] == "se_pai_dominio"){
				if ($this->dominioPai){
					$dados_calu_tela_campo[$i]["elemento"] = $this->dominioPai;
					$dados_calu_tela_campo[$i]["rotulo"] = $this->nomeDominioPai; 
				}
				else
				{
					$dados_calu_tela_campo[$i]["grupo"] = "invisivel";
				}
			}
		}
        return $dados_calu_tela_campo;      
	}
	
	/**
     * @desc Realiza customização de Insert de Lista de tabelas
    */
	function insert_lista_tabelas(){
		if ($_GET['insert'] == 'novo'){
			unset($_GET['insert']);
			$vars = $_POST;
			if ($vars["id_dominio"] && $vars["se_codigo"] && $vars["se_nome"])
			{
				$campos = array("id_dominio","codigo","nome","tipo");
				$valores = array($vars["id_dominio"],$vars["se_codigo"],"'".$vars["se_nome"]."'","'".$vars["se_tipo"]."'");
				if ($this->dominioSuper)
				{
					$campos[] = "super_dominio";
					$valores[] = ($vars["se_super_dominio"]?$vars["se_super_dominio"]:0);
				}
				if ($this->dominioPai)
				{
					$campos[] = "pai_dominio";
					$valores[] = ($vars["se_pai_dominio"]?$vars["se_pai_dominio"]:0);
				}
				$this->consulta->consultaInsert($campos,$valores,0);
				$this->mensagem = "Item criado! ";
			}
			else
			{
				$this->mensagem = "Item não criado, faltam dados! ";
			}	
		}
	}
	
	/**
     * @desc Realiza customização de Insert de Lista de tabelas
    */
	function update_lista_tabelas(){
		if (($_GET['update'] == 'super_dominio') || ($_GET['update'] == 'pai_dominio')){
			$vars = $_POST;
			foreach ($vars as $key => $value) {
				if (($value == '1') && strpos("_".$key,"dominio_") && ($key != "dominio_todos") )
				{
					$camposFiltros = array();
					$camposValores = array();
					$codigo = substr($key,8);
					$campos = array($_GET['update']);
					$valores = array($vars["se_".$_GET['update']]);
					
					if ($dados = $this->carregaDados("count(codigo)","TMKT"," (vendedor is not null) and ((vendido = 1) or (data_altera > (getdate()-45))) and (codigo = ".$codigo.") ")){
						if ($dados[0][0]) {
							continue;
						}       
					}
					$camposFiltros[] = "id_dominio";
					$camposFiltros[] = "codigo";
					$camposValores[] = $this->id_dominio;
					$camposValores[] = $codigo;
					$logico = " and ";
					$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
				}
			}                       
			$this->mensagem = "Itens enviados para ".(strtolower(($_GET['update'] == 'super_dominio')?$this->nomeDominioSuper:$this->nomeDominioPai))." ! ";
			unset($_GET['update']);
		}	
	}

	
	
	/**
     * @desc Realiza customização de Delete de Lista de tabelas
    */
	function delete_lista_tabelas(){
		if ($_GET['delete'] == 'apaga'){
			unset($_GET['delete']);
			$vars = $_GET;
			$camposFiltros = array("id_dominio","codigo");
			$valoresFiltros = array($vars["id_dominio"],$vars["codigo"]);
			$this->consulta->consultaDelete($camposFiltros,$valoresFiltros);
			$this->mensagem = "Item apagado! ";
		}
	}
	
	
	/**
     * @desc Retorna dados de tabela calu_tela_acao
     * @param $id_tela Identificador de Tela que se deseja
     * @return array de Dados de regitro de
    */
    function buscaDadosAcoesTela($id_tela){
    	global $funcoes;
    	//Enviar marcados a Gerência...
		//Enviar marcados a Cidade...
		$dados = parent::buscaDadosAcoesTela($id_tela);
		$dados_calu_tela_acoes = array();
		//Configura nome de Campos super e pai
		for ($i = 0; $i < count($dados); $i++){
			if ($dados[$i]["nome"] == "super_dominio"){
				if ($this->dominioSuper)
				{
					$dados[$i]["nome"] = "Enviar marcados a ".strtolower($this->nomeDominioSuper)."...";
					$dados_calu_tela_acoes[] = $dados[$i];
				}
			}
			elseif ($dados[$i]["nome"] == "pai_dominio"){
				if ($this->dominioPai)
				{
					$dados[$i]["nome"] = "Enviar marcados a ".strtolower($this->nomeDominioPai)."...";
					$dados_calu_tela_acoes[] = $dados[$i];
				}
			}
			else
			{
				$dados_calu_tela_acoes[] = $dados[$i];
			}
		}
		return $dados_calu_tela_acoes;	
    }
}
