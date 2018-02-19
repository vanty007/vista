<?php
header('Access-Control-Allow-Origin: *');
ini_set("allow_url_fopen", 1);
require_once("Rest.inc.php");

class PostClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();
			$this->dbConnectMysql("sysdb");
		}
function postFeed($username,$text, $longitude, $latitude,$user){
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
$postDescribe=$user.' posted';
$curDate="20".date("y-m-d h:i:s");
$postId=md5($user.$curDate);

$textOnlySql= mysqli_query($this->db,"INSERT INTO tbl_event_posts (postId, username, postDescribe,eventType,postText,postImage,longitude,latitude,address,status,createdDate) VALUES ('$postId', '$username', '$postDescribe', '0','$text','0','$longitude','$latitude','$address','0','$curDate') ");
if($textOnlySql){
$postSuccess = array('status' => "successful", "message" => "successful");
$this->response($this->json($postSuccess), 200);
}
else {
$postError = array('status' => "error", "message" => mysqli_error($this->db));
$this->response($this->json($postError), 200);

}

}

	} 



?>