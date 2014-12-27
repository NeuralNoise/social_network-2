
<?php


switch(1)
	{
	
		case ($diff <= 60):
		$count = $diff;
		
		if($count == 0 || $count == 1)
			$suffix = "second";
		else 
			$suffix = "seconds";
		break;
	
		
		case ($diff > 60 && $diff <= 3600):
		$count = floor($diff/60);
		
		 if($count == 1)
			$suffix = "minute";
		else 
			$suffix = "minutes";
		break;
		
		
		case ($diff > 3600 && $diff <= 86400):
		$count = floor($diff/3600);
		
		 if($count == 1)
			$suffix = "hour";
		else 
			$suffix = "hours";
		break;
		
		
		case ($diff > 86400 && $diff <= 2629743):
		$count = floor($diff/86400);
		
		 if($count == 1)
			$suffix = "day";
		else 
			$suffix = "days";
		break;
		
		
		case ($diff > 2629743 && $diff <= 31556926):
		$count = floor($diff/2629743);
		
		 if($count == 1)
			$suffix = "month";
		else 
			$suffix = "months";
		break;
		
		
		case ($diff > 31556926):
		$count = floor($diff/31556926);
		
		 if($count == 1)
			$suffix = "year";
		else 
			$suffix = "years";
		break;
		
		
	}	
	
	?>