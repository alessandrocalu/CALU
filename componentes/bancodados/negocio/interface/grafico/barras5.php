<?php
include ("./componentes/jpgraph/src/jpgraph.php");
include ("./componentes/jpgraph/src/jpgraph_bar.php");

if ($_GET["legend"]){
	$legend = explode(",",$_GET["legend"]);
}

if ($_GET["data"]){
	$data = explode(",",$_GET["data"]);
}

if ((!is_array($legend)) || (!is_array($data))){
	$dados_grafico = $negocio->getDadosTela();
	if ($dados_grafico){
		for ($i = 0; $i < count($dados_grafico); $i++){
			if ($dados_grafico[$i][0]["valor"] && $dados_grafico[$i][1]["valor"]){
				$legend[] = $dados_grafico[$i][0]["valor"];
				$data[] = $dados_grafico[$i][1]["valor"];
			}	
		}	
	}
}
if (!$legend[0])
{
	// Some data
	$data = array(10);
	$legend = array('Sem Info.');
}

$datay= $data;
$datax= $legend;
$agrupamento = "";

// Create the graph. These two calls are always required
$graph = new Graph(800,400,"auto");	
$graph->SetScale("textlin");

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
$graph->img->SetMargin(40,30,20,150);

// Create a bar pot
$bplot = new BarPlot($datay);

// Adjust fill color
$bplot->SetFillColor('darkgreen@0.4');

// Setup each bar with a shadow of 50% transparency
$bplot->SetShadow('black@0.4');

// Setup values
$bplot->value->Show();
$bplot->value->SetFormat('%d');
$bplot->value->SetFont(FF_ARIAL,FS_ITALIC,8);

// Center the values in the bar
$bplot->SetValuePos('center');

// Make the bar a little bit wider
$bplot->SetWidth(0.7);

$graph->Add($bplot);

// Setup the titles
$graph->title->Set(($_GET["titulo"]?$_GET["titulo"]:$negocio->getTituloTela()));
$graph->xaxis->title->Set(($_GET["nome_x"]?$_GET["nome_x"]:$agrupamento));
$graph->yaxis->title->Set(($_GET["nome_y"]?$_GET["nome_y"]:"Clientes"));

$graph->title->SetFont(FF_ARIAL,FS_ITALIC,11);
$graph->yaxis->title->SetFont(FF_ARIAL,FS_ITALIC,10);
$graph->xaxis->title->SetFont(FF_ARIAL,FS_ITALIC,8);
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);

// Display the graph
$graph->Stroke();
?>