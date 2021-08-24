<?php
session_start();


if (!empty($_GET["action"])) {
	$dftorderby   = $_GET["dftorderby"];
	$action 	  = $_GET["action"];
	$orderby 	  = $_GET["orderby"];
	$morelines	  = $_GET["morelines"];
	$startrange   = $_GET["startrange"];
	$perpage 	  = $_GET["perpage"];
	$totalrows    = $_GET["totalrows"];
	$direction    = $_GET["direction"];
	
	include("../../config.inc.php");
	$connection = mysql_connect($config['dbServer'],$config['dbUser'],$config['dbPass']) or die("Could not connect to DB");
	mysql_select_db($_SESSION['dbName'],$connection) or die("Could not find DB");
	//echo $_SESSION['gquery']."<br>";
	showDataGrid($connection,$_SESSION['gquery'],$_SESSION['garraycolumns'],$_SESSION['garrayaccess'],$dftorderby,$action,$orderby,$morelines,$startrange,$perpage,$totalrows,$direction);
}
 
 
function showDataGrid($conn,$query,$arraycolumns,$arrayaccess,$dftorderby,$action,$orderby,$morelines,$startrange,$perpage,$totalrows,$direction) {

	/*$orderby    = $_POST["orderby"]; if ($orderby == ""){$orderby = $_GET["orderby"];}
	$perpage    = $_POST["perpage"]; if ($perpage == ""){$perpage = $_GET["perpage"];}
	$startrange = $_POST["startrange"]; if ($startrange == ""){$startrange = $_GET["startrange"];}
	$morelines  = $_POST["morelines"]; if ($morelines == ""){$morelines = $_GET["morelines"];}
	*/
	if ($startrange=='') $startrange = 0;
	if ($perpage=='')	 $perpage = 20;
	if ($orderby=='')	 $orderby = $dftorderby;
	if ($morelines=='')	 $morelines = 1;
	
	if ($direction == 'asc') $direction = 'desc';
	else if ($direction == 'desc') $direction = 'asc';
	else $direction = 'asc';
	
	//if (empty($action))	
	$query .= " order by $orderby $direction ";
	//echo $query;
	$result=mysql_query($query,$conn);
	$totalrows = mysql_num_rows($result);

	$query = $query." limit ".$startrange.",".$perpage;
	$result=mysql_query($query,$conn);
	
	
	
	echo"<div id='container'>";
	echo"<div id='Loader' class=''><div></div></div>";
	echo"	<div id='grid_mainDiv' class='grid_mainDiv'>";
	
	echo"		<table border='0' align='left'  class='grid_data' cellpadding='0' cellspacing='1'>";
	echo"		<tr>";
	echo"		<td style='height:25px; width:25px;'>&nbsp;</td>";
	
	$columnscounter = count($arraycolumns);
	$columnsarray    = array();
	$columnsarrayall = array();
	
	$actionbuttons    = array("delete", "submit");
	$actionbuttonsall = array("delete", "clear", "submit");
		
	$filepath = './js/grid/UI_DataGrid.php';
	
	for ($j=1; $j<=$columnscounter; $j++){
	
		$ColIndex = 'C'.$j;
		$ObjId    = $arraycolumns[$ColIndex]['ID'];
		
		if ($arraycolumns[$ColIndex]['Order'] == 'T' && $orderby == $arraycolumns[$ColIndex]['OrderField'] && $direction=='asc')
			$orderimg = "<img src='img\asc.png' name='w' width='10'  height='10' style='padding-left:5px;'>";
		else if ($arraycolumns[$ColIndex]['Order'] == 'T' && $orderby == $arraycolumns[$ColIndex]['OrderField'] && $direction=='desc')
			$orderimg = "<img src='img\desc.png' name='w' width='10'  height='10' style='padding-left:5px;'>";	
		else
			$orderimg = "";
		
		if (in_array($arraycolumns[$ColIndex]['Type'], $actionbuttons)) {
			echo"	<th style='height:25px; width:".$arraycolumns[$ColIndex]['HWidth']."' align='".$arraycolumns[$ColIndex]['HAlign']."'><span>&nbsp;</span></th>";
		} 
		else if ($arraycolumns[$ColIndex]['Type'] != 'hidden' && $arraycolumns[$ColIndex]['Type'] != 'clear') {
			array_push($columnsarray, $ObjId);
			$columnslist = implode(',', $columnsarray);
			
			echo"	<th style='height:25px; width:".$arraycolumns[$ColIndex]['HWidth']."' align='".$arraycolumns[$ColIndex]['HAlign']."'  onclick=\"ReloadGrid('".$filepath."', '', '".$dftorderby."', 'COR','".$arraycolumns[$ColIndex]['OrderField']."','".$morelines."','".$startrange."','".$perpage."','".$totalrows."','".$direction."');\"><span>".$arrayaccess[$arraycolumns[$ColIndex]['ID']]['FieldLabel']. $orderimg ."</span></th>";
		}
		
		if (!in_array($arraycolumns[$ColIndex]['Type'], $actionbuttonsall)) {
			array_push($columnsarrayall, $ObjId);
			$columnslistall = implode(',', $columnsarrayall);
		}	
			
			
	}	
	
		
	echo"		</tr>";
	
	echo"<input type='hidden' name='SelectedRec' id='SelectedRec' value='$SelectedRec'>
		 <input type='hidden' name='SelectedRecColor' id='SelectedRecColor' value='$SelectedRecColor'>";

	
	
	$i=0;
	
	while($row=mysql_fetch_row($result)){

		$recno = $i+1;
		
		if ($i%2==0)
			$bgcolor='bgcolor1';
		else	
			$bgcolor='bgcolor2';
		
		
		echo"<tr id='CurRec$i' onclick=\"selRecordColor(this,'SelectedRec','SelectedRecColor');\" class='$bgcolor'>";		
		echo"	<td>$recno</td>";
		
		for ($j=1; $j<=$columnscounter; $j++){
	
			$ColIndex = 'C'.$j;
			
			$ObjId    = $arraycolumns[$ColIndex]['ID'] .'['. $i .']';
			$ObjName  = $arraycolumns[$ColIndex]['Name'] .'['. $i .']';
			
			$ObjValue  = $arraycolumns[$ColIndex]['Name'] .'['. $i .']';
			$$ObjValue = $row[$j-1];
			
			$KeyDown   = 'onkeydown="return dokey(event,this,'.$i.',\''.$columnslist.'\',\'imgSubmit['.$i.']\')"';
			$ListClick = 'onclick="showList(\''.$arraycolumns[$ColIndex]['ListPage'].'\','.$i.','.$arraycolumns[$ColIndex]['ListWidth'].','.$arraycolumns[$ColIndex]['ListHeight'].')"';
			
			if ($arraycolumns[$ColIndex]['Type'] == 'delete') {	
				echo"<td align='center' valign='center'><img src='img/delete.png' width='20' height='20' onmouseover=\"style.cursor='pointer'\" onclick=\"DeleteRecordGrid('".$filepath."','','".$row[0]."','".$dftorderby."','".$orderby."','".$morelines."','".$startrange."','".$perpage."','".$direction."');return false;\"  title='Delete Record' ></td>";
			}
			else if ($arraycolumns[$ColIndex]['Type'] == 'submit') {	
				if ($arraycolumns[$ColIndex]['Params'] != '')
					$SubmitClick = "onclick=\"SubmitRecordGrid('".$i."','".$filepath."','','".$dftorderby."','".$orderby."','".$morelines."','".$startrange."','".$perpage."','".$direction."',".$arraycolumns[$ColIndex]['Params'].")\"";
				else
					$SubmitClick = "onclick=\"SubmitRecordGrid('".$i."','".$filepath."','','".$dftorderby."','".$orderby."','".$morelines."','".$startrange."','".$perpage."','".$direction."')\"";
					
				echo"<td align='center' valign='center'><img src='img/confirm.png' id='imgSubmit[$i]' width='20' height='20' onmouseover=\"style.cursor='pointer'\" ".$SubmitClick." title='Save Record' ></td>";
			}
			else if ($arraycolumns[$ColIndex]['Type'] == 'hidden') {
				echo"<input type='hidden'  name='".$ObjName."' id='".$ObjId."' value='".$$ObjValue."' >";
			}
			else if ($arraycolumns[$ColIndex]['Type'] == 'textbox') {
				displayTextbox($ObjId, $ObjName, $$ObjValue, $KeyDown, $arraycolumns[$ColIndex]['Print'], $arraycolumns[$ColIndex]['MaxLength'], $arrayaccess[$arraycolumns[$ColIndex]['ID']]['FieldMod'], $arraycolumns[$ColIndex]['OpenList'], $ListClick, $arraycolumns[$ColIndex]['OpenDate'], $DateClick, $arraycolumns[$ColIndex]['Width'], $arraycolumns[$ColIndex]['SpanClass'], $arraycolumns[$ColIndex]['ClassE']);
			}
			else if ($arraycolumns[$ColIndex]['Type'] == 'droplist') {
				displayList($ObjId, $ObjName, $$ObjValue, $KeyDown, $arraycolumns[$ColIndex]['Print'], $conn, $arraycolumns[$ColIndex]['ListQuery'], $arraycolumns[$ColIndex]['Width'], $arraycolumns[$ColIndex]['SpanClass'], $arraycolumns[$ColIndex]['ClassE'], $arraycolumns[$ColIndex]['ListonChange']);
			}
			else if ($arraycolumns[$ColIndex]['Type'] == 'droparraylist') {
				displayArrayList($ObjId, $ObjName, $$ObjValue, $KeyDown, $arraycolumns[$ColIndex]['Print'], $arraycolumns[$ColIndex]['ListArrayVal'], $arraycolumns[$ColIndex]['ListArrayDesc'], $arraycolumns[$ColIndex]['Width'], $arraycolumns[$ColIndex]['SpanClass'], $arraycolumns[$ColIndex]['ClassE'], $arraycolumns[$ColIndex]['ListonChange']);
			}
		}	
		
		echo"</tr>";
		
		$i+=1;
	}
	
	
		
	for ($k=1;$k <= $morelines;$k++) {
		$recno = $i+1;
		if ($k==$morelines)
			$mode = 'i';
		else
			$mode = 'e';			

		
		echo"<tr id='CurRec$i' onclick=\"selRecordColor(this,'SelectedRec','SelectedRecColor');\" >";
		echo"	<td>$recno</td>";
		
		for ($j=1; $j<=$columnscounter; $j++){
	
			$ColIndex = 'C'.$j;	
			
			$ObjId    = $arraycolumns[$ColIndex]['ID'] .'['. $i .']';
			$ObjName  = $arraycolumns[$ColIndex]['Name'] .'['. $i .']';
			$ObjValue = $arraycolumns[$ColIndex]['Name'] .'['. $i .']';	
			
			$KeyDown   = 'onkeydown="return dokey(event,this,'.$i.',\''.$columnslist.'\',\'imgSubmit['.$i.']\')"';
			$ListClick = 'onclick="showList(\''.$arraycolumns[$ColIndex]['ListPage'].'\','.$i.','.$arraycolumns[$ColIndex]['ListWidth'].','.$arraycolumns[$ColIndex]['ListHeight'].')"';
			
			
			
			if ($arraycolumns[$ColIndex]['Type'] == 'clear') {	
				echo"<td align='center' valign='center'><img src='img/undo.png' width='20' height='20' onmouseover=\"style.cursor='pointer'\" onclick=\"ClearRecordGrid('".$columnslistall."','".$i."');return false;\" title='Clear Record' ></td>";
			}
			else if ($arraycolumns[$ColIndex]['Type'] == 'submit') {	
				if ($arraycolumns[$ColIndex]['Params'] != '')
					$SubmitClick = "onclick=\"SubmitRecordGrid('".$i."','".$filepath."','','".$dftorderby."','".$orderby."','".$morelines."','".$startrange."','".$perpage."','".$direction."',".$arraycolumns[$ColIndex]['Params'].")\"";
				else
					$SubmitClick = "onclick=\"SubmitRecordGrid('".$i."','".$filepath."','','".$dftorderby."','".$orderby."','".$morelines."','".$startrange."','".$perpage."','".$direction."')\"";
					
				echo"<td align='center' valign='center'><img src='img/confirm.png' id='imgSubmit[$i]' width='20' height='20' onmouseover=\"style.cursor='pointer'\" ".$SubmitClick." title='Save Record' ></td>";
			}
			else if ($arraycolumns[$ColIndex]['Type'] == 'hidden') {
				echo"<input type='hidden'  name='".$ObjName."' id='".$ObjId."' value='".$$ObjValue."' >";
			}
			else if ($arraycolumns[$ColIndex]['Type'] == 'textbox') {
				displayTextbox($ObjId, $ObjName, $$ObjValue, $KeyDown, $arraycolumns[$ColIndex]['Print'], $arraycolumns[$ColIndex]['MaxLength'], $arrayaccess[$arraycolumns[$ColIndex]['ID']]['FieldMod'], $arraycolumns[$ColIndex]['OpenList'], $ListClick, $arraycolumns[$ColIndex]['OpenDate'], $DateClick, $arraycolumns[$ColIndex]['Width'], $arraycolumns[$ColIndex]['SpanClass'], $arraycolumns[$ColIndex]['ClassI']);
			}
			else if ($arraycolumns[$ColIndex]['Type'] == 'droplist') {
				displayList($ObjId, $ObjName, $$ObjValue, $KeyDown, $arraycolumns[$ColIndex]['Print'], $conn, $arraycolumns[$ColIndex]['ListQuery'], $arraycolumns[$ColIndex]['Width'], $arraycolumns[$ColIndex]['SpanClass'], $arraycolumns[$ColIndex]['ClassI'], $arraycolumns[$ColIndex]['ListonChange']);
			}
			else if ($arraycolumns[$ColIndex]['Type'] == 'droparraylist') {
				displayArrayList($ObjId, $ObjName, $$ObjValue, $KeyDown, $arraycolumns[$ColIndex]['Print'], $arraycolumns[$ColIndex]['ListArrayVal'], $arraycolumns[$ColIndex]['ListArrayDesc'], $arraycolumns[$ColIndex]['Width'], $arraycolumns[$ColIndex]['SpanClass'], $arraycolumns[$ColIndex]['ClassI'], $arraycolumns[$ColIndex]['ListonChange']);
			}
		}
		echo"</tr>";
		
		$i+=1;
	}
		
	echo"		</table>";	
	echo"	</div>";
	
	
	showDataGridFooter($filepath,$dftorderby,$action,$orderby,$morelines,$startrange,$perpage,$totalrows,$direction);
	
	echo"</div>";
}

