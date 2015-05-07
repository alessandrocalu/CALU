<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe que provê funções gerais do sistema
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 20/03/2007 Atualizado: 20/03/2007
*/
class funcoes {
  
  /**
   * @desc Função soundex adaptada para Português brasileiro
   * @return string de Código SONDEX adaptado
   * @param string $texto de Texto para cálculo SOUNDEX 
  */
  function recLink($texto){
    $texto = strtoupper($texto);
    //Adequação aos nomes Nacionais(Brasil)
    if (($texto[0] == 'W') and ($texto[1] == 'A')) {
      $texto[0] = 'V';
    }
    if ($texto[0] == 'H') {
      $texto = substr($texto,1,strlen($texto)-2);
    }
    if (($texto[0] == 'K') and
      (($texto[1] == 'A') or ($texto[1] == 'O') or ($texto[1] == 'U'))) {
       $texto[0] = 'C';
    }
    if ($texto[0] == 'Y') {
      $texto[0] == 'I';
    }
    if (($texto[0] == 'C') and
      (($texto[1] == 'E') or ($texto[1] == 'I'))) {
      $texto[0] = 'S';
    }
    if (($texto[0] == 'G') and
      (($texto[1] == 'E') or ($texto[1] == 'I'))){
      $texto[0] = 'J';
    }
    //Alterção minha - Calu
    if (($texto[0] == 'P') and
      ($texto[1] == 'H')) {
      $texto = 'F'. substr($texto,2,strlen($texto)-3);
    }
    return soundex($texto);
  }
  
  /**
   * @desc Função para formatar texto tirando carcteres proibidos
   * @return string $textoFormatado de Texto formatado
   * @param string $texto de Texto para alterar 
  */
  function formataTextoGET($texto){
    $textoFormatado = "";
    for ( $i = 0; $i < strlen($texto); $i++){
      if ($texto[$i] == '&') {
        $textoFormatado = $textoFormatado.'e';
      } else if ($texto[$i] == "'") {
        $textoFormatado = $textoFormatado." ";
      } else {
        $textoFormatado = $textoFormatado.$texto[$i];
      }
    }
    return $textoFormatado;
  }
  
  /**
   * @desc Procura links em página HTML
   * @return array $links de Lista de links encontrados
   * @param string $texto de Texto para alterar 
  */
  function procuraLinks($html){
    $links = array();
    $auxStr = $html;
    while ($auxStr){
      $posicao = strpos("href",$html);
      $posicao = strpos("location",$html);
      $posicao = strpos("action",$html);
      $posicao = strpos("onClick",$html);
      if ($posicao){
      	$links[] = $posicao;
      	$auxStr = substr($html,0,$posicao+1);
      } else {
      	$auxStr = "";
      } 		
    } 	
    return $links;
  }
  
  /**
   * @desc Procura Ancoras em texto 
   * @return array $ancoras de Lista de ancoras encontradas
   * @param string $texto de Texto para alterar 
  */
  function procuraAncoras($texto){
	  $texto = "_".$texto;
	  $ancoras = array();
	  while (strpos($texto,":") > 0) {
		$primeira = strpos($texto,":");
		$ultima = strpos($texto,":",$primeira+1);
		if (!$ultima)
		{
			$ultima = strpos($texto," ",$primeira+1);
		}	
		if (!$ultima)
		{
			$ultima = strlen($texto);
		}	
		$ancoras[] = substr($texto,$primeira+1,$ultima-$primeira-1);	
		$texto = substr($texto,$ultima+1);	
	  }
	  return $ancoras;
  }  
  
  /**
   * @desc Substitui Ancoras em texto 
   * @return string de texto com ancoras substituidas
   * @param string $texto de Texto para alterar 
   * @param array $ancoras de Lista de ancoras
   * @param array $valores de Lista de valores
  */
  function substituiAncoras($texto,$ancoras,$valores){
	  $texto_final = $texto;
	  $texto = "_".$texto;
	  for ($i= 0;$i < count($ancoras) ;$i++){
		if ($valores[$i] == "NULL")  {
			return "";
		}
		if ( $posicao = strpos($texto,":".$ancoras[$i].":")){
		  $texto_final = substr($texto,1,$posicao-1);
		  $texto_final .= $valores[$i];
		  $texto_final .= substr($texto,$posicao+strlen($ancoras[$i])+2);	
		  $texto = "_".$texto_final;
		}  
	  }	

	  return $texto_final;
  }  
  
