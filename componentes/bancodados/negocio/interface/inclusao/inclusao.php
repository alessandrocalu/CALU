<html>
<head>
<title>Sexta de Prováveis</title>
<link rel="stylesheet" type="text/css" href="./css/style.css">
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
  function cancelar(){
	close();
  }
</script>
<table class="commonTable" width="100%" border="0" cellspacing="0">  
	<tr>
		<td width="250px">
			<div align='center' class='inclusao_bubble'  style='width:250px; height:100%' style="margin-bottom:3;">
				<table width=100% cellpadding=2 cellspacing=2 bgcolor=#CCCCCC >
					<tr>
            			<td>
					  	  Nome:	
						  <? $visual->exibeInput("nome","text","Lepton Sistemas",'30',15,"editBox","",""); ?>	<br>
						  Telefone:
						  <? $visual->exibeInput("telefone","text","3466-1105",'15',10,"editBox","",""); ?> <br>				  
   					      Data:
						  <? $visual->exibeInput("data","text","10/01/2008",'15',10,"editBox","",""); ?><br> 				  
                          <? $visual->exibeInput("confirmar","button","Confirmar",'15',0,"editBox","",""); ?> 				  
					  </td>
					</tr>
				</table>  
		  	</div>
			<script>
				Rounded('div.inclusao_bubble','#FFFFFF', '#CCCCCC');
			</script>
		</td>
	</tr>  
</table>	
</body>
</html>