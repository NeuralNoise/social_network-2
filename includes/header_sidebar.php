<?php

  require_once("app/init.php");

      // getting realtime connection counter

      $sql_get_connection_counter = $db->prepare("SELECT connection FROM realtime_data_counter LIMIT 1");
      $sql_get_connection_counter->execute();
      $row = $sql_get_connection_counter->fetch(PDO::FETCH_ASSOC);

      $connection_counter_realtime = $row['connection'];

?>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="http://getbootstrap.com/examples/jumbotron/jumbotron.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <script src="http://code.jquery.com/jquery-1.11.1.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>

    var snd = new Audio('notification.mp3');

function accept_connection(connectid){


var accept_connect = {
          action : "connect_accept_clicked",
          with_whom : connectid
          
        };

      $.ajax({
              type : "POST",
              url : "ajax_scripts/function_connect_accept.php",
              //beforeSend : function(){

                //}
              data : accept_connect,
              cache : false,
              success:function(r){
        
        var response = JSON.parse(r);

        if(response['code'] == 1){
          
          $('#connection_li'+connectid).fadeOut('3000');
          get_connection_request_count();

                }else if(response['code'] == 0){
                    alert("Something is wrong!");
                }else if(response['code'] == 2){
                  alert("Something is wrong!");
                }
        
              
              },

              error : function(e){
                alert(e);
              }

            })  

}



function reject_connection(connectid){


var reject_connect = {
          action : "connect_reject_clicked",
          with_whom : connectid
          
        };

      $.ajax({
              type : "POST",
              url : "ajax_scripts/function_connect_reject.php",
              //beforeSend : function(){

                //}
              data : reject_connect,
              cache : false,
              success:function(r){
        
        var response = JSON.parse(r);

        if(response['code'] == 1){
          
          $('#connection_li'+connectid).fadeOut('3000');
          get_connection_request_count();

                }else if(response['code'] == 0){
                    alert("Something is wrong!");
                }
        
              
              },

              error : function(e){
                alert(e);
              }

            })  

}




// open the connection modal

$(document).ready(function(){

  var conn_counter_real = "<?php echo $connection_counter_realtime; ?>";
  var cc = conn_counter_real;

  getConnectionRealtime(cc);

   setInterval( function(){
               getConnectionRealtime();
            }, 1200000 );

  get_connection_request_count();

$('#conn_req_tab').click(function(){

      $('#connnection_requests_modal').fadeIn('2000');
      $('.overlay').fadeIn('2000');

      get_connection_request();
      get_connection_request_count();

});

$('#connnection_requests_modal #conn_req_modal_close').click(function(){

      $('#connnection_requests_modal').fadeOut('2000');
      $('.overlay').fadeOut('2000');

});



});

function open_connection_modal(){
       $('#connections_show_modal_sidebar').fadeIn('2000');
          $('.overlay').fadeIn('2000');
          get_connection_list_own();

    }

    function close_connection_modal(){
       $('#connections_show_modal_sidebar').fadeOut('2000');
          $('.overlay').fadeOut('2000');

    }

    // get connection request realtime

    function getConnectionRealtime(count){
    
    var get_conn = {
      action : 'get_conn_counter',
      'count' : count
    };

    $.ajax({
            type: 'GET',
            url: 'ajax_scripts/function_get_connection_counter_realtime.php',
            data: get_conn,
            success: function(data){
                // put result data into "obj"
            var obj = JSON.parse(data);
        
            // if(obj.code == 1){
               
              // console.log(obj.success);
              $('#conn_req_count').html(obj.success);


             $('#conn_req_count').css({

                  /*'-webkit-box-shadow' : '0 0 20px blue', 
                   '-moz-box-shadow' : '0 0 20px blue', 
                     'box-shadow' : '0 0 20px blue',*/
                     'font-weight' : 'bold',
                     'color' : 'blue',
                     'font-size' : '13px'
                    

                 });

             for(i=0;i<10;i++) {
              $('#conn_req_count').fadeTo('slow', 0).fadeTo('slow', 10);
             // $('#conn_req_tab').fadeTo('slow', 0).fadeTo('slow', 10);
              //snd.play();

           }

              /*}else if(obj.code == 0){

               $('#conn_req_count').html(obj.success);

                $('#conn_req_tab_button').css({

                  '-webkit-box-shadow' : 'none', 
                   '-moz-box-shadow' : 'none', 
                     'box-shadow' : 'none'
                    

                 });


              }*/
              
               
                getConnectionRealtime(obj.count);

        
            },
            error: function (xhr, ajaxOptions, thrownError) {
               console.log(xhr.status);
               console.log(thrownError);
           }


        });
}


    function get_connection_request(){

      var get_connection_request = {

        action : "connection_request_show"

      };

      $.ajax({
              type : "GET",
              url : "ajax_scripts/function_get_connection_request_modal.php",
             /* beforeSend : function(){
                  $('#connection_display_loader_sidebar').show();
               },*/
              data : get_connection_request,
              cache : false,
              success:function(r){
        
              var response = JSON.parse(r);

            if(response['code'] == 1){
              
              $('.conn_req_ul').html(response['connection_requests']);
              

               }else if(response['code'] == 0){
                        alert("Something is wrong!");
                }
            
                  
               },

              error : function(e){
                alert(e);
              }

            }) 


    }

    // get connection request counter.

