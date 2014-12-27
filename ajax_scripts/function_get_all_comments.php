<?php



if(isset($_GET['action']) && $_GET['action'] == "get_all_comments"){

ignore_user_abort(true);
set_time_limit(0);

$flag = NULL;

require_once("../app/init.php");

while (true) {


$flag = 1;





// get comments
$comment_display_container = NULL;

    $last_ajax_call = isset($_GET['timestamp']) ? (int)$_GET['timestamp'] : null;

    // PHP caches file data, like requesting the size of a file, by default. clearstatcache() clears that cache
    clearstatcache();
    // get timestamp of when file has been changed the last time

$sql_get_comments_time = $db->prepare("SELECT time FROM comments ORDER BY id DESC LIMIT 1");
$numrow = $sql_get_comments_time->rowCount();
$sql_get_comments_time->execute();

$row_time = $sql_get_comments_time->fetch(PDO::FETCH_ASSOC);

	
$time_last = $row_time['time'];
	


$last_change_in_data_file = $time_last;

 if ($last_ajax_call == null || $last_change_in_data_file > $last_ajax_call) {


$sql_get_comments = $db->prepare("SELECT * FROM comments WHERE time=:change_time LIMIT 1");
$sql_get_comments->execute(array("change_time" => $time_last));
$numrow = $sql_get_comments->rowCount();

if($numrow < 1){

}else if($numrow >= 1){





$row = $sql_get_comments->fetch(PDO::FETCH_ASSOC);

	$post_id = $row['post_id'];
	$comment_id = $row['id'];
	$commented_by = $row['commented_by'];
	$likes = $row['likes'];
	$unlikes = $row['unlikes'];
	$time = $row['time'];
	$comment = $row['comment'];




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



	$comment_display_container = '<div class="col-md-12" id="per_comment_div'.$comment_id.'" style="border-bottom:1px solid #6C7A89; padding:8px;">
			 
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
//echo $comment_display_container;

	
	$array = array();
	$array['success'] = $comment_display_container;
	$array['commentid'] = $comment_id;
	$array['postid'] = $post_id;
	$array['timestamp'] = $last_change_in_data_file;
	echo json_encode($array);

 break;


}else {
        // wait for 1 sec (not very sexy as this blocks the PHP/Apache process, but that's how it goes)
        sleep( 1 );
        continue;
    }


}
	}


?>	