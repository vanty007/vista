<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
class EditProfileClass extends REST{
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");					// Initiate Database connection
		}
function editProfileMethod($username,$editUsername, $firstName,$lastName,$dateOfBirth,$sex,$nationality,$stateOfBirth,$relationship,$mobileNo){
					$realUsername=preg_replace("/[\s_]/", "_", $editUsername);
					$query = mysqli_query($this->db,"SELECT username FROM tbl_register_user WHERE ( username='$username' or email = '$username') LIMIT 1");
					if(mysqli_num_rows($query) > 0){

					$sql = mysqli_query($this->db,"UPDATE tbl_register_user a 
					SET a.firstName='$firstName',a.lastName='$lastName' ,a.dateOfBirth='$dateOfBirth',a.sex='$sex',a.nationality='$nationality',a.stateOfBirth='$stateOfBirth',
					a.relationship='$relationship',a.mobileNo='$mobileNo',a.username='$realUsername'
					WHERE username = '$username' or email = '$username' ");
					
					if($sql){
					$editProfileMessage = array('status' => "success", "message" => "0");
					$this->response($this->json($editProfileMessage), 200);

					/*$updateLoginAuth = mysqli_query($this->db,"UPDATE tbl_user_auth_info a 
					SET a.username='$realUsername' WHERE username = '$username' or email = '$username' ");

					$updateApiAccess = mysqli_query($this->db,"UPDATE tbl_api_access a 
					SET a.username='$realUsername' WHERE username = '$username'");

					$updateActiveUser = mysqli_query($this->db,"UPDATE tbl_user_active a 
					SET a.username='$realUsername' WHERE username = '$username'");

					if($updateLoginAuth && $updateApiAccess && $updateActiveUser){}*/

					
					}
					else{//"something went wrong internally, please try again".
					$editProfileErrorMessage = array('status' => "error", "message" => mysqli_error($this->db));
					$this->response($this->json($editProfileErrorMessage), 406);	
		}
					}
					else {
					$queryMessage1 = array('status' => "user not exist", "message" => "username does not exist");
					$this->response($this->json($queryMessage1), 406);
					}
					
				}
		}
		//mysql_close($this->db);
		
?>