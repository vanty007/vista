<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
class ViewProfileClass extends REST{
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");					// Initiate Database connection
		}
function viewProfileMethod($username){

					$result = mysqli_query($this->db,"SELECT a.no,a.firstName,a.lastName,a.nationality,a.stateOfBirth,b.picture,a.username,a.dateOfBirth,a.relationship,a.sex,a.mobileNo FROM tbl_register_user a, tbl_profile_pics b WHERE a.no=b.username and ( a.no='$username' or a.email = '$username') LIMIT 1");

						if(mysqli_num_rows($result) > 0){
						$row = mysqli_fetch_assoc($result);
		//echo $row['email'];
						$this->response($this->json($row), 200);

	//
						}
					else {
					$queryMessage1 = array('status' => "user not exist", "message" => "username does not exist");
					$this->response($this->json($queryMessage1), 406);
					}
					
				}
		}
		//mysql_close($this->db);
		
?>