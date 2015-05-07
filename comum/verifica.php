<?php
/**
 * @desc Sistema: CALU
 * @desc Verifica se existem os dados da sessão de login 
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 22/03/2007 Atualizado: 22/03/2007
*/
if(!isset($_SESSION["navegador_id_usuario"]) || !isset($_SESSION["navegador_nome_usuario"])){
	//Usuário não logado! Redireciona para página de login
	header("Location:index.php?local=login");
	exit;  
}
?>
