 

<?php
session_start();
include("../../libraries/config.inc.php");
 

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
mysqli_select_db($link, "binancefutures");



$actionmode = $_GET['actionmode'];

if($actionmode == "getcoins")
{
 $query = "select count(coin_id) as totalcoins from coin_name where coin_isvisible= '1' and coin_abr <> 'USDT'";
  if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
   while($row = mysqli_fetch_assoc($result)) {
	   
	   extract($row);
       echo $totalcoins;exit;
   }			
}	
 
 
 if($actionmode == "getsinglecoin")
{
 		$query = "select coin_id, coin_abr, coin_logo, coin_fullname, coin_digits, coin_isvisible from coin_name where coin_isvisible= '1' and coin_abr <> 'USDT' limit  ".$_GET["limit"] . ",1";
 
 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
 
   while($row = mysqli_fetch_assoc($result)) {
	   
	   extract($row);
	   echo $coin_abr;exit;
     
   }
}	








?>