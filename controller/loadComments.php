<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class LoadCommentsClass extends REST{
//$mycollection="";
public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnectMysql("sysdb");
		}
function loadComments($postId){
$array=array();
$commentsql = mysqli_query($this->db,"SELECT a.postId,a.username,c.lastName,c.firstName, b.picture, a.comment, a.createdDate FROM tbl_comments a, tbl_profile_pics b, tbl_register_user c WHERE a.username=b.username and a.username=c.no and a.postId='$postId' ORDER BY a.createdDate asc");
					if(mysqli_num_rows($commentsql) > 0){
					$dataa=array();

					foreach ( $commentsql as $rows ) {
					array_push($dataa, array("postId" => $rows["postId"],"username" => $rows["username"],"user" => $rows["lastName"].' '.$rows["firstName"],"picture" => $rows["picture"],"comment" => $rows["comment"],"createdDate" => $this->dateDiffApply($rows["createdDate"])));
					
					}
					$data11=array('loadComments'=>$dataa);
					array_push($array, (object)$data11);
					$this->response($this->json($array), 200);
					
					}
					else{
				}
			
		
	}
}
?>