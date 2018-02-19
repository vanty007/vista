
<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
//define('UPLOAD_DIR', 'http://localhost:8081/vistawww/images/upload/');

class DeletePostClass extends REST{

//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");			// Initiate Database connection
		}
function deletePost($postId){
$sql = mysqli_query($this->db,"SELECT postImage FROM tbl_event_posts  WHERE postId = '$postId' LIMIT 1");
if(mysqli_num_rows($sql) > 0){
$result = mysqli_fetch_assoc($sql);
		if ($result["postImage"]=="0" || $result["postImage"]==""){
$deleteTable= mysqli_query($this->db,"delete from tbl_event_posts WHERE postId = '$postId'");
if($deleteTable){
$blockSuccess = array('status' => "successful", "message" => "successful");
$this->response($this->json($blockSuccess), 200);
}	

}
		else {
$str = substr($result["postImage"], 1);
$file_name = $str;
unlink($file_name);
$deleteTable= mysqli_query($this->db,"delete from tbl_event_posts WHERE postId = '$postId'");
if($deleteTable){
$blockSuccess = array('status' => "successful", "message" => "successful");
$this->response($this->json($blockSuccess), 200);
}
}

}
else{
$blockError = array('status' => "error", "message" => "This post does not exist");
$this->response($this->json($blockError), 406);	
	}
}
}
?>