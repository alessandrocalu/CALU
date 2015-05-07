<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Grupos de Vendedor)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_vendedor_grupo extends negocio_custom {
	/**
	 * @desc Verifica validade de campo em tabela (retorna valor antigo)
	 * @param integer $id_campo identificação de campo tabela
	 * @param string $valor de valor de campo 
	 * @param integer $chave valor de chave de tabela
	 * @return mensagem de erro de verificação
	*/
	function verifica_validade_campo($id_tabela_campo, $valor, $chave, $name = "")
	{
		return $ret;
	}
	
	/**
	 * @desc Gravar campo em tabela
	 * @param integer $id_campo identificação de campo tabela
	 * @param string $valor de valor de campo 
	 * @param integer $chave valor de chave de tabela
	*/
	function gravar_campo($id_tabela_campo, $valor, $chave)
	{
		//Descobre nome de tabela de id_campo
		$dados_campo = $this->buscaDadosCampoTabela($id_tabela_campo);
		$dados_tabela = $this->buscaDadosTabela($dados_campo[0]['tabela']);
		$this->tabela = new tabela($dados_tabela[0]["nome"]);
		$chave_replace = str_replace("_", " ", $chave);
		return $this->tabela->update(array($dados_campo[0]['nome']), array("'".$valor."'"),0 , " (grupo  = '".$chave."') or (grupo  = '".$chave_replace."') ");
	}	
}