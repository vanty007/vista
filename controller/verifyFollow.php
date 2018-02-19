<?php
header('Access-Control-Allow-Origin: *');
require_once("Rest.inc.php");

class VerifyFollowClass extends REST{
//$mycollection="";
public function __construct(){
            parent::__construct();              // Init parent contructor
            $this->dbConnectMysql("sysdb");             // Initiate Database connection
        }
function verifyFollow($followEvent,$username,$neighbour){
    //$following=array('status' => "success", "following" => "0");
    $following=array();
if($followEvent=="0"){
$sql = mysqli_query($this->db,"SELECT * FROM tbl_neighbours a WHERE username = '$neighbour'");

if(mysqli_num_rows($sql) > 0){
    $data=array();
    $index = 0;
                    foreach ( $sql as $rows ) {
                    $foreignNeighbour=$rows["neighbour"];
                    $sql1 = mysqli_query($this->db,"SELECT *  FROM tbl_neighbours a, tbl_profile_pics b, tbl_register_user c WHERE a.neighbour=b.username and a.neighbour=c.no and a.username = '$neighbour' and a.neighbour='$foreignNeighbour' LIMIT 1");
        $result = mysqli_fetch_assoc($sql1);
                    if(mysqli_num_rows($sql1) > 0){
        $following[$index]["following"]=$result["lastName"].' '.$result["firstName"];
        $following[$index]["user"]=$foreignNeighbour;
        $following[$index]["picture"]=$result["picture"];
        $following[$index]["youFollow"]="1";
        
    
    }
    else if( mysqli_num_rows($sql1) == 0 && $username==$foreignNeighbour){
        $following[$index]["following"]=$result["lastName"].' '.$result["firstName"];
        $following[$index]["user"]=$foreignNeighbour;
        $following[$index]["picture"]=$result["picture"];
        $following[$index]["youFollow"]="2";
    }
     else if( mysqli_num_rows($sql1) == 0 && $username!=$foreignNeighbour){
        $following[$index]["following"]=$result["lastName"].' '.$result["firstName"];
        $following[$index]["user"]=$foreignNeighbour;
        $following[$index]["picture"]=$result["picture"];
        $following[$index]["youFollow"]="0";
    }
    $index++;
}
$this->response($this->json($following), 200);
                    }
    else{
        $following[0]["following"]="3";
        $this->response($this->json($following), 200);
    }

                    }
                    

    else if($followEvent=="1"){
$sql = mysqli_query($this->db,"SELECT * FROM tbl_neighbours a WHERE neighbour = '$neighbour'");

if(mysqli_num_rows($sql) > 0){
    $data=array();
    $index=0;
                    foreach ( $sql as $rows ) {
                    $foreignUsername=$rows["username"];
                    $sql1 = mysqli_query($this->db,"SELECT *  FROM tbl_neighbours a, tbl_profile_pics b, tbl_register_user c WHERE a.neighbour=b.username and a.neighbour=c.no and a.username = '$username' and a.neighbour='$foreignUsername' LIMIT 1");
        $result = mysqli_fetch_assoc($sql1);
                    if(mysqli_num_rows($sql1) > 0){
        $following[$index]["following"]=$result["lastName"].' '.$result["firstName"];
        $following[$index]["user"]=$foreignUsername;
        $following[$index]["picture"]=$result["picture"];
        $following[$index]["youFollow"]="1";
    }
    else if( mysqli_num_rows($sql1) == 0 && $username==$foreignUsername){
        $following[$index]["following"]=$result["lastName"].' '.$result["firstName"];
        $following[$index]["user"]=$foreignUsername;
        $following[$index]["picture"]=$result["picture"];
        $following[$index]["youFollow"]="2";
    }
     else if( mysqli_num_rows($sql1) == 0 && $username!=$foreignUsername){
        $following[$index]["following"]=$result["lastName"].' '.$result["firstName"];
        $following[$index]["user"]=$foreignUsername;
        $following[$index]["picture"]=$result["picture"];
        $following[$index]["youFollow"]="0";
    }
    $index++;
}
$this->response($this->json($following), 200);
                    }
    else{
        $following[0]["following"]="3";
        $this->response($this->json($following), 200);
    }

                    }
                    
}

}
?>

