<?php
session_start();
include("../libraries/config.inc.php");
///if($_SESSION['admin']==""){
///header("location:index.php");
///}

 function generateRandomString($length = 15, $abc = "0123456789abcdefghijklmnopqrstuvwxyz") //NEVER CHANGE $abc values
{
    return substr(str_shuffle($abc), 0, $length);
}
function GetSQLValueString($theValue, $theType) 
{	
  $theValue = trim($theValue);	
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

   
  $theValue = str_replace("'","\'","$theValue");

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

$link = mysqli_connect($config['server'], $config['dbusername'], $config['dbpass']);

if (mysqli_connect_errno())
  {
  echo "ERROR Failed to connect to MySQL: " . mysqli_connect_error();
  }
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

/*
mysqli_select_db($link, "binancefutures");

$query =" select UNIX_TIMESTAMP(current_timestamp()) as currenttimestamp from dual";
 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	         $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 

   while($row = mysqli_fetch_assoc($result)) {
				extract($row);
			}
			echo $currenttimestamp; */
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
	
<script type='text/javascript'>
var flag=false;
  var cumucloseopen = '';
  var popup;
  var TOTALTRADES=-1;
 
  var OP_BUY=1; var OP_SELL =-1;
  var GLOBALSL = 85; var GLOBALTP = 115;
  var GLOBALPRICE = 0;
  var GLOBALPOSITIONHASH = '';
  
  var globalsymbolname = '';
  var globaldigitdecimalplace=1;
  var globalstoploss = 0;
  var globaltakeprofit = 0;
  var globalordermode = 0;
  var globalquotesrefreshinterval=15000;
  
  var cumuusd = parseFloat(0);
  var cumudd = parseFloat(0);
 
  var globalpostionpopup;
  
  
  
  
  function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
  function removeCommas(nStr)
{
  return nStr.replace(new RegExp(',', 'g'),"")
}


</script>
    <title>VPS MODE</title>
<style type='text/css'>


.slidecontainer {
  width: 100%;
}

.slider {
  -webkit-appearance: none;
  width: 100%;
  height: 25px;
  background: #d3d3d3;
  outline: none;
  opacity: 0.7;
  -webkit-transition: .2s;
  transition: opacity .2s;
}

.slider:hover {
  opacity: 1;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 25px;
  height: 25px;
  background: #4CAF50;
  cursor: pointer;
}

.slider::-moz-range-thumb {
  width: 25px;
  height: 25px;
  background: #4CAF50;
  cursor: pointer;
}
 
table tbody tr:hover {
       background-color: lightblue;
       cursor: pointer;
   }
 
   table{ text-align:center;zoom:0.92;}
   
#tbl_coins_length
{
 display:none !important;
}
#tbl_coins_info
{
 display:none !important;
}
#tbl_coins_paginate
{
 display:none !important;
}
</style>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../js_css/backend/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
	

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
 
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
 

                        <!-- Nav Item - Messages -->
                      

                       

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                               
                              
                              
                            
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h2 class="h3 mb-0 text-gray-800">RUN THIS PAGE 24/7 on a secure VPS<sup>Watching Market...</sup></h2>
                      
                    </div>

                    <!-- Content Row -->
 

                    <!-- Content Row -->

 

                    <!-- Content Row -->
                     <div class="container-fluid">
					     <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" id='populateactivepositions'>
                           
                            </div>

                        </div>
						
                    </div>
					</div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>My Telegram: &nbsp;&nbsp; <?php 
						 $link = mysqli_connect($config['server'], $config['dbusername'], $config['dbpass']);

if (mysqli_connect_errno())
  {
  echo "ERROR Failed to connect to MySQL: " . mysqli_connect_error();
  }
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

 
mysqli_select_db($link, "binancefutures");

$query = "select param_value as cpy from global_parameters where param_name = 'param_copyright';";	
$resultquery = mysqli_query($link, $query) or die(mysqli_error($link));
		  while($rowquery = mysqli_fetch_assoc($resultquery)) {
			  extract($rowquery);
		  }
		  echo $cpy;
						 ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

		    <div class="modal fade" id="addnewcoinmodal" style='' tabindex="-1" role="dialog" aria-labelledby="addnewcoinlabel"
        aria-hidden="true">
		
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addnewcoinlabel">BroadCast a new position</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
		           <div class="card-body">
                            <div class="table-responsive" id='populateactivecoins'>
                           
                            </div>
							 
							
							
							
							<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
  <div id="tradingview_576df"></div>
  <div class="tradingview-widget-copyright">
  
  <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script> 
 
  <script type="text/javascript">

  var countDecimals = function(value) {
    if (Math.floor(value) !== value)
        return value.toString().split(".")[1].length || 0;
    return 0;
}

  function globalsym (a)
  { 
	  globalsymbolname=a;
	   
  }
  
    function  globaldecimal(a)
  { 
	  globaldigitdecimalplace=a;
	   
  }
  
  
 function _opennewposition()
 {
    //document.getElementById("confirmbuysell").disabled = true;
	
	//alert(GLOBALPRICE);
	//return false;
	
 

	if(globalordermode == OP_BUY)
	{
		 
		if( parseFloat($('#inputstoploss').val()) != parseFloat(0))
		{
			if( parseFloat($('#inputstoploss').val())  > GLOBALPRICE)
			{
				$('#inputstoploss').val(0); $('#inputstoploss').focus(); return false;
			}
		}
		
		if( parseFloat($('#inputtakeprofit').val()) != parseFloat(0))
		{
			if( parseFloat($('#inputtakeprofit').val())  < GLOBALPRICE)
			{
				$('#inputtakeprofit').val(0); $('#inputtakeprofit').focus(); return false;
			}
		}		
	}

	if(globalordermode == OP_SELL)
	{
	
		
		if( parseFloat($('#inputstoploss').val()) != parseFloat(0))
		{
			if( parseFloat($('#inputstoploss').val())  < GLOBALPRICE)
			{	 
				$('#inputstoploss').val(0); $('#inputstoploss').focus(); return false;
			}
		}
		
		if( parseFloat($('#inputtakeprofit').val()) != parseFloat(0))
		{
			if( parseFloat($('#inputtakeprofit').val())  > GLOBALPRICE)
			{
				$('#inputtakeprofit').val(0); $('#inputtakeprofit').focus(); return false;
			}
		}		
		
		
		
	}
	 
	
 
	var _sym =globalsymbolname;
	var ordermode= globalordermode;
	var sl =$('#inputstoploss').val();
	var tp =$('#inputtakeprofit').val();
	var amnt =$('#rangeamount').val();
	var lvg =$('#rangeleverage').val();
	
	
		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=opennewposition&sym='+_sym+'&ordermode='+ordermode+'&sl='+sl+'&tp='+tp+'&amnt='+amnt+'&lvg='+lvg,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {


 
     $('#addnewcoinmodal').modal('hide');

	   
populateactivetrades();		 
     }

   });	
   
   
 

   
	
 } 
  </script>
