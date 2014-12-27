<?php
session_start();

if(isset($_GET['action']) && $_GET['action'] == "get_home_posts" && isset($_SESSION['id'])){

$flag = NULL;
$time = time();

$userid = $_SESSION['id'];


$post_display_container = NULL;


require_once("../app/init.php");


// get comment write picture

	$sql_fetch_picture = $db->prepare("SELECT avatar FROM user_profile WHERE user_id=:user LIMIT 1");
	$sql_fetch_picture->execute(array("user" => $userid));

	$row = $sql_fetch_picture->fetch(PDO::FETCH_ASSOC);

	$avatar_comment_write = $row['avatar'];

	if(empty($avatar_comment_write)){
		$img_div_comment_write = '<div class="col-md-2" style="padding:5px;  background:black;"></div>';
	}else{
		$img_div_comment_write = '<div class="col-md-2" style="padding:5px;"><img src="userdata/'.$userid.'/dp/'.$avatar_comment_write.'" style="width:50px; height:50px;" /></div>';
	}

	
/**
*   GET PROFILE POSTS AND COMMENTS
*/

/*    -----------   Get the friend list of the user first ---------               */


// check whether we are already connected



$sql_check_connections = $db->prepare("SELECT * FROM connections WHERE person_1 = :me OR person_2 = :me");

$sql_check_connections->execute(array( "me" => $userid ));

$numrow_connections = $sql_check_connections->rowCount();


// do the queries and get the posts

$connected_with = array();	

while($conn_row = $sql_check_connections->fetch(PDO::FETCH_ASSOC)){



$conn_id = $conn_row['id'];
$person_1 = $conn_row['person_1'];
$person_2 = $conn_row['person_2']; 

if($person_1 == $userid){

	$connected_with[] = $person_2;

}else if($person_2 == $userid){
	$connected_with[] = $person_1;
}

}


$connection_array = implode("," , $connected_with);


$posti_array = array($userid, $connection_array);

 $list = implode("," , $posti_array);




	



$sql_get_post = $db->prepare("SELECT * FROM posts WHERE FIND_IN_SET(posted_by, :list) ORDER BY id DESC");
$sql_get_post->execute(array("list" => $list));

$numrow_post = $sql_get_post->rowCount();

if($numrow_post > 0){

while($row_post = $sql_get_post->fetch(PDO::FETCH_ASSOC)){

$post_id = $row_post['id'];
$posted_by = $row_post['posted_by'];
$posted_to = $row_post['posted_to'];
$likes = $row_post['likes'];
$unlikes = $row_post['unlikes'];
$time_fetch = $row_post['time'];
$random_string = $row_post['random_string'];
$post = $row_post['post'];
$type = $row_post['type'];

$diff = $time - $time_fetch; // seconds
	
include "../includes/time.php";	

if($sql_get_post){

	// check likes or dislikes of the user

	if($likes == 0 && $unlikes == 0){
		
		$rating_div = '<ul class="list-inline" id="rating_div_waiting'.$post_id.'" style="background:#DADFE1; padding:5px;">
			  <li><a href="javascript:;" onClick="like_posts('.$post_id.');"><span class="glyphicon glyphicon-thumbs-up"></span> Like</a>('.$likes.')</li>
			   <li><a href="javascript:;" onClick="dislike_posts('.$post_id.');"><span class="glyphicon glyphicon-thumbs-down"></span> Dislike</a>('.$unlikes.')</li>

			  
			 </ul>';

	}else{





		// check user has liked or not

	$sql_get_like_user = $db->prepare("SELECT * FROM post_likes WHERE user_id=:userid && post_id=:postid");
	$sql_get_like_user->execute(array("userid" => $userid, "postid" => $post_id));
	$numrow_like = $sql_get_like_user->rowCount();

	// check user has disliked or not

	$sql_get_dislike_user = $db->prepare("SELECT * FROM post_dislikes WHERE user_id=:userid && post_id=:postid");
	$sql_get_dislike_user->execute(array("userid" => $userid, "postid" => $post_id));
	$numrow_dislike = $sql_get_dislike_user->rowCount();



	if($numrow_like == 1){

		$rating_div = '<ul class="list-inline" id="rating_div_like_pressed'.$post_id.'" style="background:#DADFE1; padding:5px; ">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like(<span id="postlike'.$post_id.'">'.$likes.'</span>)</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike('.$unlikes.')</li>
			   <li>You have liked <span class="glyphicon glyphicon-thumbs-up"></span></li>
			  
			 </ul>';

	}else if($numrow_dislike == 1){

		$rating_div = '<ul class="list-inline" id="rating_div_dislike_pressed'.$post_id.'" style="background:#DADFE1; padding:5px; ">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like('.$likes.')</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike(<span id="postdislike'.$post_id.'">'.$unlikes.'</span>)</li>
			   <li>You have disliked <span class="glyphicon glyphicon-thumbs-down"></span></li>
			  
			 </ul>';

	}else{

		$rating_div = '<ul class="list-inline" id="rating_div_waiting'.$post_id.'" style="background:#DADFE1; padding:5px;">
			  <li><a href="javascript:;" onClick="like_posts('.$post_id.');"><span class="glyphicon glyphicon-thumbs-up"></span> Like</a>('.$likes.')</li>
			   <li><a href="javascript:;" onClick="dislike_posts('.$post_id.');"><span class="glyphicon glyphicon-thumbs-down"></span> Dislike</a>('.$unlikes.')</li>

			  
			 </ul>';

	}


}

	




	// count number of comments

	$sql_get_comment_count = $db->prepare("SELECT id  FROM comments WHERE post_id=:postid");
	$sql_get_comment_count->execute(array("postid" => $post_id));
	$comment_count = $sql_get_comment_count->rowCount();

	//$show_more_comment_count = $comment_count - 1; 

	if($comment_count > 1){
		$show_more_comment = ' <div class="text-center" style="background:#DADFE1;" id="show_more_comment'.$post_id.'"><a href="javascript:;" onClick="comment_post_holder_show('.$post_id.');"><span class="glyphicon glyphicon-comment"></span><span id="comment_count'.$post_id.'"> '.$comment_count.'</span> Comments <span class="glyphicon glyphicon-chevron-down"></span></a> <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate" style="display:none;" id="comment_loader'.$post_id.'"></div>';
	}else if($comment_count <= 1){
		$show_more_comment = null ;
	}

	// get last comment of each post



$comment_display_container = NULL;

$sql_get_last_comment = $db->prepare("SELECT * FROM comments WHERE post_id=:postid ORDER BY id DESC LIMIT 1");
$numrow = $sql_get_last_comment->rowCount();
$sql_get_last_comment->execute(array("postid" => $post_id));


$row = $sql_get_last_comment->fetch(PDO::FETCH_ASSOC);

	$comment_id = $row['id'];
	$commented_by = $row['commented_by'];
	$likes_comment = $row['likes'];
	$unlikes_comment = $row['unlikes'];
	$time_comment = $row['time'];
	$comment = $row['comment'];


	if($sql_get_last_comment){


		// check whether user has rated the last comment or not


			// check likes or dislikes of the user

	if($likes == 0 && $unlikes == 0){
		
		$rating_div_comment = '<ul class="list-inline" id="rating_div_waiting_comment'.$comment_id.'" style="padding:5px;">
			     <li><a href="javascript:;" onClick="like_comments('.$comment_id.');"><span class="glyphicon glyphicon-thumbs-up"></span> Like</a>('.$likes_comment.')</li>
			     <li><a href="javascript:;" onClick="dislike_comments('.$comment_id.');"><span class="glyphicon glyphicon-thumbs-down"></span> Dislike</a>('.$unlikes_comment.')</li>
			   
			   </ul>';

	}else{

		
		// check user has liked or not

	$sql_get_like_user = $db->prepare("SELECT * FROM comment_likes WHERE user_id=:userid && comment_id=:commentid");
	$sql_get_like_user->execute(array("userid" => $userid, "commentid" => $comment_id));
	$numrow_like = $sql_get_like_user->rowCount();

	// check user has disliked or not

	$sql_get_dislike_user = $db->prepare("SELECT * FROM comment_dislikes WHERE user_id=:userid && comment_id=:commentid");
	$sql_get_dislike_user->execute(array("userid" => $userid, "commentid" => $comment_id));
	$numrow_dislike = $sql_get_dislike_user->rowCount();



	if($numrow_like == 1){

		$rating_div_comment = '<ul class="list-inline" id="rating_div_like_pressed_comment'.$comment_id.'" style="background:#DADFE1; padding:5px;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like(<span id="commentlike'.$comment_id.'">'.$likes_comment.'</span>)</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike('.$unlikes_comment.')</li>
			   <li>You have <span class="glyphicon glyphicon-thumbs-up"></span></li>
			  
			 </ul>';

	}else if($numrow_dislike == 1){

		$rating_div_comment = '<ul class="list-inline" id="rating_div_dislike_pressed_comment'.$comment_id.'" style="background:#DADFE1; padding:5px;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like('.$likes_comment.')</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike(<span id="commentdislike'.$comment_id.'">'.$unlikes_comment.'</span>)</li>
			   <li>You have <span class="glyphicon glyphicon-thumbs-down"></span></li>
			  
			 </ul>';

	}else{

		$rating_div_comment = '<ul class="list-inline" id="rating_div_waiting_comment'.$comment_id.'" style="padding:5px;">
			     <li><a href="javascript:;" onClick="like_comments('.$comment_id.');"><span class="glyphicon glyphicon-thumbs-up"></span> Like</a>('.$likes_comment.')</li>
			     <li><a href="javascript:;" onClick="dislike_comments('.$comment_id.');"><span class="glyphicon glyphicon-thumbs-down"></span> Dislike</a>('.$unlikes_comment.')</li>
			   
			   </ul>';

	}




	}












		// get username comment

	$sql_fetch_username_comment = $db->prepare("SELECT username FROM user_login WHERE id=:userid");
	$sql_fetch_username_comment->execute(array("userid" => $commented_by));

	$row_username = $sql_fetch_username_comment->fetch(PDO::FETCH_ASSOC); 
	$username_comment = $row_username['username'];

	//fetch comment info

	$sql_fetch_name_comment = $db->prepare("SELECT name, avatar FROM user_profile WHERE user_id=:user");
	$sql_fetch_name_comment->execute(array("user" => $commented_by));

	$row = $sql_fetch_name_comment->fetch(PDO::FETCH_ASSOC);

	$name_comment = $row['name'];
	$avatar_comment = $row['avatar'];

	if(empty($avatar_comment)){
		$img_div_comment = '<div class="col-md-2" style="padding:5px; height:50px; width:50px; background:black;"><a href="profile.php?u='.$username_comment.'"></a></div>';
	}else{
		$img_div_comment = '<div class="col-md-2" style="padding:5px;"><a href="profile.php?u='.$username_comment.'"><img src="userdata/'.$commented_by.'/dp/'.$avatar_comment.'" style="width:50px; height:50px;" /></a></div>';
	}

	
	
	}

	// comment div display if there is 1 comment else none

	if($comment_count >= 1){

	$commment_div_display =	'<div class="col-md-12" id="per_comment_div'.$comment_id.'" style="border-bottom:1px solid #6C7A89; padding:8px;">
			 
			 '.$img_div_comment.'
			 <div class="col-md-10">
			 <div style=""><b><a href="profile.php?u='.$username_comment.'">'.$name_comment.'</a></b> <div class="pull-right">delete</div><div class="text-muted"><small>posted '.$time_comment.' mins ago</small></div></div>
			 <div style="">'.$comment.'</div>
			 
			 '.$rating_div_comment.'

			   <ul class="list-inline" id="rating_div_like_pressed_comment'.$comment_id.'" style="background:#DADFE1; padding:5px; display:none;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like(<span id="commentlike'.$comment_id.'">'.$likes_comment.'</span>)</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike('.$unlikes_comment.')</li>
			   <li>You have <span class="glyphicon glyphicon-thumbs-up"></span></li>
			  
			 </ul>

			 <ul class="list-inline" id="rating_div_dislike_pressed_comment'.$comment_id.'" style="background:#DADFE1; padding:5px; display:none;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like('.$likes_comment.')</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike(<span id="commentdislike'.$comment_id.'">'.$unlikes_comment.'</span>)</li>
			   <li>You have <span class="glyphicon glyphicon-thumbs-down"></span></li>
			  
			 </ul>
			 
			 </div>
			 </div>';

	}else{
		$commment_div_display = NULL;
	}





// get post username



	$sql_fetch_username = $db->prepare("SELECT username FROM user_login WHERE id=:userid");
	$sql_fetch_username->execute(array("userid" => $posted_by));

	$row_username = $sql_fetch_username->fetch(PDO::FETCH_ASSOC); 
	$username = $row_username['username'];

	//fetch post info

	$sql_fetch_name = $db->prepare("SELECT name, avatar FROM user_profile WHERE user_id=:user");
	$sql_fetch_name->execute(array("user" => $posted_by));

	$row = $sql_fetch_name->fetch(PDO::FETCH_ASSOC);

	$name = $row['name'];
	$avatar = $row['avatar'];

	if(empty($avatar)){
		$img_div = '<div class="col-md-2" ><div style="height:70px; width:70px; background:black;"><a href="profile.php?u='.$username.'"></a></div></div>';
	}else{
		$img_div = '<div class="col-md-2" ><div style="height:70px; width:70px; background:black;"><a href="profile.php?u='.$username.'"><img src="userdata/'.$posted_by.'/dp/'.$avatar.'" style="width:70px; height:70px;" /></a></div></div>';
	}




	if($posted_by !== $posted_to){

	$sql_fetch_username_posted_to = $db->prepare("SELECT username FROM user_login WHERE id=:userid");
	$sql_fetch_username_posted_to->execute(array("userid" => $posted_to));

	$row_username = $sql_fetch_username_posted_to->fetch(PDO::FETCH_ASSOC); 
	$username_posted_to = $row_username['username'];

	//fetch post info

	$sql_fetch_name_posted_to = $db->prepare("SELECT name, avatar FROM user_profile WHERE user_id=:user");
	$sql_fetch_name_posted_to->execute(array("user" => $posted_to));

	$row = $sql_fetch_name_posted_to->fetch(PDO::FETCH_ASSOC);

	$name_posted_to = $row['name'];
	$avatar_posted_to = $row['avatar'];

	if(empty($avatar_posted_to)){
		$img_div_posted_to = '<span style="height:40px; width:40px; background:black;"><a href="profile.php?u='.$username_posted_to.'"></a></span>';
	}else{
		$img_div_posted_to = '<span style="height:40px; width:40px; background:black;"><a href="profile.php?u='.$username_posted_to.'"><img src="userdata/'.$posted_to.'/dp/'.$avatar_posted_to.'" style="width:40px; height:40px;" /></a></span>';
	}


}


if($posted_by !== $posted_to){
	$post_name_header = '<b><a href="profile.php?u='.$username.'">'.$name.'</a></b>  <span class="glyphicon glyphicon-arrow-right"></span>  <b><a href="profile.php?u='.$username_posted_to.'">'.$name_posted_to.'</a></b> '.$img_div_posted_to.' ';
}else if($posted_by === $posted_to){
	$post_name_header = '<b><a href="profile.php?u='.$username.'">'.$name.'</a></b> ';
}

	

$post_display_container .=  '<div id="post_and_comment_holder_grid'.$post_id.'" class="row" style="background:#F2F1EF; border:1px solid #95A5A6; padding:10px; margin-top:10px; border-radius:5px;">
			
			  <!-- Each post grid -->
			
			
			
			  '.$img_div.'
			  <div class="col-md-10">
			  <div style="">'.$post_name_header.'<div class="pull-right">delete</div><div class="text-muted"><small>'.$count.' '.$suffix.' old</small></div></div>
			  <div style="word-wrap:break-word; white-space:pre-wrap;">'.$post.'</div>
			  <br />
			 
			 '.$rating_div.'


			 <ul class="list-inline" id="rating_div_like_pressed'.$post_id.'" style="background:#DADFE1; padding:5px; display:none;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like(<span id="postlike'.$post_id.'">'.$likes.'</span>)</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike('.$unlikes.')</li>
			   <li>You have liked <span class="glyphicon glyphicon-thumbs-up"></span></li>
			  
			 </ul>

			 <ul class="list-inline" id="rating_div_dislike_pressed'.$post_id.'" style="background:#DADFE1; padding:5px; display:none;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like('.$likes.')</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike(<span id="postdislike'.$post_id.'">'.$unlikes.'</span>)</li>
			   <li>You have disliked <span class="glyphicon glyphicon-thumbs-down"></span></li>
			  
			 </ul>



			
			
			 
			 <div class="row" id="comment_container'.$post_id.'" style="background:#DADFE1;">
			
			 		'.$show_more_comment.'

			 <div>

			 
			<div id="get_comments'.$post_id.'">



				'.$commment_div_display.'




			</div>
			
			 </div>


			  <div class="col-md-12" style="padding:10px;">

			 '.$img_div_comment_write.'

			 
			 
			 <div class="col-md-10" style="padding:4px; background:#DADFE1;">
			
			
			
			<input type="text" placeholder="Write Comment.." style="padding:10px; width:100%;" id="comment_form_text'.$post_id.'" />
			<a href="javascript:;" class="btn btn-xs btn-primary" onClick="comment_click('.$post_id.');" style="margin-top:5px;"><span class="glyphicon glyphicon-send"></span> Comment</a>

			

			</div>
			</div>

			 </div>	
			 
			 
			
			 
			 
			
			 
			 
			 
			 </div>
			
			
			
			
			
			
	  
			</div> ';


}

}






}else{

	$flag = 2;
	$array = array();
	$array['code'] = $flag;
	$array['no_posts'] = '<div class="row" id="empty_newsfeed'.$userid.'" style="background:#F2F1EF; border:1px solid #95A5A6; padding:10px; margin-top:10px; border-radius:5px;">Your empty newsfeed can be the resultant of your empty connections .Make connections with people and you will get the updates of their activity.</div>';
	
	echo json_encode($array);
}




if($sql_check_connections){

	$flag = 1;
	
	$array['code'] = $flag;
	$array['success'] = $post_display_container;
	echo json_encode($array);

//echo $post_display_container;

}




}
?>