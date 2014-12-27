<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == "connect_cancel_clicked" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];

$to = $_POST['to'];

require_once("../app/init.php");

$sql_cancel_request = $db->prepare("DELETE FROM connection_requests WHERE sent_from=:from_me AND sent_to=:to LIMIT 1");
$sql_cancel_request->execute(array("from_me" => $userid, "to" => $to));

if($sql_cancel_request){

	// update realtime connection counter

	$sql_update = $db->prepare("UPDATE realtime_data_counter SET connection=connection+1 LIMIT 1");
	$sql_update->execute();


	$flag = 1;
	$array = array();
	$array['code'] = $flag;
	echo json_encode($array);

}else{
	$flag = 0;
	
	$array['code'] = $flag;
	echo json_encode($array);

}

}

?>