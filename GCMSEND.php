<?php
	class GCMSEND{
		
		public function __construct(){
		}
	public function GCMSEND_Notification($message,$registrationIds){
		if(is_array($registrationIds)){
		$regID=$registrationIds;
		}
		else{
		$regID= array( $registrationIds );
	}
		//echo $message.'////'.$registrationIds;
// API access key from Google API's Console
//define( 'API_ACCESS_KEY', 'AIzaSyAMMZdXa4ZtbzwPiv0sjpj7BeNtJ98DMKA' );

//$registrationIds = array( $_GET['id'] );

// prep the bundle
$msg = array
(
	'message' 	=> $message,
	'title'		=> 'Your just received a notification message',
	//'subtitle'	=> '',
	'tickerText'	=> 'T',
	'vibrate'	=> 1,
	'sound'		=> 1,
	'largeIcon'	=> 'large_icon',
	'smallIcon'	=> 'small_icon'
);

$fields = array
(
	'registration_ids' 	=> $regID,
	'data'			=> $msg
);
 
$headers = array
(
	'Authorization: key=' . 'AIzaSyAMMZdXa4ZtbzwPiv0sjpj7BeNtJ98DMKA',
	'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
//echo $result;
curl_close( $ch );
}
}

?>