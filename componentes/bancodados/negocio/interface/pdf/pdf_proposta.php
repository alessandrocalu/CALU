<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Template para Interface de geração de relatórios PDF de listagem
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 18/01/2008 Atualizado: 18/01/2008
*/
  //Verificador de sessão
//  session_start();

  $pdf = new Cezpdf('a4','portrait');
  $pdf->selectFont("./componentes/pdf/fonts/Helvetica.afm");
  
/*  $campos_tela =  $negocio->getCamposTela();
  
  for ($i=0;$i < sizeOf($campos_tela);$i++){
	  $colunas[] = $campos_tela[$i]["rotulo"];
	  $cols[]["width"] = $campos_tela[$i]["tamanho"];
  }
  
  $dados_consulta = $negocio->getDadosTela();
    
  if ($dados_consulta) { 
	$linha = 0;
	for ($i = 0; $i < count($dados_consulta); $i++){ 
		for ($j=0;$j < sizeOf($campos_tela);$j++){
			$lista[$i][] = $dados_consulta[$i][$j]["valor"];

			if ($dados_consulta[$i][$j]["nome_comportamento"] == "totalHora"){
				$valor = explode(":",$dados_consulta[$i][$j]["valor"]);
				$total = 0;
				if (is_array($valor) && $valor[0] && $valor[1]){
					$total = ($valor[0]*60)+$valor[1];
				}

				if ($dados_consulta[0][$j]["total"]){
					$dados_consulta[0][$j]["total"] += $total; 
				}
				else
				{
					$dados_consulta[0][$j]["total"] = $total; 
				}	
				$tem_total = 1;
			}
			elseif ($dados_consulta[$i][$j]["nome_comportamento"] == "total")
			{
				if (!$dados_consulta[0][$j]["total"]){
					$dados_consulta[0][$j]["total"];
				}
				$dados_consulta[0][$j]["total"]++;
				$tem_total = 1;
			}
        }
		$linha++;
    }
	
	if ($tem_total){
		for ($i = 0; $i < count($dados_consulta[0]); $i++){ 
			if (($dados_consulta[0][$i]["nome_comportamento"] != "totalHora") &&
			   ($dados_consulta[0][$i]["nome_comportamento"] != "total"))  {
				if($i==0)
				{
					$lista[$linha+1][] = "Total"; 
				}
				else
				{
					$lista[$linha+1][] = "-"; 
				}	
			}
			elseif ($dados_consulta[0][$i]["nome_comportamento"] == "totalHora")
			{
				$hora = floor($dados_consulta[0][$i]["total"]/60); 
				$minutos = ($dados_consulta[0][$i]["total"] - ($hora*60));	
										
				if ($hora < 10){
					$hora = '0'.$hora; 
				}

				if ($minutos < 10){
					$minutos = '0'.$minutos; 
				}
				$lista[$linha+1][] = $hora.":".$minutos;
			} 
			elseif ($dados_consulta[0][$i]["nome_comportamento"] == "total")
			{
				$lista[$linha+1][] = $dados_consulta[0][$i]["total"];
			} 

		}
	}

  }
  */
  
  $servicovalor = array();
  
  $servicovalor[0]['nome'] = "Valor Mensal do Serviços..............................................................................................................:";
  $servicovalor[0]['valor'] = "128,00";
    
  $servicovalor[1]['nome'] = "Reporte das ocorrências para ( ) celulares - Pacote Avançado....................................................:";
  $servicovalor[1]['valor'] = "10,50";
   
  $servicovalor[2]['nome'] = "Valor Mensal do Serviços..............................................................................................................:";
  $servicovalor[2]['valor'] = "128,00";
    
  $servicovalor[3]['nome'] = "Valor Mensal do Serviços..............................................................................................................:";
  $servicovalor[3]['valor'] = "10,50";
  
  
  	
  	
    $servicos = array();
    
   
    for ($i = 0; $i <= 6; $i++){ 
		for ($j=0;$j <= 1;$j++){
		  if ($servicovalor[$i]){
			$servicos[$i]['nome']  = chr(187).$servicovalor[$i]['nome'];
			$servicos[$i]['valor']  = $servicovalor[$i]['valor'];
		  }
		  else{			  
		  	$servicos[$i]['nome']  = '';
			$servicos[$i]['valor']  = '';
		  }
		}
    }
    
   
   // $funcoes->dump($servicos);
  
    $total_servicos = array();
    $total_servicos[0]['valor'] = "171,50";
    
    
    $itemsvalor[0]['qtd'] = "1";
    $itemsvalor[0]['nome'] = "CENTRAL DE CHOQUE INDUSTRIAL";
    $itemsvalor[0]['valor'] = "780,00";    
    
    $itemsvalor[1]['qtd'] = "1";
    $itemsvalor[1]['nome'] = "METRO LINEAR CERCA 04 FIOS";
    $itemsvalor[1]['valor'] = "7,50";

    $itemsvalor[2]['qtd'] = "2";
    $itemsvalor[2]['nome'] = "SENSOR";
    $itemsvalor[2]['valor'] = "48,00";    
    
    
    $items = array();
    
    for ($i = 0; $i <= 8; $i++){ 
		for ($j=0;$j <= 2;$j++){
		  if ($itemsvalor[$i]){
			$items[$i]['qtd']  = $itemsvalor[$i]['qtd'];
			$items[$i]['nome']  = $itemsvalor[$i]['nome'];
			$items[$i]['valor']  = $itemsvalor[$i]['valor'];			
		  }
		  else{			  
		  	$items[$i]['qtd']  = '';
		  	$items[$i]['nome']  = '';
			$items[$i]['valor']  = '';
		  }
		}
    }
    
    
        
    $sub_total_serv = array();
    $sub_total_serv[0]['nome'] = "Sub-Total";
    $sub_total_serv[0]['valor'] = 'R$ 811,50';
    
    $desconto = array();
    $desconto[0]['nome'] = "Desconto";
    $desconto[0]['valor'] = 'R$ 10,00';
    
    $total_geral = array();
    $total_geral[0]['nome'] = "Total Geral";
    $total_geral[0]['valor'] = 'R$ 801,50';
    
    $venda = array();
    $venda[0]['taxa'] = 'R$ 100,00';
    $venda[0]['valor_equip'] = 'R$ 811,50';
    $venda[0]['parcelas'] = '2';
    $venda[0]['mensal'] = 'R$ 440,75';
    $venda[0]['vencimento'] = '10/05/2008';
    
    $locacao = array();
    $locacao[0]['taxa'] = 'R$ 100,00';
    $locacao[0]['valor_equip'] = '                ';
    $locacao[0]['parcelas'] = '2';
    $locacao[0]['mensal'] = 'R$ 440,75';
    $locacao[0]['vencimento'] = '10/05/2008';
      
    //Mostra Relatório
  	$pdf->ezSetMargins(230,20,50,35);
	$optionsText = array(right=>0,justification=>'left'); 
	

    $fonte = 10;	
	$titulo = "<b>".$negocio->getDescricaoTela()."</b>"; 
	//$pdf->ezImage("./images/cabecalho.jpg",0,800);
	//$pdf->ezStartPageNumbers(800,550,10,'','',1);
	//$pdf->ezTable($lista,$colunas,$titulo,array('fontSize'=>10,'width'=>800, 'cols' => $cols));
    $pdf->ezImage("./images/checkproposta.jpg",0,800);      	    
	$pdf->ezText(chr(187)."Monitoramento Eletrônico de Segurança 365 dias do ano, 24 horas por dia;",$fonte);
    $pdf->ezText(chr(187)."Contato Através do Operador da Central de Monitoramento, via telefone em casos de disparos e violações;",$fonte);
    $pdf->ezText(chr(187)."inclusive: sábados, domingos, e feriados",$fonte);
    $pdf->ezText(chr(187)."Contato Através do Operador da Central de Monitoramento, via telefone em casos de disparos e violações;",$fonte);    	
    $pdf->ezText(chr(187)."Averiguação móvel em casos de emergências;",$fonte);
    $pdf->ezText(chr(187)."Relatório on line via internet das ocorrências de abertura/fechamento no website www.semax.com.br;",$fonte);    
    $pdf->ezText("Vigia físico por até 36 h, nos casos de Arrombamento, em que não restabeleça a segurança do imóvel protegido",$fonte);
    
