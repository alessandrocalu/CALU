<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio 
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/
 
class negocio_custom extends negocio {

	/**
	 * @desc Verifica acesso
	 * @param string Login de Usuario
	 * @param string Senha de Usuario
	 * @return integer Id de usuario
	*/
	function verificaLogin($login, $senha, $tipo = 0){
		$ret = parent::verificaLogin($login, $senha, $tipo);
		return $ret;
	}
	
	/**
	 * @desc Verifica se consultor pode mudar para status escolhido o cadastro do cliente
	 * @param $cliente de Codigo de Cliente
	 * @param $consultor de Codigo de Consultor
	 * @param $status de Novo Status
	*/
    function verificaPermissaoMudarStatus($cliente, $consultor, $status){
		if ($dados = $this->carregaDados("count(codigo)","LOG_TMKT"," (vendedor = ".$consultor.") and (codigo = ".$cliente.") and ( prospeccao = ".$status." ) and (data_altera > (getdate()-45)) ")){
			if ($dados[0][0]) {
				return false;
			}       
		}
		return true;
	}
        
	/**
	 * @desc Retorna lista de Status de Prospecção para edição
	 * @param $id_dominio Identificador de Dominio
	 * @return array de Dados de regitro de dominio
	*/
    function listaStatusProspeccaoEdicao($todos = 0){
        $dados = $this->buscaDadosDominio(12);
        if ($dados){
			if ($_SESSION["navegador_tipo_usuario"] != 5 && !$todos){
				$values = array(0);
				$labels = array("Todos");
			}
            for ($i = 0; $i < count($dados); $i++){
                if( (($dados[$i]["codigo"] > 6)&& ($dados[$i]["codigo"] < 14)) 
					 || ($dados[$i]["codigo"] == -2) || ($_SESSION["navegador_tipo_usuario"] == 5 ) || $todos){
                    $values[] = $dados[$i]["codigo"];
                    $labels[] = $dados[$i]["nome"];
                }       
            }
            return array("values" => $values,"labels" => $labels);
        }
        return 0;
    }

	/**
	 * @desc Retorna lista de Produtos para edição
	 * @return array de Dados de regitro de dominio
	*/
    function listaProdutosEdicao(){
        $dados = $this->buscaDadosDominio(27);
        if ($dados){
            for ($i = 0; $i < count($dados); $i++){
				$produto = $dados[$i]["codigo"];
				if ( ($_SESSION["navegador_tipo_usuario"] == 7) ||
					 ($_SESSION["navegador_tipo_usuario"] == 6) ||
					 ($_SESSION["navegador_tipo_usuario"] == 3) ){
					$this->banco->setSelect("count(codigo) as total");
					$this->banco->setFrom("vendedor_produto");
					$this->banco->setWhere("produto = "."'".$produto."' and vendedor = '".$_SESSION["navegador_ator"]."' ");
					$this->banco->setOrder("");
					$this->banco->enviaSelect();
					if (($dados_permissao = $this->banco->linhaSelect()) && $dados_permissao[0]){
						$nome = $dados[$i]["nome"];
					}
					else
					{
						$nome = "";
					}

				}
				else
				{
					$nome = $dados[$i]["nome"];
				}
				
				if ($nome){
					$values[] = $produto;
					$labels[] = $nome;
				}
            }
            return array("values" => $values,"labels" => $labels);
        }
        return 0;
    }
        
	/**
	 * @desc Retorna nme de Status de Prospecção 
	*/
    function nomeProspeccao($codigo,$mostra_imagem = 1){
        $dados = $this->buscaDadosDominio(12);
        if ($dados){
            for ($i = 0; $i < count($dados); $i++){
                if($dados[$i]["codigo"] == $codigo){
                    $imagem = $codigo;
                    if ($codigo < 7){
                        $imagem = 7;
                    }
                    if ($codigo > 13){
                        $imagem = 13;
                    }
                    if ($mostra_imagem){
                        return "<img src=\"./images/pros".$imagem.".gif\" title='".$dados[$i]["nome"]."!!'>"; 
                    }
                    return $dados[$i]["nome"];
                }       
            }
        }
        return "";
    }
    
	/**
	 * @desc Retorna lista de Tabela(codigo,nome) com link Selecionado 
	 * @return array de lista
	*/
    function listaTabelaLink($id_tabela_campo, $filtroFixo = ""){
		//Customização de Filtro	
    	switch ($id_tabela_campo) {
    		//Campo Nome de Operador
    		case 99:
        		$filtroFixo = " inativo = 0 ";
        		break;
		}
    	return parent::listaTabelaLink($id_tabela_campo, $filtroFixo);
    }
        
	/**
	 * @desc Retorna lista de Tipo de Clinte
	 * @return array de Dados de regitro de dominio
	*/
    function listaTipoCliente(){
        $values = array('0,1,2,3,4','1,2','3,4');
        $labels = array("Todos","Ativos","Passivos");
        return array("values" => $values,"labels" => $labels);
	}
	
	/**
	 * @desc Retorna lista de Tipos de Equipamentos
	 * @return array de lista de Tipos de Equipamentos
	*/
    function listaTipoEquipamento(){
        $values = array('L','V');
        $labels = array("Locação","Vendas");
        return array("values" => $values,"labels" => $labels);
	}
	
	/**
	 * @desc Retorna lista de Tipos de Equipamentos
	 * @return array de lista de Tipos de Equipamentos
	*/
    function listaTipoTratamento(){
		if (!$this->dados_tratamento_proposta){
			if (!$this->tratamento_proposta){
				$this->tratamento_proposta = new tabela("TRATAMENTO_PROPOSTA"); 
			}	
	        if ($this->tratamento_proposta){
	            $this->dados_tratamento_proposta = $this->tratamento_proposta->carregar("",""," = ","descricao"); 
	        }
	        if ($erros = $this->tratamento_proposta->getErros()) {
	            $this->erros = $erros;
	        }  
		}	
                        
        if ($this->dados_tratamento_proposta){
            for ($i = 0; $i < count($this->dados_tratamento_proposta); $i++){
                $values[] = $this->dados_tratamento_proposta[$i]["id_Trat"];
                $labels[] = $this->dados_tratamento_proposta[$i]["descricao"];
            }
            return array("values" => $values,"labels" => $labels);
        }
        return 0;
    }

