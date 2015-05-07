<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Pizza Clientes)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_pizza_clientes extends negocio_custom {

	/**
	 * @desc Realiza customização de Select de Pizza de Clientes
	*/
    function select_pizza_clientes(){
        if ($_GET["select"] == 1){
            //Customiza pesquisa de acordo com agrupamento escolhido
            $clausula_order = $this->consulta->getClausulaOrder();
            $clausula_group = $this->consulta->getClausulaGroup();
            $campos = $this->consulta->getCampos();
            $origens = $this->consulta->getFroms();

            $id_tabela_campo = $_GET["agrupamento"];
            if (!$id_tabela_campo) {
                $id_tabela_campo = 169;
            }
            //Busca dados de campo para agrupamento 
            $dados = $this->buscaDadosCampoTabela($id_tabela_campo);
            if ($dados){
                $dados_tipo_campo = $this->buscaDadosTipoCampoTabela($dados[0]["tipo"]);
                $dados[0]["nome_tipo"] = $dados_tipo_campo[0]["tipo"]; 
                if ($_GET["agrupamento"] == '150') {
                    //Grupo de Vendedor
                    $dados[0]["origem"] = $origens[1]["id_consulta_from"];
                    $dados[0]["apelido"] = $origens[1]["apelido"];
                }
                else
                {
                    //Outros agrupamentos
                    $dados[0]["origem"] = $origens[0]["id_consulta_from"];
                    $dados[0]["apelido"] = $origens[0]["apelido"];
                }
                $dados[0]["campo"] = $_GET["agrupamento"]*1;
                $dados[0]["id_consulta_campo"] = $campos[0]["id_consulta_campo"];
                $dados[0]["consulta"] = $campos[0]["consulta"];
				$dados[0]["ordem"] = $campos[0]["ordem"];
                $dados[0]["expressao"] = NULL;
                $campos[0] = $dados[0];
                
                //Altera dados de consulta
                $this->consulta->setCampos($campos);
                $clausula_order = $campos[0]["apelido"].".".$campos[0]["nome"];
                $clausula_group = $campos[0]["apelido"].".".$campos[0]["nome"];
                $this->consulta->setClausulaOrder($clausula_order);
                $this->consulta->setClausulaGroup($clausula_group);
                
                //Customiza campos de tela
                $campos_tela = $this->tela->getCampos();
                $campos_tela[0]['ligacoes'][0]['campo_tabela'] = $_GET["agrupamento"]*1;
                $campos_tela[0]['ligacoes'][0]['nome'] = $dados[0]["nome"];
                $campos_tela[0]['ligacoes'][0]['tabela'] = $dados[0]["tabela"];
                $this->tela->setCampos($campos_tela);
            }                               
        }
    }
}