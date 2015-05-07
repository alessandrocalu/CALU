<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Template para SQL Livre
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 23/02/2009 Atualizado: 23/02/2009
*/
?>
<html>
<head>
	<title>Proposta</title>
</head>
<body>	
<script>
function imprime_proposta(proposta,nome_vendedor){
<?
if ($tipo){
	if ($_GET["email"]){
	?>
		window.location = '../proposta/imprimir.php?id_proposta='+proposta+'&email=<? echo $email_cliente; ?>&tipo=E';
	<?
	}
	else
	{
	?>
		window.location = '../proposta/imprimir.php?id_proposta='+proposta+'&nome_vendedor='+nome_vendedor;
	<?
	}
}
else
{
	$mensagem = $negocio->getMensagem();
	if ($mensagem){
	?>
		alert('<? echo $mensagem; ?>');
	<?	
	}
?>
	window.close();
<?	
}
?>
}

imprime_proposta(<? echo $tipo; ?>,'<? echo $nome_vendedor; ?>');	
</script>
</body>
</html>	