function get_connection_request_count(){


var conn_req_count = {

action : "get_conn_req_count"

};

$.ajax({
              type : "GET",
              url : "ajax_scripts/function_get_connection_request_count.php",
             /* beforeSend : function(){
                  $('#connection_display_loader_sidebar').show();
               },*/
              data : conn_req_count,
              cache : false,
              success:function(r){
        
              var response = JSON.parse(r);

            if(response['code'] == 1){
              
              $('#conn_req_count').html(response['conn_req_count']);
              

               }else if(response['code'] == 0){
                        alert("Something is wrong!");
                }
            
                  
               },

              error : function(e){
                alert(e);
              }

            }) 


}



    function get_connection_list_own(){

    var get_connections = {
          action : "connection_list_show_own"
         
          
        };

      $.ajax({
              type : "GET",
              url : "ajax_scripts/function_get_connection_list_own.php",
              beforeSend : function(){
                  $('#connection_display_loader_sidebar').show();
               },
              data : get_connections,
              cache : false,
              success:function(r){
        
                var response = JSON.parse(r);

            if(response['code'] == 1){
              
              $('#connection_display_loader_sidebar').hide();
              $('#display_connections_modal_sidebar').html(response['connections']);
              

               }else if(response['code'] == 0){
                        alert("Something is wrong!");
                }
            
                  
               },

              error : function(e){
                alert(e);
              }

            })  

    }



</script>



  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Community</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          
        </div><!--/.navbar-collapse -->
      </div>
    </nav>


    <div class="overlay" style="opacity:0.5; display:none;  background:#000; width:100%; height:100%; z-index:1; top: 0; left:0; position:fixed; "></div>

    
    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-2" style="background:#ECECEC; top:10px;">
          <ul class="nav nav-stacked span2" id="left_side_menu" style="padding:10px;">



            <?php

    



      // getting my username

      $sql_get_my_username = $db->prepare("SELECT username FROM user_login WHERE id=:userid");
      $sql_get_my_username->execute(array("userid" => $userid));

      $row_username = $sql_get_my_username->fetch(PDO::FETCH_ASSOC);
      $my_username = $row_username['username'];

      // getting my home image sidebar

      $sql_get_image = $db->prepare("SELECT avatar FROM user_profile WHERE user_id=:user");
      $sql_get_image->execute(array("user" => $userid));

      $row = $sql_get_image->fetch(PDO::FETCH_ASSOC);

      $dp = $row['avatar'];


      

     




                        // getting user connections

                  $connection_list = NULL;

                  // get connection count only

                  $sql_get_user_connection_count = $db->prepare("SELECT id FROM connections WHERE person_1=:me OR person_2=:me");
                  $sql_get_user_connection_count->execute(array("me" => $userid));

                  $numrow_list = $sql_get_user_connection_count->rowCount();

                  // now start the connection query

                 

      

      $time = time();

      if(empty($dp)){
        echo '<li><a href="profile.php?u='.$my_username.'"><div class="dashboard_img_home" style="width:80px; height:80px;"></div></a></li>';
      }else{
        echo '<li><a href="profile.php?u='.$my_username.'"><div class="dashboard_img_home" style="width:80px; height:80px;"><img style="height:80px; width:80px" src="userdata/'.$userid.'/dp/'.$dp.'?t='.$time.'"></div></a></li>';
      }

    ?>


    <div id="connections_show_modal_sidebar">
      <div class="text-center"><a href="javascript:;" onClick="close_connection_modal();" style="margin-top:70px;">Close</a></div>      
            
      <h5 class="text-center" style="margin-top:20px;">Connections(<?php echo $numrow_list; ?>)</h5>
      <div class="text-center"><input type="text" style="padding:5px;" id="connection_search_field" placeholder="Search..."/></div>
      <br />

      <div class="text-center" style="margin-top:50px; display:none;" id="connection_display_loader_sidebar">Loading......</div>

      <div class="col-md-12" id="display_connections_modal_sidebar" style="padding:5px; left:15px; ">
        <!--<div class="col-md-3 each-friend-display"><img src="http://displaypix.com/images/items/itm_2013-01-08_19-55-00_6.jpg" style="width:70px; height:70px;" /></div>
        <div class="col-md-3 each-friend-display"><img src="http://displaypix.com/images/items/itm_2013-01-08_19-55-00_6.jpg" style="width:70px; height:70px;" /></div>
        <div class="col-md-3 each-friend-display"><img src="http://displaypix.com/images/items/itm_2013-01-08_19-55-00_6.jpg" style="width:70px; height:70px;" /></div>
        <div class="col-md-3 each-friend-display"><img src="http://displaypix.com/images/items/itm_2013-01-08_19-55-00_6.jpg" style="width:70px; height:70px;" /></div>-->
        
        
      </div>

    </div>




            <div id="connnection_requests_modal">
      
            <a href="javascript:;" id="conn_req_modal_close" style="margin-top:50px;">Close</a>      
            
            
            <ul class="conn_req_ul">

            

            </ul>

            </div>



		  
		  
      <p><a style="text-decoration:none;" href="home.php"> <button type="button" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-home"></span> Home </button></a></p>
        
		  <p><a style="text-decoration:none;" href="#"> <button type="button" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-user"></span> Account</button></a></p>
		  <p><a style="text-decoration:none;" href="#"> <button type="button" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-lock"></span>  Security</button></a></p>
		 <p><a style="text-decoration:none;" href="javascript:;" onClick="open_connection_modal();"> <button type="button" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-tree-deciduous"></span> Connections <?php echo $numrow_list; ?></button></a></p>
		  <p><a style="text-decoration:none;" href="#"> <button type="button" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-envelope"> Messages</button></a></p>
		  <p><a style="text-decoration:none;" href="javascript:;" id="conn_req_tab"> <button type="button" id="conn_req_tab_button" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-random" ></span>  Requests    <span id="conn_req_count"></span></button></a></p>
		   <p><a style="text-decoration:none;" href="#"> <button type="button" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-tasks"></span> Notifications</button></a></p>
		   <p><a style="text-decoration:none;" href="functions/logout.php"> <button type="button" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-log-out"></span>  Logout </button></a></p>
		</ul>
        </div>