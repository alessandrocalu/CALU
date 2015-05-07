<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Template para Interface de Cadastro simples
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 17/03/2008 Atualizado: 17/03/2008
*/
$campos_tela =  $negocio->getCamposTela("cadastro"); 
if ($campos_tela){
	$total_colunas = 1;
	$total_linhas = 1;
	for ($i = 0; $i < count($campos_tela); $i++){
		if ($campos_tela[$i]["ordem"] && !$nome_cadastro){
			$nome_cadastro = $campos_tela[$i]["rotulo"];
		}
		// Primeiro digito de ordem representa linha 
		// Segundo digito de ordem representa coluna
		$total_colunas_novo = $campos_tela[$i]["ordem"] - (floor($campos_tela[$i]["ordem"]/10)*10); 
		$total_linhas_novo = floor($campos_tela[$i]["ordem"]/10);
		if ($total_colunas < $total_colunas_novo){
			$total_colunas = $total_colunas_novo;
		}	
		if ($total_linhas < $total_linhas_novo){
			$total_linhas = $total_linhas_novo;
		}	
	}	
}
else
{
	$total_colunas = 1;
	$total_linhas = 1;
}
$dados_consulta = array();
if ($_GET["se_campo"] || $_GET["proposta"]){
	$dados_consulta = $negocio->getDadosTela();
}

//$funcoes->dump($dados_consulta); 
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
  function getKey(e){
   if (e.keyCode == 13){
     document.formulario.submit();
   }
  }

  //Arredondamento
  function NiftyCheck() { if(!document.getElementById || !document.createElement) return(false); var b=navigator.userAgent.toLowerCase(); if(b.indexOf("msie 5")>0 && b.indexOf("opera")==-1) return(false); return(true); }
  function Rounded(selector,bk,color,size){ var i; var v=getElementsBySelector(selector); var l=v.length; for(i=0;i<l;i++){ AddTop(v[i],bk,color,size); AddBottom(v[i],bk,color,size); } }
  function RoundedTop(selector,bk,color,size){ var i; var v=getElementsBySelector(selector); for(i=0;i<v.length;i++) AddTop(v[i],bk,color,size); }
  function RoundedBottom(selector,bk,color,size){ var i; var v=getElementsBySelector(selector); for(i=0;i<v.length;i++) AddBottom(v[i],bk,color,size); }
  function AddTop(el,bk,color,size){ var i; var d=document.createElement("b"); var cn="r"; var lim=4; if(size && size=="small"){ cn="rs"; lim=2} d.className="rtop"; d.style.backgroundColor=bk; for(i=1;i<=lim;i++){ var x=document.createElement("b"); x.className=cn + i; x.style.backgroundColor=color; d.appendChild(x); } el.insertBefore(d,el.firstChild); }
  function AddBottom(el,bk,color,size){ var i; var d=document.createElement("b"); var cn="r"; var lim=4; if(size && size=="small"){ cn="rs"; lim=2} d.className="rbottom"; d.style.backgroundColor=bk; for(i=lim;i>0;i--){ var x=document.createElement("b"); x.className=cn + i; x.style.backgroundColor=color; d.appendChild(x); } el.appendChild(d,el.firstChild); }
  function getElementsBySelector(selector){ var i; var s=[]; var selid=""; var selclass=""; var tag=selector; var objlist=[]; if(selector.indexOf(" ")>0){ s=selector.split(" "); var fs=s[0].split("#"); if(fs.length==1) return(objlist); return(document.getElementById(fs[1]).getElementsByTagName(s[1])); } if(selector.indexOf("#")>0){ s=selector.split("#"); tag=s[0]; selid=s[1]; } if(selid!=""){ objlist.push(document.getElementById(selid)); return(objlist); } if(selector.indexOf(".")>0){ s=selector.split("."); tag=s[0]; selclass=s[1]; } var v=document.getElementsByTagName(tag); if(selclass=="") return(v); for(i=0;i<v.length;i++){ if(v[i].className==selclass){ objlist.push(v[i]); } } return(objlist); }
  
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
<br>
<br>
<form id="formulario" name="formulario" method="post" enctype="multipart/form-data">
<input type="hidden" value="<? echo $ordem; ?>" name="ordem" id="ordem">
<input type="hidden" value="<? echo $chave; ?>" name="chave" id="chave">
<?
for ($k = 0; $k < count($campos_tela); $k++){
	if (!$campos_tela[$k]["ordem"]){
		$visual->desenha_campos_edicao($dados_consulta[0],$campos_tela[$k],0); 
	}
}
?>
<table class="contentTable" align = "center" > 
  <tr>
		<td class="buttonBar" align = "center"  colspan="<? echo $total_colunas*2; ?>" >
			<table align = "center" width = "100%" >
			<tr>
				<td align = "center">
				   <div style="font-size:16px;text-align:center;" >	
				   		<b>::Cadastro <? echo $nome_cadastro; ?>::</b>
				   </div>
				</td>
			</tr>
			</table>
		</td>
  </tr>
  <?
  for ($i = 1; $i <= $total_linhas ;$i++){
  ?>
  <tr>
		<?
	        for ($j = 1; $j <= $total_colunas ;$j++){
				for ($k = 0; $k < count($campos_tela); $k++){
					//Verifica se existe campo com a ordem Linha+Coluna
					if ($campos_tela[$k]["ordem"] == (($i*10)+$j)){
						if ($campos_tela[$k]["nome_comportamento"]!="labelText"){
			?>	
							<td class = "tab" align = "right">
								<b>
								<? 
									echo ($campos_tela[$k]["nome_comportamento"]!="checkbox"?$campos_tela[$k]["rotulo"]:"");  
								?>
								</b>
							</td>
			<?
						}
			?>
						<td  class = "tab" align = "left"  <? echo ($campos_tela[$k]["elemento"]?" colspan='".$campos_tela[$k]["elemento"]."' ":""); ?>>
							<? 
							$rotulo = "";
							$exibe_rotulo = 0;
							if (($campos_tela[$k]["nome_comportamento"]=="checkbox") ||
								($campos_tela[$k]["nome_comportamento"]=="labelText")){	
								$rotulo =  $campos_tela[$k]["rotulo"];	
								$exibe_rotulo = 1;
							}
							if ($rotulo){
								$rotulo = "<b>".$rotulo."</b>";
							}
							$visual->desenha_campos_edicao($dados_consulta[0],$campos_tela[$k],$exibe_rotulo); 
							?>	
						</td>
			<?
					}
				} 
			}
			?>
  </tr>
  <?
  }
  ?>
  <tr >
	<td class="buttonBar" align = "right"  colspan="<? echo $total_colunas*2; ?>"   >
		<img src="./images/linhaHorizontal.gif"  width="100%" height="1">
		<table align = "right" width = "100%" >
			<tr>
				<td align = "right">
					<? 
					$acoes_tela = $negocio->getAcoesTela(); 
					for ($i = 0; $i < count($acoes_tela); $i++){
						$visual->exibeInput("acao_bottom_".$acoes_tela[$i]["id_tela_acao"],"button",$acoes_tela[$i]["nome"] ,'20','0',""," onClick=\"".$acoes_tela[$i]["link"]."\"","");	
						?>
						&nbsp;
						<?
					}
					?>
				</td>
			</tr>
		</table>	
	</td>
  </tr>						
</table>
</form>
<script>
<? 
$mensagem = $negocio->getMensagem();
if ($mensagem) { 
	echo "alert('".$mensagem."')";
}
?>
</script>