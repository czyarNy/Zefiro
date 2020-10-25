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

		if ($rezultat = $polaczenie->query(			
		"SELECT * FROM `days` WHERE `id` = '$id' ORDER by `date` DESC LIMIT 1"))
		{		
			$num_rows = $rezultat->num_rows;						
			$row = $rezultat->fetch_assoc();
			
			$data = $row["pm1"].', '.$row["pm25"].', '.$row["pm10"].', '.$row["temp"].', '.$row["humidity"].', '.$row["pressure"];
						
		}					
					
				
	
		
		
		
		$ret = '{'.$data.'}';

			echo $ret;
		
	











