<?php
session_start();

if(isset($_GET['action']) && $_GET['action'] == "connection_list_show" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];

$user = $_GET['user'];

require_once("../app/init.php");



$connection_list = NULL;



// now start the connection query

$sql_get_user_connection_list = $db->prepare("SELECT * FROM connections WHERE person_1=:pageid OR person_2=:pageid");
$sql_get_user_connection_list->execute(array("pageid" => $user));

//$numrow_list = $sql_get_user_connection_list->rowCount();



	while($row = $sql_get_user_connection_list->fetch(PDO::FETCH_ASSOC)){


		$person_1 = $row['person_1'];
		$person_2 = $row['person_2'];

		if($person_1 == $user){
			$connected_with = $person_2;
		}else if($person_2 == $user){
			$connected_with = $person_1;
		}

// get username of connected people

	$sql_get_username = $db->prepare("SELECT username FROM user_login WHERE id=:userid");
	$sql_get_username->execute(array("userid" => $connected_with));

	while($row_username = $sql_get_username->fetch(PDO::FETCH_ASSOC)){
		$username_connection = $row_username['username'];


	}
	
	


	
// get name and image of connected people

	$sql_get_info = $db->prepare("SELECT name, avatar FROM user_profile WHERE user_id=:connected_person");
	$sql_get_info->execute(array("connected_person" => $connected_with));

	while ($row_connection = $sql_get_info->fetch(PDO::FETCH_ASSOC)) {
		$name_connection = $row_connection['name'];
		$avatar_connection = $row_connection['avatar'];




		
		$connection_list .= '<div class="col-md-3 each-friend-display"><a href="profile.php?u='.$username_connection.'"  title="'.$name_connection.'" target="_blank"><img src="userdata/'.$connected_with.'/dp/'.$avatar_connection.'" style="width:70px; height:70px;" /></a></div>';
	

	}

		

	}


	
if($sql_get_user_connection_list && $sql_get_username && $sql_get_info){

	$flag = 1;
	$array = array();
	$array['code'] = $flag;
	$array['connections'] = $connection_list;
	echo json_encode($array);

}else{

	$flag = 0;
	
	$array['code'] = $flag;
	echo json_encode($array);

}



}

?>