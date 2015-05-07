<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Vaga)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_vaga extends negocio_custom {
	
	/**
	 * @desc Realiza customização de Insert de Vaga
	*/
    function insert_lista_vaga(){
		if ($_GET["insert"] == 'nova')
        {
			unset($_GET["insert"]);
			if ($_POST["se_nome"] && $_POST["se_descricao"]) {
                $campos = array("nome","descricao","status");
	            $valores = array("'".$_POST["se_nome"]."'","'".$_POST["se_descricao"]."'",0);
                $this->consulta->consultaInsert($campos,$valores);
				$this->mensagem = "Vaga criada!";
				return 1;
			}
			else
			{
				$this->mensagem = "Preencha os dados da vaga!";
			}
        }
		return 0;
	}
	
	/**
	 * @desc Realiza customização de update de Vaga
	*/
    function update_lista_vaga(){
		$vars = $_POST;

		if ($_GET["update"] == 'ativar')
        {
			unset($_GET["update"]);	
			$camposFiltros = array();
            $valoresFiltros = array();
            foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
                {
                    $camposFiltros[] = "codigo";
                    $valoresFiltros[] = substr($key,7);

					$camposFiltros = array();
					$camposValores = array();
					$codigo = substr($key,7);
					$campos = array("status");
					$valores = array(0);
					
					$camposFiltros[] = "codigo";
					$camposValores[] = $codigo;
					$logico = " and ";
					$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
					$this->mensagem = "Vaga(s) ativadas!";
                }
            }                  
		}

		if ($_GET["update"] == 'apagar')
        {
			unset($_GET["update"]);	
			$camposFiltros = array();
            $valoresFiltros = array();
            foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
                {
                    $camposFiltros[] = "codigo";
                    $valoresFiltros[] = substr($key,7);

					$camposFiltros = array();
					$camposValores = array();
					$codigo = substr($key,7);
					$campos = array("oculto");
					$valores = array(1);
					
					$camposFiltros[] = "codigo";
					$camposValores[] = $codigo;
					$logico = " and ";
					$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
					$this->mensagem = "Vaga(s) apagadas!";
                }
            }                  
		}
	}
	
	/**
	 * @desc Realiza customização de delete de Vaga
	*/
    function delete_lista_vaga(){
		$vars = $_POST;

		if ($_GET["delete"] == 'inativar')
        {
			unset($_GET["delete"]);	
			$camposFiltros = array();
            $valoresFiltros = array();
            foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
                {
                    $camposFiltros[] = "codigo";
                    $valoresFiltros[] = substr($key,7);

					$camposFiltros = array();
					$camposValores = array();
					$codigo = substr($key,7);
					$campos = array("status");
					$valores = array(1);
					
					$camposFiltros[] = "codigo";
					$camposValores[] = $codigo;
					$logico = " and ";
					$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
					$this->mensagem = "Vaga(s) inativadas!";
                }
            }                  
		}
	}
	
	/**
     * @desc Desenha botões para Descrição de Vaga
     * @return string de Observação
    */
    function botoesVagaDescricao($descricao, $codigo = 1){
		global $visual;
		$botoes = "";
	    $botoes .= $visual->exibeBoxTextHtml("dsc_".$codigo,"Descrição de Vaga",$descricao,"edit_box","Descrição","Descrição Vaga");
		
		return $botoes;
	}
}