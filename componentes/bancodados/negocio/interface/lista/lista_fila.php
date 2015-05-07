<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Template para Interface de Listagem simples
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 18/01/2008 Atualizado: 18/01/2008
*/

//Campos de tela (Configuração de coluna de Fila)
$campos_tela =  $negocio->getCamposTela();
$estilo_tabela = $campos_tela[0]["classe"];

$dados_fila = $negocio->getFila();
$fila = $dados_fila["fila"];
$total_filas = $dados_fila["total_filas"];
$total_linhas_fila = $dados_fila["total_linhas_fila"];

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
  					alert(<? echo "'Favor preencher o campo ".$campos_tela[$j]["rotulo"]."'"; ?>);
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
  					alert(<? echo "'Favor preencher o campo ".$campos_tela[$j]["rotulo"]."'"; ?>); 
  					return 0;
  				}
  				<?
  			}
  			elseif ($campos_tela[$j]["nome_comportamento"] == "dominio")
  			{
  				?>
  				if(document.getElementById(<? echo "'".$campos_tela[$j]["nome"]."'" ?>).value > 0) {
  					alert(<? echo "'Favor preencher o campo ".$campos_tela[$j]["rotulo"]."'"; ?>); 
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
  						alert(<? echo "'Favor preencher o campo ".$campos_tela[$j]["rotulo"]."'"; ?>); 
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

var origem = '<? echo $_GET["origem"]; ?>';
function volta_origem()
{
	window.location = './index.php?local='+origem;
}


function remove_consultor(consultor,fila)
{
	document.getElementById('fila').value = fila;
	document.getElementById('consultor').value = consultor;
	confirma2('./index.php?local=lista_vendedor_fila&delete=remover','Confirma remoção de consultor de Fila?');
}

function rodar_fila(consultor,fila)
{
	document.getElementById('fila').value = fila;
	document.getElementById('consultor').value = consultor;
	confirma2('./index.php?local=lista_vendedor_fila&update=rodar','Confirma rolagem de fila?');
}

function sobe_consultor(consultor,fila)
{
	document.getElementById('fila').value = fila;
	document.getElementById('consultor').value = consultor;
	confirma2('./index.php?local=lista_vendedor_fila&update=subir','Confirma subir ordem de consultor em Fila?');
}

function desce_consultor(consultor,fila)
{
	document.getElementById('fila').value = fila;
	document.getElementById('consultor').value = consultor;
	confirma2('./index.php?local=lista_vendedor_fila&update=descer','Confirma descer ordem de consultor em Fila?');
}

</script>
<table width="100%" >
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
	 	//Rounded('div.cerca_bubble','#FFFFFF', '#DFEFD7');
		Rounded('div.cerca_bubble','#FFFFFF', '#48C505');
	 </script>
	</td>
   </tr>						
</table>		
<table  width="100%" >
  <form id="formulario" name="formulario" method="post" >
  <input type="hidden" value="<? echo $ordem; ?>" name="ordem" id="ordem">
  <input type="hidden" value="<? echo $chave; ?>" name="chave" id="chave">
  <tr>
    <td align = "left">
		<div id="listagem"> 
			<table class="tab" width="100%" border="0" cellspacing="0">
				<tr id="tr_busca" >
  					   <th class="<? echo $estilo_tabela; ?>" align="left" colspan=<? echo $total_filas; ?> >
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
							    for ($i = 0; $i < count($acoes_tela); $i++)
							    {
							    	//Se possui multi filtros somente põe em topo ações do tipo pesquisar
							    	$visual->exibeInput("acao_top_".$acoes_tela[$i]["id_tela_acao"],"button",$acoes_tela[$i]["nome"] ,'20','0',""," onClick=\"".$acoes_tela[$i]["link"]."\"","");
									?>
									&nbsp;
									<?
								}
							?>
					</th>
 				</tr>  
				<tr>
					<td>
					   <table class="tab" width="100%" border="0" cellspacing="0">
		   				<tr>
						<?
						for ($i = 0; $i < $total_filas; $i++){
							?>
								<th class="<? echo $estilo_tabela; ?>"  width="<? echo $campos_tela[0]["tamanho"]; ?>" > 
							<?
								    echo "<nobr>".$fila[$i]["nome"]."</nobr>"; 
							?>	
  							    </th>
							<?	
						}
						?>
						</tr>
						<? 
							for ($i = 0; $i < $total_linhas_fila; $i++){ 
						?>
							<tr id="tr_linha_fila_<? echo $i; ?>" >
								
							<? 
								for ($j = 0; $j < $total_filas; $j++){ 
								?>
	  								<td class="<? echo $campos_tela[0]["classe"]; ?>" >
								<?
										if ($fila[$j]["lista"][$i]["consultor"])
										{
											$consultor = $fila[$j]["lista"][$i]["codigo"];
											$num_fila = $j+1;
											echo "<img src='./images/delete.png' title='Remover Consultor de Fila' style = 'cursor:hand;' onclick ='remove_consultor(".$consultor.",".$num_fila.")' >";
											if (!$i)
											{
												echo "<img src='./images/arrow_return.png' title='Rodar Fila' style = 'cursor:hand;' onclick ='rodar_fila(".$consultor.",".$num_fila.")' >";
											}	
											
											if ($i > 1)
											{
												echo "<img src='./images/arrow_up.png' title='Subir Consultor em Fila' style = 'cursor:hand;' onclick ='sobe_consultor(".$consultor.",".$num_fila.")' >";
											}
											
											if ( ($i) && ($i < ( count($fila[$j]["lista"]) -1 )))
											{
												echo "<img src='./images/arrow_down.png' title='Descer Consultor de Fila' style = 'cursor:hand;' onclick ='desce_consultor(".$consultor.",".$num_fila.")' >";
											}	
											echo $fila[$j]["lista"][$i]["ordem"]." - ".$fila[$j]["lista"][$i]["consultor"];
										}
										else
										{
											echo "-"; 
										}		 
								?>
					   				</td>
								<? 
								} 
								?>
					   		</tr>
							<? 
							}
							?> 
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

function recarrega_pagina()
{
	window.location = '<? echo "./index.php?local=".$local ?>';
}


setTimeout("recarrega_pagina()",60000);
</script>