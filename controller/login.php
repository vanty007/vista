<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
class LoginClass extends REST{
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sagdb");					// Initiate Database connection
		}
function login($username, $password,$option){
$uPassword=md5($password);
			if(!empty($username)){
				//if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					if($option=="real"){
					$sql = mysqli_query($this->db,"SELECT a.username,a.status,a.api_key,b.no FROM tbl_user_auth_info a, sysdb.tbl_register_user b WHERE (a.username = '$username' OR a.email='$username') AND a.password = '$uPassword' and a.username=b.username LIMIT 1");
				}
					else if($option=="facebook" || $option=="google"){
					$sql = mysqli_query($this->db,"SELECT b.no,a.username,a.status,a.api_key FROM tbl_user_auth_info a, sysdb.tbl_register_user b WHERE (a.username = '$username' OR a.email='$username') and a.username=b.username LIMIT 1");
				}
					if(mysqli_num_rows($sql) > 0){
						$result = mysqli_fetch_assoc($sql);
						if ($result["status"]=="0")
						{
					$curDate=date("y-m-d h:i:s");
					$api_key=md5($username.$password.$curDate);
					$updateTable= mysqli_query($this->db,"update tbl_user_active set dateOnline = '$curDate' WHERE username = '$username'");
					$updateTable1= mysqli_query($this->db,"update tbl_user_auth_info set api_key = '$api_key' WHERE (username = '$username' OR email='$username')");
					if($updateTable){
						if($updateTable1){
					$loginSuccess = array('status' => "successful", "id" => $result["no"], "username" => $result["username"], "status" => $result["status"], "api_key" => $api_key);
					$this->response($this->json($loginSuccess), 200);
				}
					}
					else if (!$updateTable){
					$bugError = array('status' => "bug", "message" => "Something went wrong internally");
					$this->response($this->json($bugError), 401);	
					}	
					}
					else{
						$lockMessage = array('status' => "locked", "message" => "you are temporarily locked, please use alternate login");
						$this->response($this->json($lockMessage), 400);
					}
					}
					else{
					$invalidMessage = array('status' => "Invalid Login", "message" => "invalid login details, please sign up!!");
					$this->response($this->json($invalidMessage), 404);	// If no records "No Content" status
				}
				//}
			}
			
			// If invalid inputs "Bad Request" status message and reason
			else{
			$error = array('status' => "Failed", "message" => "Invalid username or Password");
			$this->response($this->json($error), 400);
		}
			mysqli_close($this->db);
		}
		
	}
?>