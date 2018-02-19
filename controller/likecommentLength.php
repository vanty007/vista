<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class LikecommentLengthClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");
		}
function likecommentLength($username){
$array=array();

$likesql = mysqli_query($this->db,"SELECT count(*) as likeCount FROM tbl_like a WHERE username = '$username' LIMIT 1");
$likeresult = mysqli_fetch_assoc($likesql);
$likeCount = $likeresult["likeCount"];

$commentsql = mysqli_query($this->db,"SELECT count(*) as commentCount FROM tbl_comments a WHERE username = '$username' LIMIT 1");
$commentresult = mysqli_fetch_assoc($commentsql);
$commentCount = $commentresult["commentCount"];

$followersSql = mysqli_query($this->db,"SELECT count(*) as followersCount FROM tbl_neighbours a WHERE neighbour = '$username' LIMIT 1");
$followersResult = mysqli_fetch_assoc($followersSql);
$followersCount = $followersResult["followersCount"];

$followeringSql = mysqli_query($this->db,"SELECT count(*) as followeringCount FROM tbl_neighbours a WHERE username = '$username' LIMIT 1");
$followeringResult = mysqli_fetch_assoc($followeringSql);
$followeringCount = $followeringResult["followeringCount"];

$postSql = mysqli_query($this->db,"SELECT count(*) as postCount FROM tbl_event_posts a WHERE username = '$username' LIMIT 1");
$postResult = mysqli_fetch_assoc($postSql);
$postCount = $postResult["postCount"];

$profileSql = mysqli_query($this->db,"SELECT picture FROM tbl_profile_pics a WHERE username = '$username' LIMIT 1");
$profileResult = mysqli_fetch_assoc($profileSql);
$profilePics = $profileResult["picture"];

$this->dbConnectMysql("sagdb");
$notificationsql = mysqli_query($this->db,"SELECT status FROM tbl_notification_settings a WHERE username = '$username' LIMIT 1");
$notificationresult = mysqli_fetch_assoc($notificationsql);
$notificationCount = $notificationresult["status"];

array_push($array, array("likeLength"=>$likeCount,"commentLength"=>$commentCount,"followersCount"=>$followersCount,"followeringCount"=>$followeringCount,"postCount"=>$postCount,"notificationStatus"=>$notificationCount,"profilePics"=>$profilePics));

$this->response($this->json($array), 200);	

			
		
	}
}
?>