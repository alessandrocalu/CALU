<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Arquivo que trata todas os links da framework
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/ 
//Inicia Sessão
session_start();
set_time_limit(120);  

//Grava tempo de inicio de carregamento de página
//$dados_auditoria = array();
//$dados_auditoria["data_inicio"] = date("Y-m-d H:i:s");

//Captura GETs e POSTs
//$dados_auditoria["get"] = $_GET;
//$dados_auditoria["post"] = $_GET;
//$dados_auditoria["session"] = $_SESSION;

//Acesso a page direto utilizando gets grupo e interface
if (isset($_GET["grupo"]) && isset($_GET["interface"])){
	require("./componentes/bancodados/negocio/interface/".$_GET["grupo"]."/".$_GET["interface"].".php");
	exit;                                                         
}

//Verifica Acesso
$local = (isset($_GET["local"])?$_GET["local"]:"");
$chave = (isset($_GET['chave'])?$_GET['chave']:(isset($_POST['chave'])?$_POST['chave']:0));
$ordem = (isset($_GET['ordem'])?$_GET['ordem']:(isset($_POST['ordem'])?$_POST['ordem']:0));

//Captura variaveis de ambiente
//$dados_auditoria["local"] = $local;
//$dados_auditoria["chave"] = $chave;
//$dados_auditoria["ordem"] = $ordem;

include "./comum/configuracao.php";
include "./comum/carregaClasses.php";

//Cria Objetos
if ($local && $possivel_negocio_custom && file_exists($possivel_negocio_custom)){
	eval('$negocio = new negocio_custom_'.$local.';');
}
else
{
	$negocio = new negocio_custom;
}	
$valida  = new validaFormulario;
$visual  = new visual;
$funcoes = new funcoes;

//Marca data de criação de objetos
//$dados_auditoria["data_criacao_objeto"] = date("Y-m-d H:i:s");

if (($_GET["local"] != "login") && 	($_GET["local"] != "logout") ){
  $_SESSION["navegador_local"] = (isset($_GET["local"])?$_GET["local"]:"");
  $_SESSION["navegador_chave"] = (isset($_GET["chave"])?$_GET["chave"]:"");
  $_SESSION["navegador_ordem"] = (isset($_GET["ordem"])?$_GET["ordem"]:"");	
  require ("./comum/verifica.php");
} 

//Mostra tela de Login
if ($_GET["local"] == "login"){
  require("./comum/login.php");	
}
else
{
  if ($_GET["local"] == "logout"){
	unset($_SESSION["navegador_id_usuario"]);
    unset($_SESSION["navegador_nome_usuario"]);
	unset($_SESSION["navegador_tipo_usuario"]);
    require("./comum/login.php");	
	exit;
  }
  
  //Marca data de criação de objetos
//  $dados_auditoria["data_inicio_local"] = date("Y-m-d H:i:s");

  if (!$tela = $negocio->mostraLocal($local,$chave,$ordem))
  {
	 echo "<center>";
     echo "<font color=\"#FF0000\">Erro ao carregar tela!<br>";
     echo "Descrição do erro: ";
	 $erros = $negocio->getErros();	
     for ($i = 0;$i < count($erros) ;$i++) {  
       echo $erros[$i]."<br>";
     }  
     echo "</font><br>"; 
	 unset($_SESSION["navegador_id_usuario"]);
     unset($_SESSION["navegador_nome_usuario"]);
	 unset($_SESSION["navegador_tipo_usuario"]);
     require("./comum/login.php");	
	 exit;
  }
  else
  {
	 $nome_tela = $negocio->getNomeTela();
	    
	 if ($negocio->getSessaoTela()){
	    //Grava/Lê POST em/de sessão
		if ($HTTP_POST_VARS) { 
			$vars["POST"] = $HTTP_POST_VARS;
			$_SESSION["navegador_".$nome_tela] = $vars;
		}
		else
		{
			$vars = $_SESSION["navegador_".$nome_tela];
			if ($vars["POST"]){
				$_POST = $vars["POST"];
			}	
		}
	 }
	 //Tipos de tela que tem HTML (lista e cadastro)
	 if ((($negocio->getTipoTela() == 15) or ($negocio->getTipoTela() == 20)) && (!isset($flg_sem_html)) && (isset($visual))) {
		//Cabeçalho padrão
		$visual->desenhaCabecalhoHTML($local);
	 }	
	 require($tela);  
	 //Tipos de tela que tem HTML (lista e cadastro)
	 if ((($negocio->getTipoTela() == 15) or ($negocio->getTipoTela() == 20)) && (!isset($flg_sem_html)) && (isset($visual))){
		//Rodapé padrão
		$visual->desenhaRodapeHTML();
	 }
	 
	 //Marca data de criação de objetos
//	 $dados_auditoria["usuario"] = $_SESSION["navegador_id_usuario"];
//	 $dados_auditoria["tela"] = $negocio->getCodigoTela();
//     $dados_auditoria["data_fim_local"] = date("Y-m-d H:i:s");
//	 
//     $retorno_auditoria = $negocio->grava_auditoria($dados_auditoria);
//    
//     if (is_array($retorno_auditoria) && count($retorno_auditoria) && ($_GET["local"] != "cad_dm")){
//     	if ((($negocio->getTipoTela() == 15) or ($negocio->getTipoTela() == 20)) && (!$flg_sem_html)){
//	     	echo "Data Inicio Acesso: ".date("d/m/Y H:i:s",strtotime($dados_auditoria["data_inicio_local"]))."<br>";
//	     	echo "Data Fim Acesso: ".date("d/m/Y H:i:s",strtotime($dados_auditoria["data_fim_local"]))."<br>";
//	     	echo "Tempo de Acesso: ".$retorno_auditoria["tempo_diferenca"]."<br>";
//	     	echo "Números de Acessos: ".$retorno_auditoria["num_acesso"]."<br>";
//	     	echo "Média de Tempo de Acesso: ".$retorno_auditoria["media_segundos"];
//     	}	
//     }
  }
}