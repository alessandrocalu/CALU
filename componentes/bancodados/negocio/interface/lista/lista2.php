<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Template para Interface de Listagem simples
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 18/01/2008 Atualizado: 18/01/2008
*/
session_start();
$campos_tela =  $negocio->getCamposTela();
$total_campos_tabela = 0; 
//Conta campos de tabela de listagem
for ($i = 0; $i < count($campos_tela); $i++){
	if ($campos_tela[$i]["grupo"] == "tabela") {
		$total_campos_tabela++;
		$estilo_tabela = $campos_tela[$i]["classe"];
	}
}
$dados_consulta = $negocio->getDadosTela();
$total_dados = count($dados_consulta);

?>
<html>
<head>
<title><? echo $negocio->getNomeTela(); ?></title>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<script src="./js/cria_elemento.js"></script>
<script src="./js/calendario.js"></script>
<script src="./js/functions.js"></script>
<style type=text/css>
/* Arredondamento */.rtop,.rbottom{display:block} .rtop *,.rbottom *{display:block;height: 1px;overflow: hidden}.r1{margin: 0 5px}.r2{margin: 0 3px}.r3{margin: 0 2px}.r4{margin: 0 1px;height: 2px}.rs1{margin: 0 2px}.rs2{margin: 0 1px}
</style>
</head>
<body>
<script>
  //Arredondamento
  function NiftyCheck() { if(!document.getElementById || !document.createElement) return(false); var b=navigator.userAgent.toLowerCase(); if(b.indexOf("msie 5")>0 && b.indexOf("opera")==-1) return(false); return(true); }
  function Rounded(selector,bk,color,size){ var i; var v=getElementsBySelector(selector); var l=v.length; for(i=0;i<l;i++){ AddTop(v[i],bk,color,size); AddBottom(v[i],bk,color,size); } }
  function RoundedTop(selector,bk,color,size){ var i; var v=getElementsBySelector(selector); for(i=0;i<v.length;i++) AddTop(v[i],bk,color,size); }
  function RoundedBottom(selector,bk,color,size){ var i; var v=getElementsBySelector(selector); for(i=0;i<v.length;i++) AddBottom(v[i],bk,color,size); }
  function AddTop(el,bk,color,size){ var i; var d=document.createElement("b"); var cn="r"; var lim=4; if(size && size=="small"){ cn="rs"; lim=2} d.className="rtop"; d.style.backgroundColor=bk; for(i=1;i<=lim;i++){ var x=document.createElement("b"); x.className=cn + i; x.style.backgroundColor=color; d.appendChild(x); } el.insertBefore(d,el.firstChild); }
  function AddBottom(el,bk,color,size){ var i; var d=document.createElement("b"); var cn="r"; var lim=4; if(size && size=="small"){ cn="rs"; lim=2} d.className="rbottom"; d.style.backgroundColor=bk; for(i=lim;i>0;i--){ var x=document.createElement("b"); x.className=cn + i; x.style.backgroundColor=color; d.appendChild(x); } el.appendChild(d,el.firstChild); }
  function getElementsBySelector(selector){ var i; var s=[]; var selid=""; var selclass=""; var tag=selector; var objlist=[]; if(selector.indexOf(" ")>0){ s=selector.split(" "); var fs=s[0].split("#"); if(fs.length==1) return(objlist); return(document.getElementById(fs[1]).getElementsByTagName(s[1])); } if(selector.indexOf("#")>0){ s=selector.split("#"); tag=s[0]; selid=s[1]; } if(selid!=""){ objlist.push(document.getElementById(selid)); return(objlist); } if(selector.indexOf(".")>0){ s=selector.split("."); tag=s[0]; selclass=s[1]; } var v=document.getElementsByTagName(tag); if(selclass=="") return(v); for(i=0;i<v.length;i++){ if(v[i].className==selclass){ objlist.push(v[i]); } } return(objlist); }

  function marcaDesmarca(marca){
  <? 
	for ($i = 0; $i < count($dados_consulta); $i++){ 
		for ($j = 0; $j < count($dados_consulta[$i]); $j++){ 
			if (($dados_consulta[$i][$j]["nome_comportamento"] == "checkbox") && ($dados_consulta[$i][$j]["valor"])){
				 echo "eval('document.getElementById(\'".$dados_consulta[$i][$j]["nome"]."_".$dados_consulta[$i][$j]["valor"]."\').checked = marca.checked');\n ";
				 echo "eval('coloreDescolore(document.getElementById(\'".$dados_consulta[$i][$j]["nome"]."_".$dados_consulta[$i][$j]["valor"]."\'),\'tr_".$dados_consulta[$i][$j]["valor"]."\')');\n ";
			}
		}
	}
	?>	
}