	/**
	 * @desc Retorna lista de Tipos de Equipamentos
	 * @return array de lista de Tipos de Equipamentos
	*/
    function listaVagaAtiva($acao = ""){
		if (!$this->dados_vaga_ativa){
			if (!$this->tabela_vaga){
				$this->tabela_vaga = new tabela("VAGA"); 
			}	
	        if ($this->tabela_vaga){
	            $this->dados_vaga_ativa = $this->tabela_vaga->carregar("status","0 and oculto = 0 "," = ","nome"); 
	        }
	        if ($erros = $this->tabela_vaga->getErros()) {
	            $this->erros = $erros;
	        }  
		}	
                        
        if ($this->dados_vaga_ativa){
			$values[] = 0;
            $labels[] = "Escolha a vaga";

            for ($i = 0; $i < count($this->dados_vaga_ativa); $i++){
                $values[] = $this->dados_vaga_ativa[$i]["codigo"];
                $labels[] = $this->dados_vaga_ativa[$i]["nome"];
            }

			if ($acao != "filtro"){
				$values[] = -1;
		        $labels[] = "Outra";
			}

            return array("values" => $values,"labels" => $labels);
        }
        return 0;
    }
	
	/**
	 * @desc Retorna lista de Tipos de Proposta com valor
	 * @return array de lista de Tipos de Proposta
	*/
    function listaTipoProposta(){
		if (!$this->dados_tipo_proposta){
			if (!$this->tipo_proposta){
				$this->tipo_proposta = new tabela("TIPO_PROPOSTA"); 
			}	
	        if ($this->tipo_proposta){
	            $this->dados_tipo_proposta = $this->tipo_proposta->carregar("",""," = ","descricao"); 
	        }
	        if ($erros = $this->tipo_proposta->getErros()) {
	            $this->erros = $erros;
	        }  
		}                
			
        if ($this->dados_tipo_proposta){
            for ($i = 0; $i < count($this->dados_tipo_proposta); $i++){
                $values[] = $this->dados_tipo_proposta[$i]["id_TipoProposta"];
				$valor = "R$ ".number_format($this->dados_tipo_proposta[$i]["valor"],2,",",".");
                $labels[] = $this->dados_tipo_proposta[$i]["descricao"].",".$valor;
            }
            return array("values" => $values,"labels" => $labels);
        }
        return 0;
    }
        
	/**
	 * @desc Retorna lista de Consultores
	 * @param string de grupo
	 * @return array de lista de Consultores
	*/
    function listaConsultor($grupo = ""){
		if ($grupo){
			$classe = $grupo;
		}
		else
		{
			$classe = "t";
		}
		
		if (!$this->dados_consultor[$classe]){
			if (!$this->calu_usuario){
				$this->calu_usuario = new tabela("CALU_USUARIO"); 
			}	
	        if ($this->calu_usuario){
				if ($_SESSION["navegador_tipo_usuario"] != 7) {
		            if ($grupo){
						$this->dados_consultor[$classe] = $this->calu_usuario->carregar("grupo","'".$grupo."' and  tipo in (6,7) ".$filtroConsultor," = ","nome"); 
		            }
		            else
		            {
		                $this->dados_consultor[$classe] = $this->calu_usuario->carregar("tipo","7 or (tipo = 6) or (tipo = 3)"," = ","nome"); 
					}
				}       
				else
				{
					$this->dados_consultor[$classe] = $this->calu_usuario->carregar("ator",$_SESSION["navegador_ator"]," = ","nome"); 
				}
	        }
	        if ($erros = $this->calu_usuario->getErros()) {
	            $this->erros = $erros;
	        }  
		}	
                        
        if ($this->dados_consultor[$classe]){
			if ($_SESSION["navegador_tipo_usuario"] != 7){
				$values = array(0);
				$labels = array("Todos");
			}	
            for ($i = 0; $i < count($this->dados_consultor[$classe]); $i++){
                $values[] = $this->dados_consultor[$classe][$i]["ator"];
                $labels[] = $this->dados_consultor[$classe][$i]["nome"];
            }
            return array("values" => $values,"labels" => $labels);
        }
        return 0;
    }
    
	/**
	 * @desc Retorna lista de Produtos de Consultor
	 * @param string de grupo
	 * @return array de lista de Consultores
	*/
    function listaConsultorProduto($consultor, $tipo = "csv"){
    	global $funcoes;
		if (!$this->dados_consultor_produto[$consultor]){
			if (!$this->vendedor_produto){
				$this->vendedor_produto = new tabela("VENDEDOR_PRODUTO"); 
			}	
	        if ($this->vendedor_produto){
				$this->dados_consultor_produto[$consultor] = $this->vendedor_produto->carregar("vendedor",$consultor," = ","codigo"); 
	        }
	        if ($erros = $this->vendedor_produto->getErros()) {
	            $this->erros = $erros;
	        }  
		}	
                        
        if ($this->dados_consultor_produto[$consultor]){
           	$dados = $this->dados_consultor_produto[$consultor];
           	$dados_csv = array();
        	for ($contador = 0; $contador < count($dados) ;$contador++){
        		$dados_csv[] = $dados[$contador]["produto"];
        	}
            return implode(",",$dados_csv);
        }
        return "";
    }
	
	/**
	 * @desc Retorna lista de Operadores
	 * @return array de lista de Operadores
	*/
    function listaOperador(){
		if (!$this->dados_operador)
		{
			if (!$this->tabela_operador)
			{
				$this->tabela_operador = new tabela("OPERADOR"); 
			}	
	        if ($this->tabela_operador)
	        {
				if ($_SESSION["navegador_tipo_usuario"] == 5) 
				{
					$this->dados_operador = $this->tabela_operador->carregar("codigo",$_SESSION["navegador_ator"]," = ","nome"); 
				}
	        	else
	        	{
					$this->dados_operador = $this->tabela_operador->carregar("inativo","0 and 0 = 0"," = ","nome");
	        	}	 
	        }
	        if ($erros = $this->tabela_operador->getErros()) 
	        {
	            $this->erros = $erros;
	        }  
		}	
                        
        if ($this->dados_operador){
        	if ($_SESSION["navegador_tipo_usuario"] != 5)
        	{ 
        		$values = array(0);
				$labels = array("Todos");
        	}	
            for ($i = 0; $i < count($this->dados_operador); $i++){
                $values[] = $this->dados_operador[$i]["codigo"];
                $labels[] = $this->dados_operador[$i]["nome"];
            }
            return array("values" => $values,"labels" => $labels);
        }
        return 0;
    }
        
