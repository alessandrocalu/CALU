<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista de Filas)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_vendedor_fila extends negocio_custom {
	
	/**
	 * @desc Realiza customização de Adicão de consultor em fila
	*/
    function insert_lista_vendedor_fila(){
    	global $funcoes;
    	
		if ($_GET["insert"] == 'adicionar')
        {
        	unset($_GET["insert"]);
        	
        	//Verifica campos 
        	if (!$_POST["consultor"])
        	{
        		$this->mensagem = "Escolha um consultor para adiciona-lo a fila!";
        		return 0;
        	}
        	if (!$_POST["fila"])
        	{
        		$this->mensagem = "Escolha a fila a qual deseja adicinar consultor!";
        		return 0;
        	}
        
        	$num_fila = $_POST["fila"];
        	$consultor = $_POST["consultor"]; 
        	$dados_fila = $this->getFila();
        	$lista_fila = $dados_fila["fila"][$num_fila-1]["lista"];
        	
        	//Verifica se Consultor já existe em fila
        	if ($this->is_consultor_em_fila($consultor, $lista_fila))
        	{
        		$this->mensagem = "Consultor já está nesta fila!";
        		return 0;
        	}
        	
        	//Reorganiza nomeros de fila
        	$nome_campo_controle = $this->get_campo_controle_fila($num_fila);
        	$nome_campo_proximo = $this->get_campo_proximo_fila($num_fila);
        	$camposFiltros = array("codigo");
        	$total = count($lista_fila);
        	for ($i = 0; $i < $total; $i++)
        	{
        		$ordem_controle = $i+1;
        		$campos = array($nome_campo_controle);
        		$valores = array($ordem_controle);
        		$valoresFiltros = array($lista_fila[$i]["codigo"]);
        		
        		//Grava nova ordem de fila 
        		$this->consulta->consultaUpdate($campos, $valores, $camposFiltros, $valoresFiltros);
        	}
            
        	//Adiciona novo consultor em final de fila
        	$ordem_controle = $total+1;
        	$campos = array($nome_campo_controle);
        	$valores = array($ordem_controle);
        	
        	//Se for unico da fila coloca proximo
        	if ($ordem_controle == 1)
        	{
        		$campos = array($nome_campo_controle, $nome_campo_proximo);
        		$valores = array($ordem_controle, '1');
        	}
        	
        	//Grava nova ordem de fila 
        	$valoresFiltros = array($consultor);
        	$this->consulta->consultaUpdate($campos, $valores, $camposFiltros, $valoresFiltros);
			$this->mensagem = "Consultor adicionado em fila, verifique se a posição do consultor esta correta na fila!";
			return 1;
        }
		return 0;
	}
	
	/**
	 * @desc Realiza customização de Edição de consultor em fila
	*/
    function update_lista_vendedor_fila(){
    	global $funcoes;

    	if ($_GET["update"] == 'rodar')
        {
        	unset($_GET["update"]);
        	
        	//Verifica campos 
        	if (!$_POST["consultor"])
        	{
        		$this->mensagem = "Escolha um consultor para rolar fila!";
        		return 0;
        	}
        	if (!$_POST["fila"])
        	{
        		$this->mensagem = "Escolha a fila a qual deseja rolar!";
        		return 0;
        	}
        
        	$num_fila = $_POST["fila"];
        	$consultor = $_POST["consultor"]; 
        	$dados_fila = $this->getFila();
        	$lista_fila = $dados_fila["fila"][$num_fila-1]["lista"];
        	
        	$nome_campo_proximo = $this->get_campo_proximo_fila($num_fila);
        	$campos = array($nome_campo_proximo);
        	
        	if (count($lista_fila) == 1)
        	{
        		$this->mensagem = "Fila só possui um consultor, este consultor foi marcado como próximo!";
        		
        		$valores = array('1');
				$camposFiltros = array("codigo");
            	$valoresFiltros = array($consultor);
            	$this->consulta->consultaUpdate($campos,$valores,$camposFiltros, $valoresFiltros);
        		return 0;
        	}
        	
        	if (count($lista_fila) > 1)
        	{
	        	//Tira proximo de todos os consultores
	        	$valores = array('0');
				$camposFiltros = array("1");
	            $valoresFiltros = array("1");
	            $this->consulta->consultaUpdate($campos,$valores,$camposFiltros, $valoresFiltros);
	            
	            //Marca proximo em segundo consultor
	        	$valores = array('1');
				$camposFiltros = array("codigo");
	            $valoresFiltros = array($lista_fila[1]["codigo"]);
            	$this->consulta->consultaUpdate($campos,$valores,$camposFiltros, $valoresFiltros);
            	$this->mensagem = "Fila rolada!";
        	}	
            
        }

        
      	if ($_GET["update"] == 'subir')
        {
        	unset($_GET["update"]);
        	
        	//Verifica campos 
        	if (!$_POST["consultor"])
        	{
        		$this->mensagem = "Escolha um consultor para subir sua ordem na fila!";
        		return 0;
        	}
        	if (!$_POST["fila"])
        	{
        		$this->mensagem = "Escolha a fila a qual deseja subir ordem de consultor!";
        		return 0;
        	}
        	
        	//Procura posição de consultor em fila
        	$num_fila = $_POST["fila"];
        	$consultor = $_POST["consultor"]; 
        	$dados_fila = $this->getFila();
        	$lista_fila = $dados_fila["fila"][$num_fila-1]["lista"];
        	$posicao_consultor = $this->get_posicao_consultor_fila($consultor, $lista_fila);
        	
        	if ($posicao_consultor == -1)
        	{
        		$this->mensagem = "Consultor não encontrado em fila!";
        		return 0;
        	}
			if (!$posicao_consultor)
        	{
        		$this->mensagem = "Consultor é primeiro de fila!";
        		return 0;
        	}
        	        	
        	//Muda ordem de consultor
        	$nome_campo_controle = $this->get_campo_controle_fila($num_fila);
        	$campos = array($nome_campo_controle);
        	
        	$valores = array($lista_fila[$posicao_consultor-1]["ordem"]);
			$camposFiltros = array("codigo");
            $valoresFiltros = array($consultor);
            $this->consulta->consultaUpdate($campos,$valores,$camposFiltros, $valoresFiltros);
            
            //Muda ordem de consultor anterior
        	$valores = array($lista_fila[$posicao_consultor]["ordem"]);
			$camposFiltros = array("codigo");
            $valoresFiltros = array($lista_fila[$posicao_consultor-1]["codigo"]);
            $this->consulta->consultaUpdate($campos,$valores,$camposFiltros, $valoresFiltros);
            $this->mensagem = "Ordem de consultor modificada!";
        }
        
        if ($_GET["update"] == 'descer')
        {
        	unset($_GET["update"]);
        	
        	//Verifica campos 
        	if (!$_POST["consultor"])
        	{
        		$this->mensagem = "Escolha um consultor para descer sua ordem na fila!";
        		return 0;
        	}
        	if (!$_POST["fila"])
        	{
        		$this->mensagem = "Escolha a fila a qual deseja subir ordem de consultor!";
        		return 0;
        	}
        	
        	//Procura posição de consultor em fila
        	$num_fila = $_POST["fila"];
        	$consultor = $_POST["consultor"]; 
        	$dados_fila = $this->getFila();
        	$lista_fila = $dados_fila["fila"][$num_fila-1]["lista"];
        	$posicao_consultor = $this->get_posicao_consultor_fila($consultor, $lista_fila);
        	
        	if ($posicao_consultor == -1)
        	{
        		$this->mensagem = "Consultor não encontrado em fila!";
        		return 0;
        	}
			if ($posicao_consultor == (count($lista_fila)-1))
        	{
        		$this->mensagem = "Consultor é último de fila!";
        		return 0;
        	}
        	        	
        	//Muda ordem de consultor
        	$nome_campo_controle = $this->get_campo_controle_fila($num_fila);
        	$campos = array($nome_campo_controle);
        	
        	$valores = array($lista_fila[$posicao_consultor+1]["ordem"]);
			$camposFiltros = array("codigo");
            $valoresFiltros = array($consultor);
            $this->consulta->consultaUpdate($campos,$valores,$camposFiltros, $valoresFiltros);
            
            //Muda ordem de consultor anterior
        	$valores = array($lista_fila[$posicao_consultor]["ordem"]);
			$camposFiltros = array("codigo");
            $valoresFiltros = array($lista_fila[$posicao_consultor+1]["codigo"]);
            $this->consulta->consultaUpdate($campos,$valores,$camposFiltros, $valoresFiltros);
            $this->mensagem = "Ordem de consultor modificada!";
        }
        
    	return 0;
    }

	/**
	 * @desc Realiza customização de remover consultor de fila
	*/
    function delete_lista_vendedor_fila(){
    	global $funcoes;
		$vars = $_POST;
		
		if ($_GET["delete"] == 'remover')
        {
			unset($_GET["delete"]);

			//Verifica campos 
        	if (!$_POST["consultor"])
        	{
        		$this->mensagem = "Escolha um consultor para remove-lo a fila!";
        		return 0;
        	}
        	if (!$_POST["fila"])
        	{
        		$this->mensagem = "Escolha a fila da qual deseja remover consultor!";
        		return 0;
        	}
			
        	$num_fila = $_POST["fila"];
        	$consultor = $_POST["consultor"]; 
        	$dados_fila = $this->getFila();
        	$lista_fila = $dados_fila["fila"][$num_fila-1]["lista"];
      	
        	//Verifica se Consultor já existe em fila
        	if (!$this->is_consultor_em_fila($consultor, $lista_fila))
        	{
        		$this->mensagem = "Consultor não está nesta fila!";
        		return 0;
        	}
        	
        	//Verifica se Consultor é proximo de fila caso fila tenha mais de um consultor
        	if ((count($lista_fila) > 1) &&  ($this->is_consultor_proximo_em_fila($consultor, $lista_fila)))
        	{
        		$this->mensagem = "Consultor é proximo de fila, não pode ser removido dela!";
        		return 0;
        	}
        	
        	//Remove consultor de fila
        	$nome_campo_controle = $this->get_campo_controle_fila($num_fila);
        	$nome_campo_proximo = $this->get_campo_proximo_fila($num_fila);
        	$campos = array($nome_campo_controle, $nome_campo_proximo);
        	$valores = array('0', '0');
			$camposFiltros = array("codigo");
            $valoresFiltros = array($consultor);
            $this->consulta->consultaUpdate($campos,$valores,$camposFiltros, $valoresFiltros);
            
            
            
        	//Reorganiza nomeros de fila
        	$dados_fila = $this->getFila();
        	$lista_fila = $dados_fila["fila"][$num_fila-1]["lista"];
        	$total = count($lista_fila);
        	for ($i = 0; $i < $total; $i++)
        	{
        		$ordem_controle = $i+1;
        		$campos = array($nome_campo_controle);
        		$valores = array($ordem_controle);
        		$valoresFiltros = array($lista_fila[$i]["codigo"]);
        		
        		//Grava nova ordem de fila 
        		$this->consulta->consultaUpdate($campos, $valores, $camposFiltros, $valoresFiltros);
        	}
			 
			$this->mensagem = "Consultor removido de fila, verifique se fila esta correta!";
			return 1;
 		}
	}
	
	/**
	 * @desc Retorna lista de filas 
	 * @param void 
	 * @return array $ret de filas
	*/
	function getFila()
	{
		global $funcoes;
		
		//Lista de Consultores com dados de fila
		$dados_consulta = $this->consulta->consultaSelect(0);
		$total_dados = count($dados_consulta);
		$fila_1 = array();
		$fila_2 = array();
		$fila_3 = array();
		$fila_4 = array();
		$fila_5 = array();
		
		//Preenche filas 
		for ($i = 0; $i < $total_dados; $i++) 
		{
			if ($dados_consulta[$i]['Ved_Fila_Pas'])
			{
				$fila_1[$dados_consulta[$i]['Ved_Fila_Pas']] = array("consultor" => $dados_consulta[$i]['nome'], "codigo" => $dados_consulta[$i]['codigo'], "proximo" =>  $dados_consulta[$i]['proximo'], "ordem" => $dados_consulta[$i]['Ved_Fila_Pas']); 
			}
			
			if ($dados_consulta[$i]['Ved_Fila_CFTV'])
			{
				$fila_2[$dados_consulta[$i]['Ved_Fila_CFTV']] = array("consultor" => $dados_consulta[$i]['nome'], "codigo" => $dados_consulta[$i]['codigo'], "proximo" =>  $dados_consulta[$i]['proximo_CFTV'], "ordem" => $dados_consulta[$i]['Ved_Fila_CFTV']); 
			}

			if ($dados_consulta[$i]['Ved_Fila_Obr'])
			{
				$fila_3[$dados_consulta[$i]['Ved_Fila_Obr']] = array("consultor" => $dados_consulta[$i]['nome'], "codigo" => $dados_consulta[$i]['codigo'], "proximo" =>  $dados_consulta[$i]['proximo_Obr'], "ordem" => $dados_consulta[$i]['Ved_Fila_Obr']); 
			}
			
			if ($dados_consulta[$i]['Ved_Fila_Equip'])
			{
				$fila_4[$dados_consulta[$i]['Ved_Fila_Equip']] = array("consultor" => $dados_consulta[$i]['nome'], "codigo" => $dados_consulta[$i]['codigo'], "proximo" =>  $dados_consulta[$i]['proximo_Equip'], "ordem" => $dados_consulta[$i]['Ved_Fila_Equip']); 
			}
			
			if ($dados_consulta[$i]['Ved_Fila_Lev'])
			{
				$fila_5[$dados_consulta[$i]['Ved_Fila_Lev']] = array("consultor" => $dados_consulta[$i]['nome'], "codigo" => $dados_consulta[$i]['codigo'], "proximo" =>  $dados_consulta[$i]['proximo_Lev'], "ordem" => $dados_consulta[$i]['Ved_Fila_Lev']); 
			}
		}	
	
		$nomes_filas = $this->buscaDadosDominio(8);
		$total_filas = 5;
		
		//[i][nome]
		//[i][j][consultor]
		$fila = array();
		$total_linhas_fila = 0;
		
		//Monta fila 1
		//Nome
		$fila[0]["nome"] = $nomes_filas[1]["codigo"]." - ".$nomes_filas[1]["nome"];
		//Lista
		$fila[0]["lista"] = array();
		$fila[0]["lista"] = $this->ordena_fila($fila_1);
		$total_linhas_fila = count($fila_1);
		
		//Monta fila 2
		//Nome
		$fila[1]["nome"] = $nomes_filas[2]["codigo"]." - ".$nomes_filas[2]["nome"];
		//Lista
		$fila[1]["lista"] = array();
		$fila[1]["lista"] = $this->ordena_fila($fila_2);
		$total_linhas_fila = ($total_linhas_fila < count($fila_2)?count($fila_2):$total_linhas_fila);
		
		//Monta fila 3
		//Nome
		$fila[2]["nome"] = $nomes_filas[3]["codigo"]." - ".$nomes_filas[3]["nome"];
		//Lista
		$fila[2]["lista"] = array();
		$fila[2]["lista"] = $this->ordena_fila($fila_3);
		$total_linhas_fila = ($total_linhas_fila < count($fila_3)?count($fila_3):$total_linhas_fila);
		
		//Monta fila 4
		//Nome
		$fila[3]["nome"] = $nomes_filas[4]["codigo"]." - ".$nomes_filas[4]["nome"];
		//Lista
		$fila[3]["lista"] = array();
		$fila[3]["lista"] = $this->ordena_fila($fila_4);
		$total_linhas_fila = ($total_linhas_fila < count($fila_4)?count($fila_4):$total_linhas_fila);
		
		//Monta fila 5
		//Nome
		$fila[4]["nome"] = $nomes_filas[5]["codigo"]." - ".$nomes_filas[5]["nome"];
		//Lista
		$fila[4]["lista"] = array();
		$fila[4]["lista"] = $this->ordena_fila($fila_5);
		$total_linhas_fila = ($total_linhas_fila < count($fila_5)?count($fila_5):$total_linhas_fila);
		
		
		$ret["fila"] = $fila;
		$ret["total_filas"] = $total_filas;
		$ret["total_linhas_fila"] = $total_linhas_fila;
		
		return $ret;
	}
	
	/**
	 * @desc Ordena Fila 
	 * @param array $fila de fila desordenada com informação de proximo 
	 * @return array $ret de filas ordenadas de acordo com proxoimo
	*/
	function ordena_fila($fila)
	{
		$ret = array();
		
		ksort($fila);
		
		$antes = array();
		$depois = array();
		$proximo = false;
		
		reset($fila);
		foreach( $fila as $key => $value)
		{	
			if ($value['proximo'])
			{
    			$proximo = true;
  			}
  			if ($proximo)
  			{
    			$antes[] = $value; 
  			} else 
  			{
    			$depois[] = $value;
  			}
		}
		for ($i = 0; $i < count($antes); $i++)
		{
			$ret[] = $antes[$i];
		}	
		for ($i = 0; $i < count($depois); $i++)
		{
			$ret[] = $depois[$i];
		}	
		return $ret;
	}

	/**
	 * @desc Verifica se consultor esta em fila 
	 * @param interger $consultor de codigo de consultor
	 * @param array $lista_fila de fila 
	 * @return boolean se consultor pertence a fial
	*/
	function is_consultor_em_fila($consultor, $lista_fila)
	{
		$ret = false;
		$total = count($lista_fila);
        for ($i = 0; $i < $total; $i++)
        {
       		if ( $consultor == $lista_fila[$i]["codigo"])
       		{
       			return true;
       		}
       	}
		return $ret;
	}
	
	/**
	 * @desc Retorna posição de consultor em fila 
	 * @param interger $consultor de codigo de consultor
	 * @param array $lista_fila de fila 
	 * @return interger se consultor pertence a fial
	*/
	function get_posicao_consultor_fila($consultor, $lista_fila)
	{
		$ret = -1;
		$total = count($lista_fila);
        for ($i = 0; $i < $total; $i++)
        {
       		if ( $consultor == $lista_fila[$i]["codigo"])
       		{
       			return $i;
       		}
       	}
		return $ret;
	}
	
	/**
	 * @desc Verifica se consultor é o próximo da fila 
	 * @param interger $consultor de codigo de consultor
	 * @param array $lista_fila de fila 
	 * @return boolean se consultor pertence a fial
	*/
	function is_consultor_proximo_em_fila($consultor, $lista_fila)
	{
		$ret = false;
		$total = count($lista_fila);
        for ($i = 0; $i < $total; $i++)
        {
       		if ( $consultor == $lista_fila[$i]["codigo"])
       		{
       			if ($lista_fila[$i]["proximo"])
       			{
       				return true;
       			}
       			else
       			{
       				return false;
       			}
       		}
       	}
		return $ret;
	}

	/**
	 * @desc Retorna nome de campo controle de fila 
	 * @param interger $num_fila de numero de fila desejada 
	 * @return string $ret de nome de campo de controle de fila
	*/
	function get_campo_controle_fila($num_fila)
	{
		$ret = "";
		switch ($num_fila) {
    		//Fila 1
    		case 1:
    			$ret = "Ved_Fila_Pas"; 
    		break;

    		//Fila 2
    		case 2:
    			$ret = "Ved_Fila_CFTV"; 
    		break;
    		
    		//Fila 3
    		case 3:
    			$ret = "Ved_Fila_Obr"; 
    		break;
    		
    		//Fila 4
    		case 4:
    			$ret = "Ved_Fila_Equip"; 
    		break;

    		//Fila 5
    		case 5:
    			$ret = "Ved_Fila_Lev"; 
    		break;
		}	
		
		return $ret;
	}
	
	/**
	 * @desc Retorna nome de campo proximo de fila 
	 * @param interger $num_fila de numero de fila desejada 
	 * @return string $ret de nome de campo de controle de fila
	*/
	function get_campo_proximo_fila($num_fila)
	{
		$ret = "";
		switch ($num_fila) {
    		//Fila 1
    		case 1:
    			$ret = "proximo"; 
    		break;

    		//Fila 2
    		case 2:
    			$ret = "proximo_CFTV"; 
    		break;
    		
    		//Fila 3
    		case 3:
    			$ret = "proximo_Obr"; 
    		break;
    		
    		//Fila 4
    		case 4:
    			$ret = "proximo_Equip"; 
    		break;

    		//Fila 5
    		case 5:
    			$ret = "proximo_Lev"; 
    		break;
		}	
		
		return $ret;
	}
}