<?php

	session_start();
	date_default_timezone_set("Europe/Warsaw");
	if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
	{
		header('Location: log_form.php');
		exit();
	}

	require_once "connect.php";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		
		$_SESSION['saved_login'] = $login;

		$login = htmlentities($login, ENT_QUOTES, "UTF-8");

		
			if ($rezultat = @$polaczenie->query(
			sprintf("SELECT * FROM `devices` WHERE id='%s'",
			mysqli_real_escape_string($polaczenie,$login))))
			{
				$wiersz = $rezultat->fetch_assoc();
				$ilu_userow = $rezultat->num_rows;
				if (password_verify($haslo, $wiersz['password']) && $ilu_userow == 1)
				{
					$_SESSION['zalogowany'] = true;
					$_SESSION['id'] = $wiersz['id'];
					

					unset($_SESSION['blad']);
					header('Location: desktop.php');
					$rezultat->free_result();
				}else
					{
						$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy numer lub hasło!</span>';
						header('Location: log_form.php');
					}

			}else{
					$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy numer lub hasło!</span>';
					header('Location: log_form.php');

				}

		

		$polaczenie->close();
	}

?>
