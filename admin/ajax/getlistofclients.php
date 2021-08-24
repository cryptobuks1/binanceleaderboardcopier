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
 
 
print <<<here

     <table  style='cursor:pointer;' class="table table-bordered" id="tbl_coins" width="100%" cellspacing="0">
						 
                                    <thead style='zoom:0.65;'>
                                        <tr>
										    <th>Email</th>
										    <th>Pass</th>
										    <th>Binance API Key</th>
										    <th>Binance API Secret</th>
                                            <th>Binance Leverage</th>
                                            <th>Binance Lot Amout /Trade</th>
                                            <th>Enable Futures</th>
                                            <th>&nbsp;</th>
                                           
                                     
                                        </tr>
                                    </thead>
                           
                                    <tbody>
									
here;

 
$query = "select * from tbl_client ";										
 
 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
 
   while($row = mysqli_fetch_assoc($result)) {
	   
				extract($row);
				echo "<tr> <td>$client_email</td></td><td>$client_pass</td><td>$binance_apikey</td><td>$binance_secretkey</td><td>$binance_leverage</td><td>$binance_lotamountpertrade</td><td>$client_enablefutures</td><td style='text-align:center;'onclick='_edit(\"$client_id\")'>Edit</td></tr>";
				
			}
			

print <<<here
                                      
                                 
                                        
                                      
                                    </tbody>
                                </table>
										<div class="form-group"> 
					<div id='howto' style='width:100%;text-align:left !important;color:black;text-align:left;display:block;'>
					 <br><br>
					 
					
					<div class="doc-copy">
   
 
<h4>Creating API KEY and SECRET KEY</h4>

<ol style = 'zoom:0.79;'>
<li>Log into your Binance account and go to the account settings -&gt; API Management page where you can create a new API key.</li>
<li> make sure  you enable 'Enable Trading', and 'Enable Futures' are enabled.</li>
<li>Once your keys are created, make a note of the API Key and Secret Key.</li>
<li>Transfer some USDT balance from spot to futures.</li>
</ol>
 
 </div>

					
					</div>
						 
					</div>
here;





?>