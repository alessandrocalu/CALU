function verificaXY(nomeObjeto)
{
	document.getElementById(nomeObjeto).style.left = (event.clientX-140) +"";
	document.getElementById(nomeObjeto).style.top = (event.clientY+20) +"";
}

function start_edit_input(nome_input, nome_antigo, nome_btn_edit, nome_btn_confirma, nome_btn_cancela){
	editaObjeto(nome_input, nome_antigo); 
	setDisplay(nome_btn_edit,false); 
	setDisplay(nome_btn_confirma,true); 
	setDisplay(nome_btn_cancela,true);
}

function confirm_edit_input(id_campo, nome_input, valor_chave)
{
	xajax_gravar_campo(id_campo,document.getElementById(nome_input).value,valor_chave,nome_input,"text");
}

function cancel_edit_input(nome_input, nome_antigo, nome_btn_edit, nome_btn_confirma, nome_btn_cancela)
{
	cancelaObjeto(nome_input, nome_antigo); 
	setDisplay(nome_btn_edit,true); 
	setDisplay(nome_btn_confirma,false); 
	setDisplay(nome_btn_cancela,false);
}
	
function seleciona_dia(nome_area, nome_campo)
{
	var data_atual = document.getElementById(nome_campo).value;
	gera_html_calendario(nome_area, nome_campo, data_atual);
	//b = navigator.userAgent.toLowerCase();
    b= "";
	if(b.indexOf("msie")>0)
	{
		document.getElementById(nome_area).style.left = (event.clientX-140) +"";
		document.getElementById(nome_area).style.top = (event.clientY+20) +"";
	}
	if ((tempX-140) > 0) 
	{
		document.getElementById(nome_area).style.left = (tempX-140) +"";
	}
	else
	{
		document.getElementById(nome_area).style.left = "0";
	}
	document.getElementById(nome_area).style.top = (tempY+20) +"";
	document.getElementById(nome_area).style.display = "";
}
	
function exibe_text(nome_area, titulo, nome_campo, editavel, funcao_edicao)
{
	var text = document.getElementById(nome_campo).value;
	gera_html_text(nome_area, titulo, nome_campo, text, editavel, funcao_edicao);
	//b = navigator.userAgent.toLowerCase();
       b= "";
	if(b.indexOf("msie")>0)
	{
		document.getElementById(nome_area).style.left = (event.clientX-140) +"";
		document.getElementById(nome_area).style.top = (event.clientY+20) +"";
	}
	if ((tempX-140) > 0) 
	{
		document.getElementById(nome_area).style.left = (tempX-140) +"";
	}
	else
	{
		document.getElementById(nome_area).style.left = "0";
	}
	document.getElementById(nome_area).style.top = (tempY+20) +"";
	document.getElementById(nome_area).style.display = "";
}

function envia_dia_calendario(nome_area, nome_campo, ano, mes, dia)
{
	document.getElementById(nome_area).style.display = "none";
	if(dia < 10)
	{
		dia = "0"+dia;
	}
	if(mes < 10)
	{
		mes = "0"+mes;
	}
	document.getElementById(nome_campo).value = dia+"/"+mes+"/"+ano;
}

