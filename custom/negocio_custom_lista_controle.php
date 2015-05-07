<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Controle)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_controle extends negocio_custom {
	
	/**
	 * @desc Realiza customização de Update de Lista de Controle
	*/
	function update_lista_controle(){
		global $funcoes;
	    if ($_GET["update"] == 1)
	    {
			$vars = $_POST;
			$campos = array();
			$valores = array();
			if ($_POST["cnpj"]){
				$campos[] = "cnpj";
				$valores[] = "'".$_POST["cnpj"]."'";
			}	
			if ($_POST["data_contrato"] && ($_POST["data_contrato"] <> "00/00/0000")){
				$campos[] = "data_contrato";
				$valores[] = "'".$_POST["data_contrato"]."'";
			}
			if ($_POST["data_instalacao"] && ($_POST["data_instalacao"] <> "00/00/0000")){
				$campos[] = "data_instalacao";
				$valores[] = "'".$_POST["data_instalacao"]."'";
			}
			if ($_POST["data_monitoramento"] && ($_POST["data_monitoramento"] <> "00/00/0000")){
				$campos[] = "data_monitoramento";
				$valores[] = "'".$_POST["data_monitoramento"]."'";
			}
			if ($_POST["valor_mensalidade"]){	
				$campos[] = "valor_mensalidade";
				$valores[] = $funcoes->formataNumero($_POST["valor_mensalidade"]);
			}
			if ($_POST["total_equipamento"]){	
				$campos[] = "total_equipamento";
				$valores[] = $funcoes->formataNumero($_POST["total_equipamento"]);
			}	
			if ($_POST["razaosocial"]){	
				$campos[] = "razaosocial";
				$valores[] = "'".$_POST["razaosocial"]."'";
			}
			
			//Data Altera
			$campos[] = "data_altera";
			$valores[] = " getdate() ";
			$campos[] = "data_backoffice";
			$valores[] = " getdate() ";
			$campos[] = "backoffice";
			$valores[] = $_SESSION["navegador_ator"];

            $camposFiltros = array();
            $camposValores = array();

			$camposFiltros[] = "codigo";
            $camposValores[] = $_GET["chave"];
            $logico = " and ";
            			
			$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
			
			//Ajusta filtro
			$_GET["se_campo"] = $_GET["chave"];
			$_POST["data_altera0"] = date("d/m/Y");
			$_POST["data_altera1"] = date("d/m/Y");
			unset($_GET["update"]);
			
            $this->mensagem = "Contrato registrado! ";
	    }       
        return 1;
	}
	
	/**
     * @desc Retorna botões de açoes de 
    */
    function botoesAcoesControle($cliente){
		
		if ($_SESSION["navegador_tipo_usuario"] != 5){
			//Mapa de Vendas
			$botoes .= "&nbsp<a href=\"?local=lista_contrato&origem=lista_controle&se_codigo=".$cliente."\"><img src=\"./images/money.png\"   title='Dados de Contrato Fechado' /></a>";  
		}	
		else
		{
			//Imprimir Proposta 
	        $botoes .= "&nbsp<a href=\"?local=imprime_proposta&chave=".$cliente."&select=search\" target='impressao' ><img src=\"./images/form_search.png\"   title='Imprime Proposta' /></a>";  
	        
			//Imprimir Proposta Basic
	        $botoes .= "&nbsp<a href=\"?local=imprime_proposta&chave=".$cliente."&select=basic\"  target='impressao' ><img src=\"./images/form.png\"   title='Imprime Proposta Basic'  /></a>";  
			
			//Imprimir Proposta Classic
	        $botoes .= "&nbsp<a href=\"?local=imprime_proposta&chave=".$cliente."&select=classic\" target='impressao' ><img src=\"./images/form_more.png\"   title='Imprime Proposta Classic' /></a>";  
			
			//Envia proposta por email
	        $botoes .= "&nbsp<a href=\"?local=imprime_proposta&chave=".$cliente."&select=search&email=1\" target='impressao' ><img src=\"./images/mail_search.png\"   title='Envia proposta por email' /></a>";  
	
			//Email Proposta Basic
	        $botoes .= "&nbsp<a href=\"?local=imprime_proposta&chave=".$cliente."&select=basic&email=1\"  target='impressao' ><img src=\"./images/mail.gif\"   title='Envia Proposta Basic por email'  /></a>";  
			
			//Email Proposta Classic
	        $botoes .= "&nbsp<a href=\"?local=imprime_proposta&chave=".$cliente."&select=classic&email=1\" target='impressao' ><img src=\"./images/mail_more.png\"   title='Envia Proposta Classic por email' /></a>";  
	
			//Email Proposta Premium
	        $botoes .= "&nbsp<a href=\"?local=imprime_proposta&chave=".$cliente."&select=premium&email=1\" target='impressao' ><img src=\"./images/mail_more_more.png\"   title='Envia Proposta Premium por email' /></a>";  
		}
		
        return $botoes;
    }
}