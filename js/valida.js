function validaAno(ano)
{
  var valor = document.getElementById(ano).value; 
  if (valor <= 0){
    document.getElementById(ano).value = '00';
  } else {
    if (valor < 10){
      document.getElementById(ano).value = '0'+valor;
    }  
  }
}

function validaMes(mes,dia)
{
  var valorMes = document.getElementById(mes).value; 
  var valorDia = document.getElementById(dia).value; 
  if (valorMes <= 0){
    valorMes = 1;
    document.getElementById(mes).value = '01';
  } else {
    if (valor < 10){
      document.getElementById(mes).value = '0'+valorMes;
    }  
  }
}

function validaDia(mes,dia)
{
  if (valor <= 0){
    objeto.value = '01';
  } else {
    if (valor < 10){
      objeto.value = '0'+objeto.value;
    }  
  }
}

function validaNumero(objeto)
{
  if (valor <= 0){
    objeto.value = 0;
  } 
}

function editaObjeto(component, component_antigo)
{
	document.getElementById(component_antigo).value = document.getElementById(component).value;
	document.getElementById(component).disabled = false;
	document.getElementById(component).focus();
}

function cancelaObjeto(component, component_antigo)
{
	document.getElementById(component).value = document.getElementById(component_antigo).value;
	document.getElementById(component).disabled = true;
}

function confirmaObjeto(component, component_antigo)
{
	document.getElementById(component_antigo).value = document.getElementById(component).value;
	document.getElementById(component).disabled = true;
}

function focalizaObjeto(objeto)
{
	objeto.style.backgroundColor = '#FF4F4F';
	objeto.focus();
}

function FormataCnpj(campo, teclapres)
{
	var tecla = teclapres.keyCode;
	var vr = new String(campo.value);
	vr = vr.replace(".", "");
	vr = vr.replace("/", "");
	vr = vr.replace("-", "");
	tam = vr.length + 1;
	if (tecla != 14)
	{
		if (tam == 3)
			campo.value = vr.substr(0, 2) + '.';
		if (tam == 6)
			campo.value = vr.substr(0, 2) + '.' + vr.substr(2, 5) + '.';
		if (tam == 10)
			campo.value = vr.substr(0, 2) + '.' + vr.substr(2, 3) + '.' + vr.substr(6, 3) + '/';
		if (tam == 15)
			campo.value = vr.substr(0, 2) + '.' + vr.substr(2, 3) + '.' + vr.substr(6, 3) + '/' + vr.substr(9, 4) + '-' + vr.substr(13, 2);
	}
}

function FormataMoeda(campo, teclapres)
{
   var i,exceptions=[8,46,37,39,13,9];   // backspace, delete, arrowleft & right, enter, tab
   var isException=false;
   var isDot=(188==teclapres.keyCode);       // dot
   var k=String.fromCharCode(teclapres.keyCode);
   var valor = campo.value;

   for(i=0;i<exceptions.length;i++)
      if(exceptions[i]==teclapres.keyCode)
	     isException=true;

   if(isNaN(k) && (!isException) && (!isDot))
	   teclapres.returnValue=false;
   else{
      var p=new String(valor+k).indexOf(",");
      if((p<(valor.length-2) || isDot) && (p>-1) && (!isException))
    	 teclapres.returnValue=false;
      else if((valor.length)>=15 && (!isException))
    	 teclapres.returnValue=false;
   }
}

function FormataInteiro(campo, teclapres)
{
   var i,exceptions=[8,46,37,39,13,9];   // backspace, delete, arrowleft & right, enter, tab
   var isException=false;
   var k=String.fromCharCode(teclapres.keyCode);
   var valor = campo.value;

   for(i=0;i<exceptions.length;i++)
      if(exceptions[i]==teclapres.keyCode)
	     isException=true;

   if(isNaN(k) && (!isException))
	   teclapres.returnValue=false;
}

function VerificaDia(dia, mes, ano)
{
	dia = dia*1;
		
	if ((dia < 1) || (dia == NaN))
	{
		dia = 1;
	}
	
	if (dia > 31)
	{
		dia = 31;
	}
	
	if (mes != '')
	{
		mes = mes*1;
		if ((dia > 30) && ((mes == 4) || (mes == 6) || (mes == 9) || (mes == 11)))
		{
			dia = 30;
		}	
		if ((dia > 29) && (mes == 2))
		{
			dia = 29;
		}
		
		if ((mes == 2) && (ano != ''))
		{
			if ((ano % 4 == 0) && ((ano % 100 != 0) || (ano % 400 == 0))) 
			{
				if (dia > 29)
				{
					dia = 29;
				}	
			}
			else
			{
				if (dia > 28)
				{
					dia = 28;
				}	
			}	
		}

	}	

	if (dia < 10)
	{
		dia = '0'+dia;
	}
	return dia;
}

function VerificaMes(mes)
{
	mes = mes*1;
	
	if ((mes < 1) || (mes == NaN))
	{
		mes = 1;
	}
	if (mes > 12)
	{
		mes = 12;
	}
	if (mes < 10)
	{
		mes = '0'+mes;
	}
	
	return mes;
}

function VerificaAno(ano)
{
	ano = ano*1;
	
	if ((ano < 1) || (ano == NaN))
	{
		ano = 2000;
	}
	if (ano < 100)
	{
		ano = 2000+ano;
	}
	if (ano < 1000)
	{
		ano = 1000+ano;
	}

	return ano;
}


function FormataData(campo, teclapres) 
{
	var tecla = teclapres.keyCode;
	var vr = new String(campo.value);
	var dia = '';
	var mes = '';
	var ano = '';
	vr = vr.replace("/", "");
	vr = vr.replace("/", "");
	tam = vr.length + 1;
	if (tecla != 14)
	{
		if (tam == 3)
		{
			dia = VerificaDia(vr.substr(0, 2), mes, ano);
			campo.value = dia + '/';
		}	
		if (tam == 5)
		{	
			mes = VerificaMes(vr.substr(2, 2));
			dia = VerificaDia(vr.substr(0, 2), mes, ano);
			campo.value = dia + '/' + mes + '/';
		}
		if (tam == 9)
		{
			ano = VerificaAno(vr.substr(5, 4));
			mes = VerificaMes(vr.substr(2, 2));
			dia = VerificaDia(vr.substr(0, 2), mes, ano);
			campo.value = dia + '/' + mes + '/' + ano;
		}
	}
}