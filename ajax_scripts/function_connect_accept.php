<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == "connect_accept_clicked" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];

$with_whom = $_POST['with_whom'];

require_once("../app/init.php");

$sql_accept_request = $db->prepare("INSERT INTO connections VALUES(null, :person1, :person2)");
$sql_accept_request->execute(array("person1" => $userid, "person2" => $with_whom));

if($sql_accept_request){

$sql_delete_pending = $db->prepare("DELETE FROM connection_requests WHERE sent_from=:sent_from AND sent_to=:sent_to LIMIT 1");
$sql_delete_pending->execute(array("sent_from" => $with_whom, "sent_to" => $userid));

}else{

	$flag = 2;
	$array = array();
	$array['code'] = $flag;
	echo json_encode($array);
	exit;
}

if($sql_delete_pending){

	$flag = 1;
	
	$array['code'] = $flag;
	echo json_encode($array);

}else{
	$flag = 0;
	
	$array['code'] = $flag;
	echo json_encode($array);

}

}

?>