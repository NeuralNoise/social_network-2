<?php
session_start();

if(isset($_GET['action']) && $_GET['action'] == "connection_request_show" && isset($_SESSION['id'])){

$flag = NULL;

$userid = $_SESSION['id'];



require_once("../app/init.php");



$connection_list = NULL;

$request_div = NULL;

      $sql_get_conn_req = $db->prepare("SELECT * FROM connection_requests WHERE sent_to=:me ");
      $sql_get_conn_req->execute(array("me" => $userid));

      $numrow_conn_req = $sql_get_conn_req->rowCount();

      if($numrow_conn_req < 1){

      	$request_div = "You don't have any requests at this moment!";
      }else{

        while($row = $sql_get_conn_req->fetch(PDO::FETCH_ASSOC)){

        $from_whom = $row['sent_from'];

        
        // getting username


        $sql_get_conn_req_username = $db->prepare("SELECT username FROM user_login WHERE id=:id ");
        $sql_get_conn_req_username->execute(array("id" => $from_whom));

        while($row_username = $sql_get_conn_req_username->fetch(PDO::FETCH_ASSOC)) {
          
          $req_username = $row_username['username'];


        }

        


        $sql_get_name_from_whom = $db->prepare("SELECT name, avatar FROM user_profile WHERE user_id=:id");
        $sql_get_name_from_whom->execute(array("id" => $from_whom));

        while($name_row = $sql_get_name_from_whom->fetch(PDO::FETCH_ASSOC)){

          $name_from_whom = $name_row['name'];
          $avatar_from_whom = $name_row['avatar'];

          if(!empty($avatar_from_whom)){
            // dp of from whom
            $dp_from_whom_conn = '<div id="img_conn_req"><img style="width:100%; height:100%;" src="userdata/'.$from_whom.'/dp/'.$avatar_from_whom.'" /></div>';
          }else{
            $dp_from_whom_conn = '<div id="img_conn_req"></div>';
          }

          $request_div .= '<li id="connection_li'.$from_whom.'"><a href="profile.php?u='.$req_username.'" target="_tab">'.$dp_from_whom_conn.'</a><a target="_blank" href="profile.php?u='.$req_username.'" > '.$name_from_whom.'</a><a href="javascript:;" onClick="accept_connection('.$from_whom.'); "> &nbsp;Accept</a> | <a href="javascript:;" onClick="reject_connection('.$from_whom.'); ">Reject</a></li>';

          

        }


      }

      }


	
if($sql_get_conn_req){

	$flag = 1;
	$array = array();
	$array['code'] = $flag;
	$array['connection_requests'] = $request_div;
	echo json_encode($array);

}else{

	$flag = 0;
	
	$array['code'] = $flag;
	echo json_encode($array);

}



}

?>