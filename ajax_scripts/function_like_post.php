<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == "like_post" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];


$post_id = $_POST['postid'];


require_once("../app/init.php");

$sql_insert_like = $db->prepare("INSERT INTO post_likes VALUES(null, :user, :postid)");
$sql_insert_like->execute(array("user" => $userid, "postid" => $post_id));

$sql_like = $db->prepare("UPDATE posts SET likes=likes+1 WHERE id=:postid");
$sql_like->execute(array("postid" => $post_id));


// get how many likes

$sql_like_count = $db->prepare("SELECT likes FROM posts WHERE id=:postid");
$sql_like_count->execute(array("postid" => $post_id));

$row = $sql_like_count->fetch(PDO::FETCH_ASSOC);

$likecount = $row['likes'];






if($sql_insert_like && $sql_like){




	$flag = 1;
	$array = array();
	$array['code'] = $flag;
	$array['success'] = $likecount;
	
	echo json_encode($array);

}else{
	$flag = 0;
	
	$array['code'] = $flag;
	echo json_encode($array);
}

}

?>