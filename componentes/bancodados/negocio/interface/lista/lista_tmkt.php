<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Template para Interface de Listagem simples
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 18/01/2008 Atualizado: 18/01/2008
*/

$get = "";
foreach($_POST as $post_chave => $post_valor){
	$get .= "&".$post_chave."=".$post_valor;
}
foreach($_GET as $post_chave => $post_valor){
	if ($post_chave != "local"){
		$get .= "&".$post_chave."=".$post_valor;
	}
}

$campos_tela =  $negocio->getCamposTela();
$campos_totalsuper = array();
$num_campos_totalsuper = 0;
$total_campos_totalsuper = 0;
$total_campos_tabela = 0; 
$total_campos_sub_tabela = 0; 
$total_campos_super_tabela = 0; 
$tem_verifica_sub = 0;
$sumarizado = ($_GET["sumarizado"]?$_GET["sumarizado"]:$_POST["sumarizado"]);

//Conta campos de tabela de listagem
for ($i = 0; $i < count($campos_tela); $i++){
	if ($campos_tela[$i]["grupo"] == "tabela") {
		$total_campos_tabela++;
		$estilo_tabela = $campos_tela[$i]["classe"];
	}
	if ($campos_tela[$i]["grupo"] == "tabelasub") {
		$total_campos_sub_tabela++;
		$estilo_sub_tabela = $campos_tela[$i]["classe"];
	}
    if ($campos_tela[$i]["grupo"] == "tabelasuper") {
		$total_campos_super_tabela++;
		$estilo_super_tabela = $campos_tela[$i]["classe"];
	}
	if ($campos_tela[$i]["grupo"] == "verificasub") {
		$tem_verifica_sub = 1;
	}
	
	if ($campos_tela[$i]["grupo"] == "totalsuper") {
		$estilo_total_super_tabela = $campos_tela[$i]["classe"];
		$campos_totalsuper[$num_campos_totalsuper] = $campos_tela[$i];
		$campos_totalsuper[$num_campos_totalsuper]["total"] = 0; 
		$campos_totalsuper[$num_campos_totalsuper]["total_geral"] = 0; 
		$num_campos_totalsuper++;
		$total_campos_totalsuper = $num_campos_totalsuper;
	}
}

$dados_consulta = $negocio->getDadosTela();
$total_dados = count($dados_consulta);
?>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<script src="./js/cria_elemento.js"></script>
<script src="./js/calendario.js"></script>
<script src="./js/functions.js"></script>
<script src="./js/valida.js"></script>
<style type=text/css>
/* Arredondamento */.rtop,.rbottom{display:block} .rtop *,.rbottom *{display:block;height: 1px;overflow: hidden}.r1{margin: 0 5px}.r2{margin: 0 3px}.r3{margin: 0 2px}.r4{margin: 0 1px;height: 2px}.rs1{margin: 0 2px}.rs2{margin: 0 1px}
</style>
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
			elseif ($campos_tela[$j]["nome_comportamento"] == "dominio")
			{
				?>
				if(document.getElementById(<? echo "'".$campos_tela[$j]["nome"]."'" ?>).value > 0) {
					alert(<? echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>); 
					return 0;
				}
				<?
			}
			else
			{
				if ($campos_tela[$j]["elemento"])
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
		document.getElementById("formulario").action = acao+'&origem=<? echo $_GET["origem"] ?>';
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
	document.getElementById("formulario").target = '';
	if (fazer) {
		//Incluir validação de campos aqui
		document.getElementById("formulario").action = acao+'&origem=<? echo $_GET["origem"] ?>';
		document.getElementById("formulario").submit();	
	}
}

function confirma3(acao,nome)
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
		window.location = acao;
	}

}

var origem = '<? echo $_GET["origem"]; ?>';
function volta_origem()
{
	window.location = './index.php?local='+origem;
}

function mostra_esconde_grafico(grafico){
	<?
	for ($i = 0; $i < count($campos_tela); $i++){
		if ($campos_tela[$i]["nome_comportamento"] == "grafico"){
	?>
			try {	
				setDisplay("td_grafico_"+<? echo $i;  ?>,false);			
			} catch(e){
			}
	<?
		}
	}
	?>
	setDisplay("td_grafico_"+grafico,true);
}