</div>
  </div>
<!-- TradingView Widget END -->
 

<hr style='visibility:hidden;'>
							    <button onclick='buybtn(globaldigitdecimalplace);'id='buycrypto' name='buycrypto' style='width:45%;float:left;'type="button" class="btn btn-success">BUY</button>
							    <button onclick='sellbtn(globaldigitdecimalplace)' id='sellcrypto' name='sellcrypto' style='width:45%;float:right;' type="button" class="btn btn-danger">SELL</button>
							    
                                    <div class='sltpdiv' style='width:100%;display:block;'>
																 <div style='width:100%;display:block;margin-top:11.9% !important;'>
							    <label style='width:23%;display:inline-block;' for="inputstoploss"><b style='color:red;'>Stop Loss</b></label>
                                <input type="number"    style='width:48%;display:inline-block;' class="form-control" min='0' id="inputstoploss" name='inputstoploss' placeholder="Stop Loss" >
								<span  style='width:23%;display:none;margin-left:1.2%;'class="badgestoploss">0 pip</span>
								</div>
							    <div style='width:100%;display:block;margin-top:2.5%!important;'>
								<label  style='width:23%;display:inline-block;'for="inputtakeprofit"><b style='color:green;'>Take Profit</b></label>
                                <input type="number"    style='width:48%;display:inline-block;' class="form-control" id="inputtakeprofit" min='0' name='inputtakeprofit' placeholder="Take Profit" >
								<span  style='width:23%;display:none;margin-left:1.2%;'class="badgetakeprofit">0 pip</span>
                               </div>	
                               <hr style = 'visibility:hidden;'>
							    <p class='rangeamountlbl'>Amount: 100 USDT</p>
  <input type="range" min="100" max="5000" value="100" class="slider" step='100'id="rangeamount">
  <hr>
   <p class='rangeleveragelbl'>Leverage: 1x</p>
  <input type="range" min="1" max="5" value="1" class="slider" step='1' id="rangeleverage">
  
							    <ul class="list-group list-group-horizontal" style='display:none;text-align:center;color:black;font-weight:bolder;width:100%;'>

  <li class="list-group-item" style='font-size:x-small;width:25%;background:lightgrey;'>Position size :<br> 1,000 USDT </li>
  <li class="list-group-item" style='font-size:x-small;width:25%;background:lightgrey;'>Leverage :<br> 1x</li>
  <li class="list-group-item" class='approxprofit' style='font-size:x-small;width:25%;background:lightgrey;'>Estimated Profit :<br> 50 USDT</li>
  <li class="list-group-item" class='approxloss'   style='font-size:x-small;width:25%;background:lightgrey;'>Estimated Loss :<br> 300 USDT</li>
  
</ul>
							   </div>									
							   <button id='confirmbuysell' name='confirmbuysell' onclick='_opennewposition()' style='width:100%;margin-top:3%;'  type="button" class="btn btn-primary">Confirm</button>
						 
                             
                             
							
                      
 
				
				</div>
 
            </div>
        </div>
    </div>
	</div>
	
	
	
	
	
	
	
	
	
	
			    <div class="modal fade" id="closeposition" style='' tabindex="-1" role="dialog" aria-labelledby="closepos"
        aria-hidden="true">
		
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="closepos">Close Position and Broadcast to Telegram <br><sup style='color:coral;'>This action is irreversible</sup></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
		           <div class="card-body">
                  
				  <table id='tbl_closeposition' style='zoom:1.3;width:100%;text-align:center;  border: 1px solid black;'>
				   <tr style='width:100%;outline: thin solid;'>
				   <td style='width:50%;text-align:left;' class=''>Position HashCode:</td>
				   <td style='width:50%;text-align:left;' class='close_dialogue_orderid'></td>
				   </tr>	
				   <tr style='width:100%;outline: thin solid;'>
				   <td style='width:50%;text-align:left;' class=''>Position opened since:</td>
				   <td style='width:50%;text-align:left;' class='close_dialogue_date'></td>
				   </tr>
				   <tr style='width:100%;outline: thin solid;'>
				   <td style='width:50%;text-align:left;' class=''>Position Symbol:</td>
				   <td style='width:50%;text-align:left;' class='close_dialogue_symbol'></td>
				   </tr>
				    <tr style='width:100%;outline: thin solid;'>
				   <td style='width:50%;text-align:left;' class=''>Position Amount:</td>
				   <td style='width:50%;text-align:left;' class='close_dialogue_amount'></td>
				   </tr>
				    <tr style='width:100%;outline: thin solid;'>
				   <td style='width:50%;text-align:left;' class=''>Position Type:</td>
				   <td style='width:50%;text-align:left;' class='close_dialogue_type'></td>
				   </tr>
				    <tr style='width:100%;outline: thin solid;'>
				   <td style='width:50%;text-align:left;' class=''>Position Leverage:</td>
				   <td style='width:50%;text-align:left;' class='close_dialogue_leverage'></td>
				   </tr>
				    <tr style='width:100%;outline: thin solid;'>
				   <td style='width:50%;text-align:left;' class=''>Position Entry:</td>
				   <td style='width:50%;text-align:left;' class='close_dialogue_entry'></td>
				   </tr>
				    <tr style='width:100%;outline: thin solid;'>
				   <td style='width:50%;text-align:left;' class=''>Market Mark Price:</td>
				   <td style='width:50%;text-align:left;' class='close_dialogue_markprice'></td>
				   </tr>
				    <tr style='width:100%;outline: thin solid;'>
				   <td style='width:50%;text-align:left;' class=''>Position Profit:</td>
				   <td style='width:50%;text-align:left;' class='close_dialogue_profit'></td>
				   </tr>								   
				  </table>
 
				
				</div>
 
            </div>
			        <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a  id='close_pos_conf' class="btn btn-primary" onclick= 'closepositionconfirmed();'>Close Trade</a>
                </div>
				
        </div>
    </div>
	</div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>
	<script src="../js_css/backend/vendor/datatables/jquery.dataTables.js"></script>

    <!-- Page level custom scripts -->
   <!-- <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script> -->
