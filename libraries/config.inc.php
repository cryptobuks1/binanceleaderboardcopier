<?php

 
error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
error_reporting(E_ALL ^ E_DEPRECATED);
error_reporting(1);
ini_set('display_errors', 1);


ini_set('session.gc_maxlifetime', 720000);
session_set_cookie_params(720000);


$config = array();
global $config;
$config['dbusername']='root';
$config['dbpass']='';
$config['dbTitle']='binancefutures';
$config['dbServer']='localhost';
$config['dbUser_log']='root'; 
$config['dbPass_log']='';
$config['dbName_log']='binancefutures';     
				
?>