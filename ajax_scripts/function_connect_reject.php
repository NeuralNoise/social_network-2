<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == "connect_reject_clicked" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];

$with_whom = $_POST['with_whom'];

require_once("../app/init.php");



$sql_delete_request = $db->prepare("DELETE FROM connection_requests WHERE sent_from=:sent_from AND sent_to=:sent_to LIMIT 1");
$sql_delete_request->execute(array("sent_from" => $with_whom, "sent_to" => $userid));



if($sql_delete_request){

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