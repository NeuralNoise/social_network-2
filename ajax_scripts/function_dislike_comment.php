<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == "dislike_comment" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];


$comment_id = $_POST['commentid'];


require_once("../app/init.php");

$sql_insert_dislike = $db->prepare("INSERT INTO comment_dislikes VALUES(null, :user, :cmid)");
$sql_insert_dislike->execute(array("user" => $userid, "cmid" => $comment_id));

$sql_dislike = $db->prepare("UPDATE comments SET unlikes=unlikes+1 WHERE id=:cmid");
$sql_dislike->execute(array("cmid" => $comment_id));


// get how many likes

$sql_dislike_count = $db->prepare("SELECT unlikes FROM comments WHERE id=:cmid");
$sql_dislike_count->execute(array("cmid" => $comment_id));

$row = $sql_dislike_count->fetch(PDO::FETCH_ASSOC);

$dislikecount = $row['unlikes'];



//echo $likecount;


if($sql_insert_dislike && $sql_dislike && $sql_dislike_count){




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