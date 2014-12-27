
<?php
session_start();

if(isset($_SESSION['id'])){

include ("../app/init.php") ;	

$user_id = $_SESSION["id"];
$flag = NULL;

 

//if(isset($_POST['action']) && $_POST['action'] == "image_upload"){

	foreach($_FILES['image']['tmp_name'] as $key => $tmp_name ){
	
	if((($_FILES['image']['type'][$key] == 'image/jpeg') ||
	($_FILES['image']['type'][$key] == 'image/jpg') ||	
	($_FILES['image']['type'][$key] == 'image/png') ||
	($_FILES['image']['type'][$key] == 'image/png')) 
	&& ($_FILES['image']['size'][$key] < 1073741824))		//10 mb in bytes
	{
		
		
			//$time = time();
		
			//$uploaded_by = $id;
			$picture = $user_id.'.jpg';

			$sql_insert_db = $db->prepare("UPDATE user_profile SET avatar=:avatar WHERE user_id=:user LIMIT 1");

			$sql_insert_db->execute(array("avatar" => $picture, "user" => $user_id));

			$store_img = '../userdata/'.$user_id.'/dp/'.$picture;
			
			if($sql_insert_db){
				move_uploaded_file($_FILES['image']['tmp_name'][$key], $store_img);

			

			}else{

			$flag = 2; 

			$array = array();
			$array['code'] = $flag;
			$array['problem'] = "problem with db";	
			echo json_encode($array);
			exit;

			}

			$flag = 1; 

			$time = time();
			
			$array = array();
			$array['code'] = $flag;
			$array['supported'] = "Uploaded";	
			$array['image'] = '<img src="userdata/'.$user_id.'/dp/'.$picture.'?t='.$time.'" style="width:100%; height:100%; " />';
			echo json_encode($array);

			
			
			//echo '<img src="userdata/'.$user_id.'/dp/'.$picture.'?time='.$time.'" style="width:100%; height:100%; " />';
			
		
			
			 
			
			
			
			
	
}else{

$flag = 0;


$array['code'] = $flag;
$array['unsupported'] = "This file is not supported";	
echo json_encode($array);
}
		
}

//}

}

?>