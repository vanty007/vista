<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class CheckFollowClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");					// Initiate Database connection
		}
function checkFollow($username,$neighbour){

$sql = mysqli_query($this->db,"SELECT count(*) as checkFollow FROM tbl_neighbours a WHERE username = '$username' and neighbour='$neighbour' and followStatus='0' LIMIT 1");
$result = mysqli_fetch_assoc($sql);
$num_docs = $result["checkFollow"];
$length = array('status' => "success", "checkFollow" => $num_docs);
$this->response($this->json($length), 200);	
			
		
	}
}
?>

