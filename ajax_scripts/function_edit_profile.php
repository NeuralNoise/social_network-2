<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == 'edit_profile_pressed' && isset($_SESSION['id'])){

	$flag = NULL;

	$name = $_POST['name'];
	$b_city = $_POST['b_city'];
	$c_city = $_POST['c_city'];
	$school = $_POST['school'];
	$college = $_POST['college'];
	$website = $_POST['website'];
	$about = $_POST['about'];
	
	$user_id = $_SESSION['id'];

	if(empty($website)){
		$website = "No website";
	}

	if(empty($about)){
		$about = "Nothing about me is required to tell.";
	}


	require_once("../app/init.php");

	try{
	
	$sql_update_info = $db->prepare(

	"UPDATE user_profile SET name=:name, birth_city=:bcity, current_city=:ccity , school=:school, college=:college, website=:website, about=:about WHERE user_id=:user");

	$sql_update_info->execute(array(
		"name"=>$name,
		"bcity"=>$b_city,
		"ccity"=>$c_city,
		"school"=>$school,
		"college"=>$college,
		"website"=>$website,
		"about"=>$about,
		"user"=>$user_id
		));

		$flag = 1;

		$array = array();
		
		$array['code'] = $flag;

		echo json_encode($array);
	
	

		}catch(Exception $e){

			$flag = 0;

			$array['code'] = $flag;

			echo json_encode($array);

		}

}

// PULL INFO TO MAIN PAGE

if(isset($_GET['action']) && $_GET['action'] == 'pull_info_edit_profile' && isset($_SESSION['id'])){

	$user_id = $_SESSION['id'];
	require_once("../app/init.php");
	
	$pull_info_edit_profile = $db->prepare("SELECT * FROM user_profile WHERE user_id=:user LIMIT 1");

	$pull_info_edit_profile->execute(array("user"=>$user_id));

	$row = $pull_info_edit_profile->fetch(PDO::FETCH_ASSOC);

	$name = $row['name'];
	$b_city = $row['birth_city'];
	$c_city = $row['current_city'];
	$school = $row['school'];
	$college = $row['college'];
	$website = $row['website'];
	$about = $row['about'];

	

	$array = array();
	$array['name'] = $name;
	$array['b_city'] = $b_city;
	$array['c_city'] = $c_city;
	$array['school'] = $school;
	$array['college'] = $college;
	$array['website'] = $website;
	$array['about'] = $about;

	echo json_encode($array);






}





?>