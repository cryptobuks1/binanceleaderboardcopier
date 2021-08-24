function openWinCentered(url,w,h,sFTRS) {
	x = (document.all) ? 'left=' : 'screenX=';
	y = (document.all) ? 'top=' : 'screenY=';
	open(url,'popwin','width='+w+',height='+h+','+(x+(screen.width-w)/2)+','+(y+(screen.height-h)/2) + '' + sFTRS);
}			

function MM_jumpMenu(targ,selObj) 
{ 
	newsel = selObj.options[selObj.selectedIndex].value;

	if (newsel)
	{
		if(newsel=='currencycalculator') {
			openWin('/sections/seller/currencyConverter.asp','CALC','menubar=no,scrollbars=yes,resizable=yes,width=400,height=300');
		} 
		else if(newsel=='documentviewers') {
			openWin('/app/docmgmt/documentviewers.asp','DOC','menubar=no,scrollbars=yes,resizable=yes,width=600,height=600');
		} 
		else if(newsel=='browsertest') {
			openWin('/browsercheck2.asp','BROW','menubar=no,scrollbars=yes,resizable=yes,width=750,height=600');
		}
		else if(newsel=='boq_manuals') {
			openWin('/help/boq_manuals.asp','BOQ','menubar=no,scrollbars=yes,resizable=yes,width=600,height=600');
		}
		else if(newsel=='help_docs') {
			openWin('/help/help_documents.asp','BOQ','menubar=no,scrollbars=yes,resizable=yes,width=600,height=600');
		}
		else if(newsel=='helpTerminology') {
			openWin('/help/helpTerminology.asp','Term','menubar=no,scrollbars=yes,resizable=yes,width=600,height=600');
		}
		else if(newsel=='text_admin') {
			openWin('/app/adm/textmode.asp?cmd=toggle','text_admin','menubar=no,scrollbars=no,resizable=no,width=500,height=200');
		}
		else if(newsel=='help_rftprocess') {
			openWin('/help/no/08 10 23 Konkurransegjennomf%F8ring.htm','help_rftprocess','menubar=yes,scrollbars=yes,resizable=yes,width=800,height=600');
		}
		else {
			if(newsel.substring(0,4)=='http') 
				window.open(newsel);
			else
				eval(targ+"='"+ newsel +"'");
		}
	}
}



function openWin(sURL,sNAME,sFTRS) {
  window.open(sURL,sNAME,sFTRS);
}

function openWinAndRedirect(sURLOpen,sURLRedirect,sNAME,sFTRS) {
  window.open(sURLOpen,sNAME,sFTRS);
  window.location.href = sURLRedirect;
}

function redirectAndOpenWin(sURLOpen, sURLRedirect, sNAME, sFTRS) {
    window.location.href = sURLRedirect;
    window.open(sURLOpen, sNAME, sFTRS);
}

function popconfirm(mess) 
{
	retVal = confirm(mess);
	return retVal;
}	

function getCookie(name) {
  var dc = document.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf(prefix);
  if (begin == -1)
    return "";
  var end = document.cookie.indexOf(";", begin);
  if (end == -1)
    end = dc.length;
  return unescape(dc.substring(begin + prefix.length, end));
}



function getCssName() {

if (document.all) {
  //IE
  return "pcie.css";
}
else if (document.layers) {
  //Netscape
  return "pcns.css";
}
else if (document.getElementById) {
  // Netscape 6
  return "pcie.css";
}
else {
  return "pcie.css";
}

}

function writeCssPath() {
    if (getCookie("EUSCUSTOMCSS") == 1) {
        document.write('<link rel="STYLESHEET" type="text/css" href="' + 'css/' + getCookie("EUSBRAND") + getCssName() + '"/>');
    }
    else {
        document.write('<link rel="STYLESHEET" type="text/css"  href="' + 'css/' + getCssName() + '"/>');        
    }
    document.write('<link rel="STYLESHEET" type="text/css" href="css/buttons.css"/>');
}

function writeCssPathMigrated() {
    //Is this needed?
    document.write('<link rel="STYLESHEET" type="text/css" href="/css/buttons.css"/>');
}


function writeLogoImg() {
	if (getCookie("EUSASC") == 0) 
		if (getCookie("EUSCUSTOMCSS") == 1)
			document.write('<img src="' + '/css' + getCookie("EUSBRAND") + '/t_logo.gif" border="0" />');
		else
			document.write('<img src="' + '/css/t_logo.gif" border="0" />');
	else
		if (getCookie("EUSCUSTOMCSS") == 1)
			document.write('<img src="' + '/css' + getCookie("EUSBRAND") + '/t_logo.gif" border="0" /><img src="/img/power.gif" border="0"/>');
		else
			document.write('<img src="' + '/css/t_logo.gif" border="0" /><img src="/img/power.gif" border="0"/>');
}

var isC=false;		
function gotoURL(sURL)
{
	if(!isC){isC=true;setTimeout('parent.location=\'' + sURL + '\'',700)}
}

function browserType()
{
	var browser = "";
	if(document.layers) {browser = "nn4";}
	if(document.all) {browser = "ie";}
	
	return browser;
}

function addWindowOnLoadFunction(initFunction) {
    if (window.addEventListener) // W3C standard
    {
        window.addEventListener('load', initFunction, false); // NB **not** 'onload'
    }
    else if (window.attachEvent) // Microsoft
    {
        window.attachEvent('onload', initFunction);
    }
}

function loadXMLString(txt) {
	var parser, xmlDoc;
	if (txt == "") {
		txt = "<empty></empty>";
	}
    if (window.DOMParser) {
        parser = new DOMParser();
        xmlDoc = parser.parseFromString(txt, "text/xml");
    }
    else // Internet Explorer
    {
        xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
        xmlDoc.async = false;
        xmlDoc.loadXML(txt);
    }
    return xmlDoc;
}