//opções da tabela servicos
$options['justification'] = 'right';
$options['showHeadings'] = 0;
$options['shaded'] = 0;
$options['width'] = 505;
$options['showLines'] = 0;
$options['cols']['valor']['justification'] = 'right';

$titulos['nome'] = '';
$titulos['valor'] = '';

     $pdf->ezTable($servicos,$titulos,'',$options);

//opções da tabela servicos
$options_total_serv['justification'] = 'right';
$options_total_serv['showHeadings'] = 0;
$options_total_serv['shaded'] = 0;
$options_total_serv['width'] = 505;
$options_total_serv['xPos'] = 'right';
$options['showLines'] = 0;
$options_total_serv['cols']['valor']['justification'] = 'right';

$pdf->ezText("",9);//espaço
//Valor mensal - Total dos serviços
$titulos_total_serv['valor'] = '';
$pdf->ezTable($total_servicos,$titulos_total_serv,'',$options);
//opções da tabela items
$options_items['justification'] = 'right';
$options_items['showHeadings'] = 0;
$options_items['shaded'] = 0;
$options_items['width'] = 505;
$options_items['showLines'] = 0;
$options_items['cols']['valor']['justification'] = 'right';
$options_items['cols']['qtd']['justification'] = 'center';

$titulos_items['qtd'] = '';
$titulos_items['nome'] = '';
$titulos_items['valor'] = '';


