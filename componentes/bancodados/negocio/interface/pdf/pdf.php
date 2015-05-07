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
  session_start();
  
  $pdf = new Cezpdf('a4','landscape');
  $pdf->selectFont("./componentes/pdf/fonts/Helvetica.afm");
  
  $campos_tela =  $negocio->getCamposTela();
  
  for ($i=0;$i < sizeOf($campos_tela);$i++){
	  $colunas[] = "<b>".$campos_tela[$i]["rotulo"]."</b>";

/*
'cols' =>
array(<colname>=>array('justification'=>'left','width'=>100,'link'=><linkColName>),<colname>=>
....) ,allow the setting of other paramaters for the individual columns, each column can have its width
and/or its justification set.
*/
	  $cols[] = array( 'width' => $campos_tela[$i]["tamanho"], 'justification' => 'center');
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

/*	
$options is an associative array which can contain:
'showLines'=> 0,1,2, default is 1 (1->show the borders, 0->no borders, 2-> show borders AND lines
between rows.)
'showHeadings' => 0 or 1
'shaded'=> 0,1,2, default is 1 (1->alternate lines are shaded, 0->no shading, 2->both sets are shaded)
'shadeCol' => (r,g,b) array, defining the colour of the shading, default is (0.8,0.8,0.8)
'shadeCol2' => (r,g,b) array, defining the colour of the shading of the second set, default is
(0.7,0.7,0.7), used when 'shaded' is set to 2.
'fontSize' => 10
'textCol' => (r,g,b) array, text colour
'titleFontSize' => 12
'rowGap' => 2 , the space between the text and the row lines on each row
7 of 41 http://ros.co.nz/pdf - http://www.sourceforge.net/projects/pdf-php
'colGap' => 5 , the space between the text and the column lines in each column
'lineCol' => (r,g,b) array, defining the colour of the lines, default, black.
'xPos' => 'left','right','center','centre',or coordinate, reference coordinate in the x-direction
'xOrientation' => 'left','right','center','centre', position of the table w.r.t 'xPos'. This entry is to be
used in conjunction with 'xPos' to give control over the lateral position of the table.
'width' => <number>, the exact width of the table, the cell widths will be adjusted to give the table
this width.
'maxWidth' => <number>, the maximum width of the table, the cell widths will only be adjusted if
the table width is going to be greater than this.
'innerLineThickness' => <number>, the thickness of the inner lines, defaults to 1
'outerLineThickness' => <number>, the thickness of the outer lines, defaults to 1
'protectRows' => <number>, the number of rows to keep with the heading, if there are less than this
on a page, then all is moved to the next page.
*/
	
	//Mostra Relatório
	$titulo = "<b>".$negocio->getDescricaoTela()." - Data:".date("d/m/Y H:i")."</b>"; 
	
//	$funcoes->dump($lista);
//	$funcoes->dump($colunas);
//	$funcoes->dump($titulo);
//	$funcoes->dump($cols);
//	
//	exit;
	
	$pdf->ezImage("./images/cabecalho.jpg",0,800);
	$pdf->ezStartPageNumbers(800,550,10,'','',1);
	$pdf->ezTable($lista,$colunas,$titulo,
		array('shaded'=> 1,  
		      'fontSize'=>10,
	  		  'titleFontSize' => 12,
			  'xOrientation' => 'center', 
		      'width'=>800, 
		      'cols' => $cols));
	$pdf->ezStream();
  }