  /**
   * @desc Formata número 
   * @return string Número formatado
   * @param string $texto de Numero para formatar 
  */
  function formataNumero($texto){
	  $texto_final = "";
	  for ($i= 0;$i < strlen($texto) ;$i++){
		if ($texto[$i] == ','){
			$texto_final .= '.';  
		}		
		else
		{
			if (strpos('_1234567890',$texto[$i]) > 0){
				$texto_final .= $texto[$i];
			}
			else
			{
				$texto_final .= '0';
			}	
		}
	  }	
	  if (!$texto_final)
	  {
	  	$texto_final = '0';
	  }
	  return $texto_final;
  }  

  /**
   * @desc Formata data Banco
   * @return string data em formato (YYYY-mm-dd)
   * @param string $data_formatada de Data em formato (dd/mm/yyyy) 
  */
  function formataDataBanco($data_formatada){
	$dia = substr($data_formatada,0,2);
	$mes = substr($data_formatada,3,2);
	$ano = substr($data_formatada,6,4);
	if ($dia > 0 && $dia <= 31 && $mes > 0 && $mes <= 12 && $ano > 1000){
		return $ano."-".$mes."-".$dia;
	}
	else
	{
		return "0000-00-00";
	}
  }  
  
  /**
   * @desc Formata numero Banco
   * @return string de numero
   * @param string formatada 
  */
  function formataNumeroBanco($numero){
	$numero = str_replace(",",".",$numero);
	$numero = ($numero?($numero*1):0);
	return $numero;
  }  
  
  ////////////////////////////////////////////////////////
  // Function:         dump
  // Inspired from:     PHP.net Contributions
  // Description: Helps with php debugging

  /**
   * @desc Exibe conteudo de variável ou objeto
   * @param string &$var de ponteiro para Variável
   * @param boolean $info de Descrição de variável 
  */
  function dump(&$var, $info = FALSE)
  {
    $scope = false;
    $prefix = 'unique';
    $suffix = 'value';
  
    if($scope) $vals = $scope;
    else $vals = $GLOBALS;

    $old = $var;
    $var = $new = $prefix.rand().$suffix; $vname = FALSE;
    foreach($vals as $key => $val) if($val === $new) $vname = $key;
    $var = $old;

    echo "<pre style='margin: 0px 0px 10px 0px; display: block; background: white; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 10px; line-height: 13px;'>";
    if($info != FALSE) echo "<b style='color: red;'>$info:</b><br>";
    $this->do_dump($var, '$'.$vname);
    echo "</pre>";
  }

  ////////////////////////////////////////////////////////
  // Function:         do_dump
  // Inspired from:     PHP.net Contributions
  // Description: Better GI than print_r or var_dump
  