	/**
	 * @desc Retorna lista de Grupos de Consultores
	 * @return array de lista de Grupos de Consultores
	*/
	function listaGrupoConsultor($grupo = ""){
		if ($grupo){
			$classe = $grupo;
		}
		else
		{
			$classe = "t";
		}
		
		if (!$this->dados_grupo_consultor[$classe]){
			if (!$this->calu_usuario){
				$this->calu_usuario = new tabela("CALU_USUARIO"); 
			}	
			
			if ($this->calu_usuario && ($_SESSION["navegador_tipo_usuario"] != 7)){
				if ($_SESSION["navegador_tipo_usuario"] != 7) {
					if ($grupo){
						$this->dados_grupo_consultor[$classe] = $this->calu_usuario->carregar("grupo","'".$grupo."' and  tipo in (6,7) ".$filtroConsultor," = ","grupo"); 
			        }
					else
					{
						$this->dados_grupo_consultor[$classe] = $this->calu_usuario->carregar("tipo","7 or (tipo = 6)"," = ","grupo"); 
					}       
				}
				else
				{
					$this->dados_grupo_consultor[$classe] = $this->calu_usuario->carregar("ator",$_SESSION["navegador_ator"]," = ","nome"); 
				}
	        }
	        if ($erros = $this->calu_usuario->getErros()) {
		        $this->erros = $erros;
	        }  
		}	
                        
        if ($this->dados_grupo_consultor[$classe]){
			if (!$grupo && ($_SESSION["navegador_tipo_usuario"] != 7)){
				$values = array(0);
				$labels = array("Todos");
			}	
            $grupo_old = "";
            for ($i = 0; $i < count($this->dados_grupo_consultor[$classe]); $i++){
                if ($this->dados_grupo_consultor[$classe][$i]["grupo"] && ($this->dados_grupo_consultor[$classe][$i]["grupo"] != $grupo_old)) {
                    $values[] = $this->dados_grupo_consultor[$classe][$i]["grupo"];
                    $labels[] = $this->dados_grupo_consultor[$classe][$i]["grupo"];
                    $grupo_old = $this->dados_grupo_consultor[$classe][$i]["grupo"];
                }       
            }
            return array("values" => $values,"labels" => $labels);
        }
        return 0;
    }
    
	/**
	 * @desc Busca Dados de Contrato de Cliente 
	 * @param integer $cliente de codigo de cliente
	 * @return array $dados de Dados de Contrato
	*/	
	function listaContratoCliente($cliente)
	{
		if (!$this->dados_contrato){
			if (!$this->contrato){
				$this->contrato = new tabela("CONTRATO");
			}
			//Busca codigo do ultimo contrato de cliente
			$this->dados_contrato = $this->contrato->carregar("cliente", $cliente, " = ", " data_contrato DESC ");
		}
		return $this->dados_contrato;
	}

	/**
	 * @desc Retorna lista de Agrupamentos de Gráficos Navegador
	 * @return array de Agrupamentos
	*/
    function listaAgrupamento($tem_grupo = 0){
        $values = array('181','169');
        $labels = array('Geral','Vendedor');
        if ($tem_grupo){
            $values[] = '150';
            $labels[] = 'Grupo';
        }
        $values[] = '176';
        $labels[] = 'Hot Line';
        
        $values[] = '179';
        $labels[] = 'Tipo';
        
        return array("values" => $values,"labels" => $labels);
    }
    
	/**
	 * @desc Retorna lista de Tipo de dominio
	 * @return array de lista de Tipos de Dominio
	*/
    function listaTipoDominio(){
		if (!$this->dados_tipo_dominio)
		{
			if (!$this->tabela_tipo_dominio)
			{
				$this->tabela_tipo_dominio = new tabela("TIPODOMINIO"); 
			}	
	        if ($this->tabela_tipo_dominio)
	        {
				if ($_SESSION["navegador_tipo_usuario"] == 1) 
				{
					$this->dados_tipo_dominio = $this->tabela_tipo_dominio->carregar("",""," = ","nome"); 
				}
	        	else
	        	{
					$this->dados_tipo_dominio = $this->tabela_tipo_dominio->carregar("id_dominio","(2,3,5,8,9,10,13,30,31)"," in ","nome");
	        	}	 
	        }
	        if ($erros = $this->tabela_tipo_dominio->getErros()) 
	        {
	            $this->erros = $erros;
	        }  
		}	
                        
        if ($this->dados_tipo_dominio){
            for ($i = 0; $i < count($this->dados_tipo_dominio); $i++){
                $values[] = $this->dados_tipo_dominio[$i]["id_dominio"];
                $labels[] = $this->dados_tipo_dominio[$i]["nome"];
            }
            return array("values" => $values,"labels" => $labels);
        }
        return 0;
    }
    
	/**
	 * @desc Retorna lista de Dominio Selecionado 
	 * @return array de lista
	*/
    function nomeTipoDominio($codigo = 0){
        $dados = $this->listaTipoDominio();
        if (is_array($dados) && count($dados)){
        	$values = $dados["values"];
        	$labels = $dados["labels"]; 
			for ($i = 0; $i < count($values); $i++){
	        	if ($values[$i] == $codigo){
					return $labels[$i];
				}
			}
        }	
        return "";
    }
		        
	/**
	 * @desc Retorna lista de Dominio Selecionado 
	 * @return array de lista
	*/
    function listaDominio($tipo_dominio = 1){
        $dados = $this->buscaDadosDominio($tipo_dominio);
		for ($i = 0; $i < count($dados); $i++){
	        $values[] = $dados[$i]["codigo"];
			$labels[] = $dados[$i]["nome"];
		}
        return array("values" => $values,"labels" => $labels);
    }

	/**
	 * @desc Retorna lista de Dominio Selecionado 
	 * @return array de lista
	*/
    function nomeDominio($tipo_dominio = 1, $codigo = 0){
        $dados = $this->buscaDadosDominio($tipo_dominio);
		for ($i = 0; $i < count($dados); $i++){
	        if ($dados[$i]["codigo"] == $codigo){
				return $dados[$i]["nome"];
			}
		}
        return "";
    }
        
	/**
     * @desc Retorna lista de Agrupamentos de Gráficos Navegador
     * @return array de Agrupamentos
    */
    function nomeAgrupamento($valor,$agrupamento){
        switch ($agrupamento){ 
            //Geral
            case 181:
				return $valor; 
            //Vendedor
            case 169:
                if ($valor){
					if (!$this->vendedor_nome[$valor]){
						if (!$this->calu_usuario){
							$this->calu_usuario = new tabela("CALU_USUARIO");
						}	
	                    $dados = $this->calu_usuario->carregar("id",$valor." and  tipo in (6,7) "," = ","nome");
	                    if ($dados){
	                        $this->vendedor_nome[$valor] = $dados[0]["login"];
	                    }
					}	
					if ($this->vendedor_nome[$valor]){
						return $this->vendedor_nome[$valor];
					}
					 	
                }       
                break;
            //Grupo
            case 150:
                if (!$valor){
                    $valor = "Sem Grupo"; 
                }
                return $valor; 
            //Hot Line
            case 176:
                if (($valor >= 7) && ($valor <= 13))  
                {
                    return $this->nomeProspeccao($valor,0);
                }       
                break;
            //Tipo
            case 179:
                $dados = $this->buscaDadosDominio(1);
                for ($i = 0; $i < count($dados); $i++){
                    if ($dados[$i]["codigo"] == $valor){
                        return $dados[$i]["nome"];
                    }
                }
                return "Sem Tipo"; 
            default :
                return "Desconhecido";
        }
        return "";              
    }
        
