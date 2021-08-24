<?php
include "../ccxt-master/ccxt.php";
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

session_start();
include("../../libraries/config.inc.php");
//if($_SESSION['admin']=="" && ($_GET["actionmode"] != "closeopenautopositions" && $_GET["actionmode"] != "opennewposition" && $_GET["actionmode"] != "vpsclose" && $_GET["actionmode"] != "closeposition" && $_GET["actionmode"] != "countopenpositions")  ){
//header("location:index.php");
//}

function generateRandomString($length = 15, $abc = "0123456789abcdfghknqrstvwxyz") //NEVER CHANGE $abc values
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



$globalparams = "select param_name,param_value from global_parameters";
$resultglobalparams = mysqli_query($link, $globalparams) or die(mysqli_error($link));

  while($rowglobalparams = mysqli_fetch_assoc($resultglobalparams)) {
	       
				extract($rowglobalparams);
				$$param_name = $param_value; // $$ instead of $ its the pointer
				 
			 
				
			}


 
			
 
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


if($actionmode == "freezesystem")
{
		if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
  $currtime = getCurrentTimeStamp($link);	
  
  
 // $currtime = strtotime("+".$_GET["freezetime"]." h", $currtime);
 
 $freezetype = $_GET["freezetype"];
 $queryfreeze = "";
 if ($freezetype == "freezeonprofit") { $queryfreeze = "select param_value as getfr from global_parameters where param_name = 'param_on_profit_freeze_hours'";}
 if ($freezetype == "freezeonloss") { $queryfreeze = "select param_value as getfr from global_parameters where param_name = 'param_on_loss_freeze_hours'";}
 if ($freezetype == "closealltrades") { $queryfreeze = "select param_value as getfr from global_parameters where param_name = 'param_on_client_close_all_trades_freeze_hours'";}
  
  $resultfreeze = mysqli_query($link, $queryfreeze) or die(mysqli_error($link));
		    while($rowfreeze = mysqli_fetch_assoc($resultfreeze)) {
   			extract($rowfreeze);
			}
 
  
  $currtime = intval($currtime)+intval($getfr*3600);
  $queryupdate = "update global_parameters set param_value = '$currtime' where param_name = 'param_freezed_until'";
  $result = mysqli_query($link, $queryupdate) or die(mysqli_error($link));

}

