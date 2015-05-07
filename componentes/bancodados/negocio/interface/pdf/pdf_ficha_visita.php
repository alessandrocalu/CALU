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
  $pdf = new Cezpdf('a4','portrait');
  $pdf->selectFont("./componentes/pdf/fonts/Helvetica.afm");
  $lista = array(array('ficha'=>"<b>SEMAX - Segurança Máxima</b>                                                                                            ".date("d/m/Y")."\n \n<b>Marcador/Consultor:</b> <u><i></i></u> / <u><i></i></u>\n<b>Empresa:</b>  <u><i></i></u>\n<b>Contato:</b>  <u><i></i></u>\n<b>Tel.:</b> <u><i></i></u> <b>   Fax:</b> <u><i></i></u> <b>   Celular:</b> <u><i></i></u>\n<b>Data/Hora:</b> <u><i></i></u> \n<b>Endereço:</b>   <u><i> - </i></u> \n \n<b>Obs.:</b> <u><i></i></u>"));
  
  
  $dados_consulta = $negocio->getDadosTela();
  if ($dados_consulta) {
  	$lista = array(); 
    for ($i = 0; $i < count($dados_consulta); $i++){
    	$lista[] = array('ficha'=>"<b>SEMAX - Segurança Máxima</b>                                                                                            ".date("d/m/Y")."\n \n<b>Marcador/Consultor:</b> <u><i>".$dados_consulta[$i][0]["valor"]."</i></u> / <u><i>".$dados_consulta[$i][1]["valor"]."</i></u>\n<b>Empresa:</b>  <u><i>".$dados_consulta[$i][2]["valor"]."</i></u>\n<b>Contato:</b>  <u><i>".$dados_consulta[$i][3]["valor"]."</i></u>\n<b>Tel.:</b> <u><i>".$dados_consulta[$i][4]["valor"]."</i></u> <b>   Fax:</b> <u><i>".$dados_consulta[$i][5]["valor"]."</i></u> <b>   Celular:</b> <u><i>".$dados_consulta[$i][6]["valor"]."</i></u>\n<b>Data/Hora:</b> <u><i>".$dados_consulta[$i][7]["valor"]."</i></u> \n<b>Endereço:</b>   <u><i>".$dados_consulta[$i][8]["valor"]." - ".$dados_consulta[$i][9]["valor"]."</i></u> \n \n<b>Obs.:</b> <u><i>".$dados_consulta[$i][10]["valor"]."</i></u>");
    }
  }  		
  //Lista dados
  $pdf->ezTable($lista,'',$title,array('fontSize'=>11,'width'=>500,'showLines'=>2,'showHeadings'=>0,'shaded'=>0, 'rowGap'=>10 ));
  $pdf->ezStream();