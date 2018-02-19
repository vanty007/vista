
<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
define('UPLOAD_DIR', 'images/upload/');

class UploadPhotoClass extends REST{

//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");			// Initiate Database connection
		}
function uploadPhoto($file,$fileType,$fileName,$user,$status){

$validextensions = array("jpeg", "jpg", "png");
$temporary = explode(".", $fileName);
$file_extension = end($temporary);

if($status=="0"){

if ((($fileType == "image/png") || ($fileType == "image/jpg") || ($fileType == "image/jpeg")
) ) {

$sourcePath = $file; // Storing source path of the file in a variable
$targetPath = $fileName; // Target path where file is to be stored

$file_name = 'images/upload/' . $user.uniqid().uniqid().".jpg";
move_uploaded_file($file, $file_name);
echo $file_name;

}
else
{
echo "invalid image format";
}

}

if($status=="1"){
if ((($fileType == "image/png") || ($fileType == "image/jpg") || ($fileType == "image/jpeg")
) ) {

$sourcePath = $file; // Storing source path of the file in a variable
$targetPath = $fileName; // Target path where file is to be stored

$file_name = 'images/profile/' . $user.".jpg";
//echo $file_name;
if(file_exists($file_name)){
unlink($file_name);
move_uploaded_file($file, $file_name);
echo $user;
}

else{
move_uploaded_file($file, $file_name);
echo $user;

}


}
else
{
echo "invalid image format";
}

}

}
}
?>