  /**
   * @desc Exibe conteudo de variável ou objeto (função recursiva)
   * @param string &$var de ponteiro para Variável
   * @param string $var_name de Nome de Variável
   * @param string $indent de Identificação de Variável
   * @param string $reference de Referência de Variável 
  */
  function do_dump(&$var, $var_name = NULL, $indent = NULL, $reference = NULL)
  {
    $do_dump_indent = "<span style='color:#eeeeee;'>|</span> &nbsp;&nbsp; ";
    $reference = $reference.$var_name;
    $keyvar = 'the_do_dump_recursion_protection_scheme'; $keyname = 'referenced_object_name';

    if (is_array($var) && isset($var[$keyvar]))
    {
        $real_var = &$var[$keyvar];
        $real_name = &$var[$keyname];
        $type = ucfirst(gettype($real_var));
        echo "$indent$var_name <span style='color:#a2a2a2'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span><br>";
    }
    else
    {
        $var = array($keyvar => $var, $keyname => $reference);
        $avar = &$var[$keyvar];
    
        $type = ucfirst(gettype($avar));
        if($type == "String") $type_color = "<span style='color:green'>";
        elseif($type == "Integer") $type_color = "<span style='color:red'>";
        elseif($type == "Double"){ $type_color = "<span style='color:#0099c5'>"; $type = "Float"; }
        elseif($type == "Boolean") $type_color = "<span style='color:#92008d'>";
        elseif($type == "NULL") $type_color = "<span style='color:black'>";
    
        if(is_array($avar))
        {
            $count = count($avar);
            echo "$indent" . ($var_name ? "$var_name => ":"") . "<span style='color:#a2a2a2'>$type ($count)</span><br>$indent(<br>";
            $keys = array_keys($avar);
            foreach($keys as $name)
            {
                $value = &$avar[$name];
                $this->do_dump($value, "['$name']", $indent.$do_dump_indent, $reference);
            }
            echo "$indent)<br>";
        }
        elseif(is_object($avar))
        {
            echo "$indent$var_name <span style='color:#a2a2a2'>$type</span><br>$indent(<br>";
            foreach($avar as $name=>$value) $this->do_dump($value, "$name", $indent.$do_dump_indent, $reference);
            echo "$indent)<br>";
        }
        elseif(is_int($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color$avar</span><br>";
        elseif(is_string($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color\"$avar\"</span><br>";
        elseif(is_float($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color$avar</span><br>";
        elseif(is_bool($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color".($avar == 1 ? "TRUE":"FALSE")."</span><br>";
        elseif(is_null($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> {$type_color}NULL</span><br>";
        else echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $avar<br>";

        $var = $var[$keyvar];
    }
  }
  
  /**
   * @desc Envia email
   * @param string $email_param de parâmetros de envio de email
   * @param string $subject de assunto de email
   * @param string $message de conteúdo de mensagem
   * @param string $actor de ator de mensagem
   * @param string $from de receptor de mensagem 
  */
  function send_mail($email_param,$subject,$message,$actor,$from){
	$email_param = addslashes(trim($email_param));
	$html = "
		<html>
		<head>
		<title>" . $subject . "</title>
		<style type=\"text/css\">
		body {
			font-family: Verdana;
		}
		</style>
		</head>
		<body>
		<h4>" .$subject. "</h4>
		<h5>" .$message. "</h5>
		<br>
		<br>
		<br>
		<img src='http://www.semax.com.br:8080/navegador/images/logo.gif'  width='93px' height='44px' >
		<br>
		<i>" .$actor. "</i>
		</body>
		</html>
		";
		
		$headers  = "From: Semax Segurança Máxima<".$from.">\n";
		$headers .= "Reply-To: Semax Segurança Máxima<".$from.">\n";
		$headers .= "Return-Path: Semax Segurança Máxima<".$from.">\n";
		$headers .= "MIME-Version: 1.0\n"; 
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";

		return mail($email_param, $subject, $html, $headers);
  }

  /**
   * @desc Envia email com link para imagem
   * @param string $email_param de parâmetros de envio de email
   * @param string $subject de assunto de email
   * @param string $image de nome de imagem para envio de link
   * @param string $from de receptor de mensagem 
  */
  function send_mail_imagem($email_param,$subject,$imagem,$from){
	$email_param = addslashes(trim($email_param));
	$html = "
		<html>
		<head>
		<title>" . $subject . "</title>
		<style type=\"text/css\">
		body {
			font-family: Verdana;
		}
		</style>
		</head>
		<body>
		<img src='" . $imagem . "'>
		</body>
		</html>
		";
		
		$headers  = "From: Semax Segurança Máxima<".$from.">\n";
		$headers .= "Reply-To: Semax Segurança Máxima<".$from.">\n";
		$headers .= "Return-Path: Semax Segurança Máxima<".$from.">\n";
		$headers .= "MIME-Version: 1.0\n"; 
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";

		return mail($email_param, $subject, $html, $headers);
  }

  /**
   * @desc Retira Acentos de palavras
   * @param string $texto
   * @return string $ret de texto sem acentos 
  */
  function retira_acentos($texto){
  	$acentos = array("Á","Â","Ã","À","É","Ê","È","Í","Î","Ì","Ó","Ô","Õ","Ò","Ú","Û","Ù","Ç","'");
	$substitutos = array("A","A","A","A","E","E","E","I","I","I","O","O","O","O","U","U","U","C"," ");
	return str_replace($acentos, $substitutos, $texto);
  }

}