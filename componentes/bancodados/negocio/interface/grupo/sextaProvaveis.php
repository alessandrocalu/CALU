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

  function marcaDesmarca(marca,local,contador){
     var i = 0;
     for (i = 0; i < contador ; i++){
  	 	eval("document.getElementById('"+local+i+"').checked = marca.checked;");		 
  	 }	
  }

  function mostra_clientes(valor){
  	 if (valor){
		document.getElementById("clientes").style.display = "";		 
	 }
	 else
	 {
	 	document.getElementById("clientes").style.display = "none";		 
	 }
  }
    
  function mostra_passivas(valor){
  	 if (valor){
		document.getElementById("passivas").style.display = "";		 
	 }
	 else
	 {
	 	document.getElementById("passivas").style.display = "none";		 
	 }
  }
  
  function mostra_ativas(valor){
  	 if (valor){
		document.getElementById("ativas").style.display = "";		 
	 }
	 else
	 {
	 	document.getElementById("ativas").style.display = "none";		 
	 }
  }
  
    function mostra_agenda(valor){
  	 if (valor){
		document.getElementById("agenda").style.display = "";		 
	 }
	 else
	 {
	 	document.getElementById("agenda").style.display = "none";		 
	 }
  }

</script>
<table width="100%" >
  <tr>
    <td align = "left" width="1%"  height="60px">
		<img src="./images/logo.jpg"  width="150" height="60px" >
	</td>
	<td width="99%" valign = "bottom" height="65px" > 	
	  <div align='center' class='cerca_bubble' style='width:100%; height:60px' style="margin-bottom:5">
		<table width=100% align=center cellpadding=2 cellspacing=2 bgcolor=#C3D9FF >
		  <tr>
            <td>
			   <div style="font-size:20px;text-align:left;" >	
			   		<b>Sexta de Prováveis - Consultor: Vitor Hugo</b>
			   </div>
			   <div style="font-size:12px;text-align:right;">
			   <b>
			   <?
			     $visual->exibeCheckBox("cb_clientes","0","editBox","onClick=mostra_clientes(this.checked)","Pesquisa Clientes",0);
			   ?>	 
   			   &nbsp;
			   &nbsp;
			   <?
			     $visual->exibeCheckBox("cb_passivas","1","editBox","onClick=mostra_passivas(this.checked)","Lista Passivas",0);
			   ?>	 
   			   &nbsp;
			   &nbsp;
			   <?
 			     $visual->exibeCheckBox("cb_ativas","1","editBox","onClick=mostra_ativas(this.checked)","Lista Ativas",0);
			   ?>	
   			   &nbsp;
			   &nbsp;
			   <?
			     $visual->exibeCheckBox("cb_agenda","1","editBox","onClick=mostra_agenda(this.checked)","Agenda Diária",0);
			   ?>
			   &nbsp;
			   &nbsp;
			   &nbsp;
			   &nbsp;
			   &nbsp;
			   &nbsp;
			   <?
			     $visual->exibeInput("login","text","",'15',20,"editBox","","Login:");
				 $visual->exibeInput("senha","password","",'15',20,"editBox","","Senha:");
			   ?>
			   </b>
			   </div>
		   </td>
   	     </tr>
       </table>
	 </div> 
	 <script>
	 	Rounded('div.cerca_bubble','#FFFFFF', '#C3D9FF');
	 </script>
	</td>
  </tr>						