	/**
     * @desc Retorna lista de Agrupamentos de Gráficos Navegador
     * @param $semana de semana do ano (yyyy.mm.ww)
     * @return $nomeSemana de semana formatada (wªSem/mm/YYYY)
    */
    function nomeSemana($semana){
		return substr($semana,8,2).'S/'.substr($semana,5,2)."/".substr($semana,0,4);
	}

	/**
     * @desc Retorna nome de Preposto selecionado
     * @return array de Agrupamentos
    */
    function nomePreposto($valor){
		$dados = $this->buscaDadosDominio(15);
		for ($i = 0; $i < count($dados); $i++){
			if ($dados[$i]["codigo"] == $valor){
				return $dados[$i]["nome"];
			}
		}
		return "";
	}
	
	/**
     * @desc Retorna nome de Setor selecionado
     * @return array de Agrupamentos
    */
    function nomeSetor($valor){
		$dados = $this->buscaDadosDominio(16);
		for ($i = 0; $i < count($dados); $i++){
			if ($dados[$i]["codigo"] == $valor){
				return $dados[$i]["nome"];
			}
		}
		return "";
	}
	
	/**
     * @desc Retorna nome de Setor selecionado
     * @return array de Agrupamentos
    */
    function nomeTipoApropriacao($valor){
		$dados = $this->buscaDadosDominio(17);
		for ($i = 0; $i < count($dados); $i++){
			if ($dados[$i]["codigo"] == $valor){
				return $dados[$i]["nome"];
			}
		}
		return "";
	}
	
	/**
     * @desc Retorna nome de Cliente
     * @return string de Nome de Clientes
    */
    function nomeCliente($valor){
		if (!$this->dados_tmkt[$valor]){
			if (!$this->tmkt){
				$this->tmkt = new tabela("TMKT");
			}	
	        $this->dados_tmkt[$valor] = $this->tmkt->carregar("codigo",$valor);
			if ($erros = $this->tmkt->getErros()) {
				$this->erros = $erros;
			}  
		}	
			
		if ($this->dados_tmkt[$valor]){	
			return $this->dados_tmkt[$valor][0]["nome"];
		}
		return "";
	}
	
	/**
     * @desc Retorna telefone de Cliente
     * @return string de Telefone de Clientes
    */
    function telefoneCliente($valor){
		if (!$this->dados_tmkt[$valor]){
			if (!$this->tmkt){
				$this->tmkt = new tabela("TMKT");
			}	
	        $this->dados_tmkt[$valor] = $this->tmkt->carregar("codigo",$valor);
			if ($erros = $this->tmkt->getErros()) {
				$this->erros = $erros;
			}  
		}	
			
		if ($this->dados_tmkt[$valor]){	
			return $this->dados_tmkt[$valor][0]["telefone"];
		}
		return "";
	}

	/**
     * @desc Retorna Razão Social de Cliente
     * @return string de Razão Social de Clientes
    */
    function razaosocialCliente($valor){
		if (!$this->dados_tmkt[$valor]){
			if (!$this->tmkt){
				$this->tmkt = new tabela("TMKT");
			}	
	        $this->dados_tmkt[$valor] = $this->tmkt->carregar("codigo",$valor);
			if ($erros = $this->tmkt->getErros()) {
				$this->erros = $erros;
			}  
		}	
			
		if ($this->dados_tmkt[$valor]){	
			return $this->dados_tmkt[$valor][0]["razaosocial"];
		}
		return "";
	}

	/**
     * @desc Retorna CNPJ de Cliente
     * @return string de CPJ de Cliente
    */
    function cnpjCliente($valor){
		if (!$this->dados_tmkt[$valor]){
			if (!$this->tmkt){
				$this->tmkt = new tabela("TMKT");
			}	
	        $this->dados_tmkt[$valor] = $this->tmkt->carregar("codigo",$valor);
			if ($erros = $this->tmkt->getErros()) {
				$this->erros = $erros;
			}  
		}	
			
		if ($this->dados_tmkt[$valor]){	
			return $this->dados_tmkt[$valor][0]["cnpj"];
		}
		return "";
	}
	
	/**
     * @desc Retorna CNPJ de Cliente
     * @param int $codigo de Código de TMKT
     * @return int de $status (0 - Erro, 1 - Liberado, 2 - Ocupado, 3 - Vendido)
    */
    function statusCliente($codigo = 0){
		if (!$this->status_tmkt[$codigo]){
			
			$this->banco->setSelect("vendedor, vendido, convert(integer, (getdate() - data_altera)) as dias");
			$this->banco->setFrom("TMKT");
			$this->banco->setWhere(" codigo = ".$codigo);
			$this->banco->setOrder("");
			$this->banco->enviaSelect();
				
			if (!($dados = $this->banco->linhaSelect())){
				$this->erros[] = 'Registro não encontrado!';   
				return 0;             
			}
			$vendedor = $dados[0];
			$vendido = $dados[1];
			$dias = $dados[2];
			$this->status_tmkt[$codigo] = ($vendido?3:(($dias>46||!$vendedor)?1:2));
		}
		return $this->status_tmkt[$codigo]; 
	}

	/**
     * @desc Desenha botões para Follow Up - Campo BoxTexto
     * @return string de Observação
    */
    function botoesFollowUp($recado, $obs, $obs_vendedor, $codigo = 1){
		global $visual;
		$botoes = "";
	    $botoes .= $visual->exibeBoxTextHtml("recado_".$codigo, $this->nomeCliente($codigo)." :: Recado",$recado,"edit_box","","Obs.Consultor",1,"xajax_gravar_observacao(".$codigo.",document.getElementById(\'box_recado_".$codigo."\').value)");
		$botoes .= $visual->exibeBoxTextHtml("obs_".$codigo, $this->nomeCliente($codigo)." :: Obs.TMKT",$obs,"edit_box","","Obs.Telemarketing");
		$botoes .= $visual->exibeBoxTextHtml("obs_vendedor_".$codigo, $this->nomeCliente($codigo)." :: Hist.Venda",$obs_vendedor,"edit_box","","Histórico Venda",2,"xajax_gravar_observacao_vendedor(".$codigo.",document.getElementById(\'box_obs_vendedor_".$codigo."\').value)");
		return $botoes;
	}
	
