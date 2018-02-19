<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
class CreateLinkClass extends REST{
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");					// Initiate Database connection
		}
function createLink($username){
	
					$sql = mysqli_query($this->db,"SELECT username,email,status FROM sagdb.tbl_user_auth_info WHERE username = '$username' LIMIT 1");
					if(mysqli_num_rows($sql) > 0){
						$result = mysqli_fetch_assoc($sql);
						$email=$result["email"];
						if ($result["status"]=="0")
						{
					$invalidMessage = array('status' => "warning", "message" => "This account is not locked, please login");
					$this->response($this->json($invalidMessage), 406);	
				}
				else{
				mysqli_query($this->db,"delete from tbl_verify_links WHERE username = '$username'");
				$curDate=date("y-m-d h:i:s");
				$link=md5($username.$curDate);
				$insertSql= mysqli_query($this->db,"INSERT INTO tbl_verify_links (id, username, link, createdDate) VALUES (NULL, '$username', '$link', NULL) ");
					if($insertSql){
					$message = array('message' => "link to unlock your account has been sent to your email");
					$this->response($this->json($message), 200);
					$this->sendmail("Vista account unlock permission","Please find and click link (http://localhost:8081/vista/linkConfirm/".$link.") in mail to grant permission for the unlock of your vista account. Thanks",$email);
				}

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