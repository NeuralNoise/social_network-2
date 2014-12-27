<?php 
session_start();
require_once("app/init.php");

if(isset($_SESSION['id'])){
    header("Location: home.php");
    }else{ 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Community</title>
	<link rel="stylesheet" href="css/style.css">
	<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
    <script src="js/jquery-1.9.1.min.js"></script>


</head>
<body>

<div class="form_holder">
<header><h4><a id="login_href" href="javascript:;">Login</a> | <a id="register_href" href="javascript:;">Register</a></h4></header>

<div id="login_form" >

<input type="text" id="email" placeholder="Email">
<input type="password" id="password" placeholder="Password"><br />
<input type="button" id="login_button" value="Login" />

</div>

<div id="register_form" style="display:none;">

<input type="text" id="name_reg" placeholder="Fullname with space">
<input type="text" id="email_reg" placeholder="Email">
<input type="password" id="password_reg" placeholder="Password">
<input type="password" id="re_password" placeholder="Password Again">
<input type="text" id="username" placeholder="Username atleast 4 alphabets">
<span id="username_status" style="position:absolute; margin-top:-25px;"></span><br />
<input type="submit" id="register_button" value="Sign Up" />

</div>

</div>

<script type="text/javascript">

// Email Validation Function

		function ValidateEmail(email) { 
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    	};


 // Username Check function
 
 $('#username').keyup(function(){

    var username = $('#username').val();

    if(username.length == 0){
        $("#username_status").html("");
    }else if(username.length < 4){
         $("#username_status").html("&#10006");
    }else{

        var username_check = {
            action : "check_username",
            username : username
        };

        $.ajax({
                type : "POST",
                url : "ajax_scripts/function_checkusername.php",
                //beforeSend : function(){

                    //}
                data : username_check,
                cache : false,
                success:function(r){

                    $('#username_status').html(r); 

                }

            })


    }

 });       

// Login Button Press Function



	$('#login_button').click(function(){

       // event.preventDefault();

		var email_login = $('#email').val();
		var pas_login = $('#password').val();

	if(email_login != '' || pas_login != ''){    // if email and password is empty

		if (!ValidateEmail(email_login)) {      // Email Validation
            alert("Invalid email address.");
        }
        else if(pas_login == ''){
            alert("Fill in the password field!");
        }else {

            
            var login = {
            	action : "Login_pressed",
            	email : email_login,
            	password : pas_login
            };

            $.ajax({
            	type : "POST",
            	url : "ajax_scripts/function_login.php",
            	//beforeSend : function(){

            		//}
            	data : login,
            	cache : false,
            	success:function(r){

            		var response = JSON.parse(r);

            		if(response['log_code'] == 1){
            			alert(response['not_matched']);
            		}else if(response['log_code'] == 2){
            			window.location.href = "home.php";
                        //alert(response['id']);
            		} 

            	}

            })
        }


	}else{
		alert("Fill in all fields!");
	}

	});

	$('#register_button').click(function(){

       

        var name_reg = $('#name_reg').val();
		var email_reg = $('#email_reg').val();
		var pas_reg = $('#password_reg').val();
		var re_pas = $('#re_password').val();
		var username = $('#username').val();

	if(email_reg != '' || pas_reg != '' || re_pas != '' || username != ''){    // if email and password is empty

		if (!ValidateEmail(email_reg)) {      // Email Validation
            alert("Invalid email address.");
        }
        else 
        {

        if(name_reg ==''){
            alert("Fill in the Name field!");
        }else{    

        if(pas_reg == '' || re_pas == ''){
            alert("Fill in the password fields");
        } 
        else
        {   

        if(pas_reg != re_pas){
        	alert("Your passwords did not match.");

        }else if(username.length < 4){
            alert("Your username must be atleast of 4 characters long!");
        }else{	
            
            var register = {
            	action : "Register_pressed",
                name : name_reg,
            	email : email_reg,
            	password : pas_reg,
                username : username
            };

            $.ajax({
            	type : "POST",
            	url : "ajax_scripts/function_register.php",
            	//beforeSend : function(){

            		//}
            	data : register,
            	cache : false,
            	success:function(r){
				
				var response = JSON.parse(r);

				if(response['res_code'] == 1){
					alert(response['email_exists']);
                }else if(response['res_code'] == 2){
                    alert(response['username_exists']);
                }else if(response['res_code'] == 3){
					window.location.href = "home.php";
				}
				
            	//alert(r);
            	},

            	error : function(e){
            		alert(e);
            	}

            })
        }

    }
}

    }

	}else{
		alert("Fill in all fields!");
	}

	});
</script>

<script type="text/javascript">
	$('#login_href').click(function(){
		$('#register_form').hide();
		$('#login_form').show();

	});

	$('#register_href').click(function(){
		$('#login_form').hide();
		$('#register_form').show();

	});
</script>



</body>
</html>
<?php  } ?>