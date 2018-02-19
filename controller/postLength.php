<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class PostLengthClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();	
			$this->dbConnectMysql("sysdb");				// Initiate Database connection
		}
function postLength($username){
$sql = mysqli_query($this->db,"SELECT count(*) as postCount FROM tbl_event_posts a WHERE username = '$username' LIMIT 1");
$result = mysqli_fetch_assoc($sql);
$num_docs = $result["postCount"];
$length = array('status' => "success", "postLength" => $num_docs);
$this->response($this->json($length), 200);	
		
	}
}
?>