<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Apropriacao)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_apropriacao extends negocio_custom {
	
	
	/**
	 * @desc Realiza customização de Insert de Apropriação
	*/
    function insert_lista_apropriacao(){
		global $funcoes;
        if ($_GET["insert"] == 1)
        {
			if ($_POST["21_valor0_dia"] && $_POST["23_valor0_dia"] && $_POST["atividade"]) {
	            if ($dados = $this->carregaDados("max(id_apropriacao)","calu_apropriacao")){
	                $codigo = $dados[0][0]+1; 
	                $campos = array("id_apropriacao","ator","data_inicio","data_fim","atividade","preposto","setor","tipo","cobranca");
	                $valores = array($codigo,$_SESSION["navegador_ator"],"'".$funcoes->formataDataBanco($_POST["21_valor0_dia"])." ".$_POST["21_valor0_hora"]."'","'".$funcoes->formataDataBanco($_POST["23_valor0_dia"])." ".$_POST["23_valor0_hora"]."'","'".$_POST["atividade"]."'","'".$_POST["se_preposto"]."'","'".$_POST["se_setor"]."'","'".$_POST["se_tipo"]."'","'".$_POST["se_cobranca"]."'");
	                $this->consulta->consultaInsert($campos,$valores);
	            }
			}
			unset($_GET["insert"]);			
        }       
        return 1;
    }
	
	/**
	 * @desc Realiza customização de Update de Lista de Apropriação
	*/
	function update_lista_apropriacao(){
	    if ($_GET["update"] == 1)
	    {
            $vars = $_POST;
            $campos = array("preposto","setor","tipo","cobranca");
            $valores = array("'".$_POST["se_preposto"]."'","'".$_POST["se_setor"]."'","'".$_POST["se_tipo"]."'","'".$_POST["se_cobranca"]."'");
            $camposFiltros = array();
            $camposValores = array();
            foreach ($vars as $key => $value) {
		        if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
		        {
	                $codigo = substr($key,7);
	                $camposFiltros[] = "id_apropriacao";
	                $camposValores[] = $codigo;
		        }
            }                       
    
            $logico = " or ";
            $this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
            $this->mensagem = "Apropriação(ões) reclassificada(s)! ";
			unset($_GET["update"]);
	    }       
        return 1;
	}
	
	/**
	 * @desc Realiza customização de Delete de Apropriação
	*/
    function delete_lista_apropriacao(){
        if ($_GET["delete"] == 1)
        {
            $vars = $_POST;
            $camposFiltros = array();
            $valoresFiltros = array();
            foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
                {
                    $camposFiltros[] = "id_apropriacao";
                    $valoresFiltros[] = substr($key,7);
                }
            }                       
            $logico = " or ";
            $this->consulta->consultaDelete($camposFiltros,$valoresFiltros,$logico);
			unset($_GET["insert"]);			
        }       
        return 1;
    }
}