<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (CSM)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_cad_dm extends negocio_custom {
	
	/**
	 * @desc Realiza customização de Insert de CSM
	*/
    function insert_csm(){
        if ($_GET["insert"] == 1)
        {
            if ($dados = $this->carregaDados("max(codigo)","csm")){
                $codigo = $dados[0][0]+1; 
                $campos = array("codigo","nome","igreja","telefone","obs","email");
                $valores = array($codigo,"'".$_POST["nome"]."'","'".$_POST["igreja"]."'","'".$_POST["telefone"]."'","'".$_POST["obs"]."'","'".$_POST["email"]."'");
                $this->consulta->consultaInsert($campos,$valores);
            }
			unset($_GET["insert"]);			
        }       
        return 1;
    }
    
	/**
	 * @desc Realiza customização de Update de Curriculo
	*/
    function update_cad_dm(){
		global $funcoes;
		if ($_GET["update"] == 'grava')
        {
			unset($_GET["update"]);			
			if ($_POST["cad_nome"] && $_POST["cad_email"]){
				$campos = array("nome", "email", "cargo", "igreja", "federacao", "sinodo", "oficina_1", "oficina_2", "data_cadastro");

				$valores = array("'".$_POST["cad_nome"]."'","'".$_POST["cad_email"]."'", "'".$_POST["cad_cargo"]."'", "'".$_POST["cad_igreja"]."'","'".$_POST["cad_federacao"]."'","'".$_POST["cad_sinodo"]."'",$_POST["cad_oficina_1"],$_POST["cad_oficina_2"],"'".date("Y-m-d H:i:s")."'");
                $this->consulta->consultaInsert($campos,$valores); 
				
				$this->mensagem = "Obrigado por realizar seu cadastro! ";
			}
			else
			{
				$this->mensagem = "Por favor preencha seus dados para se cadastrar. Nome e Email são obrigatórios! ";
				return 0;
			}
        }
        return 1;
    }    
        
	/**
	 * @desc Realiza customização de Delete de CSM
	*/
    function delete_csm(){
        if ($_GET["delete"] == 1)
        {
	        $vars = $_POST;
	        $camposFiltros = array();
	        $valoresFiltros = array();
	        foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
                {
                    $camposFiltros[] = "codigo";
                    $valoresFiltros[] = substr($key,7);
                }
	        }                       
	        $logico = " or ";
	        $this->consulta->consultaDelete($camposFiltros,$valoresFiltros,$logico);
			unset($_GET["delete"]);
        }       
        return 1;
    }       
}