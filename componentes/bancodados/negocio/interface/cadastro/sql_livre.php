<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Template para SQL Livre
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 23/02/2009 Atualizado: 23/02/2009
*/
if ($result && ($_POST["visual"] == "1") ){
	header('Content-type: application/force-download');
	header('Content-Disposition: attachment; filename="arquivo.csv"');
	$arquivo = new arquivoTxt("CSV");

	$campos_tela =  array();
	$colunas = array();
	for ($i = 0; $i < count($result);$i++){
		$dados = $result[$i];
		reset($dados);
		foreach ($dados as $key => $value){
			if (!is_numeric($key)) {
				if (!$i){
					$colunas[0][] = $key; 
				}
				$colunas[$i+1][] = $value; 
			}	
		}
	}
	$arquivo->escreveCSV($colunas);
	exit;
}
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
<? 
if ($result && ($_POST["visual"] == "0") ){
?>
<table class="tab" width="100%" border="0" cellspacing="0">
<?
	$tab = -1;
	for ($i = 0; $i <= count($result);$i++){
		if ($i)
		{
			$dados = $result[$i-1];
		}
		else
		{
			$dados = $result[$i];
		}		
		reset($dados);
		$chave = 0;
		?>
	<tr>
		<?
		foreach ($dados as $key => $value){
			if (!is_numeric($key)) {
				if (!$i){
					echo "<th class='result_cliente'>".$key."</th>";
					//Captura nome de tabela através de campo principal
					if (($tab == -1) && strpos("_".$key,"id_"))
					{
						$tab = substr($key,3);
						$tab = new tabela("calu_".$tab);
						$lista_campos = $tab->campos;
					}
				}
				else
				{
					if (strpos("_".$key,"id_")){
						$chave = $value;
					}	
					echo "<td class='result_cliente'>";
					if (($tab > 0) && $chave)
					{
						//Captura $id_campo atraves de tabela
						$id_campo = 0;
						for ($num_campo = 0; (($num_campo < count($lista_campos)) && !$id_campo ); $num_campo++){
							if ($lista_campos[$num_campo]["nome"] == $key)
							{
								$id_campo = $lista_campos[$num_campo]["id_tabela_campo"];
							}	
						}	
						if ($id_campo)
						{
							$visual->exibeInputXajax($key."_".$chave, "text", $value, '20', '200', 'editBox', '', '', $id_campo, $chave);
						}	
						else
						{
							$value;
						}							
					}
					else
					{
						echo $value;
					}		
					echo "</td>";
				}	 
			}	
		}
		?>
	</tr>
		<?
	}
?>
  <tr>
    <td>
 
	</td>
   </tr>						
</table>		
<?
}
?>
<br>
<form id="formulario" name="formulario" method="post" action="./index.php?local=sql_livre&insert=1" target="_blank">
<input type="hidden" value="<? echo $_GET['ordem']; ?>" name="ordem" id="ordem">
<input type="hidden" value="<? echo $_GET['chave']; ?>" name="chave" id="chave">
<table class="contentTable" align = "center" > 
  <tr>
	<td class="buttonBar" align = "center" >
		<b>Entre com o código SQL:</b>
	</td>
  </tr>
  <tr>
	<td  class = "tab" align = "center">
		<textarea name="sql" id="sql" class="editBox" cols="120" rows="20"><? echo $_POST["sql"]; ?></textarea>
	</td>
  </tr>
  <tr>
	<td  class = "tab" align = "center">
		Formato<select name="visual" id="visual" class="editBox">
			<option value='0' selected >HTML</option>
			<option value='1' >CSV</option>
			<option value='2' >PDF</option>
		</select> 
	</td>
  </tr>
  <tr>
	<td class="buttonBar" align = "right">
		<input type="submit" value="Rodar SQL">
	</td>
  </tr>						
</table>
</form>