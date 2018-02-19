<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
class SwitchClass extends REST{
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sagdb");					// Initiate Database connection
		}
function switchLockNotification($username,$switchEvent, $switchStatus){
					if($switchEvent=="0"){
					$sql = mysqli_query($this->db,"UPDATE tbl_user_auth_info a SET a.status='$switchStatus' WHERE username = '$username'");
					if($sql){
					$lockStatus = array('status' => "success", "message" => "0");
					$this->response($this->json($lockStatus), 200);
					}
					else{
					$invalidMessage = array('status' => "bug", "message" => "user does not exist");
					$this->response($this->json($invalidMessage), 406);	
					mysql_close($this->db);
		}
	}

		if($switchEvent=="1"){

					$sql = mysqli_query($this->db,"UPDATE tbl_notification_settings a SET a.status='$switchStatus' WHERE username = '$username'");
					if($sql){
					$notificationStatus = array('status' => "success", "message" => "0");
					$this->response($this->json($notificationStatus), 200);
					}
				}
		}
		
	}
?>