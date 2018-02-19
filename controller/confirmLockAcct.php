<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
class ConfirmLockAcctClass extends REST{
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sagdb");					// Initiate Database connection
		}
function confirmLockAcct($username){
			
				//if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$sql = mysqli_query($this->db,"SELECT a.username,a.status FROM tbl_user_auth_info a WHERE no = '$username' LIMIT 1");
					if(mysqli_num_rows($sql) > 0){
						$result = mysqli_fetch_assoc($sql);
						$this->response($this->json($result), 200);
					}
					else{
					$invalidMessage = array('status' => "invalidUser", "message" => "Invalid user");
					$this->response($this->json($invalidMessage), 406);	
					}
		
			mysqli_close($this->db);
		}
		
	}
?>