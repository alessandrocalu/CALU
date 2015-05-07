<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio - XAJAX 
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

require_once("./componentes/xajax/xajax_core/xajax.inc.php");

$xajax = new xajax();

/**
 * @desc Gravar observação
*/
function gravar_campo($id_campo, $valor, $chave, $name, $tipo){
	global $negocio;
	$objResponse = new xajaxResponse();

	$erro = $negocio->verifica_validade_campo($id_campo, $valor, $chave, $name);

	if (is_array($erro) && count($erro) && $erro["mensagem"])
	{
		if ($tipo == 'select')
		{
			$objResponse->script(" document.getElementById('".$name."').value = ".$erro["valor_antigo"]);
		}
		$objResponse->alert($erro["mensagem"]);
		if ($erro["mensagem"])
		{
			$objResponse->script($erro["script"]);
		}
		return $objResponse;
	}
	
//	$retorno = $negocio->gravar_campo($id_campo, $valor, $chave);
//	$objResponse->alert($retorno);
//	return $objResponse;
	if ($negocio->gravar_campo($id_campo, $valor, $chave))
	{
		if ($tipo == 'text')
		{
			$script = " confirmaObjeto('".$name."', '".$name."_antigo'); setDisplay('btn_edit_".$name."',true); setDisplay('btn_confirma_".$name."',false); setDisplay('btn_cancela_".$name."',false); ";
			
			$objResponse->script($script);
		}
		else
		{
			$objResponse->alert('Valor atualizado!');
		}		
	}
	else
	{
		$objResponse->alert('Erro ao tentar gravar!');
	}
	return $objResponse;
}

$xajax->registerFunction("gravar_campo");


/**
 * @desc Gravar observação
*/
function gravar_observacao($cliente,$nota){
	global $negocio;
	$objResponse = new xajaxResponse();
    $resposta = $negocio->gravar_observacao($cliente,$nota);
	if ($resposta){
		$objResponse->alert($resposta);
		$objResponse->assign("recado_".$cliente, "value", htmlentities($nota));
	}
	else
	{
		$objResponse->alert('Erro ao tentar gravar!');
	}
	return $objResponse;
}

$xajax->registerFunction("gravar_observacao");

/**
 * @desc Gravar observação de vendedor
*/
function gravar_observacao_vendedor($cliente,$nota){
	global $negocio;
	$objResponse = new xajaxResponse();
    $resposta = $negocio->gravar_observacao_vendedor($cliente,$nota);
	if ($resposta){
		$objResponse->alert($resposta);
		$objResponse->assign("obs_vendedor_".$cliente, "value", htmlentities($nota));
	}
	else
	{
		$objResponse->alert('Erro ao tentar gravar!');
	}
	return $objResponse;
}

$xajax->registerFunction("gravar_observacao_vendedor");

/**
 * @desc Gravar produtos de vendedor
*/
function gravar_produto($vendedor, $produto, $ativo){
	global $negocio;
	$objResponse = new xajaxResponse();
	if ($vendedor && $produto){
		if ($ativo == "true"){
			//Habilita produto para vendedor
			if ($negocio->adicionar_vendedor_produto($vendedor,$produto))
			{
				$objResponse->alert('Produto adicionado para consultor!');
			}
			else
			{
				$objResponse->alert('Erro ao tentar adicionar produto para consultor!');
				$objResponse->script("document.getElementById('produto_".$vendedor."_".$produto."').checked = false;");
			}	
		}
		else
		{
			if ($negocio->remover_vendedor_produto($vendedor,$produto))
			{
				//Desabilita produdo de vendedor
				$objResponse->alert('Produto retirado de consultor!');
			}
			else
			{
				$objResponse->alert('Erro ao tentar retirar produto de consultor!');
				$objResponse->script("document.getElementById('produto_".$vendedor."_".$produto."').checked = true;");
			}	
	
		}
	}
	return $objResponse;
}

$xajax->registerFunction("gravar_produto");


function gravar_fechamento($codigo, $razaosocial, $cnpj, $contrato, $mensalidade, $equipamento, $instalacao, $meses, $name)
{
	global $negocio;
	$objResponse = new xajaxResponse();
	
	if (!$razaosocial || !$contrato || ($contrato == "00/00/0000") || !$equipamento || !$mensalidade || !$meses )
	{
		$objResponse->alert('Para fechamento de venda deve-se indicar pelo menos os seguintes dados de contrato (Razao Social, Data Contrato, Valor Mensalidade, Venda Equipamento e Meses Contrato)!');
		return $objResponse;
	}
	
	if ($ret = $negocio->gravar_contrato($codigo, $razaosocial, $cnpj, $contrato, $mensalidade, $equipamento, $instalacao, ($meses?($meses*1):0) )){
		//Muda select para Fechamento
		$objResponse->script(" document.getElementById('".$name."').value = 13 ");
		$objResponse->alert('Contrato Fechado!');
	}
	else
	{
		$objResponse->alert('Erro ao tentar registrar dados de contrato!');
	}
	
	return $objResponse;
}
$xajax->registerFunction("gravar_fechamento");


$xajax->processRequest();
$xajax->printJavascript("./componentes/xajax/");