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
 
 
 
 	$queryest = "select SUM(trade_profit) estimated__pnl from tbl_trades where     trade_profit is not null  ";
	
	if( intval($_GET['interval'])> intval(1))
{
	$queryest.=" and trade_closedat > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ".$_GET["interval"]." DAY))";
}


	if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	       	 $resultest = mysqli_query($link, $queryest) or die(mysqli_error($link));
		    while($rowest = mysqli_fetch_assoc($resultest)) {
	   
				extract($rowest);
				 $estimated__pnl = number_format($estimated__pnl,2);
				 
			}
			
			
print <<<here

     <table   style='cursor:pointer;' class="table table-bordered" id="tbl_openedpositions" width="100%" cellspacing="0">
 	 <caption style='zoom:0.94 !important;text-align:center;'> 
								 
								 
 <ul class="list-group list-group-horizontal" style='color:b;font-weight:bolder;width:100%;'>

  <li class="list-group-item" style='font-size:small;width:100%;background:transparent;'>Estimated PNL : <b style='zoom:1.21;' class='cumu_usd'>$estimated__pnl USDT</b></li>
 
  
</ul>
								 
								 </caption>
                                    <thead>
                                        <tr>
										    <th>Date</th>
                                            <th>Sym</th>
                                            <th>Amount</th>
											<th>Type</th>
                                            <th>Lev</th>
                                            <th>Entry</th>
                                    
                                            <th>Close Price</th>
                                            <th>Profit/Loss</th>
 
                                        </tr>
                                    </thead>
                           
                                    <tbody>
									
here;

 
$query = "select trade_id, trade_symbol, trade_leverage, trade_entryprice, trade_closedprice,
 trade_openedat, trade_closedat, trade_profit, trade_hash, trade_type,
 trade_coin_digits, trade_amount, trade_sl, trade_tp, trade_closereasons, trade_by, trade_estimatedloss, trade_estimatedprofit
 ,DATE_FORMAT(FROM_UNIXTIME(trade_openedat), '%e %b %Y <br>(%H:%i:%s)')
AS 'date_trade' , DATE_FORMAT(FROM_UNIXTIME(trade_closedat), '%e %b %Y <br>(%H:%i:%s)')
AS 'date_tradeclosed' from tbl_trades where trade_closedat IS NOT NULL";

if( intval($_GET['interval'])> intval(1))
{
	$query.=" and trade_closedat > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ".$_GET["interval"]." DAY))";
}

$query.=" order by trade_openedat desc ";										

 
 
 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
 
   while($row = mysqli_fetch_assoc($result)) {
	   
				extract($row);

                 // Trade Amount //
				 $trade_amount=number_format($trade_amount,10);
				
				while( (strpos($trade_amount, '.') !== false) && substr($trade_amount, -1) =="0" )
				{
					$trade_amount =  substr($trade_amount, 0, -1);
				}
				if(substr($trade_amount, -1) ==".") {$trade_amount =  substr($trade_amount, 0, -1);}
				 // End Trade Amount //
				 

                 // Trade Entry //
				 $trade_entryprice=number_format($trade_entryprice,10);
				
				while( (strpos($trade_entryprice, '.') !== false) && substr($trade_entryprice, -1) =="0" )
				{
					$trade_entryprice =  substr($trade_entryprice, 0, -1);
				}
				if(substr($trade_entryprice, -1) ==".") {$trade_entryprice =  substr($trade_entryprice, 0, -1);}
				 // End Trade Entry //


                 // Trade SL //
				 $trade_sl=number_format($trade_sl,10);
				
				while( (strpos($trade_sl, '.') !== false) && substr($trade_sl, -1) =="0" )
				{
					$trade_sl =  substr($trade_sl, 0, -1);
				}
				if(substr($trade_sl, -1) ==".") {$trade_sl =  substr($trade_sl, 0, -1);}
				 // End Trade SL //


                 // Trade TP //
				 $trade_tp=number_format($trade_tp,10);
				
				while( (strpos($trade_tp, '.') !== false) && substr($trade_tp, -1) =="0" )
				{
					$trade_tp =  substr($trade_tp, 0, -1);
				}
				if(substr($trade_tp, -1) ==".") {$trade_tp =  substr($trade_tp, 0, -1);}
				 // End Trade TP //


 

				 if($trade_profit>=0)
				 {
					 $prcolor = "lightgreen";
				 }
				 else
				 {
					  $prcolor = "coral";
				 }
				
				echo "<tr > 
				<td class='trd__date'><b class='b_date_'>$date_trade <br>until<br> $date_tradeclosed</b> <br>
				<p style='zoom:1.13;font-size:small;'><i>Order Id:<br><u>$trade_hash</u>
				</i></p><input type='hidden' class='coin_leverage' value='$trade_leverage' />
				<input type='hidden' class='trade_amount' value='$trade_amount' />
				<input type='hidden' class='coin_digits' value='$trade_coin_digits' /> 
				</td>
				<td class='tradesymbol'><p style='margin-bottom: 0rem !important;' class='thesymbol'>$trade_symbol</p>
			    </td> 
				<td class='tradeamountdisp'>
				<b class='trd_amnt'>$trade_amount</b>
				<br> 
				<p style='font-size:x-small;'>
				<b ></b> 
				<b><i class='lqd_level' style='color:black;font-size:x-small;'></i></b>
				</p>
				</td> 
				<td class='tradetype'><b class='trdtypebold'>$trade_type</b></td> 
				<td class='tradelvg'><b class='trade_leverage'>$trade_leverage</b> x</td>
				<td class='tradentry'>$trade_entryprice</td>
				<td class='tradentry'>$trade_closedprice</td>
				<td class='tradentry' style='color:$prcolor;'>$trade_profit USDT</td>
 
 ";
				
			}
			

print <<<here
                                      
                                 
                                        
                                      
                                    </tbody>
                                </table>
here;





?>