<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

session_start();
include("../../libraries/config.inc.php");
if($_SESSION['customer']==""){
header("location:index.php");
}

function generateRandomString($length = 15, $abc = "0123456789abcdfghknqrstvwxyz") //NEVER CHANGE $abc values
{
    return substr(str_shuffle($abc), 0, $length);
}
function GetSQLValueString($theValue, $theType) 
{	
  $theValue = trim($theValue);	
 ////// $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

   
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
mysqli_select_db($link, "binancefutures");
 
 
$actionmode = $_GET['actionmode'];

if($actionmode == "deletecoin")
{
	$coind_id = $_GET["coin_id"];
	$query = "update coin_name set coin_isvisible = '0' where coin_id=$coind_id";
	if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	         $result = mysqli_query($link, $query) or die(mysqli_error($link));
}

 if($actionmode == "countopenpositions")
{
	 
	$query = "select count(trade_id) as totaltrades from tbl_trades where trade_closedat IS NULL";
	if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	       	 $result = mysqli_query($link, $query) or die(mysqli_error($link));
		    while($row = mysqli_fetch_assoc($result)) {
	   
				extract($row);
				echo $totaltrades;
			}
}



if ($actionmode=="getcoindata")
{
	$coind_id = $_GET["coin_id"];
 	$query = "select coin_id, coin_abr, coin_logo, coin_fullname, coin_digits from coin_name where coin_id=$coind_id";
 
	 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
    while($row = mysqli_fetch_assoc($result)) {
	       
				extract($row);
				 
				echo "$coin_abr{}$coin_logo{}$coin_fullname{}$coin_digits";
				
			}
			 

}



if ($actionmode=="populateclientinfo")
{
	$client_id = $_GET["clientid"];
 	$query = "select client_id, client_email, client_pass, binance_apikey, binance_secretkey, binance_leverage, binance_lotamountpertrade, client_enablefutures
	from tbl_client where client_id=$client_id";
 
	 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
    while($row = mysqli_fetch_assoc($result)) {
	       
				extract($row);
				 
				echo "$client_id{}$client_email{}$client_pass{}$binance_apikey{}$binance_secretkey{}$binance_leverage{}$binance_lotamountpertrade{}$client_enablefutures";
				
			}
			 

}


 

 


?>