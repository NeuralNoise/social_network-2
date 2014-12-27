<?php

session_start();

if(isset($_GET['u']) && (!empty($_GET['u']))){

	$username = $_GET['u'];

	if(isset($_SESSION['id'])){
		$userid = $_SESSION['id'];
	}

	require_once("app/init.php");

	

	$sql_check_username = $db->prepare("SELECT id FROM user_login WHERE username=:username LIMIT 1");
	$sql_check_username->execute(array("username"=>$username));
	$numrow = $sql_check_username->rowCount();

	if($numrow == 1){

	$row = $sql_check_username->fetch(PDO::FETCH_ASSOC);	

	$user_id_not_me = $row['id'];	

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $username; ?></title>

    
<?php require_once("includes/header_sidebar.php") ; ?> 

		
		<!-- Middle Div  -->
		
        <div class="col-md-4" id="middle_div">
		
		<!-- Profile picture  -->
		
          <div class="col-md-12" style="padding:10px;">


<!-- -->



          
           <?php

require_once("app/init.php");



$sql_get_profile_info = $db->prepare("SELECT * FROM user_profile WHERE user_id=:userid LIMIT 1");
$sql_get_profile_info->execute(array("userid"=>$user_id_not_me));

$row = $sql_get_profile_info->fetch(PDO::FETCH_ASSOC);

$name = $row['name'];
$birth_city = $row['birth_city'];
$current_city = $row['current_city'];
$school = $row['school'];
$college = $row['college'];
$website = $row['website'];
$about = $row['about'];
$avatar = $row['avatar'];



if(empty($avatar)){

	echo '<div class="center-block" id="dashboard_img_profile" style="background:black; width:250px; height:250px; float:none;"></div>';
 
}else{
	
	echo '<div class="center-block" id="dashboard_img_profile" style="background:black; width:250px; height:250px; float:none;"><img src="userdata/'.$user_id_not_me.'/dp/'.$avatar.'?t='.$time.'" style="width:250px; height:250px; " /></div>';
}

// get last timestamp of comments for realtime comment

  $sql_get_comments_time = $db->prepare("SELECT time FROM comments ORDER BY id DESC LIMIT 1");
  $numrow = $sql_get_comments_time->rowCount();
  $sql_get_comments_time->execute();

  $row_time = $sql_get_comments_time->fetch(PDO::FETCH_ASSOC);

    
  $time_last = $row_time['time'];

// check whether i have sent connection request

$sql_get_connection_info = $db->prepare("SELECT id FROM connection_requests WHERE sent_from=:userid AND sent_to=:to LIMIT 1");
$sql_get_connection_info->execute(array("userid"=>$userid, "to"=>$user_id_not_me));

$numrow_1 = $sql_get_connection_info->rowCount();

// check whether that person has already sent me a connection request if i visit his profile

$sql_check_connection_sent_to_me = $db->prepare("SELECT id FROM connection_requests WHERE sent_from=:sent_from AND sent_to=:me LIMIT 1");
$sql_check_connection_sent_to_me->execute(array("sent_from"=>$user_id_not_me, "me"=>$userid));


$numrow_2 = $sql_check_connection_sent_to_me->rowCount();


// check whether we are already connected

$sql_check_we_are_connected = $db->prepare("SELECT id FROM connections WHERE (person_1=:me AND person_2=:you) OR (person_1=:you AND person_2=:me) LIMIT 1");
$sql_check_we_are_connected->execute(array("me" => $userid, "you" => $user_id_not_me));

$numrow_3 = $sql_check_we_are_connected->rowCount();


if($numrow_1 == 1){
	$connect_btn = '<div class="text-center" style="margin-top:10px;"><a href="javascript:;" style="display:none;" id="connect_send" onClick="connect_send('.$user_id_not_me.'); ">Connect</a>
	<a href="javascript:;" id="connect_cancel"  onClick="cancel_connect('.$user_id_not_me.'); ">Requested.. Cancel?</a></div>';
}else if($numrow_2 == 1){

$connect_btn = '<div class="text-center" style="margin-top:10px;">Already requested you. <a href="javascript:;" id="conn_req_profile_tab">Check</a></div>';
}else if($numrow_3 == 1){

$connect_btn = '<div class="text-center" style="margin-top:10px;"><a href="javascript:;"  id="close_connection" onClick="close_connection('.$user_id_not_me.'); ">Close Connection</a></div>';

}else{
	$connect_btn = '<div class="text-center" style="margin-top:10px;"><a href="javascript:;"  id="connect_send" onClick="connect_send('.$user_id_not_me.'); ">Connect</a>
	<a href="javascript:;" id="connect_cancel" style="display:none;" onClick="cancel_connect('.$user_id_not_me.'); ">Requested.. Cancel?</a></div>'; 
}




if($userid === $user_id_not_me){

	
	$buttons = ' <div class="pull-right" style="margin-top:10px;"><a href="javascript:;" id="popup"><span class="glyphicon glyphicon-edit"></span> Edit Profile</a></div>

						<div class="pull-left fileContainer" style="margin-top:10px;">
						<form enctype="multipart/form-data" id="myform">
						<span class="glyphicon glyphicon-camera"></span>
						 Change Picture    
						<input type="file" accept="image/*" multiple name="image[]" id="image" />
						
						</form>
						</div>
						';


		  


}elseif($userid !== $user_id_not_me){
	$buttons = $connect_btn;
}



echo '<div id="edit_profile_form">



<span style="position:absolute; margin-left:150px; margin-top:20px; font-weight:bold;">Edit your Profile Infos</span>
<input type="text" id="edit_name_profile" value="'.$name.'" placeholder="Your fullname with space" />
<input type="text" id="edit_birthcity_profile" value="'.$birth_city.'" placeholder="'.$birth_city.'"/>
<input type="text" id="edit_currentcity_profile" value="'.$current_city.'" placeholder="'.$current_city.'" />
<input type="text" id="edit_school_profile" value="'.$school.'" placeholder="'.$school.'" />
<input type="text" id="edit_university_profile" value="'.$college.'" placeholder="'.$college.'" />
<input type="text" id="edit_website_profile" value="'.$website.'" placeholder="'.$website.'" />
<textarea id="edit_about_profile" cols="40" rows="10">'.$about.'</textarea>
<input type="button" id="edit_profile_save_btn" value="Save" />
<input type="button" id="edit_profile_cancel_btn" value="Cancel" />
</div>';


// getting user connections

$connection_list = NULL;

// get connection count only

$sql_get_user_connection_count = $db->prepare("SELECT id FROM connections WHERE person_1=:pageid OR person_2=:pageid");
$sql_get_user_connection_count->execute(array("pageid" => $user_id_not_me));

$numrow_list = $sql_get_user_connection_count->rowCount();

// now start the connection query

$sql_get_user_connection_list = $db->prepare("SELECT * FROM connections WHERE person_1=:pageid OR person_2=:pageid LIMIT 3");
$sql_get_user_connection_list->execute(array("pageid" => $user_id_not_me));

//$numrow_list = $sql_get_user_connection_list->rowCount();

if($numrow_list == 0){
	$connection_list = NULL;
}else{

	while($row = $sql_get_user_connection_list->fetch(PDO::FETCH_ASSOC)){


		$person_1 = $row['person_1'];
		$person_2 = $row['person_2'];

		if($person_1 == $user_id_not_me){
			$connected_with = $person_2;
		}else if($person_2 == $user_id_not_me){
			$connected_with = $person_1;
		}

// get username of connected people

	$sql_get_username = $db->prepare("SELECT username FROM user_login WHERE id=:userid");
	$sql_get_username->execute(array("userid" => $connected_with));

	while($row_username = $sql_get_username->fetch(PDO::FETCH_ASSOC)){
		$username_connection = $row_username['username'];


	}
	
	


	
// get name and image of connected people

	$sql_get_info = $db->prepare("SELECT name, avatar FROM user_profile WHERE user_id=:connected_person");
	$sql_get_info->execute(array("connected_person" => $connected_with));

	while ($row_connection = $sql_get_info->fetch(PDO::FETCH_ASSOC)) {
		$name_connection = $row_connection['name'];
		$avatar_connection = $row_connection['avatar'];




		$connection_list .= '<div class="col-md-4 each-friend-display"><a href="profile.php?u='.$username_connection.'"  title="'.$name_connection.'"><img src="userdata/'.$connected_with.'/dp/'.$avatar_connection.'" style="width:70px; height:70px;" /></a></div>';

	}

		

	}


	
}



 ?>	





 			<?php echo $buttons; ?>
		 
		  <!--<div class="pull-right" style="margin-top:10px;">Change Picture</div>
		  <div class="pull-left" style="margin-top:10px;">Edit Profile</div>-->
		  <!--<div class="text-center" style="margin-top:10px;">Connect</div>-->
		  </div>
		  
		  <div class="col-md-12" style="padding:5px;">
		  <!-- space between textarea and post holder -->
		  </div>
		  
		  <div class="col-md-12"  style="padding:10px;">
			 <ul class="nav nav-stacked span2 text-left" id="user_info_section" style="padding:10px;">
				<li id="name_info"><?php echo $name; ?></li>
			   <li id="b_city_info">From <?php echo $birth_city; ?></li>
			   <li id="c_city_info">Lives in <?php echo $current_city; ?></li>
			   <li id="school_info">School : <?php echo $school; ?></li>
			   <li id="college_info">College : <?php echo $college; ?></li>
			   <li id="website_info"><?php echo $website; ?></li>
			   <li id="about_info" style="word-wrap: break-word; white-space:pre-wrap;"><?php echo $about; ?></li>
			 </ul>
		  </div>
		  
       </div>

       <!-- Connections display modal -->

       <div id="connections_show_modal">
			<div class="text-center"><a href="javascript:;" onClick="close_connection_modal();" style="margin-top:70px;">Close</a></div>      
            
			<h5 class="text-center" style="margin-top:20px;">Connections(<?php echo $numrow_list; ?>)</h5>
			<div class="text-center"><input type="text" style="padding:5px;" id="connection_search_field" placeholder="Search..."/></div>
			<br />

			<div class="text-center" style="margin-top:50px; display:none;" id="connection_display_loader">Loading......</div>

			<div class="col-md-12" id="display_connections_modal" style="padding:5px; left:15px; ">
				<!--<div class="col-md-3 each-friend-display"><img src="http://displaypix.com/images/items/itm_2013-01-08_19-55-00_6.jpg" style="width:70px; height:70px;" /></div>
				<div class="col-md-3 each-friend-display"><img src="http://displaypix.com/images/items/itm_2013-01-08_19-55-00_6.jpg" style="width:70px; height:70px;" /></div>
				<div class="col-md-3 each-friend-display"><img src="http://displaypix.com/images/items/itm_2013-01-08_19-55-00_6.jpg" style="width:70px; height:70px;" /></div>
				<div class="col-md-3 each-friend-display"><img src="http://displaypix.com/images/items/itm_2013-01-08_19-55-00_6.jpg" style="width:70px; height:70px;" /></div>-->
				
				
			</div>

		</div>



	   
	   <!-- Rightmost column -->
	   
        <div id="right_most_column" class="col-md-6">
          
			<!-- Recent Post and upload holder -->
		  
		  <div id="recent_post_and_uploads_holder" class="col-md-12" style=" padding:10px;">
		  <div id="recent_post" class="col-md-7" style="background:#ECECEC; padding:10px; border-right:1px solid #95A5A6">
			<div class="text-center" style="text-decoration:underline;"><h5>Connections(<?php echo $numrow_list; ?>)</h5></div>
			
			
			<!--<div class="col-md-4 each-friend-display"><img src="http://displaypix.com/images/items/itm_2013-01-08_19-55-00_6.jpg" style="width:70px; height:70px;" /></div>
			<div class="col-md-4 each-friend-display"><img src="http://displaypix.com/images/items/itm_2013-01-08_19-55-00_6.jpg" style="width:70px; height:70px;" /></div>
			<div class="col-md-4 each-friend-display"><img src="http://displaypix.com/images/items/itm_2013-01-08_19-55-00_6.jpg" style="width:70px; height:70px;" /></div>-->

			<?php echo $connection_list; ?>
			
			<?php if($numrow_list > 3){

				echo '<div class="text-center"><a href="javascript:;" onClick="open_connection_modal('.$user_id_not_me.');">More</a></div>';

			}
			?>
		  </div>
		  <div id="uploads" class="col-md-5" style="background:#ECECEC;  padding:10px;">
			<h5 style="text-decoration:underline;">Uploads</h5>
 			Photos<br />
 			Videos<br />
 			Files<br /> 
		  </div>
		  </div>
		  
		  <br />
		  
		  <!-- Textarea holder -->
		  
		  <div  class="col-md-12" style="background:#95A5A6; padding: 10px;"><textarea id="status_editor_textarea" class="form-control" rows="3" placeholder="Write whatever you want... Who's stopping? o.O"></textarea><br />
			   
		<?php
 		
 		if($userid === $user_id_not_me){

 		echo '<button type="button" onClick="post_status();" style="margin-top:-10px;" class="btn btn-default pull-right"><span class="glyphicon glyphicon-send"></span> POST</button><br />';
 			}elseif($userid !== $user_id_not_me){
 		echo '<button type="button" onClick="post_others('.$user_id_not_me.');" style="margin-top:-10px;" class="btn btn-default pull-right"><span class="glyphicon glyphicon-send"></span> POST</button><br />';
 			}
 	
 		?>

		 

		  </div>

		  

		  
		  <div class="col-md-12" style="padding:5px;">
		  <!-- space between textarea and post holder -->
		  </div>

		
		  
		  <!-- Post and comment div holder -->

		  

		   <div id="post_and_comment_holder_hidden" style="display:none;" class="col-md-12">

		   </div>

		   
		  
		   <div id="post_and_comment_holder" class="col-md-12">
		   

		  <!-- posts and comments will go here -->
		 
		  
        
           </div>

           
      
      </div>

      
	
	</div>
      
      <hr>

      <footer>
      <script type="text/javascript">



      
      $(document).ready(function(){



			$('#popup').click(function(){
			$('#edit_profile_form').fadeIn('2000');
			$('.overlay').fadeIn('2000');
		});

		$('#edit_profile_cancel_btn').click(function(){
			$('#edit_profile_form').fadeOut('2000');
			$('.overlay').fadeOut('2000');
		});

		
		$('#connections_show_modal_close').click(function(){

		      $('#connections_show_modal').fadeOut('2000');
		      $('.overlay').fadeOut('2000');

		});


		// call get profile post function

		get_profile_posts();


		// realtime comment initialization

	var last_time = "<?php echo $time_last; ?>";
    var t = last_time;



    getContent(t);


    setInterval( function(){
               getContent();
            }, 1200000 );

		



		// edit profile button click

		$('#edit_profile_save_btn').click(function(){
			
			var name = $('#edit_name_profile').val();
			var b_city = $('#edit_birthcity_profile').val();
			var c_city = $('#edit_currentcity_profile').val();
			var school = $('#edit_school_profile').val();
			var college = $('#edit_university_profile').val();
			var website = $('#edit_website_profile').val();
			var about = $('#edit_about_profile').val();

			if(name.trim() == '' || b_city.trim() == '' || c_city.trim() == '' || school.trim() == '' || college.trim() == ''){

				alert("You need to fill name , birthcity, currentcity, school, college before saving");

			}else{

				var edit_profile = {
					action : "edit_profile_pressed",
					name : name,
					b_city : b_city,
					c_city : c_city,
					school : school,
					college : college,
					website : website,
					about : about

				};

				$.ajax({
            	type : "POST",
            	url : "ajax_scripts/function_edit_profile.php",
            	//beforeSend : function(){

            		//}
            	data : edit_profile,
            	cache : false,
            	success:function(r){
				
				var response = JSON.parse(r);

				if(response['code'] == 1){
					

					$('#edit_profile_form').fadeOut('2000');
					$('.overlay').fadeOut('2000');
					edit_profile_pull_info();
					alert("Your updates have been saved!");

                }else if(response['code'] == 0){
                    alert("Something is wrong!");
                }
				
            	
            	},

            	error : function(e){
            		alert(e);
            	}

            })

			}


		});

		

		
		// edit profile pull info

		function edit_profile_pull_info(){

			var pull_info = {
				action : "pull_info_edit_profile"
			};

			$.ajax({
            	type : "GET",
            	url : "ajax_scripts/function_edit_profile.php",
            	
            	data : pull_info,
            	cache : false,
            	success:function(r){
				
				var response = JSON.parse(r);

				$('#name_info').text(response.name);
				$('#b_city_info').text(response['b_city']);
				$('#c_city_info').text(response['c_city']);
				$('#school_info').text(response['school']);
				$('#college_info').text(response['college']);
				$('#website_info').text(response['website']);
				$('#about_info').text(response['about']);

				
				
            	
            	},

            	error : function(e){
            		alert(e);
            	}

            })


		}



		/* image upload */

		$(function () {
		
		
		$('#image').change(function(){
		
			var form = new FormData($('#myform')[0]);

			

			var imageupload = {
			action : "image_upload",
			form : form
			};

		
		$.ajax({
		        url: 'ajax_scripts/function_dp_upload.php',
		        type: 'POST',
				data:form,
		        cache: false,
		        contentType: false,
		        processData: false,
		        success: function(r){
					
					var result = JSON.parse(r); 	
	                
	                if(result.code == 0){
					alert(result['unsupported']);
					}else if(result.code == 1){
					
					$('#dashboard_img_profile').html(result['image']);	
					$('ul#left_side_menu li .dashboard_img_home').html(result['image']);

					}else if(result.code == 2){
						alert(result['problem']);
					}

					
					
				},
	        });
			});
			});

		



	});



    /*send connection request*/


		function connect_send(connectid){

		

		var connect = {
					action : "connect_clicked",
					to : connectid
					
				};

			$.ajax({
            	type : "POST",
            	url : "ajax_scripts/function_connect_send_request.php",
            	//beforeSend : function(){

            		//}
            	data : connect,
            	cache : false,
            	success:function(r){
				
				var response = JSON.parse(r);

				if(response['code'] == 1){
					
					$('#connect_send').hide();
					
					$('#connect_cancel').show();

                }else if(response['code'] == 0){
                    alert("Something is wrong!");
                }
				
            	
            	},

            	error : function(e){
            		alert(e);
            	}

            })	

	}

	

	/*cancel connection*/

	function cancel_connect(connectid){

		

		var cancel_connect = {
					action : "connect_cancel_clicked",
					to : connectid
					
				};

			$.ajax({
            	type : "POST",
            	url : "ajax_scripts/function_connect_cancel.php",
            	//beforeSend : function(){

            		//}
            	data : cancel_connect,
            	cache : false,
            	success:function(r){
				
				var response = JSON.parse(r);

				if(response['code'] == 1){
					
					$('#connect_cancel').hide();
					$('#connect_send').show();
					

                }else if(response['code'] == 0){
                    alert("Something is wrong!");
                }
				
            	
            	},

            	error : function(e){
            		alert(e);
            	}

            })	

	}

	

/* close connection*/

function close_connection(connectid){

var close_connect = {
          action : "connect_close_clicked",
          with_whom : connectid
          
        };

      $.ajax({
              type : "POST",
              url : "ajax_scripts/function_connect_close.php",
              //beforeSend : function(){

                //}
              data : close_connect,
              cache : false,
              success:function(r){
        
        var response = JSON.parse(r);

        if(response['code'] == 1){
          
          $('#close_connection').hide();
          $('#connect_send').show();
          

                }else if(response['code'] == 0){
                    alert("Something is wrong!");
                }
        
              
              },

              error : function(e){
                alert(e);
              }

            })  

}


// post status

    		function post_status(){


    			var post = $('#status_editor_textarea').val();

    			if(post.trim() == ''){

    			}else{

    				$('#status_editor_textarea').prop('disabled',true);

    			
    			var post_status = {
					action : "post_status_clicked",
					post : post
					};

			$.ajax({
            	type : "POST",
            	url : "ajax_scripts/function_post_status.php",
            	//beforeSend : function(){

            		//}
            	data : post_status,
            	cache : false,
            	success:function(r){
				
				var response = JSON.parse(r);

				if(response['code'] == 1){
					
					
					$('#status_editor_textarea').val(" ");
					setTimeout(function() {
     					$('#post_and_comment_holder_hidden').html(response['success']).hide().slideDown();
					}, 1500);
					

					setTimeout(function(){
					$('#post_and_comment_holder').prepend(response['success']);
					$('#post_and_comment_holder_hidden').html('');

					$('#status_editor_textarea').prop('disabled',false);

					}, 2500);



                }else if(response['code'] == 0){
                    alert("Something is wrong!");
                }
				
            	
            	},

            	error : function(e){
            		alert(e);
            	}

            })	

				}
    		}


    	// get profile posts on refreshing
    	
    	function get_profile_posts(){

    		var user_id_profile = "<?php echo $user_id_not_me; ?>"
    		var user = user_id_profile;

    		




    		

    		var get_profile_posts = {
					action : "get_profile_posts",
					user : user
				};					

			$.ajax({
            	type : "GET",
            	url : "ajax_scripts/function_get_profile_posts.php",
            	//beforeSend : function(){

            		//}
            	data : get_profile_posts,
            	cache : false,
            	success:function(r){
				
				var response = JSON.parse(r);

				if(response['code'] == 1){
					
				
				$('#post_and_comment_holder').html(response['success']);

				



                }else if(response['code'] == 0){
                    alert("Something is wrong!");
                }
				

				//$('#post_and_comment_holder').html(r);
                
				
            	
            	},

            	error : function(e){
            		alert(e);
            	}

            })


    	}	

    	// post to others profile
    	
    	function post_others(other){

    		var post = $('#status_editor_textarea').val();

    			if(post.trim() == ''){

    			}else{

    				
    			$('#status_editor_textarea').prop('disabled',true);	
    			
    			var post_others = {
					action : "post_others_clicked",
					post : post,
					other : other
					};

			$.ajax({
            	type : "POST",
            	url : "ajax_scripts/function_post_to_others.php",
            	//beforeSend : function(){

            		//}
            	data : post_others,
            	cache : false,
            	success:function(r){
				
				var response = JSON.parse(r);

				if(response['code'] == 1){
					
					
					$('#status_editor_textarea').val(" ");
					setTimeout(function() {
     					$('#post_and_comment_holder_hidden').html(response['success']).hide().slideDown();
					}, 1500);
					

					setTimeout(function(){
					$('#post_and_comment_holder').prepend(response['success']);
					$('#post_and_comment_holder_hidden').html('');

					$('#status_editor_textarea').prop('disabled',false);

					}, 2500);



                }else if(response['code'] == 0){
                    alert("Something is wrong!");
                }
				
            	
            	},

            	error : function(e){
            		alert(e);
            	}

            })	

				}

    	}

    	    // get realtime comments
   



function getContent(timestamp){
    
var queryString = {
  action : 'get_all_comments',
 'timestamp' : timestamp
  };

    $.ajax(
        {
            type: 'GET',
            url: 'ajax_scripts/function_get_all_comments.php',
            data: queryString,
            success: function(data){
                // put result data into "obj"
                var obj = JSON.parse(data);
        

            if( $('#per_comment_div'+obj.commentid).length > 0 ){


            }else{    

                
            $('#get_comments'+obj.postid).append(obj.success);
              
            for(i=0;i<3;i++) {
              $('#per_comment_div'+obj.commentid).fadeTo('slow', 0.5).fadeTo('slow', 1.0);

           }


           //$('#per_comment_div'+obj.commentid).animate({ borderTopColor: "#0e7796" }, 'fast');

         }


                getContent(obj.timestamp);
        
            }
        }
    );
}	

    	// like posts
    	
    	function like_posts(postid){
    		
    	var like_post = {
          action : "like_post",
          postid : postid
          
        };

      $.ajax({
              type : "POST",
              url : "ajax_scripts/function_like_post.php",
             /* beforeSend : function(){
              		$('#comment_loader'+postid).show();
               },*/
              data : like_post,
              cache : false,
              success:function(r){
        
                var response = JSON.parse(r);

		        if(response['code'] == 1){
		          
		          $('#rating_div_waiting'+postid).hide();
		          $('#rating_div_like_pressed'+postid).show();

		          $('#postlike'+postid).text(response['success']);

		          

		           }else if(response['code'] == 0){
		                    alert("Something is wrong!");
		            }
		        
		              
		           },

              error : function(e){
                alert(e);
              }

            })



    	}

    	// dislike posts

    	function dislike_posts(postid){

    		var dislike_post = {
          action : "dislike_post",
          postid : postid
          
        };

      $.ajax({
              type : "POST",
              url : "ajax_scripts/function_dislike_post.php",
             /* beforeSend : function(){
              		$('#comment_loader'+postid).show();
               },*/
              data : dislike_post,
              cache : false,
              success:function(r){
        
                var response = JSON.parse(r);

		        if(response['code'] == 1){
		          
		          $('#rating_div_waiting'+postid).hide();
		          $('#rating_div_dislike_pressed'+postid).show();

		          $('#postdislike'+postid).text(response['success']);

		          

		           }else if(response['code'] == 0){
		                    alert("Something is wrong!");
		            }
		        
		              
		           },

              error : function(e){
                alert(e);
              }

            })


    	}	


    	// comment post holder show and get comments

    	function comment_post_holder_show(postid){

    		// $('#comment_container'+postid).show();
    		
    		
    		

    	var get_comments = {
          action : "get_comments",
          postid : postid
          
        };

      $.ajax({
              type : "GET",
              url : "ajax_scripts/function_get_comments.php",
              beforeSend : function(){
              		$('#comment_loader'+postid).show();
               },
              data : get_comments,
              cache : false,
              success:function(r){
        
                var response = JSON.parse(r);

		        if(response['code'] == 1){
		          
		          $('#comment_loader'+postid).hide();

		          
		          $('#get_comments'+postid).html(response['success']);
		         $('#show_more_comment'+postid).fadeOut('2000');

		        

		           }else if(response['code'] == 0){
		                    alert("Something is wrong!");
		            }
		        
		              
		           },

              error : function(e){
                alert(e);
              }

            })



    		
    	}

    	// like comments
    	
    	function like_comments(commentid){


    		
    	var like_comment = {
          action : "like_comment",
          commentid : commentid
          
        };

      $.ajax({
              type : "POST",
              url : "ajax_scripts/function_like_comment.php",
             
              data : like_comment,
              cache : false,
              success:function(r){
        
                var response = JSON.parse(r);

		        if(response['code'] == 1){
		          
		          $('#rating_div_waiting_comment'+commentid).hide();
		          $('#rating_div_like_pressed_comment'+commentid).show();

		          $('#commentlike'+commentid).text(response['success']);

		          //alert(r);

		           }else if(response['code'] == 0){
		                    alert("Something is wrong!");
		            }
		        
		              
		           },

              error : function(e){
                alert(e);
              }

            })



    	}


    	// unlike 

    	function dislike_comments(commentid){
    		
    	var dislike_comment = {
          action : "dislike_comment",
          commentid : commentid
          
        };

      $.ajax({
              type : "POST",
              url : "ajax_scripts/function_dislike_comment.php",
             /* beforeSend : function(){
              		$('#comment_loader'+postid).show();
               },*/
              data : dislike_comment,
              cache : false,
              success:function(r){
        
                var response = JSON.parse(r);

		        if(response['code'] == 1){
		          
		          $('#rating_div_waiting_comment'+commentid).hide();
		          $('#rating_div_dislike_pressed_comment'+commentid).show();

		          $('#commentdislike'+commentid).text(response['success']);

		          //alert(r);

		           }else if(response['code'] == 0){
		                    alert("Something is wrong!");
		            }
		        
		              
		           },

              error : function(e){
                alert(e);
              }

            })



    	}
    	


		function open_connection_modal(pageid){
			 $('#connections_show_modal').fadeIn('2000');
		      $('.overlay').fadeIn('2000');
		      get_connection_list(pageid);

		}

		function close_connection_modal(){
			 $('#connections_show_modal').fadeOut('2000');
		      $('.overlay').fadeOut('2000');

		}



		function get_connection_list(pageid){

		var get_connections = {
          action : "connection_list_show",
          user : pageid
          
        };

      $.ajax({
              type : "GET",
              url : "ajax_scripts/function_get_connection_list.php",
              beforeSend : function(){
              		$('#connection_display_loader').show();
               },
              data : get_connections,
              cache : false,
              success:function(r){
        
                var response = JSON.parse(r);

		        if(response['code'] == 1){
		          
		          $('#connection_display_loader').hide();
		          $('#display_connections_modal').html(response['connections']);
		          

		           }else if(response['code'] == 0){
		                    alert("Something is wrong!");
		            }
		        
		              
		           },

              error : function(e){
                alert(e);
              }

            })  

		}


			
		// post comments

		function comment_click(postid){

			var comment = $('#comment_form_text'+postid).val();

			

    			if(comment.trim() == ''){

    			}else{

    				$('#comment_form_text').prop('disabled',true);

    			
    		var post_comment = {
					action : "post_comment_clicked",
					postid : postid,
					comment : comment
					};

			$.ajax({
            	type : "POST",
            	url : "ajax_scripts/function_post_comment.php",
            	//beforeSend : function(){

            		//}
            	data : post_comment,
            	cache : false,
            	success:function(r){
				
				var response = JSON.parse(r);

				if(response['code'] == 1){
					
					
					
					
					$('#get_comments'+postid).append(response['success']);
					$('#comment_count'+postid).text(" " + response['commentcount']);
					$('#comment_form_text'+postid).val(" ");

					$('#comment_form_text').prop('disabled',false);
					
					for(i=0;i<3;i++) {
    					$('#per_comment_div'+response['commentid']).fadeTo('slow', 0.5).fadeTo('slow', 1.0);
 					 }

                }else if(response['code'] == 0){
                    alert("Something is wrong!");
                }
				
            	
            	},

            	error : function(e){
            		alert(e);
            	}

            })	

				}

		}
	

	

		
      </script>
        <p>&copy; Company 2014</p>
      </footer>
    </div> <!-- /container -->
	


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

<?php

}else{
	echo "This profile does not exist";
}

}

?>