function showDataGridFooter($filepath,$dftorderby,$action,$orderby,$morelines,$startrange,$perpage,$totalrows,$direction) {
	
	echo"<div class='grid_toolbarDiv'>
			<table width='100%' border=0>
			<tr>";
			
	echo"		<td valign=top align=left style='padding-left:10px;'>";
	echo"			<span class='toolbar_style'>Add Lines:</span><select name='morelines' id='morelines' value='".$morelines."' onchange=\"ReloadGrid('".$filepath."', '".$transid."', '".$dftorderby."', 'AL','".$orderby."',this.value,'".$startrange."','".$perpage."','".$totalrows."','".$direction."');return false;\" >";
	
					if ($morelines==1)	     $sel1 = 'selected';
					else if ($morelines==4)	 $sel4 = 'selected';	
					else if ($morelines==8)	 $sel8 = 'selected';	
					else if ($morelines==12) $sel12 = 'selected';	
					else if ($morelines==16) $sel16 = 'selected';	
	echo"			<option $sel1 value='1'>1</option><option $sel4 value='4'>4</option><option $sel8 value='8'>8</option><option $sel12 value='12'>12</option><option $sel16 value='16'>16</option></select>";
	echo"		</td>";
				
	echo"		<td  style='width:75px;border-right:1px solid #000000;text-align:right;'>";
				
				if($startrange > 0)
					echo"<img src='img/firstpage.png' title='First Page' onMouseOver=\"style.cursor='pointer'\"    onclick=\"ReloadGrid('".$filepath."', '', '".$dftorderby."', 'F','".$orderby."','".$morelines."','".$startrange."','".$perpage."','".$totalrows."','".$direction."');return false;\" >";

				$diff= $startrange - $perpage;
					
				if (($diff) >= 0 ){
					$prvstartrange = $startrange - $perpage;
					echo"&nbsp;&nbsp;<img src='img/arrowleft.png' title='Previous' onMouseOver=\"style.cursor='pointer'\"  onclick=\"ReloadGrid('".$filepath."', '', '".$dftorderby."', 'P','".$orderby."','".$morelines."','".$prvstartrange."','".$perpage."','".$totalrows."','".$direction."');return false;\" >";
				}
				else
					echo "&nbsp;";
			
	echo"		</td><td  align='left' style='width:75px;'>";
			
			
				if ($startrange <= $totalrows){
					$startrange1 = $startrange + $perpage;
					if  ($startrange1 < $totalrows){ 
						echo"<img src='img/arrowright.png' title='Next' onclick=\"ReloadGrid('".$filepath."', '', '".$dftorderby."', 'N','".$orderby."','".$morelines."','".$startrange1."','".$perpage."','".$totalrows."','".$direction."');return false;\" onmouseover=\"style.cursor='pointer'\" >";
						echo"&nbsp;&nbsp;<img src='img/lastpage.png' title='Last Page' onMouseOver=\"style.cursor='pointer'\" onclick=\"ReloadGrid('".$filepath."', '', '".$dftorderby."', 'L','".$orderby."','".$morelines."','".$prvstartrange."','".$perpage."','".$totalrows."','".$direction."');return false;\" >";	
					}
				} else
					echo "&nbsp;";
			
				
			
				$currentpage = ($startrange / $perpage) + 1;
				$totalpages  = ceil($totalrows / $perpage);
				
	echo"		</td><td style='width:90px;'>&nbsp;<span class='toolbar_style'>Page $currentpage / $totalpages</span></td>";
	
	echo"		<td align='right' style='width:100px;'><span class='toolbar_style'>Per Page </span><span class='toolbar_style'><select name='perpage' id='perpage' value='$perpage' onchange=\"ReloadGrid('".$filepath."', '', '".$dftorderby."', 'CP','".$orderby."','".$morelines."',0,this.value,'".$totalrows."','".$direction."');\">";
				  
				if ($perpage==20)		$s20 = 'selected';
				else if ($perpage==30)	$s30 = 'selected';
				else if ($perpage==40)	$s40 = 'selected';
				else if ($perpage==50)	$s50 = 'selected';

								
				echo'<option '.$s20.' value="20">20</option>
					 <option '.$s30.' value="30">30</option>
					 <option '.$s40.' value="40">40</option>
					 <option '.$s50.' value="50">50</option>
					 </select></span>
				</td>';
	
	echo"		<td style='width:30px;'><img src='img/Refresh-icon.png'  onMouseOver=\"style.cursor='pointer'\"  width='20' height='20' onclick=\"ReloadGrid('".$filepath."', '', '".$dftorderby."', 'R','".$orderby."','".$morelines."','".$startrange."','".$perpage."','".$totalrows."','".$direction."');\" ></td>";

				$rangefrom = ($startrange + 1);
				if (($startrange + $perpage) >= $totalrows) $rangeto = $totalrows; else $rangeto = ($startrange + $perpage);
				
	if ($totalrows > 0)			
		echo"	<td align='right' style='width:150px; padding-right:10px;'><span class='toolbar_style'>View ".$rangefrom." - ".$rangeto." of ".$totalrows."</span></td>";
	else
		echo"	<td align='right' style='width:150px; padding-right:10px;'><span class='toolbar_style'>No records to view</span></td>";
	
	
	echo"	</tr>";
	echo"	</table></div>";

}

