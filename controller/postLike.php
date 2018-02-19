<?php
header('Access-Control-Allow-Origin: *');
 require_once("Rest.inc.php");
class PostLikeClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();
			$this->dbConnectMysql("sysdb");
		}
function pushLike($postId, $username,$realUser,$user){

$sql = mysqli_query($this->db,"SELECT * FROM tbl_like a WHERE username = '$username' and postId='$postId'");
if(mysqli_num_rows($sql) > 0){
$result = mysqli_fetch_assoc($sql);
$deleteTable= mysqli_query($this->db,"delete from tbl_like where username = '$username' and postId='$postId'");
$deleteTable1= mysqli_query($this->db,"delete from tbl_notification_mesage where username = '$username' and postId='$postId'");
if($deleteTable && $deleteTable1){
$unLike = array('status' => "successful", "message" => "unliked");
$this->response($this->json($unLike), 200);
}

}
else{
$registerlIKESql= mysqli_query($this->db,"INSERT INTO tbl_like (id, username, postId,createdDate) VALUES (NULL, '$username', '$postId', NULL) ");
if($registerlIKESql){
$likeSuccess = array('status' => "successful", "message" => "liked");
$this->response($this->json($likeSuccess), 200);
if($username==$realUser){}
else{
$this->pushNotification($realUser,$username,$postId,"1","$user liked your post");
}
}
}


}
}
?>