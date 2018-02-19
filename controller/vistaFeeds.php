
<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class vistaFeeds extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");
			//echo $this->dbConnectMongodbVista().$dbhost;				// Initiate Database connection
		}
function loadFeeds($longitude,$latitude,$varCount){

$limit=5;
//$varCount=$varCount+$limit;
$array=array();

					$sql = mysqli_query($this->db,"SELECT 
	a.postId,a.username,b.picture, a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate,
	ACOS(
       COS(RADIANS($latitude))
     * COS(RADIANS(latitude))
     * COS(RADIANS($longitude) - RADIANS(longitude))
     + SIN(RADIANS($latitude)) 
     * SIN(RADIANS(latitude))
) AS haversineDistance FROM tbl_event_posts a, tbl_profile_pics b WHERE a.username=b.username and  a.status!='1' order by haversineDistance asc limit $varCount, $limit");
					if(mysqli_num_rows($sql) > 0){
					$row=mysqli_fetch_array($sql,MYSQLI_ASSOC);
					$data=array();
					$commentCount=array();

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

					$data1=array('vistaFeeds'=>$data);
					array_push($array, (object)$data1);
					//echo json_encode($array);
					$this->response($this->json($array), 200);
					//var_dump($array);
					
					}
					else{
					$sql1 = mysqli_query($this->db,"SELECT 
	a.postId,a.username,b.picture, a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate,
	ACOS(
       COS(RADIANS($latitude))
     * COS(RADIANS(latitude))
     * COS(RADIANS($longitude) - RADIANS(longitude))
     + SIN(RADIANS($latitude)) 
     * SIN(RADIANS(latitude))
) AS haversineDistance
 FROM tbl_event_posts a a.username=b.username and  a.status!='1'  order by haversineDistance asc limit $varCount, $limit");
					if($sql1 && mysqli_num_rows($sql1) > 0){
					$row=mysqli_fetch_array($sql1,MYSQLI_ASSOC);
					$data=array();
					$commentCount=array();

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
					$data1=array('vistaFeeds'=>$data);
					array_push($array, (object)$data1);
					$this->response($this->json($array), 200);
					
					}
					else{
					$invalidMessage = array('status' => "empty", "message" => "empty result");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}

			}



	}
}
?>