
<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class verifyNotificationClass extends REST{

//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sagdb");				// Initiate Database connection
		}
function verifyNotification($username,$token){
$sql = mysqli_query($this->db,"SELECT * FROM tbl_notification_settings WHERE username = '$username'");
if(mysqli_num_rows($sql) > 0){
$result = mysqli_fetch_assoc($sql);
$tokenField = $result["token"];

  if($tokenField=="0"){
$updateTable= mysqli_query($this->db,"update tbl_notification_settings set token = '$token' WHERE username = '$username'");
if($updateTable){
					$msg = array('status' => "success", "message" => "notification updated successfully");
					$this->response($this->json($msg), 200);	

}

}

}
else{
$registerTokenSql= mysqli_query($this->db,"INSERT INTO tbl_notification_settings (id, username, status,token) VALUES (NULL, '$username', '0', '$token') ");
if($registerTokenSql){
					$msg = array('status' => "success", "message" => "notification updated successfully");
					$this->response($this->json($msg), 200);
}
}


			
		
	}
}
?>