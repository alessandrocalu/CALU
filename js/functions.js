var tempX = 0; 
var tempY = 0; 
if(!document.getElementById) {
	document.captureEvents(Event.CLICK); 
}
document.onmousemove = getMouseXY; 

function getMouseXY(e) {
	if (!document.all) event = e; 
	tempX = (event.clientX+self.document.body.scrollLeft); 
	tempY = (event.clientY+self.document.body.scrollTop); 
}

function showWindow(urlWindow,nome, width, height, top, left) {
     return window.open(urlWindow,"filha","height="+height+",width="+width+",top="+top+",left="+left+",scrollbars=yes,resizable=yes");
}

function modalWin(urlModalWindow, nome, width, height) {
  if (window.showModalDialog) {
    return window.showModalDialog(urlModalWindow,nome,"dialogWidth:"+width+"px;dialogHeight:"+height+"px;");
  }
  else {
    return window.open(urlModalWindow,nome,'height='+height+',width='+width+',toolbar=no,directories=no,status=no, menubar=no,scrollbars=yes,resizable=no ,modal=yes');
  }
}

function local(url) {
  window.location=url;  
}

function showModalWindow(urlModalWindow, width, height)
{
  window.showModalDialog(urlModalWindow,"","dialogWidth="+width+
    "pt;dialogHeight="+height+
    "pt; center=yes;help=no;status=no;unadorned=yes;");
}

function openModalWindow(button, urlModalWindow, urlTargetWindow, width, height)
{
    showModalWindow(urlJanelaModal, width, height);
	// URL da pagina que deve ser carregada depois que a janela modal é fechada
	button.href = urlTargetWindow;	
}

function searchValue(arrayOfValues, value)
{
	var found = false;
	for(i = 0; i <= arrayOfValues.length; i++)
		if (arrayOfValues[i] == value)
			found = true;
	return found;
}

function setDisplay(component,visible)
{
	if (visible == true)
	{		
		document.getElementById(component).style.display = ""; // exibe objeto
	}
	else
	{
		document.getElementById(component).style.display = "none"; // esconde objeto
	}
}

function setDisplayBlock(component,visible)
{
	if (visible == true)
	{		
		document.getElementById(component).style.display = "block"; // exibe objeto
	}
	else
	{
		document.getElementById(component).style.display = "none"; // esconde objeto
	}
}

function setDisplayControl(component, visible, control, imageExpand, imageCollapse)
{
	setDisplay(component,visible);
	if (visible)
	{
		document.getElementById(control).src = imageCollapse;
	}
	else
	{
		document.getElementById(control).src = imageExpand;
	}
}

function setDisplayControlList(list_component, visible, control, imageExpand, imageCollapse)
{
	for (var i = 0; i < list_component.length ;i++)
	{
		setDisplay(list_component[i],visible);
	}
	if (visible)
	{
		document.getElementById(control).src = imageCollapse;
	}
	else
	{
		document.getElementById(control).src = imageExpand;
	}
}

function toggleComponents(component1, component2)
{
	if (document.getElementById(component1).style.display == "none")
	{		
		document.getElementById(component1).style.display = ""; // exibe objeto
		document.getElementById(component2).style.display = "none"; // esconde objeto
	}
	else
	{
		document.getElementById(component1).style.display = "none"; // esconde objeto
		document.getElementById(component2).style.display = ""; // exibe objeto
	}
}