<?php
session_start();
include("../libraries/config.inc.php");
if($_SESSION['admin']==""){
header("location:index.php");
}
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
 <style type='text/css'>
@keyframes zoomEffect {
0% {
color:white;
background:lightgreen;
 
}
50% {
color:magenta;
 background:purple;
}
100% {
color:grey;
background:coral; 
}
}
.infinitelooop
{
	animation: 11s ease 0s normal none infinite running zoomEffect;
}
	</style>
	

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Coins Listing</title>
<style type='text/css'>
table tbody tr:hover {
       background-color: #8e32d44d;
       cursor: pointer;
   }
 
 
.form-control
{
background: transparent !important;
    border: 0px !important;	
}

.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}

#img-upload{
    max-width: 125px !important;
    max-height: 125px !important;
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
	<script>
	  var coin_id = 0;
	  var globalmode='add';
	</script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
             <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="main.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Binance <sup>LeaderBoard Copier</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item ">
                <a class="nav-link" href="main.php">
                    <i class="fas fa-fw fa-pen"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Panel
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="tradeshistory.php">
                    <i class="fas fa-fw fa-reply"></i>
                    <span>Trades History</span></a>
            </li>
 
             <!-- <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-broadcast-tower"></i>
                    <span>Broadcast Message</span></a>
            </li>-->
			
			              <li class="nav-item active">
                <a class="nav-link" href="tradablepairs.php">
                    <i class="fas fa-fw fa-coins"></i>
                    <span>Tradable Pairs</span></a>
            </li>
			
			
            <!-- Divider -->
         
            <!-- Heading -->
            <div class="sidebar-heading">
                Settings
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
 

            <!-- Nav Item - Charts -->
						            <li class="nav-item">
                <a class="nav-link" href="manageclients.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Create API</span></a>
            </li>
			
            <li class="nav-item">
                <a class="nav-link" href="profileandsecurity.php">
                    <i class="fas fa-fw fa-hammer"></i>
                    <span>Profile and Security</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item ">
                <a class="nav-link" href="globalparameters.php">
                    <i class="fas fa-fw fa-question-circle"></i>
                    <span>Global Parameters</span></a>
            </li>
			
			<li class="nav-item ">
                <a class="nav-link infinitelooop"  href="donation.php">
                    <i class="fas fa-heart 	" style = 'zoom:1.3;'></i>
                    <span style='text-transform:uppercase;'>Donate</span></a>
            </li>
			
			

            <li class="nav-item ">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-fw fa-door-closed"></i>
                    <span>Logout</span></a>
            </li>
 
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

      

        </ul>
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
                        <h1 class="h3 mb-0 text-gray-800">Tradable Pairs</h1>
 
                    </div>

                    <!-- Content Row -->
             
 
                    <!-- Content Row -->

 
                    <!-- Content Row -->
                     <div class="container-fluid">
					     <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"><button style='zoom:0.92;' type="button" class="btn btn-success" onclick="addnewcoin();">Add A New Coin</button></h6>
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
	
	
	
	    <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="deletemodallabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletemodallabel">Delete this coin?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Please confirm by clicking OK</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" onclick="_confirmdelete();">OK</a>
                </div>
            </div>
        </div>
    </div>
	
	
		    <div class="modal fade" id="addnewcoinmodal" tabindex="-1" role="dialog" aria-labelledby="addnewcoinlabel"
        aria-hidden="true">
		
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addnewcoinlabel">Add New Coin</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
				<input autocomplete="off" type='text' id='coinabbr' style='width:95%;margin:0 auto;' placeholder='Coin Abbr ...'/><hr style='visibility:hidden;'>
				<input autocomplete="off" type='text' id='coinname' style='width:95%;margin:0 auto;' placeholder='Coin Name ...'/><hr style='visibility:hidden;'>
			    <input autocomplete="off" type='number' id='coindigits' min='1' max='50' style='width:95%;margin:0 auto;' placeholder='Coin Digits ...'/><hr style='visibility:hidden;'>
<div class="col-md-6">
    <div class="form-group">
        <label>Upload Image</label>
        <div class="input-group">
            <span class="input-group-btn">
                <span class="btn btn-default btn-file">
                    Browse… <input type="file" id="imgInp">
                </span>
            </span>
            <input type="text" class="form-control" id='fileInput' readonly>
        </div>
        <img id='img-upload'/>
    </div>
				
				</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" onclick="return _confirmupload(document.getElementById('coinabbr').value,document.getElementById('coinname').value ,document.getElementById('coindigits').value );">OK</a>
                </div>
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
    
	<script src="../js_css/backend/vendor/datatables/jquery.dataTables.js"></script>

    <!-- Page level custom scripts -->
   
<script>
 

function addnewcoin()
{
	globalmode='add';
	$("#coinabbr").prop("readonly", false);
	$('#coinabbr').val('');
	$('#coinname').val('');
	$('#coindigits').val('');
	$('#img-upload').removeAttr('src');
	$('#fileInput').val('');
$('#addnewcoinmodal').modal('show');
setTimeout(function(){ 
$('#coinabbr').focus();
 }, 860);	
}

function _edit(coinid)
{
	globalmode='edit';
  	$("#coinabbr").prop("readonly", true);
	 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=getcoindata&coin_id='+coinid,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
   var res = data.split("{}");
     var coinabr = res[0];
	 var coinimage = res[1];
	 var coinfullname = res[2];
	 var coindigits = res[3];
   $('#coinabbr').val(coinabr);
   $('#coinname').val(coinfullname);
   $('#coindigits').val(coindigits);
   
    $('#img-upload').attr('src', coinimage);
   $('#addnewcoinmodal').modal('show');
     }

   });
   