	/**
     * @desc Desenha botões para Follow Up - Campo BoxTexto
     * @return string de Observação
    */
    function botoesHistFollowUp($nome_cliente, $obs, $obs_vendedor, $codigo = 1){
		global $visual;
		$botoes = "";
		$botoes .= $visual->exibeBoxTextHtml("obs_".$codigo, $nome_cliente." :: Obs.TMKT",$obs,"edit_box","","Obs.Telemarketing");
		$botoes .= $visual->exibeBoxTextHtml("obs_vendedor_".$codigo, $nome_cliente." :: Hist.Venda", $obs_vendedor,"edit_box","","Histórico Venda");
		return $botoes;
	}
	
	/**
	 * @desc Retorna botões de açoes de Proposta
	*/
	function botoesAcoesProposta($proposta){
		global $chave;
		$cliente = 0;
		if ($chave) {
			$cliente = $chave;
		}
		//Editar Proposta
		$botoes .= "&nbsp<a href=\"?local=cad_proposta&chave=".$cliente."&proposta=".$proposta."\"><img src=\"./images/edit.gif\"   title='Edita Proposta'/></a>";
		return $botoes;
	}
    
	/**
     * @desc Retorna botões de açoes de Lista de Contrato
     * @param int $codigo de Código de Contrato
     * @return string de html de botões
    */
    function botoesAcoesContrato($codigo){
    	$cliente = ($_GET["chave"]?$_GET["chave"]:$_POST["chave"]);
        $botoes = "";
        //Apagar Item
        $botoes .= "&nbsp<a href='?local=cad_contrato&chave=".$cliente."&se_campo=".$codigo."'><img src='./images/edit.gif' title='Edita Contrato'/></a>";
        return $botoes; 
    }
    
    /**
     * @desc Retorna botões de açoes de Consulta
     * @param int $consulta de identificação de consulta
     * @return string de html de botões
    */
    function botoesAcoesTabelas($codigo){
    	$id_dominio = $_GET["id_dominio"];
    	
    	if (!$id_dominio){
    		$id_dominio = $_POST["id_dominio"];
    	}
    	
        $botoes = "";
        //Apagar Item
        //$botoes .= "&nbsp<a href=\"?local=cad_tabelas&chave=".$id_dominio."&se_campo=".$codigo."\"><img src=\"./images/edit.gif\"   title='Edita Item'/></a>";
        $botoes .= "&nbsp<a href=\"?local=lista_tabelas&delete=apaga&id_dominio=".$id_dominio."&codigo=".$codigo."\"><img src=\"./images/del.gif\"   title='Apaga Item'/></a>";
        return $botoes; 
    }

	/**
     * @desc Gravar observação(nota) de consultor em cliente 
     * @param  $cliente de Cliente
     * @param  $nota de Observação de Consultor
    */
    function gravar_observacao($cliente,$nota){
		$campos = array("recado","religa","avisa_agenda");
        $valores = array("'".$nota."'","getDate()","1");
        $camposFiltros = array("codigo");
        $camposValores = array($cliente);
   
        $logico = " and ";
        $this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
        return $this->mensagem = "Nota de consultor gravada! ";
	}
	
	/**
     * @desc Gravar observação(venda) de consultor em cliente 
     * @param  $cliente de Cliente
     * @param  $nota de Observação de Consultor
    */
    function gravar_observacao_vendedor($cliente,$nota){
		$campos = array("obs_vendedor");
        $valores = array("'".$nota."'");
        $camposFiltros = array("codigo");
        $camposValores = array($cliente);
   
        $logico = " and ";
        $this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
        return $this->mensagem = "Nota de venda/visita registrada! ";
	}
	
	/**
     * @desc Adiciona produto para vendedor 
     * @param  $consultor de Cconsultor
     * @param  $produto de Produto
    */
    function adicionar_vendedor_produto($consultor,$produto){
		$campos = "vendedor,produto";
		$valores = $consultor.",".$produto;
		$this->carregaInsert($campos,"VENDEDOR_PRODUTO",$valores,0);
        return 1;
	}
	
	/**
     * @desc Remove produto para vendedor 
     * @param  $consultor de Cconsultor
     * @param  $produto de Produto
    */
    function remover_vendedor_produto($consultor,$produto){
		//Apaga campo anterior
		$this->carregaDelete("VENDEDOR_PRODUTO", " vendedor = '".$consultor."' and produto = '".$produto."' ",0);
        return 1;
	}
	
	
	
	/**
	 * @desc Verifica validade de campo em tabela
	 * @param integer $id_campo identificação de campo tabela
	 * @param string $valor de valor de campo 
	 * @param integer $chave valor de chave de tabela
	 * @return mensagem de erro de verificação
	*/
	function verifica_validade_campo($id_tabela_campo, $valor, $chave, $name = ""){
		
		$ret = parent::verifica_validade_campo($id_tabela_campo, $valor, $chave, $name);
		
		//Customizações de campos 
		switch ($id_tabela_campo) {
    		//Campo Hot-Line de Venda
    		case 97:
    			//Testa Hot Line Antigo = fechamento
                if ($ret["linha_antiga"]["prospeccao"] == 13)
                {
                	$ret["mensagem"] = 'Contrato Fechado, voce nao pode mudar mais o Hot Line!';
                	return $ret;
                } 
    			
    			if((($valor == 9) || ($valor <= 6) || ($valor >= 13)) && ($valor != -2))
    			{
    				$ret["mensagem"] = 'Hot Line de Telemarketing ou Controle!'; 
                }
                
                //Testa mudança para status em menos de 45 dias
                if (!$this->verificaPermissaoMudarStatus($chave,$_SESSION["navegador_ator"],$valor))
                {
                	$ret["mensagem"] = 'Voce nao pode retornar para este Hot Line!';
                	return $ret;
                } 
                                
                //Testa para fechamento se preecheu dados de cliente (CNPJ e Razão Social)
                //Também deve preencher dados de Contrato caso não exista nenhum cadastrado()
                //Caso exista exibe último dos contratos e permite alterar
                if($valor == 13)
                {
                	$nome = htmlentities($ret["linha_antiga"]["nome"]);
                	$cnpj = $ret["linha_antiga"]["cnpj"];
                	$razao_social = htmlentities($ret["linha_antiga"]["razaosocial"]);
                	$data_contrato = "";
                	$valor_mensalidade = "";
                	$valor_equipamento = "";
                	
                	//Busca dados de ultimo contrato 
                	$dados_contrato = $this->listaContratoCliente($chave);
                	if (is_array($dados_contrato) && count($dados_contrato)){
                		$data_contrato = substr($dados_contrato[0]["data_contrato"],0,10);
                		$valor_mensalidade = number_format($dados_contrato[0]["valor_mensalidade"],2,",","");
                		$valor_equipamento = number_format($dados_contrato[0]["valor_equipamento"],2,",","");
                		$valor_instalacao = number_format($dados_contrato[0]["valor_instalacao"],2,",","");
                		$meses = $dados_contrato[0]["meses"];
                	}
                	
                	$ret["mensagem"] = 'Informe os dados do Contrato Fechado!';
                	$ret["script"] = " var lista_nome = ['cnpj', 'razaosocial', 'contrato', 'mensalidade', 'equipamento', 'instalacao', 'meses']; ";
                	$ret["script"] .= " var lista_tipo = ['text', 'text', 'data', 'moeda', 'moeda', 'moeda', 'inteiro']; ";
                	$ret["script"] .= " var lista_tamanho = ['18', '30', '10', '10', '10', '10', '3']; ";
                	$ret["script"] .= " var lista_maxlength = ['18','50', '10', '10', '10', '10', '10']; ";
                	$ret["script"] .= " var lista_rotulo = ['CNPJ','Razao Social*', 'Data Contrato*', 'Valor Mensalidade*', 'Venda Equipamento*', 'Valor Instalacao', 'Meses Contrato*']; ";
                	$ret["script"] .= " var lista_valor = ['".$cnpj."','".$razao_social."','".$data_contrato."','".$valor_mensalidade."','".$valor_equipamento."','".$valor_instalacao."','".$meses."']; ";
                	$ret["script"] .= " tempY -= 300; ";
                	$ret["script"] .= " tempX -= 300; ";
                	$ret["script"] .= "gera_html_box('container', 'Contrato Cliente: ".$nome."', 1, \"xajax_gravar_fechamento(".$chave.",document.getElementById('razaosocial').value,document.getElementById('cnpj').value,document.getElementById('contrato').value,document.getElementById('mensalidade').value,document.getElementById('equipamento').value,document.getElementById('instalacao').value,document.getElementById('meses').value, '".$name."')\", lista_nome, lista_tipo, lista_tamanho, lista_maxlength, lista_rotulo, lista_valor);";
                }         
			break;
		}
		return $ret;
	}
	