var corAnterior;
function coloreDescolore(objeto,passivo){
	try{
		if (objeto.checked){
			document.getElementById(passivo).bgColor = '#FF6F6F';
		}
		else
		{
			document.getElementById(passivo).bgColor = corAnterior;
		}
	}
	catch(e){ }
}

function confirma(acao,nome,janela)
{
	var fazer = false;
	<?
	//Incluir validação de campos aqui
	for ($j = 0; $j < count($campos_tela); $j++){
		if ($campos_tela[$j]["grupo"] == "filtro"){
			if ($campos_tela[$j]["nome_comportamento"] == "mes") {
				?>
				if(document.getElementById(<? echo "'".$campos_tela[$j]["nome"]."'" ?>).value < 1) {
					alert(<? echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>);
					return 0;
				}
				<?
			}
			elseif ($campos_tela[$j]["nome_comportamento"] == "ano")
			{
				?>
				if(document.getElementById(<? echo "'".$campos_tela[$j]["nome"]."'" ?>).value < 1) {
					alert(<? echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>);				
					return 0;
				}
				<?
			}
			elseif ($campos_tela[$j]["nome_comportamento"] == "calendario")
			{
				?>
				if(document.getElementById(<? echo "'".$campos_tela[$j]["nome"]."'" ?>).value == "") {
					alert(<? echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>); 
					return 0;
				}
				<?
			}
			elseif ($campos_tela[$j]["nome_comportamento"] == "calendarioHora")
			{
				?>
				if(document.getElementById(<? echo "'".$campos_tela[$j]["nome"]."_dia'" ?>).value == "") {
					alert(<? echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>); 
					return 0;
				}
				<?
			}
			else
			{
				if ($campos_tela[$j]["elemento"] && ($campos_tela[$j]["nome_comportamento"] != "dominio") )
				{
					?>
					if(document.getElementById(<? echo "'".$campos_tela[$j]["nome"]."'" ?>).value.length < <? echo $campos_tela[$j]["elemento"]; ?>) {
						alert(<? echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>); 
						return 0;
					}
					<?
				}	
			}
		}
	}
	?>
    if (nome != ''){
	    if (confirm(nome)){
			fazer = true;	
		}
	}
	else
	{
		fazer = true;
	}

	if (janela != ''){
		document.getElementById("formulario").target = janela;
	}
	else
	{
		document.getElementById("formulario").target = '';
	}
	
	if (fazer) {
		document.getElementById("formulario").action = acao;
		document.getElementById("formulario").submit();	
	}
}

function confirma2(acao,nome)
{
	var fazer = false;
	if (nome != ''){
	    if (confirm(nome)){
			fazer = true;	
		}
	}
	else
	{
		fazer = true;
	}
	if (fazer) {
		//Incluir validação de campos aqui
		document.getElementById("formulario").action = acao;
		document.getElementById("formulario").submit();	
	}
}

</script>
<!--
<table width="100%" >
  <tr>
    <td align = "left" width="1%"  height="44px">
      <img src="./images/logo.gif"  width="93px" height="44px" >
    </td>
    <td width="99%" height="70px" > 	
	  <div align='center' class='cerca_bubble' style='width:100%; height:70px' style="margin-bottom:5">
		<table width=100% align=center cellpadding=2 cellspacing=2 bgcolor=#DFEFD7 >
		  <tr>
            <td>
			   <div style="font-size:20px;text-align:center;" >	
			   		<b><? echo $negocio->getDescricaoTela(); ?></b>
			   </div>
		   </td>
   	     </tr>
		 <tr>
			<td>
  			   <img src="./images/linhaHorizontal.gif"  width="100%" height="1">
			   <ul id="mainlevel-nav">
			   <? 
			      $itens_menu = $negocio->getItensMenu(); 
			      for ($i = 0; $i < count($itens_menu); $i++) { 
			   ?>         
			       <li> 
				   <?
				      $visual->exibeLink($itens_menu[$i]["nome"], $itens_menu[$i]["link"], "", "mainlevel-nav");
				   ?>
				   </li>
			  <?
				  }
              ?>
			  </ul>
			</td>
         </tr>
       </table>
	 </div> 
	 <script>
	 	Rounded('div.cerca_bubble','#FFFFFF', '#DFEFD7');
	 </script>
	</td>
   </tr>						