$('#addnewcoinmodal').modal('show');
setTimeout(function(){ 
$('#coinname').focus();
 }, 860);	
}


function _delete(coinid,coinname)
{
	coin_id = coinid;
  $('#deletemodal').modal('show');
}
function _confirmdelete()
{
	 //alert(coin_id);
		theurl = '';
	 
	 $.ajax({

     type: "GET",
     url: 'ajax/listener.php?actionmode=deletecoin&coin_id='+coin_id,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
 
		  populateactivetrades();
		  $('#deletemodal').modal('hide');
     }

   });

	
}

 
function _confirmupload(abbr,name,digits)
{
	
	  if(($('#coinabbr').val().trim() == ''))
	  {
		  $('#coinabbr').focus();
		  return false;
	  }
	  
	  	  if(($('#coinname').val().trim() == ''))
	  {
		  $('#coinname').focus();
		  return false;
	  }
	  
	  	  if(($('#coindigits').val().trim() == ''))
	  {
		  $('#coindigits').focus();
		  return false;
	  }
	 //alert(coin_id);
		theurl = '';
	 
 var _image = $('#img-upload').prop('src');

 
 
if(globalmode=='add')
{
	$.ajax({
  type: 'POST',
  url: 'ajax/listener.php?actionmode=addnewcoin&coinabbr='+abbr+'&coinname='+name+'&coindigits='+digits,
  data: _image,
  contentType: 'application/my-binary-type', // set accordingly
  processData: false
});

}

if(globalmode=='edit')
{
	$.ajax({
  type: 'POST',
  url: 'ajax/listener.php?actionmode=addnewcoin&coinabbr='+abbr+'&coinname='+name+'&coindigits='+digits,
  data: _image,
  contentType: 'application/my-binary-type', // set accordingly
  processData: false
});

}

  populateactivetrades();
 $('#addnewcoinmodal').modal('hide');
	
}

$(document).ready(function()
{
	  populateactivetrades();
	  
	  
	  	$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {
		    
		    var input = $(this).parents('.input-group').find(':text'),
		        log = label;
		    
		    if( input.length ) {
		        input.val(log);
		    } else {
		        if( log ) alert(log);
		    }
	    
		});
		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#img-upload').attr('src', e.target.result);
		        }
		        
		        reader.readAsDataURL(input.files[0]);
		    }
		}

		$("#imgInp").change(function(){
		    readURL(this);
		}); 	
		
		
});

	function populateactivetrades()
{
	 
  
	theurl = '';
	 
	 $.ajax({

     type: "GET",
     url: 'ajax/getlistoftradablepairs.php',
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
           // data is ur summary
          $('#populateactivepositions').html(data);
		 
		  
		  var table = $('#tbl_coins').DataTable({
			  "aaSorting": [],
			    "pageLength" : 25,
   'aoColumnDefs': [{
        'bSortable': false,
        'aTargets': [-1,-2] /* 1st one, start by the right */,
    }]
});

		  
		 
     }

   });
}
   
   
   		(function($) {
    $(document).ready(function() {
		setInterval(function(){ 
		
		  $( ".tradesymbol" ).each(function( index ) {
         var symname = $(this).html();
		 var nearestmarkeprice = $(this).closest( "tr" ).find( ".trademarkeprice" );
		 var nearesttradeprofitloss = $(this).closest( "tr" ).find( ".tradeprofitloss" );
		 var nearesttradetype= $(this).closest( "tr" ).find( ".tradetype" );
		 var nearesttradeentry= $(this).closest( "tr" ).find( ".tradentry" );
		 	theurl = '';
	 
	 $.ajax({

     type: "GET",
     url: 'https://api.binance.com/api/v3/ticker/price?symbol='+symname,
     data: theurl, // appears as $_GET['id'] @ your backend side
     success: function(data) {
		 var pricedata=data.price;
		 var profit =0;
		   pricedata = parseFloat(pricedata);
             $(nearestmarkeprice).html(pricedata);
			 
			 if($(nearesttradetype).html().indexOf("BUY") >= 0)
			 {
				 profit = parseFloat($(nearestmarkeprice).html())-parseFloat($(nearesttradeentry).html());
				 profit= parseFloat(profit)*parseFloat(2);
				if(parseFloat(profit)>parseFloat(0))
				{
					 $(nearesttradeprofitloss).css("color","lightgreen");
				}
				else
				{
						 $(nearesttradeprofitloss).css("color","#d43432");
				}
			 }

			 if($(nearesttradetype).html().indexOf("SELL") >= 0)
			 {
				  profit = parseFloat($(nearesttradeentry.html()))-parseFloat($(nearestmarkeprice.html()));
				  profit= parseFloat(profit)*parseFloat(2);
				  	if(parseFloat(profit)>parseFloat(0))
				{
					 $(nearesttradeprofitloss).css("color","lightgreen");
				}
				else
				{
						 $(nearesttradeprofitloss).css("color","#d43432");
				}
			 }
			 
			 var gainpercentage = parseFloat(profit)*parseFloat(0.01);
			 gainpercentage=parseFloat(gainpercentage).toFixed(2)
			 $(nearesttradeprofitloss).html( parseFloat(profit).toFixed(2) + '  ' +gainpercentage +'%');
			 
			 
			 
		 
     }

   });



		  
});

		
		}, 1000);
  
    });
})(jQuery);


   
 

</script>
</body>

</html>