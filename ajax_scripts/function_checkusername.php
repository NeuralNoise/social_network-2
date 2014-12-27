<?php

if(isset($_POST['action']) && $_POST['action'] == "check_username"){


$username = $_POST['username'];

require_once("../app/init.php");

$sql_check_username = $db->prepare("SELECT id FROM user_login WHERE username=:username LIMIT 1");
$sql_check_username->execute(array("username"=>$username));
$numrows = $sql_check_username->rowCount();

if($numrows < 1){
	echo "&#10004";
}else{
	echo "&#10006";
	
}

}


?>