<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Neg�cio (Agenda Di�ria)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_agenda_diaria extends negocio_custom {
	
	/**
     * @desc Realiza customiza��o de Update de Agenda Di�ria
    */
    function update_agenda_diaria(){
		global $funcoes;
        //Bot�o Prosapec��o de Clientes 
        if ($_GET["update"] == 4)
        {
            $this->mensagem = "Prospe��o gravadas! ";
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
            $this->mensagem = "Status alterado(s)! ";
			unset($_GET["update"]);
        }       
        
        if ($_GET["update"] == 3)
        {
            $vars = $_POST;
            $campos = array("vendedor","data_altera","prospeccao");
            $valores = array('NULL','getdate()',0);
            $camposFiltros = array();
            $camposValores = array();
            foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
                {
                    $codigo = substr($key,7);
                    $camposFiltros[] = "codigo";
                    $camposValores[] = $codigo;
                }
            }                       
    
            $logico = " or ";
            $this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
            $this->mensagem = "Desist�ncia(s) confirmada(s)! ";
			unset($_GET["update"]);
        }       
        return 1;
    }
	
	/**
	 * @desc Retorna bot�es de a�oes de Agenda Di�ria
	*/
	function botoesAcoesAgendaDiaria($cliente){
		$botoes = "";
		//Campos
	    $botoes .= "&nbsp<a href=\"?local=lista_contrato&origem=agenda_diaria&se_codigo=".$cliente."\"><img src=\"./images/money.png\"   title='Dados de Contrato Fechado' /></a>";  
		return $botoes;
	}
}