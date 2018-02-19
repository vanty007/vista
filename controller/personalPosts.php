<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class PersonalPostClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");			// Initiate Database connection
		}
function personalPost($username,$count){
$limit=10;
$array=array();
$sql = mysqli_query($this->db,"SELECT a.postId,a.username,c.username as user,b.picture,a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate FROM tbl_event_posts a, tbl_profile_pics b, tbl_register_user c WHERE a.username=b.username and a.username = '$username' and a.username=c.no and (a.postImage!='0' and a.postImage!='')   and a.status!='1' order by a.createdDate desc limit $count, $limit");
					if(mysqli_num_rows($sql) > 0){
					$data=array();

					foreach ( $sql as $rows ) {
					array_push($data, array("postId" => $rows["postId"],"username" => $rows["username"], "user" => $rows["user"],"picture" => $rows["picture"],"postDescribe" => $rows["postDescribe"],"eventType" => $rows["eventType"],"postText" => $rows["postText"],"postImage" => $rows["postImage"],"longitude" => $rows["longitude"],"latitude" => $rows["latitude"],"address" => $rows["address"],"createdDate" => $this->dateDiffApply($rows["createdDate"])));
					
					}
					$data1=array('myPersonalPost'=>$data);
					array_push($array, (object)$data1);
					$this->response($this->json($array), 200);
					
					}
					else{
					$invalidMessage = array('status' => "bug", "message" => "no post available");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}
	

	
		
	}
}
?>

