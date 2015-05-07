<?PHP
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
$total_campos_tabela = 0;
$estilo_tabela = "result_cliente"; 
//Conta campos de tabela de listagem
for ($i = 0; $i < count($campos_tela); $i++){
	if ($campos_tela[$i]["grupo"] == "tabela") {
		$total_campos_tabela++;
		$estilo_tabela = $campos_tela[$i]["classe"];
	}
}

$tem_multi = 0;
//Verifica se existe filtro multi
for ($j = 0; $j < count($campos_tela); $j++){
	if (strpos("_".$campos_tela[$j]["grupo"], "multi")){
		$tem_multi = 1;
	}
}

$dados_consulta = $negocio->getDadosTela();
$total_dados = count($dados_consulta);

$tem_grafico = 0; //Inicializa listagem contém gráficos
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
  <?PHP 
	for ($i = 0; $i < count($dados_consulta); $i++){ 
		for ($j = 0; $j < count($dados_consulta[$i]); $j++)
		{ 
			if (($dados_consulta[$i][$j]["nome_comportamento"] == "checkbox") && ($dados_consulta[$i][$j]["valor"]))
			{
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
	<?PHP
	//Incluir validação de campos aqui
	for ($j = 0; $j < count($campos_tela); $j++){
		if ($campos_tela[$j]["grupo"] == "filtro"){
			if ($campos_tela[$j]["nome_comportamento"] == "mes") {
				?>
				if(document.getElementById(<?PHP echo "'".$campos_tela[$j]["nome"]."'" ?>).value < 1) {
					alert(<?PHP echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>);
					return 0;
				}
				<?PHP
			}
			elseif ($campos_tela[$j]["nome_comportamento"] == "ano")
			{
				?>
				if(document.getElementById(<? echo "'".$campos_tela[$j]["nome"]."'" ?>).value < 1) {
					alert(<?PHP echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>);				
					return 0;
				}
				<?PHP
			}
			elseif ($campos_tela[$j]["nome_comportamento"] == "calendario")
			{
				?>
				if(document.getElementById(<?PHP echo "'".$campos_tela[$j]["nome"]."'" ?>).value == "") {
					alert(<?PHP echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>); 
					return 0;
				}
				<?PHP
			}
			elseif ($campos_tela[$j]["nome_comportamento"] == "calendarioHora")
			{
				?>
				if(document.getElementById(<?PHP echo "'".$campos_tela[$j]["nome"]."_dia'" ?>).value == "") {
					alert(<?PHP echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>); 
					return 0;
				}
				<?PHP
			}
			elseif ($campos_tela[$j]["nome_comportamento"] == "dominio")
			{
				?>
				if(!(document.getElementById(<?PHP echo "'".$campos_tela[$j]["nome"]."'" ?>).value > 0)) {
					alert(<?PHP echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>); 
					return 0;
				}
				<?PHP
			}
			else
			{
				if ($campos_tela[$j]["elemento"])
				{
					?>
					if(document.getElementById(<?PHP echo "'".$campos_tela[$j]["nome"]."'" ?>).value.length < <?PHP echo $campos_tela[$j]["elemento"]; ?>) {
						alert(<?PHP echo "'Favor preencher o ".$campos_tela[$j]["rotulo"]."'"; ?>); 
						return 0;
					}
					<?PHP
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
	document.getElementById("formulario").target = '';
	if (fazer) {
		//Incluir validação de campos aqui
		document.getElementById("formulario").action = acao;
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

var origem = '<?PHP echo (isset($_GET["origem"])?$_GET["origem"]:''); ?>';
function volta_origem()
{
	window.location = './index.php?local='+origem;
}

function mostra_esconde_grafico(grafico){
	<?PHP
	for ($i = 0; $i < count($campos_tela); $i++){
		if ($campos_tela[$i]["nome_comportamento"] == "grafico"){
	?>
			try {	
				setDisplay("td_grafico_"+<?PHP echo $i;  ?>,false);			
			} catch(e){
			}
	<?PHP
		}
	}
	?>
	setDisplay("td_grafico_"+grafico,true);
}

var mostra = false;
function mostra_esconde_busca(){
	<?PHP
	for ($i = 0; $i < 20; $i++){
	?>
		try {	
			setDisplay("tr_multi_<?PHP echo $i;  ?>",mostra);			
		} catch(e){
		}
	<?PHP
	}
	?>
	setDisplay("tr_busca",mostra);
	mostra = !(mostra);
}

</script>
<table width="100%" >
  <tr>
    <td align = "left" width="1%"  height="44px">
      <img src="./images/logo.png"  width="60px" height="44px" >
    </td>
    <td width="99%" height="70px" > 	
	  <div align='center' class='cerca_bubble' style='width:100%; height:70px' style="margin-bottom:5">
		<table width=100% align=center cellpadding=2 cellspacing=2 bgcolor=#00aeef >
		  <tr>
            <td>
				
			   <div style="font-size:20px;color:#FFFFFF;text-align:center;" >	
			   		<b><?PHP echo $negocio->getDescricaoTela(); ?></b>
			   </div>
		   </td>
   	     </tr>
		 <tr>
			<td align='center' >
					&nbsp; 
			   <?PHP 
			      $itens_menu = $negocio->getItensMenu(); 
			      for ($i = 0; $i < count($itens_menu); $i++) { 
				      $visual->exibeLink($itens_menu[$i]["nome"], $itens_menu[$i]["link"], "", "mainlevel-nav");
				   ?>
				   &nbsp;
			  <?PHP
				  }
              ?>
			</td>
         </tr>
       </table>
	 </div> 
	 <script>
		Rounded('div.cerca_bubble','#FFFFFF', '#00aeef');
	 </script>
	</td>
   </tr>						
</table>		
<table  width="100%" >
  <form id="formulario" name="formulario" method="post" >
  <input type="hidden" value="<?PHP echo $ordem; ?>" name="ordem" id="ordem">
  <input type="hidden" value="<?PHP echo $chave; ?>" name="chave" id="chave">
  <tr>
    <td align = "left">
		<div id="listagem"> 
			<table class="tab" width="100%" border="0" cellspacing="0">  
				<tr>
					<td>
					   <!-- <tbody id="listagem"> -->
					   <table class="tab" width="100%" border="0" cellspacing="0">
						<tr id="tr_busca" >
  						   <th class="<?PHP echo $estilo_tabela; ?>" align="left" colspan=<?PHP echo $total_campos_tabela; ?> >
							<?PHP 
								$acoes_tela = $negocio->getAcoesTela(); 
								
								//Desenha todos os campos
								for ($j = 0; $j < count($campos_tela); $j++){
									if ( ($campos_tela[$j]["grupo"] == "filtro") && (strpos("_".$campos_tela[$j]["tipos"],",".$_SESSION["navegador_tipo_usuario"].",") > 0)){
										$visual->desenha_campos($campos_tela[$j]);
									}	
								}
								
								?>
								<br>
								<?PHP

								//Desenha todas as ações
							    for ($i = 0; $i < count($acoes_tela); $i++)
							    {
							    	//Se possui multi filtros somente põe em topo ações do tipo pesquisar
							    	if (!$tem_multi || ($acoes_tela[$i]["nome_tipo"] == "Busca") )
							    	{
										$visual->exibeInput("acao_top_".$acoes_tela[$i]["id_tela_acao"],"button",$acoes_tela[$i]["nome"] ,'20','0',""," onClick=\"".$acoes_tela[$i]["link"]."\"","");
										?>
										&nbsp;
										<?PHP
							    	}	
								}
							?>
							</th>
 					    </tr>	
						<?PHP
						for ($i = 1; $i <= 20; $i++){
							$achou = 0;
							for ($j = 0; $j < count($campos_tela); $j++){
								if ($campos_tela[$j]["grupo"] == "multi".$i){
									if (!$achou){
										?>
											<tr id="tr_multi_<?PHP echo $i;?>" >
												<th class="<?PHP echo $estilo_tabela; ?>" align="left" colspan=<?PHP echo $total_campos_tabela; ?> >
										<?PHP
										$achou = 1;
									}
									$visual->desenha_campos($campos_tela[$j]);
								}
							}
							if ($achou){
										?>
												</th>
											</tr>
										<?PHP
							}
							$achou = 0;	
						}	
						?>
		   				<tr>
						<?PHP
						for ($i = 0; $i < count($campos_tela); $i++){
							if ($campos_tela[$i]["grupo"] == "tabela"){
								if ($campos_tela[$i]["nome_comportamento"] == "checkbox") {
								?>
									<th class="<?PHP echo $campos_tela[$i]["classe"]; ?>"  width="<?PHP echo $campos_tela[$i]["tamanho"]; ?>">
									<img src='./images/busca.gif' title="Exibe/esconde filtros de busca" onclick='mostra_esconde_busca()' >
									</th>
								<?PHP
								}
								else
								{
								?>
									<th class="<?PHP echo $campos_tela[$i]["classe"]; ?>"  width="<?PHP echo $campos_tela[$i]["tamanho"]; ?>" <?PHP echo ($campos_tela[$i]["nome_comportamento"] == "grafico")?" onClick = mostra_esconde_grafico(".$i.") ":""; ?> > 
								<?PHP
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
									  <A HREF="javascript:confirma2('./index.php?local=<?PHP echo $_GET['local'];?>&chave=<?PHP echo $chave; ?>&ordem=<?PHP echo $campos_tela[$i]["elemento"]; ?>','')"  class='topLinkButton' >
								<?PHP
									}
									elseif ($campos_tela[$i]["nome_comportamento"] == "grafico")
									{
								?>
									  <A HREF="javascript:mostra_esconde_grafico( <?PHP echo $i; ?> )"  class='topLinkButton' >
								<?PHP

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
									<?PHP
									}
									?>	
								   </th>
								<?PHP
									if ($campos_tela[$i]["nome_comportamento"] == "grafico"){
										$tem_grafico = 1;
									}	
								}
							}
						}
						?>
						</tr>
						<?PHP if ($dados_consulta) { 
								$campo_valor = array();
							?>
							<?PHP for ($i = 0; $i < count($dados_consulta); $i++){ ?>
							<tr id="tr_<?PHP echo $dados_consulta[$i][0]["valor"]; ?>" <?PHP echo ($_POST[$dados_consulta[$i][0]["nome"]."_".$dados_consulta[$i][0]["valor"]]?"bgColor='#FF6F6F'":""); ?> >
								<?PHP for ($j = 0; $j < count($dados_consulta[$i]); $j++){ ?>
	  							<td class=<?PHP echo $dados_consulta[$i][$j]["classe"]; ?> >
								<?PHP
	
									$visual->desenha_campos_listagem($dados_consulta[$i][$j], $dados_consulta[$i][0]);
										
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
								?>
					   			</td>
								<?PHP } ?>
					   		</tr>
							<?PHP } 
							if ($tem_total){
							?>
							<tr>
 							<?PHP for ($i = 0; $i < count($dados_consulta[0]); $i++){ 
									if (($dados_consulta[0][$i]["nome_comportamento"] != "totalHora") &&     ($dados_consulta[0][$i]["nome_comportamento"] != "total")){
										if($i==0)
										{
										?>
											<th class=<?PHP echo $dados_consulta[0][$i]["classe"]; ?> colspan=2 >
												Total:
											</th>
										<?PHP
										}
										elseif ($i>1)
										{
										?>
											<td class=<?PHP echo $dados_consulta[0][$i]["classe"]; ?> align="center" >
												-
											</td>
										<?PHP
										}		
									}
									elseif ($dados_consulta[0][$i]["nome_comportamento"] == "totalHora")
									{
									?>
										<td class=<?PHP echo $dados_consulta[0][$i]["classe"]; ?> >
										<?PHP
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
									<?PHP 
									} 
									elseif ($dados_consulta[0][$i]["nome_comportamento"] == "total")
									{
									?>
										<td class=<?PHP echo $dados_consulta[0][$i]["classe"]; ?> >
										<?PHP
											echo $dados_consulta[0][$i]["total"];
										?>
										</td>
									<?PHP 
									} 
								}
							?>
 							</tr>	

						<?PHP	}
						} 
						if ($tem_grafico){
							for ($j = 0; $j < count($campos_tela); $j++){
								if (($campos_tela[$j]["nome_comportamento"]  == "grafico" )){									
								?>
							<tr>	
								<td class=<?PHP echo $campos_tela[$j]["classe"]; ?> align="center" colspan=<?PHP echo $total_campos_tabela; ?> id="<?PHP echo "td_grafico_".$j; ?>" <?PHP echo ($esconde)?"  style='display:none;'":""; ?>  >
									<img src="?local=<?PHP echo $campos_tela[$j]["link"].$get; ?>"  title="<?PHP echo $campos_tela[$j]["rotulo"]; ?>" width="<?PHP echo $campos_tela[$j]["maxlength"]; ?>px" height="<?PHP echo $campos_tela[$j]["elemento"]; ?>px" >
								</td>
							</tr>		
								<?PHP
									$esconde = 1;
								}	
							}
						}
						?>
						<tr>
 					    	<th class="<?PHP echo $estilo_tabela; ?>" align="left" colspan=<?PHP echo $total_campos_tabela; ?>>
							<?PHP 
								$acoes_tela = $negocio->getAcoesTela(); 
							    for ($i = 0; $i < count($acoes_tela); $i++){
									if (($acoes_tela[$i]["nome_tipo"] <> "Busca" ) && ($acoes_tela[$i]["nome_tipo"] <> "Campos" )) {			
											$visual->exibeInput("acao_bottom_".$acoes_tela[$i]["id_tela_acao"],"button",$acoes_tela[$i]["nome"] ,'20','0',""," onClick=\"".$acoes_tela[$i]["link"]."\"","");	
									}
									?>
									&nbsp;
									<?PHP
								}
							?>
 					    	</th>
 					    </tr>	
				   	   </table>
					   <!--</tbody> -->
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
<?PHP 
if ($dados_consulta) { 
?>
	if (document.getElementById('tr_<?PHP echo $dados_consulta[0][0]["valor"]; ?>'))
	{	
		corAnterior = document.getElementById('tr_<?PHP echo $dados_consulta[0][0]["valor"]; ?>').bgColor;
		corAnterior = 'white';
	}	 
<?PHP 
}

$mensagem = $negocio->getMensagem();
if ($mensagem) { 
	echo "alert('".$mensagem."')";
}
?>
</script>