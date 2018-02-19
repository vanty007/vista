<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class vistaFeedsClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");				// Initiate Database connection
		}
function searchFeeds($searchType,$longitude,$latitude,$varCount){
$limit=10;
$array=array();

	if($searchType=="0"){
					$sql = mysqli_query($this->db,"SELECT a.postId,a.username,b.username as user,a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate,
	ACOS(
       COS(RADIANS($latitude))
     * COS(RADIANS(latitude))
     * COS(RADIANS($longitude) - RADIANS(longitude))
     + SIN(RADIANS($latitude)) 
     * SIN(RADIANS(latitude))
) AS haversineDistance
FROM tbl_event_posts a, tbl_register_user b WHERE a.username=b.no and a.postImage!='0' and a.postImage!=''  and a.status!='1' order by haversineDistance asc limit $varCount, $limit");
					if(mysqli_num_rows($sql) > 0){
					$row=mysqli_fetch_array($sql,MYSQLI_ASSOC);
					$data=array();

					foreach ( $sql as $rows ) {
					array_push($data, (object)$rows);
					
					}
					$data1=array('searchFeeds'=>$data);
					array_push($array, (object)$data1);
					$this->response($this->json($array), 200);
					
					}
					else{
					$sql1 = mysqli_query($this->db,"SELECT a.postId,a.username,b.username as user,a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate,
						ACOS(
       COS(RADIANS($latitude))
     * COS(RADIANS(latitude))
     * COS(RADIANS($longitude) - RADIANS(longitude))
     + SIN(RADIANS($latitude)) 
     * SIN(RADIANS(latitude))
) AS haversineDistance FROM tbl_event_posts a, tbl_register_user b WHERE a.username=b.no and a.postImage!='0' and a.postImage!=''  and a.status!='1'  order by haversineDistance asc limit $varCount, $limit");
					if(mysqli_num_rows($sql1) > 0){
					$row1=mysqli_fetch_array($sql1,MYSQLI_ASSOC);
					$dataa=array();

					foreach ( $sql1 as $rows ) {
					array_push($dataa, (object)$rows);
					
					}
					$data11=array('searchFeeds'=>$dataa);
					array_push($array, (object)$data11);
					$this->response($this->json($array), 200);
					
					}
					else{
					$invalidMessage = array('status' => "empty", "message" => "empty result");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}

			}
}

	if($searchType=="1"){
					$sql1 = mysqli_query($this->db,"SELECT a.postId,a.username,b.username as user,a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate FROM tbl_event_posts a, tbl_register_user b WHERE a.username=b.no and a.address like '%$longitude%' or b.username='$longitude' and a.postImage!='0' and a.postImage!=''  and a.status!='1'  order by a.longitude asc limit $varCount, $limit");
					if(mysqli_num_rows($sql1) > 0){
					$row1=mysqli_fetch_array($sql1,MYSQLI_ASSOC);
					$dataa=array();

					foreach ( $sql1 as $rows ) {
					array_push($dataa, (object)$rows);
					
					}
					$data11=array('searchFeeds'=>$dataa);
					array_push($array, (object)$data11);
					$this->response($this->json($array), 200);
					
					}
					else{
					$invalidMessage = array('status' => "empty", "message" => "empty result");
					$this->response($this->json($invalidMessage), 406);	// If no records "No Content" status
				}
				}

	if($searchType=="2"){
					$sql1 = mysqli_query($this->db,"SELECT a.postId,a.username,b.username as user,a.postDescribe,a.eventType,a.postText,a.postImage,a.longitude,a.latitude,a.address,a.createdDate FROM tbl_event_posts a, tbl_register_user b WHERE a.username=b.no and b.username ='$longitude' and a.postImage!='0' and a.postImage!=''  and a.status!='1'  order by a.longitude asc limit $varCount, $limit");
					if(mysqli_num_rows($sql1) > 0){
					$row1=mysqli_fetch_array($sql1,MYSQLI_ASSOC);
					$dataa=array();

					foreach ( $sql1 as $rows ) {
					array_push($dataa, (object)$rows);
					
					}
					$data11=array('searchFeeds'=>$dataa);
					array_push($array, (object)$data11);
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