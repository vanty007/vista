<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
ini_set("allow_url_fopen", 1);

class testClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			//$this->dbConnectMongodbVista();	
			//echo $this->dbConnectMongodbVista().$dbhost;				// Initiate Database connection
		}
function test(){
$json = file_get_contents('http://localhost/images/json.json');
//$json = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCyJ4dfsPpvX6wGlOEIcjg8AJUXV_rHJO0&latlng=6.59651,3.44711&sensor=true');
/*$decoded = json_decode($json);
$comments = $decoded->results;
foreach($comments as $comment){
   $name = $comment->formatted_address;
   echo $name;
   //$message = $comment->message;
   //do something with it
}*/

$obj = json_decode($json);
echo $obj->results[0]->formatted_address;
	

	
		
	}
}
?>

