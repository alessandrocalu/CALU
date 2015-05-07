<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Neg�cio (Curr�culo)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_curriculo extends negocio_custom {
	
	/**
	 * @desc Realiza customiza��o de Insert de Curriulo
	*/
    function insert_curriculo(){
		global $funcoes;
		if ($_GET["insert"] == 'novo_curriculo')
        {
			unset($_GET["insert"]);			
			
			if ($_POST["email"] && $_POST["senha"]){
				$curriculo = new tabela("CURRICULO"); 
				$dados_curriculo = $curriculo->carregar("email","'".$_POST["email"]."'"," = ","email"); 
				if ($erros = $curriculo->getErros()) {
					$this->erros = $erros;
				}  
				if (is_array($dados_curriculo) && (count($dados_curriculo) > 0)){
					//Verifica senha 
					if ($_POST["senha"] == $dados_curriculo[0]["senha"])
					{
						$_GET["chave"] = $dados_curriculo[0]["codigo"];
						$_GET["proposta"] = 1;
					}
					else
					{
						$mensagem = "<p>Voc�, ou algu�m utilizando seu email, tentou acessar seu curr�culo cadastrado no site da SEMAX (www.semax.com.br) mas n�o soube informar a senha. Informamos que a senha cadastrada para seu curr�culo na SEMAX � <i>".$dados_curriculo[0]["senha"]."</i> .</p>";
						
						$mensagem .= "<p>Caso n�o seja voc� que tentou acessar seu curr�culo ou ainda nem tenha cadastrado um curr�culo no site da SEMAX favor nos informar atrav�s do email rh@semax.com.br.</p>";

						$funcoes->send_mail($_POST["email"],"Cadastro de Curr�culo",$mensagem,"Recursos Humanos","rh@semax.com.br");
						$this->mensagem = "J� existe um curr�culo cadastrado para este email, a senha para acesso foi enviada para este email! ";
						$this->mostraLocal("acesso_curriculo");
						return 0;
					}
				}
				else
				{
					$campos =  array("email","senha","sexo","area_interesse","escolaridade","estado_civil","ultimo_salario","oculto");
					$valores = array("'".$_POST["email"]."'","'".$_POST["senha"]."'","0","0","0","0","'0,00'","0");
					$this->consulta->consultaInsert($campos,$valores);
					//Recupera chave de curriculo
					$dados_curriculo = $curriculo->carregar("email","'".$_POST["email"]."'"," = ","email"); 
					$_GET["chave"] = $dados_curriculo[0]["codigo"];
					$_GET["proposta"] = 1;
				}
			}
			else
			{
				//Verifica preenchimento de email e senha
				if (!$_POST["email"]){
					$this->mensagem = "Por favor preencha o seu email para cadastro de curr�culo! ";
				}
				else
				{
					if (!$_POST["senha"]){
						$this->mensagem = "Por favor indique a senha que voc� usar� para acesso futuro a seu curr�culo! ";
					}
				}
				$this->mostraLocal("acesso_curriculo");
				return 0;
			}
        }       
        return 1;
    }    
	
	/**
	 * @desc Realiza customiza��o de Update de Curriculo
	*/
    function update_curriculo(){
		global $funcoes;
		if ($_GET["update"] == 'atualizar_curriculo')
        {
			unset($_GET["update"]);			
			if ($_POST["email"] && $_POST["senha"]){
				//Procura por email j� cadastrado
				$curriculo = new tabela("CURRICULO"); 
				$dados_curriculo = $curriculo->carregar("email","'".$_POST["email"]."'"," = ","email"); 
				if ($erros = $curriculo->getErros()) {
					$this->erros = $erros;
				}  
				if (is_array($dados_curriculo) && (count($dados_curriculo) > 0)){
					//Verifica senha 
					if ($_POST["senha"] == $dados_curriculo[0]["senha"]){
						$_GET["chave"] = $dados_curriculo[0]["codigo"];
						$_GET["proposta"] = 1;
					}
					else
					{
						$mensagem = "<p>Voc�, ou algu�m utilizando seu email, tentou acessar seu curr�culo cadastrado no site da SEMAX (www.semax.com.br) mas n�o soube informar a senha. Informamos que a senha cadastrada para seu curr�culo na SEMAX � <i>".$dados_curriculo[0]["senha"]."</i> .</p>";
						
						$mensagem .= "<p>Caso n�o seja voc� que tentou acessar seu curr�culo ou ainda nem tenha cadastrado um curr�culo no site da SEMAX favor nos informar atrav�s do email rh@semax.com.br.</p>";

						$funcoes->send_mail($_POST["email"],"Cadastro de Curr�culo",$mensagem,"Recursos Humanos","rh@semax.com.br");
						$this->mensagem = "Senha inv�lida para acesso de curr�culo, a senha correta foi enviada para seu email! ";
						$this->mostraLocal("acesso_curriculo");
						return 0;
					}
				}
				else
				{
					$this->mensagem = "N�o exite curr�culo cadastrado para este email!";
					$this->mostraLocal("acesso_curriculo");
					return 0;
				}
			}
			else
			{
				//Verifica preenchimento de email e senha
				if (!$_POST["email"]){
					$this->mensagem = "Por favor preencha o seu email para atualizar seu curr�culo! ";
				}
				else
				{
					if (!$_POST["senha"]){
						$this->mensagem = "Por favor indique a senha de acesso a seu curr�culo! ";
					}
				}
				$this->mostraLocal("acesso_curriculo");
				return 0;
			}
        }
		if ($_GET["update"] == 'cadastrar_curriculo')
        {
			if (!$_POST["nome"]){
				$this->mensagem = "Por favor informe seu nome no curr�culo! ";
				return 0;
			}
			if ($_POST["data_nascimento"] == "00/00/0000"){
				$this->mensagem = "Por favor informe sua data de nascimento no curr�culo! ";
				return 0;
			}
			if (!$_POST["sexo"]){
				$this->mensagem = "Por favor informe seu sexo no curr�culo! ";
				return 0;
			}
			if (!$_POST["estado_civil"]){
				$this->mensagem = "Por favor informe seu estado civil no curr�culo! ";
				return 0;
			}
			if (!$_POST["escolaridade"]){
				$this->mensagem = "Por favor informe sua escolaridade no curr�culo! ";
				return 0;
			}
			if (!$_POST["area_interesse"]){
				$this->mensagem = "Por favor informe a �rea de interesse! ";
				return 0;
			}

			if (!$_POST["vaga"]){
				$this->mensagem = "Por favor candidate-se a vaga de interesse! ";
				return 0;
			}

			if(!$_FILES["local_arquivo"]['name']) {
				$this->mensagem = "Por favor anexe seu curr�culo (documento .doc ou .pdf)!";
				return 0;
			}
			if($_FILES["local_arquivo"]['size']  > 102400) {
				$this->mensagem = "Documento anexo n�o pode ter mais de 100K! ";
				return 0;
			}
			if(!($_FILES["local_arquivo"]['type'] == "application/pdf" or $_FILES["local_arquivo"]['type'] == "application/msword")) {
				$this->mensagem = "Documento anexo precisa ser do tipo .doc ou .pdf! ";
				return 0;
			}


			if ($_POST["codigo"] && $_POST["nome"] && $_POST["data_nascimento"] && $_POST["sexo"] &&
				$_POST["estado_civil"] && $_POST["escolaridade"] && $_POST["area_interesse"])
			{
				//Realiza upload de arquivo
				$local_arquivo = "";
				$extensao = ".doc";
				if ($_FILES["local_arquivo"]['type'] == "application/pdf"){
					$extensao = ".pdf";
				}
				if ($_FILES["local_arquivo"]['name']){
					$local_arquivo = "rh/curriculos/curriculo_".$_POST["codigo"].$extensao;
				}

				//Upload de Arquivo
				if ($local_arquivo && $_FILES["local_arquivo"]['name']){
					copy($_FILES["local_arquivo"]['tmp_name'],$local_arquivo); 
				}

				$campos = array("nome", "sexo", "estado_civil", "escolaridade", "area_interesse", "ultimo_salario", "local_arquivo", "data_nascimento", "data_atualizacao", "area_interesse_outra", "carta_apresentacao","oculto");

				 $valores = array("'".$_POST["nome"]."'", $_POST["sexo"], $_POST["estado_civil"], $_POST["escolaridade"], $_POST["area_interesse"], "'".$_POST["ultimo_salario"]."'", "'".$local_arquivo."'", "'".$funcoes->formataDataBanco($_POST["data_nascimento"])."'", 'getdate()',  "'".$_POST["area_interesse_outra"]."'", "'".$_POST["carta_apresentacao"]."'","0");

                 $camposFiltros[] = "codigo";
                 $camposValores[] = $_POST["codigo"];
                 $logico = " and ";
                 $this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 

				 //Atualiza Vagas de candidatura
				 $this->carregaDelete("VAGA_CURRICULO", " curriculo = ".$_POST["codigo"], 0);

				 if ($_POST["vaga"] > 0){
					 $this->carregaInsert("curriculo,vaga", "VAGA_CURRICULO", $_POST["codigo"].",".$_POST["vaga"], 0);
				 }	
			}
			
			$mensagem = "<p>Voc� acabou de cadastrar ou atualizar seu curriculo no site da SEMAX (www.semax.com.br). Obrigado por seu interesse em fazer parte da empresa que mais cresce no mercado de seguran�a eletr�nica.</p>";
						
			$mensagem .= "<p>Caso n�o seja voc� que tenha cadastrado um curriculo relacionado a este email no site da SEMAX, favor nos informar atrav�s do email rh@semax.com.br.</p>";

			$funcoes->send_mail($_POST["email"],"Cadastro de Curr�culo",$mensagem,"Recursos Humanos","rh@semax.com.br");

			$this->mensagem = "Curr�culo enviado, clique em sair para encerrar!";
		}       
        return 1;
    }    
}