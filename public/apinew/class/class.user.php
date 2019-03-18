<?php
class USER
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	public function runQuery($sql)
	{
		$stmt = $this->db->prepare($sql);
		return $stmt;
	}
	
	public function lasdID()
	{
		$stmt = $this->db->lastInsertId();
		return $stmt;
	}
	
	
	///Validated Mobile For Bangladeshi Telecom Operator : number_valid();
	public function number_valid($mobile)
	{
		// Set default response of '0' is ok
		$response['code'] = 0;
		$response['data'] = NULL;
							   if (preg_match("/^[0]{1}[1]{1}[1,5,6,7,8,9]{1}[0-9]{8}$/", $mobile)){
								  if(preg_match("/^[0]{1}[1]{1}[1]{1}[0-9]{8}$/", $mobile)){
										$response['code'] = 0;
										$response['data'] = 'CityCell';
								  }
								  elseif(preg_match("/^[0]{1}[1]{1}[5]{1}[0-9]{8}$/", $mobile)){
										$response['code'] = 0;
										$response['data'] = 'TeleTalk';
								  }
								  elseif(preg_match("/^[0]{1}[1]{1}[6]{1}[0-9]{8}$/", $mobile)){
										$response['code'] = 0;
										$response['data'] = 'Airtel';
								  }
								  elseif(preg_match("/^[0]{1}[1]{1}[7]{1}[0-9]{8}$/", $mobile)){
										$response['code'] = 0;
										$response['data'] = 'GrameenPhone';
								  }
								  elseif(preg_match("/^[0]{1}[1]{1}[8]{1}[0-9]{8}$/", $mobile)){
									 	$response['code'] = 0;
										$response['data'] = 'Robi';
								  }
								  elseif(preg_match("/^[0]{1}[1]{1}[9]{1}[0-9]{8}$/", $mobile)){
										$response['code'] = 0;
										$response['data'] = 'blink';
								  }
								  else {
										$response['code'] = 1;
										$response['data'] = 'Invalid Mobile Number! Number Must be Start with 017/011/018/019/016/015 Than rest of 8 Digit';
								   }
								}
								else{
								   		$response['code'] = 1;
										$response['data'] = 'Invalid Mobile Number! Number Must be Start with 017/011/018/019/016/015 Than rest of 8 Digit';
								}
							   return $response;
	} 
		
	public function Postvariables()
	{	
		$data=NULL;
		foreach ($_REQUEST as $key => $value){
				$data[$key]=$this->clean($value);
			}
			return $data;
	}
	
	public function Getvariables()
	{	
		$data=NULL;
		foreach ($_GET as $key => $value){
				$data[$key]=$this->clean($value);
			}
			return $data;
	}
	
	
	// GET A ACCOUNT INFORMATION USED IN OUR DATABASE OR NO Using Account No and Mobile No.
	public function check_account($data){
			try
			{	
			$company=NULL;
			$company_id['account_no']=$data['account_no'];
			$company_id['mobile_no']=$data['mobile'];
				$stmt = $this->db->prepare("SELECT * FROM desco_user WHERE account_no=:account_no AND mobile_no=:mobile_no LIMIT 1");
				$stmt->execute(array(':account_no'=>$company_id['account_no'],':mobile_no'=>$company_id['mobile_no']));
				$storeRow=$stmt->fetch(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0)
				{	
					$company = $storeRow;
					$company['return']= 'OK';
				}
				else {
					$company['return']= 'NO';
				
				}
				return $company;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		
		}
	
	
	// GET S&D's and Offices For a Account Holder
	public function check_sd_office($deptID){
			try
			{	
			$company=NULL;
				$stmt = $this->db->prepare("SELECT * FROM address_depo WHERE snd_deptid=:snd_deptid LIMIT 1");
				$stmt->execute(array(':snd_deptid'=>$deptID));
				$storeRow=$stmt->fetch(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0)
				{	
					$company = $storeRow;
					$company['return']= 'OK';
				}
				else {
					$company['return']= 'NO';
				
				}
				return $company;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		
		}
	
	
	  // SSLCOMMERZ LOOKUP METHOD
	public function ssl_lookup_method($track){
		try
		{



//$url ='https://securepay.sslcommerz.com/validator/api/testbox/merchantTransIDvalidationAPI.php?tran_id='.$track.'&Store_Id=testbox&Store_Passwd=qwerty&v=1&format=json';

			$url ='https://securepay.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php?tran_id='.$track.'&Store_Id=desco001live&Store_Passwd=desco001live12043&v=1&format=json';


			$fields = array(
						
						);

						$fields_string=NUll;						
						//url-ify the data for the POST
						foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
						rtrim($fields_string, '&');
						
						//open connection
						$ch = curl_init();
						$ip = '72.5.160.68';
						//set the url, number of POST vars, POST data
						
						curl_setopt( $ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POST, count($fields));
						curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						
						//execute post
						$result = curl_exec($ch);
						
						
						//print_r($result);
						$url_forward = json_decode($result, true);
						
						//close connection
						curl_close($ch);
			return $url_forward;		 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	
	// GET S&D's and Offices For a Account Holder
	public function check_Trans_withSSLCommerz(){
			try
			{	
			$company=NULL;
			$check ='0';
				$stmt = $this->db->prepare("SELECT * FROM transaction WHERE checknow=:checknow ORDER BY Id ASC");
				$stmt->execute(array(':checknow'=>$check));
				$storeRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0)
				{	
					$company = $storeRow;
					$company['return']= 'OK';
				}
				else {
					$company['return']= 'NO';
				
				}
				return $company;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		
		}
	
	
	// GET S&D's and Offices For a Account Holder
	public function check_Trans_Online($transaction_ID){
			try
			{	
			$company=NULL;
				$stmt = $this->db->prepare("SELECT * FROM transaction WHERE transaction_ID=:transaction_ID LIMIT 1");
				$stmt->execute(array(':transaction_ID'=>$transaction_ID));
				$storeRow=$stmt->fetch(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0)
				{	
					$company = $storeRow;
					$company['return']= 'OK';
				}
				else {
					$company['return']= 'NO';
				
				}
				return $company;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		
		}
		
		
	// GET A ACCOUNT INFORMATION USED IN OUR DATABASE OR NO Using Account No and Mobile No.
	public function check_session($sessionID){
			try
			{	
			$company=NULL;
				$stmt = $this->db->prepare("SELECT * FROM desco_user WHERE sessionkey=:sessionkey LIMIT 1");
				$stmt->execute(array(':sessionkey'=>$sessionID));
				$storeRow=$stmt->fetch(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0)
				{	
					$company = $storeRow;
					$company['return']= 'OK';
				}
				else {
					$company['return']= 'NO';
				
				}
				return $company;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		
		}
		
		
		
	// GET All Active Account using Mobile Number INFORMATION USED IN OUR DATABASE OR NO Using Account No and Mobile No.
	public function check_accounts_list($mobile){
			try
			{	
			$company=NULL;
			$status=1;
				$stmt = $this->db->prepare("SELECT account_no, sessionkey, snd_deptid FROM desco_user WHERE mobile_no=:mobile_no AND status=:status ORDER BY Id ASC");
				$stmt->execute(array(':mobile_no'=>$mobile,':status'=>$status));
				$storeRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0)
				{	
					$company = $storeRow;
					$company['return']= 'OK';
				}
				else {
					$company['return']= 'NO';
				
				}
				return $company;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		
		}
	
	// Any Data Insert 
	public function data_insert($table, $data){
		try
		{
			$result = '1';
			$query = 'INSERT INTO '.$table.' (';
			$values = array();
			$query1 =NULL;
			$query2 =NULL;
			foreach ($data as $name => $value) {
				$query1 .= ' '.$name.','; // the :$name part is the placeholder, e.g. :zip
				$query2 .= ' :'.$name.','; // the :$name part is the placeholder, e.g. :zip
				$values[':'.$name] = $value; // save the placeholder
			}
			
			$query .= substr($query1, 0, -1).') VALUES ('.substr($query2, 0, -1).');'; // remove last , and add a ;
			$stmt=$this->db->prepare($query);
			foreach ($data as $name => $value) {
				$stmt->bindValue(':'.$name, $value);
			}
			
			$stmt->execute();
			return $result;		 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	
	
	//SEND OTP TO Customers 
	public function send_otp_sms($mobile,$OTP,$USERID){
		try	{
			$user ="descoapp";
	$pass = "22r@1B67";
	$sid = "DESCOApp";
	
	$SMSHISTORY = NULL;
	
	$SMSBODY = 'Your DESCO Mobile Apps Activation OTP is '.$OTP;
	
	$mobile='88'.$mobile;
	$SMSHISTORY['sms'] = $SMSBODY;
	$SMSHISTORY['date'] = date("Y-m-d H:i:s");
	$SMSHISTORY['user_id'] = $USERID;
	
	$SMSHIST = $this->data_insert('otp_sms_history',$SMSHISTORY);
	
	$transacationID = $this->lasdID();
	
	$url="http://sms.sslwireless.com/pushapi/dynamic/server.php";
	$param="user=$user&pass=$pass&sms[0][0]=$mobile&sms[0][1]=".urlencode($SMSBODY)."&sms[0][2]=$transacationID&sid=$sid";

				$crl = curl_init();
				curl_setopt($crl,CURLOPT_SSL_VERIFYPEER,FALSE);
				curl_setopt($crl,CURLOPT_SSL_VERIFYHOST,2);
				curl_setopt($crl,CURLOPT_URL,$url); 
				curl_setopt($crl,CURLOPT_HEADER,0);
				curl_setopt($crl,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($crl,CURLOPT_POST,1);
				curl_setopt($crl,CURLOPT_POSTFIELDS,$param); 
				$response = curl_exec($crl);
				curl_close($crl);
			
		   
		   
		   		return $response;
		   
		   }
		catch(PDOException $e){
				echo $e->getMessage();
			}
	}
	
	
	
	
	public function Update_All_Transaction($TrackID,$data) {
		try
			{
			$query = 'UPDATE transaction_all SET';
			$values = array();
			
			foreach ($data as $name => $value) {
				$query .= ' '.$name.' = :'.$name.','; // the :$name part is the placeholder, e.g. :zip
				$values[':'.$name] = $value; // save the placeholder
			}
			
			$values[':Id'] = $TrackID;
			$values[':status'] = 1;
			
			$query = substr($query, 0, -1).' WHERE Id=:Id AND status=:status LIMIT 1;'; // remove last , and add a ;
			$stmt=$this->MyDB->prepare($query);
		  	$data= $stmt->execute($values);
		   
		   
		   return $data;
		   
		   }
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		
		}
	
	
	// t_transaction Table Update Global Query
	public function desco_update($acc,$mob,$data){
		try
			{
			$query = 'UPDATE desco_user SET';
			$values = array();
			
			foreach ($data as $name => $value) {
				$query .= ' '.$name.' = :'.$name.','; // the :$name part is the placeholder, e.g. :zip
				$values[':'.$name] = $value; // save the placeholder
			}
			
			$values[':account_no'] = $acc;
			$values[':mobile_no'] = $mob;
			
			$query = substr($query, 0, -1).' WHERE account_no=:account_no AND mobile_no=:mobile_no LIMIT 1;'; // remove last , and add a ;
			$stmt=$this->db->prepare($query);
		  	$data= $stmt->execute($values);
		   
		   
		   return $data;
		   
		   }
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
	}
	
	public function encrypt_cbc($str,$key) {

        //$key = $this->hex2bin($key);    
        $iv = $key;

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, $key, $iv);
        $encrypted = mcrypt_generic($td, $str);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return bin2hex($encrypted);
    }

    public function decrypt_cbc($code,$key) {
        //$key = $this->hex2bin($key);
        $code = $this->hex2bin($code);
        $iv = $key;

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $code);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return utf8_encode(trim($decrypted));
    }

    protected function hex2bin($hexdata) {
        $bindata = '';

        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    } 
	
	
	public function Send_OTP() {
        $GET_OTP = mt_rand(100000, 999999);
		//SEND SMS THEN RETURN THEN UPDATE IT TO DATABASE
		

        return $GET_OTP;
    }
	
	
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}

        //DATA CHECK FOR SQL INJECTIONS
	private function cleanInput($input) {
	 
		  $search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		  );
 
		$output = preg_replace($search, '', $input);
		return $output;
	  }
	
	////Sanitization function //Function to sanitize values received from the form. Prevents SQL injection
	public function clean($input) {
		if (is_array($input)) {
			foreach($input as $var=>$val) {
				$output[$var] = mysql_real_escape_string($val);
			}
		}
		else {
			if (get_magic_quotes_gpc()) {
				$input = stripslashes($input);
			}
			$input  = $this->cleanInput($input);
			$output = $input;
		}
		return $output;
	}
   
    public function user_ip(){
	
	//Read User IP Address
			$ipaddress = '';
			if (getenv('HTTP_CLIENT_IP'))
				$ipaddress = getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			else if(getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
			   $ipaddress = getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
	}  
	
	
	
	// GET S&D's and Offices For a Account Holder
	public function Find_Trans_Online(){
			try
			{	
			$company=NULL;
			$added_in_ssl=0;
				$stmt = $this->db->prepare("SELECT * FROM transaction WHERE added_in_ssl=:added_in_ssl ORDER BY Id ASC");
				$stmt->execute(array(':added_in_ssl'=>$added_in_ssl));
				$storeRow=$stmt->fetchALL(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0)
				{	
					$company = $storeRow;
					$company['return']= 'OK';
				}
				else {
					$company['return']= 'NO';
				
				}
				return $company;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		
		}
		
		
		
		// GET S&D's and Offices For a Account Holder
	public function Find_Trans_All_Online(){
			try
			{	
			$company=NULL;
			$added_in_ssl=0;
				$stmt = $this->db->prepare("SELECT * FROM transaction_all WHERE added_in_ssl=:added_in_ssl ORDER BY Id ASC");
				$stmt->execute(array(':added_in_ssl'=>$added_in_ssl));
				$storeRow=$stmt->fetchALL(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0)
				{	
					$company = $storeRow;
					$company['return']= 'OK';
				}
				else {
					$company['return']= 'NO';
				
				}
				return $company;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		
		}
		
		
		
		
		public function Update_Trans_Online($trackID,$data){
		try
			{
			$query = 'UPDATE desco_user SET';
			$values = array();
			
			foreach ($data as $name => $value) {
				$query .= ' '.$name.' = :'.$name.','; // the :$name part is the placeholder, e.g. :zip
				$values[':'.$name] = $value; // save the placeholder
			}
			
			$values[':transaction_ID'] = $trackID;
			
			$query = substr($query, 0, -1).' WHERE transaction_ID=:transaction_ID LIMIT 1;'; // remove last , and add a ;
			$stmt=$this->db->prepare($query);
		  	$data= $stmt->execute($values);
		   
		   
		   return $data;
		   
		   }
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
	}
}
?>