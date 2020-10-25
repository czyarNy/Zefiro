<?php

	$id  = $_POST['id']; 

	$host = "szkolnewybory.pl:3306";
	$db_user = "zephyrior";
	$db_password = "6bny2B0&";
	$db_name = "admin_zephyrior";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}

		$type = $_POST['type'];
		$time = $_POST['time'];
		$date = new DateTime();
 
		
		
		$interval = new DateInterval("P".$time."D");
		$date->sub($interval);
		$date2 = $date->format("Y-m-d H:i:s");
		
		
			if($time == 1)
			{
				if ($rezultat = $polaczenie->query(			
				"SELECT * FROM `hours` WHERE `id` = '$id' AND `date` > '$date2' ORDER by `date` ASC"))
				{		
					$num_rows = $rezultat->num_rows;
					$data = '';
					
						
					for($i=0; $i < $num_rows; $i++)
					{
						$row = $rezultat->fetch_assoc();
						if($i==$num_rows-1)
							{
								$data .= $row["$type"];
							}else
								{
									$data .= $row["$type"].', ';
								}
					}					
				}
			}else
				{
					
					if ($rezultat = $polaczenie->query(			
					"SELECT * FROM `days` WHERE `id` = '$id' AND `date` > '$date2' ORDER by `date` ASC"))
					{		
						$num_rows = $rezultat->num_rows;
						$data = '';
						
						
						for($i=0; $i < $num_rows; $i++)
						{
							$row = $rezultat->fetch_assoc();
							if($i==$num_rows-1)
							{
								$data .= $row["$type"];
							}else
								{
									$data .= $row["$type"].', ';
								}
							
							
						
						}					
					}
				}
	
		
		
		
		$ret = '{'.$data.'}';

			echo $ret;
		
	











