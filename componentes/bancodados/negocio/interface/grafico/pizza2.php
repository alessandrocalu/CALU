<?php
include ("./componentes/jpgraph/src/jpgraph.php");
include ("./componentes/jpgraph/src/jpgraph_pie.php");
include ("./componentes/jpgraph/src/jpgraph_pie3d.php");

$data = array(40,60,21,33,40,60,21,33,40,60,21,33);
$legend = array('Janeiro','Fevereito','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');


$graph = new PieGraph(300,200,"auto");
$graph->SetShadow();

$graph->title->Set($negocio->getTituloTela());
$graph->title->SetFont(FF_FONT1,FS_BOLD);

$p1 = new PiePlot3D($data);
$p1->SetSize(0.5);
$p1->SetCenter(0.45);
$p1->SetLegends($legend);

$graph->Add($p1);
$graph->Stroke();

?>


