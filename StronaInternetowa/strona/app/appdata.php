<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$host = "szkolnewybory.pl";
		$db_user = "zephyrior";
		$db_password = "6bny2B0&";
		$db_name = "admin_zephyrior";

		$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

		if ($polaczenie->connect_errno!=0)
		{
			echo "Error: ".$polaczenie->connect_errno;
		}
		else
		{
			
			$login = $_POST['id'];
			$haslo = $_POST['password'];

			$login = htmlentities($login, ENT_QUOTES, "UTF-8");

			
				if ($rezultat = $polaczenie->query(
				sprintf("SELECT * FROM `devices` WHERE `id`='%s'",
				mysqli_real_escape_string($polaczenie,$login))))
				{
					$wiersz = $rezultat->fetch_assoc();
					$ilu_userow = $rezultat->num_rows;
					if (password_verify($haslo, $wiersz['password']) && $ilu_userow == 1)
					{
						echo "1";
						
						$rezultat->free_result();
					}else
						{
							echo "0";
						}

				}else
					{
						echo "0";
					}

			

			$polaczenie->close();
		}
	}
	else
		{
			echo "NO POST.";
		}
?>