<script>

function disablebuysellhidesltp()
{
 
	
	$("#buycrypto").css("opacity","1");
    $("#sellcrypto").css("opacity","1");
	$('#inputstoploss').val(0);
	$('#inputtakeprofit').val(0);
	$('.sltpdiv').fadeOut();	
	document.getElementById("confirmbuysell").disabled = true;
	$("#confirmbuysell").css("opacity","0.6");
	
}
$(document).ready(function()
{
	<?php
	echo "var srccopy='";
	$link = mysqli_connect($config['server'], $config['dbusername'], $config['dbpass']);

if (mysqli_connect_errno())
  {
  echo "ERROR Failed to connect to MySQL: " . mysqli_connect_error();
  }
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

 
mysqli_select_db($link, "binancefutures");

$query = "select param_value as tomirror from global_parameters where param_name = 'param_copyingsrc';";	
$resultquery = mysqli_query($link, $query) or die(mysqli_error($link));
		  while($rowquery = mysqli_fetch_assoc($resultquery)) {
			  extract($rowquery);
		  }
echo "https://www.binance.com/en/futures-activity/leaderboard/user?uid=$tomirror"; echo "'";  
	?>
	
	 
	var rangeamt = document.getElementById("rangeamount");
var rangelvg = document.getElementById("rangeleverage");


rangeamt.oninput = function() {
  $('.rangeamountlbl').html('Amount: ' +this.value + ' USDT');
}

rangelvg.oninput = function() {
   $('.rangeleveragelbl').html('Leverage: ' +this.value+'x');
}

	  populateactivetrades();
	  populateactivecoins();
	  
	 
	    popup =  window.open(srccopy, "binancepopup", "width=500,height=500");
      setInterval(function(){
		  
	
      try
	  {
				  html =$.parseHTML( popup.document.getElementsByTagName("BODY")[0].innerHTML ) ;
	      
		  
		   if( $(html ).find(".css-1ik9u98").length> 0 )         // use this if you are using id to check
{
	 
	  flag=true;
	  cumucloseopen = '';
			$(html ).find(".css-1ik9u98").each(function( index ) {
  //console.log( index + ": " + $( this ).html() );
     var pairname = ( $(this).find(".css-5x6ly7").html());
	 var sign=  ( $(this).find(".css-197wpr2").html());	 
	 if(sign.indexOf('-') > -1)
	 {
		 pairname =  '-'+pairname.trim().toString().toUpperCase();
	 }
	 else
	 {
pairname = pairname.trim().toString().toUpperCase();
	 }
	 
	 
     cumucloseopen = cumucloseopen+ pairname+',';
 
	 
	 
});

}
else{
	flag=false;
}

 
	  if(flag==true || flag ==false)
{
		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=closeopenautopositions&coinsarray='+cumucloseopen,
     data: '', // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 try {
   	      popup.close();
	      popup =  window.open(srccopy, "binancepopup", "width=500,height=500");
		 }
		 catch(err) {
  
}
          
		 
     }

   });	
}  
else
{
			 try {
   	      popup.close();
	      popup =  window.open(srccopy, "binancepopup", "width=500,height=500");
		 }
		 catch(err) {
  
}
}
	  }
	  catch(err) {
 
}

	
		  }, 27000); 
     
});

