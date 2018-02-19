
<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class TogglePostStatusClass extends REST{

//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");			// Initiate Database connection
		}
function togglePostStatus($postId,$value){
$updateTable= mysqli_query($this->db,"update tbl_event_posts set status = '$value' WHERE postId='$postId'");
if($updateTable){
$blockSuccess = array('status' => "successful", "message" => "successful");
$this->response($this->json($blockSuccess), 200);
}	
	}
}
?>