function displayList($fieldid, $fieldname, $value, $keydown, $prints, $conn, $query, $w1, $spanclass, $class, $onChange){
	if ($w1 == ""){
		$w1 = "100px";
	}
		
	print "<td align='left'><span class='".$spanclass."'>";
	if ($prints == 'T'){
		print "<input type='hidden' name='".$fieldname."' id='".$fieldid."' value='".$value."' class='".$class."' ".$keydown." >";
	}else{
		if ($onChange == ''){
			print "<select name='".$fieldname."' id='".$fieldid."' style='width:".$w1."'>";
		}else{
			print "<select name='".$fieldname."' id='".$fieldid."' ".$onChange." style='width:".$w1."' class='".$class."' ".$keydown." >";
		}
	}
	if ($prints != 'T'){
		print "<option value=''>---</option>";
	}		 
		
	$result = mysql_query($query, $conn);
	while($cur = mysql_fetch_row($result)){
		if ($cur[0] == $value){
			if ($prints == 'T'){
				print $cur[1];
			}else{
				print "<option selected value='".$cur[0]."'>".$cur[1]."</option>";
			}
		}else{
			if ($prints != 'T'){
				print "<option value='".$cur[0]."'>".$cur[1]."</option>";
			}
		}
	}		
		
	if ($prints == 'T'){
		print "&nbsp;";
	}else{
		print "</select>";		
	}
	print "</span></td>";	
}

