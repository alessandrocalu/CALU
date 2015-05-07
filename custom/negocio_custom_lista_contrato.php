<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Contrato)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_contrato extends negocio_custom {
	
	/**
	 * @desc Realiza customização de Update de Lista de Contratos
	*/
	function update_lista_contrato(){
		global $funcoes;
		if ($_GET["update"] == 1)
	    {
			$vars = $_POST;
			$funcoes->dump($vars);
			if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") && false )
            {
				$camposFiltros = array();
                $camposValores = array();
                $campos = array("data_altera","visita");
                $codigo = substr($key,7);
                if ($_POST["visita_".$codigo."_data"] && $_POST["visita_".$codigo."_hora"]){
                        $visita = "'".$funcoes->formataDataBanco($_POST["visita_".$codigo."_data"])." ".$_POST["visita_".$codigo."_hora"]."'";
                }
                $valores = array('getdate()',$visita);
                $camposFiltros[] = "codigo";
                $camposValores[] = $codigo;
                $logico = " or ";
                $this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
            }
	        $this->mensagem = "Dados de Cliente atualizados! ";
		}

		unset($_GET["update"]);
	}
	
	/**
     * @desc Mostrar local
     * @param  $local de Tela que deve ser mostrada
     * @return string de link de local
    */
    function getDadosTela()
    {
    	$this->mensagem = "Preencha pelo menos filtro de Código ou Data de Contrato! ";
    	if ((($_GET["se_codigo"] > 0) || ($_POST["se_codigo"] > 0)) || 
    	    ($_POST["se_data_contrato0"] && $_POST["se_data_contrato1"] && ($_POST["se_data_contrato0"] > '0000-00-00') && ($_POST["se_data_contrato1"] > '0000-00-00')))
    	{
    		$this->mensagem = "";
    		$this->consulta->setFiltroFixo("");
	   	}
	   	else
	   	{
	   		//Consulta Pesada
	   		$this->mensagem = "Preencha pelo menos filtro de Código ou Data de Contrato! ";
    	    $this->consulta->setFiltroFixo(" 0 = 1 ");
	   	}
    	return parent::getDadosTela();
    }

	/**
	 * @desc Gravar campo em tabela
	 * @param integer $id_campo identificação de campo tabela
	 * @param string $valor de valor de campo 
	 * @param integer $chave valor de chave de tabela
	*/
	function gravar_campo($id_tabela_campo, $valor, $chave){
		global $funcoes;
		
		//Configura campos Valor
		switch ($id_tabela_campo) {
    		case 259: 
    		case 260:
    		case 404: 
    			$valor = $funcoes->formataNumeroBanco($valor);
         		break;
		}
		
		//Configura campos Data
		switch ($id_tabela_campo) {
    		case 261: 
    		case 262:
    		case 263: 
    			$valor = $funcoes->formataDataBanco($valor);
         		break;
		}
		
		//Para campos da tabela contrato, muda chave para contrato
		switch ($id_tabela_campo) {
    		//Campo Grupo de Vendedor
    		case 258: 
    		case 259: 
    		case 260:
    		case 261:
    		case 262: 
    		case 263:
    		case 264:
    		case 265:
    		case 266:
    		case 404: 
    		case 405:
				if (!$this->contrato){
					$this->contrato = new tabela("CONTRATO");
				}
				$dados_contrato = $this->listaContratoCliente($chave);
				if (is_array($dados_contrato) && count($dados_contrato))
				{
					$chave = $dados_contrato[0]["codigo"];
				}
				else
				{
					$campos = array("cliente","data_contrato","valor_mensalidade","valor_equipamento", "valor_instalacao", "meses");
					$valores = array($codigo,"'".date("Y-m-d")."'", 0, 0, 0, 24);
					$this->contrato->insert($campos, $valores);
					$dados_contrato = $this->listaContratoCliente($chave);
					if (is_array($dados_contrato) && count($dados_contrato))
					{
						$chave = $dados_contrato[0]["codigo"];
					}
				}
        		break;
		}
		
		$ret = parent::gravar_campo($id_tabela_campo, $valor, $chave);
		return $ret;
	}
}