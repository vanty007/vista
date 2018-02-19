<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
class LinkConfirmClass extends REST{
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");					// Initiate Database connection
		}
function linkConfirm($link){
					$sql = mysqli_query($this->db,"SELECT link,username FROM tbl_verify_links WHERE link = '$link'LIMIT 1");
					if(mysqli_num_rows($sql) > 0){
						$result = mysqli_fetch_assoc($sql);
						$username=$result["username"];

					$updateTable= mysqli_query($this->db,"update sagdb.tbl_user_auth_info set status=0  WHERE username = '$username'");
					if($updateTable){
					$message = array('message' => "Your account has been unlocked, please login from vista app now");
					$this->response($this->json($message), 200);
					mysqli_query($this->db,"delete from tbl_verify_links WHERE link = '$link'");
				}

					}
					
					else{
					$invalidMessage = array('status' => "error", "message" => "This link cannot be found, please go to vista app to register unlock of your account again");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}
				//}
				mysqli_close($this->db);
			}
			
		}
		
	
?>