</table>
-->
<table  width="100%" >
  <form id="formulario" name="formulario" method="post" >
  <tr>
    <td align = "left">
		<div id="listagem"> 
			<table class="tab" width="100%" border="0" cellspacing="0">  
				<tr>
					<td>
					   <table class="tab" width="100%" border="0" cellspacing="0">
 					    <tr>
  						   <th class="<? echo $estilo_tabela; ?>" align="left" colspan=<? echo $total_campos_tabela; ?> >
							<? 
								function trara_campos($i,$j){
									global $visual,$campos_tela, $acoes_tela, $negocio; 
									if ($campos_tela[$j]["nome_comportamento"] == "mes") {
										$visual->exibeSelectMes($campos_tela[$j]["nome"],$_POST[$campos_tela[$j]["nome"]],$campos_tela[$j]["classe"],$campos_tela[$j]["acao_comportamento"],$campos_tela[$j]["rotulo"]);
									}
									elseif ($campos_tela[$j]["nome_comportamento"] == "ano")
									{
										$visual->exibeSelectAno($campos_tela[$j]["nome"],$_POST[$campos_tela[$j]["nome"]],$campos_tela[$j]["classe"],$campos_tela[$j]["acao_comportamento"],$campos_tela[$j]["rotulo"]);
									}
									elseif ($campos_tela[$j]["nome_comportamento"] == "calendario")
									{
										$visual->exibeSelectCalendario($campos_tela[$j]["nome"],$_POST[$campos_tela[$j]["nome"]],$campos_tela[$j]["classe"],$campos_tela[$j]["link"],$campos_tela[$j]["rotulo"],"270px","115px");
									}
									elseif ($campos_tela[$j]["nome_comportamento"] == "calendarioHora")
									{
										$visual->exibeSelectCalendario($campos_tela[$j]["nome"]."_dia",$_POST[$campos_tela[$j]["nome"]."_dia"],$campos_tela[$j]["classe"],$campos_tela[$j]["link"],$campos_tela[$j]["rotulo"],$campos_tela[$j]["tamanho"],"115px");
										$visual->exibeSelectHora($campos_tela[$j]["nome"]."_hora",$_POST[$campos_tela[$j]["nome"]."_hora"],$campos_tela[$j]["classe"],$campos_tela[$j]["acao_comportamento"],"");
													echo "&nbsp;";
									}
									elseif ($campos_tela[$j]["nome_comportamento"] == "prospeccao")
									{
										$lista_status = $negocio->listaStatusProspeccaoEdicao();
											
										$visual->exibeSelect($campos_tela[$j]["nome"],$lista_status["values"],$lista_status["labels"],$_POST[$campos_tela[$j]["nome"]],1,$campos_tela[$j]["classe"],$campos_tela[$j]["acao_comportamento"],$campos_tela[$j]["rotulo"]);
									}

									else
									{
										$visual->exibeInput($campos_tela[$j]["nome"],$campos_tela[$j]["nome_comportamento"],$_POST[$campos_tela[$j]["nome"]],$campos_tela[$j]["tamanho"],$campos_tela[$j]["maxlength"],$campos_tela[$j]["classe"],$campos_tela[$j]["acao_comportamento"],$campos_tela[$j]["rotulo"]);
									}

								}
								$acoes_tela = $negocio->getAcoesTela(); 
							    for ($i = 0; $i < count($acoes_tela); $i++){
									if ($acoes_tela[$i]["nome_tipo"] <> "Busca" ) {									

										if ($acoes_tela[$i]["nome_tipo"] == "Campos"){
											for ($j = 0; $j < count($campos_tela); $j++){
												if ($campos_tela[$j]["grupo"] == $acoes_tela[$i]["id_tela_acao"]){
													$visual->desenha_campos($campos_tela[$j]);
												//	trara_campos($i,$j);
												}
											}
											$visual->exibeInput("acao_top_".$acoes_tela[$i]["id_tela_acao"],"button",$acoes_tela[$i]["nome"] ,'20','0',""," onClick=\"".$acoes_tela[$i]["link"]."\"","");	
										}										
									}
									else
									{
										?>
										&nbsp;&nbsp;&nbsp;
										<?
										//Cria formulário de busca
										$tem_filtro = 0;
										for ($j = 0; $j < count($campos_tela); $j++){
											if ($campos_tela[$j]["grupo"] == "filtro"){
												$tem_filtro = 1;
												$visual->desenha_campos($campos_tela[$j]);
												//trara_campos($i,$j);
											}	
										}
										if ($tem_filtro){
											$visual->exibeInput("acao_top_".$acoes_tela[$i]["id_tela_acao"],"button",$acoes_tela[$i]["nome"] ,'20','0',""," onClick=\"".$acoes_tela[$i]["link"]."\"","");	
										}
									}
									?>
									&nbsp;
									<?
								}
							?>
							</th>
 					    </tr>	
		   				<tr>
						<?
						for ($i = 0; $i < count($campos_tela); $i++){
							if ($campos_tela[$i]["grupo"] == "tabela"){
								if ($campos_tela[$i]["nome_comportamento"] == "checkbox") {
								?>
									<th class="<? echo $campos_tela[$i]["classe"]; ?>"  width="<? echo $campos_tela[$i]["tamanho"]; ?>">
									<?
								     $visual->exibeCheckBox($campos_tela[$i]["nome"]."_todos","0","editBox"," onClick=marcaDesmarca(this);","",0);
								    ?>	 
									</th>
								<?
								}
								else
								{
								?>
									<th class="<? echo $campos_tela[$i]["classe"]; ?>"  width="<? echo $campos_tela[$i]["tamanho"]; ?>"> 
								<?
									if (($campos_tela[$i]["nome_comportamento"] == "ordem") ||     
									    ($campos_tela[$i]["nome_comportamento"] == "calendarioHora") ||
									    (($campos_tela[$i]["nome_comportamento"] == "edit") && $campos_tela[$i]["elemento"] != "no") ) 
									{
								?>
									  <A HREF="javascript:confirma2('./index.php?local=<? echo $_GET['local'];?>&ordem=<? echo $campos_tela[$i]["elemento"]; ?>','')"  class='topLinkButton' >
								<?
									}
								    echo $campos_tela[$i]["rotulo"]; 

									if (($campos_tela[$i]["nome_comportamento"] == "ordem") ||     
									    ($campos_tela[$i]["nome_comportamento"] == "calendarioHora") ||		(($campos_tela[$i]["nome_comportamento"] == "edit") && $campos_tela[$i]["elemento"] != "no")) 
									{
									?>
								       </a>   
									<?
									}
									?>	
								   </th>
								<?
								}
							}
						}
						?>
						</tr>
						<form id="lista" name="lista" method="post" >
						<? if ($dados_consulta) { 
								$campo_valor = array();
							?>
							<? for ($i = 0; $i < count($dados_consulta); $i++){ ?>
							<tr id="tr_<? echo $dados_consulta[$i][0]["valor"]; ?>" <? echo ($_POST[$dados_consulta[$i][0]["nome"]."_".$dados_consulta[$i][0]["valor"]]?"bgColor='#FF6F6F'":""); ?> >
								<? for ($j = 0; $j < count($dados_consulta[$i]); $j++){ ?>
	  							<td class=<? echo $dados_consulta[$i][$j]["classe"]; ?> >
								<?
									$campo_valor[$dados_consulta[$i][$j]["nome"]] = $dados_consulta[$i][$j]["valor"]; 
									if ($dados_consulta[$i][$j]["nome_comportamento"] == "checkbox") {
										$disabled = 1;
										if ($dados_consulta[$i][$j]["valor"]) 
										{
											$disabled = 0;
										}	

										$visual->exibeCheckBox($dados_consulta[$i][$j]["nome"]."_".$dados_consulta[$i][$j]["valor"],$_POST[$dados_consulta[$i][$j]["nome"]."_".$dados_consulta[$i][$j]["valor"]],"editBox","onClick=coloreDescolore(this,'tr_".$dados_consulta[$i][0]["valor"]."');","",$disabled);
									}
									elseif ($dados_consulta[$i][$j]["nome_comportamento"] == "calendarioHora") {
										
										if ($dados_consulta[$i][$j]["valor"]){
											$vetor_data = explode(" ",$dados_consulta[$i][$j]["valor"]);
											$data = $vetor_data[0];
											$hora = $vetor_data[1];
										}
										else
										{
											$data = -1;
											$hora = "00:00";
										}	

										if ($dados_consulta[$i][$j]["maxlength"]) {
											$maxlength = $dados_consulta[$i][$j]["maxlength"].'px';
										}
										else
										{
											$maxlength = '500px';
										}

										$visual->exibeSelectCalendario($dados_consulta[$i][$j]["nome"]."_".$dados_consulta[$i][0]["valor"]."_data",$data,"editBox","","",$maxlength,(170+($i*30))."px");

										$visual->exibeSelectHora($dados_consulta[$i][$j]["nome"]."_".$dados_consulta[$i][0]["valor"]."_hora",$hora,"editBox","","");
										
									}
									elseif ($dados_consulta[$i][$j]["nome_comportamento"] == "edit")  {
										$visual->exibeInput($dados_consulta[$i][$j]["nome"]."_".$dados_consulta[$i][0]["valor"],"text",$dados_consulta[$i][$j]["valor"],'23',$dados_consulta[$i][$j]["maxlength"],"editBox",$dados_consulta[$i][$j]["acao_comportamento"],"");
									}
									elseif ($dados_consulta[$i][$j]["nome_comportamento"] == "textarea") {								
										$visual->exibeTextarea($dados_consulta[$i][$j]["nome"]."_".$dados_consulta[$i][0]["valor"],$dados_consulta[$i][$j]["valor"],60,5,"editBox",'readonly','');
									}
									else	
									{
										if ($dados_consulta[$i][$j]["link"]){
											$ancoras = $funcoes->procuraAncoras($dados_consulta[$i][$j]["link"]);
											$valores = array();
	  									    for ($k = 0; $k < count($ancoras); $k++){
												$valores[] = $campo_valor[$ancoras[$k]];
											}	
											$link = $funcoes->substituiAncoras($dados_consulta[$i][$j]["link"],$ancoras,$valores);
											$visual->exibeLink($dados_consulta[$i][$j]["valor"], $link, 0, "tabLink"); 
										}
										else
										{
											echo $dados_consulta[$i][$j]["valor"]."&nbsp"; 
										}
									}	

									if ($dados_consulta[$i][$j]["nome_comportamento"] == "totalHora"){
										$valor = explode(":",$dados_consulta[$i][$j]["valor"]);
										$total = 0;
										if (is_array($valor) && $valor[0] && $valor[1]){
											$total = ($valor[0]*60)+$valor[1];
										}

										if ($dados_consulta[0][$j]["total"]){
											$dados_consulta[0][$j]["total"] += $total; 
										}
										else
										{
											$dados_consulta[0][$j]["total"] = $total; 
										}	
										$tem_total = 1;
									}
								?>
					   			</td>
								<? } ?>
					   		</tr>
							<? } 
							if ($tem_total){
							?>
							<tr>
 							<? for ($i = 0; $i < count($dados_consulta[0]); $i++){ 
									if ($dados_consulta[0][$i]["nome_comportamento"] != "totalHora"){
										if($i==0)
										{
										?>
											<th class=<? echo $dados_consulta[0][$i]["classe"]; ?> colspan=2 >
												Total:
											</th>
										<?
										}
										elseif ($i>1)
										{
										?>
											<td class=<? echo $dados_consulta[0][$i]["classe"]; ?> align="center" >
												-
											</td>
										<?
										}		
									}
									else
									{	
									?>
										<td class=<? echo $dados_consulta[0][$i]["classe"]; ?> >
										<?
											$hora = floor($dados_consulta[0][$i]["total"]/60); 
											$minutos = ($dados_consulta[0][$i]["total"] - ($hora*60));	
										
											if ($hora < 10){
												$hora = '0'.$hora; 
											}

											if ($minutos < 10){
												$minutos = '0'.$minutos; 
											}
											echo $hora.":".$minutos;
										?>
										</td>
									<? 
									} 
								}
							?>
 							</tr>	

						<?	}
						} 
						?>
						<tr>
 					    	<th class="<? echo $estilo_tabela; ?>" align="left" colspan=<? echo $total_campos_tabela; ?>>
							<? 
								$acoes_tela = $negocio->getAcoesTela(); 
							    for ($i = 0; $i < count($acoes_tela); $i++){
									if (($acoes_tela[$i]["nome_tipo"] <> "Busca" ) && ($acoes_tela[$i]["nome_tipo"] <> "Campos" )) {			
											$visual->exibeInput("acao_bottom_".$acoes_tela[$i]["id_tela_acao"],"button",$acoes_tela[$i]["nome"] ,'20','0',""," onClick=\"".$acoes_tela[$i]["link"]."\"","");	
									}
									?>
									&nbsp;
									<?
								}
							?>
 					    	</th>
 					    </tr>	
				   	   </table>
				   </td>
				</tr>  
			</table>	
		   <br>
		   <img src="./images/linhaHorizontal.gif"  width="100%" height="2">
		   <br>
		</div>
	</td>
  </tr>						
</table>
</form>
<script>
corAnterior = 'white'; 
<? 
if ($dados_consulta) { 
?>
	corAnterior = document.getElementById('tr_<? echo $dados_consulta[0][0]["valor"]; ?>').bgColor;
	corAnterior = 'white'; 
<? 
}

$mensagem = $negocio->getMensagem();
if ($mensagem) { 
	echo "alert('".$mensagem."')";
}
?>
</script>
</body>
</html>