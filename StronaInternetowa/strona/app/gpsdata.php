<?php

	

	$host = "szkolnewybory.pl:3306";
	$db_user = "zephyrior";
	$db_password = "6bny2B0&";
	$db_name = "admin_zephyrior";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
		
		$x = $_POST['x'];
		$y = $_POST['y'];
		$id  = $_POST['id'];
		
		
			$pass = password_hash("$pass", PASSWORD_DEFAULT);
			if ($polaczenie->query(			
			"UPDATE `devices` SET `x` = '$x', `y`=$y WHERE `id` = '$id'"))
			{		
				echo 1;
				
			}else{
				echo 0;
			}
		
		
		
		