	/**
	 * @desc Gravar campo em tabela
	 * @param integer $id_campo identificação de campo tabela
	 * @param string $valor de valor de campo 
	 * @param integer $chave valor de chave de tabela
	*/
	function gravar_campo($id_tabela_campo, $valor, $chave){
		
		$ret = parent::gravar_campo($id_tabela_campo, $valor, $chave);
	
		//Customizações de campos 
		switch ($id_tabela_campo) {
    		//Campo Grupo de Vendedor
    		case 427:
    			$nome_grupo = $this->nomeDominio(31, $valor);
				if (!$this->vendedor){
					$this->vendedor = new tabela("VENDEDOR");
				}
				$this->vendedor->update(array("grupo"),array("'".$nome_grupo."'"),0, " codigo = '".$chave."'" );
    			
				if (!$this->calu_usuario){
					$this->calu_usuario = new tabela("CALU_USUARIO");
				}
				$this->calu_usuario->update(array("grupo"),array("'".$nome_grupo."'"),0, " tipo in (2,3,6,7) and ator = '".$chave."'" );
				
				
        		break;
        		
        	//Campo Tipo de Vendedor
    		case 365:
				if (!$this->calu_usuario)
				{
					$this->calu_usuario = new tabela("CALU_USUARIO");
				}
				$valor_acesso = 7;
				//Lider
				if ($valor == 4)
				{
					$valor_acesso = 6; 
				}
				//Controle
				if ($valor == 5)
				{
					$valor_acesso = 3; 
				}
				
				$this->calu_usuario->update(array("tipo"),array("'".$valor_acesso."'"),0, " tipo in (2,3,6,7) and ator = '".$chave."'" );
        		break;
        		
        	//Campo Nome de Vendedor
    		case 101:
				if (!$this->calu_usuario){
					$this->calu_usuario = new tabela("CALU_USUARIO");
				}
				$this->calu_usuario->update(array("nome"),array("'".$valor."'"),0, " tipo in (2,3,6,7) and ator = '".$chave."'" );
        		break;

        	//Campo Login de Vendedor
    		case 359:
				if (!$this->calu_usuario){
					$this->calu_usuario = new tabela("CALU_USUARIO");
				}
				$this->calu_usuario->update(array("login"),array("'".$valor."'"),0, " tipo in (2,3,6,7) and ator = '".$chave."'" );
        		break;
        		
        	//Campo Operador de Vendedor
        	case 102:
				if (!$this->calu_usuario){
					$this->calu_usuario = new tabela("CALU_USUARIO");
				}
				$this->calu_usuario->update(array("operador"),array("'".$valor."'"),0, " tipo in (2,3,6,7) and ator = '".$chave."'" );
        		break;
        		
        	//Campo Operador II de Vendedor
        	case 406:
				if (!$this->calu_usuario){
					$this->calu_usuario = new tabela("CALU_USUARIO");
				}
				$this->calu_usuario->update(array("operador_2"),array("'".$valor."'"),0, " tipo in (2,3,6,7) and ator = '".$chave."'" );
        		break;

        	//Campo Suspenso de Vendedor
        	case 347:
				if (!$this->calu_usuario){
					$this->calu_usuario = new tabela("CALU_USUARIO");
				}
				//Apaga usuário suspenso
				if ($valor){
					$this->calu_usuario->delete(0, " tipo in (2,3,6,7) and ator = '".$chave."'" );
				}
				else
				{
					$dados = $this->tabela->carregar("codigo", $chave);
					if ($dados && is_array($dados) && is_array($dados[0]))
					{
						$valor_acesso = 7;
						//Lider
						if ($dados[0]["tipo_consultor"] == 4)
						{
							$valor_acesso = 6; 
						}
						//Controle
						if ($dados[0]["tipo_consultor"] == 5)
						{
							$valor_acesso = 3; 
						}
					}
					$campos = array("id", "login", "senha", "tipo", "ator", "nome", "operador", "operador_2", "grupo", "tela");
					$valores = array($chave,"'".$dados[0]["login"]."'","'1234'",$valor_acesso,$chave,"'".$dados[0]["nome"]."'",$dados[0]["operador"],$dados[0]["operador_2"],"'".$dados[0]["grupo"]."'","'agenda_diaria'");
					$this->calu_usuario->insert($campos, $valores);
				}
        		break;
        		
        	//Campo Prospecção de Cliente
        	case 97:
        		//Preenche data de ultima alteração
        		$this->tabela->update(array("data_altera"), array("getdate()"), $chave);
        		break;
		}
		
		return $ret;
	}
	
