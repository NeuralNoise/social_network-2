<?php

if(isset($_POST['action']) && $_POST['action'] == 'Register_pressed'){

	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$username = $_POST['username'];


	require_once("../app/init.php");

	//checking if email exists

	$sql_check_email = null;
	$sql_register = null;
	$flag = 0;

	$sql_check_email = $db->prepare("SELECT id FROM user_login WHERE email=:email LIMIT 1");
	$sql_check_email->bindParam(':email', $email);
	$sql_check_email->execute();
	
	$num_rows = $sql_check_email->rowCount();

	if($num_rows > 0){
		$flag = 1;
		$response = array();
		$response['email_exists'] = "This email address already exists!";
		$response['res_code'] = $flag;
		echo json_encode($response);
	}else{

		// check username

		$sql_check_username = $db->prepare("SELECT id FROM user_login WHERE username=:username LIMIT 1");
		$sql_check_username->execute(array("username"=>$username));
		$numrows = $sql_check_username->rowCount();

		if($numrows > 0){

			$flag = 2;

			$response['username_exists'] = "This username already exists!";
			$response['res_code'] = $flag;
			echo json_encode($response);

		}else{

		


		// password encryption creation

		$salt_1 = '2emgAd86';
		$salt_2 = 'nnbghdgf9&';

		$pass = md5(sha1($salt_1.$password.$salt_2)); 

		

		$sql_register = $db->prepare("INSERT INTO user_login VALUES(null,:em,:pass,:username)");
		$sql_register->execute(array('em'=>$email, 'pass'=>$pass, 'username'=>$username));

		$user_id = $db->lastInsertId();

		
		mkdir("../userdata/$user_id", 0777);
		mkdir("../userdata/$user_id/dp", 0777);

		chmod("../userdata/$user_id", 0777);
		chmod("../userdata/$user_id/dp", 0777);



		$sql_user_info = $db->prepare("INSERT INTO user_profile VALUES(null, :userid, :name, :b_city, :c_city, :school, :college, :website, :about, :avatar)");
		$sql_user_info->execute(array(
			"userid"=>$user_id, 
			"name"=>$name, 
			"b_city"=>"You are from which city? (Calcutta, India)?",
			"c_city"=>"Living at (Silicon Valley, California)?",
			"school"=>"School?",
			"college"=>"College, University?",
			"website"=>"My website/blog (full url)?",
			"about"=>"Say something about yourself?",
			"avatar"=> ''
			));

		$flag = 3;

		

		session_start();
		$_SESSION['id'] = $user_id;
		
		
		$response['res_code'] = $flag;
		
		echo json_encode($response);

		}

		
	}





}

?>