function populateactivecoins()
{
	theurl = '';
	 
	 $.ajax({

     type: "GET",
     url: 'ajax/getlistoftradablepairs.php',
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
           // data is ur summary
          $('#populateactivecoins').html(data);
		 
		  
		  var table = $('#tbl_coins').DataTable({
			     "aaSorting": [],
			     "pageLength": 5,
 "bSort" : false
});

		  
		 
     }

   });	
	
}
	function populateactivetrades()
{
	 
  
	theurl = '';
	 
	 $.ajax({

     type: "GET",
     url: 'ajax/getlistoftrades2.php',
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
           // data is ur summary
          $('#populateactivepositions').html(data);
		 
		  
		  var table = $('#tbl_openedpositions').DataTable({
			   responsive: true,
			  "aaSorting": [],
			  "pageLength" : 25,
   'aoColumnDefs': [{
        'bSortable': false,
        'aTargets': [-1,-2,-3,-4,-5,-6] /* 1st one, start by the right */
    }]
});

		  
		 
     }

   });
}
   
   
   		(function($) {
    $(document).ready(function() {
		setInterval(function(){ 
		 
		  $( ".tradesymbol" ).find('.thesymbol').each(function( index ) {
         var symname = $(this).html();
		 var nearestmarkeprice = $(this).closest( "tr" ).find( ".trademarkeprice" );
		 var nearesthash = $(this).closest( "tr" ).find( ".trd__hash" );
		 var liquidation = $(this).closest( "tr" ).find( ".liqid_p" );
		 var nearestleverage = $(this).closest( "tr" ).find( ".trade_leverage" );
		 var nearestliquidation = $(this).closest( "tr" ).find( ".lqd_level" );
		 var nearestamt = $(this).closest( "tr" ).find( ".trd_amnt" );
		 var nearesttradeprofitloss = $(this).closest( "tr" ).find( ".tradeprofitloss" );
		 var nearesttradetype= $(this).closest( "tr" ).find( ".tradetype" );
		 var nearesttradeentry= $(this).closest( "tr" ).find( ".tradentry" );
		 var coindigits= $(this).closest( "tr" ).find( ".coin_digits" );
		 
		 var nearestsl = $(this).closest( "tr" ).find( ".tradesl" );
		 var nearesttp = $(this).closest( "tr" ).find( ".tradetp" );
		 	theurl = '';
	 
if( window.navigator.onLine)
{
		 $.ajax({

     type: "GET",
     url: 'https://api.binance.com/api/v3/ticker/price?symbol='+symname,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 var pricedata=data.price;
		 var profit =0;
		   pricedata = parseFloat(pricedata);
		  
             $(nearestmarkeprice).html(addCommas(parseFloat(pricedata).toFixed(parseInt($(coindigits).val()))));
             
			  if($(nearesttradetype).html().indexOf("BUY") >= 0)
			 {
				 
			 var entry =  parseFloat(removeCommas($(nearesttradeentry).html()));
			 var price =  parseFloat(removeCommas($(nearestmarkeprice).html()));
			 var nearest_amt =  parseFloat(removeCommas($(nearestamt).html()));
			  
 
             
			  
			  if ( parseFloat(price) >=parseFloat(entry))
			  {
			 
                                var reversedentry = 	parseFloat(entry)-parseFloat(price)	;
                                var _100per100 = 	parseFloat(price);
                                var perc = 	(parseFloat(reversedentry)*parseFloat(100)/parseFloat(price))*parseFloat($(nearestleverage).html());		
                                
                                var profitinusd = ((parseFloat(perc)*parseFloat(nearest_amt)) /parseFloat(100));	
                                profitinusd =-Math.abs(parseFloat(profitinusd));
								profitinusd=parseFloat(2)*parseFloat(( Math.abs(profitinusd)));
								cumuusd = parseFloat(cumuusd)+Math.abs(parseFloat(profitinusd));	
                                profitpercentage = 	((parseFloat(profitinusd) *parseFloat(100))/		parseFloat(nearest_amt));
								cumudd = parseFloat(cumudd)+Math.abs(parseFloat(profitpercentage));
								
			 $(nearesttradeprofitloss).css("color","lightgreen");
			  }
			  
			  else
			  {
                                var reversedentry = 	parseFloat(entry)-parseFloat(price)	;
                                var _100per100 = 	parseFloat(price);
                                var perc = 	(parseFloat(reversedentry)*parseFloat(100)/parseFloat(price))*parseFloat($(nearestleverage).html());		
                                
                                var profitinusd = ((parseFloat(perc)*parseFloat(nearest_amt)) /parseFloat(100));	
                                profitinusd =parseFloat(1)*parseFloat((-Math.abs(parseFloat(profitinusd))));
								cumuusd = parseFloat(cumuusd)-Math.abs(parseFloat(profitinusd))
                                profitpercentage = 	((parseFloat(profitinusd) *parseFloat(100))/		parseFloat(nearest_amt));
									cumudd = parseFloat(cumudd)-Math.abs(parseFloat(profitpercentage));
                                $(nearesttradeprofitloss).css("color","coral");								
					   
			  }
			 
		 if(parseInt(profitpercentage)<=parseInt(-90)) 
		 {
			 // liquidation code 
			 				  		 		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=vpsclose&positionhash='+$(nearesthash).html()+'&tradeprofit='+profitinusd+'&markprice='+price,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
      populateactivetrades();	
	 
		 
     }

   });
   
		 }
			 
			 
			 var _entry =     ($(nearesttradeentry).html());
			 var _leverage =  ($(nearestleverage).html());
			 
			 
			 _entry=parseFloat(removeCommas(_entry));
			 _leverage=parseFloat(removeCommas(_leverage));
			 
			 liq__price= parseInt(1)/parseInt(_leverage);
			 liq__price= parseFloat(_entry) * parseFloat(liq__price);
			 liq__price = parseFloat(_entry)- parseFloat(liq__price);
			 liq__price = parseFloat(liq__price).toFixed(parseInt($(coindigits).val()));
			 liq__price=addCommas(liq__price);
			  
			// // // // $(nearestliquidation).html('<i>Liquidation Price</i><br><b> approx: ' +addCommas(liq__price)+'</b>');
			 
			 
			 
			 $(nearesttradeprofitloss).html( addCommas(parseFloat(profitinusd).toFixed(2)) + '<i>$</i><br>' +addCommas(parseFloat(profitpercentage).toFixed(2)) +'<i> %</i> ');	
			 
			 
			   //vps code
			   	 var nsl =  parseFloat(removeCommas($(nearestsl).html()));
				 var ntp =  parseFloat(removeCommas($(nearesttp).html()));
				 
				 if( parseFloat(price)>= parseFloat(ntp) &&  parseFloat(ntp) > parseFloat(0))
				 {
				   
				  //  alert( $(nearesthash).html());
				  //alert(profitinusd); 
				  
				  		 		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=vpsclose&positionhash='+$(nearesthash).html()+'&tradeprofit='+profitinusd+'&markprice='+price,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
      populateactivetrades();	
	 
		 
     }

   });
   
   
				 }






				 if( parseFloat(price)<= parseFloat(nsl) &&  parseFloat(nsl) > parseFloat(0))
				 {
				   
				  //  alert( $(nearesthash).html());
				  //alert(profitinusd); 
				  
				  		 		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=vpsclose&positionhash='+$(nearesthash).html()+'&tradeprofit='+profitinusd+'&markprice='+price,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
      populateactivetrades();	
	 
		 
     }

   });
   
   
				 }


				 
			   
			   //end vps code 
			   
			   
			 }
           

 


			  if($(nearesttradetype).html().indexOf("SELL") >= 0)
			 {
				 
			 var entry =  parseFloat(removeCommas($(nearesttradeentry).html()));
			 var price =  parseFloat(removeCommas($(nearestmarkeprice).html()));
			 var nearest_amt =  parseFloat(removeCommas($(nearestamt).html()));
			  
			  if ( parseFloat(entry)  >= parseFloat(price))
			  {
				  

                                var reversedentry = 	parseFloat(price)-parseFloat(entry)	;
                                var _100per100 = 	parseFloat(entry);
                                var perc = 	(parseFloat(reversedentry)*parseFloat(100)/parseFloat(entry))*parseFloat($(nearestleverage).html());		
                                
                                var profitinusd = ((parseFloat(perc)*parseFloat(nearest_amt)) /parseFloat(100));	
                                profitinusd =-Math.abs(parseFloat(profitinusd));
                                profitinusd =parseFloat(2)*parseFloat((Math.abs(parseFloat(profitinusd))));
								cumuusd = parseFloat(cumuusd)+Math.abs(parseFloat(profitinusd))	
                                profitpercentage = 	((parseFloat(profitinusd) *parseFloat(100))/		parseFloat(nearest_amt));	
                             	cumudd = parseFloat(cumudd)+Math.abs(parseFloat(profitpercentage));								
			 $(nearesttradeprofitloss).css("color","lightgreen");
			  }
			  
			  else
			  {
                                var reversedentry = 	parseFloat(price)-parseFloat(entry)	;
                                var _100per100 = 	parseFloat(entry);
                                var perc = 	(parseFloat(reversedentry)*parseFloat(100)/parseFloat(entry))*parseFloat($(nearestleverage).html());		
                                
                                var profitinusd = ((parseFloat(perc)*parseFloat(nearest_amt)) /parseFloat(100));	
                                profitinusd =parseFloat(1)*parseFloat((-Math.abs(parseFloat(profitinusd))));
								cumuusd = parseFloat(cumuusd)-Math.abs(parseFloat(profitinusd));
                                profitpercentage = 	((parseFloat(profitinusd) *parseFloat(100))/		parseFloat(nearest_amt));		
									cumudd = parseFloat(cumudd)-Math.abs(parseFloat(profitpercentage));
                                $(nearesttradeprofitloss).css("color","coral");								
				   
			  }
			 
			 
			 if(parseInt(profitpercentage)<=parseInt(-90)) 
			 {
						  
				  		 		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=vpsclose&positionhash='+$(nearesthash).html()+'&tradeprofit='+profitinusd+'&markprice='+price,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
      populateactivetrades();	
	 
		 
     }

   });		 
			 }
			
			 var _entry =     ($(nearesttradeentry).html());
			 var _leverage =  ($(nearestleverage).html());
			 
			 _entry=parseFloat(removeCommas(_entry));
			 _leverage=parseFloat(removeCommas(_leverage));
			 
			 liq__price= parseInt(1)/parseInt(_leverage);
			 liq__price= parseFloat(_entry) * parseFloat(liq__price);
			 liq__price = parseFloat(_entry)+ parseFloat(liq__price);
		     liq__price = parseFloat(liq__price).toFixed(parseInt($(coindigits).val()));
			 liq__price=addCommas(liq__price);
			// // // // $(nearestliquidation).html('<i>Liquidation Price</i> <b> approx: ' +liq__price+'</b>');
			 
			 $(nearesttradeprofitloss).html( addCommas(parseFloat(profitinusd).toFixed(2)) + '<i>$</i><br>' +addCommas(parseFloat(profitpercentage).toFixed(2)) +'<i> %</i> ');		
			 
			 
			 			   //vps code
			   	 var nsl =  parseFloat(removeCommas($(nearestsl).html()));
				 var ntp =  parseFloat(removeCommas($(nearesttp).html()));
				 
				 if( parseFloat(price)<= parseFloat(ntp) &&  parseFloat(ntp) > parseFloat(0))
				 {
				   
				  //  alert( $(nearesthash).html());
				  //alert(profitinusd); 
				  
				  		 		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=vpsclose&positionhash='+$(nearesthash).html()+'&tradeprofit='+profitinusd+'&markprice='+price,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
      populateactivetrades();	
	 
		 
     }

   });
   
   
				 }






				 if( parseFloat(price)>= parseFloat(nsl) &&  parseFloat(nsl) > parseFloat(0))
				 {
				   
				  //  alert( $(nearesthash).html());
				  //alert(profitinusd); 
				  
				  		 		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=vpsclose&positionhash='+$(nearesthash).html()+'&tradeprofit='+profitinusd+'&markprice='+price,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
      populateactivetrades();	
	 
		 
     }

   });
   
   
				 }


				 
			   
			   //end vps code 
			   
			   
			 }
			 
			 
 

			 
			 
     }

   });
}

 // END OF ITERATION



 	  
});
  if(parseInt(cumuusd)>=0)
  {
  $('.cumu_usd').html(''+addCommas(parseFloat(cumuusd).toFixed(2))+' USDT');
  $('.cumu_usd').css("color","lightgreen");
  }
  if(parseInt(cumuusd)<0)
  {
  $('.cumu_usd').html(''+addCommas(parseFloat(cumuusd).toFixed(2))+' USDT');
  $('.cumu_usd').css("color","coral"); 
  }
  
  
  
  if(parseInt(cumudd)>=0)
  {
  $('.cumu_dd').html(''+addCommas(parseFloat(-cumudd).toFixed(2))+' %');
  $('.cumu_dd').css("color","lightgreen");
  }
  if(parseInt(cumudd)<0)
  {
  $('.cumu_dd').html(''+addCommas(parseFloat(-cumudd).toFixed(2))+' %');
  $('.cumu_dd').css("color","coral"); 
  }
  

  	<?php
	echo "var max_ddfromserver='";
	$link = mysqli_connect($config['server'], $config['dbusername'], $config['dbpass']);