function gera_html_calendario(nome_area, nome_campo, string_data)
{
	var itens = string_data.split("/");
	var data;
	
	if (itens[0] == "0000" || itens[1] == "00" || itens[2] == "00")
	{
		data = new Date();
	}
	else
	{
		data = new Date(itens[2],itens[1]-1,itens[0]);
	}

	var hoje = new Date();
	var vet_dias = gera_data_calendario(data);	

	var div = document.getElementById(nome_area);
	div.innerHTML = "";

	// CRIA NOVO CONTAINER (DIV)
	var tablex = cria_elemento (div, "table", "table_"+nome_area, "bgcolor = '#AAAAAA' style='border: 1px solid #333333;'", "");
	var table = cria_elemento (tablex, "tbody", "tbody_"+nome_area, "", "");
		var tr = cria_elemento (table, "TR", "tr_ano_"+nome_area+"_"+i, "", "");
			var td = cria_elemento (tr, "TH", "td_ano_"+nome_area, " style = 'background-color: #666666; color : #FFFFFF' colspan = '7'", "");
				var data_temp = new Date((data.getFullYear()-1), data.getMonth(), data.getDate());
				cria_elemento (td, "input", "botao_menos_ano_"+nome_area, " type = 'button' value='<<'  onclick = 'gera_html_calendario(\""+nome_area+"\", \""+nome_campo+"\", \""+data_temp.getDate()+"/"+(data_temp.getMonth()+1)+"/"+data_temp.getFullYear()+"\")'", "");
				var select = cria_elemento (td, "select", "select_ano_"+nome_area, " onchange = 'gera_html_calendario(\""+nome_area+"\", \""+nome_campo+"\", \""+data_temp.getDate()+"/"+(data_temp.getMonth()+1)+"/\"+this.value)'", "");
					for (var a = data.getFullYear()-80; a < data.getFullYear()+31; a++)
					{
						var op = cria_elemento (select, "option", "op_ano_"+nome_area+"_"+a, " value = '"+a+"'", a);
					}
				var data_temp = new Date((data.getFullYear()+1), data.getMonth(), data.getDate());
				cria_elemento (td, "input", "botao_menos_mais_"+nome_area, " type = 'button'  value='>>' onclick = 'gera_html_calendario(\""+nome_area+"\", \""+nome_campo+"\", \""+data_temp.getDate()+"/"+(data_temp.getMonth()+1)+"/"+data_temp.getFullYear()+"\")'", "");
			var tr = cria_elemento (table, "TR", "tr_"+nome_area+"_"+i, "", "");
			var td = cria_elemento (tr, "TH", "td_mes_"+nome_area, " style = 'font-size:10;background-color: #04B201; color : #FFFFFF' colspan = '7'", "");
				var data_temp = new Date(data.getFullYear(), (data.getMonth()-1), data.getDate());
				cria_elemento (td, "input", "botao_menos_mes_"+nome_area, " type = 'button' value='<' onclick = 'gera_html_calendario(\""+nome_area+"\", \""+nome_campo+"\", \""+data_temp.getDate()+"/"+(data_temp.getMonth()+1)+"/"+data_temp.getFullYear()+"\")'", "");
				td.innerHTML += mesToString(data);
				var data_temp = new Date(data.getFullYear(), (data.getMonth()+1), data.getDate());
				cria_elemento (td, "input", "botao_mais_mes_"+nome_area, " type = 'button'  value='>' onclick = 'gera_html_calendario(\""+nome_area+"\", \""+nome_campo+"\", \""+data_temp.getDate()+"/"+(data_temp.getMonth()+1)+"/"+data_temp.getFullYear()+"\")'", "");

		var semana = new Array("D","S","T","Q","Q","S","S");
		var dias = 0;
		var contador = 0;
		for(var i=1; i < 8; i++)
		{
			var tr = cria_elemento (table, "TR", "tr_"+nome_area+"_"+i, "", "");
			for(var j=0;j < 7;j++)
			{
				if(i > 2 && j == 0 && vet_dias[dias] < 0)
				{
					i=10;
					break;
				}
			
				var dia = (data.getDate() < 10) ? ("0"+data.getDate()) : data.getDate();
				var mes = ((data.getMonth()+1) < 10) ? ("0"+(data.getMonth()+1)) : (data.getMonth()+1);
				var ano = data.getFullYear();
				var data_calendario = ano+""+mes+""
				if (vet_dias[dias] < 10)
				{
					data_calendario += "0";
				}
				data_calendario += vet_dias[dias];
				
				if(i==1)
				{
					var td = cria_elemento (tr, "TH", "td_"+nome_area+"_"+contador, " style = 'font-size:9;color : #FFFFFF' ", semana[j]);
				}
				else if(vet_dias[dias] < 0) // OUTRO MES
				{
					dias++;
					var td = cria_elemento (tr, "TD", "td_"+nome_area+"_"+contador, " ", "");
				}
				else // DIAS NORMAIS
				{
					var td = cria_elemento (tr, "TD", "td_"+nome_area+"_"+contador, "align = 'right' style = 'font-size:9; cursor:pointer; color : #FFFFFF' onclick = 'envia_dia_calendario(\""+nome_area+"\", \""+nome_campo+"\", "+ano+", "+mes+", "+vet_dias[dias]+")'", "&nbsp;"+vet_dias[dias++]+"&nbsp;");
				}
					
				contador++;
			}
		}
		var tr = cria_elemento (table, "TR", "tr_final_"+nome_area+"_"+i, "", "");
			var td = cria_elemento (tr, "TH", "td_final2_"+nome_area, " style = 'font-size:9;background-color: #666666; color : #FFFFFF; cursor:pointer;'  colspan = '2' onclick = 'envia_dia_calendario(\""+nome_area+"\", \""+nome_campo+"\", "+hoje.getFullYear()+", "+(hoje.getMonth()+1)+", "+hoje.getDate()+")'", "HOJE");
			var td = cria_elemento (tr, "TH", "td_final3_"+nome_area, " ", "");
			var td = cria_elemento (tr, "TH", "td_final4_"+nome_area, " style = 'font-size:9;background-color: #666666; color : #FFFFFF; cursor:pointer;'  colspan = '1' onclick = 'envia_dia_calendario(\""+nome_area+"\", \""+nome_campo+"\", \"0000\", \"0\", \"0\")'", "NA");
			var td = cria_elemento (tr, "TH", "td_final0_"+nome_area, " ", "");
			var td = cria_elemento (tr, "TH", "td_final1_"+nome_area, " style = 'font-size:9; background-color: #666666; color : #FFFFFF; cursor:pointer;' colspan = '2' onclick = \"document.getElementById('"+nome_area+"').style.display = 'none';\"", "FECHAR");
			
		document.getElementById("op_ano_"+nome_area+"_"+(data.getFullYear())).selected = 1;
}