if($actionmode == "closeopenautopositions")
{
	
	 




					 
					 
	 $array =$_GET['coinsarray'];
	 $array = Explode(",",$array);


 $array2 =$_GET['coinsarray'];
 $array2 = Explode(",",$array2);
 $concattoclose = "";
 foreach($array2 as $value2){
	 $concattoclose = $concattoclose."'".$value2."'".",";
	 $concattoclose = str_replace("-", "", "$concattoclose");
	 //echo $concattoclose;exit;
 }
 $concattoclose= substr( $concattoclose, 0, -1);
  
 $querycloseclosedpositions = "select trade_id,trade_amount,trade_leverage,trade_entryprice,trade_type ,trade_symbol  from tbl_trades where trade_by = 'ROBOTNOTHAND' and trade_closedat is null and trade_symbol not in(	$concattoclose) ";
 
 
 
if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	       	 $resultclose = mysqli_query($link, $querycloseclosedpositions) or die(mysqli_error($link));
			 $num_rows = mysqli_num_rows($resultclose);
			  
   if( intval($num_rows)>intval(0))
   {  
	   		    while($rowclose = mysqli_fetch_assoc($resultclose)) {
	   
				extract($rowclose);
if($trade_type == "BUY")
{
	openclosebinancetrade($link,$trade_symbol,false,"BUY");
	
  
	                 $_urlsym ="https://api.binance.com/api/v3/ticker/price?symbol=".$trade_symbol;
	                 
	                 $jsonsym = file_get_contents($_urlsym);
                     $objsym = json_decode($jsonsym);
                     $currprice =  $objsym->price; 
					 
					 
	if($currprice>$trade_entryprice)
	{
	    
 
$reversedentry =floatval($trade_entryprice)-floatval($currprice);
$_100per100 = 	floatval($currprice);	
$perc = 	(floatval($reversedentry)*floatval(100)/floatval($currprice))*floatval($trade_leverage);
$profitinusd = ((floatval($perc)*floatval($trade_amount)) /floatval(100));
$profitinusd =-(abs(floatval($profitinusd)));
$profitinusd=floatval(2)*floatval(( abs($profitinusd)));

$queryclose2="update tbl_trades set trade_closedprice=$currprice,trade_closedat=  (select UNIX_TIMESTAMP(current_timestamp())),trade_profit=$profitinusd,trade_closereasons='CLOSEDBYPROVIDER' where trade_id=$trade_id";
 
  if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $resultclose2 = mysqli_query($link, $queryclose2) or die(mysqli_error($link));
			

	}
	else
	{
 							
$reversedentry =floatval($trade_entryprice)-floatval($currprice);
$_100per100 = 	floatval($currprice);	
$perc = 	(floatval($reversedentry)*floatval(100)/floatval($currprice))*floatval($trade_leverage);
$profitinusd = ((floatval($perc)*floatval($trade_amount)) /floatval(100));
$profitinusd=floatval(1)*floatval(-( abs($profitinusd)));

$queryclose2="update tbl_trades set trade_closedprice=$currprice,trade_closedat=  (select UNIX_TIMESTAMP(current_timestamp())),trade_profit=$profitinusd,trade_closereasons='CLOSEDBYPROVIDER' where trade_id=$trade_id";
 
  if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $resultclose2 = mysqli_query($link, $queryclose2) or die(mysqli_error($link));
			
	}
}

if($trade_type == "SELL")
{
	openclosebinancetrade($link,$trade_symbol,false,"SELL");
      $_urlsym ="https://api.binance.com/api/v3/ticker/price?symbol=".$trade_symbol;
	                 $jsonsym = file_get_contents($_urlsym);
                     $objsym = json_decode($jsonsym);
                     $currprice =  $objsym->price; 
                     
	 if($trade_entryprice>$currprice)
	 {
							
$reversedentry =floatval($currprice)-floatval($trade_entryprice);
$_100per100 = 	floatval($trade_entryprice);	
$perc = 	(floatval($reversedentry)*floatval(100)/floatval($trade_entryprice))*floatval($trade_leverage);
$profitinusd = ((floatval($perc)*floatval($trade_amount)) /floatval(100));
$profitinusd =-(abs(floatval($profitinusd)));
$profitinusd=floatval(2)*floatval(( abs($profitinusd)));

$queryclose2="update tbl_trades set trade_closedprice=$currprice,trade_closedat=  (select UNIX_TIMESTAMP(current_timestamp())),trade_profit=$profitinusd,trade_closereasons='CLOSEDBYPROVIDER' where trade_id=$trade_id";
 
  if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $resultclose2 = mysqli_query($link, $queryclose2) or die(mysqli_error($link));
				 
	 }
	 else
	 {
 
		 
$reversedentry =floatval($currprice)-floatval($trade_entryprice);
$_100per100 = 	floatval($trade_entryprice);	
$perc = 	(floatval($reversedentry)*floatval(100)/floatval($trade_entryprice))*floatval($trade_leverage);
$profitinusd = ((floatval($perc)*floatval($trade_amount)) /floatval(100));
$profitinusd=floatval(1)*floatval(-( abs($profitinusd)));

$queryclose2="update tbl_trades set trade_closedprice=$currprice,trade_closedat=  (select UNIX_TIMESTAMP(current_timestamp())),trade_profit=$profitinusd,trade_closereasons='CLOSEDBYPROVIDER' where trade_id=$trade_id";
 
  if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $resultclose2 = mysqli_query($link, $queryclose2) or die(mysqli_error($link));
		
		
		
	 }
}


				
			}

   }

foreach($array as $value){
 
 
  
 
 
 if(getCurrentTimeStamp($link) > getFreezed_until($link))
 {
	 
	 
  $_url = "https://api.binance.com/api/v1/klines?symbol=" .$value."&interval=1d&limit=1";
  
  $json = file_get_contents($_url);
  $obj = json_decode($json);
  $_open1day = floatval($obj[0][1]);
  $_close1day = floatval($obj[0][4]);
  $daychangeper_1day = floatval(0);
  $daychangeper_1day = (floatval($_close1day) * floatval(100)) / floatval($_open1day);
 
  if (floatval($daychangeper_1day) >= floatval(100)) {
      $daychangeper_1day = floatval($daychangeper_1day) - floatval(100);
   }
   else
	   {
          $daychangeper_1day = floatval(-  (100 - floatval($daychangeper_1day)));
		  
      }
	   $__isbuy =true;
	  
	 // echo $value . "asdf";exit;
	  if(strpos($value, "-") !== false){
		$__isbuy=false;
	  
	  }
	  
	  
	if( $__isbuy ==true)
	{
 
       $query = "select count(trade_id) as trade_openedandexist from tbl_trades where trade_symbol = '$value' and trade_by = 'ROBOTNOTHAND' and   trade_closedat is null ";
       	   
	   if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	       	 $result = mysqli_query($link, $query) or die(mysqli_error($link));
		    while($row = mysqli_fetch_assoc($result)) {
				 
				  $rndm=generateRandomString(10);
	   
				extract($row);
				if( $trade_openedandexist  == "0")
				{
				 
				  	 $_url2 ="https://api.binance.com/api/v3/ticker/price?symbol=".$value;
	                 $json2 = file_get_contents($_url2);
                     $obj2 = json_decode($json2);
                     $marketprice =  $obj2->price; 
      
                     if( !  is_numeric($marketprice)==true   )
	                {
		               exit;
	                }
					$val2 =  str_replace('USDT','',$value);
					$queryselectcoindigits = "select coin_digits as cn_digits from coin_name where coin_abr='$val2'";
				 
					if(! $link ) {
                         die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
                              }
		 
	       	              $resultdigits = mysqli_query($link, $queryselectcoindigits) or die(mysqli_error($link));
						 
		               while($rowdigits = mysqli_fetch_assoc($resultdigits)) {
	 
				              extract($rowdigits);
				             
							 
			         }
			
			           $queryinsert = "";
					   					 if($param_enablerobot=="true")
					 {
							  		   $chkexistincoins = str_replace("USDT", "", $value);
							
				            $queryselectcnname = "select coin_abr as thecoin_abr from coin_name where coin_abr = '$chkexistincoins' ";
							
							 
							            $rslt_ = mysqli_query($link, $queryselectcnname) or die(mysqli_error($link));
		               while($row__ = mysqli_fetch_assoc($rslt_)) {
	   
				              extract($row__);
				             
							 
			         }
					 
							
				if( strlen($thecoin_abr) >0)
				{
						 if( intval(getopentrade($link)) < intval($param_maxtrades))
						 {
					
					$queryinsert = " insert into tbl_trades (  trade_symbol, 
					trade_leverage, trade_entryprice, trade_closedprice,
					trade_openedat, trade_closedat, trade_profit,
					trade_hash, trade_type, trade_coin_digits, trade_amount,
					trade_sl, trade_tp, trade_closereasons, trade_by, 
					trade_estimatedloss, trade_estimatedprofit) values
					('$value','1','$marketprice',0,(select UNIX_TIMESTAMP(current_timestamp())),
					NULL,0,'$rndm','BUY','$cn_digits','20','0','0',NULL,'ROBOTNOTHAND',NULL,NULL)";
						 }
					 

						
					 
					 
						 if( intval(getopentrade($link)) < intval($param_maxtrades))
						 {
					 openclosebinancetrade($link,$value,true,"BUY");
						 }
						 }
					 }
	  
	 
			
$token = $param_telegramtoken;


 
	
	//$text =  "<b> Finally </b> <i>Test</i> <u>hello</u><br>";
   $text= "<b>New Automated Trade: </b>".PHP_EOL.PHP_EOL."<i>kindly check your Web portal to view trade details.</i>".PHP_EOL.PHP_EOL."<u>$param_clientwebportal</u>";
 
  //$text = str_replace("[BRK]", PHP_EOL, "$text");
  
$data = [
    'text' => $text,
    'chat_id' => $param_telegramchatid,
	'parse_mode' => 'HTML'
];
 if($param_sendtelegram == "true")
 {
 $data =file_get_contents( "https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data)) ;		
 } 
					 
					if(! $link ) {
                         die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
                              }
					  $resultinsert = mysqli_query($link, $queryinsert) or die(mysqli_error($link));
					
					
				}					
			}
		
	}



	if($__isbuy ==false)
	{
  $value = str_replace("-", "", "$value");
       $query = "select count(trade_id) as trade_openedandexist from tbl_trades where trade_symbol = '$value' and trade_by = 'ROBOTNOTHAND' and   trade_closedat is null ";
	   	if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	       	 $result = mysqli_query($link, $query) or die(mysqli_error($link));
		    while($row = mysqli_fetch_assoc($result)) {
				  $rndm=generateRandomString(10);
	   
				extract($row);
				if( $trade_openedandexist  == "0")
				{
				  	 $_url2 ="https://api.binance.com/api/v3/ticker/price?symbol=".$value;
	                 $json2 = file_get_contents($_url2);
                     $obj2 = json_decode($json2);
                     $marketprice =  $obj2->price; 
      
                     if( !  is_numeric($marketprice)==true   )
	                {
		               exit;
	                }
					$val2 =  str_replace('USDT','',$value);
					$queryselectcoindigits = "select coin_digits as cn_digits from coin_name where coin_abr='$val2'";
					 
					if(! $link ) {
                         die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
                              }
		 
	       	              $resultdigits = mysqli_query($link, $queryselectcoindigits) or die(mysqli_error($link));
		               while($rowdigits = mysqli_fetch_assoc($resultdigits)) {
	   
				              extract($rowdigits);
				             
							 
			         }
			
					
					    $queryinsert = "";
													 if($param_enablerobot=="true")
					 {
						   $chkexistincoins = str_replace("USDT", "", $value);
							
				            $queryselectcnname = "select coin_abr as thecoin_abr from coin_name where coin_abr = '$chkexistincoins' ";
							            $rslt_ = mysqli_query($link, $queryselectcnname) or die(mysqli_error($link));
		               while($row__ = mysqli_fetch_assoc($rslt_)) {
	   
				              extract($row__);
				             
							 
			         }
					 
							
				if( strlen($thecoin_abr) >0)
				{
						 if( intval(getopentrade($link)) < intval($param_maxtrades))
						 {
					$queryinsert = " insert into tbl_trades (  trade_symbol, 
					trade_leverage, trade_entryprice, trade_closedprice,
					trade_openedat, trade_closedat, trade_profit,
					trade_hash, trade_type, trade_coin_digits, trade_amount,
					trade_sl, trade_tp, trade_closereasons, trade_by, 
					trade_estimatedloss, trade_estimatedprofit) values
					('$value','1','$marketprice',0,(select UNIX_TIMESTAMP(current_timestamp())),
					NULL,0,'$rndm','SELL','$cn_digits','20','0','0',NULL,'ROBOTNOTHAND',NULL,NULL)";
						 }

						 


					 
					 
						 if( intval(getopentrade($link)) < intval($param_maxtrades))
						 {
							 
					  openclosebinancetrade($link,$value,true,"SELL");
					 
						 }
						 
					 }
						 } 
	 
			
					  $token = $param_telegramtoken;


 
	
	//$text =  "<b> Finally </b> <i>Test</i> <u>hello</u><br>";
   $text= "<b>New Automated Trade: </b>".PHP_EOL.PHP_EOL."<i>kindly check your Web portal to view trade details.</i>".PHP_EOL.PHP_EOL."<u>$param_clientwebportal</u>";
 
  //$text = str_replace("[BRK]", PHP_EOL, "$text");
  
$data = [
    'text' => $text,
    'chat_id' => $param_telegramchatid,
	'parse_mode' => 'HTML'
];

 if($param_sendtelegram == "true")
 {
 $data =file_get_contents( "https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data)) ;
 }
 
					if(! $link ) {
                         die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
                              }
					  $resultinsert = mysqli_query($link, $queryinsert) or die(mysqli_error($link));
					
					
				}					
			}
		
	}
	
}

}
	 
}




