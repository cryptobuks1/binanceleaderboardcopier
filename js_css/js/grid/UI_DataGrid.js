var displayOverlay = false;


if (!Array.prototype.indexOf)
{
	Array.prototype.indexOf = function(obj, start) {
		for (var i = (start || 0), j = this.length; i < j; i++) {
			if (this[i] === obj) { return i; }
		}
		return -1;
	}
}

/*function getXMLHttpRequest() 
{
	var xmlhttp = false;
	if (window.XMLHttpRequest) { 
		//xmlhttp = new XMLHttpRequest();
		//xmlhttp = new window.XMLHttpRequest;
		try { 
			xmlhttp = new XMLHttpRequest();
		} catch (e) { 
			xmlhttp = new window.ActiveXObject("Microsoft.XMLHTTP");
		}
	} else if(window.ActiveXObject) { 
		try {
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				xmlhttp = false;
			}
	   }
	}
	
	return xmlhttp;

}*/

function getXMLHttpRequest()
{   	
	var xmlhttp = false;
	
	if(window.ActiveXObject) { 
		try {
			var versionIds = ["Msxml2.XMLHTTP.6.0","Msxml2.XMLHTTP.5.0",
							"Msxml2.XMLHTTP.4.0","Msxml2.XMLHTTP.3.0", 
							"Msxml2.XMLHTTP.2.6","Microsoft.XMLHTTP.1.0", 
							"Microsoft.XMLHTTP.1","Microsoft.XMLHTTP"];
			for(var i=0; i<versionIds.length && xmlhttp == false; i++) 
			{
			  xmlhttp = CreateXmlHttp(versionIds[i]);
			}
		} catch(e) {}
	}
	else if (window.XMLHttpRequest) { 
		try { 
			xmlhttp = new XMLHttpRequest();
		} catch (e) { 
			xmlhttp = new window.ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
  return xmlhttp;
}
 
function CreateXmlHttp(id) 
{   /*Creates an ActiveX object used by Internet Explorer that will make Ajax calls*/
    var xmlHttp = false;
    try 
    {
      xmlHttp = new ActiveXObject(id);
    }catch(e) {}
    return xmlHttp;
}

function ReloadGrid(filepath,formname,transid,gridid,index,dftorderby,action,orderby,morelines,strt,perpage,totalrows,direction,callback){


	if (action=='F') {
		strt = 0;
	}	
	else if (action=='L')
	{
		var result=totalrows % perpage;
		
		if (result==0)
			strt=totalrows - perpage;
		else	
			strt=totalrows - result;
		
	}

	//$(document).ready(function() {
		//$(function() {
			$("#Loader").addClass("ajaxloading");
	//	});
	//});

	//var currec = document.getElementById('SelectedRec').value;
	//var curreccolor = document.getElementById('SelectedRecColor').value;

	
	var xmlhttp1 = getXMLHttpRequest();
	
	xmlhttp1.onreadystatechange=function()
	{
		if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
		{
			{ 
				document.getElementById(gridid).innerHTML=xmlhttp1.responseText;
				
				/*if (action == 'ADD') {
					document.getElementById('SelectedRec').value = currec;
					document.getElementById('SelectedRecColor').value = curreccolor;
				}*/
				
				if (typeof(callback) == 'function') {
					callback();
				}	
			}
		}
	}


	xmlhttp1.open("GET",filepath+"?transid="+transid+"&form="+formname+"&dftorderby="+dftorderby+"&action="+action+"&orderby="+orderby+"&morelines="+morelines+"&startrange="+strt+"&perpage="+perpage+"&totalrows="+totalrows+"&direction="+direction,true);
	xmlhttp1.send();

}

function ReloadGridDt(filepath,formname,action,gridid,transid,transdtid,callback){
	//$(document).ready(function() {
		//$(function() {
			$("#Loader").addClass("ajaxloading");
	//	});
	//});

	var xmlhttp2 = getXMLHttpRequest();
	
	xmlhttp2.onreadystatechange=function()
	{
		if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
		{
			{ 
				document.getElementById(gridid).innerHTML=xmlhttp2.responseText;
				$("#Loader").removeClass("ajaxloading");
				
				if (typeof(callback) == 'function') {
					callback();
				}	
			}
		}
	}


	xmlhttp2.open("GET",filepath+"?transid="+transid+"&transdtid="+transdtid+"&form="+formname+"&action="+action,true);
	xmlhttp2.send();

}

function MoveNextRecord(nextcell) {
	if (document.getElementById(nextcell) ) { 
		document.getElementById(nextcell).focus();
													
		if (document.getElementById(nextcell).type != 'select-one') 
			document.getElementById(nextcell).select();
	}
}

function ClearRecordGrid(objects,index,modreccol){

	var vClrMsg   = 'Are you sure you want to clear this record?';
	
	if (confirm ( vClrMsg ))
	{
		var columnsarray=new Array();
		columnsarray = objects.split(",");
		
		for (var i=0; i<columnsarray.length; i++) {
			var obj =  columnsarray[i] + '[' + index + ']';
			if (document.getElementById(obj)) {
				document.getElementById(obj).value = '';
			}
		}
		
		if (document.getElementById(modreccol)) {
			document.getElementById(modreccol).value = '';
		}
	}
}

function AutoSaveRecordGrid(PCurrRec,PPrevRec,PModRec,PBtnSubmit) {

	vPrevRec = document.getElementById(PPrevRec).value;
	vCurrRec = PCurrRec;
	vModRec  = document.getElementById(PModRec).value;

	if (vPrevRec != "" && vPrevRec != vCurrRec && vModRec != ""){
		window.setTimeout(function() {
			
			SubBtnID = PBtnSubmit+'['+vModRec+']';
			if (document.getElementById(SubBtnID))
				document.getElementById(SubBtnID).click();
		}, 500 );
	}
}


function showList(Link,RowIndx,Width,Height){
	var XPos = (screen.availHeight - Height) / 2;
	var YPos = (screen.availWidth - Width) / 2;
	
	
	Link = Link + '&' + RowIndx;
	win = window.open(Link, 'Search', 'toolbar=no,location=no,status=yes,menubar=no,scrollbars=yes,resizable=no,width='+Width+',height='+Height+',left='+YPos+',top='+XPos);		
	win.creator = self;
	win.focus();	
}
	
	
function selRecordColor(Obj,RecName,RecColor){

	if (document.getElementById(RecName).value=='')
	{
		document.getElementById(RecName).value  = Obj.id;
		document.getElementById(RecColor).value = Obj.style.backgroundColor;
	} else {
		var PrevObj = document.getElementById(RecName).value;
		document.getElementById(PrevObj).style.backgroundColor = document.getElementById(RecColor).value;

		document.getElementById(RecName).value  = Obj.id;
		document.getElementById(RecColor).value = Obj.style.backgroundColor;
	}	
	
	Obj.style.backgroundColor='#B8EDFF';
	
}

function dokey(e,obj,rowindx,columnslist,submitid,preventnext){

	//if (e.ctrlKey == false)
	//return;
	var vindx = rowindx;
	
	if (!e) { var e = window.Events;}
    var intKey = (window.Events) ? e.which : e.keyCode;
	
	
	var columnsarray=new Array();
	columnsarray = columnslist.split(",");
	var firstcell = columnsarray[0];
	var lastcell  = columnsarray[columnsarray.length-1];
	//var arrayname = obj.id.split("[");
	
	var objname = obj.id;
	objname = objname.substring(0,objname.lastIndexOf("["));
	

	if (( ((intKey == 37) || (intKey == 39)) && e.ctrlKey )  ||  (intKey == 13))
	{
		var cindx = columnsarray.indexOf(objname);
		
		if (intKey == 37) { //left key
			var nextindx = Number(cindx) - 1;
		}
		if (intKey == 13 || intKey == 39) { //right key
			var nextindx = Number(cindx) + 1;
		}

			
		var nextitem = columnsarray[nextindx] + '[' + rowindx + ']' ;
			
		if (document.getElementById(nextitem)) { 
			var nextobj = document.getElementById(nextitem);
			
			
			
				nextobj.focus();
				
			if (nextobj.type != 'select-one') nextobj.select();
		}
		else {
			var nextrow  = rowindx + 1;
			var nextcell = firstcell + '[' +  nextrow + ']';
				
			if (document.getElementById(submitid))
				document.getElementById(submitid).click();
		
			MoveNextRecord(nextcell);
						
		}			
	}
	else if ( ((intKey == 38) || (intKey == 40)) && e.ctrlKey) {
		
		if (intKey == 38) 
			rowindx = rowindx - 1;
		if (intKey == 40) 
			rowindx = rowindx + 1;
			
		var nextitem =  objname + '[' + rowindx + ']';
		if (document.getElementById(nextitem)) {
			var nextobj = document.getElementById(nextitem);
			nextobj.focus();
			
			if (nextobj.type != 'select-one') nextobj.select();
		}	
	}

}

function dokey1(e,obj,rowindx,columnslist,submitid){

	//if (e.ctrlKey == false)
	//return;
	var vindx = rowindx;
	
	if (!e) { var e = window.Events;}
    var intKey = (window.Events) ? e.which : e.keyCode;
	
	
	var columnsarray=new Array();
	columnsarray = columnslist.split(",");
	var firstcell = columnsarray[0];
	var lastcell  = columnsarray[columnsarray.length-1];
	//var arrayname = obj.id.split("[");
	
	var objname = obj.id;
	objname = objname.substring(0,objname.lastIndexOf("["));
	

	if (( ((intKey == 37) || (intKey == 39)) && e.ctrlKey )  ||  (intKey == 13))
	{
		var cindx = columnsarray.indexOf(objname);
		
		if (intKey == 37) { //left key
			var nextindx = Number(cindx) - 1;
		}
		if (intKey == 13 || intKey == 39) { //right key
			var nextindx = Number(cindx) + 1;
		}

			
		var nextitem = columnsarray[nextindx] + '[' + rowindx + ']' ;
			
		if (document.getElementById(nextitem)) { 
			var nextobj = document.getElementById(nextitem);
			
			
			
			//	nextobj.focus();
				
			if (nextobj.type != 'select-one') nextobj.select();
		}
		else {
			var nextrow  = rowindx + 1;
			var nextcell = firstcell + '[' +  nextrow + ']';
				
			if (document.getElementById(submitid))
				document.getElementById(submitid).click();
		
			MoveNextRecord(nextcell);
						
		}			
	}
	else if ( ((intKey == 38) || (intKey == 40)) && e.ctrlKey) {
		
		if (intKey == 38) 
			rowindx = rowindx - 1;
		if (intKey == 40) 
			rowindx = rowindx + 1;
			
		var nextitem =  objname + '[' + rowindx + ']';
		if (document.getElementById(nextitem)) {
			var nextobj = document.getElementById(nextitem);
			nextobj.focus();
			
			if (nextobj.type != 'select-one') nextobj.select();
		}	
	}

}


function LeaveGrid(CurrField,NextField,TabName,TabIndex){

if(document.getElementById(CurrField).value!='')
return;	
			if(document.getElementById(NextField)) {
			
				if(TabName!='')
					document.getElementById(TabName).tabber.tabShow(TabIndex);//opn the tab
				
				document.getElementById(NextField).focus();

				}
}

function LeaveToMainGrid(CurrField,NextField,RecIndex,TabIndex){

if(document.getElementById(CurrField).value!='')
return;	

			if(NextField!='') {
			
				
		var VRecIndex_G1 = document.getElementById(RecIndex).value;
		
			VRecIndex_G1 = parseInt(VRecIndex_G1) + 1;
		
		var str = NextField+'['+ VRecIndex_G1 + ']';
		
			if(document.getElementById(str))
				document.getElementById(str).focus();
return;	
	}
}


function AutoCompleteData(e,tagid,idholder,nameholder,tablename) {

	var input = document.getElementById(tagid);
	var idholder = document.getElementById(idholder);
	var nameholder = document.getElementById(nameholder);
	
	if (!e) { var e = window.Events;}
    var intKey = (window.Events) ? e.which : e.keyCode;
	//var intKey = (window.Events) ? event.which : event.keyCode;
	if ((intKey == 38) || (intKey == 40) || (intKey == 37) || (intKey == 39) || (intKey == 17) || (intKey == 16)) return false;
		
	/*$(input).attr("autocomplete", "off");
	$(input).autocomplete("Get_AutocompleteQuery.php?tablename="+tablename, {
		width: 200,
		selectFirst: false,		 
		autoFill: false,  			 
		mustMatch:false,  	 
		max: 120 ,
		formatItem: function(data, i, n, value) { 
			return  value.split("||")[0];
		} 
	});

		
	$(input).result(function(event, data, formatted) {  
		if(idholder)   $(idholder).val(data[1]);
		if(nameholder) $(nameholder).val(data[2]);
			
		//fireTrigger(idholder,'change');
		$(idholder).trigger("change");
	});*/
	
	//input.setAttribute("autocomplete","off");
	
	$(input).autocomplete({
                source: "Get_AutocompleteQuery.php?tablename="+tablename,
                minLength: 1,
				selectFirst: false, 
                select: function(e, ui) { 
                   if (ui.item){
						if(idholder)   $(idholder).val(ui.item.id);
						if(nameholder) $(nameholder).val(ui.item.name);
						
						$(idholder).trigger("change");
					}	
					
                },
				change: function(e,ui) { 
					if (ui.item){
						if(idholder)   $(idholder).val(ui.item.id);
						if(nameholder) $(nameholder).val(ui.item.name);
					}
                },
				focus: function(e,ui) {
					if (ui.item){
						if(idholder)   $(idholder).val(ui.item.id);
						if(nameholder) $(nameholder).val(ui.item.name);
						
						$(idholder).trigger("change");
					}
                }
            });


}	

function GetSelectedItem(tagid,tablename,index,pdate,pcurrency,pclient) {
	var pitem = document.getElementById(tagid).value;
	pitem = encodeURIComponent(pitem);
	
	var xmlhttp = getXMLHttpRequest();
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			{ 
				var data = $.parseJSON(xmlhttp.responseText);
				
				if (pclient == -1) {
					if (document.getElementById('price' + '[' + index + ']')) document.getElementById('price' + '[' + index + ']').value = data[0].i_lpurp;
				}	
				else if (pclient == -2) { 
					if (document.getElementById('costprice' + '[' + index + ']')) document.getElementById('costprice' + '[' + index + ']').value = data[0].i_cost;	
				}
				else if (pclient == -3) { 
					if (document.getElementById('costpricefg' + '[' + index + ']')) document.getElementById('costpricefg' + '[' + index + ']').value = data[0].i_cost;	
				}
				else if (pclient == -4) { 
					if (document.getElementById('costpricerm' + '[' + index + ']')) document.getElementById('costpricerm' + '[' + index + ']').value = data[0].i_cost;	
				}				
				else {
					if (document.getElementById('price' + '[' + index + ']')) document.getElementById('price' + '[' + index + ']').value = data[0].i_sp;
					if (document.getElementById('costprice' + '[' + index + ']')) document.getElementById('costprice' + '[' + index + ']').value = data[0].i_cost;	
					if (document.getElementById('sellprice' + '[' + index + ']')) document.getElementById('sellprice' + '[' + index + ']').value = data[0].i_sp;	
				}	
					
				if (document.getElementById('lastprice' + '[' + index + ']')) document.getElementById('lastprice' + '[' + index + ']').value = data[0].i_lsp;
				if (document.getElementById('sordtdiscount' + '[' + index + ']')) document.getElementById('sordtdiscount' + '[' + index + ']').value = data[0].c_discper;
                if (document.getElementById('itemunit1' + '[' + index + ']')) document.getElementById('itemunit1' + '[' + index + ']').value = data[0].i_unit1;
				if (document.getElementById('itemunit2' + '[' + index + ']')) document.getElementById('itemunit2' + '[' + index + ']').value = data[0].i_unit2;
				if (document.getElementById('itemvat' + '[' + index + ']')) document.getElementById('itemvat' + '[' + index + ']').value = data[0].i_vat;
				if (document.getElementById('itemminprice' + '[' + index + ']')) document.getElementById('itemminprice' + '[' + index + ']').value = data[0].i_minprice;
				if (document.getElementById('itemnonstock' + '[' + index + ']')) document.getElementById('itemnonstock' + '[' + index + ']').value = data[0].i_nonstock;
				if (document.getElementById('itemzeroprice' + '[' + index + ']')) document.getElementById('itemzeroprice' + '[' + index + ']').value = data[0].i_zeroprice;
				if (document.getElementById('itemunitcoef' + '[' + index + ']')) document.getElementById('itemunitcoef' + '[' + index + ']').value = data[0].i_unitcoef;
				if (document.getElementById('itemdescription' + '[' + index + ']')) document.getElementById('itemdescription' + '[' + index + ']').value = data[0].i_itemname;
				
				
				if (pclient == -3) { 
					if (document.getElementById('itemexpirefg' + '[' + index + ']')) document.getElementById('itemexpirefg' + '[' + index + ']').value = data[0].i_itemexpire;
					if (document.getElementById('itemwithserialfg' + '[' + index + ']')) document.getElementById('itemwithserialfg' + '[' + index + ']').value = data[0].i_itemwithserial;
				
					var ProjObj = document.getElementById('projectcodedtfg' + '[' + index + ']');
					var CostCentObj = document.getElementById('costcentcodedtfg' + '[' + index + ']');
				}
				else if (pclient == -4) {				
					if (document.getElementById('itemexpirerm' + '[' + index + ']')) document.getElementById('itemexpirerm' + '[' + index + ']').value = data[0].i_itemexpire;
					if (document.getElementById('itemwithserialrm' + '[' + index + ']')) document.getElementById('itemwithserialrm' + '[' + index + ']').value = data[0].i_itemwithserial;
				
					var ProjObj = document.getElementById('projectcodedtrm' + '[' + index + ']');
					var CostCentObj = document.getElementById('costcentcodedtrm' + '[' + index + ']');
				}
				else {
					if (document.getElementById('itemexpire' + '[' + index + ']')) document.getElementById('itemexpire' + '[' + index + ']').value = data[0].i_itemexpire;
					if (document.getElementById('itemwithserial' + '[' + index + ']')) document.getElementById('itemwithserial' + '[' + index + ']').value = data[0].i_itemwithserial;
				
					var ProjObj = document.getElementById('projectcodedt' + '[' + index + ']');
					var CostCentObj = document.getElementById('costcentcodedt' + '[' + index + ']');
				}
					
				//if (ProjObj && ProjObj.type == 'text') {
				if (ProjObj) {
					if (data[0].i_project) {
						ProjObj.value = data[0].i_project;
					}
					else if (document.getElementById('txtProjectCode')) {
						ProjObj.value = document.getElementById('txtProjectCode').value;
					}	
				}	
				
				
				//if (CostCentObj && CostCentObj.type == 'text') {
				if (CostCentObj) {
					if (data[0].i_costcent) {
						CostCentObj.value = data[0].i_costcent;
					}
					else if (document.getElementById('txtCostCentCode')) {
						CostCentObj.value = document.getElementById('txtCostCentCode').value;
					}	
				}	
		
				
				
			}
		}
	}


	xmlhttp.open("GET","Get_AutocompleteQuery.php?tablename="+tablename+"&pitem="+pitem+"&pdate="+pdate+"&pcurrency="+pcurrency+"&pclient="+pclient,true);
	xmlhttp.send();
	

}

