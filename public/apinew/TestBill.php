<?php 
phpinfo();
exit;

require_once 'class/dbconfig.php'; 

//$testbox = $desco->Update_DESC0_SSLTable('121633068406','2017-01-31','2017-01-22');
//echo'<pre>'; 
//print_r($testbox);
//echo'</pre>'; 
//echo'<br>'; 
//exit;

$AllBillCheck = $user->check_Trans_withSSLCommerz();


foreach($AllBillCheck as $bills){

/*$SSLPayment = $user->ssl_lookup_method('DL111620024466');

$lastTrans = $SSLPayment['no_of_trans_found']-1;
$PayemntStatus = $SSLPayment['element'][$lastTrans];

if($PayemntStatus['status']!='VALIDATED' || $PayemntStatus['status']!='VALID'){
		echo '<pre>';
			print_r($PayemntStatus['status']);	
		echo '</pre>';
	}*/


$testbox = $desco->Update_DESC0_SSLTable($bills['Bill_no'],$bills['date'],$bills['Due_Date']);
echo'<pre>'; 
print_r($testbox);
echo'</pre>'; 
echo'<br>'; 
/*Dublicate Bill Check
$DESCOBILLINFO = $desco->Get_Desco_All_Bill_SSL_Table($bills['Bill_no']);

if($DESCOBILLINFO['COUNT']>1){
	$infor['Bill_no']=$bills['Bill_no'];
	$infor['added_in_ssl_time'] = $bills['added_in_ssl_time'];
    echo '<pre>';
	print_r($infor);	
	echo '</pre>';
}
//Dublicate Bill Check
*/

}
?>
