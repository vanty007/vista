
<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class UpdateNotificationClass extends REST{

//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");			// Initiate Database connection
		}
function updateNotification($reactor,$postId){
$updateTable= mysqli_query($this->db,"update tbl_notification_mesage set status = '1' WHERE reactor = '$reactor' and postId='$postId'");
if($updateTable){
$blockSuccess = array('status' => "successful", "message" => "successful");
$this->response($this->json($blockSuccess), 200);
}	
	}
}
?>