function GetItemProjCostCent(tagid, pvalue, pindex) {
	pvalue = encodeURIComponent(pvalue);
	
	var xmlhttp = getXMLHttpRequest();
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var data = $.parseJSON(xmlhttp.responseText);
					
			if (document.getElementById('txtProjectCode')) { 
				if (data[0].i_project) {
					document.getElementById('txtProjectCode').value = data[0].i_project;
				}
			}

			if (document.getElementById('txtCostCentCode')) { 
				if (data[0].i_costcent) {
					document.getElementById('txtCostCentCode').value = data[0].i_costcent;
				}
			}			
			
		}
	}


	xmlhttp.open("GET","Get_AutocompleteQuery.php?tablename=getprojcostcent&pitem="+pvalue,true);
	xmlhttp.send();
	

}

function GetSelectedItemQty(tablename,index,pdate) {
		
	var pitem = document.getElementById('itemcode' + '[' + index + ']').value;
	var pwarehouse = document.getElementById('warehousecode' + '[' + index + ']').value;

	pitem = encodeURIComponent(pitem);
	
	if (pitem != "") {
	
		var xmlhttp = getXMLHttpRequest();
		
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				{ 
					var data = $.parseJSON(xmlhttp.responseText);
					
					if (document.getElementById('existingqty' + '[' + index + ']'))  document.getElementById('existingqty' + '[' + index + ']').value = data[0].i_existqty;
					if (document.getElementById('bookingqty' + '[' + index + ']'))   document.getElementById('bookingqty' + '[' + index + ']').value = data[0].i_bookqty;
					if (document.getElementById('availableqty' + '[' + index + ']')) document.getElementById('availableqty' + '[' + index + ']').value = data[0].i_availqty;
					if (document.getElementById('exitunit2qty' + '[' + index + ']')) document.getElementById('exitunit2qty' + '[' + index + ']').value = data[0].i_existunit2qty;
				}
			}
		}


		xmlhttp.open("GET","Get_AutocompleteQuery.php?tablename="+tablename+"&pitem="+pitem+"&pdate="+pdate+"&pwarehouse="+pwarehouse,true);
		xmlhttp.send();
	}

}

