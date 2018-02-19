<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
class RegisterUserClass extends REST{
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");					// Initiate Database connection
		}
function register($firstName, $lastName, $username, $password, $email, $option){

					$query = mysqli_query( $this->db,"SELECT email FROM tbl_register_user WHERE email = '$email' LIMIT 1");
					$query1 = mysqli_query( $this->db,"SELECT username FROM tbl_register_user WHERE username = '$username' LIMIT 1");
					if(mysqli_num_rows($query) > 0){
					$queryMessage = array('status' => "duplicate", "message" => "email already exist");
					$this->response($this->json($queryMessage), 406);
					}
					else if (mysqli_num_rows($query1) > 0){
					$queryMessage1 = array('status' => "duplicate", "message" => "username already exist");
					$this->response($this->json($queryMessage1), 406);
					}
					else {
					$curDate=date("y-m-d h:i:s");
					$realUsername="";
				if($option=="real"){
					$api_key=md5($username.$password.$curDate);
					$encyptPassword=md5($password);
					$realUsername=preg_replace("/[\s_]/", "_", $username);
				}
					else if($option=="facebook" || $option=="google"){
					$api_key=md5($username.$password.$curDate);
					$encyptPassword="";
					$randNumeric = mt_rand(1000, 9999);
					$realUsername=preg_replace("/[\s_]/", "_", $username)."_".$randNumeric;
				}

					$sql= mysqli_query($this->db,"INSERT INTO tbl_register_user (no, firstName, lastName,username, password, api_key, role_id, createdDate, updatedDate, 
					email, status,type) VALUES (NULL, '$firstName', '$lastName', '$realUsername', '$encyptPassword','$api_key', '0', '$curDate', '$curDate', '$email', '1','$option') ");
					if($sql){
					$authSql = mysqli_query($this->db,"SELECT * FROM tbl_register_user a WHERE (username = '$realUsername' OR email='$realUsername') LIMIT 1");
					$authResult = mysqli_fetch_assoc($authSql);

					$registerSuccess = array('status' => "successful", "id" => $authResult["no"],"firstname" => $firstName, "lastName" => $lastName, "username" => $realUsername, "api_key" => $api_key, "role_id" => "0"
					, "email" => $email);
					$this->response($this->json($registerSuccess), 200);
					$this->sendmail("Vista Account Creation","Your account have been created successfully, please kindly surf the beauty of vista",$email);
					}
					else if (!$sql){
					$bugError = array('status' => "bug", "message" => "Something went wrong internally");
					$this->response($this->json($bugError), 406);	
					}	
				}
		}
		//mysql_close($this->db);
		
	}
?>