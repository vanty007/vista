
<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");
define('UPLOAD_DIR', 'images/profile/');

class EditProfilePhotoClass extends REST{

//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");			// Initiate Database connection
		}
function editProfilePhoto($file,$fileType,$fileName,$user){

$validextensions = array("jpeg", "jpg", "png");
$temporary = explode(".", $fileName);
$file_extension = end($temporary);

if ((($fileType == "image/png") || ($fileType == "image/jpg") || ($fileType == "image/jpeg")
) ) {

$sourcePath = $file; // Storing source path of the file in a variable
$targetPath = $fileName; // Target path where file is to be stored


//$file_name = UPLOAD_DIR . $user.".jpg";
$file_name = UPLOAD_DIR . $user.uniqid().uniqid().".jpg";
//echo $file_name;
if(file_exists($file_name)){
unlink($file_name);
move_uploaded_file($sourcePath, $file_name);
//echo $user;
$this->savetoDb($user, $file_name);
}

else{
move_uploaded_file($sourcePath, $file_name);
$this->savetoDb($user, $file_name);
//echo $user;

}


}
else
{
echo "unable to upload";
}
}


function savetoDb($userid, $filename){
$filename='/'.$filename;
$curDate=date("y-m-d h:i:s");
$sql = mysqli_query($this->db,"SELECT username FROM tbl_profile_pics WHERE username = '$userid'  LIMIT 1");
if(mysqli_num_rows($sql) > 0){
$updateTable= mysqli_query($this->db,"update tbl_profile_pics set picture='$filename' where username = '$userid'");
if($updateTable){
$msg = array('status' => "successful");
$this->response($this->json($msg), 200);
	}
	}

	else{
$insertSql= mysqli_query($this->db,"INSERT INTO tbl_profile_pics (no, username, picture,createdDate) VALUES (NULL, '$userid', '$filename', '$curDate') ");
if($insertSql){
$msg = array('status' => "successful");
$this->response($this->json($msg), 200);
}
	}
}

}
?>