function GetSelectedPurItem(tablename,index,pdate,pcurrency) {
		
	var pitem = document.getElementById('itemcode' + '[' + index + ']').value;
	pitem = encodeURIComponent(pitem);
	
	if (pitem != "") {
	
		var xmlhttp = getXMLHttpRequest();
		
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				{ 
					var data = $.parseJSON(xmlhttp.responseText);
					
					if (document.getElementById('lastpurprice' + '[' + index + ']'))  document.getElementById('lastpurprice' + '[' + index + ']').value = data[0].i_lastprice;
					if (document.getElementById('lastdiscount' + '[' + index + ']'))  document.getElementById('lastdiscount' + '[' + index + ']').value = data[0].i_lastdisc;
					if (document.getElementById('lastdate' + '[' + index + ']'))      document.getElementById('lastdate' + '[' + index + ']').value = data[0].i_lastdate;
				}
			}
		}


		xmlhttp.open("GET","Get_AutocompleteQuery.php?tablename="+tablename+"&pitem="+pitem+"&pdate="+pdate+"&pcurrency="+pcurrency,true);
		xmlhttp.send();
	}

}


function GetItemCost(tagid,targetid,tablename,index,pdate,pcurrency) {
	var pitem = document.getElementById(tagid).value;
	pitem = encodeURIComponent(pitem);
	
	//var costprice = document.getElementById('costprice' + '[' + index + ']');
	var costprice = document.getElementById(targetid);
	var xmlhttp = getXMLHttpRequest();
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var data = $.parseJSON(xmlhttp.responseText);
				
			if (costprice) {
				//if (costprice.value == 0 || costprice.value == '')
					costprice.value = data[0].i_cost;	
			}	
			
		}
	}


	xmlhttp.open("GET","Get_AutocompleteQuery.php?tablename="+tablename+"&pitem="+pitem+"&pdate="+pdate+"&pcurrency="+pcurrency,true);
	xmlhttp.send();
	

}