if($actionmode == "changevpsstatus")
{
	$rndstr= generateRandomString(10);
	$query = "update global_parameters set param_value = '$rndstr' where param_name = 'vpsstatus';";
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



if($actionmode == "getvpsstatus")
{
	 
	$query = "select param_value from global_parameters where param_name = 'vpsstatus'";
	if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	       	 $result = mysqli_query($link, $query) or die(mysqli_error($link));
		    while($row = mysqli_fetch_assoc($result)) {
	   
				extract($row);
				echo $param_value;
			}
}


 

if ($actionmode=="modifyglobalparam")
{
 
	 
		$coinname = GetSQLValueString($_GET["param_name"],"text");
	    $coindigits = GetSQLValueString($_GET["param_value"],"text");
		$query = "update global_parameters set param_value = $coindigits where param_name = $coinname";
        if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
	         $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
		
	 

			 

}


if ($actionmode=="addnewclient")
{
	$client_email = GetSQLValueString($_GET["clientemail"],"text");
	
	$queryabbr = "select client_id uniqueclientid from tbl_client where client_email=$client_email";
	 
	if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		  
		 $resultabbr = mysqli_query($link, $queryabbr) or die(mysqli_error($link));
		    while($row = mysqli_fetch_assoc($resultabbr)) {
	   
				extract($row);
			}
		
	if($uniqueclientid>0)
	{
		

	
 
	    $clientemail = GetSQLValueString($_GET["clientemail"],"text");
	    $clientpass = GetSQLValueString($_GET["clientpass"],"text");
	    $clientbinanceapikey = GetSQLValueString($_GET["clientbinanceapikey"],"text");
	    $clientbinancesecretkey = GetSQLValueString($_GET["clientbinancesecretkey"],"text");
	    $clientbinanceleverage = GetSQLValueString($_GET["clientbinanceleverage"],"text");
	    $clientbinancelotamoutpertrade = GetSQLValueString($_GET["clientbinancelotamoutpertrade"],"text");
	    $clientisenabled = GetSQLValueString($_GET["clientisenabled"],"text");
	  // client_id, client_email, client_pass, binance_apikey, binance_secretkey, binance_leverage, binance_lotamountpertrade, client_enablefutures
		$query = "update tbl_client set client_pass = $clientpass, binance_apikey =$clientbinanceapikey , binance_secretkey= $clientbinancesecretkey , binance_leverage= $clientbinanceleverage , binance_lotamountpertrade= $clientbinancelotamoutpertrade  , client_enablefutures= $clientisenabled  where client_email=$client_email";
 
	
    
    

		 	if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
	         $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
		
	}
	else
	{
		 
		 
    
   ///////////////////////////// $decodedData = base64_decode($data);
	    $clientemail = GetSQLValueString($_GET["clientemail"],"text");
	    $clientpass = GetSQLValueString($_GET["clientpass"],"text");
	    $clientbinanceapikey = GetSQLValueString($_GET["clientbinanceapikey"],"text");
	    $clientbinancesecretkey = GetSQLValueString($_GET["clientbinancesecretkey"],"text");
	    $clientbinanceleverage = GetSQLValueString($_GET["clientbinanceleverage"],"text");
	    $clientbinancelotamoutpertrade = GetSQLValueString($_GET["clientbinancelotamoutpertrade"],"text");
	    $clientisenabled = GetSQLValueString($_GET["clientisenabled"],"text");
		$query = "insert into tbl_client(client_id, client_email, client_pass, binance_apikey, binance_secretkey, binance_leverage, binance_lotamountpertrade, client_enablefutures) values(NULL,$client_email,$clientpass,$clientbinanceapikey,$clientbinancesecretkey,$clientbinanceleverage,$clientbinancelotamoutpertrade,$clientisenabled)";
	if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	         $result = mysqli_query($link, $query) or die(mysqli_error($link));
	}

			 

}


if ($actionmode=="addnewcoin")
{
	$coinabbr = GetSQLValueString($_GET["coinabbr"],"text");
	
	$queryabbr = "select coin_id uniquecoinid from coin_name where coin_abr=$coinabbr";
	 
	if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		  
		 $resultabbr = mysqli_query($link, $queryabbr) or die(mysqli_error($link));
		    while($row = mysqli_fetch_assoc($resultabbr)) {
	   
				extract($row);
			}
		
	if($uniquecoinid>0)
	{
		
	$postedimage = (file_get_contents('php://input'));
	
	if(strlen($postedimage) >50)
	{
	 
		$coinname = GetSQLValueString($_GET["coinname"],"text");
	    $coindigits = GetSQLValueString($_GET["coindigits"],"text");
		$query = "update coin_name set coin_isvisible = '1', coin_logo = '$postedimage' ,coin_fullname =$coinname , coin_digits= $coindigits where coin_abr=$coinabbr";
		

	}
	else
	{
	    $coinname = GetSQLValueString($_GET["coinname"],"text");
	    $coindigits = GetSQLValueString($_GET["coindigits"],"text");
		$query = "update coin_name set coin_isvisible = '1', coin_fullname =$coinname , coin_digits= $coindigits  where coin_abr=$coinabbr";
	}
	
    
    

		 	if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
	         $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
		
	}
	else
	{
		 
			$postedimage = (file_get_contents('php://input'));
   
   ///////////////////////////// $decodedData = base64_decode($data);
	$coinname = GetSQLValueString($_GET["coinname"],"text");
	$coindigits = GetSQLValueString($_GET["coindigits"],"text");
		$query = "insert into coin_name(coin_id, coin_abr, coin_logo, coin_fullname,coin_digits,coin_isvisible) values(NULL,$coinabbr,'$postedimage',$coinname,$coindigits,'1')";
	if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	         $result = mysqli_query($link, $query) or die(mysqli_error($link));
	}

			 

}


if ($actionmode=="getcoindata")
{
	 
 	$query = "select coin_id, coin_abr, coin_logo, coin_fullname, coin_digits from coin_name where coin_isvisible=1 and Coin_abr <> 'USDT' and coin_id=".$_GET["coin_id"];
 
	 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
	   while($row = mysqli_fetch_assoc($result)) {
	   
				extract($row);
			}
 echo "$coin_abr{}$coin_logo{}$coin_fullname{}$coin_digits";
			 

}

if ($actionmode=="getclientdata")
{
	 
 	$query = " select client_id, client_email, client_pass, binance_apikey, binance_secretkey, binance_leverage, binance_lotamountpertrade, client_enablefutures from tbl_client where  client_id=".$_GET["client_id"];
 
	 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
	   while($row = mysqli_fetch_assoc($result)) {
	   
				extract($row);
			}
 echo "$client_id{}$client_email{}$client_pass{}$binance_apikey{}$binance_secretkey{}$binance_leverage{}$binance_lotamountpertrade{}$client_enablefutures";
			 

}




if ($actionmode=="getparamdata")
{
	 
 	$query = "select * from global_parameters where param_name='".$_GET["param_name"]."'";
 
	 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
	   while($row = mysqli_fetch_assoc($result)) {
	   
				extract($row);
			}
 echo "$param_name{}$param_value";
			 

}



if ($actionmode=="getcoindatajson")
{
	 
 	$query = "select coin_id, coin_abr, coin_logo, coin_fullname, coin_digits from coin_name where coin_isvisible=1 and Coin_abr <> 'USDT' order by coin_abr asc";
 
	 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 
	 if (mysqli_num_rows($result) > 0) {
		 
            while($row = mysqli_fetch_assoc($result)) {
				 $coin[] = $row;
			}
			
			$struct = array("Coins" => $coin);
print json_encode($struct);
	 }
			 

}



if ($actionmode=="getcoinjson")
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


if ($actionmode=="opennewposition")
{
	
	
	
	
	 $sym_=$_GET["sym"];
	 $sym=$_GET["sym"]."USDT";
	 $ordermode=$_GET["ordermode"];
     $estprofit=$_GET["estprofit"];
	 $estloss=$_GET["estloss"];

     $estprofit = explode('<br>', $estprofit);
     $estloss = explode('<br>', $estloss);
     $estprofit = $estprofit[1];
     $estloss = $estloss[1];
     $estprofit= GetSQLValueString($estprofit,"text");
	 $estloss= GetSQLValueString($estloss,"text");
	 $_url ="https://api.binance.com/api/v3/ticker/price?symbol=".$sym;

	
	 $json = file_get_contents($_url);
     $obj = json_decode($json);
     $entryprice =  $obj->price; 
      
     if( !  is_numeric($entryprice)==true   )
	 {
		 exit;
	 }
	 
    $rndm=generateRandomString(10);
	 
	 if($ordermode=="-1")
	 {
		$ordermode = "'SELL'"; 
	 }
	 else
	 {
		 $ordermode = "'BUY'"; 
	 }
	 
	 
	 $sl=$_GET["sl"];
	  $sl= GetSQLValueString($sl,"text");
	 $tp=$_GET["tp"];
	  $tp= GetSQLValueString($tp,"text");
	 $amnt=$_GET["amnt"];
	  $amnt= GetSQLValueString($amnt,"text");
	 $lvg=$_GET["lvg"];
	  $lvg= GetSQLValueString($lvg,"text");
 	$query = "insert into tbl_trades( trade_symbol, 
	trade_leverage, trade_entryprice, trade_closedprice,
	trade_openedat, trade_closedat, trade_profit, trade_hash,
	trade_type, trade_coin_digits, trade_amount, trade_sl,
	trade_tp, trade_closereasons , trade_by,trade_estimatedloss,trade_estimatedprofit)
	values('$sym',$lvg,'$entryprice','0',(select UNIX_TIMESTAMP(current_timestamp())),NULL,'0','$rndm',$ordermode,(select coin_digits from coin_name where coin_abr='$sym_'),$amnt, $sl,$tp,NULL,'HANDNOTROBOT',$estloss,$estprofit)
	 ";
 
 
	 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
 
 if ($result==1)
 {
	  $last_id = mysqli_insert_id($link);
	  
	  $querylastid = "select trade_hash as lsttradehash from tbl_trades where trade_id = $last_id";
	  	 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	  $resultlastid = mysqli_query($link, $querylastid) or die(mysqli_error($link));
	  
	      while($rowlastid = mysqli_fetch_assoc($resultlastid)) {
	       
				extract($rowlastid);
				 
			 
				
			}


	  
	  
 


			
	  
	  
$token = $param_telegramtoken;


 
	
	//$text =  "<b> Finally </b> <i>Test</i> <u>hello</u><br>";
   $text= "<b>New Trade: id#<u>$lsttradehash</u></b>".PHP_EOL.PHP_EOL."<i>kindly check your Web portal to view trade details.</i>".PHP_EOL.PHP_EOL."<u>$param_clientwebportal</u>";
 
  //$text = str_replace("[BRK]", PHP_EOL, "$text");
  
$data = [
    'text' => $text,
    'chat_id' => $param_telegramchatid,
	'parse_mode' => 'HTML'
];

 $data =file_get_contents( "https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data)) ;

 } 
 
			 

}




if($actionmode=="closeposition")
{
	
	  $positionhash=$_GET["positionhash"];
	  $positionhash= GetSQLValueString($positionhash,"text");
	  $markprice=$_GET["markprice"];
	  $markprice= GetSQLValueString($markprice,"text");
 	  $tradeprofit=$_GET["tradeprofit"];
	  $tradeprofit= GetSQLValueString($tradeprofit,"text");
	
      $query="update tbl_trades set trade_closedprice=$markprice,trade_closedat=  (select UNIX_TIMESTAMP(current_timestamp())),trade_profit=$tradeprofit,trade_closereasons='MANUALLYCLOSED' where trade_hash=$positionhash";
 
	 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			

	  if ($result==1)
 {
 
/*

	$token = $param_telegramtoken;
	
	//$text =  "<b> Finally </b> <i>Test</i> <u>hello</u><br>";
   $text= $_GET["text"];
  
  $text = str_replace("[BRK]", PHP_EOL, "$text");
  
$data = [
    'text' => $text,
    'chat_id' => $param_telegramchatid,
	'parse_mode' => 'HTML'
];

$data =file_get_contents( "https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data)) ;
 //echo "https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data);
 
 */
 }
	 
}





if($actionmode=="vpsclose")
{
	
	  $positionhash=$_GET["positionhash"];
	  $positionhash= GetSQLValueString($positionhash,"text");
	  $markprice=$_GET["markprice"];
	  $markprice= GetSQLValueString($markprice,"text");
 	  $tradeprofit=$_GET["tradeprofit"];
	  $tradeprofit= GetSQLValueString($tradeprofit,"text");
	
      $query="update tbl_trades set trade_closedprice=$markprice,trade_closedat=  (select UNIX_TIMESTAMP(current_timestamp())),trade_profit=$tradeprofit,trade_closereasons='CLOSEDBYVPS' where trade_hash=$positionhash";
 
	 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		
	        $result = mysqli_query($link, $query) or die(mysqli_error($link));
			

	  if ($result==1)
 {
 
/*

	$token = $param_telegramtoken;
	
	//$text =  "<b> Finally </b> <i>Test</i> <u>hello</u><br>";
   $text= $_GET["text"];
  
  $text = str_replace("[BRK]", PHP_EOL, "$text");
  
$data = [
    'text' => $text,
    'chat_id' => $param_telegramchatid,
	'parse_mode' => 'HTML'
];

$data =file_get_contents( "https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data)) ;
 //echo "https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data);
 
 */
 }
	 
}

function getFreezed_until($link)
{
	  $queryfreeze = "select param_value  as freezed_until from global_parameters where param_name = 'param_freezed_until';";
      $resultfreeze = mysqli_query($link, $queryfreeze) or die(mysqli_error($link));
		    while($rowfreeze = mysqli_fetch_assoc($resultfreeze)) {
				 
				 
	   
				extract($rowfreeze);
				
			}
			return $freezed_until;
}
function getCurrentTimeStamp($link)
{
	$query =" select UNIX_TIMESTAMP(current_timestamp()) as currenttimestamp from dual";
 if(! $link ) {
            die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
         }
		 
	         $result = mysqli_query($link, $query) or die(mysqli_error($link));
			 

   while($row = mysqli_fetch_assoc($result)) {
				extract($row);
			}
			return $currenttimestamp;
}
function getopentrade($link)
{
	$querycnt = "select count(*) as openedtrades_ from tbl_trades where trade_closedat is null;";
						 	if(! $link ) {
                         die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
                              }
						  $resultcnt = mysqli_query($link, $querycnt) or die(mysqli_error($link));
						  
						    while($rowcnt = mysqli_fetch_assoc($resultcnt)) {
	 
				              extract($rowcnt);
				              return $openedtrades_;
							 
			         }
}
function openclosebinancetrade($link,$_sym2,$openposition,$ordertype)
{
	


$querygetuserdata = "select client_id, client_email, client_pass, binance_apikey, binance_secretkey, binance_leverage, binance_lotamountpertrade from tbl_client where client_enablefutures = '1'";
					 
					if(! $link ) {
                         die('RESPONSE_ERROR Could not connect: ' . mysqli_error());
                              }
		 
	       	              $resultuserdata = mysqli_query($link, $querygetuserdata) or die(mysqli_error($link));
		               while($rowuserdata = mysqli_fetch_assoc($resultuserdata)) {
	   
				              extract($rowuserdata);
				            
                               $leverage = $binance_leverage;
                               $fixedamount = $binance_lotamountpertrade;
                               $apikey = $binance_apikey; 
                               $secretkey = $binance_secretkey;
							   
							   $fixedamount= floatval($binance_lotamountpertrade) * floatval($binance_leverage);
							
 if (strlen($apikey)>10 && strlen($secretkey)>10)
 {
	   	try { 
 
	 /* code here */
$exchange_id = 'binance';
$exchange_class = "\\ccxt\\$exchange_id";
$binance = new $exchange_class(array(
    'apiKey' => $apikey,
    'secret' => $secretkey,
    'timeout' => 60000,
    'enableRateLimit' => true,
    'options' => array('defaultType' => "future",'adjustForTimeDifference' => "True")
));



$_sym=substr($_sym2, 0, -4);
$_sym = $_sym."/USDT";
$symbol = $_sym;


$_urlsym ="https://api.binance.com/api/v3/ticker/price?symbol=".$_sym2;
	                 
	                 $jsonsym = file_get_contents($_urlsym);
                     $objsym = json_decode($jsonsym);
                     $currprice =  $objsym->price; 
 
					 
$fixedamount = (floatval($fixedamount)*floatval(1)) /(floatval($currprice));
$fixedamount=$fixedamount*1; 
$binance->load_markets();
$binance->verbose = false; 
$params = array(
    'symbol' => $_sym2,
    'leverage' => $leverage,
	'recvWindow'=> 50000,
);
$balance = $binance->fetch_balance();
 
$freebalance =  ($balance['info']['maxWithdrawAmount']);
 
 } catch (Exception $e) {
   
}
if($openposition ==true && (floatval($freebalance)> (floatval($binance_lotamountpertrade) *1) ))
{
	 
	try {
    $response3 = $binance->fapiPrivate_post_leverage($params);
	$response4 = $binance->createOrder($symbol,"MARKET",$ordertype,$fixedamount,null,$params);
	// // // //var_dump($response4);
} catch (Exception $e) {
   
}


}

 
if($openposition ==false)
{
    $position =  ($balance['info']['positions']);
	$posamt=0;
	foreach ($position as $pos) {
        if( ($pos["symbol"]) == "$_sym2")
		{
			$posamt= abs($pos["positionAmt"]);
			 
		}
}

 
	 
	if(floatval($posamt)>floatval(0))
	{
		 
	$rev= "";
	if($ordertype == "BUY")
	{
			try {
 		    $rev= "SELL";
	        $response3 = $binance->fapiPrivate_post_leverage($params);
			$response4 = $binance->createOrder($symbol,"MARKET",$rev,$posamt,null,$params);
} 
           catch (Exception $e) {
   
}

		
	}
	else
	{
					try {
 		$rev= "BUY";
	    $response3  = $binance->fapiPrivate_post_leverage($params);
        $response4  = $binance->createOrder($symbol,"MARKET",$rev,$posamt,null,$params);
}
        catch (Exception $e) {
   
}


	}
	}
	
}
							/* end code here */
							
 }
			         }
 
}



?>