var mostra = false;
function mostra_esconde_busca(){
	<?
	for ($i = 0; $i < 20; $i++){
	?>
		try {	
			setDisplay("tr_multi_<? echo $i;  ?>",mostra);			
		} catch(e){
		}
	<?
	}
	?>
	setDisplay("tr_busca",mostra);
	mostra = !(mostra);
}

</script>
<table width="100%">
  <tr>
    <td align = "left" width="1%"  height="44px">
      <img src="./images/logo.gif"  width="93px" height="44px" >
    </td>
    <td width="99%" height="70px" > 	
	  <div align='center' class='cerca_bubble' style='width:100%; height:70px' style="margin-bottom:5">
		<table width=100% align=center cellpadding=2 cellspacing=2 bgcolor=#48C505 >
		  <tr>
            <td>
				
			   <div style="font-size:20px;color:#FFFFFF;text-align:center;" >	
			   		<b><? echo $negocio->getDescricaoTela(); ?></b>
			   </div>
		   </td>
   	     </tr>
		 <tr>
			<td align='center' >
					&nbsp; 
			   <? 
			      $itens_menu = $negocio->getItensMenu(); 
			      for ($i = 0; $i < count($itens_menu); $i++) { 
				      $visual->exibeLink($itens_menu[$i]["nome"], $itens_menu[$i]["link"], "", "mainlevel-nav");
				   ?>
				   &nbsp;
			  <?
				  }
              ?>
			</td>
         </tr>
       </table>
	 </div> 
	 <script>
	 	Rounded('div.cerca_bubble','#FFFFFF', '#48C505');
	 </script>
	</td>
   </tr>						