function ShowItemQtyByWarehouse(tablename,divtag,pitem,pwarehouse,pdate) {
	pitem = encodeURIComponent(pitem);
	
	if (pitem != "") {
	
		var xmlhttp = getXMLHttpRequest();
		
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				{ 
					//var data = $.parseJSON(xmlhttp.responseText);
					
					document.getElementById(divtag).innerHTML=xmlhttp.responseText;
					$('#'+divtag).slideToggle();
				}
			}
		}


		xmlhttp.open("GET","Get_AutocompleteQuery.php?tablename="+tablename+"&pitem="+pitem+"&pdate="+pdate+"&pwarehouse="+pwarehouse,true);
		xmlhttp.send();
	}

}

function GetLedgerBalance(tablename,tagid,targetid,index,pdate) {
		
	var pledger = document.getElementById(tagid + '[' + index + ']').value;
	
	if (pledger != "") {
	
		var xmlhttp = getXMLHttpRequest();
		
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				{ 
					var data = $.parseJSON(xmlhttp.responseText);
					
					if (document.getElementById(targetid + '[' + index + ']'))  document.getElementById(targetid + '[' + index + ']').value = data[0].currbalance;
				}
			}
		}


		xmlhttp.open("GET","Get_AutocompleteQuery.php?tablename="+tablename+"&pledger="+pledger+"&pdate="+pdate,true);
		xmlhttp.send();
	}

}

