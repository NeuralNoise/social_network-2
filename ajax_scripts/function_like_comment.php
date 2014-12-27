<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == "like_comment" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];


$comment_id = $_POST['commentid'];


require_once("../app/init.php");

$sql_insert_like = $db->prepare("INSERT INTO comment_likes VALUES(null, :user, :cmid)");
$sql_insert_like->execute(array("user" => $userid, "cmid" => $comment_id));

$sql_like = $db->prepare("UPDATE comments SET likes=likes+1 WHERE id=:cmid");
$sql_like->execute(array("cmid" => $comment_id));


// get how many likes

$sql_like_count = $db->prepare("SELECT likes FROM comments WHERE id=:cmid");
$sql_like_count->execute(array("cmid" => $comment_id));

$row = $sql_like_count->fetch(PDO::FETCH_ASSOC);

$likecount = $row['likes'];



//echo $likecount;


if($sql_insert_like && $sql_like && $sql_like_count){




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