$pdf->ezText("",12);//espaço
$pdf->ezText("",16);//espaço
//items da proposta
$pdf->ezTable($items,$titulos_items,'',$options_items);
    
$pdf->ezText("",7);//espaço
//Sub Total dos items
$titulos_sub_total_serv['nome'] = '';
$titulos_sub_total_serv['valor'] = '';

$pdf->ezTable($sub_total_serv,$titulos_sub_total_serv,'',$options);
   
//Descontos
if (desconto)
  $pdf->ezTable($desconto,$titulos,'',$options);
//Total Geral  
$pdf->ezTable($total_geral,$titulos,'',$options);
   
$pdf->ezText("",8);//espaco        


//linha Venda
//$options_venda['justification'] = 'right';
$options_venda['showHeadings'] = 0;
$options_venda['shaded'] = 0;
$options_venda['width'] = 470;
$options_venda['showLines'] = 0;
$options_venda['xPos'] = 'right';
$options_venda['xOrientation']= 'left';



$titulo_venda['tipo'] = '';
$titulo_venda['taxa'] = '';
$titulo_venda['valor_equip'] = '';
$titulo_venda['parcelas'] = '';
$titulo_venda['mensal'] = '';
$titulo_venda['vencimento'] = '';
$options_items['cols']['parcelas']['width'] = 30;


if ($venda)
  $pdf->ezTable($venda,$titulo_venda,'',$options_venda);
//else
if ($locacao)
  $pdf->ezTable($locacao,$titulo_venda,'',$options_venda);      
    
$pdf->ezText("",12);// espaço	
$pdf->ezText("",12);// espaço
$pdf->ezText("",12);// espaço
$pdf->ezText("",12);// espaço
$pdf->ezText("",12);//espaço
$pdf->ezText("",12);//espaço



$option = array();
$option['aleft'] = 120;
$pdf->ezText("Domingos",12,$option);// espaço

$pdf->ezStream();