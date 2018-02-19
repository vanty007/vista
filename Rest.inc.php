<?php
	/* File : Rest.inc.php
	 * Author : Arun Kumar Sekar
	*/
	require_once('PHPMailerAutoload.php');
	require_once("GCMSEND.php");
	class REST extends GCMSEND{
		
		public $_allow = array();
		public $_content_type = "application/json";
		public $_request = array();
		
		private $_method = "";		
		private $_code = 200;
		
		public function __construct(){
			$this->inputs();
		}
		//handles database connect for mysql
		
		public function dbConnectMysql($dbName){
		 //$DB_SERVER = '192.168.8.101';
		 $DB_SERVER = 'localhost';
		 $DB_USER = 'root';
		 $DB_PASSWORD = "";
		 $DB = $dbName;
		
		 $this->db = mysqli_connect($DB_SERVER,$DB_USER,$DB_PASSWORD);
		if($this->db)
		mysqli_select_db($this->db,$DB);
		}
		//handles database connect for mongodb
		public function dbConnectMongodbVista(){
		
		//$dbhost = '192.168.1.6';
		$dbhost = 'localhost:27017';
		$dbname = 'vista';
		$mng = new MongoDB\Driver\Manager("mongodb://$dbhost");
		//$db = $m->$dbname;
		//$collection = $m->$tbl;

		return $mng;
		}
		//handles output
		public function json($data){
			if(is_array($data)){
				
				return json_encode($data,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE );
				//return '{"items":'.json_encode($data).'}';
			}
		}
		
		public function get_referer(){
			return $_SERVER['HTTP_REFERER'];
		}
		
		public function response($data,$status){
			$this->_code = ($status)?$status:200;
			$this->set_headers();
			echo $data;
			//exit;
		}
		
		private function get_status_message(){
			$status = array(
						100 => 'Continue',  
						101 => 'Switching Protocols',  
						200 => 'OK',
						201 => 'Created',  
						202 => 'Accepted',  
						203 => 'Non-Authoritative Information',  
						204 => 'No Content',  
						205 => 'Reset Content',  
						206 => 'Partial Content',  
						300 => 'Multiple Choices',  
						301 => 'Moved Permanently',  
						302 => 'Found',  
						303 => 'See Other',  
						304 => 'Not Modified',  
						305 => 'Use Proxy',  
						306 => '(Unused)',  
						307 => 'Temporary Redirect',  
						400 => 'Bad Request',  
						401 => 'Unauthorized',  
						402 => 'Payment Required',  
						403 => 'Forbidden',  
						404 => 'Not Found',  
						405 => 'Method Not Allowed',  
						406 => 'Not Acceptable',  
						407 => 'Proxy Authentication Required',  
						408 => 'Request Timeout',  
						409 => 'Conflict',  
						410 => 'Gone',  
						411 => 'Length Required',  
						412 => 'Precondition Failed',  
						413 => 'Request Entity Too Large',  
						414 => 'Request-URI Too Long',  
						415 => 'Unsupported Media Type',  
						416 => 'Requested Range Not Satisfiable',  
						417 => 'Expectation Failed',  
						500 => 'Internal Server Error',  
						501 => 'Not Implemented',  
						502 => 'Bad Gateway',  
						503 => 'Service Unavailable',  
						504 => 'Gateway Timeout',  
						505 => 'HTTP Version Not Supported');
			return ($status[$this->_code])?$status[$this->_code]:$status[500];
		}
		
		public function get_request_method(){
			return $_SERVER['REQUEST_METHOD'];
		}
		
		private function inputs(){
			switch($this->get_request_method()){
				case "POST":
					$this->_request = $this->cleanInputs($_POST);
					break;
				case "GET":
				case "DELETE":
					$this->_request = $this->cleanInputs($_GET);
					break;
				case "PUT":
					parse_str(file_get_contents("php://input"),$this->_request);
					$this->_request = $this->cleanInputs($this->_request);
					break;
				default:
					$this->response('',406);
					break;
			}
		}		
		
		private function cleanInputs($data){
			$clean_input = array();
			if(is_array($data)){
				foreach($data as $k => $v){
					$clean_input[$k] = $this->cleanInputs($v);
				}
			}else{
				if(get_magic_quotes_gpc()){
					$data = trim(stripslashes($data));
				}
				$data = strip_tags($data);
				$clean_input = trim($data);
			}
			return $clean_input;
		}		
		
		private function set_headers(){
			header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
			header("Content-Type:".$this->_content_type);
		}

public function sendmail($header,$body,$to){
			
$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
$mail->Host = "smtp.mail.yahoo.com";
$mail->Port = 587; // or 587
//$mail->IsHTML(true);
$mail->Username = "tayo.ogundeji@yahoo.com";
$mail->Password = "oluwadeji2";
$mail->SetFrom ("tayo.ogundeji@yahoo.com");
$mail->Subject = "$header";
$mail->Body = "$body";
$mail->AddAddress ($to);
 if(!$mail->Send())
    {
    //echo "Mailer Error: " . $mail->ErrorInfo;
    }
    else
    {
    //echo "Message has been sent";
    }
	
}
	public function pushNotification($username,$reactor,$postId,$postType,$message){
	$curDate="20".date("y-m-d h:i:s");
	$this->dbConnectMysql("sysdb");
	if($postType!="0")
	{
$sql = mysqli_query($this->db,"SELECT * FROM sagdb.tbl_notification_settings where username='$username' and status='0'");
if(mysqli_num_rows($sql) > 0){
$result = mysqli_fetch_assoc($sql);
$token=$result["token"];
//echo $token;
$this->GCMSEND_Notification($message,$token);
}
$insertNotification= mysqli_query($this->db,"INSERT INTO tbl_notification_mesage (id, postId, username, message, reactor, postType, notifyStatus, status,createdDate) VALUES (NULL, '$postId', '$username','$message','$reactor','$postType','0','0','$curDate') ");
if($insertNotification){

}


	}
	else {
$sql = mysqli_query($this->db,"SELECT a.username,b.token FROM tbl_notification_mesage a, sagdb.tbl_notification_settings b WHERE a.username=b.username and a.postId = '$postId' and a.username!='$reactor' and b.token!='0'  and b.status='0' group by a.username order by createdDate desc");
	$arrayToken= array();
				if(mysqli_num_rows($sql) > 0){
					foreach ( $sql as $rows ) {
					$theUsername=$rows["username"];
					$token=$rows["token"];
					array_push($arrayToken, $token);
					
$insertNotification= mysqli_query($this->db,"INSERT INTO tbl_notification_mesage (id, postId, username, message, reactor, postType, notifyStatus, status,createdDate) VALUES (NULL, '$postId', '$theUsername','$message','$reactor','$postType','0','0','$curDate') ");
if($insertNotification){
$this->GCMSEND_Notification($message,$arrayToken);
}
					
					}
					
					}
					else{
$sql = mysqli_query($this->db,"SELECT * FROM sagdb.tbl_notification_settings where username='$username'  and status='0'");
if(mysqli_num_rows($sql) > 0){
$result = mysqli_fetch_assoc($sql);
$token=$result["token"];
$this->GCMSEND_Notification($message,$token);
}
$insertNotification= mysqli_query($this->db,"INSERT INTO tbl_notification_mesage (id, postId, username, message, reactor, postType, notifyStatus, status,createdDate) VALUES (NULL, '$postId', '$username','$message','$reactor','$postType','0','0','$curDate') ");
if($insertNotification){
}		
					}
	}
}

public function dateDiffApply($time){
	$time=strtotime($time) ;
            $time = time() - $time; // to get the time since that moment
            $time = ($time<1)? 1 : $time;
            $tokens = array (
                31536000 => 'year',
                2592000 => 'month',
                604800 => 'week',
                86400 => 'day',
                3600 => 'hour',
                60 => 'minute',
                1 => 'second'
            );

            foreach ($tokens as $unit => $text) {
                if ($time < $unit) continue;
                $numberOfUnits = floor($time / $unit);
                return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ago':' ago');
            }

}

	}	
?>