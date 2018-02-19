<?php
 header('Access-Control-Allow-Origin: *');  
	
	
	require_once("Rest.inc.php");
	require_once("controller/login.php");
	require_once("controller/vistaFeeds.php");
	require_once("controller/registerUser.php");
	require_once("controller/Post.php");
	require_once("controller/postComment.php");
	require_once("controller/postLike.php");
	require_once("controller/follow.php");
	require_once("controller/postLength.php");
	require_once("controller/followingLength.php");
	require_once("controller/followerLength.php");
	require_once("controller/personalPosts.php");
	require_once("controller/checkFollow.php");
	require_once("controller/PersonPost.php");
	require_once("controller/verifyFollow.php");
	require_once("controller/likecommentLength.php");
	require_once("controller/confirmLockAcct.php");
	require_once("controller/switch.php");
	require_once("controller/sharePost.php");
	require_once("controller/changePassword.php");
	require_once("controller/editProfile.php");
	require_once("controller/viewProfile.php");
	require_once("controller/searchFeeds.php");
	require_once("controller/verifyNotification.php");
	require_once("controller/loadNotifcation.php");
	require_once("controller/updateNotification.php");
	require_once("controller/loadComments.php");
	require_once("controller/linkConfirm.php");
	require_once("controller/createLink.php");
	require_once("controller/sendPassword.php");
	require_once("controller/deletePost.php");
	require_once("controller/togglePostStatus.php");
	require_once("controller/uploadPhoto.php");
	require_once("controller/editProfileUpload.php");
	require_once("controller/testingApi.php");


	class API extends REST {
	
		public $data = "";
		
		
	
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}

				private function isApi_key($key){
		$this->dbConnectMysql("sagdb");
			$sql = mysqli_query($this->db,"SELECT api_key FROM tbl_user_auth_info WHERE api_key = '$key' LIMIT 1");
					if(mysqli_num_rows($sql) > 0){
					return true;
					}
					else{
					return false;

				//}
			}
		mysqli_close($this->db);
		}

		private function isUser($username){
		$this->dbConnectMysql("sagdb");
			$sql = mysqli_query($this->db,"SELECT username FROM tbl_user_auth_info WHERE username = '$username' LIMIT 1");
					if(mysqli_num_rows($sql) > 0){
					return true;
					}
					else{
					return false;

				//}
			}
		mysqli_close($this->db);
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		private function testingApi(){
			
			if($this->get_request_method() != "GET"){
			$invalidMessage = array('status' => "bad request", "message" => "This is a bad request method");
			$this->response($this->json($invalidMessage), 406);
			}
			else{
			$testingApi = new testingApiclass;
			$testingApi->testingApimethod();
}
}

		private function editProfileUpload(){

if(!isset($_FILES["file"]["type"])) {
     $error = array("error" => "photo file parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['user'])) {
     $error = array("error" => "user parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($this->_request['api_key'])){
			$user = $this->_request['user'];
			$file = $_FILES['file']['tmp_name'];
			$fileType = $_FILES['file']['type'];
			$fileName = $_FILES['file']['name'];
			$uploadPhoto = new EditProfilePhotoClass;
			$uploadPhoto->editProfilePhoto($file,$fileType,$fileName,$user);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}

			}

		}

		private function uploadPhoto(){

if(!isset($this->_request['text'])) {
     $error = array("error" => "text parameter must be provided");
    $this->response($this->json($error), 406);
}

else if(!isset($this->_request['longitude'])) {
     $error = array("error" => "longitude parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['latitude'])) {
     $error = array("error" => "latitude parameter must be provided");
    $this->response($this->json($error), 406);
}

else if(!isset($_FILES["file"]["type"])) {
     $error = array("error" => "photo file parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['user'])) {
     $error = array("error" => "user parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($this->_request['api_key'])){
			$text = $this->_request['text'];
			$longitude = $this->_request['longitude'];
			$latitude = $this->_request['latitude'];

			$user = $this->_request['user'];
			$username = $this->_request['username'];
			$file = $_FILES['file']['tmp_name'];
			$fileType = $_FILES['file']['type'];
			$fileName = $_FILES['file']['name'];
			$uploadPhoto = new UploadPhotoClass;
			$uploadPhoto->uploadPhoto($file,$fileType,$fileName,$user,$text, $longitude, $latitude,$username);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}

			}

		}

		private function togglePostStatus(){
			if(!isset($this->_request['postId'])) {
     $error = array("error" => "postId parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['value'])) {
     $error = array("error" => "value parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$postId = $this->_request['postId'];
			$value = $this->_request['value'];
			$togglePostStatus = new TogglePostStatusClass;
			$togglePostStatus->togglePostStatus($postId,$value);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}

			}

		}

		private function deletePost(){
			if(!isset($this->_request['postId'])) {
     $error = array("error" => "postId parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$postId = $this->_request['postId'];
			$deletePost = new DeletePostClass;
			$deletePost->deletePost($postId);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}

			}

		}

		private function sendPassword(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
			$username = $this->_request['username'];
			$sendPassword = new sendPasswordClass;
			$sendPassword->sendPassword($username);

			}
			
		}

		private function createLink(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
			$username = $this->_request['username'];
			$createLink = new CreateLinkClass;
			$createLink->createLink($username);

			}
			
		}

		private function linkConfirm(){
			if(!isset($_GET['link'])) {
     $error = array("error" => "link parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
			$link =  $_GET['link'];
			$linkConfirm = new LinkConfirmClass;
			$linkConfirm->linkConfirm($link);
		

			}
			
		}

		private function loadComments(){
			if(!isset($this->_request['postId'])) {
     $error = array("error" => "postId parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$postId = $this->_request['postId'];
			$loadComments = new LoadCommentsClass;
			$loadComments->loadComments($postId);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}

			}
			
		}

		private function updateNotification(){
			if(!isset($this->_request['reactor'])) {
     $error = array("error" => "reactor parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['postId'])) {
     $error = array("error" => "postId parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$reactor = $this->_request['reactor'];
			$postId = $this->_request['postId'];
			$updateNotification = new UpdateNotificationClass;
			$updateNotification->updateNotification($reactor,$postId);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
			
		}

		private function loadNotifcation(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['count'])) {
     $error = array("error" => "count parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $this->_request['username'];
			$count = $this->_request['count'];
			$loadNotifcation = new LoadNotifcationClass;
			$loadNotifcation->loadNotifcation($username,$count);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}


		private function verifyNotification(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['token'])) {
     $error = array("error" => "token parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $this->_request['username'];
			$token = $this->_request['token'];
			//$latitude = $_REQUEST['latitude'];
			$verifyNotication = new verifyNotificationClass;
			$verifyNotication->verifyNotification($username,$token);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}



		private function searchFeeds(){
			if(!isset($this->_request['longitude'])) {
     $error = array("error" => "longitude parameter must be provided");
    $this->response($this->json($error), 406);
}
			if(!isset($this->_request['latitude'])) {
     $error = array("error" => "latitude parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['varCount'])) {
     $error = array("error" => "varCount parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['searchType'])) {
     $error = array("error" => "searchType parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$longitude = $this->_request['longitude'];
			$latitude = $this->_request['latitude'];
			$varCount = $this->_request['varCount'];
			$searchType = $this->_request['searchType'];
			//$latitude = $_REQUEST['latitude'];
			$searchFeeds = new vistaFeedsClass;
			$searchFeeds->searchFeeds($searchType,$longitude,$latitude,$varCount);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}

		private function viewProfile(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$viewProfile = new ViewProfileClass;
			$viewProfile->viewProfileMethod($username);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}

		private function editProfile(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['editUsername'])) {
     $error = array("error" => "editUsername parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['firstName'])) {
     $error = array("error" => "firstName parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['lastName'])) {
     $error = array("error" => "lastName parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['dateOfBirth'])) {
     $error = array("error" => "dateOfBirth parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['sex'])) {
     $error = array("error" => "sex parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['nationality'])) {
     $error = array("error" => "nationality parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['stateOfBirth'])) {
     $error = array("error" => "stateOfBirth parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['relationship'])) {
     $error = array("error" => "relationship parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['mobileNo'])) {
     $error = array("error" => "mobileNo parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$editUsername = $_REQUEST['editUsername'];
			$firstName = $_REQUEST['firstName'];
			$lastName = $_REQUEST['lastName'];
			$dateOfBirth = $_REQUEST['dateOfBirth'];
			$sex = $_REQUEST['sex'];
			$nationality = $_REQUEST['nationality'];
			$stateOfBirth = $_REQUEST['stateOfBirth'];
			$relationship = $_REQUEST['relationship'];
			$mobileNo = $_REQUEST['mobileNo'];
			$editProfile = new EditProfileClass;
			$editProfile->editProfileMethod($username,$editUsername, $firstName,$lastName,$dateOfBirth,$sex,$nationality,$stateOfBirth,$relationship,$mobileNo);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}

		private function changePassword(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['password'])) {
     $error = array("error" => "password parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['oldPassword'])) {
     $error = array("error" => "oldPassword parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];
			$oldPassword = $_REQUEST['oldPassword'];
			$changePassword = new ChangePasswordClass;
			$changePassword->ChangeUserPassword($username,$password,$oldPassword);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}

		private function login(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['password'])) {
     $error = array("error" => "password parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['option'])) {
     $error = array("error" => "option parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];
			$option = $_REQUEST['option'];
			$loginNow = new LoginClass;
			$loginNow->login($username, $password,$option);
		}
			
		}
			private function sharePost(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['postText'])) {
     $error = array("error" => "postText parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['postImage'])) {
     $error = array("error" => "postImage parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['eventType'])) {
     $error = array("error" => "eventType parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['neighbour'])) {
     $error = array("error" => "neighbour parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['longitude'])) {
     $error = array("error" => "longitude parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['latitude'])) {
     $error = array("error" => "latitude parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$user = $_REQUEST['user'];
			$postText = $_REQUEST['postText'];
			$postImage = $_REQUEST['postImage'];
			$eventType = $_REQUEST['eventType'];
			$neighbour = $_REQUEST['neighbour'];
			$longitude = $_REQUEST['longitude'];
			$latitude = $_REQUEST['latitude'];
			$shareThePost = new SharePostClass;
			$shareThePost->shareThePost($neighbour,$username,$postText,$postImage,$eventType,$longitude,$latitude,$user);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}

			private function switchLockNotification(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['switchEvent'])) {
     $error = array("error" => "switchEvent parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['switchStatus'])) {
     $error = array("error" => "switchStatus parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$switchEvent = $_REQUEST['switchEvent'];
			$switchStatus = $_REQUEST['switchStatus'];
			$switchLockNotification = new SwitchClass;
			$switchLockNotification->switchLockNotification($username,$switchEvent,$switchStatus);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}


		private function confirmLockAcct(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$confirmLockAcct = new ConfirmLockAcctClass;
			$confirmLockAcct->confirmLockAcct($username);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}

		private function likecommentLength(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$likecommentLength = new LikecommentLengthClass;
			$likecommentLength->likecommentLength($username);
}
					else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
				}
		}

			private function verifyFollow(){
			if(!isset($this->_request['neighbour'])) {
     $error = array("error" => "neighbour parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['followEvent'])) {
     $error = array("error" => "followEvent parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$neighbour = $_REQUEST['neighbour'];
			$followEvent = $_REQUEST['followEvent'];
			$verifyFollow = new VerifyFollowClass;
			//$verifyFollow->verifyFollow("1","vanty","vikky");
			$verifyFollow->verifyFollow($followEvent,$username,$neighbour);
		}
					else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}

		private function personPost(){
			if(!isset($this->_request['postId'])) {
     $error = array("error" => "postId parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['postClickType'])) {
     $error = array("error" => "postClickType parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$postClickType = $_REQUEST['postClickType'];
			$postId = $_REQUEST['postId'];
			$personPost = new PersonPostClass;
			$personPost->personPost($postClickType,$username,$postId);
		}
			else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}

		private function checkFollow(){
			if(!isset($this->_request['neighbour'])) {
     $error = array("error" => "neighbour parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$neighbour = $_REQUEST['neighbour'];
			$checkFollow = new CheckFollowClass;
			$checkFollow->checkFollow($username,$neighbour);
		}
											else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}
		private function postLength(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$postLength = new PostLengthClass;
			$postLength->postLength($username);
		}
				else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}

		private function followingLength(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$followingLength = new FollowingLengthClass;
			$followingLength->followingLength($username);
		}
							else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}
		private function followerLength(){
			if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$followerLength = new FollowerLengthClass;
			$followerLength->followerLength($username);
		}
							else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}

		private function personalPost(){
			if(!isset($this->_request['count'])) {
     $error = array("error" => "count parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$count = $_REQUEST['count'];
			$personalPost = new PersonalPostClass;
			$personalPost->personalPost($username,$count);
}
							else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}

				}
		}
		private function registerUser(){

			if(!isset($this->_request['firstName'])) {
     $error = array("error" => "firstName parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['lastName'])) {
     $error = array("error" => "lastName parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['password'])) {
     $error = array("error" => "password parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['email'])) {
     $error = array("error" => "email parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['option'])) {
     $error = array("error" => "option parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
			$firstName = $_REQUEST['firstName'];
			$lastName = $_REQUEST['lastName'];
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];
			$email = $_REQUEST['email'];
			$option = $_REQUEST['option'];
			$registerUser = new RegisterUserClass;
			$registerUser->register($firstName, $lastName, $username, $password, $email, $option);
		
			}

		}
		private function postFeed(){
if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
if(!isset($this->_request['user'])) {
     $error = array("error" => "user parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['text'])) {
     $error = array("error" => "text parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['longitude'])) {
     $error = array("error" => "longitude parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['latitude'])) {
     $error = array("error" => "latitude parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $_REQUEST['username'];
			$user = $_REQUEST['user'];
			$text = $_REQUEST['text'];
			$longitude = $_REQUEST['longitude'];
			$latitude = $_REQUEST['latitude'];
			$post = new PostClass;
			$post->postFeed($username,$text, $longitude, $latitude,$user);
}
					else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
				}
		}
		private function postComment(){
			if(!isset($this->_request['postId'])) {
     $error = array("error" => "postId parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['realUser'])) {
     $error = array("error" => "realUser parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['comment'])) {
     $error = array("error" => "comment parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$comment = $this->_request['comment'];
			$username = $this->_request['username'];
			$user = $this->_request['user'];
			$postId = $this->_request['postId'];
			$realUser = $this->_request['realUser'];
			$postComment = new PostCommentClass;
			$postComment->pushComment($postId, $username, $comment,$realUser,$user);
}
					else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
				}
		}

		private function postLike(){
			if(!isset($this->_request['postId'])) {
     $error = array("error" => "postId parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['realUser'])) {
     $error = array("error" => "realUser parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$username = $this->_request['username'];
			$postId = $this->_request['postId'];
			$user = $this->_request['user'];
			$realUser = $this->_request['realUser'];
			$postLike = new PostLikeClass;
			$postLike->pushLike($postId, $username,$realUser,$user);
}
					else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
				}
		}

		private function follow(){
			if(!isset($this->_request['followEvent'])) {
     $error = array("error" => "followEvent parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['username'])) {
     $error = array("error" => "username parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['neighbour'])) {
     $error = array("error" => "neighbour parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['longitude'])) {
     $error = array("error" => "longitude parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['latitude'])) {
     $error = array("error" => "latitude parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
	if($this->isApi_key($_GET['api_key'])){
			$api_key =  $_GET['api_key'];
			$followEvent = $this->_request['followEvent'];
			$username = $this->_request['username'];
			$neighbour = $this->_request['neighbour'];
			$user = $this->_request['user'];
			$longitude = $this->_request['longitude'];
			$latitude = $this->_request['latitude'];
			$follow = new FollowClass;
			$follow->triggerFollow($followEvent, $username,$neighbour,$longitude,$latitude,"0", "0",$user);
}
					else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
}
		}
		
		private function vistaFeeds(){
			if(!isset($this->_request['longitude'])) {
     $error = array("error" => "longitude parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['latitude'])) {
     $error = array("error" => "latitude parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($this->_request['varCount'])) {
     $error = array("error" => "varCount parameter must be provided");
    $this->response($this->json($error), 406);
}
else if(!isset($_GET['api_key'])) {
     $error = array("error" => "api_key parameter must be provided");
    $this->response($this->json($error), 406);
}
else{
			if($this->isApi_key($_GET['api_key'])){

			$longitude = $this->_request['longitude'];
			$latitude = $this->_request['latitude'];
			$varCount = $this->_request['varCount'];
			$api_key =  $_GET['api_key'];
			//$latitude = $_REQUEST['latitude'];
			$feeds = new vistaFeeds;
			$feeds->loadFeeds($longitude,$latitude,$varCount);

		}
					else{
			$invalidMessage = array('status' => "Unauthorized", "message" => "You are not authorized, please login again!!!");
			$this->response($this->json($invalidMessage), 401);		
				}
			}
		}
		private function users(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$sql = mysql_query("SELECT user_id, user_fullname, user_email FROM users WHERE user_status = 1", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
			}
			$this->response('',204);	// If no records "No Content" status
		}

		
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
?>