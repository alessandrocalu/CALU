<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Ativas)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_ativas extends negocio_custom {

	/**
     * @desc Realiza customização de Update de Lista de Ativas
    */
	function update_lista_ativas(){
		global $funcoes;
		//Botão Prospecção de fs 
		if ($_GET["update"] == 4){
			$vars = $_POST;
			foreach ($vars as $key => $value) {
				if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
				{
					$camposFiltros = array();
					$camposValores = array();
					$codigo = substr($key,7);
					$campos = array("cod_ult_opera","vendedor","data_altera","tipo","prospeccao","operador_1","operador_2");
					$valores = array($_SESSION["navegador_operador"],$_SESSION["navegador_ator"],'getdate()',1,7,$_SESSION["navegador_operador"],$_SESSION["navegador_operador_2"]);
					
					if ($dados = $this->carregaDados("count(codigo)","TMKT"," (vendedor is not null) and ((vendido = 1) or (data_altera > (getdate()-45))) and (codigo = ".$codigo.") ")){
						if ($dados[0][0]) {
							continue;
						}       
					}
					$camposFiltros[] = "codigo";
					$camposValores[] = $codigo;
					$logico = " or ";
					$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
				}
			}                       
			$this->mensagem = "Prospeção gravadas! ";
			unset($_GET["update"]);
		}       
                
        if ($_GET["update"] == 1)
        {
            $vars = $_POST;
            foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
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
            }
                            
            $this->mensagem = "Cliente(s) agendado(s)! ";
			unset($_GET["update"]);
        }       
                
        if ($_GET["update"] == 2)
        {
            if (($_POST['prospeccao'] > 6)  || ($_POST['prospeccao'] == -2) ){
                $prospeccao = $_POST['prospeccao']; 
            }
            else
            {
                $prospeccao = 7;
            }
            $vars = $_POST;
            $campos = array("vendedor","data_altera","prospeccao");
            $valores = array($_SESSION["navegador_ator"],'getdate()',$prospeccao);
            $camposFiltros = array();
            $camposValores = array();
            foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
                {
					//Gera um update para cada registro
					$camposFiltros = array();
					$camposValores = array();
					
                    $codigo = substr($key,7);
                    $camposFiltros[] = "codigo";
                    $camposValores[] = $codigo;
					
					if ($this->verificaPermissaoMudarStatus($codigo,$_SESSION["navegador_ator"],$prospeccao)){
			            $logico = " and ";
						$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
					}
                }
            }                       
    
            $this->mensagem = "Status alterado(s)! ";
			unset($_GET["update"]);
        }       
        
        if ($_GET["update"] == 3)
        {
            $vars = $_POST;
            $campos = array("vendedor","data_altera","prospeccao","operador_1","operador_2");
            $valores = array('NULL','getdate()',0,0,0);
            $camposFiltros = array();
            $camposValores = array();
            foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
                {
					//Gera um update para cada registro
					$camposFiltros = array();
					$camposValores = array();
					
                    $codigo = substr($key,7);
                    $camposFiltros[] = "codigo";
                    $camposValores[] = $codigo;
					
					$logico = " and ";
					$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
                }
            }                       
            
            $this->mensagem = "Desistência(s) confirmada(s)! ";
			unset($_GET["update"]);
        }       
        return 1;
    }
	
	/**
     * @desc Realiza customização de Update de Lista de Ativas
    */
	function insert_lista_ativas(){
		//Não faz nada
	}

    /**
	 * @desc Retorna botões de açoes de Lista Ativas
	*/
	function botoesAcoesListaAtivas($cliente){
		$botoes = "";
		//Campos
		$botoes .= "&nbsp<a href=\"?local=lista_contrato&origem=lista_ativas&se_codigo=".$cliente."\"><img src=\"./images/money.png\"   title='Dados de Contrato Fechado' /></a>";  
		return $botoes;
	}
}