<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == "connect_clicked" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];

$to = $_POST['to'];

require_once("../app/init.php");

$sql_send_request = $db->prepare("INSERT INTO connection_requests VALUES(null, :from_me, :to)");
$sql_send_request->execute(array("from_me" => $userid, "to" => $to));

if($sql_send_request){


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