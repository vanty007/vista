<?php
header('Access-Control-Allow-Origin: *');
 require_once("Rest.inc.php");

class PostCommentClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();
			$this->dbConnectMysql("sysdb");
		}
function pushComment($postId, $username, $comment, $realUser, $user){
$curDate=date("y-m-d h:i:s");
$commentSql= mysqli_query($this->db,"INSERT INTO tbl_comments (id, username, postId,createdDate,comment) VALUES (NULL, '$username', '$postId', NULL,'$comment') ");
if($commentSql){
$commentSuccess = array('status' => "successful", "message" => "successful");
$this->response($this->json($commentSuccess), 200);
//pushNotification($username,$reactor,$postId,$postType,$message)
if($username==$realUser){}
else{
$this->pushNotification($realUser,$username,$postId,"0","$user commented on $realUser post");
}
}


}
}
?>