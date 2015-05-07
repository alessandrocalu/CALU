<?php
/**
 * @desc Sistema: CALU
 * @desc Encerra sessão 
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 22/03/2007 Atualizado: 22/03/2007
*/
session_start();
session_destroy();
header("Location:../index.php");
?>