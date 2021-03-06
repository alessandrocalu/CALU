<?php
include ("./componentes/jpgraph/src/jpgraph.php");
include ("./componentes/jpgraph/src/jpgraph_pie.php");
include ("./componentes/jpgraph/src/jpgraph_pie3d.php");
$dados_grafico = $negocio->getDadosTela();
if ($dados_grafico){
	for ($i = 0; $i < count($dados_grafico); $i++){
		if ($dados_grafico[$i][0]["valor"] && $dados_grafico[$i][1]["valor"]){
			$legend[] = $dados_grafico[$i][0]["valor"];
			$data[] = $dados_grafico[$i][1]["valor"];
		}	
	}	
}
if (!$legend[0])
{
	// Some data
	$data = array(10);
	$legend = array('Sem informações');
}	

// Create the Pie Graph.
$graph = new PieGraph(800,400,"auto");
$graph->SetShadow();

// Set A title for the plot
$graph->title->Set($negocio->getTituloTela());
$graph->title->SetFont(FF_ARIAL,FS_ITALIC,11); 
$graph->title->SetColor("darkblue");
$graph->legend->Pos(0.03,0.1);
$graph->legend->SetFont(FF_ARIAL,FS_ITALIC,8);

// Create 3D pie plot
$p1 = new PiePlot3d($data);
$p1->SetTheme("sand");
$p1->SetCenter(0.4);
$p1->SetSize(240);

// Adjust projection angle
$p1->SetAngle(45);

// Adjsut angle for first slice
$p1->SetStartAngle(45);

// Display the slice values
$p1->value->SetFont(FF_ARIAL,FS_ITALIC,8);
$p1->value->SetColor("navy");

// Add colored edges to the 3D pie
// NOTE: You can't have exploded slices with edges!
$p1->SetEdge("navy");

$p1->SetLegends($legend);

$graph->Add($p1);
$graph->Stroke();

?>


