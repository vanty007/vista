
<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
ini_set("allow_url_fopen", 1);

class SharePostClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();
			$this->dbConnectMysql("sysdb");
		}
function shareThePost($neighbour,$username,$postText,$postImage,$eventType,$longitude,$latitude,$user){
//$json = file_get_contents('http://localhost/images/json.json');
//$myfile="https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCyJ4dfsPpvX6wGlOEIcjg8AJUXV_rHJO0&latlng=6.59651,3.44711&sensor=true";
$myfile="https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCyJ4dfsPpvX6wGlOEIcjg8AJUXV_rHJO0&latlng=".$latitude.",".$longitude."&sensor=true";
//echo $myfile;
try{
$json = @file_get_contents($myfile);
if($json === false){
$address="";
}
else{
$obj = json_decode($json);
if($obj->status=="ZERO_RESULTS"){
$address="";
	}
	else{
$address=$obj->results[0]->address_components[2]->long_name.", ".$obj->results[0]->address_components[4]->long_name;
//$address=$obj->results[0]->formatted_address;
}
}

}
catch(exception $e){
$address="";
}
$neighbourSql = mysqli_query($this->db,"SELECT username FROM tbl_register_user a WHERE no = '$neighbour' LIMIT 1");
$neighbourResult = mysqli_fetch_assoc($neighbourSql);
$neighbourUser = $neighbourResult["username"];


$postDescribe=$user." shared ".$neighbourUser." post";
$curDate="20".date("y-m-d h:i:s");
$postId=md5($user.$curDate);
if ($eventType=="0"){
	//posting image alone
	if ($postText=="" && $postImage!="0"){

$imageOnlySql= mysqli_query($this->db,"INSERT INTO tbl_event_posts (postId, username, postDescribe,eventType,postText,postImage,longitude,latitude,address,status,createdDate) VALUES ('$postId', '$username', '$postDescribe', '$eventType','','$postImage','$longitude','$latitude','$address','0','$curDate') ");
if($imageOnlySql){
$postSuccess = array('status' => "successful", "message" => "successful");
$this->response($this->json($postSuccess), 200);
if($user==$neighbourUser){}
else{
$this->pushNotification($neighbourUser,$user,$postId,"2","$user shared $neighbourUser post");
}
}
else {
$postError = array('status' => "error", "message" => mysqli_error($this->db));
$this->response($this->json($postError), 200);

}


}
//end posting image alone

	//posting image/text
		else if ($postText!="" && $postImage!="0"){
$imageTextSql= mysqli_query($this->db,"INSERT INTO tbl_event_posts (postId, username, postDescribe,eventType,postText,postImage,longitude,latitude,address,status,createdDate) VALUES ('$postId', '$username', '$postDescribe', '$eventType','$postText','$postImage','$longitude','$latitude','$address','0','$curDate') ");
if($imageTextSql){
$postSuccess = array('status' => "successful", "message" => "successful");
$this->response($this->json($postSuccess), 200);
if($user==$neighbourUser){}
else{
$this->pushNotification($neighbourUser,$user,$postId,"2","$user shared $neighbourUser post");
}
}
else {
$postError = array('status' => "error", "message" => mysqli_error($this->db));
$this->response($this->json($postError), 200);

}

}
	// posting text alone
	else if ($postText!="" && $postImage=="0"){
$textOnlySql= mysqli_query($this->db,"INSERT INTO tbl_event_posts (postId, username, postDescribe,eventType,postText,postImage,longitude,latitude,address,status,createdDate) VALUES ('$postId', '$username', '$postDescribe', '$eventType','$postText','0','$longitude','$latitude','$address','0','$curDate') ");
if($textOnlySql){
$postSuccess = array('status' => "successful", "message" => "successful");
$this->response($this->json($postSuccess), 200);
if($user==$neighbourUser){}
else{
$this->pushNotification($neighbourUser,$user,$postId,"2","$user shared $neighbourUser post");
}
}
else {
$postError = array('status' => "error", "message" => mysqli_error($this->db));
$this->response($this->json($postError), 200);

}

}//end posting text alone

	} 

}
}

?>