//displayArrayList
function displayArrayList($fieldid, $fieldname, $value, $keydown, $prints, $v1, $v2, $w1, $spanclass, $class, $onChange){		
	if ($w1 == ""){
		$w1 = "100px";
	}
			
	print "<td align='left'><span class='".$spanclass."'>";
	if ($prints == 'T'){
		print "<input type='hidden' name='".$fieldname."' id='".$fieldid."' value='".$value."'>";
	}else{
		if ($onChange == ''){
			print "<select name='".$fieldname."' id='".$fieldid."' style='width:".$w1."' class='".$class."' ".$keydown." >";
		}else{
			print "<select name='".$fieldname."' id='".$fieldid."' ".$onChange." style='width:".$w1."' class='".$class."' ".$keydown." >";
		}
	}
	if ($prints != 'T'){
		print "<option value=''>---</option>";
	}
		
	for ($i=0;$i<count($v1);$i++){
		if ($v1[$i] == $value){
			if ($prints == 'T'){
				print $v2[$i];
			}else{
				print "<option selected value='".$v1[$i]."'>".$v2[$i]."</option>";
			}
		}else{
			if ($prints != 'T'){
				print "<option value='".$v1[$i]."'>".$v2[$i]."</option>";
			}
		}
	}
		
	if ($prints == 'T'){
		print "&nbsp;";
	}else{
		print "</select>";		
	}		
	print "</span></td>";	
}
	
	
//Display Textbox
function displayTextbox($fieldid, $fieldname, $value, $keydown, $prints, $maxlength, $readonly, $openlist, $listclick, $opendate, $dateclick, $w1, $spanclass, $class){
	/*
	$field = Textbox Name
	$name = Display Name
	$value = Textbox Value
	$prints = Form to be printed or not
	$maxlength = Maxlength of Textbox data value and physical size of textbox		
	$w1 = Width of the table cell <td> displaying the Textbox Name
	$w1 = Width of the table cell <td> displaying the Textbox
	*/
	if ($w1 == ""){
		$w1 = "100px";
	}
	

	if ($maxlength == ""){
		$maxlength = "1024";
	}
	if ($prints == 'T'){
		print "<td align='left'><span class='".$spanclass."'><input type='hidden' name='".$fieldname."' id='".$fieldid."' value='".$value."'>&nbsp;&nbsp;".$value."</span></td>";
	}else{
		if($opendate == 'T') $class = $class . ' date';
		
		print "<td align='left'><span class='".$spanclass."'><input type='text' name='".$fieldname."' id='".$fieldid."' ".$keydown." maxlength='".$maxlength."' value='".$value."' style='width:".$w1."' class='".$class."' $readonly></span>";
		
		if ($openlist == 'T')
			print "<img src='img/openlist.png' onmouseover=\"style.cursor='pointer'\"  ".$listclick." >";
		//else if ($opendate == 'T')
			//print "<a href='#' ".$dateclick." ><img src='./img/calendar.png' width='17' height='17' border=0 align=center></a>";
			
		print "</td>";	
	}
}

