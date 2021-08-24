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
							 
                                    <thead>
                                        <tr>
										    <th>Param Name</th>
											
										    <th>Param Value</th>
									 
                                            <th>&nbsp;</th>
                                     
                                        </tr>
                                    </thead>
                           
                                    <tbody>
									
here;

 
$query = "select * from global_parameters where param_isvisible = 'y'";										
 
 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
 
   while($row = mysqli_fetch_assoc($result)) {
	   
				extract($row);
				if($param_name == "param_freezed_until" )
				{
					if(strlen($param_value) > 3)
					{
					    $sql = "SELECT  FROM_UNIXTIME(param_value) as dtfmt FROM global_parameters WHERE param_name='param_freezed_until';";
					    $resultsql = mysqli_query($link, $sql) or die(mysqli_error($link));
			 
 
   while($rowsql = mysqli_fetch_assoc($resultsql)) {
	   
				extract($rowsql);
   }
			 
					echo "<tr title = '$dtfmt'> <td>$param_name</td><td>$dtfmt</td><td style='text-align:center;'>&nbsp;</td></tr>";
					
				}
				else
				{
							echo "<tr > <td>$param_name</td><td>$param_value</td><td style='text-align:center;'>&nbsp;</td></tr>";
				}
				}
				else
				{
				echo "<tr> <td>$param_name</td><td>$param_value</td><td style='text-align:center;'onclick='_modify(\"$param_name\",\"$param_value\")';'>Edit</td></tr>";
				}
			}
			

print <<<here
                                      
                                 
                                        
                                      
                                    </tbody>
                                </table>
here;





?>