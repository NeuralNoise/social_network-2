<?php
session_start();

if(isset($_GET['action']) && $_GET['action'] == "get_conn_req_count" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];



require_once("../app/init.php");



$sql_get_conn_req_count = $db->prepare("SELECT * FROM connection_requests WHERE sent_to=:me ");
$sql_get_conn_req_count->execute(array("me" => $userid));

$numrow_conn_req = $sql_get_conn_req_count->rowCount();




if($numrow_conn_req > 0){
  $count = $numrow_conn_req;
}else{
  $count = NULL;
}

	
if($sql_get_conn_req_count){

	$flag = 1;
	$array = array();
	$array['code'] = $flag;
	$array['conn_req_count'] = $count;
	echo json_encode($array);

}else{

	$flag = 0;
	
	$array['code'] = $flag;
	echo json_encode($array);

}



}

?>