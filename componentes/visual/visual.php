<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe para tratamento de componentes visuais
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 22/03/2007 Atualizado: 22/03/2007
*/
class visual {

  /**
   * @desc Retorna sigla de mês com três letras
   * @return string de sigla de mês com três letras
   * @param string $numMes de Número de Mês
  */
  function mesSigla($numMes){
    if ($numMes == 1){
      return  "jan";
    }
  
    if ($numMes == 2){
      return  "feb";
    }

    if ($numMes == 3){
      return  "mar";
    }

    if ($numMes == 4){
      return  "apr";
    }

    if ($numMes == 5){
      return  "mai";
    }

    if ($numMes == 6){
      return  "jun";
    }

    if ($numMes == 7){
      return  "jun";
    }
  
    if ($numMes == 8){
      return  "ago";
    }
  
    if ($numMes == 9){
      return  "sep";
    }

    if ($numMes == 10){
      return  "oct";
    }

    if ($numMes == 11){
      return  "nov";
    }
  
    if ($numMes == 12){
      return  "dec";
    }  
	return "";
  }  
 
  /**
   * @desc Insere inputs para cadastro de data
   * @param string $nomeAno de Nome de objeto Ano
   * @param integer $valorAno de Valor do Ano
   * @param string $nomeMes de Nome de objeto Mês
   * @param integer $valorMes de Valor do Mês
   * @param string $nomeDia de Nome de objeto Dia
   * @param integer $valorDia de Valor do Dia
   * @param string $rotulo de Ródulo
   * @param string $estilo de Classe de estilo
  */
  function exibeData($nomeAno, $valorAno, $nomeMes, $valorMes, $nomeDia, $valorDia, $rotulo, $estilo){
    if (!$valorAno) {
  	  $valorAno = date("y");
    }
    if (!$valorMes) {
      $valorMes = date("m");
    }	
    if (!$valorDia) {
      $valorDia = date("d");
    }
    
    if ($rotulo) {
      $carregaRotulo = " ".$rotulo."&nbsp;";    	
    } else {
      $carregaRotulo = " ";	
    } 		 	
  
    //Rótulo
    echo $carregaRotulo;
    //Dia 
    $this->exibeInput($nomeDia,"text",$valorDia,'2',2, $estilo,"onChange='validaDia(\"".$nomeMes."\",\"".$nomeDia."\")'","");
    //Mês
    echo "&nbsp; / &nbsp;";
    $values = array('01','02','03','04','05','06','07','08','09','10','11','12');
    $texts = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
    $this->exibeSelect($nomeMes,$values,$texts,$valorMes,1,$estilo,"onChange='validaDia(\"".$nomeMes."\",\"".$nomeDia."\")'","");
    //Ano
    echo "&nbsp; / &nbsp;";
    $this->exibeInput($nomeAno,"text",$valorDia,'2',2, $estilo,"onChange='validaAno(\"".$nomeAno."\")'","");
    //Calendário visual
    echo "&nbsp;";
    $this->exibeInput("Calendario".$nomeAno.$nomeMes.$nomeDia,"button","Calendário",'20',20,$estilo," onClick='showWindow(\"./componentes/visual/calendario/calendar2.php?nomedia=".$nomeDia."&nomemes=".$nomeMes."&nomeano=".$nomeAno."\",\"calendario\",100,100,20,20)'","");
  }
  
  /**
   * @desc Insere inputs para cadastro de data e Hora
   * @param string $nomeAno de Nome de objeto Ano
   * @param integer $valorAno de Valor do Ano
   * @param string $nomeMes de Nome de objeto Mês
   * @param integer $valorMes de Valor do Mês
   * @param string $nomeDia de Nome de objeto Dia
   * @param integer $valorDia de Valor do Dia
   * @param string $nomeHora de Nome de objeto Hora
   * @param integer $valorHora de Valor da Hora
   * @param string $nomeMinuto de Nome de objeto Minuto
   * @param integer $valorMinuto de Valor de Minutos
   * @param string $rotulo de Ródulo
   * @param string $estilo de Classe de estilo
  */
  function exibeDataHora($nomeAno,$valueAno,$nomeMes,$valueMes,$nomeDia,$valueDia,$nomeHora,$valueHora,$nomeMinuto,$valueMinuto,$rotulo,$estilo){
    if (!$valorHora) {
  	  $valorHora = date("H");
    }
    if (!$valorMinuto) {
      $valorMinuto = date("n");
    }	
    $this->exibeData($nomeAno, $valorAno, $nomeMes, $valorMes, $nomeDia, $valorDia, $rotulo, $estilo);
    echo "&nbsp;&nbsp;";
    //Hora 
    $this->exibeInput($nomeHora,"text",$valorHora,'2',2, $estilo,"onChange='validaHora(\"".$nomeHora."\")'","");
    echo "&nbsp; : &nbsp;";
    //Minuto 
    $this->exibeInput($nomeMinuto,"text",$valorMinuto,'2',2, $estilo,"onChange='validaMinuto(\"".$nomeMinuto."\")'","");
  }
  