	/**
	 * @desc Gravar dados de fechamento de contrato de cliente
	 * @param integer $codigo de codigo de cliente
	 * @param string $razaosocial de razão social 
	 * @param string $cnpj de CNPJ
	 * @param string $contrato de Data de Contrato
	 * @param float $mensalidade de Valor de Mensalidade
	 * @param float $equipamento de Valor de Equipamento
	 * @param float $instalacao de Valor de Instalação
	 * @param int $meses de Meses de Contrato
	*/
	function gravar_contrato($codigo, $razaosocial, $cnpj, $contrato, $mensalidade, $equipamento, $instalacao, $meses){
		global $funcoes;
		$ret = 0;
		
		//Grava dados de cliente (RazaoSocial e CNPJ)
		if (!$this->tmkt)
		{
			$this->tmkt = new tabela("TMKT");
		}
		$ret = $this->tmkt->update(array("razaosocial", "cnpj", "prospeccao", "data_altera"),array("'".$razaosocial."'","'".$cnpj."'","13", "getdate()"),$codigo);
		
		//Procura por ultimo contrato de Cliente
		if ($ret)
		{
			$campos = array("cliente","data_contrato","valor_mensalidade","valor_equipamento", "valor_instalacao", "meses");
			$valores = array($codigo,"'".$funcoes->formataDataBanco($contrato)."'", $funcoes->formataNumeroBanco($mensalidade), $funcoes->formataNumeroBanco($equipamento), $funcoes->formataNumeroBanco($instalacao), $meses);

			$dados_contrato = $this->listaContratoCliente($codigo);
			if (is_array($dados_contrato) && count($dados_contrato))
			{
				$this->contrato->update($campos, $valores, $dados_contrato[0]["codigo"]);
			}
			else
			{
				$this->contrato->insert($campos, $valores);
			}
		}
		return $ret;	
	}	
	
