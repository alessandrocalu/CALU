// Verificar qual navegador
function QualNavegador() 
{
	var s = navigator.appName;
	if ( s == "Microsoft Internet Explorer" )
		return "IE";
	else if ( s == "Netscape" )
		return "NE";
	else
		return "";
}

// Verificar qual a vers? do navegador
function QualVersao()
{
	var s = navigator.appVersion;
	if ( QualNavegador() == "IE" )
	{
		var i = s.search("MSIE");
		s=s.substring(i+5);
		i=s.search(".");
		return parseInt(s.substring(0,i+1));
	}
	else if ( QualNavegador() == "NE" )
		return parseInt(s.substring(0,1));
	else
		return 0;
}

//	cria_elemento ("pai", "elemento", "name", "valores", "innerHTML");
/*
func?o que faz a cria?o din?ica de um componente html
*/
function cria_elemento (pai, elemento, name, valores, inner)
{
	var nome = (name != "")? " name = '"+name + "' id = '"+name+"'" : "";

	if(valores.search("'radio'") != -1)
		elemento = "radio";
/*
if(elemento == "radio")
	alert(QualNavegador()+" "+elemento+" "+name);
*/

	if(QualNavegador() == "IE"){
		switch(elemento){
			case "radio":
				var aux = document.createElement("<input name = '"+name+"' type = 'radio' id = '"+inner+"' "+valores+">");
			break;
			default:
				var aux = document.createElement("<"+elemento+" "+nome+" "+valores+">");
		}
	}
	else{
		switch(elemento){
			case "tr":
				var aux = document.createElement(elemento);
				aux.id = name;
				aux.name = name;
			break;
			case "option":
				var aux = document.createElement(elemento);
				aux.id = name;

				if(valores.search("selected") != -1){
					aux.selected = true;
				}

				if(valores.search("value") != -1){
					valores = valores.substr(valores.search("value"));
					valores = valores.substr(valores.search("="));
					valores = valores.substr(valores.search("'")+1);
					valores = valores.substring(0, valores.search("'"));
				}
				aux.value = valores;
			break;
			case "radio":
				pai.innerHTML += "<input name = '"+name+"' type = 'radio' id = '"+inner+"' "+valores+">";
				var aux = document.getElementById(inner);
			break;
			default:
				pai.innerHTML += "<"+elemento+" "+nome+" "+valores+">";
				var aux = document.getElementById(name);
		}
	}

	if(inner != "" && elemento != "radio"){
		aux.innerHTML += inner;
	}

	pai.appendChild(aux);

	return aux;
}

/**
 * @desc fun?o de remover um nodo 
 */
function remove_elemento(nom_obj, pergunta)
{
	this.ret = true;

	var tr = document.getElementById(nom_obj);

	if (pergunta != "" && tr)
	{
		if (confirm(pergunta))
		{
			tr.parentNode.removeChild(tr);
		}
		else
		{
			this.ret = false; 
		}
	}
	else
	{
		tr.parentNode.removeChild(tr);
	}
	return this.ret;
}
