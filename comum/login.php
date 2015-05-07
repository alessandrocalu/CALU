<?php
/**
 * @desc Sistema: CALU
 * @desc Inicia sessão de usuário 
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 22/03/2007 Atualizado: 22/03/2007
*/
?>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<script src="./js/functions.js"></script>
<style type=text/css>
/* Arredondamento */.rtop,.rbottom{display:block} .rtop *,.rbottom *{display:block;height: 1px;overflow: hidden}.r1{margin: 0 5px}.r2{margin: 0 3px}.r3{margin: 0 2px}.r4{margin: 0 1px;height: 2px}.rs1{margin: 0 2px}.rs2{margin: 0 1px}
</style>
<script>
  function getKey(e){
   if (e.keyCode == 13){
     document.formulario.submit();
   }
  }
</script>
</head>
<body onKeyPress="getKey(event);">
<script>
  //Arredondamento
  function NiftyCheck() { if(!document.getElementById || !document.createElement) return(false); var b=navigator.userAgent.toLowerCase(); if(b.indexOf("msie 5")>0 && b.indexOf("opera")==-1) return(false); return(true); }
  function Rounded(selector,bk,color,size){ var i; var v=getElementsBySelector(selector); var l=v.length; for(i=0;i<l;i++){ AddTop(v[i],bk,color,size); AddBottom(v[i],bk,color,size); } }
  function RoundedTop(selector,bk,color,size){ var i; var v=getElementsBySelector(selector); for(i=0;i<v.length;i++) AddTop(v[i],bk,color,size); }
  function RoundedBottom(selector,bk,color,size){ var i; var v=getElementsBySelector(selector); for(i=0;i<v.length;i++) AddBottom(v[i],bk,color,size); }
  function AddTop(el,bk,color,size){ var i; var d=document.createElement("b"); var cn="r"; var lim=4; if(size && size=="small"){ cn="rs"; lim=2} d.className="rtop"; d.style.backgroundColor=bk; for(i=1;i<=lim;i++){ var x=document.createElement("b"); x.className=cn + i; x.style.backgroundColor=color; d.appendChild(x); } el.insertBefore(d,el.firstChild); }
  function AddBottom(el,bk,color,size){ var i; var d=document.createElement("b"); var cn="r"; var lim=4; if(size && size=="small"){ cn="rs"; lim=2} d.className="rbottom"; d.style.backgroundColor=bk; for(i=lim;i>0;i--){ var x=document.createElement("b"); x.className=cn + i; x.style.backgroundColor=color; d.appendChild(x); } el.appendChild(d,el.firstChild); }
  function getElementsBySelector(selector){ var i; var s=[]; var selid=""; var selclass=""; var tag=selector; var objlist=[]; if(selector.indexOf(" ")>0){ s=selector.split(" "); var fs=s[0].split("#"); if(fs.length==1) return(objlist); return(document.getElementById(fs[1]).getElementsByTagName(s[1])); } if(selector.indexOf("#")>0){ s=selector.split("#"); tag=s[0]; selid=s[1]; } if(selid!=""){ objlist.push(document.getElementById(selid)); return(objlist); } if(selector.indexOf(".")>0){ s=selector.split("."); tag=s[0]; selclass=s[1]; } var v=document.getElementsByTagName(tag); if(selclass=="") return(v); for(i=0;i<v.length;i++){ if(v[i].className==selclass){ objlist.push(v[i]); } } return(objlist); }

  function marcaDesmarca(marca,local,contador){
     var i = 0;
     for (i = 0; i < contador ; i++){
  	 	eval("document.getElementById('"+local+i+"').checked = marca.checked;");		 
  	 }	
  
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
            <td  height="70px" >
			   <div style="font-size:20px;color:#FFFFFF;text-align:center;" >	
			   		<b>Login do Sistema</b>
			   </div>
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
<br>
<br>
<form name="formulario" action="index.php?local=login&acao=enviar" method="post">
<table class="contentTable" align = "center" >
  <tr>
    <td class="tableComponent" align = "center">
	  <?php $visual->exibeInput("login","text","",'16',50,"editBox","","Login:"); ?>
	</td>
  </tr>
  <tr>
    <td class="tableComponent" align = "center">
      <?php $visual->exibeInput("senha","password","",'16',50,"editBox","","Senha:"); ?>	
    </td>
  </tr>
  <tr>
	<td class="buttonBar" align = "right" >
	  <?php 
	  $visual->exibeInput("login","button","Entrar",'20','0',"","onClick=\"javascript:document.formulario.submit()\"",""); 
	  ?>	
	</td>
  </tr>						
</table>
</form>
<?php
//Para verificação de senha
if (isset($_GET["acao"]) && $_GET["acao"] == 'enviar') {
  
  //Recupera o login
  $login = $valida->validaCampo("Login",(isset($_POST['login'])?$_POST['login']:""),"text",1);
  $senha = $valida->validaCampo("Senha",(isset($_POST['senha'])?$_POST['senha']:""),"text",1); 

    //Verifica POST
  if ($erros = $valida->confereErros()){
    echo "<center>";
    echo "<font color=\"#FF0000\">Você deve digitar sua senha e login!<br>";
    echo "Descrição do erro: ";
    for ($i = 0;$i < count($erros) ;$i++) {  
      echo $erros[$i]."<br>";
    }  
    echo "</font><br>";
       
  } else {
    // Verifica Login 
   	if ($negocio->verificaLogin($login,$senha)) {
      //Volta para tela principal		
  	  if ($_SESSION["navegador_local"]){
  	  	echo "<script> window.location.replace('index.php?local=".$_SESSION["navegador_local"]."&chave=".$_SESSION["navegador_chave"]."&ordem=".$_SESSION["navegador_ordem"]."'); </script>";
      } else {
        echo "<script> window.location.replace('index.php'); </script>";
      } 
      exit;
    }  else {
      $erros = $negocio->getErros();	
      echo "<center>";
      echo "<font color=\"#FF0000\">Login Inválido!<br>";
      echo "Descrição do erro: ";
      for ($i = 0;$i < count($erros) ;$i++) {  
        echo $erros[$i]."<br>";
      }  
      echo "</font><br>";  
    }
  }
}
?>
</body>
</html>