function gera_html_text(nome_area, titulo, nome_campo, string_data, editavel, funcao_edicao)
{
	var div = document.getElementById(nome_area);
	div.innerHTML = "";

	// CRIA NOVO CONTAINER (DIV)
	var tablex = cria_elemento (div, "table", "table_"+nome_area, "bgcolor = '#AAAAAA' style='border: 1px solid #333333;'", "");
	var table = cria_elemento (tablex, "tbody", "tbody_"+nome_area, "", "");
		var tr_th = cria_elemento (table, "TR", "tr_th_"+nome_area, "", "");
			var th = cria_elemento (tr_th, "TH", "th_titulo_"+nome_area, " colspan = '6' style = 'font-size:9; background-color: #666666; color : #FFFFFF; '", titulo);
		var tr = cria_elemento (table, "TR", "tr_"+nome_area, "", "");
			var td = cria_elemento (tr, "TD", "td_"+nome_area, " style = 'font-size:10;background-color: #04B201; color : #FFFFFF' colspan = '6'", "");
				if (editavel == 1)
				{
					cria_elemento (td, "textarea", "box_"+nome_campo, " rows=5 cols=50", string_data);
				}
				else
				{
					cria_elemento (td, "textarea", "box_"+nome_campo, " rows=5 cols=50 readonly", string_data);
				}	
			var tr2 = cria_elemento (table, "TR", "tr_final_"+nome_area, "", "");
		if (editavel == 1)
		{
			var td2 = cria_elemento (tr2, "TH", "td_edita_"+nome_area, " style = 'font-size:9; background-color: #666666; color : #FFFFFF; cursor:pointer;' colspan = '3' onclick = \""+funcao_edicao+"; document.getElementById('"+nome_area+"').style.display = 'none';\"", "GRAVAR");
			var td3 = cria_elemento (tr2, "TH", "td_final_"+nome_area, " style = 'font-size:9; background-color: #666666; color : #FFFFFF; cursor:pointer;' colspan = '3' onclick = \"document.getElementById('"+nome_area+"').style.display = 'none';\"", "FECHAR");
		}
		else
		{
			var td2 = cria_elemento (tr2, "TH", "td_final_"+nome_area, " style = 'font-size:9; background-color: #666666; color : #FFFFFF; cursor:pointer;' colspan = '6' onclick = \"document.getElementById('"+nome_area+"').style.display = 'none';\"", "FECHAR");
		}
}

