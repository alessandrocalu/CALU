<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Clientes)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_operador extends negocio_custom {
	/**
     * @desc Realiza customização de Insert de Lista de tabelas
    */
	function insert_lista_operador(){
		if ($_GET['insert'] == 'novo'){
			unset($_GET['insert']);
			$vars = $_POST;
			if ($vars["se_nome"])
			{
				$codigo = 1;
        		if ($dados = $this->carregaDados("max(codigo)","operador")){
                	$codigo = $dados[0][0]+1;
        		} 
				
				$campos = array("codigo", "nome","supervisor","inativo","consultor","senha","intervalo_agenda","operador_2");
				$valores = array($codigo, "'".$vars["se_nome"]."'",$vars["supervisor"],$vars["inativo"],"0","'123'","15",($vars["se_operador_2"]?$vars["se_operador_2"]:$codigo));
				$this->consulta->consultaInsert($campos,$valores,0);
				$this->mensagem = "Operador adicionado! ";
			}
			else
			{
				$this->mensagem = "Operador não adicionado, preencher nome! ";
			}	
		}
	}
	
	
	/**
	 * @desc Gravar campo em tabela
	 * @param integer $id_campo identificação de campo tabela
	 * @param string $valor de valor de campo 
	 * @param integer $chave valor de chave de tabela
	*/
	function gravar_campo($id_tabela_campo, $valor, $chave){
		
		$ret = parent::gravar_campo($id_tabela_campo, $valor, $chave);
	
		//Customizações de campos 
		switch ($id_tabela_campo) {

        	//Campo Inativo de Operador
        	case 324:
				if (!$this->operador){
					$this->calu_usuario = new tabela("OPERADOR");
				}
				//operador com Inativo igual a 0, reset senha de operador
				if (!$valor){
					$campos = array("senha");
					$valores = array("123");
					$this->calu_usuario->update($campos, $valores, $chave);	
				}
        		break;
		}
		
		return $ret;
	}
}