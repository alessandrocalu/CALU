<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Neg�cio (Progress�o Clientes)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_progressao_clientes extends negocio_custom {

	/**
	 * @desc Realiza customiza��o de Select de Progress�o de Clientes
	*/
    function select_progressao_clientes(){
        if ($_GET["select"] == 1){
            //Customiza pesquisa de acordo com agrupamento escolhido
            $clausula_order = $this->consulta->getClausulaOrder();
            $clausula_group = $this->consulta->getClausulaGroup();
            $campos = $this->consulta->getCampos();
            $origens = $this->consulta->getFroms();
            
            if ($_GET["agrupamento"] == 181){
                $_GET["agrupamento"] = 169;
            }

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
                $dados[0]["id_consulta_campo"] = $campos[1]["id_consulta_campo"];
                $dados[0]["consulta"] = $campos[1]["consulta"];
				$dados[0]["ordem"] = $campos[1]["ordem"];
                $dados[0]["expressao"] = NULL;
                $campos[2] = $dados[0];
                
                //Altera dados de consulta
                $this->consulta->setCampos($campos);
                $clausula_order = "hp.mesano,DATEPART(ww, hp.data_altera),".$campos[2]["apelido"].".".$campos[2]["nome"];
                $clausula_group = "hp.mesano,DATEPART(ww, hp.data_altera),".$campos[2]["apelido"].".".$campos[2]["nome"];
                $this->consulta->setClausulaOrder($clausula_order);
                $this->consulta->setClausulaGroup($clausula_group);
                
                //Customiza campos de tela
                $campos_tela = $this->tela->getCampos();
                $campos_tela[1]['ligacoes'][0]['campo_tabela'] = $_GET["agrupamento"]*1;
                $campos_tela[1]['ligacoes'][0]['nome'] = $dados[0]["nome"];
                $campos_tela[1]['ligacoes'][0]['tabela'] = $dados[0]["tabela"];
                $this->tela->setCampos($campos_tela);
            }                               
        }
    }
}