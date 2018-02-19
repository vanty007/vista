<?php
header('Access-Control-Allow-Origin: *');
ini_set("allow_url_fopen", 1);
require_once("Rest.inc.php");
class testingApiclass extends REST{
public function __construct(){
			parent::__construct();						// Initiate Database connection
		}
	function testingApimethod(){
//echo $this->PAYMENTCONFIRMATIONMethod("500850441500651531193");
 //$this->pushNotification("nelson","ola","2","2","how are you oo");
$myfile="https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCyJ4dfsPpvX6wGlOEIcjg8AJUXV_rHJO0&latlng=6.59651,3.44711&sensor=true";
//echo $myfile;
$json = file_get_contents($myfile);
$obj = json_decode($json);
if($obj->status=="ZERO_RESULTS"){
echo $obj->status;
	}
	else{
echo $obj->results[0]->address_components[2]->long_name.", ".$obj->results[0]->address_components[4]->long_name;
//echo $obj->results[0]->formatted_address;
}
		}
		
	}
?>