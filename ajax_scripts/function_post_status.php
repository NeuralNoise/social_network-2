<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == "post_status_clicked" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];

$post = $_POST['post'];


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


$time = time();

while($i = 1){

$random_string = md5(uniqid(rand(), true));


$check_string = $db->prepare("SELECT id FROM posts WHERE random_string=:rand LIMIT 1");
$check_string->execute(array("rand" => $random_string));
$numrow = $check_string->rowCount();

if($numrow == 1){

$i++;

}else{
	$this_random_string = $random_string; 
}

	break;
}

$sql_insert = $db->prepare("INSERT INTO posts VALUES(null, :posted_by, :posted_to, :likes, :unlikes, :time, :rand, :post, :type)");
$sql_insert->execute(array(

	"posted_by" => $userid,
	"posted_to" => $userid,
	"likes" => 0,
	"unlikes" => 0,
	"time" => $time,
	"rand" => $this_random_string,
	"post" => $post,
	"type" => 1


	));

$post_id = $db->lastInsertId();

if($sql_insert){

	// get username

	$sql_fetch_username = $db->prepare("SELECT username FROM user_login WHERE id=:userid");
	$sql_fetch_username->execute(array("userid" => $userid));

	$row_username = $sql_fetch_username->fetch(PDO::FETCH_ASSOC); 
	$username = $row_username['username'];

	//fetch post info

	$sql_fetch_name = $db->prepare("SELECT name, avatar FROM user_profile WHERE user_id=:user LIMIT 1");
	$sql_fetch_name->execute(array("user" => $userid));

	$row = $sql_fetch_name->fetch(PDO::FETCH_ASSOC);

	$name = $row['name'];
	$avatar = $row['avatar'];

	if(empty($avatar)){
		$img_div = '<div class="col-md-2" ><div style="height:70px; width:70px; background:black;"><a href="profile.php?u='.$username.'"></a></div></div>';
	}else{
		$img_div = '<div class="col-md-2" ><div style="height:70px; width:70px; background:black;"><a href="profile.php?u='.$username.'"><img src="userdata/'.$userid.'/dp/'.$avatar.'" style="width:70px; height:70px;" /></a></div></div>';
	}

	$post_display_container = ' <div id="post_and_comment_holder_grid'.$post_id.'" class="row" style="background:#F2F1EF; border:1px solid #95A5A6; padding:10px; margin-top:10px; border-radius:5px;">
			
			  <!-- Each post grid -->
			
			
			
			  '.$img_div.'
			  <div class="col-md-10">
			  <div style=""><b><a href="profile.php?u='.$username.'">'.$name.'</a></b> <div class="pull-right">delete</div><div class="text-muted"><small>posted '.$time.' ago</small></div></div>
			  <div style="word-wrap:break-word; white-space:pre-wrap;">'.$post.'</div>
			  <br />
			 
			 <ul class="list-inline" id="rating_div_waiting'.$post_id.'" style="background:#DADFE1; padding:5px;">
			  <li><a href="javascript:;" onClick="like_posts('.$post_id.');"><span class="glyphicon glyphicon-thumbs-up"></span> Like</a>(0)</li>
			   <li><a href="javascript:;" onClick="dislike_posts('.$post_id.');"><span class="glyphicon glyphicon-thumbs-down"></span> Dislike</a>(0)</li>

			  
			 </ul>

			 <ul class="list-inline" id="rating_div_like_pressed'.$post_id.'" style="background:#DADFE1; padding:5px; display:none;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like(<span id="postlike'.$post_id.'">0</span>)</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike(0)</li>
			   <li>You have liked <span class="glyphicon glyphicon-thumbs-up"></span></li>
			  
			 </ul>

			 <ul class="list-inline" id="rating_div_dislike_pressed'.$post_id.'" style="background:#DADFE1; padding:5px; display:none;">
			  <li><span class="glyphicon glyphicon-thumbs-up"></span> Like(0)</li>
			   <li><span class="glyphicon glyphicon-thumbs-down"></span> Dislike(<span id="postdislike'.$post_id.'">0</span>)</li>
			   <li>You have disliked <span class="glyphicon glyphicon-thumbs-down"></span></li>
			  
			 </ul>
			 
			

			 <div class="col-md-12" style="padding:10px;">
			 '.$img_div_comment_write.'


			 <div class="col-md-10" style="padding:4px; background:#DADFE1;">
			
			
			<input type="text" placeholder="Write Comment.." style="padding:10px; width:100%;" id="comment_form_text'.$post_id.'" />
			<a href="javascript:;" class="btn btn-xs btn-primary" onClick="comment_click('.$post_id.');" style="margin-top:5px;"><span class="glyphicon glyphicon-send"></span> Comment</a>

			

			</div>

			 


			 <div class="row" id="comment_container'.$post_id.'">
			 <div>


			<div id="get_comments'.$post_id.'">

			</div>
			
			 </div>
			 </div>	
			 
			
			 </div>
			 
			 
			 </div>
			
			
			
			
			
			
	  
			</div> '; 



	$flag = 1;
	$array = array();
	$array['code'] = $flag;
	$array['success'] = $post_display_container;
	$array['uid'] = $userid;
	echo json_encode($array);

}else{
	$flag = 0;
	
	$array['code'] = $flag;
	echo json_encode($array);

}

}

?>