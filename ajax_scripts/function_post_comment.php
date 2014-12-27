<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == "post_comment_clicked" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];

$comment = $_POST['comment'];
$post_id = $_POST['postid'];


require_once("../app/init.php");


$time = time();

$likes = 0;
$unlikes = 0;



$sql_insert = $db->prepare("INSERT INTO comments VALUES(null, :postid, :commented_by, :likes, :unlikes, :time, :comment)");

$sql_insert->execute(array(

	"postid" => $post_id,
	"commented_by" => $userid,
	"likes" => 0,
	"unlikes" => 0,
	"time" => $time,
	"comment" => $comment
	));

// get comment count
$comment_id = $db->lastInsertId();


	/*$sql_get_comment_count = $db->prepare("SELECT id  FROM comments WHERE post_id=:postid");
	$sql_get_comment_count->execute(array("postid" => $post_id));
	$comment_count = $sql_get_comment_count->rowCount();





	// get username

	$sql_fetch_username = $db->prepare("SELECT username FROM user_login WHERE id=:userid");
	$sql_fetch_username->execute(array("userid" => $userid));

	$row_username = $sql_fetch_username->fetch(PDO::FETCH_ASSOC); 
	$username = $row_username['username'];

	//fetch comment info

	$sql_fetch_name = $db->prepare("SELECT name, avatar FROM user_profile WHERE user_id=:user LIMIT 1");
	$sql_fetch_name->execute(array("user" => $userid));

	$row = $sql_fetch_name->fetch(PDO::FETCH_ASSOC);

	

	$name = $row['name'];
	$avatar = $row['avatar'];

	if(empty($avatar)){
		$img_div = '<div class="col-md-2" style="padding:5px; height:50px; width:50px; background:black;"><a href="profile.php?u='.$username.'"></a></div>';
	}else{
		$img_div = '<div class="col-md-2" style="padding:5px;"><a href="profile.php?u='.$username.'"><img src="userdata/'.$userid.'/dp/'.$avatar.'" style="width:50px; height:50px;" /></a></div>';
	}

	$comment_display_container = '  <div class="col-md-12" id="per_comment_div'.$comment_id.'" style="border-bottom:1px solid #6C7A89; padding:8px;">
			 
			 '.$img_div.'
			 <div class="col-md-10">
			 <div style=""><b><a href="profile.php?u='.$username.'">'.$name.'</a></b> <div class="pull-right">delete</div><div class="text-muted"><small>posted '.$time.' mins ago</small></div></div>
			 <div style="">'.$comment.'</div>
			 
			 <ul class="list-inline" id="rating_div_waiting_comment'.$comment_id.'" style="padding:5px;">
			     <li><a href="javascript:;" onClick="like_comments('.$comment_id.');"><span class="glyphicon glyphicon-thumbs-up"></span> Like</a>('.$likes.')</li>
			     <li><a href="javascript:;" onClick="dislike_comments('.$comment_id.');"><span class="glyphicon glyphicon-thumbs-down"></span> Dislike</a>('.$unlikes.')</li>
			   
			   </ul>

			   <ul class="list-inline" id="rating_div_like_pressed_comment'.$comment_id.'" style="background:#DADFE1; padding:5px; display:none;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like(<span id="commentlike'.$comment_id.'">'.$likes.'</span>)</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike('.$unlikes.')</li>
			   <li>You have <span class="glyphicon glyphicon-thumbs-up"></span></li>
			  
			 </ul>

			 <ul class="list-inline" id="rating_div_dislike_pressed_comment'.$comment_id.'" style="background:#DADFE1; padding:5px; display:none;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like('.$likes.')</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike(<span id="commentdislike'.$comment_id.'">'.$unlikes.'</span>)</li>
			   <li>You have <span class="glyphicon glyphicon-thumbs-down"></span></li>
			  
			 </ul>


			 </div>
			 </div> '; 



	
	$array = array();
	$array['code'] = $flag;
	$array['success'] = $comment_display_container;
	$array['commentid'] = $comment_id;
	$array['commentcount'] = $comment_count;
	echo json_encode($array);

*/



}

?>