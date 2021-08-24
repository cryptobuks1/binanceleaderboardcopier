<?php
session_start();
include("../../libraries/config.inc.php");
if($_SESSION['customer']==""){
header("location:index.php");
}

function generateRandomString($length = 15, $abc = "0123456789abcdefghijklmnopqrstuvwxyz") //NEVER CHANGE $abc values
{
    return substr(str_shuffle($abc), 0, $length);
}
function GetSQLValueString($theValue, $theType) 
{	
  $theValue = trim($theValue);	
  //////$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

   
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
 
 $querycnt = "select count(coin_id) tradableinstruments from coin_name where coin_isvisible ='1'";										
  if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $resultcnt = mysqli_query($link, $querycnt) or die(mysqli_error($link));
			   while($row = mysqli_fetch_assoc($resultcnt)) {
	   
				extract($row);
			 
			}
			
print <<<here

     <table  style='cursor:pointer;' class="table table-bordered" id="tbl_coins" width="100%" cellspacing="0">
							<caption style='zoom:0.94;'> <ul><li><b>$tradableinstruments</b> Tradable instruments found</li></ul></caption>	 
                                    <thead>
                                        <tr>
										    <th>Coin</th>
											
										    <th>Name</th>
										    <th>Logo</th>
                                            
                                     
                                        </tr>
                                    </thead>
                           
                                    <tbody>
									
here;

 
$query = "select coin_digits, coin_id, coin_abr, coin_logo,(select coin_logo from coin_name where coin_abr='USDT') usdt_logo ,coin_fullname from coin_name where coin_abr <>'USDT' and coin_isvisible = '1' order by coin_id desc ";										
 
 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
 
   while($row = mysqli_fetch_assoc($result)) {
	   
				extract($row);
				echo "<tr onclick ='disablebuysellhidesltp();globalsym(\"$coin_abr\");globaldecimal(\"$coin_digits\");displaytradingview(\"$coin_abr\");'> <td>$coin_abr/USDT</td></td><td>$coin_fullname vs USDT</td><td style='text-align:center;'><img src='$coin_logo' style='max-width:20px;max-height:20px;' /> <img src='$usdt_logo' style='max-width:20px;max-height:20px;' /> ";
				
			}
			

print <<<here
                                      
                                 
                                        
                                      
                                    </tbody>
                                </table>
here;





?>