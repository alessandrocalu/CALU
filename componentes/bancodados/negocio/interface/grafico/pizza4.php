<?php
include ("./componentes/jpgraph/src/jpgraph.php");
include ("./componentes/jpgraph/src/jpgraph_pie.php");

$data = array(40,60,21,33);

$graph = new PieGraph(300,200,"auto");
$graph->SetShadow();

$graph->title->Set($negocio->getTituloTela());
$graph->title->SetFont(FF_FONT1,FS_BOLD);

$p1 = new PiePlot($data);
$p1->SetLegends($gDateLocale->GetShortMonth());
$p1->SetCenter(0.4);

$graph->Add($p1);
$graph->Stroke();

?>


