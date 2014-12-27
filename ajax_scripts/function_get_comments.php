<?php
session_start();

if(isset($_GET['action']) && $_GET['action'] == "get_comments" && isset($_SESSION['id'])){





$flag = NULL;

$userid = $_SESSION['id'];


$post_id = $_GET['postid'];


require_once("../app/init.php");


// get comments
$comment_display_container = NULL;

//  get last comment id





$sql_get_comments = $db->prepare("SELECT * FROM comments WHERE post_id=:postid ORDER BY id ASC");
$numrow = $sql_get_comments->rowCount();
$sql_get_comments->execute(array("postid" => $post_id));


while($row = $sql_get_comments->fetch(PDO::FETCH_ASSOC)){

	$comment_id = $row['id'];
	$commented_by = $row['commented_by'];
	$likes = $row['likes'];
	$unlikes = $row['unlikes'];
	$time = $row['time'];
	$comment = $row['comment'];




	if($sql_get_comments){


		// check user has rated or not

		// check likes or dislikes of the user

	if($likes == 0 && $unlikes == 0){
		
		$rating_div_comment = '<ul class="list-inline" id="rating_div_waiting_comment'.$comment_id.'" style="padding:5px;">
			     <li><a href="javascript:;" onClick="like_comments('.$comment_id.');"><span class="glyphicon glyphicon-thumbs-up"></span> Like</a>('.$likes.')</li>
			     <li><a href="javascript:;" onClick="dislike_comments('.$comment_id.');"><span class="glyphicon glyphicon-thumbs-down"></span> Dislike</a>('.$unlikes.')</li>
			   
			   </ul>';

	}else{

		
		// check user has liked or not

	$sql_get_like_user = $db->prepare("SELECT * FROM comment_likes WHERE user_id=:userid && comment_id=:commentid");
	$sql_get_like_user->execute(array("userid" => $userid, "commentid" => $comment_id));
	$numrow_like = $sql_get_like_user->rowCount();

	// check user has disliked or not

	$sql_get_dislike_user = $db->prepare("SELECT * FROM post_dislikes WHERE user_id=:userid && comment_id=:commentid");
	$sql_get_dislike_user->execute(array("userid" => $userid, "commentid" => $comment_id));
	$numrow_dislike = $sql_get_dislike_user->rowCount();



	if($numrow_like == 1){

		$rating_div_comment = '<ul class="list-inline" id="rating_div_like_pressed_comment'.$comment_id.'" style="background:#DADFE1; padding:5px;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like(<span id="commentlike'.$comment_id.'">'.$likes.'</span>)</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike('.$unlikes.')</li>
			   <li>You have <span class="glyphicon glyphicon-thumbs-up"></span></li>
			  
			 </ul>';

	}else if($numrow_dislike == 1){

		$rating_div_comment = '<ul class="list-inline" id="rating_div_dislike_pressed_comment'.$comment_id.'" style="background:#DADFE1; padding:5px;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like('.$likes.')</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike(<span id="commentdislike'.$comment_id.'">'.$unlikes.'</span>)</li>
			   <li>You have <span class="glyphicon glyphicon-thumbs-down"></span></li>
			  
			 </ul>';

	}else{

		$rating_div_comment = '<ul class="list-inline" id="rating_div_waiting_comment'.$comment_id.'" style="padding:5px;">
			     <li><a href="javascript:;" onClick="like_comments('.$comment_id.');"><span class="glyphicon glyphicon-thumbs-up"></span> Like</a>('.$likes.')</li>
			     <li><a href="javascript:;" onClick="dislike_comments('.$comment_id.');"><span class="glyphicon glyphicon-thumbs-down"></span> Dislike</a>('.$unlikes.')</li>
			   
			   </ul>';

	}




	}







		// get username

	$sql_fetch_username = $db->prepare("SELECT username FROM user_login WHERE id=:userid");
	$sql_fetch_username->execute(array("userid" => $commented_by));

	$row_username = $sql_fetch_username->fetch(PDO::FETCH_ASSOC); 
	$username = $row_username['username'];

	//fetch post info

	$sql_fetch_name = $db->prepare("SELECT name, avatar FROM user_profile WHERE user_id=:user");
	$sql_fetch_name->execute(array("user" => $commented_by));

	$row = $sql_fetch_name->fetch(PDO::FETCH_ASSOC);

	$name = $row['name'];
	$avatar = $row['avatar'];

	if(empty($avatar)){
		$img_div = '<div class="col-md-2" style="padding:5px; height:50px; width:50px; background:black;"><a href="profile.php?u='.$username.'"></a></div>';
	}else{
		$img_div = '<div class="col-md-2" style="padding:5px;"><a href="profile.php?u='.$username.'"><img src="userdata/'.$commented_by.'/dp/'.$avatar.'" style="width:50px; height:50px;" /></a></div>';
	}



	$comment_display_container .= '<div class="col-md-12" id="per_comment_div'.$comment_id.'" style="border-bottom:1px solid #6C7A89; padding:8px;">
			 
			 '.$img_div.'
			 <div class="col-md-10">
			 <div style=""><b><a href="profile.php?u='.$username.'">'.$name.'</a></b> <div class="pull-right">delete</div><div class="text-muted"><small>posted '.$time.' mins ago</small></div></div>
			 <div style="">'.$comment.'</div>
			 
			  '.$rating_div_comment.'

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
			 </div>'; 
	
	}


} 

if($sql_get_comments){


//echo $comment_display_container;

	$flag = 1;
	$array = array();
	$array['code'] = $flag;
	$array['success'] = $comment_display_container;
	$array['commentid'] = $comment_id;
	echo json_encode($array);

}else{
	$flag = 0;
	
	$array['code'] = $flag;
	echo json_encode($array);
}

}

?>