if (mysqli_connect_errno())
  {
  echo "ERROR Failed to connect to MySQL: " . mysqli_connect_error();
  }
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

 
mysqli_select_db($link, "binancefutures");

$query = "select param_value as _max_dd from global_parameters where param_name = 'param_max_loss_percentage';";	
$resultquery = mysqli_query($link, $query) or die(mysqli_error($link));
		  while($rowquery = mysqli_fetch_assoc($resultquery)) {
			  extract($rowquery);
		  }
echo "$_max_dd"; echo "'; ";  


	echo "  var max_growth='";
	$link = mysqli_connect($config['server'], $config['dbusername'], $config['dbpass']);

if (mysqli_connect_errno())
  {
  echo "ERROR Failed to connect to MySQL: " . mysqli_connect_error();
  }
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

 
mysqli_select_db($link, "binancefutures");

$query = "select param_value as _max_gr from global_parameters where param_name = 'param_max_profit_percentage';";	
$resultquery = mysqli_query($link, $query) or die(mysqli_error($link));
		  while($rowquery = mysqli_fetch_assoc($resultquery)) {
			  extract($rowquery);
		  }
echo "$_max_gr"; echo "';";  


	?>


if(parseInt(cumuusd)>parseInt(max_growth))
{

		 		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=freezesystem&freezetype=freezeonprofit',
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
     
	 		 		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=closeopenautopositions&coinsarray=',
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
     
		 
     }

   }); 
   
   
		 
     }

   }); 


}

 
if(parseInt(cumuusd)<parseInt(-parseInt(max_ddfromserver)))
{

		 		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=freezesystem&freezetype=freezeonloss',
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
     
	 		 		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=closeopenautopositions&coinsarray=',
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
     
		 
     }

   }); 
   
   
		 
     }

   }); 


}
  
