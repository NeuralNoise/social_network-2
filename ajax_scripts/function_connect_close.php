<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == "connect_close_clicked" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];

$with_whom = $_POST['with_whom'];

require_once("../app/init.php");

$sql_close_connect = $db->prepare("DELETE FROM connections WHERE (person_1=:me AND person_2=:you) OR (person_1=:you AND person_2=:me) LIMIT 1");
$sql_close_connect->execute(array("me" => $userid, "you" => $with_whom));

if($sql_close_connect){

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