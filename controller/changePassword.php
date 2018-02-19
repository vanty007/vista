<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
class ChangePasswordClass extends REST{
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sagdb");					// Initiate Database connection
		}
function ChangeUserPassword($username, $password,$oldPassword){
$uPassword=md5($password);
if($oldPassword==""){
$uOldPassword=$oldPassword;
}
else{
$uOldPassword=md5($oldPassword);
}
					$query = mysqli_query( $this->db,"SELECT username FROM tbl_user_auth_info WHERE  password='$uOldPassword' and( username='$username' or email = '$username') LIMIT 1");
					if(mysqli_num_rows($query) > 0){
					$sql = mysqli_query($this->db,"UPDATE tbl_user_auth_info a SET a.password='$uPassword' WHERE username = '$username' or email = '$username' ");
					
					if($sql){
					$passwordChangeMessage = array('status' => "success", "message" => "0");
					$this->response($this->json($passwordChangeMessage), 200);
					
					}
					else{
					$passwordChangeErrorMessage = array('status' => "error", "message" => "something went wrong, password not changed");
					$this->response($this->json($passwordChangeErrorMessage), 401);	
		}
					}
					else {
					$queryMessage1 = array('status' => "user not exist", "message" => "Password mismatched!!");
					$this->response($this->json($queryMessage1), 401);
					}
					
				}
		}
		//mysql_close($this->db);
		
?>