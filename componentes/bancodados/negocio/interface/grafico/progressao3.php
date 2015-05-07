<?php
include ("./componentes/jpgraph/src/jpgraph.php");
include ("./componentes/jpgraph/src/jpgraph_line.php");

$cor[0] = "navy";
$cor[1] = "red";
$cor[2] = "green";
$cor[3] = "#FFCC33";
$cor[4] = "blue";
$cor[5] = "orange";
$cor[6] = "silver";
$cor[7] = "cyan";
$cor[8] = "#CC00CC";
$cor[9] = "#996633";
$cor[10] = "#66FFCC";
$cor[11] = "#003333";
$cor[12] = "#660000";
$cor[13] = "#999933";
$cor[14] = "#669966";
$cor[15] = "#990099";
$cor[16] = "#6600FF";
$cor[17] = "black";
$cor[18] = "white";
$cor[19] = "#FFFF99";
$cor[20] = "#FFCCCC";
$cor[21] = "#CCFFFF";
$cor[22] = "#99FF33";
$cor[23] = "#CCCCFF";
$cor[24] = "#666666";

$dados_grafico = $negocio->getDadosTela();
if ($dados_grafico){
	$legend = array();
	$grupo = array();
	for ($i = 0; $i < count($dados_grafico); $i++){
		if ($dados_grafico[$i][0]["valor"] && $dados_grafico[$i][1]["valor"] && $dados_grafico[$i][2]["valor"]){
			$valor_grupo = $dados_grafico[$i][0]["valor"];
			$valor_legend = $dados_grafico[$i][1]["valor"];
			$valor_valor = $dados_grafico[$i][2]["valor"];			

			//Preenche Grupo 
			$achou_grupo = 0;
			for ($j=0;$j < count($grupo); $j++){
				if ($grupo[$j] == $valor_grupo){
					$indice_grupo = $j;
					$achou_grupo = 1;
					break;
				}
			}
			if (!$achou_grupo){
				$grupo[] = $valor_grupo; 
				$indice_grupo = count($grupo)-1;
			}
						
			//Preenche Legenda
			$achou_legend = 0;
			for ($j=0;$j < count($legend); $j++){
				if ($legend[$j] == $valor_legend){
					$indice_legend = $j;
					$achou_legend = 1;
					break;
				}
			}
			if (!$achou_legend){
				$legend[] = $valor_legend; 
				$indice_legend = count($legend)-1;
			}

			
			$data[$indice_legend][$indice_grupo] = $valor_valor;
		}	
	}	
	//Preenche lacunas de data
	for ($i = 0; $i < count($legend); $i++){
		for ($j = 0; $j < count($grupo); $j++){
			if (!$data[$i][$j]){
				$data[$i][$j] = 0;
			}
		}
	}
	
}
if (!$legend[0])
{
	// Some data
	$data[0][0] = 10;
	$legend = array('Sem Info.');
	$grupo = array('Semana');
}

// Setup the graph
$graph = new Graph(800,400);
$graph->SetMarginColor('white');
$graph->SetScale("textlin");
$graph->SetFrame(false);
$graph->SetMargin(30,50,30,100);

$graph->title->Set($negocio->getTituloTela());
$graph->title->SetFont(FF_ARIAL,FS_ITALIC,11);


$graph->yaxis->HideZeroLabel();
$graph->ygrid->SetFill(true,'#EFEFEF@0.5','#BBCCFF@0.5');
$graph->xgrid->Show();

$graph->xaxis->SetTickLabels($grupo);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->SetFont(FF_ARIAL,FS_ITALIC,8);


//Cria linhas de gráfico
for ($i = 0; $i < count($legend); $i++){
	$dados_linha = array();
	for ($j = 0; $j < count($grupo); $j++){
		$dados_linha[] = $data[$i][$j];
	}
	$linha = new LinePlot($dados_linha);
	$linha->SetColor($cor[$i]);
	$linha->SetLegend($legend[$i]);
	$graph->Add($linha);
}

$graph->legend->SetFont(FF_ARIAL,FS_ITALIC,8);
$graph->legend->SetShadow('gray@0.4',5);
$graph->legend->SetPos(0.1,0.1,'right','top');
// Output line
$graph->Stroke();
?>