cumuusd=0;		
cumudd=0;
		}, globalquotesrefreshinterval);
  
    });
})(jQuery);


   
 
 function closeposition(_this)
 {
	 globalpostionpopup = _this;
	 var isexist = $(_this).closest( "tr" ).find( ".tradeprofitloss" ).html().toString();
	 var thislen = isexist.toString().length;
	 
	 if( parseInt(thislen)>parseInt(1))
	 {
	 $('#closeposition').modal('show');
	 
	 }
 
 
	 
setTimeout(function(){ 
 try {
  var trdamt = $('tr.'+GLOBALPOSITIONHASH).find('td.tradeamountdisp').find('.trd_amnt').html();
  $('.close_dialogue_amount').html( (trdamt) + ' USDT');
  
  var trdtype = $('tr.'+GLOBALPOSITIONHASH).find('td.tradetype').find('.trdtypebold').html();
  $('.close_dialogue_type').html(trdtype);
  
  
  var trdleverage = $('tr.'+GLOBALPOSITIONHASH).find('td.tradelvg').find('.trade_leverage').html();
  $('.close_dialogue_leverage').html(trdleverage+'x');
  
  var posentry = $('tr.'+GLOBALPOSITIONHASH).find('td.tradentry').html();
  $('.close_dialogue_entry').html( (posentry)+ ' USDT');
  
    var posmarkprice = $('tr.'+GLOBALPOSITIONHASH).find('td.trademarkeprice').html();
  $('.close_dialogue_markprice').html( (posmarkprice)+ ' USDT');
  
  var posprofitloss = $('tr.'+GLOBALPOSITIONHASH).find('td.tradeprofitloss').html().toString().substr(0, $('tr.'+GLOBALPOSITIONHASH).find('td.tradeprofitloss').html().toString().indexOf('<'));
  $('.close_dialogue_profit').html( (posprofitloss) + ' USDT');
  
  
  
 var posdate = $('tr.'+GLOBALPOSITIONHASH).find('td.trd__date').find('.b_date_').html();
  $('.close_dialogue_date').html(posdate);
  
 var possymbol = $('tr.'+GLOBALPOSITIONHASH).find('td.tradesymbol').find('.thesymbol').html();
  $('.close_dialogue_symbol').html(possymbol);  
  
   var posorderid = GLOBALPOSITIONHASH;
  $('.close_dialogue_orderid').html(posorderid);  
  }
catch(err) {
  
}
  
}, 250);
	 
 }

function closepositionconfirmed()
{
	
	document.getElementById("close_pos_conf").disabled = true;
	var cumuposinfo = '<b><i>We have Manually Closed this position</i></b>';
	var table = document.getElementById('tbl_closeposition');
	var rowLength = table.rows.length;

for(var i=0; i<rowLength; i+=1){
  var row = table.rows[i];

  //your code goes here, looping over every row.
  //cells are accessed as easy

  var cellLength = row.cells.length;
  cumuposinfo=cumuposinfo+"[BRK]";
  for(var y=0; y<cellLength; y+=1){
    var cell = row.cells[y];

    if(y==0)
	{
		 cumuposinfo = cumuposinfo +"[BRK]<b>"+($(cell).html().toString())+"</b> ";
	}
	else{
		 cumuposinfo = cumuposinfo +"[BRK]<i><u>"+($(cell).html().toString())+"</u></i> ";
	}
    
  }
}
cumuposinfo= cumuposinfo+'[BRK][BRK]for more info and statistics  please visit your webportal. ';

var positionhash = $('.close_dialogue_orderid').html();
var markprice = parseFloat(removeCommas($('.close_dialogue_markprice').html()));
var tradeprofit = $('.close_dialogue_profit').html().toString().replace("USDT","");
var tradeprofit =removeCommas(tradeprofit);
 

 
		 		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=closeposition&positionhash='+positionhash+'&tradeprofit='+tradeprofit+'&markprice='+markprice+'&text='+cumuposinfo,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
      populateactivetrades();	
	  $('#closeposition').modal('hide');	
		 
     }

   });



}


function broadcastposition()
{
    //$('#coinabbr').val('');
	//$('#coinname').val('');
	//$('#img-upload').removeAttr('src');
	//$('#fileInput').val('');
$('#addnewcoinmodal').modal('show');
setTimeout(function(){
$('.sltpdiv').fadeOut();	
$('#tradingview_576df').fadeOut();	
//$('#coinabbr').focus();
$('#tbl_coins_filter').find('input').focus();

$("#buycrypto").css("opacity","0.6");
$("#sellcrypto").css("opacity","0.6");

document.getElementById("buycrypto").disabled = true;
document.getElementById("sellcrypto").disabled = true;
document.getElementById("confirmbuysell").disabled = true;


$("#confirmbuysell").css("opacity","0.6");
    	 
 }, 800);	
	
}

