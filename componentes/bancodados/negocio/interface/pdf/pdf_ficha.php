<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Template para Interface de geração de relatórios PDF de listagem
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 18/01/2008 Atualizado: 18/01/2008
*/
$cliente = $_GET["chave"];
if (!$cliente){
	$cliente = -1;
}
$nome_cliente = $negocio->nomeCliente($cliente);
$telefone_cliente = $negocio->telefoneCliente($cliente);

$pdf = new Cezpdf('a4','portrait');
$pdf->selectFont("./componentes/pdf/fonts/Helvetica.afm");
      
//Mostra Relatório
$fonte = 10;	
$pdf->ezText($nome_cliente,$fonte);
$pdf->ezText($telefone_cliente,$fonte);
$pdf->ezText("",7);//espaço
$pdf->ezStream();
