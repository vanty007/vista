<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
class sendPasswordClass extends REST{
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");					// Initiate Database connection
		}
function sendPassword($username){
	
					$sql = mysqli_query($this->db,"SELECT username,email,password,status FROM sagdb.tbl_user_auth_info WHERE username = '$username' LIMIT 1");
					if(mysqli_num_rows($sql) > 0){
						$result = mysqli_fetch_assoc($sql);
						$email=$result["email"];
						$password=$result["password"];
						if ($result["status"]=="1")
						{
					$invalidMessage = array('status' => "warning", "message" => "This account is currently locked, please unlock it first");
					$this->response($this->json($invalidMessage), 406);	
				}
				else{
					$message = array('message' => "Your password has been sent to your mail account");
					$this->response($this->json($message), 200);
					$this->sendmail("Vista account password recovery","Please find your password. Thanks"."</br>Password:".$password,$email);
				}

					}
					
					else{
					$invalidMessage = array('status' => "error", "message" => "This user can not be found, please register this user");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}
				//}
				mysqli_close($this->db);
			}
			
		}
		
	
?>