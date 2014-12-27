<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == "dislike_post" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];


$post_id = $_POST['postid'];


require_once("../app/init.php");

$sql_insert_dislike = $db->prepare("INSERT INTO post_dislikes VALUES(null, :user, :postid)");
$sql_insert_dislike->execute(array("user" => $userid, "postid" => $post_id));

$sql_dislike = $db->prepare("UPDATE posts SET unlikes=unlikes+1 WHERE id=:postid");
$sql_dislike->execute(array("postid" => $post_id));


// get how many likes

$sql_dislike_count = $db->prepare("SELECT unlikes FROM posts WHERE id=:postid");
$sql_dislike_count->execute(array("postid" => $post_id));

$row = $sql_dislike_count->fetch(PDO::FETCH_ASSOC);

$dislikecount = $row['unlikes'];






if($sql_insert_dislike && $sql_dislike){




	$flag = 1;
	$array = array();
	$array['code'] = $flag;
	$array['success'] = $dislikecount;
	
	echo json_encode($array);

}else{
	$flag = 0;
	
	$array['code'] = $flag;
	echo json_encode($array);
}

}

?>