</table>		
<table  width="100%">
  <form id="formulario" name="formulario" method="post" >
  <input type="hidden" value="<? echo $ordem; ?>" name="ordem" id="ordem">
  <input type="hidden" value="<? echo $chave; ?>" name="chave" id="chave">
  <tr>
    <td align = "left">
		<div id="listagem"> 
			<table class="tab" width="100%" border="0" cellspacing="0"  cellpadding="0">  
				<tr>
					<td>
					   <tbody>
					   <table class="tab" width="100%" border="0" cellspacing="0"  cellpadding="0">
						<tr id="tr_busca" >
  						   <th class="<? echo $estilo_tabela; ?>" align="left" colspan=<? echo $total_campos_tabela; ?> >
							<? 
								$acoes_tela = $negocio->getAcoesTela(); 
								
								//Desenha todos os campos
								for ($j = 0; $j < count($campos_tela); $j++){
									if ( ($campos_tela[$j]["grupo"] == "filtro") && (strpos("_".$campos_tela[$j]["tipos"],",".$_SESSION["navegador_tipo_usuario"].",") > 0)){
										$visual->desenha_campos($campos_tela[$j]);
									}	
								}
								
								?>
								<br>
								<?

								//Desenha todas as ações
							    for ($i = 0; $i < count($acoes_tela); $i++){
									$visual->exibeInput("acao_top_".$acoes_tela[$i]["id_tela_acao"],"button",$acoes_tela[$i]["nome"] ,'20','0',""," onClick=\"".$acoes_tela[$i]["link"]."\"","");
									?>
									&nbsp;
									<?
									
								}
							?>
							</th>
 					    </tr>	
						<?
						for ($i = 1; $i <= 20; $i++){
							$achou = 0;
							for ($j = 0; $j < count($campos_tela); $j++){
								if ($campos_tela[$j]["grupo"] == "multi".$i){
									if (!$achou){
										?>
											<tr id="tr_multi_<? echo $i;?>" >
												<th class="<? echo $estilo_tabela; ?>" align="left" colspan=<? echo $total_campos_tabela; ?> >
										<?
										$achou = 1;
									}
									$visual->desenha_campos($campos_tela[$j]);
								}
							}
							if ($achou){
										?>
												</th>
											</tr>
										<?
							}
							$achou = 0;	
						}	
						?>
		   				<tr>
						<?
						$campos_verificacao_sub = array();
						$valor_campo_super = "";
						$campo_super = 0;
						$campo_verifica_sub = 0;
						$em_grupo_sub = 0;
						$campos_sub = array();
						for ($i = 0; $i < count($campos_tela); $i++){
							if ($campos_tela[$i]["grupo"] == "tabela"){
								if (($campos_tela[$i]["nome_comportamento"] == "checkbox") || ($campos_tela[$i]["nome_comportamento"] == "maismenos") ) {
								?>
									<th class="<? echo $campos_tela[$i]["classe"]; ?>"  width="<? echo $campos_tela[$i]["tamanho"]; ?>">
										<img src='./images/busca.gif' title="Exibe/esconde filtros de busca" onclick='mostra_esconde_busca()' >
									</th>
								<?
								}
								else
								{
								?>
									<th class="<? echo $campos_tela[$i]["classe"]; ?>"  width="<? echo $campos_tela[$i]["tamanho"]; ?>" <? echo ($campos_tela[$i]["nome_comportamento"] == "grafico")?" onClick = mostra_esconde_grafico(".$i.") ":""; ?> > 
								<?
									if (($campos_tela[$i]["nome_comportamento"] == "ordem") ||     
									    ($campos_tela[$i]["nome_comportamento"] == "calendarioHora") ||
										($campos_tela[$i]["nome_comportamento"] == "total") ||
									    (($campos_tela[$i]["nome_comportamento"] == "edit") && $campos_tela[$i]["elemento"] != "no") ||
									    (($campos_tela[$i]["nome_comportamento"] == "editXajax") && $campos_tela[$i]["elemento"] != "no") ||
									    (($campos_tela[$i]["nome_comportamento"] == "select_dominio") && $campos_tela[$i]["elemento"] != "no") ||
									    (($campos_tela[$i]["nome_comportamento"] == "select_tabela") && $campos_tela[$i]["elemento"] != "no") ||
									    (($campos_tela[$i]["nome_comportamento"] == "calendarioXajax") && $campos_tela[$i]["elemento"] != "no") ) 
									{
								?>
									  <A HREF="javascript:confirma2('./index.php?local=<? echo $_GET['local'];?>&chave=<? echo $chave; ?>&ordem=<? echo $campos_tela[$i]["elemento"]; ?>','')"  class='topLinkButton' >
								<?
									}
									elseif ($campos_tela[$i]["nome_comportamento"] == "grafico")
									{
								?>
									  <A HREF="javascript:mostra_esconde_grafico( <? echo $i; ?> )"  class='topLinkButton' >
								<?

									}
								    echo $campos_tela[$i]["rotulo"]; 

								    if (($campos_tela[$i]["nome_comportamento"] == "ordem") ||     
									    ($campos_tela[$i]["nome_comportamento"] == "calendarioHora") ||
										($campos_tela[$i]["nome_comportamento"] == "total") ||
										($campos_tela[$i]["nome_comportamento"] == "grafico") ||
									    (($campos_tela[$i]["nome_comportamento"] == "edit") && $campos_tela[$i]["elemento"] != "no") ||
									    (($campos_tela[$i]["nome_comportamento"] == "editXajax") && $campos_tela[$i]["elemento"] != "no") ||
									    (($campos_tela[$i]["nome_comportamento"] == "select_dominio") && $campos_tela[$i]["elemento"] != "no") ||
									    (($campos_tela[$i]["nome_comportamento"] == "select_tabela") && $campos_tela[$i]["elemento"] != "no") ||
									    (($campos_tela[$i]["nome_comportamento"] == "calendarioXajax") && $campos_tela[$i]["elemento"] != "no") ) 
									{
									?>
								       </a>   
									<?
									}
									?>	
								   </th>
								<?
									if ($campos_tela[$i]["nome_comportamento"] == "grafico"){
										$tem_grafico = 1;
									}	
								}
							}

							//Para preenchimento de cabeçalho de tabela sub
							if ($campos_tela[$i]["grupo"] == "tabelasub"){
								$campos_sub[] = $i;
							}
						}
						?>
						</tr>
						<form id="lista" name="lista" method="post" >
						<? if ($dados_consulta) { 
								$campo_valor = array();
								for ($i = 0; $i < count($dados_consulta); $i++){ 
								//Desenha campo super
								if (($total_campos_super_tabela > 0) && ($valor_campo_super != $dados_consulta[$i][$campo_super]["valor"])) {
									if ($i && $total_campos_totalsuper){
									?>
										<tr id="tr_super_total_<? echo $dados_consulta[$i-1][0]["valor"]; ?>">
											<th class=<? echo $estilo_super_tabela; ?> colspan=<? echo $total_campos_tabela; ?> align="left" >
												<table class="tab" width="100%" border="0" cellspacing="0"  cellpadding="0" >
												<tr>
													<?
													$num_coluna = 0;
													$legend = array();
													$data = array();
													//Imprime Sub Totais
													for ($num_campos_totalsuper = 0; $num_campos_totalsuper < $total_campos_totalsuper; $num_campos_totalsuper++){
														if ($campos_totalsuper[$num_campos_totalsuper]["total"]){
															?>
															<td class=<? echo $estilo_super_tabela; ?> width="20%" align="left" > 
															<? 
																$legend[] = $campos_totalsuper[$num_campos_totalsuper]["rotulo"];
																$data[] = $campos_totalsuper[$num_campos_totalsuper]["total"];
																echo $campos_totalsuper[$num_campos_totalsuper]["rotulo"].":&nbsp;"; 
																echo $campos_totalsuper[$num_campos_totalsuper]["total"];
															?> 
															</td> 
															<?	
															$num_coluna++;
															if ($num_coluna >= 5){
																echo "</tr><tr>";
																$num_coluna = 0;
															}
														}	
													}
													if ($num_coluna < 5 && $num_coluna > 0) {
													?>
														<td class=<? echo $estilo_super_tabela; ?> colspan= <? echo (5 - $num_coluna) ?>  > &nbsp; </td>
													<?
													}	
													?>
												</tr>
												</table>
											</th>	
										</tr>
										<tr id="tr_super_grafico_<? echo $dados_consulta[$i-1][0]["valor"]; ?>">
											<th class=<? echo $estilo_super_tabela; ?> colspan=<? echo $total_campos_tabela; ?> align="left" >
											<? 
												$name = $negocio->getTituloTela()." - ".$valor_campo_super;
												$name_x = "";
												$name_y = "";
												$visual->desenhaGraficoBarras($name,$legend,$data,$nome_x,$nome_y); 
											?>
											</th>
										</tr>
									<?
									}
									?>
									<tr id="tr_super_<? echo $dados_consulta[$i][0]["valor"]; ?>">
										<th class=<? echo $estilo_super_tabela; ?> colspan=<? echo $total_campos_tabela; ?> align="left" >
									<?
										for ($super = 0; ($super < count($dados_consulta[$i]) && !$campo_super) ; $super++){ 
											if ($dados_consulta[$i][$super]["grupo"] == "tabelasuper") {
												$campo_super = $super;
											}
										}
										if (!$sumarizado)
										{
											$visual->exibeMaisMenos($dados_consulta[$i][$campo_super]["nome"]."_".$dados_consulta[$i][0]["valor"],"lista_filhos_super_".$dados_consulta[$i][0]["valor"],$dados_consulta[$i][$campo_super]["rotulo"],1,1);
										}	
										$incide_super = $i;
										echo $dados_consulta[$i][$campo_super]["rotulo"].": ".$dados_consulta[$i][$campo_super]["valor"]."  ";
										$valor_campo_super = $dados_consulta[$i][$campo_super]["valor"];
										//Para garantir o verifica sub abaixo do verifica super
										if ($tem_verifica_sub){
											$valor_verifica_sub_old = "";
										}
									?>
										<script> 
											var lista_filhos_super_<? echo $dados_consulta[$i][0]["valor"]; ?> = [];
											var num_filhos_super_<? echo $dados_consulta[$i][0]["valor"]; ?> = 0;
										</script>
										</th>
									</tr>
									<?
									//Zera Totais de campo super
									for ($num_campos_totalsuper = 0; $num_campos_totalsuper < $total_campos_totalsuper; $num_campos_totalsuper++){
										$campos_totalsuper[$num_campos_totalsuper]["total"] = 0;
									}
								}
								if ($tem_verifica_sub){
									//Procura por campo de verificação de sub tabela
									for ($verifica_sub = 0; ($verifica_sub < count($dados_consulta[$i]) && !$campo_verifica_sub) ; $verifica_sub++){ 
										if ($dados_consulta[$i][$verifica_sub]["grupo"] == "verificasub") {
											$campo_verifica_sub = $verifica_sub;
										}
									}
								}
							
							if (!$sumarizado)
							{
								?>
								<tr id="tr_<? echo $dados_consulta[$i][0]["valor"].($em_grupo_sub?"_sub":""); ?>" <? echo ($_POST[$dados_consulta[$i][0]["nome"]."_".$dados_consulta[$i][0]["valor"]]?"bgColor='#FF6F6F'":""); ?> >
								<?

								if (($total_campos_super_tabela > 0) && !$em_grupo_sub){										
								?>
									<script> 
										lista_filhos_super_<? echo $dados_consulta[$incide_super][0]["valor"]; ?>[num_filhos_super_<? echo $dados_consulta[$incide_super][0]["valor"]; ?>] = 
											<? echo "'tr_".$dados_consulta[$i][0]["valor"]."'"; ?>; 
										num_filhos_super_<? echo $dados_consulta[$incide_super][0]["valor"]; ?>++; 
									</script>
								<?
								}
							}
							
								if ($tem_verifica_sub){
									$valor_verifica_sub = $dados_consulta[$i][$campo_verifica_sub]["valor"];
								}
								for ($j = 0; $j < count($dados_consulta[$i]); $j++){ 
									if ( (($dados_consulta[$i][$j]["grupo"] == "tabela") && 
										  (($valor_verifica_sub != $valor_verifica_sub_old) || 
										  !$tem_verifica_sub)) ||
										 (($dados_consulta[$i][$j]["grupo"] == "tabelasub") &&
										  ($valor_verifica_sub == $valor_verifica_sub_old) ) )  {

									if (!$sumarizado)
									{	  	
									?>										
									<td class=<? echo $dados_consulta[$i][$j]["classe"]; ?> >
									<?
										if ($dados_consulta[$i][$j]["nome_comportamento"] == "maismenos") {
											if (!$sumarizado)
											{
												$visual->exibeMaisMenos($dados_consulta[$i][$j]["nome"]."_".$dados_consulta[$i][$j]["valor"],"div_".$dados_consulta[$i][0]["valor"]."_grupo_sub",$dados_consulta[$i][$j]["rotulo"],0);
											}	
										}	
										else
										{
											$visual->desenha_campos_listagem($dados_consulta[$i][$j], $dados_consulta[$i][0]);
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
										if ($dados_consulta[$i][$j]["nome_comportamento"] == "total"){
											if (!$dados_consulta[0][$j]["total"]){
												$dados_consulta[0][$j]["total"] = 0; 
											}
											$dados_consulta[0][$j]["total"]++; 
											$tem_total = 1;
										}
									if (!$sumarizado)
									{
										?>
										</td>
										<?
									} 
										//Acumula totais de campo super
										for ($num_campos_totalsuper = 0; $num_campos_totalsuper < $total_campos_totalsuper; $num_campos_totalsuper++){
											if (($campos_totalsuper[$num_campos_totalsuper]["link"]  == $dados_consulta[$i][$j]["nome"] ) &&
											    ($campos_totalsuper[$num_campos_totalsuper]["elemento"]  == $dados_consulta[$i][$j]["valor"] )){
												$campos_totalsuper[$num_campos_totalsuper]["total"] = $campos_totalsuper[$num_campos_totalsuper]["total"]+1;
												$campos_totalsuper[$num_campos_totalsuper]["total_geral"] = $campos_totalsuper[$num_campos_totalsuper]["total_geral"]+1;
											}	
										}
									}
								}
							if (!$sumarizado)
							{
							?>
					   		</tr>
							<?
							}
								if ($tem_verifica_sub){
									if ($valor_verifica_sub != $valor_verifica_sub_old){
										$valor_verifica_sub_old = $dados_consulta[$i][$campo_verifica_sub]["valor"];
										//Desenha cabeçalho
										if ($total_campos_super_tabela > 0){										
										?>
											<script> 
												lista_filhos_super_<? echo $dados_consulta[$incide_super][0]["valor"]; ?>[num_filhos_super_<? echo $dados_consulta[$incide_super][0]["valor"]; ?>] = 
												<? echo "'tr_".$dados_consulta[$i][0]["valor"]."_grupo_sub'"; ?>; 
												num_filhos_super_<? echo $dados_consulta[$incide_super][0]["valor"]; ?>++; 
											</script>
										<?
										}
										?>
										<tr id="tr_<? echo $dados_consulta[$i][0]["valor"]; ?>_grupo_sub">
											<td id="td_<? echo $dados_consulta[$i][0]["valor"]; ?>_grupo_sub" colspan=<? echo $total_campos_tabela; ?>  >
												<div id="div_<? echo $dados_consulta[$i][0]["valor"]; ?>_grupo_sub" style="display:none;">
													<table  class="tab" width="100%" border="0" cellspacing="0"  cellpadding="0" id="table_<? echo $dados_consulta[$i][0]["valor"]; ?>_grupo_sub">
		   												<tr>
														<?
															for ($k = 0; $k < count($campos_tela); $k++){
																if ($campos_tela[$k]["grupo"] == "tabelasub"){
																?>	
																	<th class="<? echo $campos_tela[$k]["classe"]; ?>"  width="<? echo $campos_tela[$k]["tamanho"]; ?>" > 
																	<?
																	    echo $campos_tela[$k]["rotulo"]; 
																	?>	
																   </th>
																<?
																}
															}
														?>
														</tr>
										
										<?
										$em_grupo_sub = 1;
										$i--;
									}
									else
									{
										if (($i+1) < count($dados_consulta)){
											$valor_verifica_sub_new = $dados_consulta[$i+1][$campo_verifica_sub]["valor"];
											if ($total_campos_super_tabela > 0){
												$valor_campo_super_new = $dados_consulta[$i+1][$campo_super]["valor"];
											}
										}
										else
										{
											$valor_verifica_sub_new = 0;
											$valor_campo_super_new = 0;
										}

										//Desenha Rodapé
										if (($valor_verifica_sub != $valor_verifica_sub_new) || 
											(($total_campos_super_tabela > 0) && ($valor_campo_super != $valor_campo_super_new ))){
											$em_grupo_sub = 0;
											?>
														</table>
													</div>
												</td>
											</tr>
											<?
										}
									}
								}
							}
							//Desenha ultima linha de sub.total 
							if ($i && $total_campos_totalsuper){
							?>
								<tr id="tr_super_total_<? echo $dados_consulta[$i-1][0]["valor"]; ?>">
									<th class=<? echo $estilo_super_tabela; ?> colspan=<? echo $total_campos_tabela; ?> align="left" >
										<table class="tab" width="100%" border="O" cellspacing="0"  cellpadding="0">
										<tr>
										<?
										$num_coluna = 0;
										$legend = array();
										$data = array();
										//Imprime Sub Totais
										for ($num_campos_totalsuper = 0; $num_campos_totalsuper < $total_campos_totalsuper; $num_campos_totalsuper++){
											if ($campos_totalsuper[$num_campos_totalsuper]["total"]){
												?>
													<td class=<? echo $estilo_super_tabela; ?> width="20%" align="left" > 
												<? 
													$legend[] = $campos_totalsuper[$num_campos_totalsuper]["rotulo"];			$data[] = $campos_totalsuper[$num_campos_totalsuper]["total"];
													echo $campos_totalsuper[$num_campos_totalsuper]["rotulo"].":&nbsp;"; 
													echo $campos_totalsuper[$num_campos_totalsuper]["total"]; 
												?> 
													</td> 
												<?
												$num_coluna++;
												if ($num_coluna >= 5){
														echo "</tr><tr>";
														$num_coluna = 0;
												}
											}	
										}
										if ($num_coluna < 5 && $num_coluna > 0) {
										?>
											<td class=<? echo $estilo_super_tabela; ?> colspan= <? echo (5 - $num_coluna) ?>  > &nbsp; </td>
										<?
										}
										?>
										</tr>
										</table>
									</th>	
								</tr>
								<tr id="tr_super_grafico_<? echo $dados_consulta[$i-1][0]["valor"]; ?>">
									<th class=<? echo $estilo_super_tabela; ?> colspan=<? echo $total_campos_tabela; ?> align="left" >
									<? 
										$name = "Controle de Qualidade - ".$valor_campo_super;
										$name_x = "";
										$name_y = "";
										$visual->desenhaGraficoBarras($name,$legend,$data,$nome_x,$nome_y); 
									?>
									</th>
								</tr>
							<?
							}
							if ($tem_total){
							?>
							<tr>
 							<? for ($i = 0; $i < count($dados_consulta[0]); $i++){ 
									if (($dados_consulta[0][$i]["nome_comportamento"] != "totalHora") &&
										($dados_consulta[0][$i]["nome_comportamento"] != "total") &&
										($dados_consulta[0][$i]["grupo"] == "tabela") ){
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
									elseif ($dados_consulta[0][$i]["nome_comportamento"] == "totalHora")
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
									elseif ($dados_consulta[0][$i]["nome_comportamento"] == "total")
									{
									?>
										<td class=<? echo $dados_consulta[0][$i]["classe"]; ?> >
										<?
											echo $dados_consulta[0][$i]["total"];
										?>
										</td>
									<? 
									} 
								}
							?>
 							</tr>	
						<?	
							}
							//Desenha ultima linha de sub.total 
							if ($i && $total_campos_totalsuper){
								//Imprime total geral
								?>
								<tr id="tr_super_total_geral">
									<th class=<? echo $dados_consulta[0][0]["classe"]; ?> colspan=<? echo $total_campos_tabela; ?> align="left" >
										<table class="tab" width="100%" border="O" cellspacing="0"  cellpadding="0">
										<tr>
										<?
										$num_coluna = 0;
										$legend = array();
										$data = array();
										//Imprime Sub Totais
										for ($num_campos_totalsuper = 0; $num_campos_totalsuper < $total_campos_totalsuper; $num_campos_totalsuper++){
											if ($campos_totalsuper[$num_campos_totalsuper]["total_geral"]){
												?>
													<td  class=<? echo $dados_consulta[0][0]["classe"]; ?> width="20%" align="left" > 
												<? 
													$legend[] = $campos_totalsuper[$num_campos_totalsuper]["rotulo"];
													$data[] = $campos_totalsuper[$num_campos_totalsuper]["total_geral"]; 
													echo $campos_totalsuper[$num_campos_totalsuper]["rotulo"].":&nbsp;"; 
													echo $campos_totalsuper[$num_campos_totalsuper]["total_geral"]; 
												?> 
													</td> 
												<?
												$num_coluna++;
												if ($num_coluna >= 5){
														echo "</tr><tr>";
														$num_coluna = 0;
												}
											}	
										}
										if ($num_coluna < 5 && $num_coluna > 0) {
										?>
											<td  class=<? echo $dados_consulta[0][0]["classe"]; ?> colspan= <? echo (5 - $num_coluna) ?>  > &nbsp; </td>
										<?
										}
										?>
										</tr>
										</table>
									</th>	
								</tr>
								<tr id="tr_super_grafico">
									<th class=<? echo $dados_consulta[0][0]["classe"]; ?> colspan=<? echo $total_campos_tabela; ?> align="left" >
									<? 
										$name = "Controle de Qualidade";
										$name_x = "";
										$name_y = "";
										
										$visual->desenhaGraficoBarras($name,$legend,$data,$nome_x,$nome_y); 
									?>
									</th>
								</tr>
							<?
							}
						} 
						if ($tem_grafico){
							for ($j = 0; $j < count($campos_tela); $j++){
								if (($campos_tela[$j]["nome_comportamento"]  == "grafico" )){									
								?>
							<tr>	
								<td class=<? echo $campos_tela[$j]["classe"]; ?> align="center" colspan=<? echo $total_campos_tabela; ?> id="<? echo "td_grafico_".$j; ?>" <? echo ($esconde)?"  style='display:none;'":""; ?>  >
									<img src="?local=<? echo $campos_tela[$j]["link"].$get; ?>"  title="<? echo $campos_tela[$j]["rotulo"]; ?>" width="<? echo $campos_tela[$j]["maxlength"]; ?>px" height="<? echo $campos_tela[$j]["elemento"]; ?>px" >
								</td>
							</tr>		
								<?
									$esconde = 1;
								}	
							}
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
					   </tbody>
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
	if (document.getElementById('tr_<? echo $dados_consulta[0][0]["valor"]; ?>'))
	{
		corAnterior = document.getElementById('tr_<? echo $dados_consulta[0][0]["valor"]; ?>').bgColor;
		corAnterior = 'white';
	}	 
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