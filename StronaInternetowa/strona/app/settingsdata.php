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

		$color = $_POST['color'];
		$mode = $_POST['mode'];
		$pass = $_POST['pass'];
		$id  = $_POST['id'];
		
		if(strlen($pass) > 0)
		{
			$pass = password_hash("$pass", PASSWORD_DEFAULT);
			if ($polaczenie->query(			
			"UPDATE `devices` SET `color` = '$color', `mode`=$mode, `password`='$pass' WHERE `id` = '$id'"))
			{		
				echo 1;
				
			}else{
				echo 0;
			}
		}else
			{
				if ($polaczenie->query(			
				"UPDATE `devices` SET `color` = '$color', `mode`=$mode WHERE `id` = '$id'"))
				{		
					echo 1;
					
				}else{
					echo 0;
				}
			}
		
		
		









