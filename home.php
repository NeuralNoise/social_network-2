<?php

session_start();

if(isset($_SESSION['id'])){
	
	$userid = $_SESSION['id'];
	//echo $userid;

	require_once("app/init.php");

	$get_username = $db->prepare("SELECT username FROM user_login WHERE id=:userid LIMIT 1");
	$get_username->execute(array("userid"=>$userid));

	$row = $get_username->fetch(PDO::FETCH_ASSOC);

	$username = $row['username'];

	
// get last timestamp of comments

  $sql_get_comments_time = $db->prepare("SELECT time FROM comments ORDER BY id DESC LIMIT 1");
  $numrow = $sql_get_comments_time->rowCount();
  $sql_get_comments_time->execute();

  $row_time = $sql_get_comments_time->fetch(PDO::FETCH_ASSOC);

    
  $time_last = $row_time['time'];


  // get last timestamp of posts

  $sql_get_posts_time = $db->prepare("SELECT time FROM posts ORDER BY id DESC LIMIT 1");
  $numrow = $sql_get_posts_time->rowCount();
  $sql_get_posts_time->execute();

  $row_time_posts = $sql_get_posts_time->fetch(PDO::FETCH_ASSOC);

    
  $time_last_posts = $row_time['time'];

	
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

    <title>Home</title>

	<?php require_once("includes/header_sidebar.php") ; ?> 




		
		
		
        <div class="col-md-7">
		
		  <div class="col-md-12" style="background:#95A5A6; padding: 10px; top:15px;"><textarea placeholder="Write whatever you want... Who's stopping? o.O" id="status_editor_textarea" class="form-control" rows="3" ></textarea><br />
			   <button type="button" style="margin-top:-10px;" onClick="post_status();" class="btn btn-default pull-right"><span class="glyphicon glyphicon-send"></span> POST</button><br />
		  		
		  </div>
		  
		  <div class="col-md-12" style="padding:5px;">
		  <!-- space between textarea and post holder -->
		  </div>

		  <div id="post_and_comment_holder_hidden" style="display:none;" class="col-md-12">

		  </div>
		
		<div id="post_and_comment_holder_home" class="col-md-12" >
		
		 <!-- posts and comments will go here -->
			
		</div>
          
       </div>
	   
	   <!-- 3rd column -->
	   
	   
        <div class="col-md-3">
          
		  
		  
        </div>
      </div>

      <hr>



      <footer>

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
    <script>
      // get profile posts on refreshing
    $(document).ready(function(){

    	get_home_posts();

    var last_time = "<?php echo $time_last; ?>";
    var t = last_time;


//    var last_time_post = "<?php echo $time_last_posts; ?>";
  //  var pt = last_time_post;

    getContent(t);

    //getPostsRealtime(pt);


    setInterval( function(){
               getContent();
            }, 1200000 );
  
    	

    	});

    	function get_home_posts(){

    		
    		
    		var get_profile_posts = {
					action : "get_home_posts"
					
				};					

			$.ajax({
            	type : "GET",
            	url : "ajax_scripts/function_get_home_posts.php",
            	//beforeSend : function(){

            		//}
            	data : get_profile_posts,
            	cache : false,
            	success:function(r){

            var response = JSON.parse(r);

            if(response['code'] == 1){
				
				

				$('#post_and_comment_holder_home').html(response['success']);

      }else if(response['code'] == 2){
        $('#post_and_comment_holder_home').html(response['no_posts']);
      }
                
				
            	
            	},

            	error : function(e){
            		alert(e);
            	}

            })


    	}

      // 

     

      
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



// get realtime posts


/*
function getPostsRealtime(timestamp){
    
var queryString = {
  action : 'get_posts_realtime',
 'timestamp' : timestamp
  };

    $.ajax({
            type: 'GET',
            url: 'ajax_scripts/function_get_home_post_realtime.php',
            data: queryString,
            success: function(data){
                // put result data into "obj"
               


            var obj = JSON.parse(data);
        

            if( $('#post_and_comment_holder_grid'+obj.postid).length > 0){


            }else{    

                
           setTimeout(function() {
              $('#post_and_comment_holder_hidden').html(obj.success).hide().slideDown();
          }, 5500);
          

          setTimeout(function(){
          
          $('#post_and_comment_holder_hidden').html('');
          $('#post_and_comment_holder_home').prepend(obj.success);

          
         

          }, 6500);
          

            }
           

                getPostsRealtime(obj.timestamp);

              }
        
            
        });
}
*/


  
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
					
					$('#post_and_comment_holder_hidden').html('');
					$('#post_and_comment_holder_home').prepend(response['success']);


          $('#empty_newsfeed'+response['uid']).hide();

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
				

        $('#comment_form_text'+postid).val(" ");
			//	var response = JSON.parse(r);

			
					
					
			/*		
					
					$('#get_comments'+postid).append(response['success']);
					$('#comment_count'+postid).text(" " + response['commentcount']);
					$('#comment_form_text'+postid).val(" ");
          $('#comment_form_text').prop('disabled',false);
					
					
					//for(i=0;i<3;i++) {
    					$('#per_comment_div'+response['commentid']).fadeTo('slow', 0.5).fadeTo('slow', 1.0);*/
 					 //}

                
				
            	
            	},

            	error : function(e){
            		alert(e);
            	}

            })	

				}

		}

    	
    	</script>
  </body>
</html>

<?php

	}elseif(!isset($_SESSION['id'])){
	
	header("Location:index.php");
	exit;
	
	}




?>