function displaytradingview(abr)
{
	
	setTimeout(function(){ $('#buycrypto').focus();}, 400);
	
	document.getElementById("buycrypto").disabled = false;
    document.getElementById("sellcrypto").disabled = false;

	 $('#tradingview_576df').fadeIn();	
	$("#buycrypto").css("opacity","1");
$("#sellcrypto").css("opacity","1");
	  var width = document.getElementById('tbl_coins_filter').offsetWidth;
	  var height=325;
  new TradingView.widget(
  {
  "width": width,
  "height": height,
  "symbol": abr+"USDT",
  "interval": "D",
  "timezone": "Etc/UTC",
  "theme": "light",
  "style": "1",
  "locale": "en",
  "toolbar_bg": "#f1f3f6",
  "enable_publishing": false,
  "allow_symbol_change": true,
  "container_id": "tradingview_576df"
}
  );
}

function buybtn(_digits)
{
	
 
$('.rangeamountlbl').html('Amount: 100 USDT');	
$('.rangeleveragelbl').html('Leverage: 1x');
	
$('#rangeamount').val(100);	
$('#rangeleverage').val(1);
	 
	globalordermode = OP_BUY;
	document.getElementById("confirmbuysell").disabled = false;
	$('#confirmbuysell').css("opacity","1");
	var _step=1;
	if(globaldigitdecimalplace==0) {_step=10;}
	if(globaldigitdecimalplace==1) {_step=1;}
	if(globaldigitdecimalplace==2) {_step=0.1;}
	if(globaldigitdecimalplace==3) {_step=0.01;}
	if(globaldigitdecimalplace==4) {_step=0.001;}
	if(globaldigitdecimalplace==5) {_step=0.0001;}
	if(globaldigitdecimalplace==6) {_step=0.00001;}
	if(globaldigitdecimalplace==7) {_step=0.000001;}
	if(globaldigitdecimalplace==8) {_step=0.0000001;}
	if(globaldigitdecimalplace==9) {_step=0.00000001;}
	if(globaldigitdecimalplace==10){_step=0.000000001;}
	if(globaldigitdecimalplace==11){_step=0.0000000001;}
	if(globaldigitdecimalplace==12){_step=0.00000000001;}
	if(globaldigitdecimalplace==13){_step=0.000000000001;}
	if(globaldigitdecimalplace==14){_step=0.0000000000001;}
	if(globaldigitdecimalplace==15){_step=0.00000000000001;}
	 
	$("#inputstoploss").attr('step', _step);
	$("#inputtakeprofit").attr('step', _step);
	
	
	
		 		 $.ajax({

     type: "GET",
     url: 'https://api.binance.com/api/v3/ticker/price?symbol='+globalsymbolname+"USDT",
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
      var pr = data.price;
	  GLOBALPRICE = data.price;
	  var prtp = parseInt(GLOBALTP)*parseFloat(pr)/parseInt(100);
	  var prsl = parseInt(GLOBALSL)*parseFloat(pr)/parseInt(100);
	  
	  globalstoploss = prsl;
	  globaltakeprofit = prtp;
	   $('.badgestoploss').html(GLOBALSL+' pips');
	   $('.badgetakeprofit').html(GLOBALTP+' pips');
	  if(parseInt(globaldigitdecimalplace) ==parseInt(1) || parseInt(globaldigitdecimalplace) ==parseInt(2))
	  {		  
	  prtp = prtp.toFixed(parseInt(2));
      prsl = prsl.toFixed(parseInt(2));
	  }
else
{ 
      prtp = prtp.toFixed(parseInt(globaldigitdecimalplace));
      prsl = prsl.toFixed(parseInt(globaldigitdecimalplace));
}	  
	
    // $("#inputstoploss").attr('max', prsl);	
     $("#inputtakeprofit").attr('min', 0);	
	
     $("#inputstoploss").attr('min',   0);	
   
	
	
	
	$("#inputstoploss").val(prsl);
	$("#inputtakeprofit").val(prtp);
		 
     }

   });
   
   
	
	setTimeout(function(){ $('#confirmbuysell').focus();}, 400);
	$('.sltpdiv').fadeIn();
    $('#sellcrypto').css("opacity","0.4");
	 $('#buycrypto').css("opacity","1");
	 
	 		 $.ajax({

     type: "GET",
     url: 'https://api.binance.com/api/v3/ticker/price?symbol='+globalsymbolname+"USDT",
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
	
		 
     }

   });
	 
}
function sellbtn(_digits)
{
	
	$('.rangeamountlbl').html('Amount: 100 USDT');	
$('.rangeleveragelbl').html('Leverage: 1x');
	
$('#rangeamount').val(100);	
$('#rangeleverage').val(1);


	globalordermode = OP_SELL;
	document.getElementById("confirmbuysell").disabled = false;
	$('#confirmbuysell').css("opacity","1");
	var _step=1;
	if(globaldigitdecimalplace==0) {_step=10;}
	if(globaldigitdecimalplace==1) {_step=1;}
	if(globaldigitdecimalplace==2) {_step=0.1;}
	if(globaldigitdecimalplace==3) {_step=0.01;}
	if(globaldigitdecimalplace==4) {_step=0.001;}
	if(globaldigitdecimalplace==5) {_step=0.0001;}
	if(globaldigitdecimalplace==6) {_step=0.00001;}
	if(globaldigitdecimalplace==7) {_step=0.000001;}
	if(globaldigitdecimalplace==8) {_step=0.0000001;}
	if(globaldigitdecimalplace==9) {_step=0.00000001;}
	if(globaldigitdecimalplace==10){_step=0.000000001;}
	if(globaldigitdecimalplace==11){_step=0.0000000001;}
	if(globaldigitdecimalplace==12){_step=0.00000000001;}
	if(globaldigitdecimalplace==13){_step=0.000000000001;}
	if(globaldigitdecimalplace==14){_step=0.0000000000001;}
	if(globaldigitdecimalplace==15){_step=0.00000000000001;}
	 
	$("#inputstoploss").attr('step', _step);
	$("#inputtakeprofit").attr('step', _step);
	
		 		 $.ajax({

     type: "GET",
     url: 'https://api.binance.com/api/v3/ticker/price?symbol='+globalsymbolname+"USDT",
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 
      var pr = data.price;
	  GLOBALPRICE = data.price;
	  var prsl = parseInt(GLOBALSL)*parseFloat(pr)/parseInt(100);
	  var prtp = parseInt(GLOBALTP)*parseFloat(pr)/parseInt(100);
	  
	  globalstoploss = prsl;
	  globaltakeprofit = prtp;
	  
	  
	   	  $('.badgestoploss').html(GLOBALSL+' pips');
	       $('.badgetakeprofit').html(GLOBALTP+' pips');
	  
	  if(parseInt(globaldigitdecimalplace) ==parseInt(1) || parseInt(globaldigitdecimalplace) ==parseInt(2))
	  {		  
	  prtp = prtp.toFixed(parseInt(2));
      prsl = prsl.toFixed(parseInt(2));
	  }
else
{ 
      prtp = prtp.toFixed(parseInt(globaldigitdecimalplace));
      prsl = prsl.toFixed(parseInt(globaldigitdecimalplace));
}	  
	 
  //   $("#inputstoploss").attr('min', prsl);	
   //  $("#inputtakeprofit").attr('max', prtp);	
	
	 //    $("#inputstoploss").attr('max',   +1000000000);	
         $("#inputtakeprofit").attr('min', 0);	
         $("#inputstoploss").attr('min', 0);	
	
	$("#inputstoploss").val(prtp);
	$("#inputtakeprofit").val(prsl);
		 
     }

   });
   
   
	
	setTimeout(function(){ $('#confirmbuysell').focus();}, 400);
	$('.sltpdiv').fadeIn();
    $('#sellcrypto').css("opacity","1");
	 $('#buycrypto').css("opacity","0.4");
	 
	 		 $.ajax({

     type: "GET",
     url: 'https://api.binance.com/api/v3/ticker/price?symbol='+globalsymbolname+"USDT",
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
	
		 
     }

   });
	 
}