	/**
	 * @desc Calcula comissão de vendedor
	 * @param int $tipo_consultor de Tipo de Consultor que vendeu
	 * @param int $produto de Produto vendido
	 * @param int $meses de Meses de Contrato fechado
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_comissao($tipo_consultor, $produto, $meses, $valor_mesalidade)
	{
		$valor_comissao = 0;
		//Para clientes fechados com Fila Tropa de Elite 
		if ($tipo_fila == 5)
		{
			$valor_comissao = $this->calcula_comissao_tropa_elite($valor_mesalidade);
		}
		else
		{
			//Calcula comissão de acordo com tipo de vendedor
			switch ($tipo_consultor) {
    			//Estágiário
    			case 0:
    				$valor_comissao = $this->calcula_comissao_estagiario($valor_mesalidade, $numero_contrato);
    			break;

    			//Agente Credenciado
    			//Júnior
    			case 1:
    				$valor_comissao = $this->calcula_comissao_agente_junior($valor_mesalidade);
    			break;	
    			//Sênior
    			case 2:
    				$valor_comissao = $this->calcula_comissao_agente_senior($valor_mesalidade);
    			break;	
    			//Premium
    			case 3:
    				$valor_comissao = $this->calcula_comissao_agente_premium($valor_mesalidade);
    			break;	
    			
    			//Consultor
    			//Bronze
    			case 6:
    				$valor_comissao = $this->calcula_comissao_bronze($meses,$valor_mesalidade);
    			break;	
    			//Prata
    			case 7:
    				$valor_comissao = $this->calcula_comissao_prata($meses,$valor_mesalidade);
    			break;	
    			//Ouro
    			case 8:
    				$valor_comissao = $this->calcula_comissao_ouro($meses,$valor_mesalidade);
    			break;
    			//Diamente
    			case 9:
    				$valor_comissao = $this->calcula_comissao_diamante($meses,$valor_mesalidade);
    			break;
			}		
		}
		return number_format($valor_comissao,2,",",""); 
	}
	
	/**
	 * @desc Calcula numero de contratos já fechados por vendedor em mês
	 * @param int $vendedor de Código de Consultor
	 * @param string $data_contrato_atual de Data de Contrato Atual
	 * @return int de Numero de Contratos fechados até o atual
	*/	
	function calcula_numero_contratos_ate_atual($codigo_contrato, $vendedor, $data_contrato_atual)
	{
		global $funcoes;
		$numero_contratos = 0;
		$data_contrato_atual = $funcoes->formataDataBanco($data_contrato_atual);
		if ($dados = $this->carregaDados(" con.codigo, convert(char(11),con.data_contrato,103) as data "," TMKT cli INNER JOIN CONTRATO con on (con.cliente = cli.codigo )  "," (cli.vendedor = '".$vendedor."') and ( con.data_contrato between '".date("Y-m-01",strtotime($data_contrato_atual))."' and '".date("Y-m-d",strtotime($data_contrato_atual))."' ) ", " con.data_contrato, con.codigo ")){
			for ($i = 0; $i < count($dados); $i++) {
				$codigo =  $dados[$i][0];
				$data = $funcoes->formataDataBanco($dados[$i][1]);
				if (($data < $data_contrato_atual) || (($data == $data_contrato_atual) && ($codigo <=  $codigo_contrato))) 
				{
					$numero_contratos++;
				}
			}
		}
		return $numero_contratos; 
	}
	
	
	/**
	 * @desc Calcula comissão de estagiario
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_comissao_estagiario($valor_mesalidade)
	{
		$valor_comissao = 0;
		return $valor_comissao; 
	}
	
	/**
	 * @desc Calcula comissão de Agente Júnior
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_comissao_agente_junior($valor_mesalidade)
	{
		$valor_comissao = 1*$valor_mesalidade;
		return $valor_comissao; 
	}
	
	/**
	 * @desc Calcula comissão de Agente Senior
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_comissao_agente_senior($valor_mesalidade)
	{
		$valor_comissao = 2*$valor_mesalidade;
		return $valor_comissao; 
	}
	
	/**
	 * @desc Calcula comissão de Agente Premium
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_comissao_agente_premium($valor_mesalidade)
	{
		$valor_comissao = 3*$valor_mesalidade;
		return $valor_comissao; 
	}
	
	/**
	 * @desc Calcula comissão de Consultor Bronze
	 * @param int $meses de Meses de Contrato fechado
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_comissao_bronze($meses,$valor_mesalidade)
	{
		// 4% sobre mensalidade
		$valor_comissao = number_format($meses*$valor_mesalidade*0.04,2);
		return $valor_comissao; 
	}
	
	/**
	 * @desc Calcula comissão de Consultor Prata
	 * @param int $meses de Meses de Contrato fechado
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_comissao_prata($meses,$valor_mesalidade)
	{
		// 5% sobre mensalidade
		$valor_comissao = number_format($meses*$valor_mesalidade*0.05,2);
		return $valor_comissao; 
	}
	
	/**
	 * @desc Calcula comissão de Consultor Ouro
	 * @param int $meses de Meses de Contrato fechado
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_comissao_ouro($meses,$valor_mesalidade)
	{
		// 6% sobre mensalidade
		$valor_comissao = number_format($meses*$valor_mesalidade*0.06,2);
		return $valor_comissao; 
	}
	
	/**
	 * @desc Calcula comissão de Consultor Diamante
	 * @param int $meses de Meses de Contrato fechado
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_comissao_diamante($meses,$valor_mesalidade)
	{
		// 7% sobre mensalidade
		$valor_comissao = number_format($meses*$valor_mesalidade*0.07,2);
		return $valor_comissao; 
	}
	
	
	/**
	 * @desc Calculo de Comissão de Tropa de Elite
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_comissao_tropa_elite($valor_mesalidade)
	{
		$valor_comissao = 0;
		return $valor_comissao; 
	}
	
	/**
	 * @desc Calcula prêmio de vendedor
	 * @param int $tipo_consultor de Tipo de Consultor que vendeu
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_premio($codigo_contrato, $vendedor, $produto, $data_contrato)
	{
		$valor_premio = 0;
		$numero_contrato = $this->calcula_numero_contratos_ate_atual($codigo_contrato, $vendedor, $data_contrato);
		//Para clientes fechados com Fila Tropa de Elite 
		if ($tipo_fila == 5)
		{
			$valor_premio = $this->calcula_premio_tropa_elite($valor_mesalidade);
		}
		else
		{
			//Calcula comissão de acordo com tipo de vendedor
			switch ($tipo_consultor) {
    			//Estágiário
    			case 0:
    				$valor_premio = $this->calcula_premio_estagiario($numero_contrato);
    			break;
    			//Agente Credenciado
    			//Júnior, Sênior, Prêmium
    			case 1:
    			case 2:
    			case 3:
    				$valor_premio = $this->calcula_premio_agente($numero_contrato);
    			break;	
    			
    			//Consultor
    			//Bronze, Prata, Ouro, Diamante
    			case 6: 
    			case 7: 
    			case 8: 
    			case 9:
    				$valor_premio = $this->calcula_premio_consultor_clt($produto, $numero_contrato);
    			break;	
			}		
		}
		return number_format($valor_premio,2,",",""); 
	}
	
	/**
	 * @desc Calcula premio de estagiario
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_premio_estagiario($numero_contrato)
	{
		$valor_premio = 0;
		switch ($numero_contrato) {
    		//Estágiário
    		case 3:
    			$valor_premio = 360;
    			break;
    		case 4:
    			$valor_premio = 240;
    			break;
    		case 5:
    			$valor_premio = 200;
    			break;
    		case 6:
    			$valor_premio = 300;
    			break;
    		default:
    			$valor_premio = 0;	
		}
		
		return $valor_premio; 
	}
	
	/**
	 * @desc Calcula Prêmio de Agente Júnior, Sênior e Premium
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_premio_agente($numero_contrato)
	{
		$valor_premio = 0;
		switch ($numero_contrato) {
    		case 3:
    			$valor_premio = 300;
    			break;
    		case 4:
    			$valor_premio = 100;
    			break;
    		case 5:
    			$valor_premio = 100;
    			break;
    		case 6:
    			$valor_premio = 100;
    			break;
    		default:
    			$valor_premio = 0;	
		}
		return $valor_premio;  
	}

	/**
	 * @desc Calcula Prêmio de Consultor Bronze, Prata, Ouro, Diamente
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_premio_consultor_clt($produto, $numero_contrato)
	{
		$valor_premio = 0;
		switch ($produto) {
    		//Dayli Care
    		case 3:
				if ($numero_contrato < 4)
				{
					$valor_premio = 0;
				}
				elseif ($numero_contrato <= 8)
				{
					$valor_premio = 100; 
				} 
				elseif ($numero_contrato > 8)
				{
					$valor_premio = 0; 
				}
    			break;
    		//Manutenção e Semax View	
    		case 1:
    		case 5:
				if ($numero_contrato < 4)
				{
					$valor_premio = 0;
				}
				elseif ($numero_contrato = 4)
				{
					$valor_premio = 400;
				}
				elseif ($numero_contrato <= 7)
				{
					$valor_premio = 100; 
				} 
				elseif ($numero_contrato > 7)
				{
					$valor_premio = 0; 
				}
    			break;
    		default:
    			$valor_premio = 0;	
		}
		
		return $valor_premio;  
	}
	
	/**
	 * @desc Calculo de Prêmio de Tropa de Elite
	 * @param float $valor_mensalidade de Valor de Mensalidade
	 * @return string Valor de Comissão
	*/	
	function calcula_premio_tropa_elite($valor_mesalidade)
	{
		return 0; 
	}
	
	/**
	 * @desc Calculo de Comissão Estimada de Equipamentos
	 * @param int $tipo_vendedor de Tipo de Vendedor
	 * @param float $valor_equipamento de Valor de Equipamentos
	 * @return string Valor de Comissão Estima de Equipamentos
	*/
	function calcula_comissao_equipamento($tipo_vendedor, $valor_equipamento)
	{
		return number_format(($tipo_vendedor?$valor_equipamento*0.05:0),2,",","");
	}
	
	/**
     * @desc Consulta de CEP Republica Virtual 
     * @param string $cep contendo o CEP
     * @return array $ret com colunas "uf", "cidade", "bairro", "tipoLogradouro", "logradouro"
     * @see http://republicavirtual.com.br/cep/index.php 
    **/
    function getCepRepublicaVirtual($cep) {
    	$ret = array();
        //Verifica formato $cep
        if (!$cep && !is_string($cep)){
        	$ret["erro"] = "Formato de CEP inválido para busca.";
        	return $ret;    
        }

        //República Virtual, busca de CEP 
        $link = "http://cep.republicavirtual.com.br/web_cep.php?cep=".$cep."&formato=xml";
        $conteudo = @file_get_contents($link);
        if($conteudo === FALSE) {
            $ret["erro"] = "Erro em acesso a url.";
            return $ret;    
        } 
        
        $xmlObj = simplexml_load_string($conteudo);
        
        if (is_object($xmlObj) && $xmlObj->resultado){
            $ret = array("uf" => $xmlObj->uf, 
                         "cidade" => $xmlObj->cidade, 
                         "bairro" => $xmlObj->bairro, 
                         "tipoLogradouro" => $xmlObj->tipo_logradouro,
                         "logradouro" => $xmlObj->logradouro);
        }
        return $ret;
    }
}