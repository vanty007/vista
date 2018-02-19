<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class PersonPostClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");				// Initiate Database connection
		}
function personPost($postClickType,$username,$postId){
	$array=array();
	if($postClickType=="4" || $postClickType=="5" || $postClickType=="6"){
		if($postClickType=="5"){
$sql = mysqli_query($this->db,"SELECT a.postId,a.username,b.picture, a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate,a.status FROM tbl_event_posts a, tbl_profile_pics b WHERE a.username=b.username and a.username = '$username'  and a.status!='1' order by a.createdDate desc");
}

else if($postClickType=="6"){
$sql = mysqli_query($this->db,"SELECT a.postId,a.username,b.picture, a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate,a.status FROM tbl_event_posts a, tbl_profile_pics b WHERE a.username=b.username and a.username = '$username' and a.postImage!='0' and a.postImage!=''  and a.status!='1' order by a.createdDate desc");
}

else if($postClickType=="56"){
$sql = mysqli_query($this->db,"SELECT a.postId,a.username,b.picture, a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate,a.status FROM tbl_event_posts a, tbl_profile_pics b WHERE a.username=b.username and a.username = '$username' and a.postImage!='0' and a.postImage!='' order by a.createdDate desc");	
}
//for my personal post
else{
$sql = mysqli_query($this->db,"SELECT a.postId,a.username,b.picture, a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate,a.status FROM tbl_event_posts a, tbl_profile_pics b WHERE a.username=b.username and a.username = '$username' order by a.createdDate desc");	
}
$data=array();
					if(mysqli_num_rows($sql) > 0){
					foreach ( $sql as $rows ) {
					$postId1=$rows["postId"];
					//$postUser=$rows["username"];
					$commentSql = mysqli_query($this->db,"SELECT count(*) as commentCount FROM tbl_comments a,tbl_event_posts b WHERE  a.postId=b.postId and b.postId='$postId1' ");
					$likeSql = mysqli_query($this->db,"SELECT count(*) as likeCount FROM tbl_like a,tbl_event_posts b WHERE  a.postId=b.postId and b.postId='$postId1'");
					$result = mysqli_fetch_assoc($commentSql);
					$result1 = mysqli_fetch_assoc($likeSql);
					//array_push($rows, array("comment" => $result["commentCount"],"like" => $result1["likeCount"]));
					array_push($data, array("postId" => $rows["postId"],"username" => $rows["username"],"picture" => $rows["picture"],"postDescribe" => $rows["postDescribe"],"eventType" => $rows["eventType"],"postText" => $rows["postText"],"postImage" => $rows["postImage"],"longitude" => $rows["longitude"],"latitude" => $rows["latitude"],"address" => $rows["address"],"createdDate" => $this->dateDiffApply($rows["createdDate"]),"like" => $result1["likeCount"],"comment" => $result["commentCount"],"status" => $rows["status"]));
					//array_push($commentCount, array("comment" => mysqli_num_rows($commentSql)));
					
					}
					$data1=array('allMyPost'=>$data);
					array_push($array, (object)$data1);
					$this->response($this->json($array), 200);
					
					}
					else{
					$invalidMessage = array('status' => "bug", "message" => "no post available");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}
}

//liked post
else if($postClickType=="1"){
	if($postId!="0"){
$sql = mysqli_query($this->db,"SELECT b.postId,a.username,c.picture,b.postDescribe,b.eventType,b.postText,b.postImage,b.longitude,b.latitude,b.address,a.createdDate FROM tbl_like a,tbl_event_posts b, tbl_profile_pics c WHERE  a.postId=b.postId and a.postId='$postId' and b.username=c.username and a.username='$username'  and b.status!='1' ");
	}
	else{
$sql = mysqli_query($this->db,"SELECT * FROM tbl_event_posts a, tbl_like b, tbl_profile_pics c WHERE a.postId=b.postId and a.username=c.username and b.username = '$username' order by a.createdDate desc");
}
$data=array();
					if(mysqli_num_rows($sql) > 0){
						foreach ( $sql as $rows ) {
					$postId1=$rows["postId"];
					//$postUser=$rows["username"];
					$commentSql = mysqli_query($this->db,"SELECT count(*) as commentCount FROM tbl_comments a,tbl_event_posts b WHERE  a.postId=b.postId and b.postId='$postId1'");
					$likeSql = mysqli_query($this->db,"SELECT count(*) as likeCount FROM tbl_like a,tbl_event_posts b WHERE  a.postId=b.postId and b.postId='$postId1'");
					$result = mysqli_fetch_assoc($commentSql);
					$result1 = mysqli_fetch_assoc($likeSql);
					//array_push($rows, array("comment" => $result["commentCount"],"like" => $result1["likeCount"]));
					array_push($data, array("postId" => $rows["postId"],"username" => $rows["username"],"picture" => $rows["picture"],"postDescribe" => $rows["postDescribe"],"eventType" => $rows["eventType"],"postText" => $rows["postText"],"postImage" => $rows["postImage"],"longitude" => $rows["longitude"],"latitude" => $rows["latitude"],"address" => $rows["address"],"createdDate" => $this->dateDiffApply($rows["createdDate"]),"like" => $result1["likeCount"],"comment" => $result["commentCount"]));
					//array_push($commentCount, array("comment" => mysqli_num_rows($commentSql)));
					
					}
					$data1=array('allMyPost'=>$data);
					array_push($array, (object)$data1);
					$this->response($this->json($array), 200);
					
					}
					else{
					$invalidMessage = array('status' => "bug", "message" => "no post available");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}


	}

	else if($postClickType=="0"){
		if($postId!="0"){
$sql = mysqli_query($this->db,"SELECT b.postId,a.username,c.picture, b.postDescribe,b.eventType,b.postText,b.postImage,b.longitude,b.latitude,b.address,a.createdDate FROM tbl_comments a,tbl_event_posts b, tbl_profile_pics c WHERE  a.postId=b.postId and a.postId='$postId' and b.username=c.username and a.username='$username'  and b.status!='1'");
		}
		else{
$sql = mysqli_query($this->db,"SELECT * FROM tbl_event_posts a, tbl_comments b, tbl_profile_pics c WHERE a.postId=b.postId and a.username=c.username and b.username = '$username' order by a.createdDate desc");
}
$data=array();
					if(mysqli_num_rows($sql) > 0){
						foreach ( $sql as $rows ) {
					$postId1=$rows["postId"];
					//$postUser=$rows["username"];
					$commentSql = mysqli_query($this->db,"SELECT count(*) as commentCount FROM tbl_comments a,tbl_event_posts b WHERE  a.postId=b.postId and b.postId='$postId1'");
					$likeSql = mysqli_query($this->db,"SELECT count(*) as likeCount FROM tbl_like a,tbl_event_posts b WHERE  a.postId=b.postId and b.postId='$postId1'");
					$result = mysqli_fetch_assoc($commentSql);
					$result1 = mysqli_fetch_assoc($likeSql);
					//array_push($rows, array("comment" => $result["commentCount"],"like" => $result1["likeCount"]));
					array_push($data, array("postId" => $rows["postId"],"username" => $rows["username"],"picture" => $rows["picture"],"postDescribe" => $rows["postDescribe"],"eventType" => $rows["eventType"],"postText" => $rows["postText"],"postImage" => $rows["postImage"],"longitude" => $rows["longitude"],"latitude" => $rows["latitude"],"address" => $rows["address"],"createdDate" => $this->dateDiffApply($rows["createdDate"]),"like" => $result1["likeCount"],"comment" => $result["commentCount"]));
					//array_push($commentCount, array("comment" => mysqli_num_rows($commentSql)));
					
					}
					$data1=array('allMyPost'=>$data);
					array_push($array, (object)$data1);
					$this->response($this->json($array), 200);
					
					}
					else{
					$invalidMessage = array('status' => "bug", "message" => "no post available");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}
	}	

		else if($postClickType=="3"){
$sql = mysqli_query($this->db,"SELECT a.postId,a.username,b.picture, a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate,a.status FROM tbl_event_posts a, tbl_profile_pics b WHERE a.username=b.username and a.postId='$postId' and a.username = '$username' and a.status!='1' order by createdDate desc");
$data=array();
					if(mysqli_num_rows($sql) > 0){
						foreach ( $sql as $rows ) {
					$postId=$rows["postId"];
					//$postUser=$rows["username"];
					$commentSql = mysqli_query($this->db,"SELECT count(*) as commentCount FROM tbl_comments a,tbl_event_posts b WHERE  a.postId=b.postId and b.postId='$postId'");
					$likeSql = mysqli_query($this->db,"SELECT count(*) as likeCount FROM tbl_like a,tbl_event_posts b WHERE  a.postId=b.postId and b.postId='$postId'");
					$result = mysqli_fetch_assoc($commentSql);
					$result1 = mysqli_fetch_assoc($likeSql);
					//array_push($rows, array("comment" => $result["commentCount"],"like" => $result1["likeCount"]));
					array_push($data, array("postId" => $rows["postId"],"username" => $rows["username"],"picture" => $rows["picture"],"postDescribe" => $rows["postDescribe"],"eventType" => $rows["eventType"],"postText" => $rows["postText"],"postImage" => $rows["postImage"],"longitude" => $rows["longitude"],"latitude" => $rows["latitude"],"address" => $rows["address"],"createdDate" => $this->dateDiffApply($rows["createdDate"]),"like" => $result1["likeCount"],"comment" => $result["commentCount"]));
					//array_push($commentCount, array("comment" => mysqli_num_rows($commentSql)));
					
					}
					$data1=array('allMyPost'=>$data);
					array_push($array, (object)$data1);
					$this->response($this->json($array), 200);
					
					}
					else{
					$invalidMessage = array('status' => "bug", "message" => "no post available");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}
	}	

		else if($postClickType=="7"){
					$sql = mysqli_query($this->db,"SELECT a.postId,a.username,b.username as user,b.picture, a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate,
	ACOS(
       COS(RADIANS($username))
     * COS(RADIANS(latitude))
     * COS(RADIANS($postId) - RADIANS(longitude))
     + SIN(RADIANS($username)) 
     * SIN(RADIANS(latitude))
) AS haversineDistance
FROM tbl_event_posts a, tbl_profile_pics b WHERE a.username=b.username and a.postImage!='0' and a.postImage!=''  and a.status!='1' order by haversineDistance asc ");

$data=array();
					if(mysqli_num_rows($sql) > 0){
						foreach ( $sql as $rows ) {
					$postId=$rows["postId"];
					//$postUser=$rows["username"];
					$commentSql = mysqli_query($this->db,"SELECT count(*) as commentCount FROM tbl_comments a,tbl_event_posts b WHERE  a.postId=b.postId and b.postId='$postId'");
					$likeSql = mysqli_query($this->db,"SELECT count(*) as likeCount FROM tbl_like a,tbl_event_posts b WHERE  a.postId=b.postId and b.postId='$postId'");
					$result = mysqli_fetch_assoc($commentSql);
					$result1 = mysqli_fetch_assoc($likeSql);
					//array_push($rows, array("comment" => $result["commentCount"],"like" => $result1["likeCount"]));
					array_push($data, array("postId" => $rows["postId"],"username" => $rows["username"],"picture" => $rows["picture"],"postDescribe" => $rows["postDescribe"],"eventType" => $rows["eventType"],"postText" => $rows["postText"],"postImage" => $rows["postImage"],"longitude" => $rows["longitude"],"latitude" => $rows["latitude"],"address" => $rows["address"],"createdDate" => $this->dateDiffApply($rows["createdDate"]),"like" => $result1["likeCount"],"comment" => $result["commentCount"]));
					//array_push($commentCount, array("comment" => mysqli_num_rows($commentSql)));
					
					}
					$data1=array('allMyPost'=>$data);
					array_push($array, (object)$data1);
					$this->response($this->json($array), 200);
					
					}
					else{
					$invalidMessage = array('status' => "bug", "message" => "no post available");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}
	}			





		
	}
}
?>