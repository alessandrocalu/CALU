<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Clientes)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_clientes extends negocio_custom {

	/**
     * @desc Realiza customização de Insert de Lista de Clientes
    */
    function insert_lista_clientes(){
        if ($_GET["insert"] == 1)
		{
			//Inclusão de Multi Cliente 
			$vars = $_POST;
			//acrescenta filtro de produto
			$produto = ($vars["se_produto"]?$vars["se_produto"]:1);
			$filtroFixo = " cli.produto = ".$produto." ";
			$this->mensagem = "";
			$sucesso = true;
			for($i = 1; $i < 20 ; $i++) {
				if ($vars["se_telefone".$i] || $vars["se_nome".$i])
				{
					$this->mensagem .= "Prospecção para ".$vars["se_nome".$i]." tel ".$vars["se_telefone".$i];
				
					if ($vars["se_telefone".$i] && $vars["se_nome".$i] && (strlen($vars["se_telefone".$i]) == 8) )
					{
						//Verifica existencia de cliente (input)
						$camposFiltros = array();
						$valoresFiltros = array();

				
						if ($vars["se_telefone".$i]){
							//Telefone
							$camposFiltros[] = "cli.telefone";
							$valoresFiltros[] = "'".$vars["se_telefone".$i]."'";
							//FAX
							$camposFiltros[] = "cli.fax";
							$valoresFiltros[] = "'".$vars["se_telefone".$i]."'";
							//Celular
							$camposFiltros[] = "cli.celular";
							$valoresFiltros[] = "'".$vars["se_telefone".$i]."'";
						}
					
						if ($vars["se_celular".$i]){
							//Telefone
							$camposFiltros[] = "cli.telefone";
							$valoresFiltros[] = "'".$vars["se_celular".$i]."'";
							//FAX
							$camposFiltros[] = "cli.fax";
							$valoresFiltros[] = "'".$vars["se_celular".$i]."'";
							//Celular
							$camposFiltros[] = "cli.celular";
							$valoresFiltros[] = "'".$vars["se_celular".$i]."'";
						}
					
						if ($vars["se_nome".$i]){
							//Nome
							$camposFiltros[] = "cli.nome";
							$valoresFiltros[] = "'".$vars["se_nome".$i]."'";
							//Razão Social
							$camposFiltros[] = "cli.razaosocial";
							$valoresFiltros[] = "'".$vars["se_nome".$i]."'";
						}
						
						//Montar endereço
						$endereco = '';
						if ($vars["se_endereco".$i]){
							$endereco .= $vars["se_endereco".$i];
						}
						//Número
						if ($vars["se_numero".$i]){
							$endereco .= ", ".$vars["se_numero".$i];
						}
						//Complemento
						if ($vars["se_complemento".$i]){
							$endereco .= " ".$vars["se_complemento".$i];
						}
						if ($endereco){
							//Nome
							$camposFiltros[] = "cli.endereco";
							$valoresFiltros[] = "'".$endereco."'";
						}
						$_POST["se_endereco".$i] = $endereco;
						
						if (!$this->consulta->consultaSelectCount($camposFiltros,$valoresFiltros," or ", $filtroFixo)){
							if ($dados = $this->carregaDados("sequencial_tmkt","config")){
								$codigo = $dados[0][0]+1; 
								$this->carregaUpdate(" sequencial_tmkt = ".$codigo,"config", " 1=1 ", 0);
								//Grava Cliente
								$campos = array("codigo","nome","telefone","celular","endereco","cod_opera_criacao",
												"cod_ult_opera","vendedor","prospeccao","recado","data_criacao",
												"data_altera","tipo","vendido","contato","produto","operador_1","operador_2");
								$valores = array($codigo,"'".strtoupper($vars["se_nome".$i])."'","'".strtoupper($vars["se_telefone".$i])."'","'".strtoupper($vars["se_celular".$i])."'","'".strtoupper($endereco)."'",$_SESSION["navegador_operador"],
												 $_SESSION["navegador_operador"],$_SESSION["navegador_ator"],7,"'".strtoupper($vars["se_obs".$i])."'","getdate()",
												 "getdate()",1,0,"'".strtoupper($vars["se_contato".$i])."'", $produto, $_SESSION["navegador_operador"],$_SESSION["navegador_operador_2"]);
								if ($vars["se_bairro".$i]){
									$campos[] = "bairro";
									$valores[] = $vars["se_bairro".$i]; 
								}
								if ($vars["se_cidade".$i]){
									$campos[] = "cidade";
									$valores[] = $vars["se_cidade".$i]; 
								}
								$this->consulta->consultaInsert($campos,$valores,0);

								//Limpa cliente incluido
								unset($_POST["se_nome".$i]);
								unset($_POST["se_telefone".$i]);
								unset($_POST["se_celular".$i]);
								unset($_POST["se_endereco".$i]);
								unset($_POST["se_numero".$i]);
								unset($_POST["se_complemento".$i]);
								unset($_POST["se_bairro".$i]);
								unset($_POST["se_cidade".$i]);
								
								$this->mensagem .=  ": Prospeção gravada! ";
							}
							else
							{
								$this->mensagem .= ": Erro banco! ";
								$sucesso = false;
							}
						}
						else
						{
							$this->mensagem .= ": Já existe cliente! ";
							$sucesso = false;
						}
					}
					else
					{
						$this->mensagem .= ": Dados inválidos! ";
						$sucesso = false;
					}
				}
			}
			

			if ($_POST["2_valor0"] && $_POST["8_valor0"]){

				$this->mensagem .= "Prospecção para ".$_POST["2_valor0"]." tel ".$_POST["8_valor0"];

				//Verifica se já existe cliente cadastrado
				//Verifica existencia de cliente (input)
				$camposFiltros = array();
				$valoresFiltros = array();
					
				if ($_POST["8_valor0"]){
					//Telefone
					$camposFiltros[] = "cli.telefone";
					$valoresFiltros[] = "'".$_POST["8_valor0"]."'";
					//FAX
					$camposFiltros[] = "cli.fax";
					$valoresFiltros[] = "'".$_POST["8_valor0"]."'";
					//Celular
					$camposFiltros[] = "cli.celular";
					$valoresFiltros[] = "'".$_POST["8_valor0"]."'";
				}
					
				if ($_POST["celular"]){
					//Telefone
					$camposFiltros[] = "cli.telefone";
					$valoresFiltros[] = "'".$_POST["celular"]."'";
					//FAX
					$camposFiltros[] = "cli.fax";
					$valoresFiltros[] = "'".$_POST["celular"]."'";
					//Celular
					$camposFiltros[] = "cli.celular";
					$valoresFiltros[] = "'".$_POST["celular"]."'";
				}
					
				if ($_POST["2_valor0"]){
					//Nome
					$camposFiltros[] = "cli.nome";
					$valoresFiltros[] = "'".$_POST["2_valor0"]."'";
					//Razão Social
					$camposFiltros[] = "cli.razaosocial";
					$valoresFiltros[] = "'".$_POST["2_valor0"]."'";
				}

				//Montar endereço
				$endereco = '';
				if ($_POST["endereco"]){
					$endereco .= $_POST["endereco"];
				}
				//Número
				if ($_POST["se_numero"]){
					$endereco .= ", ".$_POST["se_numero"];
				}
				//Complemento
				if ($_POST["se_complemento"]){
					$endereco .= " ".$_POST["se_complemento"];
				}
				if ($endereco){
					//Nome
					$camposFiltros[] = "cli.endereco";
					$valoresFiltros[] = "'".$endereco."'";
				}
				if ($endereco){
					//Nome
					$camposFiltros[] = "cli.endereco";
					$valoresFiltros[] = "'".$endereco."'";
				}
				$_POST["endereco"] = $endereco;

				if (!($this->consulta->consultaSelectCount($camposFiltros,$valoresFiltros," or ", $filtroFixo))){
				
					//Inclusão de ultimo input 
					if ($dados = $this->carregaDados("sequencial_tmkt","config")){
						//Incrementa código 
						$codigo = $dados[0][0]+1; 
						$this->carregaUpdate(" sequencial_tmkt = ".$codigo,"config", " 1=1 ", 0);
						//Grava Cliente
						$campos = array("codigo","nome","telefone","celular","endereco","cod_opera_criacao",
										"cod_ult_opera","vendedor","prospeccao","recado","data_criacao",
										"data_altera","tipo","vendido","contato","produto","operador_1","operador_2");
						$valores = array($codigo,"'".strtoupper($_POST["2_valor0"])."'","'".strtoupper($_POST["8_valor0"])."'","'".strtoupper($_POST["celular"])."'","'".strtoupper($endereco)."'",$_SESSION["navegador_operador"],
										$_SESSION["navegador_operador"],$_SESSION["navegador_ator"],7,"'".strtoupper($_POST["obs"])."'","getdate()",
										"getdate()",1,0,"'".strtoupper($_POST["contato"])."'",$produto,$_SESSION["navegador_operador"],$_SESSION["navegador_operador_2"]);
						if ($_POST["se_bairro"]){
							$campos[] = "bairro";
							$valores[] = $_POST["se_bairro"]; 
						}
						if ($_POST["se_cidade"]){
							$campos[] = "cidade";
							$valores[] = $_POST["se_cidade"]; 
						}
						$this->consulta->consultaInsert($campos,$valores,0);

						$this->mensagem .= ": Prospeção gravada! ";
					}
					else
					{
						$this->mensagem .= ": Erro banco! ";
						$sucesso = false;
					}
				}
				else
				{
					$this->mensagem .= ": Já existe cliente! ";
					$sucesso = false;
				}		
			}	
			
			if ($sucesso){	
				unset($_GET["insert"]);
				$this->mostraLocal("lista_ativas");
			}
			
			unset($_GET["insert"]);
        }       
        return 1;
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
	
	/**
	 * @desc Desenha status de prospec em listagem
	 * @param int $codigo
	 * @return string de Desenho de Status
	*/
	function desenhaStatusCliente($codigo = 0){
		$status = $this->statusCliente($codigo);
		//Vendido
		if ($status == 3){
			return "<font color='#FF0000'>Vendido";
		}
		//Ocupado
		if ($status == 2){
			return "<font color='#FF0000'>Ocupado";
		}
		//Liberado
		if ($status == 1){
			return "<font color='#008F00'>Liberado";
		}
		return "";
	}
	
	/**
	 * @desc Gravar campo em tabela
	 * @param integer $id_campo identificação de campo tabela
	 * @param string $valor de valor de campo 
	 * @param integer $chave valor de chave de tabela
	*/
	function gravar_campo($id_tabela_campo, $valor, $chave){
		$valor = strtoupper(trim($valor));
		$ret = parent::gravar_campo($id_tabela_campo, $valor, $chave);
		
		return $ret;
	}
		
	
	
	/**
	 * @desc Verifica validade de campo em tabela
	 * @param integer $id_campo identificação de campo tabela
	 * @param string $valor de valor de campo 
	 * @param integer $chave valor de chave de tabela
	 * @return mensagem de erro de verificação
	*/
	function verifica_validade_campo($id_tabela_campo, $valor, $chave, $name = ""){
		$valor = strtoupper(trim($valor));
		$ret = parent::verifica_validade_campo($id_tabela_campo, $valor, $chave, $name);
		$produto = $ret["linha_antiga"]["produto"];
		
		$this->banco->setSelect("count(codigo) as total");
		$this->banco->setFrom("TMKT");
		
		if (!$valor){
			$ret['mensagem'] = "Campo vazio!";
			return $ret;     
		}
		
		//Customizações de campos 
		switch ($id_tabela_campo) {
    		//Campo Telefone
    		case 84:
    		case 85:
    		case 86:
    			//Teste telefone já existente
				$this->banco->setWhere(" (produto = ".$produto.") and (codigo <> ".$chave.") and (telefone = '".$valor."' or celular = '".$valor."' or fax = '".$valor."')");
				$this->banco->setOrder("");
				$this->banco->enviaSelect();
				
				if ($erros = $this->banco->getErros()) {
					$ret['mensagem'] = $erros;
					return $ret;     
				} 
				
				if ((!($dados = $this->banco->linhaSelect())) || ($dados[0] > 0) ){
					$ret['mensagem'] = 'Telefone(telefone, celular ou fax) repetido!';   
					return $ret;             
				}
			break;
			
			//Campo Nome
    		case 82:
    			//Teste telefone já existente
				$this->banco->setWhere(" (produto = ".$produto.") and (codigo <> ".$chave.") and (nome = '".$valor."' or razaosocial = '".$valor."')");
				$this->banco->setOrder("");
				$this->banco->enviaSelect();
				
				if ($erros = $this->banco->getErros()) {
					$ret['mensagem'] = $erros;
					return $ret;     
				} 
				
				if ((!($dados = $this->banco->linhaSelect())) || ($dados[0] > 0) ){
					$ret['mensagem'] = 'Nome(nome ou razao social) repetido!';   
					return $ret;             
				}
    		break;

    		//Campo Endereço
    		case 93:
    			//Teste Endereço já existente
				$this->banco->setWhere(" (produto = ".$produto.") and (codigo <> ".$chave.") and (endereco = '".$valor."')");
				$this->banco->setOrder("");
				$this->banco->enviaSelect();
				
				if ($erros = $this->banco->getErros()) {
					$ret['mensagem'] = $erros;
					return $ret;     
				} 
				
				if ((!($dados = $this->banco->linhaSelect())) || ($dados[0] > 0) ){
					$ret['mensagem'] = 'Endereco repetido!';   
					return $ret;             
				}
    		break;
		}
		return $ret;
	}
	
}