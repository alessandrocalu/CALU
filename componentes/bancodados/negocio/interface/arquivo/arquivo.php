<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Template para Interface arquivo CSV
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 05/08/2008 Atualizado: 05/08/2008
*/
header('Content-type: application/force-download');
header('Content-Disposition: attachment; filename="arquivo.csv"');
$arquivo = new arquivoTxt("CSV");

$sumarizado = ($_GET["sumarizado"]?$_GET["sumarizado"]:$_POST["sumarizado"]);
//Relatório comum
if (!$sumarizado)
{
	$campos_tela =  $negocio->getCamposTela("tabela");
	$colunas = array();
	for ($i = 0; $i < count($campos_tela);$i++){
		$colunas[0][] = $campos_tela[$i]["rotulo"];
	}
	$dados_consulta = $negocio->getDadosTela();
	for ($i = 0; $i < count($dados_consulta);$i++){
		for ($j = 0; $j < count($campos_tela);$j++){
			$colunas[$i+1][$j] = $dados_consulta[$i][$j]["valor"];
		}	
	}
	$arquivo->escreveCSV($colunas);
}
//Relatório simarizado
else
{
	$campos_tela =  $negocio->getCamposTela();
	$campos_totalsuper = array();
	$num_campos_totalsuper = 0;
	$total_campos_totalsuper = 0;
	$total_campos_tabela = 0; 
	$total_campos_super_tabela = 0; 


	//Conta campos de tabela de listagem
	for ($i = 0; $i < count($campos_tela); $i++){
		if ($campos_tela[$i]["grupo"] == "tabela") {
			$total_campos_tabela++;
		}
    	if ($campos_tela[$i]["grupo"] == "tabelasuper") {
			$total_campos_super_tabela++;
		}
	
		if ($campos_tela[$i]["grupo"] == "totalsuper") {
			$campos_totalsuper[$num_campos_totalsuper] = $campos_tela[$i];
			$campos_totalsuper[$num_campos_totalsuper]["total"] = 0; 
			$campos_totalsuper[$num_campos_totalsuper]["total_geral"] = 0; 
			$num_campos_totalsuper++;
			$total_campos_totalsuper = $num_campos_totalsuper;
		}
	}

	$dados_consulta = $negocio->getDadosTela();
	$total_dados = count($dados_consulta);

	if ($dados_consulta) 
	{ 
		$campo_valor = array();
		for ($i = 0; $i < count($dados_consulta); $i++)
		{ 
			//Identifica campo super
			for ($super = 0; ($super < count($dados_consulta[$i]) && !$campo_super) ; $super++){ 
				if ($dados_consulta[$i][$super]["grupo"] == "tabelasuper") {
					$campo_super = $super;
				}
			}
			
			//Desenha campo super
			if (($total_campos_super_tabela > 0) && ($valor_campo_super != $dados_consulta[$i][$campo_super]["valor"])) 
			{
				if ($i && $total_campos_totalsuper)
				{
					$num_coluna = 0;
					$legend = array();
					$data = array();
					//Imprime Sub Totais
					for ($num_campos_totalsuper = 0; $num_campos_totalsuper < $total_campos_totalsuper; $num_campos_totalsuper++)
					{
						if ($campos_totalsuper[$num_campos_totalsuper]["total"])
						{
							$legend[] = $campos_totalsuper[$num_campos_totalsuper]["rotulo"];
							$data[] = $campos_totalsuper[$num_campos_totalsuper]["total"];
							echo $campos_totalsuper[$num_campos_totalsuper]["rotulo"].";"; 
							echo $campos_totalsuper[$num_campos_totalsuper]["total"].";";
							$num_coluna++;
							if ($num_coluna >= 5)
							{
								echo "\n";
								$num_coluna = 0;
							}
						}	
					}
					if ($num_coluna < 5 && $num_coluna > 0) 
					{
						echo ";";
					}
					echo "\n";
				}
				echo $dados_consulta[$i][$campo_super]["rotulo"].": ".$dados_consulta[$i][$campo_super]["valor"]."\n";
				$valor_campo_super = $dados_consulta[$i][$campo_super]["valor"];
			
				//Zera Totais de campo super
				for ($num_campos_totalsuper = 0; $num_campos_totalsuper < $total_campos_totalsuper; $num_campos_totalsuper++)
				{
					$campos_totalsuper[$num_campos_totalsuper]["total"] = 0;
				}
			}
			
			for ($j = 0; $j < count($dados_consulta[$i]); $j++){ 
				if ($dados_consulta[$i][$j]["grupo"] == "tabela")  
				{
					if ($dados_consulta[$i][$j]["nome_comportamento"] == "totalHora")
					{
						$valor = explode(":",$dados_consulta[$i][$j]["valor"]);
						$total = 0;
						if (is_array($valor) && $valor[0] && $valor[1])
						{
							$total = ($valor[0]*60)+$valor[1];
						}
						if ($dados_consulta[0][$j]["total"])
						{
							$dados_consulta[0][$j]["total"] += $total; 
						}
						else
						{
							$dados_consulta[0][$j]["total"] = $total; 
						}	
						$tem_total = 1;
					}
					if ($dados_consulta[$i][$j]["nome_comportamento"] == "total")
					{
						if (!$dados_consulta[0][$j]["total"])
						{
							$dados_consulta[0][$j]["total"] = 0; 
						}
						$dados_consulta[0][$j]["total"]++; 
						$tem_total = 1;
					}
					
					//Acumula totais de campo super
					for ($num_campos_totalsuper = 0; $num_campos_totalsuper < $total_campos_totalsuper; $num_campos_totalsuper++){
						if (($campos_totalsuper[$num_campos_totalsuper]["link"]  == $dados_consulta[$i][$j]["nome"] ) &&
						    ($campos_totalsuper[$num_campos_totalsuper]["elemento"]  == $dados_consulta[$i][$j]["valor"] )){
							$campos_totalsuper[$num_campos_totalsuper]["total"] = $campos_totalsuper[$num_campos_totalsuper]["total"]+1;
							$campos_totalsuper[$num_campos_totalsuper]["total_geral"] = $campos_totalsuper[$num_campos_totalsuper]["total_geral"]+1;
						}	
					}
				}
			}	
		}
		
		
		//Desenha ultima linha de sub.total 
		if ($i && $total_campos_totalsuper)
		{
			$num_coluna = 0;
			$legend = array();
			$data = array();
			//Imprime Sub Totais
			for ($num_campos_totalsuper = 0; $num_campos_totalsuper < $total_campos_totalsuper; $num_campos_totalsuper++){
				if ($campos_totalsuper[$num_campos_totalsuper]["total"])
				{
					$legend[] = $campos_totalsuper[$num_campos_totalsuper]["rotulo"];			
					$data[] = $campos_totalsuper[$num_campos_totalsuper]["total"];
					echo $campos_totalsuper[$num_campos_totalsuper]["rotulo"].";"; 
					echo $campos_totalsuper[$num_campos_totalsuper]["total"].";"; 
					$num_coluna++;
					if ($num_coluna >= 5)
					{
						echo "\n";
						$num_coluna = 0;
					}
				}	
			}
			if ($num_coluna < 5 && $num_coluna > 0) 
			{
				echo ";";
			}
			echo "\n";
		}
		
		//Desenha linha de totais
		if ($tem_total)
		{
			for ($i = 0; $i < count($dados_consulta[0]); $i++)
			{ 
				if (($dados_consulta[0][$i]["nome_comportamento"] != "totalHora") &&
					($dados_consulta[0][$i]["nome_comportamento"] != "total") &&
					($dados_consulta[0][$i]["grupo"] == "tabela") )
				{
					if($i==0)
					{
						echo "Total:;" ;
					}
					elseif ($i>1)
					{
						echo ";";
					}		
				}
				elseif ($dados_consulta[0][$i]["nome_comportamento"] == "totalHora")
				{
					$hora = floor($dados_consulta[0][$i]["total"]/60); 
					$minutos = ($dados_consulta[0][$i]["total"] - ($hora*60));	
					if ($hora < 10)
					{
						$hora = '0'.$hora; 
					}
					if ($minutos < 10)
					{
						$minutos = '0'.$minutos; 
					}
					echo $hora.":".$minutos;
				} 
				elseif ($dados_consulta[0][$i]["nome_comportamento"] == "total")
				{
					echo $dados_consulta[0][$i]["total"];
				} 
			}
			echo "\n";
		}

		//Desenha linha de total geral 
		
		echo "Total:\n";
		if ($i && $total_campos_totalsuper){
			//Imprime total geral
			$num_coluna = 0;
			$legend = array();
			$data = array();
			//Imprime Sub Totais
			for ($num_campos_totalsuper = 0; $num_campos_totalsuper < $total_campos_totalsuper; $num_campos_totalsuper++)
			{
				if ($campos_totalsuper[$num_campos_totalsuper]["total_geral"])
				{
					$legend[] = $campos_totalsuper[$num_campos_totalsuper]["rotulo"];
					$data[] = $campos_totalsuper[$num_campos_totalsuper]["total_geral"]; 
					echo $campos_totalsuper[$num_campos_totalsuper]["rotulo"].";"; 
					echo $campos_totalsuper[$num_campos_totalsuper]["total_geral"].";"; 
					$num_coluna++;
					if ($num_coluna >= 5)
					{
						echo "\n";
						$num_coluna = 0;
					}
				}	
			}
			if ($num_coluna < 5 && $num_coluna > 0) 
			{
				echo ";";
			}
		}
	}	 
}	