function GetLedgerAmount(tablename,option,ptransid,type_dbcd,index,pcurrency,pdate) {
		
	if (ptransid != "") {
	
		var xmlhttp = getXMLHttpRequest();
		
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				{ 
					var data = $.parseJSON(xmlhttp.responseText);
					
					if (option == 1) {
						if (document.getElementById('transdtamount_h[' + index + ']'))   document.getElementById('transdtamount_h[' + index + ']').value = data[0].TransDtAmount;
						if (document.getElementById('transdtamount1_h[' + index + ']'))  document.getElementById('transdtamount1_h[' + index + ']').value = data[0].TransDtAmount1;
						if (document.getElementById('transdtamount2_h[' + index + ']'))  document.getElementById('transdtamount2_h[' + index + ']').value = data[0].TransDtAmount2;
						
						if (document.getElementById('transdtamount[' + index + ']'))   document.getElementById('transdtamount[' + index + ']').value = data[0].TransDtAmount_D;
						if (document.getElementById('transdtamount1[' + index + ']'))  document.getElementById('transdtamount1[' + index + ']').value = data[0].TransDtAmount1_D;
						if (document.getElementById('transdtamount2[' + index + ']'))  document.getElementById('transdtamount2[' + index + ']').value = data[0].TransDtAmount2_D;
					} else {
						if (document.getElementById('transdtamount02_h[' + index + ']'))  document.getElementById('transdtamount02_h[' + index + ']').value = data[0].TransDtAmount;
						if (document.getElementById('transdtamount12_h[' + index + ']'))  document.getElementById('transdtamount12_h[' + index + ']').value = data[0].TransDtAmount1;
						if (document.getElementById('transdtamount22_h[' + index + ']'))  document.getElementById('transdtamount22_h[' + index + ']').value = data[0].TransDtAmount2;
						
						if (document.getElementById('transdtamount02[' + index + ']'))  document.getElementById('transdtamount02[' + index + ']').value = data[0].TransDtAmount_D;
						if (document.getElementById('transdtamount12[' + index + ']'))  document.getElementById('transdtamount12[' + index + ']').value = data[0].TransDtAmount1_D;
						if (document.getElementById('transdtamount22[' + index + ']'))  document.getElementById('transdtamount22[' + index + ']').value = data[0].TransDtAmount2_D;	
					}	
				}
			}
		}


		xmlhttp.open("GET","Get_AutocompleteQuery.php?tablename="+tablename+"&PTransId="+ptransid+"&Type_dbcd="+type_dbcd+"&pcurrency="+pcurrency+"&pdate="+pdate,true);
		xmlhttp.send();
	}

}

