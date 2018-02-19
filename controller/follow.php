<?php
header('Access-Control-Allow-Origin: *');
 require_once("Rest.inc.php");
class FollowClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();
			$this->dbConnectMysql("sysdb");
		}
function triggerFollow($followEvent, $username, $neighbour,$longitude,$latitude,$followStatus,$status,$user){

if($followEvent=="0"){
$checkFollowSql = mysqli_query($this->db,"SELECT neighbour FROM tbl_neighbours WHERE username = '$username' and neighbour='$neighbour' LIMIT 1");
if(mysqli_num_rows($checkFollowSql) > 0){
$followError = array('status' => "error", "message" => "You already following this user");
$this->response($this->json($followError), 406);
}
else{
$followSql= mysqli_query($this->db,"INSERT INTO tbl_neighbours (id, username, neighbour,placeOfAdditionLongitude,placeOfAdditionLATITUDE,followStatus,status,createdDate) VALUES (NULL, '$username', '$neighbour', '$longitude', '$latitude', '$followStatus', '$status', NULL) ");
if($followSql){
$followSuccess = array('status' => "successful", "message" => "follow successful");
$this->response($this->json($followSuccess), 200);
$this->pushNotification($neighbour,$username,"follow","2","$user followed you");
}
else {
$followError = array('status' => "successful", "message" => mysqli_error($this->db));
$this->response($this->json($followError), 200);
}
}

}
if($followEvent=="1"){
$deleteTable= mysqli_query($this->db,"delete from tbl_neighbours where username = '$username' and neighbour='$neighbour'");
if($deleteTable){
$unfollowSuccess = array('status' => "successful", "message" => "unfollow successful");
$this->response($this->json($unfollowSuccess), 200);
}

}

if($followEvent=="2"){
$updateTable= mysqli_query($this->db,"update tbl_neighbours set status = '$status' WHERE username = '$username' and neighbour='$neighbour'");
if($updateTable){
$blockSuccess = array('status' => "successful", "message" => "successful");
$this->response($this->json($blockSuccess), 200);
}

}

}
}

?>
