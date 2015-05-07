<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Troca Senha)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/01/2011 Atualizado: 21/01/2011
*/

class negocio_custom_troca_senha extends negocio_custom {
	/**
     * @desc Realiza customização de Select de Troca de Senha
    */
	function select_troca_senha(){
		$_GET["chave"] = $_SESSION["navegador_id_usuario"];
		$_GET["se_campo"] = $_SESSION["navegador_id_usuario"];
	}
	
	
	/**
     * @desc Realiza customização de Insert de Troca de Senha
    */
	function update_troca_senha(){
		if (($_GET["update"] == 'altera_senha') && $_POST["codigo"])
        {
        	//Verifica preenchmento de dados
        	if (!$_POST["senha_antiga"] || !$_POST["senha_nova"] || !$_POST["senha_confere"])
        	{
        		$this->mensagem = "Favor preencher Senha Antiga, Nova Senha! Repita Nova Senha!";
				return 0;
        	}
        	
        	//Verifica diferença de Nova Senha e Repetição
        	if ($_POST["senha_nova"] != $_POST["senha_confere"])
        	{
        		$this->mensagem = "Nova Senha difere de Repetição de Nova Senha!";
				return 0;
        	}
        	
        	//Verifica senha anterior
        	$this->banco->setSelect(" id,senha ");
			$this->banco->setFrom(" calu_usuario ");
			$this->banco->setWhere(" id = "."'".$_POST["codigo"]."' ");
			$this->banco->setOrder(" id ");
        	$this->banco->enviaSelect();
        	if ($erros = $this->banco->getErros()) {
				$this->erros = $erros;
				return 0;     
        	} 
        	if (!($dados = $this->banco->linhaSelect())){
				$this->mensagem = 'Usuário não encontrado!';   
				return 0;             
        	}       
        	if (!strcmp($_POST["senha_antiga"],$dados[1])) {
				//Altera Senha
				$campos = array("senha");
				$valores = array("'".$_POST["senha_nova"]."'");
				$camposFiltros[] = "id";
				$camposValores[] = $_POST["codigo"];
				$logico = " and ";
				$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
				$this->mensagem = "Senha Alterada!";
				return 1;
        	} 
        	else
        	{
        		$this->mensagem = "Senha Antiga não confere!";
        		return 0;
        	}       
			unset($_GET["update"]);	
        }	
	}
}
