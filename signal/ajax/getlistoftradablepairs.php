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
 
 
print <<<here

     <table  style='cursor:pointer;' class="table table-bordered" id="tbl_coins" width="100%" cellspacing="0">
								 <caption style='zoom:0.94;'> <ul><li>Coins that are listed in futures trading</li></ul></caption>
                                    <thead>
                                        <tr>
										    <th>Coin</th>
											
										    <th>Name</th>
										    <th>Digits</th>
										    <th>Logo</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                     
                                        </tr>
                                    </thead>
                           
                                    <tbody>
									
here;

 
$query = "select coin_id,coin_digits, coin_abr, coin_logo, coin_fullname from coin_name where coin_abr <>'USDT'  and  coin_isvisible = '1' order by coin_id desc ";										
 
 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
 
   while($row = mysqli_fetch_assoc($result)) {
	   
				extract($row);
				echo "<tr> <td>$coin_abr</td></td><td>$coin_fullname</td><td>$coin_digits</td><td style='text-align:center;'><img src='$coin_logo' style='max-width:25px;max-height:25px;' /><td style='text-align:center;'onclick='_edit(\"$coin_id\")'>Edit</td><td style='text-align:center;'onclick='_delete(\"$coin_id\",\"$coin_abr\")';'>Delete</td>";
				
			}
			

print <<<here
                                      
                                 
                                        
                                      
                                    </tbody>
                                </table>
here;





?>