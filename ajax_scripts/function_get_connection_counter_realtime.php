<?php

if(isset($_GET['action']) && $_GET['action'] == "get_conn_counter"){



ignore_user_abort(true);
set_time_limit(0);

$flag = NULL;

require_once("../app/init.php");

while (true) {








$last_ajax_call = isset($_GET['count']) ? (int)$_GET['count'] : 0;

    // PHP caches file data, like requesting the size of a file, by default. clearstatcache() clears that cache
clearstatcache();
    // get timestamp of when file has been changed the last time

// getting realtime connection counter

      $sql_get_connection_counter = $db->prepare("SELECT connection FROM realtime_data_counter LIMIT 1");
      $sql_get_connection_counter->execute();
      $row = $sql_get_connection_counter->fetch(PDO::FETCH_ASSOC);

      $connection_counter_realtime = $row['connection'];
	


	 $last_change_in_data_file = $connection_counter_realtime;

 if ($last_change_in_data_file > $last_ajax_call) {

session_start();

if(isset($_SESSION['id'])){
$userid = $_SESSION['id'];



	$sql_get_conn_req_count = $db->prepare("SELECT * FROM connection_requests WHERE sent_to=:me");
	$sql_get_conn_req_count->execute(array("me" => $userid));

	$numrow_conn_req = $sql_get_conn_req_count->rowCount();

		
		$array = array();

		if($numrow_conn_req > 0){

			$flag = 1;
		  
		  $count = $numrow_conn_req;

		  	$array['success'] = $count;
		  	$array['code'] = $flag;
			$array['count'] = $last_change_in_data_file;
			$array['u'] = $userid;
			echo json_encode($array);


		}else{

			$count = null;

			$flag = 0;

			$array['success'] = $count;
		  	$array['code'] = $flag;
			$array['count'] = $last_change_in_data_file;
			$array['u'] = $userid;
			echo json_encode($array);
		 
		}


	
	}
	

 break;


}else {
        // wait for 1 sec (not very sexy as this blocks the PHP/Apache process, but that's how it goes)
        sleep( 1 );
        continue;
    }


}
	}


?>	