</table>		
<table  width="100%" >
  <tr>
    <td align = "left">
		<div id="clientes" style="display:none">
			<table class="commonTable" width="100%" border="0" cellspacing="0">  
				<tr>
					<td width="180px">
					   <div align='center' class='cliente_bubble'  style='width:180px; height:100%' style="margin-bottom:3;">
						<table width=100% cellpadding=2 cellspacing=2 bgcolor=#CCCCCC >
					  	 <tr>
            			   <td>
						   	  Nome:<br>	
							  <? $visual->exibeInput("nome","text","",'30',15,"editBox","",""); ?>	<br>
							  Telefone:<br>	
							  <? $visual->exibeInput("telefone","text","",'15',10,"editBox","",""); ?> 
   							  <? $visual->exibeLink("Buscar","\"javascript:alert('Pesquisa de clientes realizada!');\"","","button"); ?>	
						   </td>
					     </tr>
						</table>  
		  			   </div>
						<script>
					 	Rounded('div.cliente_bubble','#FFFFFF', '#CCCCCC');
					    </script>
					</td>
					<td>	
					   <table class="commonTable" width="100%" border="0" cellspacing="0">
 					    <tr>
 					    	<th class="result_cliente" align="left" colspan=4>
 					    	<?
							$visual->exibeInput("criarProspecao","button","Criar Prospeção",'20','0',""," onClick=\"modalWin('./index.php?local=prospeccao','Prospeccção',300,180);\"","");	
							?>
 					    	</th>
 					    </tr>	
		   				<tr>
							<th class="result_cliente" width="3%">
							<?
						     $visual->exibeCheckBox("pesquisaTodos1","0","editBox"," onClick=\"marcaDesmarca(this,'pesquisa',3);\"","",0);
						    ?>	 
							</th>
							<th class="result_cliente"  width="40%">Pesquisa Cliente</th>
							<th class="result_cliente"  width="40%">Contato</th>
							<th class="result_cliente"  width="20%">Telefone</th>
						</tr>
						<? for( $i = 0; $i < 3;$i++) { ?>
						<tr>
	  						<td class="top">
			 			    <?
						     $visual->exibeCheckBox("pesquisa".$i,"0","editBox","","",0);
						    ?>	 
		   					</td>
					   		<td class="result_cliente">
					     		Lepton Sistemas S/C Ltda
					   		</td>
							<td class="result_cliente">
					     		Alessandro Alonso Ferreira Calu
					   		</td>
							<td class="result_cliente">
					     		(31)3481-2225
					   		</td>
					   	</tr>
						<? } ?>
				   	   </table>
				   </td>
				</tr>  
			</table>	
		   <br>
		   <img src="./images/linhaHorizontal.gif"  width="100%" height="2">
		   <br>
		</div>
		<div id="passivas">
		   <table class="commonTable" width="100%" border="0" cellspacing="0">
		   	<tr>
 				<th class="result_passivo" align="left" colspan=6>
 				<?
					$visual->exibeInput("agendarPassiva","button","Agendar",'20','0',""," onClick=\"modalWin('./index.php?local=agendar','Agendar',300,180);\"","");	
					$visual->exibeInput("desistirPassiva","button","Desistir",'20','0',""," onClick=\"confirm('Deseja realmente desistir deste cliente?');\"","");	
				?>
 				</th>
 		    </tr>	
			<tr>
				<th class="result_passivo" width="3%">
				<?
				$visual->exibeCheckBox("passivaTodos1","0","editBox"," onClick=\"marcaDesmarca(this,'passiva',3);\" ","",0);
				?>	 
				</th>
				<th class="result_passivo"  width="25%">Clientes Passivos</th>
				<th class="result_passivo"  width="2%">I</th>
				<th class="result_passivo"  width="2%">F</th>
				<th class="result_passivo"  width="25%">Contato</th>
				<th class="result_passivo"  width="18%">Telefone</th>
				<th class="result_passivo"  width="25%">Follow Up</th>
			</tr>
			<? for( $i = 0; $i < 3;$i++) { ?>
			<tr>
	  			<td class="top">
 			    <?
			     $visual->exibeCheckBox("passiva".$i,"0","editBox","","",0);
			    ?>	 
		   		</td>
		   		<td class="result_passivo">
		     		Lepton Sistemas S/C Ltda
		   		</td>
				<td class="result_passivo">
		     	X	
		   		</td>
				<td class="result_passivo">&nbsp;
		     		
		   		</td>
				<td class="result_passivo">
		     		Alessandro Alonso Ferreira Calu
		   		</td>
				<td class="result_passivo">
		     		(31)3481-2225
		   		</td>
				<td class="result_passivo">&nbsp;
		     		
		   		</td>
		   	</tr>
			<? } ?>
		   </table>
		   <br>
		   <img src="./images/linhaHorizontal.gif"  width="100%" height="2">
		   <br>
		</div>
		<div id="ativas">
		  <table class="commonTable" width="100%" border="0" cellspacing="0">
		   	<tr>
 				<th class="result_ativo"align="left" colspan=6>
 				<?
					$visual->exibeInput("agendarAtiva","button","Agendar",'20','0',""," onClick=\"modalWin('./index.php?local=agendar','Agendar',300,180);\"","");	
					$visual->exibeInput("desistirAtiva","button","Desistir",'20','0',""," onClick=\"confirm('Deseja realmente desistir deste cliente?');\"","");	
				?>
 				</th>
 		    </tr>	
		   	<tr>
				<th  class="result_ativo" width="3%">
				<?
				$visual->exibeCheckBox("ativaTodos1","0","editBox"," onClick=\"marcaDesmarca(this,'ativa',5);\" ","",0);
				?>	 
				</th>
				<th class="result_ativo"  width="25%">Clientes Ativos</th>
				<th class="result_ativo"  width="25%">Contato</th>
				<th class="result_ativo"  width="17%">Telefone</th>
				<th class="result_ativo"  width="30%">Follow Up</th>
			</tr>
			<? for( $i = 0; $i < 5;$i++) { ?>
			<tr>
	  			<td class="top">
 			    <?
			     $visual->exibeCheckBox("ativa".$i,"0","editBox","","",0);
			    ?>	 
		   		</td>
		   		<td class="result_ativo">
		     		Lepton Sistemas S/C Ltda
		   		</td>
				<td class="result_ativo">
		     		Alessandro Alonso Ferreira Calu
		   		</td>
				<td class="result_ativo">
		     		(31)3481-2225
		   		</td>
				<td class="result_ativo">&nbsp;
		     		
		   		</td>
		   	</tr>
			<? } ?>
		   </table>
		   <br>
		   <img src="./images/linhaHorizontal.gif"  width="100%" height="2">
		   <br>
		</div>
		<div id="agenda">
			<table class="commonTable" width="100%" border="0" cellspacing="0">  
				<tr>
					<td width="180px">
					   <div align='center' class='agenda_bubble'  style='width:180px; height:100%' style="margin-bottom:3;">
						<table width=100% cellpadding=2 cellspacing=2 bgcolor=#C3D9FF >
					  	 <tr>
            			   <td>
						   	  <iframe src="./componentes/visual/calendario/calendar2.php" width="100%" height="100%" scrolling="no"  frameborder="0"></iframe>
						   </td>
					     </tr>
						</table>  
		  			   </div>
						<script>
					 	Rounded('div.agenda_bubble','#FFFFFF', '#C3D9FF');
					    </script>
					</td>
					<td>	
			  		  <table class="commonTable" width="100%" border="0" cellspacing="0">
		   				<tr>
							<th class="result"  width="5%">Hora</th>
							<th class="result"  width="30%">Clientes/ Atividades Diárias</th>
							<th class="result"  width="35%">Contato</th>
							<th class="result"  width="15%">Telefone</th>
							<th class="result"  width="15%">Hot Line</th>
						</tr>
						<? for( $i = 0; $i < 7;$i++) { ?>
						<tr>
					   		<td class="result">
		    			 		<? echo (8+(2*$i)); ?>:00
					   		</td>

					   		<td class="result">
		    			 		Lepton Sistemas S/C Ltda
					   		</td>
							<td class="result">
		    			 		Alessandro Alonso Ferreira Calu
					   		</td>
							<td class="result">
					     		(31)3481-2225
					   		</td>
							<td class="result">
								<select class="editBox">
								   <option selected>Prospeção</option>
								   <option >Visita</option>
								   <option >Envio de Contrato</option>
								   <option >Desistencia</option>								   
								   <option >Fechado</option>
								   
								</select>
		   					</td>
					   	</tr>
						<? } ?>
					  </table>
					</td>
				</tr>	
			</table>	  
		</div>
	</td>
  </tr>						
</table>
</body>
</html>