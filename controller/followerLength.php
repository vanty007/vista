<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class FollowerLengthClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");					// Initiate Database connection
		}
function followerLength($username){
	$sql = mysqli_query($this->db,"SELECT count(*) as followerCount FROM tbl_neighbours a WHERE neighbour = '$username' LIMIT 1");
$result = mysqli_fetch_assoc($sql);
$num_docs = $result["followerCount"];
$length = array('status' => "success", "followerLength" => $num_docs);
$this->response($this->json($length), 200);	

			
		
	}
}
?>