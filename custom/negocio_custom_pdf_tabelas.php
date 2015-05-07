<?
/**
 * @desc Sistema: CALU an link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Tabelas gerais - PDF)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/
class negocio_custom_pdf_tabelas extends negocio_custom {
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
		$id_dominio = $_POST["id_dominio"];
		if ($dados_tipo_dominio = $this->buscaDadosTipoDominio($id_dominio)){
			$this->dominioPai = $dados_tipo_dominio[0]["pai_dominio"];
			$this->dominioSuper = $dados_tipo_dominio[0]["super_dominio"];
			if ($this->dominioPai){
				if ($dados_tipo_dominio = $this->buscaDadosTipoDominio($this->dominioPai)){
					$this->nomeDominioPai = $dados_tipo_dominio[0]["nome"];
				}
			}
			if ($this->dominioSuper){
				if ($dados_tipo_dominio = $this->buscaDadosTipoDominio($this->dominioSuper)){
					$this->nomeDominioSuper = $dados_tipo_dominio[0]["nome"];
				}
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
		}
        return $dados_calu_tela_campo;      
	}
}