function gera_html_box(nome_area, titulo, editavel, funcao_edicao, lista_nome, lista_tipo, lista_tamanho, lista_maxlength, lista_rotulo, lista_valor)
{
	var tipo = 'text';
	var mascara = '';
	var div = document.getElementById(nome_area);
	div.innerHTML = "";
	
	// CRIA NOVO CONTAINER (DIV)
	var tablex = cria_elemento (div, "table", "table_"+nome_area, "bgcolor = '#AAAAAA' style='border: 1px solid #333333;'", "");
	var table = cria_elemento (tablex, "tbody", "tbody_"+nome_area, "", "");
		var tr = cria_elemento (table, "TR", "tr_"+nome_area, "", "");
			var th = cria_elemento (tr, "TH", "th_titulo_"+nome_area, " colspan = '2' style = 'font-size:9; background-color: #666666; color : #FFFFFF; '", titulo);
			
				for (var i = 0; i < lista_nome.length ;i++)
				{
					tr = cria_elemento (table, "TR", "tr_campo_"+lista_nome[i], "", "");
						td = cria_elemento (tr, "TD", "td_label_"+lista_nome[i], " align = 'right' style = 'font-size:10;background-color: #04B201; color : #FFFFFF' ", lista_rotulo[i]);
						td = cria_elemento (tr, "TD", "td_valor_"+lista_nome[i], " style = 'font-size:10;background-color: #04B201; color : #FFFFFF' ", "");

						tipo = 'text';
						mascara = '';
						if (lista_tipo[i] == 'cnpj'){
							mascara = " onKeyPress='FormataCnpj(this,event)' ";
						}
						if (lista_tipo[i] == 'moeda'){
							mascara = " onKeyDown='FormataMoeda(this,event)'  ";
						}	
						if (lista_tipo[i] == 'data'){
							mascara = " onKeyUp='FormataData(this,event)' ";
						}
						if (lista_tipo[i] == 'inteiro'){ 
							mascara = " onKeyDown='FormataInteiro(this,event)' ";
						}
						cria_elemento (td, "input", lista_nome[i], " value = '"+lista_valor[i]+"' type= "+tipo+" size = "+lista_tamanho[i]+" maxlength = "+lista_maxlength[i]+mascara, "");
							
				}	
		var tr2 = cria_elemento (table, "TR", "tr_final_"+nome_area, "", "");
		if (editavel == 1)
		{
			var td2 = cria_elemento (tr2, "TH", "td_edita_"+nome_area, " style = 'font-size:9; background-color: #666666; color : #FFFFFF; cursor:pointer;' onclick = \""+funcao_edicao+"; document.getElementById('"+nome_area+"').style.display = 'none';\"", "GRAVAR");
			var td3 = cria_elemento (tr2, "TH", "td_final_"+nome_area, " style = 'font-size:9; background-color: #666666; color : #FFFFFF; cursor:pointer;' onclick = \"document.getElementById('"+nome_area+"').style.display = 'none';\"", "FECHAR");
		}
		else
		{
			var td2 = cria_elemento (tr2, "TH", "td_final_"+nome_area, " style = 'font-size:9; background-color: #666666; color : #FFFFFF; cursor:pointer;' colspan = '2' onclick = \"document.getElementById('"+nome_area+"').style.display = 'none';\"", "FECHAR");
		}
	if ((tempX-140) > 0) 
	{
		div.style.left = (tempX-140) +"";
	}
	else
	{
		div.style.left = "0";
	}
		
	if ((tempY+20) > 0) 
	{
		div.style.top = (tempY+20) +"";
	}
	else
	{
		div.style.top = "0";
	}
	div.style.display = "";
		
}

function mesToString(dia){
	switch(dia.getMonth()){
		case 0: return "Janeiro";
		case 1: return "Fevereiro";
		case 2: return "Março";
		case 3:	return "Abril";
		case 4: return "Maio";
		case 5: return "Junho";
		case 6: return "Julho";
		case 7: return "Agosto";
		case 8: return "Setembro";
		case 9: return "Outubro";
		case 10: return "Novembro";
		case 11: return "Dezembro";
	}
}

function gera_data_calendario(data){
	var vet_dias = []
	if(data == null) 
		return;
	var hj = data.getTime();
	var mes = data.getMonth();
	data.setDate(1);
	var start = data.getDay();
	var dias = 0;
	for(var i = 1-start; i <= 45-start; i++)
	{
		data.setTime(hj);
		data.setDate(i);
		vet_dias[dias++] = ( (mes != data.getMonth()) ? -data.getDate() : data.getDate() );
	}
	data.setTime(hj);
	return vet_dias;
}

function muda_mes_calendario(amount, tb_name)
{
	data.setMonth(amount+data.getMonth());
	gera_data_calendario(data);	
	gera_html_calendario(tb_name);
}