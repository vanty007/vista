<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class LoadNotifcationClass extends REST{

//$mycollection="";
public function __construct(){
			parent::__construct();	
			$this->dbConnectMysql("sysdb");			// Init parent contructor
		}
function loadNotifcation($username,$count){
$limit=30;
$array=array();

$sql = mysqli_query($this->db,"SELECT a.postId,c.username as user, a.username, b.picture, a.message, a.reactor,a.postType, a.notifyStatus, a.status, a.createdDate  FROM tbl_notification_mesage a, tbl_profile_pics b, tbl_register_user c WHERE a.reactor=b.username and a.username=c.no and a.username = '$username' order by a.createdDate desc limit $count, $limit");
					if(mysqli_num_rows($sql) > 0){
					$row=mysqli_fetch_array($sql,MYSQLI_ASSOC);
					$data=array();
					$statusBar="";
					foreach ( $sql as $rows ) {
					if($rows["status"]=="0"){
						$statusBar="list-group-item-warning";
					}
					else{
						$statusBar="";
					}
						array_push($data, array("postId" => $rows["postId"],"user" => $rows["user"],"username" => $rows["username"],"picture" => $rows["picture"],"message" => $rows["message"],"reactor" => $rows["reactor"],"postType" => $rows["postType"],"notifyStatus" => $rows["notifyStatus"],"status" => $rows["status"],"statusBar" => $statusBar,"createdDate" => $this->dateDiffApply($rows["createdDate"])));
					
					

					//array_push($data, (object)$rows);
					
					}
					$data1=array('loadNotifcation'=>$data);
					array_push($array, (object)$data1);
					$this->response($this->json($array), 200);
					
					}
					else{
					$invalidMessage = array('status' => "error", "message" => "You have no notification");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}

	}
}
?>