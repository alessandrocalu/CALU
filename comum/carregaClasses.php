<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Inclue todas as definições de classe 
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 22/03/2007 Atualizado: 22/03/2007
*/
include "./componentes/arquivo/arquivoTxt.php";

include "./componentes/bancodados/comunicacao/bancoDados.php";
//include "./componentes/bancodados/comunicacao/firebird.php";
include "./componentes/bancodados/comunicacao/mySQL.php";
//include "./componentes/bancodados/comunicacao/sqlServer.php";
include "./componentes/bancodados/negocio/negocio.php";
include "./custom/negocio_custom.php";
$possivel_negocio_custom = "";
if ($local){
	$possivel_negocio_custom = "./custom/negocio_custom_".$local.".php";
	if (file_exists($possivel_negocio_custom)){
		require($possivel_negocio_custom);
	}
}
include "./componentes/bancodados/negocio/interface/tela.php";
include "./componentes/bancodados/negocio/consulta/consulta.php";
include "./componentes/bancodados/negocio/tabela/tabela.php";


include "./componentes/pdf/class.ezpdf.php";
include "./componentes/sessao/resultados.php";
include "./componentes/validacao/validaFormulario.php";
include "./componentes/funcoes/funcoes.php";

//include "./componentes/visual/graficos/graficoBarras.php";
//include "./componentes/visual/graficos/graficoPizza.php";
include "./componentes/visual/visual.php";