function CheckLookup(tablename,dbtablename,dbfieldname,inputval,buttonid) {

	//var inputval = "";
	//if (document.getElementById(inputid))
		//inputval = document.getElementById(inputid).value;
	
	inputval = encodeURIComponent(inputval);
	
	if (inputval != "") {
	
		var xmlhttp = getXMLHttpRequest();
		
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				{ 
					var data = $.parseJSON(xmlhttp.responseText);
					var Exists = data[0].exists;
					
					if (Exists == 0) {
						if (document.getElementById(buttonid))
							document.getElementById(buttonid).click();
					} 
				}
			}
		}


		xmlhttp.open("GET","Get_AutocompleteQuery.php?tablename="+tablename+"&dbtablename="+dbtablename+"&dbfieldname="+dbfieldname+"&inputval="+inputval,true);
		xmlhttp.send();
	}
}

function GetNameFromCode(actionname,dbtablename,dbfieldcode,dbfieldname,dbfieldcurr,inputval,targetid,targetid1) {

	inputval = encodeURIComponent(inputval);
	
	if (inputval != "") {
	
		var xmlhttp = getXMLHttpRequest();
		
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				{ 
					var data = $.parseJSON(xmlhttp.responseText);
					
					if (document.getElementById(targetid))   document.getElementById(targetid).value = data[0].name;
					
					if (document.getElementById(targetid1)){
						document.getElementById(targetid1).value = data[0].curr;
						$(document.getElementById(targetid1)).trigger("change");
					}	
				}
			}
		}


		xmlhttp.open("GET","Get_AutocompleteQuery.php?tablename="+actionname+"&dbtablename="+dbtablename+"&dbfieldcode="+dbfieldcode+"&dbfieldname="+dbfieldname+"&dbfieldcurr="+dbfieldcurr+"&inputval="+inputval,true);
		xmlhttp.send();
	}
}