  /**
   * @desc Escreve input 
   * @param string $name de Nome de objeto 
   * @param string $type de Tipo do objeto input
   * @param string $value de Valor de objeto 
   * @param string $size de Tamanho do objeto
   * @param integer $maxlength de Tamanho máximo do objeto
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de objeto
   * @param string $rotulo de Ródulo de objeto input
  */
  function exibeInput($name, $type = "text", $value, $size = '20', $maxlength = 20, $estilo, $evento, $rotulo){
  	if ($estilo) {
      $carregaEstilo = " class = '".$estilo."' ";    	
    } else {
      $carregaEstilo = " ";	
    } 	
    if ($rotulo) {
      $carregaRotulo = " ".$rotulo."&nbsp;";    	
    } else {
      $carregaRotulo = " ";	
    } 	
	echo $carregaRotulo.'<input '.$carregaEstilo.' type = "'.$type.'" id ="'.$name.'" name ="'.$name.'" size = "'.$size.'" maxlength="'.$maxlength.'" value="'.$value.'" '.$evento.' >';
  }
  
  /**
   * @desc Escreve input com atualização xajax 
   * @param string $name de Nome de objeto 
   * @param string $type de Tipo do objeto input
   * @param string $value de Valor de objeto 
   * @param string $size de Tamanho do objeto
   * @param integer $maxlength de Tamanho máximo do objeto
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de objeto
   * @param string $rotulo de Ródulo de objeto input
  */
  function exibeInputXajax($name, $type = "text", $value, $size = '20', $maxlength = 20, $estilo, $evento, $rotulo, $id_campo, $chave){
  	global $negocio;
  	$evento .= " disabled ";
  	echo "<nobr nowrap>";
  	$this->exibeInput($name, $type, $value, $size, $maxlength, $estilo, $evento, $rotulo);
  	$this->exibeInput($name."_antigo", 'hidden', $value, $size, $maxlength, $estilo, "", "");
  	?>
  	<img src='./images/edit1.gif' id='btn_edit_<?php echo $name; ?>' style='cursor:pointer;' title='Edita informação' onClick='start_edit_input("<?php echo $name; ?>","<?php echo $name."_antigo"; ?>","<?php echo "btn_edit_".$name; ?>","<?php echo "btn_confirma_".$name; ?>", "<?php echo "btn_cancela_".$name; ?>" )' />
  	<img src='./images/button_ok.png' id='btn_confirma_<?php echo $name; ?>' style='cursor:pointer;display:none;' title='Confirma edição' onClick='confirm_edit_input("<?php echo $id_campo; ?>", "<?php echo $name; ?>", <?php echo $chave; ?>)' />
  	<img src='./images/delete.png' id='btn_cancela_<?php echo $name; ?>' style='cursor:pointer;display:none;' title='Cancela edição' onClick='cancel_edit_input("<?php echo $name; ?>","<?php echo $name."_antigo"; ?>","<?php echo "btn_edit_".$name; ?>","<?php echo "btn_confirma_".$name; ?>", "<?php echo "btn_cancela_".$name; ?>" )' /></nobr>
  	<?php
  }

  /**
   * @desc Escreve textarea
   * @param string $name de Nome de objeto 
   * @param string $texto de Conteúdo de Textarea
   * @param integer $cols de Numero de linhas
   * @param integer $rows de Numero de linhas
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de objeto
   * @param string $rotulo de Ródulo de objeto input
  */
  function exibeTextarea($name,$texto,$cols,$rows,$estilo,$evento,$rotulo){
  	if ($estilo) {
      $carregaEstilo = " class = '".$estilo."' ";    	
    } else {
      $carregaEstilo = " ";	
    } 	
    
    if ($rotulo) {
      $carregaRotulo = " ".$rotulo."&nbsp;";    	
    } else {
      $carregaRotulo = " ";	
    } 	
    echo $carregaRotulo."<textarea ".$carregaEstilo." name='".$name."' id='".$name."' cols='".$cols."' rows='".$rows."' ".$evento." >".$texto."</textarea>";
  }
  
