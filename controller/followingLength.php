<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class FollowingLengthClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");					// Initiate Database connection
		}
function followingLength($username){
	$sql = mysqli_query($this->db,"SELECT count(*) as neighbourCount FROM tbl_neighbours a WHERE username = '$username' LIMIT 1");
$result = mysqli_fetch_assoc($sql);
$num_docs = $result["neighbourCount"];
$length = array('status' => "success", "followingLength" => $num_docs);
$this->response($this->json($length), 200);	
			
		
	}
}
?>