document.getElementById('inputstoploss').oninput = function () {
        var max = parseFloat(this.max);
		var min = parseFloat(this.min);

        if (parseFloat(this.value) > max) {
            this.value = max; 
		}
			  

        if (parseFloat(this.value) < min) {
            this.value = min; 
		}
			
        }
 
document.getElementById('inputtakeprofit').oninput = function () {
        var max = parseFloat(this.max);
		var min = parseFloat(this.min);

        if (parseFloat(this.value) > max) {
            this.value = max; 
		}
			  

        if (parseFloat(this.value) < min) {
            this.value = min; 
		}
			
        }
 
	
	

 $(document).on('keyup mouseup ', '#inputstoploss', function() {    

   if(globalordermode== OP_BUY)
   {	   
       var currvalue = ($(this).val());
       currvalue=parseFloat(currvalue);
	   var newpips =  parseFloat(parseFloat(globalstoploss) - parseFloat(currvalue));
	   newpips =newpips.toFixed(globaldigitdecimalplace);
	    
	       if( parseInt(globaldigitdecimalplace)>0)
		   {
			   newpips = parseInt(GLOBALSL)+ parseFloat(newpips) * parseFloat((Math.pow(10, globaldigitdecimalplace)));
		   }
   
  
    $('.badgestoploss').html(parseInt(newpips)+' pips'); 
   
   
   }
    if(globalordermode== OP_SELL)
   {	   
       var currvalue = ($(this).val());
       currvalue=parseFloat(currvalue);
	   var newpips =  parseFloat(  parseFloat(currvalue)-parseFloat(globalstoploss));
	   newpips =newpips.toFixed(globaldigitdecimalplace);
	    
	       if( parseInt(globaldigitdecimalplace)>0)
		   {
			   newpips = parseInt(GLOBALSL)+ parseFloat(newpips) * parseFloat((Math.pow(10, globaldigitdecimalplace)));
		   }
   
  
   $('.badgestoploss').html(parseInt(newpips)+' pips'); 
   
   
   }  
  
  
   
});

 $(document).on('keyup mouseup', '#inputtakeprofit', function() {  

   if(globalordermode== OP_BUY)
   {	   
       var currvalue = ($(this).val());
       currvalue=parseFloat(currvalue);
	   var newpips =  parseFloat(parseFloat(currvalue)-parseFloat(globaltakeprofit));
	   newpips =newpips.toFixed(globaldigitdecimalplace);
	    
	       if( parseInt(globaldigitdecimalplace)>0)
		   {
			   newpips = parseInt(GLOBALTP)+ parseFloat(newpips) * parseFloat((Math.pow(10, globaldigitdecimalplace)));
		   }
   
  
   $('.badgetakeprofit').html(parseInt(newpips)+' pips'); 
   
   
   
   }
   
     if(globalordermode== OP_SELL)
   {	   
       var currvalue = ($(this).val());
       currvalue=parseFloat(currvalue);
	   var newpips =  parseFloat(parseFloat(globaltakeprofit)-parseFloat(currvalue));
	   newpips =newpips.toFixed(globaldigitdecimalplace);
	    
	       if( parseInt(globaldigitdecimalplace)>0)
		   {
			   newpips = parseInt(GLOBALTP)+ parseFloat(newpips) * parseFloat((Math.pow(10, globaldigitdecimalplace)));
		   }
   
  
   $('.badgetakeprofit').html(parseInt(newpips)+' pips'); 
   
   
   } 
   
});



 



setInterval(function(){ 

 

if($('#closeposition').is(':visible'))
{
	 
	closeposition (globalpostionpopup);
}
 }, 1000);
 
 
 setInterval(function(){ 


		 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=countopenpositions',
     data: '', // appears as $_GET['id'] @ your backend side
     success: function(data) {


 
 if(parseInt(data) != parseInt(TOTALTRADES))
	   {
		   TOTALTRADES = parseInt(data);
		   populateactivetrades();	
	   }	 
     }

   });	
   
   



	/*	 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=changevpsstatus',
     data: '', // appears as $_GET['id'] @ your backend side
     success: function(data) {


 
  
     }

   });	*/
   
   


 
 
 }, 2500);
 
 
</script>
	
	<!--<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">-->
	
</body>

</html>