//Display Checkbox
function displayCheckbox($fieldid, $fieldname, $value, $checkedvalue, $prints, $checked, $onclick, $spanclass, $class){

	if ($prints == 'T'){
		print "<td align='left'><span class='".$spanclass."'><input type='hidden' name='".$fieldname."' id='".$fieldid."' value='".$value."'>&nbsp;&nbsp;".$value."</span></td>";
	}else{
		
		print "<td align='left'><span class='".$spanclass."'><input type='checkbox' name='".$fieldname."' id='".$fieldid."' value='".$value."' ".$onlick."  ".$checked." class='".$class."' "; 
		if($value == $checkedvalue) echo " checked  />";  else echo " />"; 
		
		print "</td>";	
	}
}


//Display Text Area
function displayTextarea($fieldid, $fieldname, $value, $prints, $rows, $cols, $w1, $w2, $spanclass){
	/*
	$field = Textarea Name
	$name = Display Name
	$value = Textarea Value
	$prints = Form to be printed or not
	$rows = Textarea rows
	$cols = Textarea columns
	$w1 = Width of the table cell <td> displaying the Textarea Name
	$w1 = Width of the table cell <td> displaying the Textarea
	*/
	if ($w1 == ""){
		$w1 = "50%";
	}
	if ($w2 == ""){
		$w2 = "50%";
	}
	if ($rows == ""){
		$rows = "5";
	}
	if ($cols == ""){
		$cols = "30";
	}
	if ($prints == 'T'){
		print "<td align='left' width='".$w1."'><span class='".$spanclass."'><input type='hidden' name='".$field."' value='".$value."'>&nbsp;&nbsp;".$value."</span></td>";
	}else{
		print "<td align='left' width='".$w1."'><span class='".$spanclass."'><textarea name='".$fieldname."' name='".$fieldid."' rows='".$rows."' cols='".$cols."'>".$value."</textarea></span></td>";
	}
}

if (!function_exists('GetSQLValueString')) {	
function GetSQLValueString($theValue, $theType) 
{
  $theValue = trim($theValue);	
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "Null";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "Null";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "Null";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "Null";
      break;
  }
  return $theValue;
}
}
	
?>	