  /**
   * @desc Escreve select 
   * @param string $name de Nome de objeto 
   * @param array $values de Lista de Value
   * @param array $texts de Lista de Text 
   * @param string $selected de value selecionado
   * @param integer $size de Linhas de Select
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de select
   * @param string $rotulo de Ródulo do objeto
  */
  function exibeSelect($name,$values,$texts,$selected,$size,$estilo,$evento,$rotulo){
  	if ($estilo) {
      $carregaEstilo = " class = '".$estilo."' ";    	
    } else {
      $carregaEstilo = " ";	
    } 	
    if ($rotulo) {
      $carregaRotulo = " ".$rotulo."&nbsp;";    	
    } else {
      $carregaRotulo = " ";	
    } 	
    echo $carregaRotulo."<select ".$carregaEstilo." id='".$name."'  name='".$name."' style='z-index:1;' size='".$size."' ".$evento." >\n";
    for ($contador = 0; $contador < sizeof($values) ;$contador++){
      if ($values[$contador] == $selected)  {
        echo "<option selected value = '".$values[$contador]."'>".$texts[$contador]."</option>\n";
      } else {
        echo "<option value = '".$values[$contador]."'>".$texts[$contador]."</option>\n";
      }
    }
    echo "</select>";
  }
  
  
   /**
   * @desc Escreve select de Mês
   * @param string $name de Nome de objeto 
   * @param integer $value de Mês selecionado
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de select
   * @param string $rotulo de Ródulo do objeto
  */
  function exibeSelectMes($name,$value,$estilo,$evento,$rotulo){
	 if(!$value)
	 {
		$value = date("m"); 
	 } 	
	 $values = array('1','2','3','4','5','6','7','8','9','10','11','12');  
	 $texts = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho',
	                'Julho', 'Agosto','Setembro','Outubro','Novembro','Dezembro');
	 $this->exibeSelect($name,$values,$texts,$value,1,$estilo,$evento,$rotulo);
  }
  
  /**
   * @desc Escreve select de Ano
   * @param string $name de Nome de objeto 
   * @param integer $value de Mês selecionado
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de select
   * @param string $rotulo de Ródulo do objeto
  */
  function exibeSelectAno($name,$value,$estilo,$evento,$rotulo){
	 if(!$value)
	 {
		$value = date("Y"); 
	 } 	
	 $values = array();
	 $texts = array();
	 for ($i = ($value - 5);$i <= ($value+5);$i++ ){
		$values[] = $i;
		$texts[] = $i;
	 }				
	 $this->exibeSelect($name,$values,$texts,$value,1,$estilo,$evento,$rotulo);
  }
  
  /**
   * @desc Escreve select de Hora
   * @param string $name de Nome de objeto 
   * @param integer $value de Hora selecionada
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de select
   * @param string $rotulo de Ródulo do objeto
  */
  function exibeSelectHora($name,$value,$estilo,$evento,$rotulo){
	 if(!$value)
	 {
		$value = date("H:i"); 
	 } 	
	$values = array();
	$texts = array();

	 for ($i = 0;$i < 24;$i++ ){
		$hora = $i;
		if ($hora < 10){
			$hora = '0'.$i;
		} 
		for ($j = 0;$j < 60;$j+=15){
			$minuto = $j;
			if ($minuto < 10){
				$minuto = '0'.$j;
			} 
			$values[] = $hora.":".$minuto;
			$texts[] = $hora.":".$minuto;
		}	
	 }				
	 $this->exibeSelect($name,$values,$texts,$value,1,$estilo,$evento,$rotulo);
  }
  
  /**
   * @desc Escreve select de Calendário
   * @param string $name de Nome de objeto 
   * @param integer $value de Mês selecionado
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de select
   * @param string $rotulo de Ródulo do objeto
  */
  function exibeSelectCalendario($name,$value,$estilo,$evento,$rotulo,$left,$top){
	 if (($value == -1) || (!$value && (strpos("_".$name,"se")> 0) )) 
	 {
		 $value = "00/00/0000";
	 }
	 
 	 if (!$value)
 	 {
		 $value = date("d/m/Y");		 
	 }
	 $this->exibeInput($name,"text",$value,"8","10",$estilo,' readonly ',$rotulo);
	 echo 	'&nbsp;<img src="./images/calendar.gif" title="Seleciona Data" onclick="seleciona_dia(\'container\', \''.$name.'\');" title="'.$rotulo.'" style="border: 0px none ; cursor: pointer;"  id="img_'.$name.'" width="15">';
	 if ($evento)
	 {
	 	echo '&nbsp;<img src="./images/edit1.gif" title="Grava informação" onclick="'.$evento.'" style="border: 0px none ; cursor: pointer;"  id="img_confirma_'.$name.'" width="15">';
	 }	 
	 return 0;
  }


  /**
   * @desc Desenha gráfico de barras
   * @param string $name de Nome do grafico
   * @param array $legend de lista de rotulos de legenda
   * @param array $data de lista de valores de gráfico
   * @param string $nome_x de Nome do Eixo X
   * @param string $nome_y de Nome do Eixo Y
  */
  function desenhaGraficoBarras($name,$legend,$data,$nome_x,$nome_y){
	 if (is_array($legend)){
		 $legend = implode(",",$legend);
	 }
	 if (is_array($data)){
		 $data = implode(",",$data);
	 }		 
	 ?>
		<a href="index.php?grupo=grafico&interface=barras5&titulo=<?php echo $name; ?>&legend=<?php echo $legend; ?>&data=<?php echo $data ?>&nome_x=<?php echo $nome_x ?>&nome_y=<?php echo $nome_y ?>" target="grafico">Visualizar Gráfico</a>
	 <?php
  }	

   /**
   * @desc Escreve Box Text
   * @param string $name de Nome de objeto 
   * @param integer $texto de Texto inicial
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de select
   * @param string $rotulo de Ródulo do objeto
  */
  function exibeBoxText($name,$texto,$estilo,$evento,$rotulo){
     $this->exibeInput($name,"hidden",$texto,"20","400",$estilo,"","");
 	 echo 	'<img src="./images/show.gif" title="" onclick="exibe_text(\'container\', \''.$name.'\', \''.$name.'\',0,\'\');" tilte="'.$rotulo.'" id="img_'.$name.'" width="15">'; 
	 return 0;
  }

  /**
   * @desc Escreve Box Text (retorna html link)
   * @param string $name de Nome de objeto 
   * @param integer $texto de Texto inicial
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de select
   * @param string $rotulo de Ródulo do objeto
   * @return string $html de link de BoxText
  */
  function exibeBoxTextHtml($name, $titulo, $texto, $estilo, $evento, $rotulo, $editavel = 0,$funcao_edicao = ""){
     $html = "";
     $this->exibeInput($name,"hidden",$texto,"20","400",$estilo,"","");
 	 $html = '<img src="./images/'.($editavel?'edit1.gif':'show.gif').'" onclick="exibe_text(\'container\', \''.$titulo.'\', \''.$name.'\',\''.$editavel.'\',\''.$funcao_edicao.'\');" title="'.$rotulo.'" id="img_'.$name.'" style="border: 0px none ; cursor: pointer;" width="15">'; 
	 return $html;
  }


  /**
   * @desc Escreve checkbox 
   * @param string $name de Nome de objeto 
   * @param integer $valor de Valor de objeto
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de objeto
   * @param string $rotulo de Ródulo de objeto input
   * @param integer $disable de Se checkBox esta habilitado ou não
  */
  function exibeCheckbox($name,$valor,$estilo,$evento,$rotulo,$disabled){
  	
  	if ($estilo) {
      $carregaEstilo = " class = '".$estilo."' ";    	
    } else {
      $carregaEstilo = " ";	
    } 	
  	
  	if ($rotulo) {
      $carregaRotulo = "&nbsp;".$rotulo."";    	
    } else {
      $carregaRotulo = " ";	
    } 
    
    if ($disabled) {
      $carregaDisabled = " disabled ";    	
    } else {
      $carregaDisabled = " ";	
    } 	
    
    if ($valor) {
      $carregaChecked = " checked ";    	
    } else {
      $carregaChecked = " ";	
    } 	
    
    echo "<input ".$carregaEstilo." id='".$name."' name='".$name."' type='checkbox' value='1' ".$carregaChecked."  ".$carregaDisabled." ".$evento." >".$carregaRotulo;
  }

  /**
   * @desc Escreve radio 
   * @param string $name de Nome de objeto 
   * @param array $lista_valor de Lista de Valores de CheckBox
   * @param array $lista_rotulo de Lista de Labels de CheckBox
   * @param string $valor_padrao de Valor de seleção atual de CheckBox (CSV)
   * @param string $classe de Classe de estilos
   * @param integer $chave de Código de Tabela Principal 
   * @param string $nome_funcao_xajax de Nome da Função Xajax de interação
  */
  function exibeCheckBoxLista($name, $lista_valor, $lista_rotulo, $valor_padrao, $classe, $chave, $nome_funcao_xajax){
  	for ($contador = 0; $contador < sizeof($lista_valor) ;$contador++){
  		if ($lista_valor[$contador]){
	  		?>
	  			<nobr nowrap><input class='<?php echo $classe; ?>' id='<?php echo $name."_".$chave."_".$lista_valor[$contador]; ?>' name='<?php echo $name."_".$chave."_".$lista_valor[$contador]; ?>' type='checkbox' value='1'
	  		<?php
			if (strpos("_,".$valor_padrao.",",",".$lista_valor[$contador].",")) {
				echo " checked ";
			} 
			?>
				onclick = '<?php echo $nome_funcao_xajax."(".$chave.",".$lista_valor[$contador].",this.checked)"; ?>'  ><?php echo $lista_rotulo[$contador]; ?></nobr><br> 
			<?php
  		}
	}
  }	

  /**
   * @desc Escreve radio 
   * @param string $name de Nome de objeto 
   * @param string $value de Valor de objeto
   * @param string $estilo de Classe de estilo do objeto
   * @param string $evento de Eventos de objeto
   * @param string $rotulo de Ródulo de objeto input
   * @param integer $disabled de Se checkBox esta habilitado ou não
  */
  function exibeRadio($name,$value,$checked,$estilo,$evento,$rotulo,$disabled){
    
    if ($rotulo) {
      $carregaRotulo = "&nbsp;".$rotulo."";    	
    } else {
      $carregaRotulo = " ";	
    } 
    
    if ($disabled) {
      $carregaDisabled = " disabled ";    	
    } else {
      $carregaDisabled = " ";	
    } 	
    
    if ($checked) {
      $carregaChecked = " checked ";    	
    } else {
      $carregaChecked = " ";	
    } 	
    
    echo "<input ".$carregaEstilo." id='".$name."' name='".$name."' type='radio' value='".$value."' ".$carregaChecked."  ".$carregaDisabled." ".$evento." >".$carregaRotulo;
  } 

  /**
   * @desc Escreve link 
   * @param string $titulo de Titulo do link
   * @param string $link de Link
   * @param string $imagem de Link
   * @param string $estilo de Classe de estilo do objeto
  */
  function exibeLink($titulo, $link, $imagem, $estilo){
  	$descricao = "";
  	if ($titulo){
  	  $descricao = $titulo;	
  	} 
  	if ($imagem){
  	  $descricao = " <img title='".$titulo."' src = './images/".$imagem."' >";	
  	}	
 	
  	if ($estilo) {
      $carregaEstilo = " class = '".$estilo."' ";    	
    } else {
      $carregaEstilo = " ";	
    } 
    echo "<a ".$carregaEstilo." href=".$link.">".$descricao."</a>";
  }

  /**
   * @desc Escreve Mais Menos 
   * @param string $elemento de Elemento Mostra/Esconde
   * @param string $nome da imagem
  */
  function exibeMaisMenos($nome,$elemento,$rotulo,$expandido = 1, $lista  = 0){
	echo "<script> var estado_".$nome." = ".($expandido?"0":"1")."; </script>";
	if ($lista){
		echo "<img id='".$nome."' name='".$nome."'  title='".$rotulo."' src='./images/".($expandido?"menos.JPG":"mais.JPG")."'  onclick=\"setDisplayControlList(".$elemento.",estado_".$nome.", '".$nome."', './images/mais.JPG','./images/menos.JPG'); estado_".$nome." = !(estado_".$nome.");\" />";
	}
	else
	{
		echo "<img id='".$nome."' name='".$nome."'  title='".$rotulo."' src='./images/".($expandido?"menos.JPG":"mais.JPG")."'  onclick=\"setDisplayControl('".$elemento."',estado_".$nome.", '".$nome."', './images/mais.JPG','./images/menos.JPG'); estado_".$nome." = !(estado_".$nome.");\" />";
	}
  }


  /**
   * @desc Desenha Cabeçalho 
   * @param string $title de titulo de página
  */
  function desenhaCabecalhoHTML($title){
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	    <html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title><?php echo $title; ?></title>
			</head>
			<body>
			<div id="container" name="container" style="display: none; position: absolute; z-index:9; backgroundColor:white;"></div>
		<?php	 
  }


  /**
   * @desc Desenha Rodapé 
  */
  function desenhaRodapeHTML(){
		?>
			</body>
		</html>
		<?php	 
  }
  

  /**
   * @desc Desenha campos para busca 
   * @param array de dados de Campo Tela
  */
  function desenha_campos($campo_tela){
    global $negocio;

  	//Carrega valor de campo
	$valor = "";
	if ($_GET[$campo_tela["nome"]])
	{
		$valor = $_GET[$campo_tela["nome"]];
	}
	elseif ($_POST[$campo_tela["nome"]])
	{
		$valor = $_POST[$campo_tela["nome"]];
	}
	
	switch ($campo_tela["nome_comportamento"]) {
		case "mes":
			$this->exibeSelectMes($campo_tela["nome"],$valor,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);			
			break;

	    case "ano":
			$this->exibeSelectAno($campo_tela["nome"],$valor,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
		    break;

	    case "calendario":
		    $this->exibeSelectCalendario($campo_tela["nome"],$valor,$campo_tela["classe"],$campo_tela["link"],$campo_tela["rotulo"],"270px","115px");
	        break;

		case "calendarioHora":
		    $this->exibeSelectCalendario($campo_tela["nome"]."_dia",$_POST[$campo_tela["nome"]."_dia"],$campo_tela["classe"],$campo_tela["link"],$campo_tela["rotulo"],$campo_tela["tamanho"],"115px");
			$this->exibeSelectHora($campo_tela["nome"]."_hora",$_POST[$campo_tela["nome"]."_hora"],$campo_tela["classe"],$campo_tela["acao_comportamento"],"");
			echo "&nbsp;";
	        break;

		case "prospeccao":
		    $lista_status = $negocio->listaStatusProspeccaoEdicao();
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
	        break;
			
		case "prospeccao_todos":
		    $lista_status = $negocio->listaStatusProspeccaoEdicao(1);
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
	        break;	

		case "consultor":
			$grupo = "";
			if ($_SESSION["navegador_tipo_usuario"] == 6)
			{
				$grupo = $_SESSION["navegador_grupo"];
			}

			$lista_status = $negocio->listaConsultor($grupo);
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
	        break;
		
		case "operador":
			$lista_status = $negocio->listaOperador();
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
	        break;
		
		case "produto":
			$lista_produto = $negocio->listaProdutosEdicao();
			$this->exibeSelect($campo_tela["nome"],$lista_produto["values"],$lista_produto["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
	        break;
		
		case "grupo":
			$grupo = "";
			if ($_SESSION["navegador_tipo_usuario"] == 6)
			{
				$grupo = $_SESSION["navegador_grupo"];
			}
		
			$lista_status = $negocio->listaGrupoConsultor($grupo);
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
	        break;

		case "tipo_tmkt":
			$lista_status = $negocio->listaTipoCliente();
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
			break;

		case "vaga":
			$lista_status = $negocio->listaVagaAtiva("filtro");
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
			break;
		
		case "dominio":
			$lista_status = $negocio->listaDominio($campo_tela["elemento"]);
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
			break;
			
		case "select_dominio":
			$lista_status = $negocio->listaDominio($campo_tela["link"]);
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
			break;
		break;
		
		case "select_tabela":
			$filtro = $campo_tela["tamanho"];
			$lista_status = $negocio->listaTabelaLink($campo_tela["link"], $filtro);
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
			break;
		break;
		
		case "select_tabela_texto":
			$valor_chave = str_replace(" ", "_", $campo_chave["valor"]);
			$lista_status = $negocio->listaTabelaLink($linha_consulta_campo["link"]);
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
		break;		
		
		case "tipo_dominio":
			$lista_status = $negocio->listaTipoDominio();
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
			break;	
			
		case "tipoWhere":
			$lista_tipo_where = $negocio->listaTipoWhere();
			$this->exibeSelect($campo_tela["nome"],$lista_tipo_where["values"],$lista_tipo_where["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
			break;	
			
		case "tabelaBD":
			$lista_tabela_bd = $negocio->buscaListaTabelaBD();
			$values = array();
			$labels = array();
			for ($num_tabela = 0; $num_tabela < count($lista_tabela_bd);$num_tabela++){
				$labels[] = $lista_tabela_bd[$num_tabela]["nome"];
				$values[] = $lista_tabela_bd[$num_tabela]["codigo"];
			}	
			$this->exibeSelect($campo_tela["nome"],$values,$labels,$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
			break;
		
		case "agrupamento": 
			$tem_grupo = 1;
			if ($_SESSION["navegador_tipo_usuario"] == 6)
			{
				$tem_grupo = 0;
			}
			$lista_status = $negocio->listaAgrupamento($tem_grupo);
			
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
			break;
			
		case "nome_cliente":
			$cliente = -1;
			if ($_GET["chave"])
			{
				$cliente = $_GET["chave"];
			}
			elseif ($_POST["chave"]){
				$cliente = $_POST["chave"];
			}
			echo "::".$negocio->nomeCliente($cliente)."::<br>";
			break;	

		case "razao_social":
			$cliente = -1;
			if ($_GET["chave"])
			{
				$cliente = $_GET["chave"];
			}
			elseif ($_POST["chave"]){
				$cliente = $_POST["chave"];
			}
			$this->exibeInput($campo_tela["nome"],$campo_tela["nome_comportamento"],$negocio->razaosocialCliente($cliente),$campo_tela["tamanho"],$campo_tela["maxlength"],$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
			break;	

		case "cnpj":
			$cliente = -1;
			if ($_GET["chave"])
			{
				$cliente = $_GET["chave"];
			}
			elseif ($_POST["chave"]){
				$cliente = $_POST["chave"];
			}

			$this->exibeInput($campo_tela["nome"],$campo_tela["nome_comportamento"],$negocio->cnpjCliente($cliente),$campo_tela["tamanho"],$campo_tela["maxlength"],$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
			break;	
		
		case "textarea":
			$this->exibeTextarea($campo_tela["nome"],$valor,60,5,$campo_tela["classe"],'',$campo_tela["rotulo"]);
			echo "<br>";
			break;

		default:
			$this->exibeInput($campo_tela["nome"],$campo_tela["nome_comportamento"],$valor,$campo_tela["tamanho"],$campo_tela["maxlength"],$campo_tela["classe"],$campo_tela["acao_comportamento"],$campo_tela["rotulo"]);
	}
  }


  /**
   * @desc Desenha campos para edição 
   * @param array de dados de Campo Tela
  */
  function desenha_campos_edicao($linha_consulta,$campo_tela,$exibe_rotulo){
	global $negocio, $funcoes; 
	
	//Procura por indice campo em linha de consulta
	$indice_campo = -1;
	for ($num_campo = 0; $num_campo < count($linha_consulta) ;$num_campo++){
		if ($linha_consulta[$num_campo]["nome"] == $campo_tela["nome"]){
			$indice_campo = $num_campo;
			$num_campo = count($linha_consulta);
		}	
	}
	
	//Carrega valor de campo
	$valor = "";
	if ($linha_consulta[$indice_campo]["valor"]){
		$valor = $linha_consulta[$indice_campo]["valor"];
	}
	elseif ($_GET[$campo_tela["nome"]])
	{
		$valor = $_GET[$campo_tela["nome"]];
	}
	elseif ($_POST[$campo_tela["nome"]])
	{
		$valor = $_POST[$campo_tela["nome"]];
	}
	
	
	//Carrega rotulo de campo
	$rotulo = "";
	if ($exibe_rotulo){
		$rotulo = $campo_tela["rotulo"];
	}
	
	
	//Desenha comportamento de campo
	switch ($campo_tela["nome_comportamento"]) {
		case "mes":
			$this->exibeSelectMes($campo_tela["nome"],$valor,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			break;
			
		case "ano":
			$this->exibeSelectAno($campo_tela["nome"],$valor,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			break;
			
		case "calendario":

			if (!$valor){
				$valor = -1;
			}
			$this->exibeSelectCalendario($campo_tela["nome"],$valor,$campo_tela["classe"],$campo_tela["link"],$rotulo,"270px","115px");
			break;
			
		case "calendarioHora":
			$this->exibeSelectCalendario($campo_tela["nome"]."_dia",$valor,$campo_tela["classe"],$campo_tela["link"],$rotulo,$campo_tela["tamanho"],"115px");
			$this->exibeSelectHora($campo_tela["nome"]."_hora",$valor,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			echo "&nbsp;";
			break;		
		
		case "prospeccao":
			$lista_status = $negocio->listaStatusProspeccaoEdicao();
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			break;	
			
		case "consultor":
			$grupo = "";
			if ($_SESSION["navegador_tipo_usuario"] == 6)
			{
				$grupo = $_SESSION["navegador_grupo"];
			}
			$lista_status = $negocio->listaConsultor($grupo);
			
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			break;
			
		case "produto":
			$lista_produto = $negocio->listaProdutosEdicao();
			$this->exibeSelect($campo_tela["nome"],$lista_produto["values"],$lista_produto["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
	        break;

			
		case "grupo":
			$lista_status = $negocio->listaGrupoConsultor();
			
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			break;
			
		case "tipo_tmkt":
			$lista_status = $negocio->listaTipoCliente();
			
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			break;
		
		case "tipo_equipamento":
			$lista_status = $negocio->listaTipoEquipamento();
			
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			break;	
			
		case "vaga":
			$lista_status = $negocio->listaVagaAtiva();
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			break;

		case "id_trat":
			$lista_status = $negocio->listaTipoTratamento();
			
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			break;
		
		case "tipo_proposta":
			$lista_status = $negocio->listaTipoProposta();
			
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			break;
			
		case "tipoWhere":
			$lista_tipo_where = $negocio->listaTipoWhere();
			$this->exibeSelect($campo_tela["nome"],$lista_tipo_where["values"],$lista_tipo_where["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);
			break;	
		
		case "checkbox":
			$this->exibeCheckBox($campo_tela["nome"],$valor,$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo,0);
			break;
			
		case "nome_cliente":
			$cliente = -1;
			if ($_GET["chave"])
			{
				$cliente = $_GET["chave"];
			}
			elseif ($_POST["chave"]){
				$cliente = $_POST["chave"];
			}
			
			echo $negocio->nomeCliente($cliente);
			break;
		
		case "telefone_cliente":
			$cliente = -1;
			if ($_GET["chave"])
			{
				$cliente = $_GET["chave"];
			}
			elseif ($_POST["chave"]){
				$cliente = $_POST["chave"];
			}
			echo $negocio->telefoneCliente($cliente);
			break;
		
		case "dominio":
			$lista_status = $negocio->listaDominio($campo_tela["link"]);
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],'');
			break;
			
		case "select_dominio":
			$lista_status = $negocio->listaDominio($campo_tela["link"]);
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],'');
			break;
		break;
		
		case "select_tabela":
			
			$lista_status = $negocio->listaTabelaLink($campo_tela["link"]);
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],'');
			break;
		break;
		
		case "select_tabela_texto":
			$valor_chave = str_replace(" ", "_", $campo_chave["valor"]);
			$lista_status = $negocio->listaTabelaLink($linha_consulta_campo["link"]);
			$this->exibeSelect($linha_consulta_campo["nome"]."_".$valor_chave,$lista_status["values"],$lista_status["labels"],$linha_consulta_campo["valor"],1,$linha_consulta_campo["classe"]," onChange = 'xajax_gravar_campo(".$linha_consulta_campo["ligacoes"][0]["campo_tabela"].",this.value,\"".$valor_chave."\",\"".$linha_consulta_campo["nome"]."_".$valor_chave."\",\"select\")'",'');
		break;	

		case "tipo_dominio":
			$lista_status = $negocio->listaTipoDominio();
			$this->exibeSelect($campo_tela["nome"],$lista_status["values"],$lista_status["labels"],$valor,1,$campo_tela["classe"],$campo_tela["acao_comportamento"],'');
			break;	
		
		case "textarea":
			$this->exibeTextarea($campo_tela["nome"],$valor,60,5,$campo_tela["classe"],'',$rotulo);
			break;
		
		default:
			$this->exibeInput($campo_tela["nome"],$campo_tela["nome_comportamento"],$valor,$campo_tela["tamanho"],$campo_tela["maxlength"],$campo_tela["classe"],$campo_tela["acao_comportamento"],$rotulo);

	}
  }
  
  /**
   * @desc Desenha campos de listagem 
   * @param array de dados de Campo Tela
  */
  function desenha_campos_listagem($linha_consulta_campo, $campo_chave){
  	global $campo_valor, $funcoes, $negocio;

  	$campo_valor[$linha_consulta_campo["nome"]] = $linha_consulta_campo["valor"];
	
  	//Desenha comportamento de campo
	switch ($linha_consulta_campo["nome_comportamento"]) {
		
		case "checkbox":
			$disabled = 1;
			if ($linha_consulta_campo["valor"]) 
			{
				$disabled = 0;
			}	
			$this->exibeCheckBox($linha_consulta_campo["nome"]."_".$linha_consulta_campo["valor"],$_POST[$linha_consulta_campo["nome"]."_".$linha_consulta_campo["valor"]],"editBox","onClick=coloreDescolore(this,'tr_".$campo_chave["valor"]."');","",$disabled);
		
		break;	  	
		
		case "calendarioHora":
			
			if ($linha_consulta_campo["valor"]){
				$vetor_data = explode(" ",$linha_consulta_campo["valor"]);
				$data = $vetor_data[0];
				$hora = $vetor_data[1];
			}
			else
			{
				$data = -1;
				$hora = "00:00";
			}	

			if ($linha_consulta_campo["maxlength"]) {
				$maxlength = $linha_consulta_campo["maxlength"].'px';
			}
			else
			{
				$maxlength = '500px';
			}

			$this->exibeSelectCalendario($linha_consulta_campo["nome"]."_".$campo_chave["valor"]."_data",$data,"editBox","","",$maxlength,(170+($i*30))."px");

			$this->exibeSelectHora($linha_consulta_campo["nome"]."_".$campo_chave["valor"]."_hora",$hora,"editBox","","");			
		
		break;

		case "edit":
			$this->exibeInput($linha_consulta_campo["nome"]."_".$campo_chave["valor"],"text",$linha_consulta_campo["valor"],'23',$linha_consulta_campo["maxlength"],"editBox",$linha_consulta_campo["acao_comportamento"],"");			
		break;
  	
		case "editXajax":
			if ($campo_chave["valor"]){
				$this->exibeInputXajax($linha_consulta_campo["nome"]."_".$campo_chave["valor"],"text",$linha_consulta_campo["valor"],$linha_consulta_campo["tamanho"],$linha_consulta_campo["maxlength"],"editBox",$linha_consulta_campo["acao_comportamento"],"",$linha_consulta_campo["ligacoes"][0]["campo_tabela"],$campo_chave["valor"]);
			}
			else
			{
				echo $linha_consulta_campo["valor"]."&nbsp"; 
			}					
		break;
		
		case "moedaXajax":
			$this->exibeInputXajax($linha_consulta_campo["nome"]."_".$campo_chave["valor"],"text",$linha_consulta_campo["valor"],$linha_consulta_campo["tamanho"],$linha_consulta_campo["maxlength"],"editBox"," onKeyDown='FormataMoeda(this,event)' ","",$linha_consulta_campo["ligacoes"][0]["campo_tabela"],$campo_chave["valor"]);			
		break;
		
		case "cnpjXajax":
			$this->exibeInputXajax($linha_consulta_campo["nome"]."_".$campo_chave["valor"],"text",$linha_consulta_campo["valor"],$linha_consulta_campo["tamanho"],$linha_consulta_campo["maxlength"],"editBox"," onKeyPress='FormataCnpj(this,event)' ","",$linha_consulta_campo["ligacoes"][0]["campo_tabela"],$campo_chave["valor"]);			
		break;
		
		case "calendarioXajax":
			if ($linha_consulta_campo["valor"]){
				$data = $linha_consulta_campo["valor"];
			}
			else
			{
				$data = -1;
			}
			echo "<nobr nowrap>";	
			$this->exibeSelectCalendario($linha_consulta_campo["nome"]."_".$campo_chave["valor"],$data,"editBox","xajax_gravar_campo(".$linha_consulta_campo["ligacoes"][0]["campo_tabela"].",document.getElementById('".$linha_consulta_campo["nome"]."_".$campo_chave["valor"]."').value,".$campo_chave["valor"].",'".$linha_consulta_campo["nome"]."_".$campo_chave["valor"]."','select')","","100px","100px");
			echo "</nobr>";			
		break;
		
		case "dominio":
			$lista_status = $negocio->listaDominio($linha_consulta_campo["link"]);
			$this->exibeSelect($linha_consulta_campo["nome"]."_".$campo_chave["valor"],$lista_status["values"],$lista_status["labels"],$linha_consulta_campo["valor"],1,$linha_consulta_campo["classe"],$linha_consulta_campo["acao_comportamento"],'');
		break;
		
		case "select_dominio":
			$lista_status = $negocio->listaDominio($linha_consulta_campo["link"]);
			if ($campo_chave["valor"]){
				$this->exibeSelect($linha_consulta_campo["nome"]."_".$campo_chave["valor"],$lista_status["values"],$lista_status["labels"],$linha_consulta_campo["valor"],1,$linha_consulta_campo["classe"]," onChange = 'xajax_gravar_campo(".$linha_consulta_campo["ligacoes"][0]["campo_tabela"].",this.value,".$campo_chave["valor"].",\"".$linha_consulta_campo["nome"]."_".$campo_chave["valor"]."\",\"select\")'",'');
			}
			else
			{
				$this->exibeSelect($linha_consulta_campo["nome"]."_".$campo_chave["valor"],$lista_status["values"],$lista_status["labels"],$linha_consulta_campo["valor"],1,$linha_consulta_campo["classe"]," disabled ",''); 
			}		
		break;
		
		case "select_tabela":
			$lista_status = $negocio->listaTabelaLink($linha_consulta_campo["link"]);
			$this->exibeSelect($linha_consulta_campo["nome"]."_".$campo_chave["valor"],$lista_status["values"],$lista_status["labels"],$linha_consulta_campo["valor"],1,$linha_consulta_campo["classe"]," onChange = 'xajax_gravar_campo(".$linha_consulta_campo["ligacoes"][0]["campo_tabela"].",this.value,".$campo_chave["valor"].",\"".$linha_consulta_campo["nome"]."_".$campo_chave["valor"]."\",\"select\")'",'');
		break;
		
		case "select_tabela_texto":
			$valor_chave = str_replace(" ", "_", $campo_chave["valor"]);
			$lista_status = $negocio->listaTabelaLink($linha_consulta_campo["link"]);
			$this->exibeSelect($linha_consulta_campo["nome"]."_".$valor_chave,$lista_status["values"],$lista_status["labels"],$linha_consulta_campo["valor"],1,$linha_consulta_campo["classe"]," onChange = 'xajax_gravar_campo(".$linha_consulta_campo["ligacoes"][0]["campo_tabela"].",this.value,\"".$valor_chave."\",\"".$linha_consulta_campo["nome"]."_".$valor_chave."\",\"select\")'",'');
		break;
		
		case "textarea":
			$this->exibeTextarea($linha_consulta_campo["nome"]."_".$campo_chave["valor"],$linha_consulta_campo["valor"],60,5,"editBox",'readonly','');
		break;
		
		case "checkbox_dominio":
			$lista_status = $negocio->listaDominio($linha_consulta_campo["link"]);
			$this->exibeCheckBoxLista($linha_consulta_campo["nome"],$lista_status["values"],$lista_status["labels"],$linha_consulta_campo["valor"],$linha_consulta_campo["classe"],$campo_chave["valor"],$linha_consulta_campo["elemento"]);
		break;	

		default:
			if ($linha_consulta_campo["link"]){
				$ancoras = $funcoes->procuraAncoras($linha_consulta_campo["link"]);
				$valores = array();
	  		    for ($k = 0; $k < count($ancoras); $k++){
					$valores[] = $campo_valor[$ancoras[$k]];
				}	
				$link = $funcoes->substituiAncoras($linha_consulta_campo["link"],$ancoras,$valores);
				$this->exibeLink($linha_consulta_campo["valor"], $link, 0, "tabLink"); 
			}
			else
			{
				echo $linha_consulta_campo["valor"]."&nbsp"; 
			}
			
	}
  }	
}