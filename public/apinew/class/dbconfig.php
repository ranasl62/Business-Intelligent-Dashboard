<?php
session_start();
setlocale(LC_ALL, 'bn-BD');
date_default_timezone_set('Asia/Dhaka');


//---------------- DESCO MOBILE APPS DATABASE Connection Start--------------
$DB_host_care = "192.168.92.138";
$DB_user_care = "descombapp";
$DB_pass_care = "Deag3#dg56ag";
$DB_name_care = "desco_mobileapp";

try
{
	$DB_con_care = new PDO("mysql:host={$DB_host_care};dbname={$DB_name_care}",$DB_user_care,$DB_pass_care);
	$DB_con_care->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo $e->getMessage().' MySQL DB Connect error<br/>';
}


include_once 'class.user.php';
$user = new USER($DB_con_care);
