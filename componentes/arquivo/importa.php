<?
set_time_limit(0);  
include "arquivoTxt.php";
include "../bancodados/comunicacao/bancoDados.php";
include "../bancodados/comunicacao/sqlServer.php";
include "../funcoes/funcoes.php";

//$arquivoDados = new arquivoTxt("uploads/semax500.csv");
//$arquivoDados = new arquivoTxt("uploads/semax160.csv");
//$arquivoDados = new arquivoTxt("uploads/mail.csv");
$arquivoDados = new arquivoTxt("uploads/mg.csv");
$banco = new sqlServer; 
$funcoes = new funcoes; 
$banco->servidor = "srvsemax";
$banco->usuario =  "tmkt";
$banco->senha = "Plan 9";
$banco->nomeBanco = "DbTmkt";
$arquivoDados->abreTxt("rt");
$dados = $arquivoDados->arquivoLinhas();
$contador = 0;

//$funcoes->send_mail_imagem("alessandrocalu@gmail.com","Sua Segurança Começa Aqui - 31 3277-2255","http://www.semax.com.br/folhetos/gr3familia.jpg","vilene@semax.com.br");
//$funcoes->send_mail_imagem("alessandrocalu@gmail.com","Sua Segurança Começa Aqui - 31 3277-2255","http://www.semax.com.br/folhetos/conservadora2.jpg","vilene@semax.com.br");
echo "Inicio de importação de arquivo<br>";
$total = count($dados);
for ($i=0;$i < count($dados) ;$i++) {
	$lista = "";
	$lista = explode(";",$dados[$i]); 
	$codigo = trim($lista[0]);
	$cidade = $funcoes->retira_acentos(strtoupper(trim($lista[1])));
	$logradouro = $funcoes->retira_acentos(strtoupper(trim($lista[2])));
	$bairro = $funcoes->retira_acentos(strtoupper(trim($lista[3])));
	$cep = trim($lista[4]);
	$tipo = $funcoes->retira_acentos(strtoupper(trim($lista[5])));
		try{
//			$funcoes->send_mail_imagem($email,"Sua Segurança Começa Aqui - 31 3277-2255","http://www.semax.com.br/folhetos/gr3familia.jpg","vilene@semax.com.br");
//			$funcoes->send_mail_imagem($email,"Sua Segurança Começa Aqui - 31 3277-2255","http://www.semax.com.br/folhetos/conservadora2.jpg","vilene@semax.com.br");
            
			$banco->from = "cep";
			$banco->insert =  " codigo, cidade, logradouro, bairro, cep, tp_logradouro ";
			$banco->values = " ".$codigo.",'".$cidade."','".$logradouro."', '".$bairro."', '".$cep."' , '".$tipo."' ";
			$banco->enviaInsert();
			
			echo $contador++;
			echo " de ".$total."<br>";
		}
		catch(Exception $e){
		}

/*		
		//Verifica se não existe telefone ou nome
		$banco->select = "count(*) as Result";
		$banco->from = "TMKT";
	    $banco->where = " telefone = '".$telefone."' or fax = '".$telefone."' or celular = '".$telefone."' or nome = '".$nome."' "; 
		$banco->enviaSelect();
		$teste = $banco->linhaSelect();
		//echo $banco->sql."<br>";

		if ($teste[0] == 0){
			//Verifica se não existe telefone ou nome
			$banco->select = "max(codigo)+1";
			$banco->from = "TMKT";
			$banco->where = ""; 
			$banco->enviaSelect();
			$teste = $banco->linhaSelect();

			$banco->from = "TMKT";
			$banco->insert =  " codigo,nome,telefone,email,contato ";
			$banco->values = " ".$teste[0].",'".$nome."','".$telefone."', '".$email."', '".$contato."' ";
			echo $banco->insert."<br>"; 
			echo $banco->values."<br>"; 
			$banco->enviaInsert();
		}
*/
		
}
$arquivoDados->fechaTxt();