
<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
ini_set("allow_url_fopen", 1);
//define('UPLOAD_DIR', 'images/upload/');

class UploadPhotoClass extends REST{

//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");			// Initiate Database connection
		}
function uploadPhoto($file,$fileType,$fileName,$user,$text, $longitude, $latitude, $username){

$validextensions = array("jpeg", "jpg", "png");
$temporary = explode(".", $fileName);
$file_extension = end($temporary);

if ((($fileType == "image/png") || ($fileType == "image/jpg") || ($fileType == "image/jpeg")
) ) {

$sourcePath = $file; // Storing source path of the file in a variable
$targetPath = $fileName; // Target path where file is to be stored

$file_name = 'images/upload/' . $user.uniqid().uniqid().".jpg";
move_uploaded_file($file, $file_name);
$this->postFeed($user,$text,$file_name, $longitude, $latitude, $username);
//echo $file_name;

}
else
{
echo "invalid image format";
}


}

function postFeed($username, $text, $image, $longitude, $latitude, $user){
$image='/'.$image;
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

$Sql= mysqli_query($this->db,"INSERT INTO tbl_event_posts (postId, username, postDescribe,eventType,postText,postImage,longitude,latitude,address,status,createdDate) VALUES ('$postId', '$username', '$postDescribe', '0','$text','$image','$longitude','$latitude','$address','0','$curDate') ");
if($Sql){
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