function SetModRecord(tagid,index) {
	if (document.getElementById(tagid))
		document.getElementById(tagid).value = index;
}

function fireTrigger(el,evttype) {
	if (document.createEvent) {
        var evt = document.createEvent('HTMLEvents');
        evt.initEvent( evttype, false, false);
        el.dispatchEvent(evt);
    } 
	else if (document.createEventObject) {
        el.fireEvent('on' + evttype);
    }    
}	

function ScrollHeader(Header_Div, Content_Div)
{	
    $("#"+Header_Div).scrollLeft($("#"+Content_Div).scrollLeft());
}

function getScrollSizes() 
{ 
	// call after document is finished loading
	var el= document.createElement('div');
	el.style.display= 'hidden';
	el.style.overflow= 'scroll';
	document.body.appendChild(el);
	var h= el.offsetHeight-el.clientHeight;
	document.body.removeChild(el);
	
	return h;
}

			
			
function roundNumber(num, dec) { // Arguments: number to round, number of decimal places
  var newnumber = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
  newnumber = parseFloat(newnumber); 
  
  return newnumber;
}

function formatNumber(num, dec) { // Arguments: number to format, number of decimal places
	return num.toFixed(dec);
}

function checkNumberVal(tagID){

	var checkOK = ",0123456789.";
	var checkStr = document.getElementById(tagID).value;
	var allValid = true;
	var allNum = "";
	for (i = 0;  i < checkStr.length;  i++)
	{
		ch = checkStr.charAt(i);
		for (j = 0;  j < checkOK.length;  j++)
		if (ch == checkOK.charAt(j))
			break;
		if (j == checkOK.length)
		{
			allValid = false;
			break;
		}
		if (ch != ",")
			allNum += ch;
	}
	if (!allValid)
	{				
		alert("Value not allowed. Please enter only numbers.");
		document.getElementById(tagID).focus();	

		return (false);
	}

}



function scrollPage() { 
	var scrollY;
	if (typeof(window.pageYOffset) == 'number') {
		scrollY = window.pageYOffset;
	}
	else {
		scrollY = document.documentElement.scrollTop;
	}
	
	document.cookie='ypos=' + scrollY;
	
}
function scrollToPage() {
	window.scrollTo(0,readCookie('ypos'));
}

function readCookie(name){
	return(document.cookie.match('(^|; )'+name+'=([^;]*)')||0)[2];
}
function createCookie(name,val){
	document.cookie= name + '=' + val;
}
function eraseCookie(name)
{
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}